[{if $FBtabs}]
    <div class="tabbedWidgetBox clear">
        <ul id="itemFbTabs" class="tabs clear">
            [{foreach from=$FBtabs item="FBtab"}]
            <li class="fbTab">[{$FBtab}]</li>
            [{/foreach}]
        </ul>
        <div class="widgetBoxBottomRound FXgradBlueLight">
            [{foreach from=$FBtabsContent item="FBtabContent"}]
            [{$FBtabContent}]
            [{/foreach}]
        </div>
    </div>
[{/if}]
