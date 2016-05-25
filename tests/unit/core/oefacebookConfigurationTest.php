<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../FacebookTestCase.php';

class Unit_Core_oeFacebookConfigurationTest extends FacebookTestCase
{

    /**
     * Set up the fixture.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Tear down the fixture.
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * oxViewconfig::getFbAppId() test case
     *
     * @return null
     */
    public function testGetFbAppId()
    {
        $test = 'MyFacebookAppId';
        $this->getConfig()->setConfigParam('oeFacebookAppId', $test);

        $ViewConfig = oxNew('oeFacebookConfiguration');
        $this->assertEquals($test, $ViewConfig->getFbAppId());
    }

    /**
     * getShowFbConnect() test case
     *
     * @return null
     */
    public function testGetShowFbConnect()
    {
        $ViewConfig = oxNew('oeFacebookConfiguration');

        $this->getConfig()->setConfigParam('oeFacebookShowConnect', false);
        $this->assertFalse($ViewConfig->getShowFbConnect());

        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);
        $this->getConfig()->setConfigParam('oeFacebookAppId', true);
        $this->getConfig()->setConfigParam('oeFacebooksSecretKey', true);
        $this->assertTrue($ViewConfig->getShowFbConnect());

        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);
        $this->getConfig()->setConfigParam('oeFacebookAppId', false);
        $this->getConfig()->setConfigParam('oeFacebooksSecretKey', false);
        $this->assertFalse($ViewConfig->getShowFbConnect());

        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);
        $this->getConfig()->setConfigParam('oeFacebookAppId', false);
        $this->getConfig()->setConfigParam('oeFacebooksSecretKey', true);
        $this->assertFalse($ViewConfig->getShowFbConnect());

        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);
        $this->getConfig()->setConfigParam('oeFacebookAppId', true);
        $this->getConfig()->setConfigParam('oeFacebooksSecretKey', false);
        $this->assertFalse($ViewConfig->getShowFbConnect());

    }

    /**
     * Testing getter for checking if user is connected using Facebook connect
     *
     * return null
     */
    public function testIsConnectedWithFb()
    {
        $facebook = $this->getMock('oeFacebook', array("isConnected"));
        $facebook->expects($this->any())->method("isConnected")->will($this->returnValue(true));
        oxTestModules::addModuleObject('oeFacebook', $facebook);
        $test = oxNew('oeFacebookConfiguration');

        $this->setConfigParam('oeFacebookShowConnect', true);
        $this->assertTrue($test->isConnectedWithFb());

        $this->setConfigParam('oeFacebookShowConnect', false);
        $this->assertFalse($test->isConnectedWithFb());
    }

    /**
     * Testing getter for checking if user is connected using Facebook connect
     *
     * return null
     */
    public function testIsNotConnectedWithFb()
    {
        $facebook = $this->getMock('oeFacebook', array("isConnected"));
        $facebook->expects($this->any())->method("isConnected")->will($this->returnValue(false));
        oxTestModules::addModuleObject('oeFacebook', $facebook);

        $this->setConfigParam('oeFacebookShowConnect', true);

        $test = oxNew('oeFacebookConfiguration');
        $this->assertFalse($test->isConnectedWithFb());
    }

    /**
     * Testing getting connected with Facebook connect user id
     *
     * return null
     */
    public function testGetFbUserId()
    {
        oxTestModules::addFunction('oeFacebook', "getUser", "{return 123;}");

        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('oeFacebookShowConnect', false);

        $test = oxNew('oeFacebookConfiguration');
        $this->assertNull($test->getFbUserId());

        $myConfig->setConfigParam('oeFacebookShowConnect', true);
        $this->assertEquals("123", $test->getFbUserId());
    }

    /**
     * Testing getting true or false for showing popup after user
     * connected using Facebook connect - FB connect is disabled
     *
     * return null
     */
    public function testShowFbConnectToAccountMsg_FbConnectIsOff()
    {
        $this->setRequestParameter("fblogin", false);

        $test = oxNew('oeFacebookConfiguration');
        $this->assertFalse($test->showFbConnectToAccountMsg());
    }

    /**
     * Testing getting true or false for showing popup after user
     * connected using Facebook connect - FB connect is enabled
     * user connected using FB, but does not has account in shop
     *
     * return null
     */
    public function testShowFbConnectToAccountMsg_FbOn_NoAccount()
    {
        $this->setRequestParameter("fblogin", true);

        $test = $this->getMock('oeFacebookConfiguration', array('getUser'));
        $test->expects($this->any())->method('getUser')->will($this->returnValue(null));

        $this->assertTrue($test->showFbConnectToAccountMsg());
    }

    /**
     * Testing getting true or false for showing popup after user
     * connected using Facebook connect - FB connect is enabled
     * user connected using FB and has account in shop
     *
     * return null
     */
    public function testShowFbConnectToAccountMsg_FbOn_AccountOn()
    {
        $this->setRequestParameter("fblogin", true);
        $oUser = oxNew('oxUser');

        $test = $this->getMock('oeFacebookConfiguration', array('getUser'));
        $test->expects($this->any())->method('getUser')->will($this->returnValue($oUser));

        $this->assertFalse($test->showFbConnectToAccountMsg());
    }
}
