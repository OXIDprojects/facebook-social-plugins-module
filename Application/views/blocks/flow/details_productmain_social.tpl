<div class="social">
    [{if $oViewConf->isFacebookPluginActive('Share') || $oViewConf->isFacebookPluginActive('Like') && $oViewConf->getFbAppId() }]
    [{if $oViewConf->isFacebookPluginActive('Confirm') && !$oViewConf->isFbWidgetVisible()}]
    <div class="socialButton" id="productFbShare">
        [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/share.tpl') ident="#productFbShare"}]
        [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/like.tpl') assign="fbfile"}]
     [{assign var='fbfile' value=$fbfile|strip|escape:'url'}]
        [{oxscript add="oxFacebook.buttons['#productFbLike']={html:'`$fbfile`',script:''};"}]
    </div>
    <div class="socialButton" id="productFbLike"></div>
    [{else}]
    <div class="socialButton" id="productFbShare">
        [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/share.tpl') ident="#productFbShare"}]
    </div>
    <div class="socialButton" id="productFbLike">
        [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/like.tpl') ident="#productFbLike"}]
    </div>
    [{/if}]
    [{/if}]
</div>
