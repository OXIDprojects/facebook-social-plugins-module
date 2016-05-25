[{if $oViewConf->getShowFbConnect()}]
    <div class="altLoginBox corners clear">
        <span>[{oxmultilang ident="LOGIN_WITH"}]
        </span>
        <div id="loginboxFbConnect">
            [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/connect.tpl') ident="#loginboxFbConnect" }]
        </div>
    </div>
[{/if}]
