<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <?php include_once getAdminSnippet('localeSelect'); ?>
        </td>
    </tr>
    <tr class="topRow">
        <td colspan="3"><h2><?= sysTranslations::get('usp_all_items') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/toevoegen" title="<?= sysTranslations::get('usp_add') ?>"
                   alt="<?= sysTranslations::get('usp_add') ?>"><?= sysTranslations::get(
                        'usp_add'
                    ) ?></a><br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/volgorde-wijzigen&lang=1" title="<?= sysTranslations::get('global_change_order') ?>"
                   alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get('global_change_order') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline"></th>
        <th><?= sysTranslations::get('global_title') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 70px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody id="tableContents">
    <?php

    foreach ($aAllUsps AS $oUsp) { ?>
        <tr data-id="<?= $oUsp->uspId ?>" class="movable">
            <td>
                <!-- online offline button -->
                <a id="usp_<?= $oUsp->uspId ?>_online_1" title="<?= sysTranslations::get('usp_set_offline') ?>" class="action_icon <?= ($oUsp->online ? '' : 'hide') ?> online_icon"
                   href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() ?>/ajax-setOnline/<?= $oUsp->uspId ?>/?online=0&<?= CSRFSynchronizerToken::query() ?>"></a>
                <a id="usp_<?= $oUsp->uspId ?>_online_0" title="<?= sysTranslations::get('usp_set_online') ?>" class="action_icon <?= ($oUsp->online ? 'hide' : '') ?> offline_icon"
                   href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() ?>/ajax-setOnline/<?= $oUsp->uspId ?>/?online=1&<?= CSRFSynchronizerToken::query() ?>"></a>
            </td>
            <td><?= $oUsp->name ?></td>
            <td>
                <a class="action_icon edit_icon" title="<?= sysTranslations::get('usp_edit') ?>" href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId ?>"></a>
                <a class="action_icon delete_icon" title="<?= sysTranslations::get('usp_delete') ?>" onclick="return confirmChoice('<?= $oUsp->name ?>')" href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() ?>/verwijderen/<?= $oUsp->uspId ?>?<?= CSRFSynchronizerToken::query() ?>"></a>
            </td>
        </tr>
        <?php

    }
    if (empty($aAllUsps)) { ?>
        <tr><td colspan="3"><i><?= sysTranslations::get('usp_no_items') ?></i></td></tr>
    <?php } ?>
    </tbody>
</table>

<?php

# create necessary javascript
$sController             = Request::getControllerSegment();
$uspOnline           = sysTranslations::get('usp_online');
$uspOffline          = sysTranslations::get('usp_offline');
$uspStatusNotChanged = sysTranslations::get('usp_status_not_changed');
$sBottomJavascript       = <<<EOT
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
                    $("#usp_"+dataObj.uspId+"_online_0").hide(); // hide offline button
                    $("#usp_"+dataObj.uspId+"_online_1").hide(); // hide online button
                    $("#usp_"+dataObj.uspId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$uspOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$uspOnline");                            
                } else {
                    showStatusUpdate("$uspStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
