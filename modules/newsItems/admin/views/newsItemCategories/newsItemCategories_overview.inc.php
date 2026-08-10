<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <?php include_once getAdminSnippet('localeSelect'); ?>
        </td>
    </tr>
    <tr class="topRow">
        <td colspan="3"><h2><?= sysTranslations::get('news_all_categories') ?></h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('news_category_add') ?>"
                                  alt="<?= sysTranslations::get('news_category_add') ?>"><?= sysTranslations::get('news_category_add') ?></a><br/><a class="changeOrderBtn textRight"
                                                                                                                                                     href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen"
                                                                                                                                                     title="<?= sysTranslations::get('global_change_order') ?>"
                                                                                                                                                     alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get(
                        'global_change_order'
                    ) ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline">&nbsp;</th>
        <th><?= sysTranslations::get('news_category_name') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aNewsItemCategories AS $oNewsItemCategory) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        echo '<a id="newsItemCategory_' . $oNewsItemCategory->newsItemCategoryId . '_online_1" title="' . sysTranslations::get(
                'news_category_set_offline_tooltip'
            ) . '" class="action_icon ' . ($oNewsItemCategory->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oNewsItemCategory->newsItemCategoryId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="newsItemCategory_' . $oNewsItemCategory->newsItemCategoryId . '_online_0" title="' . sysTranslations::get(
                'news_category_set_online_tooltip'
            ) . '" class="action_icon ' . ($oNewsItemCategory->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oNewsItemCategory->newsItemCategoryId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . _e($oNewsItemCategory->name) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('news_category_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItemCategory->newsItemCategoryId . '"></a>';

        if ($oNewsItemCategory->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('news_category_delete') . '" onclick="return confirmChoice(\'' . _e($oNewsItemCategory->name) . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oNewsItemCategory->newsItemCategoryId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('news_category_not_deletable') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aNewsItemCategories)) {
        echo '<tr><td colspan="3"><i>' . sysTranslations::get('news_no_categories') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>
<?php

# create necessary javascript
$sNewsCatOnlineMsg     = sysTranslations::get('news_category_online');
$sNewsCatOfflineMsg    = sysTranslations::get('news_category_offline');
$sNewsCatnotchangedMsg = sysTranslations::get('news_category_not_changed');
$sController           = http_get('controller');
$sBottomJavascript     = <<<EOT
<script>
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
                    $("#newsItemCategory_"+dataObj.newsItemCategoryId+"_online_0").hide(); // hide offline button
                    $("#newsItemCategory_"+dataObj.newsItemCategoryId+"_online_1").hide(); // hide online button
                    $("#newsItemCategory_"+dataObj.newsItemCategoryId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sNewsCatOfflineMsg");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sNewsCatOnlineMsg");                            
                } else {
                    showStatusUpdate("$sNewsCatnotchangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>