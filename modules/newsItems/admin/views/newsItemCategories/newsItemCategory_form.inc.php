<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('news_category') ?></legend>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('news_category_set_online') ?>" type="radio" <?= $oNewsItemCategory->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('news_category_set_online') ?>" type="radio" <?= !$oNewsItemCategory->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oNewsItemCategory->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" style="width: 116px;"><label for="name"><?= sysTranslations::get('global_name') ?> *</label></td>
                        <td><input id="name" class="required autofocus default" name="name" title="<?= sysTranslations::get('global_set_name') ?>" type="text" value="<?= _e($oNewsItemCategory->name) ?>"/></td>
                        <td><span class="error"><?= $oNewsItemCategory->isPropValid("name") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <?php if ($oCurrentUser->isSEO()) { ?>
                        <tr>
                            <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('user_seo') ?></h2></td>
                        </tr>
                        <?php

                        if ($oNewsItemCategory->newsItemCategoryId) {
                            $aLocales = $oNewsItemCategory->getLocales();
                            ?>
                            <tr>
                                <td><?= sysTranslations::get('global_current_url') ?></td>
                                <td>
                                    <?php

                                    foreach ($aLocales as $oLocale) {
                                        echo getBaseUrl($oLocale) . $oNewsItemCategory->getUrlPath() . '<br />';
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
                                    <input class="default charCounterWindowTitle" id="windowTitle" type="text" maxlength="255" name="windowTitle" value="<?= _e($oNewsItemCategory->windowTitle) ?>"/>
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
                                    <textarea cols="34" rows="5" class="charCounterMetaDescription default" id="metaDescription" maxlength="255" name="metaDescription"><?= _e($oNewsItemCategory->metaDescription) ?></textarea>
                                    <div id="metaDescriptionCounter" style="text-align: right;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="metaKeywords"><?= sysTranslations::get('global_keywords') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_keywords_seo') ?>">&nbsp;</div>
                            </td>
                            <td colspan="2">
                                <div class="inline-block"><input class="default charCounterMetaKeywords" id="metaKeywords" type="text" maxlength="255" name="metaKeywords" value="<?= _e($oNewsItemCategory->metaKeywords) ?>"/>
                                    <div id="metaKeywordsCounter" style="text-align: right;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="urlPartText"><?= sysTranslations::get('global_seo_url') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_seo_url_tooltip') ?> <?= CLIENT_HTTP_URL ?>/nieuws/<b>[url tekst]</b>">&nbsp;</div>
                            </td>
                            <td><input class="default" id="urlPart" type="text" name="urlPartText" value="<?= _e($oNewsItemCategory->getUrlPartText()) ?>"/></td>
                            <td></td>
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
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>