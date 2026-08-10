<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <?php include_once getAdminSnippet('localeSelect'); ?>
        </td>
    </tr>
    <tr class="topRow">
        <td colspan="5"><h2><?= sysTranslations::get('dynamiccontent_all_items') ?></h2>
            <div class="right">
                <?php

                if ($oCurrentUser->isAdmin()) { ?>
                    <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('dynamiccontent_add') ?>"
                       alt="<?= sysTranslations::get('dynamiccontent_add') ?>"><?= sysTranslations::get('dynamiccontent_add') ?></a><br/>
                <?php } ?>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline"></th>
        <th><?= sysTranslations::get('global_name') ?></th>
        <th><?= ucfirst(sysTranslations::get('global_type')) ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody id="tableContents">
    <?php

    foreach ($aAllDynamicContents AS $oDynamicContent) {

        if ($oDynamicContent->adminOnly && !$oCurrentUser->isAdmin()) {
            continue;
        }

        echo '<tr data-id="' . $oDynamicContent->dynamicContentId . '" class="movable">';
        echo '<td>';
        # online offline button            
        echo '<a id="dynamicContent_' . $oDynamicContent->dynamicContentId . '_online_1" title="' . sysTranslations::get(
                'dynamiccontent_set_offline'
            ) . '" class="action_icon ' . ($oDynamicContent->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oDynamicContent->dynamicContentId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="dynamicContent_' . $oDynamicContent->dynamicContentId . '_online_0" title="' . sysTranslations::get(
                'dynamiccontent_set_online'
            ) . '" class="action_icon ' . ($oDynamicContent->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oDynamicContent->dynamicContentId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';

        echo '<td>' . $oDynamicContent->name . '</td>';
        echo '<td>' . $oDynamicContent->type . '</td>';
        echo '<td>';
        if (!$oDynamicContent->adminOnly || $oCurrentUser->isAdmin()) {
            echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('dynamiccontent_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oDynamicContent->dynamicContentId . '"></a>';
        }
        if ($oCurrentUser->isAdmin()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('dynamiccontent_delete') . '" onclick="return confirmChoice(\'' . $oDynamicContent->getName() . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oDynamicContent->dynamicContentId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        }
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aAllDynamicContents)) {
        echo '<tr><td colspan="5"><i>' . sysTranslations::get('dynamiccontent_no_items') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>

<?php

# create necessary javascript
$sController                    = http_get('controller');
$dynamicContentOnline           = sysTranslations::get('dynamiccontent_online');
$dynamicContentOffline          = sysTranslations::get('dynamiccontent_offline');
$dynamicContentStatusNotChanged = sysTranslations::get('dynamiccontent_status_not_changed');
$sBottomJavascript              = <<<EOT
<script type="text/javascript">
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: $(this).prop('href'),
            data: 'ajax=1',
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $("#dynamicContent_"+dataObj.dynamicContentId+"_online_0").hide(); // hide offline button
                    $("#dynamicContent_"+dataObj.dynamicContentId+"_online_1").hide(); // hide online button
                    $("#dynamicContent_"+dataObj.dynamicContentId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$dynamicContentOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$dynamicContentOnline");                            
                } else {
                    showStatusUpdate("$dynamicContentStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>