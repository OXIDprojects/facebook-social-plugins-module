<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'oefacebook',
    'title'       => array(
        'de' => 'Facebook Social Plugins',
        'en' => 'Facebook Social Plugins',
    ),
    'description' => array(
        'de' => 'OXID eSales Facebook Social Plugins Module',
        'en' => 'OXID eSales Facebook Social Plugins Module',
    ),
    'thumbnail'    => 'module.png',
    'version'     => '2.0.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'http://www.oxid-esales.com/',
    'email'       => '',
    'extend'      => array('oxcmp_user' => 'oe/oefacebook/Application/component/oeFacebookUserComponent',
                           'user' => 'oe/oefacebook/Application/controller/oeFacebookUserController',
                           'oxuser' => 'oe/oefacebook/Application/model/oeFacebookUser',
                           'oxviewconfig' => 'oe/oefacebook/core/oeFacebookViewConfig'
    ),
    'files'       => array('oeFacebookEvents' => 'oe/oefacebook/core/oeFacebookEvents.php',
                           'oeFacebook' => 'oe/oefacebook/core/oeFacebook.php',
                           'oeFacebookConfiguration' => 'oe/oefacebook/core/oeFacebookConfiguration.php',
    ),
    'templates'   => array(),
    'blocks'      => array(
        array('template' => 'layout/base.tpl', 'block'=>'base_style', 'file'=>'Application/views/blocks/oefacebook_css.tpl'),
        array('template' => 'layout/base.tpl', 'block'=>'head_html_namespace', 'file'=>'Application/views/blocks/head_html_namespace.tpl'),

        //azure theme
        array('theme' => 'azure', 'template' => 'layout/base.tpl', 'block'=>'head_meta_open_graph', 'file'=>'Application/views/blocks/azure/head_meta_open_graph.tpl'),
        array('theme' => 'azure', 'template' => 'layout/page.tpl', 'block'=>'layout_init_social', 'file'=>'Application/views/blocks/azure/layout_init_social.tpl'),
        array('theme' => 'azure', 'template' => 'page/details/inc/productmain.tpl', 'block'=>'details_productmain_social', 'file'=>'Application/views/blocks/azure/details_productmain_social.tpl'),
        array('theme' => 'azure', 'template' => 'widget/header/loginbox.tpl', 'block'=>'thirdparty_login', 'file'=>'Application/views/blocks/azure/facebook_login.tpl'),
        array('theme' => 'azure', 'template' => 'layout/footer.tpl', 'block'=>'footer_social', 'file'=>'Application/views/blocks/azure/footer_fblike.tpl'),
        array('theme' => 'azure', 'template' => 'layout/sidebar.tpl', 'block'=>'sidebar_social', 'file'=>'Application/views/blocks/azure/sidebar_facebookfacepile.tpl'),
        array('theme' => 'azure', 'template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_comments', 'file'=>'Application/views/blocks/azure/details_tabs_fbcomments.tpl'),
        array('theme' => 'azure', 'template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_invite', 'file'=>'Application/views/blocks/azure/details_tabs_fbinvite.tpl'),
        array('theme' => 'azure', 'template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_social', 'file'=>'Application/views/blocks/azure/details_tabs_facebook.tpl'),

        //flow theme
        array('theme' => 'flow', 'template' => 'layout/base.tpl', 'block'=>'head_meta_open_graph', 'file'=>'Application/views/blocks/flow/head_meta_open_graph.tpl'),
        array('theme' => 'flow', 'template' => 'layout/page.tpl', 'block'=>'layout_init_social', 'file'=>'Application/views/blocks/flow/layout_init_social.tpl'),
        array('theme' => 'flow', 'template' => 'page/details/inc/productmain.tpl', 'block'=>'details_productmain_social', 'file'=>'Application/views/blocks/flow/details_productmain_social.tpl'),
        array('theme' => 'flow', 'template' => 'widget/header/loginbox.tpl', 'block'=>'thirdparty_login', 'file'=>'Application/views/blocks/flow/facebook_login.tpl'),
        array('theme' => 'flow', 'template' => 'page/details/ajax/fullproductinfo.tpl', 'block'=>'fullproductinfo_init_details_page', 'file'=>'Application/views/blocks/flow/init_details_page.tpl'),
        array('theme' => 'flow', 'template' => 'page/details/ajax/productmain.tpl', 'block'=>'productmain_init_details_page', 'file'=>'Application/views/blocks/flow/init_details_page.tpl'),
        array('theme' => 'flow', 'template' => 'layout/footer.tpl', 'block'=>'footer_social', 'file'=>'Application/views/blocks/flow/footer_fblike.tpl'),
        array('theme' => 'flow', 'template' => 'layout/sidebar.tpl', 'block'=>'sidebar_social', 'file'=>'Application/views/blocks/flow/sidebar_facebookfacepile.tpl'),
        array('theme' => 'flow', 'template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_social_navigation', 'file'=>'Application/views/blocks/flow/details_tabs_facebook_navigation.tpl'),
        array('theme' => 'flow', 'template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_social_content', 'file'=>'Application/views/blocks/flow/details_tabs_facebook_content.tpl'),
        array('theme' => 'flow', 'template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_invite', 'file'=>'Application/views/blocks/flow/details_tabs_fbinvite.tpl'),
        array('theme' => 'flow', 'template' => 'page/details/inc/tabs.tpl', 'block'=>'details_tabs_comments', 'file'=>'Application/views/blocks/flow/details_tabs_fbcomments.tpl'),

    ),
    'settings'    => array(
        array('group' => 'main', 'name' => 'oeFacebookConfirmEnabled', 'type' => 'bool', 'value' => '1'),
        array('group' => 'main', 'name' => 'oeFacebookAppId', 'type' => 'str', 'value' => ''),
        array('group' => 'main', 'name' => 'oeFacebooksSecretKey', 'type' => 'str', 'value' => ''),
        array('group' => 'main', 'name' => 'oeFacebookShowConnect', 'type' => 'select',   'value' => '0', 'constrains' => '0|1', 'position' => 0),
        array('group' => 'main', 'name' => 'oeFacebookCommentsEnabled', 'type' => 'select',   'value' => '0', 'constrains' => '0|1', 'position' => 0),
        array('group' => 'main', 'name' => 'oeFacebookFacepileEnabled', 'type' => 'select',   'value' => '0', 'constrains' => '0|1', 'position' => 0),
        array('group' => 'main', 'name' => 'oeFacebookInviteEnabled', 'type' => 'select',   'value' => '0', 'constrains' => '0|1', 'position' => 0),
        array('group' => 'main', 'name' => 'oeFacebookLikeEnabled', 'type' => 'select',   'value' => '0', 'constrains' => '0|1', 'position' => 0),
        array('group' => 'main', 'name' => 'oeFacebookShareEnabled', 'type' => 'select',   'value' => '0', 'constrains' => '0|1', 'position' => 0)
    ),
    'events'       => array(
        'onActivate'   => 'oefacebookevents::onActivate',
        'onDeactivate' => 'oefacebookevents::onDeactivate'
    ),
);
