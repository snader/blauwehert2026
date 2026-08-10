<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?></legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><label for="q"><?= sysTranslations::get('global_search_word') ?></label></td>
                <td><input class="default" type="text" id="q" name="FAQItemFilter[q]" value="<?= _e($aFAQItemFilter['q']) ?>"/></td>
            </tr>
            <?php if (class_exists('FAQItemCategoryManager')) { ?>
                <tr>
                    <td class="withLabel"><label for="faqItemCategoryId"><?= ucfirst(sysTranslations::get('faq_category_menu')) ?></label></td>
                    <td>
                        <select class="default" name="FAQItemFilter[faqItemCategoryId]" id="faqItemCategoryId">
                            <option value="">-- alle <?= sysTranslations::get('faq_category_menu') ?> --</option>
                            <?php

                            foreach (FAQItemCategoryManager::getFAQItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]) AS $oFAQItemCategory) {
                                echo '<option ' . ($aFAQItemFilter['faqItemCategoryId'] == $oFAQItemCategory->faqItemCategoryId ? 'selected' : '') . ' value="' . $oFAQItemCategory->faqItemCategoryId . '">' . $oFAQItemCategory->name . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterFAQItems" value="<?= sysTranslations::get('faq_filter') ?>"/> <input type="submit" name="resetFilter" value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
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
        <td colspan="6">
            <h2><?= sysTranslations::get('faq_all') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('faq_add_tooltip') ?>"
                   alt="<?= sysTranslations::get('faq_add_tooltip') ?>"><?= sysTranslations::get('faq_add') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted" style="width: 30px;">&nbsp;</th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_title') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aFAQItems AS $oFAQItem) {
        echo '<tr>';
        echo '<td>';

        # online offline button
        echo '<a id="FAQItem_' . $oFAQItem->faqItemId . '_online_1" title="' . sysTranslations::get('faq_set_online') . '" class="action_icon ' . ($oFAQItem->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oFAQItem->faqItemId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="FAQItem_' . $oFAQItem->faqItemId . '_online_0" title="' . sysTranslations::get('faq_set_offline') . '" class="action_icon ' . ($oFAQItem->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oFAQItem->faqItemId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';

        echo '</td>';
        echo '<td>' . _e($oFAQItem->question) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('faq_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oFAQItem->faqItemId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('faq_delete') . '" onclick="return confirmChoice(\'' . $oFAQItem->question . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/verwijderen/' . $oFAQItem->faqItemId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (count($aFAQItems) == 0) {
        echo '<tr><td colspan="6"><i>' . sysTranslations::get('faq_no_FAQ') . '</i></td></tr>';
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

$sNewIsOnlineMsg   = sysTranslations::get('faq_is_online');
$sNewIsOfflineMsg  = sysTranslations::get('faq_is_offline');
$sNewNotChangedMsg = sysTranslations::get('faq_not_changed');
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
                    $("#FAQItem_"+dataObj.faqItemId+"_online_0").hide(); // hide offline button
                    $("#FAQItem_"+dataObj.faqItemId+"_online_1").hide(); // hide online button
                    $("#FAQItem_"+dataObj.faqItemId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
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