<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../FacebookTestCase.php';

class Unit_Core_oeFacebookEventsTest extends FacebookTestCase
{
    /**
     * Set up the fixture.
     */
    protected function setUp()
    {
        parent::setUp();
        oeFacebookEvents::onDeactivate();
    }

    /**
     * Tear down the fixture.
     */
    public function tearDown()
    {
        oeFacebookEvents::addFacebookToUserTable();

        parent::tearDown();
    }

    /**
     * Test onActivate event.
     */
    public function testOnActivate()
    {
        oeFacebookEvents::onActivate();

        $DbMetaDataHandler = oxNew('OxidEsales\Eshop\Core\DbMetaDataHandler');
        $this->assertTrue($DbMetaDataHandler->fieldExists(oeFacebookEvents::FBID_COLUMN_NAME, 'oxuser'));

        $content = oxNew('OxidEsales\Eshop\Application\Model\Content');
        $result = $content->loadByIdent(oeFacebookEvents::FB_CONTENTS_LOADID);
        $this->assertTrue($result);
    }

    /**
     * Test onDeactivate event.
     * Currently it does not delete anything form database.
     */
    public function testOnDeactivate()
    {
        oeFacebookEvents::onDeactivate();

        $DbMetaDataHandler = oxNew('OxidEsales\Eshop\Core\DbMetaDataHandler');
        $this->assertTrue($DbMetaDataHandler->fieldExists(oeFacebookEvents::FBID_COLUMN_NAME, 'oxuser'));

        $content = oxNew('OxidEsales\Eshop\Application\Model\Content');
        $result = $content->loadByIdent(oeFacebookEvents::FB_CONTENTS_LOADID);
        $this->assertTrue($result);
    }

    /**
     * Test removeFacebookFromUserTable function.
     */
    public function testRemoveFacebookFromUserTable()
    {
        oeFacebookEvents::removeFacebookFromUserTable();

        $DbMetaDataHandler = oxNew('OxidEsales\Eshop\Core\DbMetaDataHandler');
        $this->assertFalse($DbMetaDataHandler->fieldExists(oeFacebookEvents::FBID_COLUMN_NAME, 'oxuser'));
    }

    /**
     * Test removeFacebookContent function.
     */
    public function testRemoveFacebookContent()
    {
        oeFacebookEvents::removeFacebookContent();

        $content = oxNew('OxidEsales\Eshop\Application\Model\Content');
        $result = $content->loadByIdent(oeFacebookEvents::FB_CONTENTS_LOADID);
        $this->assertFalse($result);
    }

    /**
     * Test getQuery
     */
    public function testGetQuery()
    {
        $parameters = array('loadid' => oeFacebookEvents::FB_CONTENTS_LOADID,
                            'shopid' => 'testshopid');

        $result = oeFacebookEvents::getQuery('install_content.sql.tpl', $parameters);

        $this->assertContains('testshopid', $result);
    }

    /**
     * Test that content is only added if missing and not overwritten.
     */
    public function testDoNotOverwriteContent()
    {
        oeFacebookEvents::addFacebookContent();
        $content = oxNew('OxidEsales\Eshop\Application\Model\Content');
        $content->loadByIdent(oeFacebookEvents::FB_CONTENTS_LOADID);
        $content->setTitle('changed title');
        $content->save();

        oeFacebookEvents::addFacebookContent();
        $content = oxNew('OxidEsales\Eshop\Application\Model\Content');
        $content->loadByIdent(oeFacebookEvents::FB_CONTENTS_LOADID);
        $this->assertSame('changed title', $content->getTitle());
    }
}
