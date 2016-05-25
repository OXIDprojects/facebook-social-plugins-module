[{if $oViewConf->isFacebookPluginActive('Facepile') && $oViewConf->isConnectedWithFb()}]
    <div id="facebookFacepile" class="box">
        <h3>[{oxmultilang ident="OEFACEBOOK_FACEPILE"}]</h3>
        <div class="content" id="productFbFacePile">
            [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/facepile.tpl') ident="#productFbFacePile" type="text"}]
        </div>
    </div>
[{/if}]
