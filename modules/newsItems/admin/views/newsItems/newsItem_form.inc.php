<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('news_news_item') ?></legend>
                <table class="withForm" style="width: 100%;">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('news_set_online_tooltip') ?>" type="radio" <?= $oNewsItem->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('news_set_online_tooltip') ?>" type="radio" <?= !$oNewsItem->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oNewsItem->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="date"><?= sysTranslations::get('global_date') ?> *</label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('news_date_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <input title="<?= sysTranslations::get('global_date_tooltip') ?>" size="9" id="date" class="datePickerDefault hasDatePicker required dateNL" type="text" name="date"
                                   value="<?= $oNewsItem->date ? Date::strToDate($oNewsItem->date)
                                       ->format("%d-%m-%Y") : Date::strToDate('NOW')
                                       ->format("%d-%m-%Y") ?>"/>
                        </td>
                        <td><span class="error"><?= $oNewsItem->isPropValid("date") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" style="width: 120px;"><label for="title"><?= sysTranslations::get('global_title') ?> *</label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('news_title_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td><input id="title" class="required autofocus default" title="<?= sysTranslations::get('news_enter_title_tooltip') ?>" type="text" autocomplete="off" name="title" value="<?= _e($oNewsItem->title) ?>"/></td>
                        <td><span class="error"><?= $oNewsItem->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="onlineFromDate"><?= sysTranslations::get('news_online_from') ?> *</label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('news_online_from_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <input title="<?= sysTranslations::get('global_date_tooltip') ?>" size="9" id="onlineFromDate" class="datePickerDefault hasDatePicker required dateNL" type="text" name="onlineFromDate"
                                   value="<?= $oNewsItem->onlineFrom ? Date::strToDate($oNewsItem->onlineFrom)
                                       ->format("%d-%m-%Y") : Date::strToDate('NOW')
                                       ->format("%d-%m-%Y") ?>"/>
                            <input title="<?= sysTranslations::get('global_time_tooltip') ?>" size="3" id="onlineFromTime" class="timePickerDefault hasTimePicker required time" type="text" name="onlineFromTime"
                                   value="<?= $oNewsItem->onlineFrom ? Date::strToDate($oNewsItem->onlineFrom)
                                       ->format("%H:%M") : Date::strToDate('NOW')
                                       ->format("%H:%M") ?>"/>
                        </td>
                        <td><span class="error"><?= $oNewsItem->isPropValid("onlineFrom") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="onlineToDate"><?= sysTranslations::get('news_online_to') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('news_online_to_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <input title="<?= sysTranslations::get('global_date_tooltip') ?>" size="9" id="onlineToDate" class="datePickerDefault hasDatePicker dateNL" data-rule-required="#onlineToTime:filled" type="text"
                                   name="onlineToDate" value="<?= $oNewsItem->onlineTo ? Date::strToDate($oNewsItem->onlineTo)
                                ->format("%d-%m-%Y") : '' ?>"/>
                            <input title="<?= sysTranslations::get('global_time_tooltip') ?>" size="3" id="onlineToTime" class="timePickerDefault hasTimePicker time" data-rule-required="#onlineToDate:filled" type="text" name="onlineToTime"
                                   value="<?= $oNewsItem->onlineTo ? Date::strToDate($oNewsItem->onlineTo)
                                       ->format("%H:%M") : '' ?>"/>
                        </td>
                        <td></td>
                    </tr>
                    <?php

                    if (class_exists('NewsItemCategoryManager')) {
                        $aNewsItemCategories = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
                        ?>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3"><h2><?= sysTranslations::get('news_related_categories') ?></h2></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php

                                # set current categories in an array to check whether to select/check the options
                                $aNewsItemCategoryIds = [];
                                if (empty($aNewsItemCategories)) {
                                    echo '<i>' . sysTranslations::get('news_add_categories') . '</i>';
                                } else {
                                    foreach ($oNewsItem->getCategories('all') AS $oNewsItemCategory) {
                                        $aNewsItemCategoryIds[] = $oNewsItemCategory->newsItemCategoryId;
                                    }

                                    echo '<ul style="list-style: none; margin: 0; padding: 0;">';
                                    foreach ($aNewsItemCategories as $oNewsItemCategory) {
                                        echo '<li><input class="alignCheckbox required" title="' . sysTranslations::get(
                                                'news_related_categories_tooltip'
                                            ) . '" id="newsItemCategory_' . $oNewsItemCategory->newsItemCategoryId . '" type="checkbox" name="newsItemCategoryIds[]" value="' . $oNewsItemCategory->newsItemCategoryId . '" ' . ((count(
                                                    $aNewsItemCategories
                                                ) == 1 || in_array($oNewsItemCategory->newsItemCategoryId, $aNewsItemCategoryIds)) ? 'checked' : '') . ' /> <label for="newsItemCategory_' . $oNewsItemCategory->newsItemCategoryId . '">' . _e(
                                                $oNewsItemCategory->name
                                            ) . '</label>';
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </td>
                            <td><span class="error"><?= $oNewsItem->isPropValid("categories") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <?php

                    }
                    ?>
                    <tr>
                        <td colspan="3" style="padding-top: 20px;"><label for="intro"><?= sysTranslations::get('news_intro') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('news_intro_tooltip') ?>">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="intro" id="intro" class="tiny_MCE_default tiny_MCE intro"><?= $oNewsItem->intro ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top: 20px;"><label for="content"><?= sysTranslations::get('news_news_item_2') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('news_news_item_2_tooltip') ?>">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="content" id="content" class="tiny_MCE_default tiny_MCE"><?= $oNewsItem->content ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="source"><?= sysTranslations::get('news_source') ?></label></td>
                        <td><input class="default" id="source" type="text" name="source" value="<?= _e($oNewsItem->source) ?>"/></td>
                        <td></td>
                    </tr>
                    <?php if ($oCurrentUser->isSEO()) { ?>
                        <tr>
                            <td colspan="3"><h2><?= sysTranslations::get('user_seo') ?></h2></td>
                        </tr>
                        <?php

                        if ($oNewsItem->newsItemId) {
                            $aLocales = $oNewsItem->getLocales();
                            ?>
                            <tr>
                                <td><?= sysTranslations::get('global_current_url') ?></td>
                                <td>
                                    <?php

                                    foreach ($aLocales as $oLocale) {
                                        echo getBaseUrl($oLocale) . $oNewsItem->getUrlPath() . '<br />';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="withLabel"><label for="windowTitle"><?= sysTranslations::get('global_window_title') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_title_seo') ?>">&nbsp;</div>
                            </td>
                            <td colspan="2">
                                <div class="inline-block">
                                    <input class="default charCounterWindowTitle" id="windowTitle" type="text" maxlength="255" name="windowTitle" value="<?= _e($oNewsItem->windowTitle) ?>"/>
                                    <div id="windowTitleCounter" style="text-align: right;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="metaDescription"><?= sysTranslations::get('global_description') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_description_seo') ?>">&nbsp;</div>
                            </td>
                            <td colspan="2">
                                <div class="inline-block">
                                    <textarea class="default charCounterMetaDescription" id="metaDescription" maxlength="255" name="metaDescription"><?= _e($oNewsItem->metaDescription) ?></textarea>
                                    <div id="metaDescriptionCounter" style="text-align: right;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="metaKeywords"><?= sysTranslations::get('global_keywords') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_keywords_seo') ?>">&nbsp;</div>
                            </td>
                            <td colspan="2">
                                <div class="inline-block"><input class="default charCounterMetaKeywords" id="metaKeywords" type="text" maxlength="255" name="metaKeywords" value="<?= _e($oNewsItem->metaKeywords) ?>"/>
                                    <div id="metaKeywordsCounter" style="text-align: right;"></div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="3">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
    <?php
    if (!empty($oNewsItem->newsItemId)) {
        /** @var $oAutocompleteManager AutocompleteManager */
        foreach ($aAutocompleters as $oAutocompleteManager) {
            echo $oAutocompleteManager->includeTemplate();
        }
    }
    ?>

    <?php if (moduleExists('pages')) { ?>
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('global_preview') ?></legend>
                <?php

                if (!$oNewsItem->newsItemId) {
                    echo sysTranslations::get('news_item_save_first');
                } else {
                    echo '<p>' . sysTranslations::get('news_item_preview') . '</p>';
                    echo '<a target="_blank" class="btn-default" href="' . $oNewsItem->getBaseUrlPath() . '?preview=1">' . sysTranslations::get('global_preview') . '</a>';
                }
                ?>
            </fieldset>
        </div>
    <?php } ?>

    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('global_images') ?></legend>
            <?php

            if ($oNewsItem->newsItemId !== null) {

                $oImageManagerHTML->includeTemplate();
            } else {
                echo '<p><i>' . sysTranslations::get('news_images_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('global_files') ?></legend>
            <?php

            if ($oNewsItem->newsItemId !== null) {
                $oFileManagerHTML->includeTemplate();
            } else {
                echo '<p><i>' . sysTranslations::get('news_files_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('global_links') ?></legend>
            <?php

            if ($oNewsItem->newsItemId !== null) {
                $oLinkManagerHTML->includeTemplate();
            } else {
                echo '<p><i>' . sysTranslations::get('news_links_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('global_videolinks') ?></legend>
            <?php

            if ($oNewsItem->newsItemId !== null) {
                $oVideoLinkManagerHTML->includeTemplate();
            } else {
                echo '<p><i>' . sysTranslations::get('news_video_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$jsLang = empty($oCurrentUser) ? 'nl' : $oCurrentUser->getLanguage()->abbr;
$oPageLayout->addJavascript(
    '
<script>
    initTinyMCE(".tiny_MCE_default.intro", "/admin/paginas/link-list" , undefined, undefined, undefined, undefined, "' . $jsLang . '");
    initTinyMCE(".tiny_MCE_default:not(.intro)", "/admin/paginas/link-list", "/admin/nieuws/image-list/' . $oNewsItem->newsItemId . '", undefined, undefined, undefined, "' . $jsLang . '");
</script>
'
);
?>