[{if $oViewConf->isFacebookPluginActive('Invite') && $oViewConf->getFbAppId()}]
    [{capture append="FBtabs"}]<a href="#productFbInvite" data-toggle="tab">[{oxmultilang ident="OEFACEBOOK_INVITE"}]</a>[{/capture}]
    [{capture append="FBtabsContent"}]
    <div id="productFbInvite" class="tab-pane[{if $blFirstTab}] active[{/if}]">
        <fb:serverfbml width="560px" id="productFbInviteFbml">
            [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/invite.tpl') ident="#productFbInviteFbml" type="text"}]
        </fb:serverfbml>
    </div>
    [{assign var="blFirstTab" value=false}]
    [{/capture}]
[{/if}]
