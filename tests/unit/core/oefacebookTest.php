<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../FacebookTestCase.php';


class Unit_Core_oeFacebookTest extends FacebookTestCase
{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Testing method isConnected() - FB connect is disabled
     */
    public function testIsConnected_FbConnectIsDisabled()
    {
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', false);

        $facebook = oxNew("oeFacebook");
        $this->assertFalse($facebook->isConnected());
    }

    /**
     * Testing method isConnected() - FB connect is enabled
     *
     * @return null
     */
    public function testIsConnected_FbConnectIsEnabled()
    {
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);

        $facebook = $this->getMock('oeFacebook', array('getUser', 'api'));
        $facebook->expects($this->once())->method('getUser')->will($this->returnValue(1));
        $facebook->expects($this->once())->method('api')->will($this->returnValue(true));

        $this->assertTrue($facebook->isConnected());
    }

    /**
     * Testing method isConnected() - FB connect is enaabled but no FB session is active
     *
     * @return null
     */
    public function testIsConnected_noFbSession_withUser()
    {
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);

        $facebook = $this->getMock('oeFacebook', array('getUser'));
        $facebook->expects($this->once())->method('getUser')->will($this->returnValue(10));

        $this->assertFalse($facebook->isConnected());
    }

    /**
     * Testing method isConnected() - FB connect is enaabled but no FB user is active
     *
     * @return null
     */
    public function testIsConnected_noFbUser()
    {
        $this->getConfig()->setConfigParam('oeFacebookShowConnect', true);

        $facebook = $this->getMock('oeFacebook', array('getUser'));
        $facebook->expects($this->once())->method('getUser')->will($this->returnValue(null));

        $this->assertFalse($facebook->isConnected());
    }

    /**
     * Test FB session SET manipulation
     *
     * @return null
     */
    public function testSetPersistentData()
    {
        $session = oxRegistry::getSession();
        $facebook = $this->getProxyClass('oeFacebook');

        $sessionKey = $facebook->constructSessionVariableName('access_token');
        $this->assertFalse($session->hasVariable($sessionKey));
        $facebook->setPersistentData('access_token', 'test1');
        $this->assertSame('test1', $session->getVariable($sessionKey));
    }

    /**
     * Test FB session GET manipulation
     *
     * @return null
     */
    public function testGetPersistentData()
    {
        $session = oxRegistry::getSession();
        $facebook = $this->getProxyClass('oeFacebook');

        $sessionKey = $facebook->constructSessionVariableName('access_token');
        $session->setVariable($sessionKey, 'test2');
        $value = $facebook->getPersistentData('access_token');
        $this->assertSame('test2', $value);
    }

    /**
     * Test FB session GET manipulation
     *
     * @return null
     */
    public function testClearPersistentData()
    {
        $session = oxRegistry::getSession();
        $facebook = $this->getProxyClass('oeFacebook');

        $sessionKey = $facebook->constructSessionVariableName('access_token');
        $session->setVariable($sessionKey, 'test3');
        $facebook->clearPersistentData('access_token');
        $this->assertFalse($session->hasVariable($sessionKey));
    }
}
