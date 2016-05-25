[{if $oViewConf->getShowFbConnect()}]
    <div class="altLoginBox corners clear">
        <span>[{oxmultilang ident="LOGIN_WITH" suffix="COLON"}]</span>
        <div id="loginboxFbConnect">
            [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/connect.tpl') ident="#loginboxFbConnect" }]
        </div>
    </div>
[{/if}]
