[{if $oViewConf->isFacebookPluginActive('Like') && $oViewConf->getFbAppId()}]
    <section class="footer-box footer-box-facebook">
        [{if $oViewConf->isFacebookPluginActive('Like') && $oViewConf->getFbAppId()}]
        <div class="facebook pull-left" id="footerFbLike">
            [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/like.tpl') ident="#footerFbLike" parent="footer"}]
        </div>
        [{/if}]
    </section>
[{/if}]
