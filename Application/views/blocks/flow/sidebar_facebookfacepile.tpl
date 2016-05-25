[{if $oViewConf->isFacebookPluginActive('Facepile') && $oViewConf->isConnectedWithFb()}]
    <div id="facebookFacepile" class="box well well-sm">
        <section>
            <div class="page-header h3">[{oxmultilang ident="FACEBOOK_FACEPILE"}]</div>
            <div class="content" id="productFbFacePile">
                [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/facepile.tpl') ident="#productFbFacePile" type="text"}]
            </div>
        </section>
    </div>
[{/if}]
