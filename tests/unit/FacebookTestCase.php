<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

abstract class FacebookTestCase extends \OxidTestCase
{
    /**
     * Fixture set up.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->addFacebookField();
    }

    /**
     * Fixture set up.
     */
    protected function tearDown()
    {
        $this->removeFacebookField();

        parent::tearDown();
    }

    /**
     * Test helper to prepare the database.
     */
    protected function addFacebookField()
    {
        $query = "ALTER TABLE `oxuser` ADD COLUMN `" . oeFacebookEvents::FBID_COLUMN_NAME . "`" .
                 " bigint unsigned NOT NULL default '0' COMMENT 'Facebook id (used for openid login)'";

        $metaDataHandler = oxNew('OxidEsales\Eshop\Core\DbMetaDataHandler');
        if (!$metaDataHandler->fieldExists('OEFACEBOOKFBID', 'oxuser')) {
            oxDb::getDb()->execute($query);
        }
    }

    /**
     * Test helper to remove the additional field
     */
    protected function removeFacebookField()
    {
        $query = "ALTER TABLE `oxuser` DROP COLUMN `" . oeFacebookEvents::FBID_COLUMN_NAME . "`";

        $metaDataHandler = oxNew('OxidEsales\Eshop\Core\DbMetaDataHandler');
        if ($metaDataHandler->fieldExists(oeFacebookEvents::FBID_COLUMN_NAME, 'oxuser')) {
            oxDb::getDb()->execute($query);
        }
    }
}
