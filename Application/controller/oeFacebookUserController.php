<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

/**
 * User details.
 * Collects and arranges user object data (information, like shipping address, etc.).
 */
class oeFacebookUserController extends oeFacebookUserController_parent
{
    /**
     * Loads customer basket object form session (oxSession::getBasket()),
     * passes action article/basket/country list to template engine. If
     * available - loads user delivery address data (oxAddress). If user
     * is connected using Facebook connect calls user::_fillFormWithFacebookData to
     * prefill form data with data taken from user Facebook account. Returns
     * name template file to render user::_sThisTemplate.
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        $config = $this->getConfig();

        parent::render();

        if ($config->getConfigParam('oeFacebookShowConnect') && !$this->getUser()) {
            $this->_fillFormWithFacebookData();
        }

        return $this->_sThisTemplate;
    }

    /**
     * Fills user form with date taken from Facebook
     *
     */
    protected function _fillFormWithFacebookData()
    {
        // Create our Application instance.
        $facebook = oxRegistry::get('oefacebook');

        if ($facebook->isConnected()) {
            $facebookUser = $facebook->api('/me');

            $invoiceAddress = $this->getInvoiceAddress();
            $charset = oxRegistry::getLang()->translateString("charset");

            // do not stop converting on error - just try to translit unknown symbols
            $charset .= '//TRANSLIT';

            if (!$invoiceAddress["oxuser__oxfname"]) {
                $invoiceAddress["oxuser__oxfname"] = iconv('UTF-8', $charset, $facebookUser["first_name"]);
            }

            if (!$invoiceAddress["oxuser__oxlname"]) {
                $invoiceAddress["oxuser__oxlname"] = iconv('UTF-8', $charset, $facebookUser["last_name"]);
            }

            $this->setInvoiceAddress($invoiceAddress);
        }
    }

}
