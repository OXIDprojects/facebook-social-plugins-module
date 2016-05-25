[{if $oViewConf->isFacebookPluginActive('Comments') && $oViewConf->getFbAppId()}]
    [{capture append="FBtabs"}]<a href="#productFbComments" data-toggle="tab">[{oxmultilang ident="OEFACEBOOK_COMMENTS"}]</a>[{/capture}]
    [{assign var='_fbScript' value="http://connect.facebook.net/en_US/all.js#appId="|cat:$oViewConf->getFbAppId()|cat:"&amp;xfbml=1"}]
    [{capture append="FBtabsContent"}]
        <div id="productFbComments" class="tab-pane[{if $blFirstTab}] active[{/if}]">
            [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_flow/comments.tpl') ident="#productFbComments" script=$_fbScript type="text"}]
        </div>
        [{assign var="blFirstTab" value=false}]
    [{/capture}]
[{/if}]
