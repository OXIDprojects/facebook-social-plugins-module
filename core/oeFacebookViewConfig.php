<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

use OxidEsales\Eshop\Core\Registry;

/**
 * Extends view config data access class.
 */
class oeFacebookViewConfig extends oeFacebookViewConfig_parent
{
    protected $facebookConfiguration = null;

    /**
     * Getter for facebook configuration object
     */
    public function getFacebookConfiguration()
    {
        if (is_null($this->facebookConfiguration)) {
            $this->facebookConfiguration = oxNew('oeFacebookConfiguration');
        }

        return $this->facebookConfiguration;
    }

    /**
     * Wrapper for oeFacebookConfiguration::getShowFbConnect
     *
     * @return bool
     */
    public function getShowFbConnect()
    {
        return $this->getFacebookConfiguration()->getShowFbConnect();
    }

    /**
     * Wrapper for oeFacebookConfiguration::getFbAppId
     *
     * @return string
     */
    public function getFbAppId()
    {
        return $this->getFacebookConfiguration()->getFbAppId();
    }

    /**
     * Wrapper for oeFacebookConfiguration::isFacebookPluginActive
     *
     * @return bool
     */
    public function isFacebookPluginActive($name)
    {
        return $this->getFacebookConfiguration()->isFacebookPluginActive($name);
    }

    /**
     * Wrapper for oeFacebookConfiguration::isConnectedWithFb
     *
     * @return bool
     */
    public function isConnectedWithFb()
    {
        return $this->getFacebookConfiguration()->isConnectedWithFb();
    }

    /**
     * Wrapper for oeFacebookConfiguration::isFbWidgetVisible
     *
     * @return bool
     */
    public function isFbWidgetVisible()
    {
        return $this->getFacebookConfiguration()->isFbWidgetVisible();
    }

}
