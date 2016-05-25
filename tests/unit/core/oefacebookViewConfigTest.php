<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

require_once __DIR__ . '/../FacebookTestCase.php';

use OxidEsales\Eshop\Core\ViewConfig;

if (!class_exists('oeFacebookViewConfig_parent')) {
    class oeFacebookViewConfig_parent extends ViewConfig
    {
    }
}

class Unit_Core_oeFacebookViewConfigTest extends FacebookTestCase
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

    public function testGetFacebookConfiguration()
    {
        $viewConfig = oxNew('oeFacebookViewConfig');
        $this->assertTrue(is_a($viewConfig->getFacebookConfiguration(), 'oeFacebookConfiguration'));
    }
}
