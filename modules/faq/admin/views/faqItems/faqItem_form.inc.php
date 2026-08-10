<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('faq_FAQ_item') ?></legend>
                <table class="withForm" style="width: 100%;">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('faq_set_online_tooltip') ?>" type="radio" <?= $oFAQItem->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('faq_set_online_tooltip') ?>" type="radio" <?= !$oFAQItem->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oFAQItem->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" style="width: 120px;"><label for="question"><?= sysTranslations::get('faq_question') ?> *</label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('faq_question_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td><input id="question" class="required autofocus default" title="<?= sysTranslations::get('faq_enter_question_tooltip') ?>" type="text" autocomplete="off" name="question" value="<?= _e($oFAQItem->question) ?>"/>
                        </td>
                        <td><span class="error"><?= $oFAQItem->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <?php

                    if (class_exists('FAQItemCategoryManager')) {
                        $aFAQItemCategories = FAQItemCategoryManager::getFAQItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
                        ?>
                        <tr>
                            <td colspan="3"><h2><?= sysTranslations::get('faq_related_categories') ?></h2></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php

                                # set current categories in an array to check whether to select/check the options
                                $afaqItemCategoryIds = [];
                                if (empty($aFAQItemCategories)) {
                                    echo '<i>' . sysTranslations::get('faq_add_categories') . '</i>';
                                } else {
                                    foreach ($oFAQItem->getCategories('all') AS $oFAQItemCategory) {
                                        $afaqItemCategoryIds[] = $oFAQItemCategory->faqItemCategoryId;
                                    }

                                    echo '<ul style="list-style: none; margin: 0; padding: 0;">';
                                    foreach ($aFAQItemCategories as $oFAQItemCategory) {
                                        echo '<li><input class="alignCheckbox required" title="' . sysTranslations::get(
                                                'faq_related_categories_tooltip'
                                            ) . '" id="FAQItemCategory_' . $oFAQItemCategory->faqItemCategoryId . '" type="checkbox" name="faqItemCategoryIds[]" value="' . $oFAQItemCategory->faqItemCategoryId . '" ' . (in_array(
                                                $oFAQItemCategory->faqItemCategoryId,
                                                $afaqItemCategoryIds
                                            ) ? 'CHECKED' : '') . ' /> <label for="FAQItemCategory_' . $oFAQItemCategory->faqItemCategoryId . '">' . _e($oFAQItemCategory->name) . '</label>';
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </td>
                            <td><span class="error"><?= $oFAQItem->isPropValid("categories") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <?php

                    }
                    ?>
                    <tr>
                        <td colspan="3" style="padding-top: 20px;"><label for="answer"><?= sysTranslations::get('faq_answer') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('faq_answer_tooltip') ?>">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="answer" id="answer" class="tiny_MCE_default tiny_MCE answer"><?= $oFAQItem->answer ?></textarea></td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
    <div id="bottomOptions">
        <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
    </div>
</div>
<?php

$jsLang = empty($oCurrentUser) ? 'nl' : $oCurrentUser->getLanguage()->abbr;
$oPageLayout->addJavascript(
    '
<script>
    initTinyMCE(".tiny_MCE_default.answer", "/admin/paginas/link-list" , undefined, undefined, undefined, undefined, "' . $jsLang . '");
</script>
'
);
?>