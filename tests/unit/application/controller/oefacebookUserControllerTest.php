<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../../FacebookTestCase.php';

/**
 * Testing user class
 */
class oeFacebookUserControllerTest extends FacebookTestCase
{
    protected $_oUser = array();

    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp()
    {
        parent::setUp();

        // setting up user
        $this->setupUsers();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown()
    {
        if ($this->_oUser) {
            $this->_oUser->delete();
        }

        parent::tearDown();

    }

    /**
     * Setting up users
     */
    protected function setupUsers()
    {
        $myDB = oxDb::getDB();
        $sTable = getViewName('oxuser');
        $iLastCustNr = ( int ) $myDB->getOne('select max( oxcustnr ) from ' . $sTable) + 1;
        $this->_oUser = oxNew('oxuser');
        $this->_oUser->oxuser__oxshopid = new oxField($this->getConfig()->getShopId(), oxField::T_RAW);
        $this->_oUser->oxuser__oxactive = new oxField(1, oxField::T_RAW);
        $this->_oUser->oxuser__oxrights = new oxField('user', oxField::T_RAW);
        $this->_oUser->oxuser__oxusername = new oxField('test@oxid-esales.com', oxField::T_RAW);
        $this->_oUser->oxuser__oxpassword = new oxField(crc32('Test@oxid-esales.com'), oxField::T_RAW);
        $this->_oUser->oxuser__oxcustnr = new oxField($iLastCustNr + 1, oxField::T_RAW);
        $this->_oUser->oxuser__oxcountryid = new oxField("testCountry", oxField::T_RAW);
        $this->_oUser->save();

        $sQ = 'insert into oxaddress ( oxid, oxuserid, oxaddressuserid, oxcountryid ) values ( "test_user", "' . $this->_oUser->getId() . '", "' . $this->_oUser->getId() . '", "testCountry" ) ';
        $myDB->Execute($sQ);
    }

    /**
     * Testing if render calls function for filling user data taken
     * from Facebook account - FB connect is disabled
     *
     * @return null
     */
    public function testRenderFillsFormWithFbUserData_FbConnectDisabled()
    {
        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('oeFacebookShowConnect', false);

        $oView = $this->getMock('oeFacebookUserController', array("_fillFormWithFacebookData"));
        $oView->expects($this->never())->method('_fillFormWithFacebookData');
        $oView->render();
    }

    /**
     * Testing if render calls function for filling user data taken
     * from Facebook account - FB connect is enabled and no user
     *
     * @return null
     */
    public function testRenderFillsFormWithFbUserData_FbConnectEnabledNoUser()
    {
        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('oeFacebookShowConnect', true);

        $oView = $this->getMock('oeFacebookUserController', array("_fillFormWithFacebookData", "getUser"));
        $oView->expects($this->any())->method('getUser')->will($this->returnValue(null));
        $oView->expects($this->once())->method('_fillFormWithFacebookData');
        $oView->render();
    }

    /**
     * Testing if render calls function for filling user data taken
     * from Facebook account - FB connect is enabled
     *
     * @return null
     */
    public function testRenderFillsFormWithFbUserData_FbConnectEnabledUserConnected()
    {
        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('oeFacebookShowConnect', true);
        $oUser = oxNew('oxUser');

        $oView = $this->getMock('oeFacebookUserController', array("_fillFormWithFacebookData", "getUser"));
        $oView->expects($this->any())->method('getUser')->will($this->returnValue($oUser));
        $oView->expects($this->never())->method('_fillFormWithFacebookData');
        $oView->render();
    }

    /**
     * Testing if render calls function for filling user data taken
     * from Facebook account - FB connect is enabled
     *
     * @return null
     */
    public function testFillFormWithFacebookData()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return true;}");
        oxTestModules::addFunction('oeFacebook', "api", "{return array(first_name=>'testFirstName', last_name=>'testLastName');}");

        $oView = $this->getProxyClass('oeFacebookUserController');
        $oView->UNITfillFormWithFacebookData();

        $aViewData = $oView->getInvoiceAddress();
        $this->assertEquals("testFirstName", $aViewData["oxuser__oxfname"]);
        $this->assertEquals("testLastName", $aViewData["oxuser__oxlname"]);
    }

    /**
     * Testing if render calls function for filling user data taken
     * from Facebook account - data already filled up
     *
     * @return null
     */
    public function testFillFormWithFacebookData_dateAlreadyPrefilled()
    {
        oxTestModules::addFunction('oeFacebook', "isConnected", "{return true;}");
        oxTestModules::addFunction('oeFacebook', "api", "{return array(first_name=>'testFirstName', last_name=>'testLastName');}");

        $oView = $this->getProxyClass('oeFacebookUserController');
        $aViewData["invadr"]["oxuser__oxfname"] = "testValue1";
        $aViewData["invadr"]["oxuser__oxlname"] = "testValue2";
        $aViewData = $oView->setNonPublicVar("_aViewData", $aViewData);

        $oView->UNITfillFormWithFacebookData();

        $aViewData = $oView->getNonPublicVar("_aViewData");
        $this->assertEquals("testValue1", $aViewData["invadr"]["oxuser__oxfname"]);
        $this->assertEquals("testValue2", $aViewData["invadr"]["oxuser__oxlname"]);
    }

}
