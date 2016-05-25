<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../../FacebookTestCase.php';

class Unit_Core_oeFacebookUserTest extends FacebookTestCase
{
    protected $_aShops = array(1);
    protected $_aUsers = array();

    protected $_aDynPaymentFields = array('kktype'   => 'Test Bank',
                                          'kknumber' => '123456',
                                          'kkmonth'  => '123456',
                                          'kkyear'   => 'Test User',
                                          'kkname'   => 'Test Name',
                                          'kkpruef'  => '123456');

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown()
    {
        $oUser = oxNew('oxUser');
        if ($oUser->loadActiveUser()) {
            $oUser->logout();
        }
        $oUser->setAdminMode(null);
        oxRegistry::getSession()->deleteVariable('deladrid');

        $this->getSession()->setVariable('usr', null);
        $this->getSession()->setVariable('auth', null);

        // resetting globally admin mode

        // removing email wrapper module
        oxRemClassModule('Unit\Application\Model\UserTest_oxNewsSubscribed');
        oxRemClassModule('Unit\Application\Model\UserTest_oxUtilsServerHelper');
        oxRemClassModule('Unit\Application\Model\UserTest_oxUtilsServerHelper2');
        oxRemClassModule('Unit\Application\Model\oxEmailHelper');

        // removing users
        foreach ($this->_aUsers as $sUserId => $oUser) {
            /** @var oxUser $oUser */
            $oUser->delete($sUserId);
            unset($this->_aUsers[$sUserId]);
        }

        // restore database
        $oDbRestore = self::_getDbRestore();
        $oDbRestore->restoreDB();

        parent::tearDown();
    }

    /**
     * Creates user.
     *
     * @param string $sUserName
     * @param int    $iActive
     * @param string $sRights either user or malladmin
     * @param int    $sShopId User shop ID
     *
     * @return oxUser
     */
    protected function createUser($sUserName = null, $iActive = 1, $sRights = 'user', $sShopId = null)
    {
        $oUtils = oxRegistry::getUtils();
        $oDb = $this->getDb();

        $iLastNr = count($this->_aUsers) + 1;

        if (is_null($sShopId)) {
            $sShopId = $this->getConfig()->getShopId();
        }

        $oUser = oxNew('oxUser');
        $oUser->oxuser__oxshopid = new oxField($sShopId, oxField::T_RAW);
        $oUser->oxuser__oxactive = new oxField($iActive, oxField::T_RAW);
        $oUser->oxuser__oxrights = new oxField($sRights, oxField::T_RAW);

        // setting name
        $sUserName = $sUserName ? $sUserName : 'test' . $iLastNr . '@oxid-esales.com';
        $oUser->oxuser__oxusername = new oxField($sUserName, oxField::T_RAW);
        $oUser->oxuser__oxpassword = new oxField(crc32($sUserName), oxField::T_RAW);
        $oUser->oxuser__oxcountryid = new oxField("testCountry", oxField::T_RAW);
        $oUser->save();

        $sUserId = $oUser->getId();
        $sId = oxUtilsObject::getInstance()->generateUID();

        // loading user groups
        $sGroupId = $oDb->getOne('select oxid from oxgroups order by rand() ');
        $sQ = 'insert into oxobject2group (oxid,oxshopid,oxobjectid,oxgroupsid) values ( "' . $sUserId . '", "' . $sShopId . '", "' . $sUserId . '", "' . $sGroupId . '" )';
        $oDb->Execute($sQ);

        $sQ = 'insert into oxorder ( oxid, oxshopid, oxuserid, oxorderdate ) values ( "' . $sId . '", "' . $sShopId . '", "' . $sUserId . '", "' . date('Y-m-d  H:i:s', time() + 3600) . '" ) ';
        $oDb->Execute($sQ);

        // adding article to order
        $sArticleID = $oDb->getOne('select oxid from oxarticles order by rand() ');
        $sQ = 'insert into oxorderarticles ( oxid, oxorderid, oxamount, oxartid, oxartnum ) values ( "' . $sId . '", "' . $sId . '", 1, "' . $sArticleID . '", "' . $sArticleID . '" ) ';
        $oDb->Execute($sQ);

        // adding article to basket
        $sQ = 'insert into oxuserbaskets ( oxid, oxuserid, oxtitle ) values ( "' . $sUserId . '", "' . $sUserId . '", "oxtest" ) ';
        $oDb->Execute($sQ);

        $sArticleID = $oDb->getOne('select oxid from oxarticles order by rand() ');
        $sQ = 'insert into oxuserbasketitems ( oxid, oxbasketid, oxartid, oxamount ) values ( "' . $sUserId . '", "' . $sUserId . '", "' . $sArticleID . '", "1" ) ';
        $oDb->Execute($sQ);

        // creating test address
        $sCountryId = $oDb->getOne('select oxid from oxcountry where oxactive = "1"');
        $sQ = 'insert into oxaddress ( oxid, oxuserid, oxaddressuserid, oxcountryid ) values ( "test_user' . $iLastNr . '", "' . $sUserId . '", "' . $sUserId . '", "' . $sCountryId . '" ) ';
        $oDb->Execute($sQ);

        // creating test executed user payment
        $aDynValue = $this->_aDynPaymentFields;
        $oPayment = oxNew('oxPayment');
        $oPayment->load('oxidcreditcard');
        $oPayment->setDynValues($oUtils->assignValuesFromText($oPayment->oxpayments__oxvaldesc->value, true, true, true));

        $aDynValues = $oPayment->getDynValues();
        while (list($key, $oVal) = each($aDynValues)) {
            $oVal = new oxField($aDynValue[$oVal->name], oxField::T_RAW);
            $oPayment->setDynValue($key, $oVal);
            $aDynVal[$oVal->name] = $oVal->value;
        }

        $sDynValues = '';
        if (isset($aDynVal)) {
            $sDynValues = $oUtils->assignValuesToText($aDynVal);
        }

        $sQ = 'insert into oxuserpayments ( oxid, oxuserid, oxpaymentsid, oxvalue ) values ( "' . $sId . '", "' . $sUserId . '", "oxidcreditcard", "' . $sDynValues . '" ) ';
        $oDb->Execute($sQ);

        $this->_aUsers[$sUserId] = $oUser;

        return $oUser;
    }

