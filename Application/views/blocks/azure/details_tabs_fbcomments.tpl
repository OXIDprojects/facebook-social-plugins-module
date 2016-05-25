[{if $oViewConf->isFacebookPluginActive('Comments') && $oViewConf->getFbAppId()}]
    [{capture append="FBtabs"}]<a href="#productFbComments">[{oxmultilang ident="OEFACEBOOK_COMMENTS"}]</a>[{/capture}]
    [{assign var='_fbScript' value="http://connect.facebook.net/en_US/all.js#appId="|cat:$oViewConf->getFbAppId()|cat:"&amp;xfbml=1"}]
    [{capture append="FBtabsContent"}]<div id="productFbComments">
    [{include file=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/enable.tpl') source=$oViewConf->getModulePath('oefacebook','Application/views/tpl/widget/facebook_azure/comments.tpl') ident="#productFbComments" script=$_fbScript type="text"}]</div>[{/capture}]
[{/if}]
