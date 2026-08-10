<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?></legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><label for="q"><?= sysTranslations::get('global_search_word') ?></label></td>
                <td><input class="default" id="q" type="text" name="newsItemFilter[q]" value="<?= _e($aNewsItemFilter['q']) ?>"/></td>
            </tr>
            <?php if (class_exists('NewsItemCategoryManager')) { ?>
                <tr>
                    <td class="withLabel"><label for="newsItemCategoryId"><?= ucfirst(sysTranslations::get('global_category')) ?></label></td>
                    <td>
                        <select class="default" id="newsItemCategoryId" name="newsItemFilter[newsItemCategoryId]">
                            <option value="">-- <?= sysTranslations::get('global_all_categories') ?> --</option>
                            <?php

                            foreach (NewsItemCategoryManager::getNewsItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]) AS $oNewsItemCategory) {
                                echo '<option ' . ($aNewsItemFilter['newsItemCategoryId'] == $oNewsItemCategory->newsItemCategoryId ? 'selected' : '') . ' value="' . $oNewsItemCategory->newsItemCategoryId . '">' . $oNewsItemCategory->name . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterNewsItems" value="<?= sysTranslations::get('news_filter') ?>"/> <input type="submit" name="resetFilter" value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>
<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <?php include_once getAdminSnippet('localeSelect'); ?>
        </td>
    </tr>
    <tr class="topRow">
        <td colspan="6"><h2><?= sysTranslations::get('news_all') ?></h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('news_add_tooltip') ?>"
                                  alt="<?= sysTranslations::get('news_add_tooltip') ?>"><?= sysTranslations::get('news_add') ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted" style="width: 30px;">&nbsp;</th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_date') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_title') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('news_online_from') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('news_online_to') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aNewsItems AS $oNewsItem) {
        echo '<tr>';
        echo '<td>';

        # online offline button
        echo '<a id="newsItem_' . $oNewsItem->newsItemId . '_online_1" title="' . sysTranslations::get(
                'news_set_online'
            ) . '" class="action_icon ' . ($oNewsItem->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oNewsItem->newsItemId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="newsItem_' . $oNewsItem->newsItemId . '_online_0" title="' . sysTranslations::get(
                'news_set_offline'
            ) . '" class="action_icon ' . ($oNewsItem->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oNewsItem->newsItemId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';

        echo '</td>';
        echo '<td>' . Date::strToDate($oNewsItem->date)
                ->format('%d-%m-%Y') . '</td>';
        echo '<td>' . _e($oNewsItem->title) . '</td>';
        echo '<td>' . ($oNewsItem->onlineFrom ? Date::strToDate($oNewsItem->onlineFrom)
                ->format('%d-%m-%Y %H:%M') : '') . '</td>';
        echo '<td>' . ($oNewsItem->onlineTo ? Date::strToDate($oNewsItem->onlineTo)
                ->format('%d-%m-%Y %H:%M') : '') . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('news_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('news_delete') . '" onclick="return confirmChoice(\'' . $oNewsItem->title . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/verwijderen/' . $oNewsItem->newsItemId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (count($aNewsItems) == 0) {
        echo '<tr><td colspan="6"><i>' . sysTranslations::get('news_no_news') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="6">
            <form method="POST">
                <?= generatePaginationHTML($iPageCount, $iCurrPage) ?>
                <input type="hidden" name="setPerPage" value="1"/>
                <select name="perPage" onchange="$(this).closest('form').submit();">
                    <option value="<?= $iNrOfRecords ?>"><?= sysTranslations::get('global_all') ?></option>
                    <option <?= $iPerPage == 10 ? 'SELECTED' : '' ?> value="10">10</option>
                    <option <?= $iPerPage == 25 ? 'SELECTED' : '' ?> value="25">25</option>
                    <option <?= $iPerPage == 50 ? 'SELECTED' : '' ?> value="50">50</option>
                    <option <?= $iPerPage == 100 ? 'SELECTED' : '' ?> value="100">100</option>
                </select> <?= sysTranslations::get('global_per_page') ?>
            </form>
        </td>
    </tr>
    </tfoot>
</table>
<?php

$sNewIsOnlineMsg   = sysTranslations::get('news_is_online');
$sNewIsOfflineMsg  = sysTranslations::get('news_is_offline');
$sNewNotChangedMsg = sysTranslations::get('news_not_changed');
# add ajax code for online/offline handling
$sOnlineOfflineJavascript = <<<EOT
<script>
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: this.href,
            data: "ajax=1",
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if(dataObj.success == true){
                    $("#newsItem_"+dataObj.newsItemId+"_online_0").hide(); // hide offline button
                    $("#newsItem_"+dataObj.newsItemId+"_online_1").hide(); // hide online button
                    $("#newsItem_"+dataObj.newsItemId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sNewIsOfflineMsg");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sNewIsOnlineMsg");                            
                }else{
                        showStatusUpdate("$sNewNotChangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sOnlineOfflineJavascript);
?>