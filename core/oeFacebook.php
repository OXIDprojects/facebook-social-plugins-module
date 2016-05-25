<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

require_once getShopBasePath() . "modules/oe/oefacebook/core/facebook/facebook.php";

/**
 * Facebook API
 *
 */
class oeFacebook extends \Facebook
{

    /**
     * User is connected using Facebook connect.
     *
     * @var bool
     */
    protected $_blIsConnected = null;

    /**
     * Sets default application parameters - FB application ID,
     * secure key and cookie support.
     *
     * @return null
     */
    public function __construct()
    {
        $oConfig = oxRegistry::getConfig();

        $aFbConfig["appId"] = $oConfig->getConfigParam('oeFacebookAppId');
        $aFbConfig["secret"] = $oConfig->getConfigParam('oeFacebooksSecretKey');
        $aFbConfig["cookie"] = true;

        BaseFacebook::__construct($aFbConfig);
    }

    /**
     * Checks is user is connected using Facebook connect.
     *
     * @return bool
     */
    public function isConnected()
    {
        $oConfig = oxRegistry::getConfig();

        if (!$oConfig->getConfigParam("oeFacebookShowConnect")) {
            return false;
        }

        if ($this->_blIsConnected !== null) {
            return $this->_blIsConnected;
        }

        $this->_blIsConnected = false;
        $oUser = $this->getUser();

        if (!$oUser) {
            $this->_blIsConnected = false;

            return $this->_blIsConnected;
        }

        $this->_blIsConnected = true;
        try {
            $this->api('/me');
        } catch (FacebookApiException $e) {
            $this->_blIsConnected = false;
        }

        return $this->_blIsConnected;
    }

    /**
     * Provides the implementations of the inherited abstract
     * methods.  The implementation uses PHP sessions to maintain
     * a store for authorization codes, user ids, CSRF states, and
     * access tokens.
     *
     * @param string $key   Session key
     * @param string $value Session value
     *
     * @return null
     */
    protected function setPersistentData($key, $value)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to setPersistentData.');

            return;
        }

        $sSessionVarName = $this->constructSessionVariableName($key);
        oxRegistry::getSession()->setVariable($sSessionVarName, $value);
    }

    /**
     * GET session value
     *
     * @param string $key     Session key
     * @param bool   $default Default value, if session key not found
     *
     * @return string Session value / default
     */
    protected function getPersistentData($key, $default = false)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to getPersistentData.');

            return $default;
        }

        $sSessionVarName = $this->constructSessionVariableName($key);

        return (oxRegistry::getSession()->hasVariable($sSessionVarName) ?
            oxRegistry::getSession()->getVariable($sSessionVarName) : $default);
    }

    /**
     * Remove session parameter
     *
     * @param string $key Session param key
     *
     * @return null
     */
    protected function clearPersistentData($key)
    {
        if (!in_array($key, self::$kSupportedKeys)) {
            self::errorLog('Unsupported key passed to clearPersistentData.');

            return;
        }

        $sSessionVarName = $this->constructSessionVariableName($key);
        oxRegistry::getSession()->deleteVariable($sSessionVarName);
    }
}
