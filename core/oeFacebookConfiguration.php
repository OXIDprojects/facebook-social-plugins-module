<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

use OxidEsales\Eshop\Core\Registry;

/**
 * Provides facebook application configuration.
 */
class oeFacebookConfiguration
{
    /**
     * Facebook widget status marker
     *
     * @var bool
     */
    protected $facebookWidgetsOn = null;

    /**
     * Returns facebook application key value
     *
     * @return string
     */
    public function getFbAppId()
    {
        return Registry::getConfig()->getConfigParam('oeFacebookAppId');
    }

    /**
     * Checks if Facebook connect is on. If yes, also checks if Facebook application id
     * and secure key are entered in config table.
     *
     * @return bool
     */
    public function getShowFbConnect()
    {
        $config = Registry::getConfig();

        if ($config->getConfigParam('oeFacebookShowConnect')) {
            if ($config->getConfigParam('oeFacebookAppId') && $config->getConfigParam('oeFacebooksSecretKey')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if feature is enabled
     *
     * @param string $name feature name
     *
     * @return bool
     */
    public function isFacebookPluginActive($name)
    {
        return Registry::getConfig()->getConfigParam('oeFacebook' . $name . 'Enabled');
    }

    /**
     * Returns TRUE if facebook widgets are on
     *
     * @return boolean
     */
    public function isFbWidgetVisible()
    {
        if ($this->facebookWidgetsOn === null) {
            $utils = Registry::get("oxUtilsServer");

            // reading ..
            $this->facebookWidgetsOn = (bool) $utils->getOxCookie("fbwidgetson");
        }

        return $this->facebookWidgetsOn;
    }

    /**
     * Checks if user is connected via Facebook connect
     *
     * @return bool
     */
    public function isConnectedWithFb()
    {
        $config = Registry::getConfig();

        if ($config->getConfigParam("oeFacebookShowConnect")) {
            $facebook = Registry::get('oeFacebook');

            return $facebook->isConnected();
        }

        return false;
    }

    /**
     * Gets get Facebook user id
     *
     * @return int
     */
    public function getFbUserId()
    {
        if (Registry::getConfig()->getConfigParam("oeFacebookShowConnect")) {
            $facebook = Registry::get('oeFacebook');

            return $facebook->getUser();
        }
    }

    /**
     * Returns true if popup message about connecting your existing account
     * to Facebook account must be shown
     *
     * @return bool
     */
    public function showFbConnectToAccountMsg()
    {
        if (Registry::getConfig()->getRequestParameter("fblogin")) {
            if (!$this->getUser() || ($this->getUser() && Registry::getSession()->getVariable('_blFbUserIdUpdated'))) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Wrapper for oxconfig::getUser.
     *
     * @return oxUser
     */
    public function getUser()
    {
        return Registry::getConfig()->getUser();
    }
}
