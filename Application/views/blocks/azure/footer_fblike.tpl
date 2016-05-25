[{if $oViewConf->isFacebookPluginActive('Like') && $oViewConf->getFbAppId()}]
    <div class="facebook" id="footerFbLike">
        [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/like.tpl') ident="#footerFbLike" parent="footer"}]
    </div>
[{/if}]
