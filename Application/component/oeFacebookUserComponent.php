<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

/**
 * User object manager.
 * Sets user details data, switches, logouts, logins user etc.
 *
 * @subpackage oxcmp
 */
class oeFacebookUserComponent extends oeFacebookUserComponent_parent
{
    /**
     * Executes oxcmp_user::login() and updates logged in user Facebook User ID (if user was
     * connected using Facebook Connect)
     *
     */
    public function login_updateFbId()
    {
        $this->login();

        if ($oUser = $this->getUser()) {
            //updating user Facebook ID
            if ($oUser->updateFbId()) {
                oxRegistry::getSession()->setVariable('_blFbUserIdUpdated', true);
            }
        }
    }
}
