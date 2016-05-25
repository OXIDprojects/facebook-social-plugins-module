<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

use OxidEsales\Eshop\Core\Registry;

/**
 * User manager.
 */
class oeFacebookUser extends oeFacebookUser_parent
{
    /**
     * Saves (updates) user object data information in DB. Return true on success.
     *
     * @return bool
     */
    public function save()
    {
        // checking if user Facebook ID should be updated
        if (Registry::getConfig()->getConfigParam("oeFacebookShowConnect")) {
            $oFb = Registry::get('oeFacebook');
            if ($oFb->isConnected() && $oFb->getUser()) {
                $this->oxuser__oefacebookfbid = new oxField($oFb->getUser());
            }
        }

        return parent::save();
    }

    /**
     * Loads active user object. If
     * user is not available - returns false.
     *
     * @param bool $blForceAdmin (default false)
     *
     * @return bool
     */
    public function loadActiveUser($blForceAdmin = false)
    {
        $oConfig = $this->getConfig();

        $blAdmin = $this->isAdmin() || $blForceAdmin;

        // first - checking session info
        $sUserID = $blAdmin ? oxRegistry::getSession()->getVariable('auth') : oxRegistry::getSession()->getVariable('usr');

        // trying automatic login (by 'remember me' cookie)
        $blFoundInCookie = false;
        if (!$sUserID && !$blAdmin && $oConfig->getConfigParam('blShowRememberMe')) {
            $sUserID = $this->_getCookieUserId();
            $blFoundInCookie = $sUserID ? true : false;
        }

        // If facebook connection is enabled, trying to login user using Facebook ID
        if (!$sUserID && !$blAdmin && $oConfig->getConfigParam("oeFacebookShowConnect")) {
            $sUserID = $this->getFacebookUserId();
        }

        // checking user results
        if ($sUserID) {
            if ($this->load($sUserID)) {
                // storing into session
                if ($blAdmin) {
                    oxRegistry::getSession()->setVariable('auth', $sUserID);
                } else {
                    oxRegistry::getSession()->setVariable('usr', $sUserID);
                }

                // marking the way user was loaded
                $this->_blLoadedFromCookie = $blFoundInCookie;

                return true;
            }
        } else {
            // no user
            if ($blAdmin) {
                oxRegistry::getSession()->deleteVariable('auth');
            } else {
                oxRegistry::getSession()->deleteVariable('usr');
            }

            return false;
        }
    }

    /**
     * Checks if user is connected via Facebook connect and if so, returns user id.
     *
     * @return string
     */
    protected function getFacebookUserId()
    {
        $oDb = oxDb::getDb();
        $oFb = oxRegistry::get('oeFacebook');
        if ($oFb->isConnected() && $oFb->getUser()) {
            $sSelect = $this->formFacebookUserIdQuery($oFb);

            $sUserID = $oDb->getOne($sSelect);
        }

        return $sUserID;
    }

    /**
     * Updates user Facebook ID
     *
     * @return null
     */
    public function updateFbId()
    {
        $oFb = oxRegistry::get('oeFacebook');
        $blRet = false;

        $fieldname = 'oxuser__' . strtolower(oeFacebookEvents::FBID_COLUMN_NAME);

        if ($oFb->isConnected() && $oFb->getUser()) {
            $this->$fieldname = new oxField($oFb->getUser());
            $blRet = $this->save();
        }

        return $blRet;
    }

    /**
     * Forms Facebook user ID query.
     *
     * @param oeFacebook $facebookConnector
     *
     * @return string
     */
    protected function formFacebookUserIdQuery($facebookConnector)
    {
        $userSelectQuery = "oxuser." . oeFacebookEvents::FBID_COLUMN_NAME . " = " .
                            oxDb::getDb()->quote($facebookConnector->getUser());

        $query = "select oxid from oxuser where oxuser.oxactive = 1 and {$userSelectQuery}";

        return $query;
    }

}
