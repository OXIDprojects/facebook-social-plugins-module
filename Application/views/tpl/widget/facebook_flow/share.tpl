[{if $oViewConf->isFacebookPluginActive('Share') && $oViewConf->getFbAppId()}]
    <fb:share-button href="[{$oView->getCanonicalUrl()}]"></fb:share-button>
[{/if}]

