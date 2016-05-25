[{if $oViewConf->isFacebookPluginActive('Invite') && $oViewConf->getFbAppId()}]
    [{capture append="FBtabs"}]<a href="#productFbInvite">[{oxmultilang ident="OEFACEBOOK_INVITE"}]</a>[{/capture}]
    [{capture append="FBtabsContent"}]
    <div id="productFbInvite">
        <fb:serverfbml width="560px" id="productFbInviteFbml">
            [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/invite.tpl') ident="#productFbInviteFbml" type="text"}]
        </fb:serverfbml>
    </div>
    [{/capture}]
[{/if}]
