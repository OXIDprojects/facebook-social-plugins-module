[{if $oViewConf->getFbAppId()}]
    <meta property="og:site_name" content="[{$oViewConf->getBaseDir()}]">
    <meta property="fb:app_id" content="[{$oViewConf->getFbAppId()}]">
    <meta property="og:title" content="[{$oView->getPageTitle()}]">
    [{if $oViewConf->getActiveClassName() == 'details'}]
        <meta property="og:type" content="product">
        <meta property="og:image" content="[{$oView->getActPicture()}]">
        <meta property="og:url" content="[{$oView->getCanonicalUrl()}]">
    [{else}]
        <meta property="og:type" content="website">
        <meta property="og:image" content="[{$oViewConf->getImageUrl('basket.png')}]">
        <meta property="og:url" content="[{$oViewConf->getCurrentHomeDir()}]">
    [{/if}]
[{/if}]