    /**
     * Testing saving updating user facebook ID if user is connete via Facebook connect
     */
    public function testSaveUpdatesFacebookId()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return true;}");
        oxTestModules::addFunction('oeFacebook', "getUser", "{return 123456;}");
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', false);

        // FB connect is disabled so no value should be saved
        $oUser = $this->createUser();
        $sUserId = $oUser->getId();
        $oUser->save();

        $this->assertEquals(0, $this->getDb()->getOne("select oefacebookfbid from oxuser where oxid='$sUserId' "));

        // FB connect is eanbled, FB ID is expected
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);
        $oUser->save();

        $this->assertEquals(123456, $this->getDb()->getOne("select oefacebookfbid from oxuser where oxid='$sUserId' "));
    }

    /**
     * Testing saving updating user facebook ID - user is not connected via Facebook
     */
    public function testSaveUpdatesFacebookId_notConnected()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return false;}");
        oxTestModules::addFunction('oeFacebook', "getUser", "{return 123456;}");
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);

        // FB connect is disabled so no value should be saved
        $oUser = $this->createUser();
        $sUserId = $oUser->getId();
        $oUser->save();

        $this->assertEquals(0, $this->getDb()->getOne("select oefacebookfbid from oxuser where oxid='$sUserId' "));
    }

    /**
     * oxuser::laodActiveUser() test loading active user via facebook connect
     * when user logged in to fb and user exists in db
     */
    public function testLoadActiveUser_FacebookConnectLoggedIn()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return true;}");
        oxTestModules::addFunction('oeFacebook', "getUser", "{return 123456;}");
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);

        $oUser = $this->createUser();
        $sUserId = $oUser->getId();

        // Saving user Facebook ID
        $this->getDb()->execute("update oxuser set oxactive = 1, oefacebookfbid='123456' where oxid='$sUserId' ");

        $testUser = $this->getMock('oxuser', array('isAdmin'));
        $testUser->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertTrue($testUser->loadActiveUser());
        $this->assertEquals($sUserId, $testUser->getId());
    }

    /**
     * oxuser::laodActiveUser() test loading active user via facebook connect
     * when user logged in to fb and no user exists in db
     */
    public function testLoadActiveUser_FacebookConnectLoggedInNoUser()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return true;}");
        oxTestModules::addFunction('oeFacebook', "getUser", "{return 123456;}");
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);

        $oUser = $this->createUser();
        $sUserId = $oUser->getId();

        // Saving user Facebook ID
        $this->getDb()->execute("update oxuser set oxactive = 1, oefacebookfbid='' where oxid='$sUserId' ");

        $testUser = $this->getMock('oxuser', array('isAdmin'));
        $testUser->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertFalse($testUser->loadActiveUser());
    }

    /**
     * oxuser::laodActiveUser() test loading active user via facebook connect
     * when user is not connected to fb, but exists in db
     */
    public function testLoadActiveUser_FacebookConnectNotLoggedIn()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return false;}");
        oxTestModules::addFunction('oeFacebook', "getUser", "{return 123456;}");
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);

        $oUser = $this->createUser();
        $sUserId = $oUser->getId();

        // Saving user Facebook ID
        $this->getDb()->execute("update oxuser set oxactive = 1, oefacebookfbid='123456' where oxid='$sUserId' ");

        $testUser = $this->getMock('oxuser', array('isAdmin'));
        $testUser->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertFalse($testUser->loadActiveUser());
    }

    /**
     * oxuser::laodActiveUser() test loading active user via facebook connect
     * when facebook connect is disabled
     */
    public function testLoadActiveUser_FacebookConnectDisabled()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return true;}");
        oxTestModules::addFunction('oeFacebook', "getUser", "{return 123456;}");
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', false);

        $oUser = $this->createUser();
        $sUserId = $oUser->getId();

        // Saving user Facebook ID
        $this->getDb()->execute("update oxuser set oxactive = 1, oefacebookfbid='123456' where oxid='$sUserId' ");

        $testUser = $this->getMock('oxuser', array('isAdmin'));
        $testUser->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertFalse($testUser->loadActiveUser());
    }

}
