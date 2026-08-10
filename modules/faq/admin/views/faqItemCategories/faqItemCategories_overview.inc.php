<table class="sorted withActionIcons">
    <thead>
    <tr class="topRow">
        <td colspan="6">
            <?php include_once getAdminSnippet('localeSelect'); ?>
        </td>
    </tr>
    <tr class="topRow">
        <td colspan="3">
            <h2><?= sysTranslations::get('faq_all_categories') ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('faq_category_add') ?>"
                   alt="<?= sysTranslations::get('faq_category_add') ?>"><?= sysTranslations::get('faq_category_add') ?></a><br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen" title="<?= sysTranslations::get('global_change_order') ?>"
                   alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get('global_change_order') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline">&nbsp;</th>
        <th><?= sysTranslations::get('faq_category_name') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 100px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aFAQItemCategories AS $oFAQItemCategory) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        echo '<a id="FAQItemCategory_' . $oFAQItemCategory->faqItemCategoryId . '_online_1" title="' . sysTranslations::get(
                'faq_category_set_offline_&'. CSRFSynchronizerToken::query() .'tooltip'
            ) . '" class="action_icon ' . ($oFAQItemCategory->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oFAQItemCategory->faqItemCategoryId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="FAQItemCategory_' . $oFAQItemCategory->faqItemCategoryId . '_online_0" title="' . sysTranslations::get(
                'faq_category_set_online_tooltip'
            ) . '" class="action_icon ' . ($oFAQItemCategory->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oFAQItemCategory->faqItemCategoryId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . _e($oFAQItemCategory->name) . '</td>';
        echo '<td>';

        if (!empty($oFAQItemCategory->getFAQItems('all'))) {
            echo '<a class="action_icon change_order_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/faq-volgorde-bewerken/' . $oFAQItemCategory->faqItemCategoryId . '"></a>';
        }
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('faq_category_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oFAQItemCategory->faqItemCategoryId . '"></a>';
        if ($oFAQItemCategory->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('faq_category_delete') . '" onclick="return confirmChoice(\'' . _e($oFAQItemCategory->name) . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oFAQItemCategory->faqItemCategoryId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="' . sysTranslations::get('faq_category_not_deletable') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aFAQItemCategories)) {
        echo '<tr><td colspan="3"><i>' . sysTranslations::get('faq_no_categories') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>
<?php

# create necessary javascript
$sFAQCatOnlineMsg     = sysTranslations::get('faq_category_online');
$sFAQCatOfflineMsg    = sysTranslations::get('faq_category_offline');
$sFAQCatnotchangedMsg = sysTranslations::get('faq_category_not_changed');
$sController          = http_get('controller');
$sBottomJavascript    = <<<EOT
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
                    $("#FAQItemCategory_"+dataObj.faqItemCategoryId+"_online_0").hide(); // hide offline button
                    $("#FAQItemCategory_"+dataObj.faqItemCategoryId+"_online_1").hide(); // hide online button
                    $("#FAQItemCategory_"+dataObj.faqItemCategoryId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sFAQCatOfflineMsg");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sFAQCatOnlineMsg");                            
                } else {
                    showStatusUpdate("$sFAQCatnotchangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>