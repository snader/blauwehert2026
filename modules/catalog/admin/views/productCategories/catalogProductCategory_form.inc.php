<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_category_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('catalog_category') ?></legend>
                <table class="withForm" style="width: 100%;">
                    <tr>
                        <td style="width: 116px;"><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_category_online_offline_tooltip') ?>" type="radio" <?= $oProductCategory->online ? 'CHECKED' : '' ?> id="online_1" name="online"
                                   value="1"/> <label for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_category_online_offline_tooltip') ?>" type="radio" <?= !$oProductCategory->online ? 'CHECKED' : '' ?> id="online_0" name="online"
                                   value="0"/> <label for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oProductCategory->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <div class="clearfix">
                                <div class="tabs">
                                    <div class="tabsHolder cf unselectable"></div>
                                    <?php

                                    $sJS = '';
                                    foreach (AdminLocales::getLanguages() as $oLanguage) {
                                        $oTranslation = $oProductCategory->getTranslations($oLanguage->languageId);
                                        ?>
                                        <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                            <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                            <div class="tabContent">
                                                <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                    <tr>
                                                        <td class="withLabel" style="width: 116px;"><label for="name_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('catalog_category_name') ?> *</label></td>
                                                        <td><input id="name_<?= $oLanguage->languageId ?>" class="required autofocus default"
                                                                   title="<?= sysTranslations::get('global_set_name') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`" type="text"
                                                                   name="name[<?= $oLanguage->languageId ?>]" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->name) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oProductCategory->isPropValid("name_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><label for="content_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_content') ?></label></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><textarea name="content[<?= $oLanguage->languageId ?>]" id="content_<?= $oLanguage->languageId ?>" class="tiny_MCE_default tiny_MCE"><?= (is_numeric(
                                                                    http_get('param2')
                                                                ) && $oTranslation ? _e($oTranslation->content) : '') ?></textarea></td>
                                                    </tr>
                                                    <?php if ($oCurrentUser->isSEO()) { ?>
                                                        <tr>
                                                            <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_seo') ?></h2></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="withLabel"><label for="windowTitle_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_window_title') ?></label>
                                                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_title_seo') ?>">&nbsp;</div>
                                                            </td>
                                                            <td colspan="2">
                                                                <div class="inline-block">
                                                                    <input class="default charCounterWindowTitle_<?= $oLanguage->languageId ?>" id="windowTitle_<?= $oLanguage->languageId ?>" type="text"
                                                                           maxlength="255" name="windowTitle[<?= $oLanguage->languageId ?>]"
                                                                           value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->windowTitle) : '') ?>"/>
                                                                    <div id="windowTitleCounter_<?= $oLanguage->languageId ?>" style="text-align: right;"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="metaDescription_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_meta_description') ?></label>
                                                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_description_seo') ?>">&nbsp;</div>
                                                            </td>
                                                            <td colspan="2">
                                                                <div class="inline-block">
                                                                    <textarea class="charCounterMetaDescription_<?= $oLanguage->languageId ?> default" id="metaDescription_<?= $oLanguage->languageId ?>"
                                                                              maxlength="255" name="metaDescription[<?= $oLanguage->languageId ?>]"><?= (is_numeric(http_get('param2')) && $oTranslation ? _e(
                                                                            $oTranslation->metaDescription
                                                                        ) : '') ?></textarea>
                                                                    <div id="metaDescriptionCounter_<?= $oLanguage->languageId ?>" style="text-align: right;"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="withLabel"><label for="metaKeywords_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_keywords') ?></label>
                                                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_keywords_seo') ?>">&nbsp;</div>
                                                            </td>
                                                            <td colspan="2">
                                                                <div class="inline-block"><input class="default charCounterMetaKeywords_<?= $oLanguage->languageId ?>" id="metaKeywords_<?= $oLanguage->languageId ?>" type="text"
                                                                                                 maxlength="255" name="metaKeywords[<?= $oLanguage->languageId ?>]"
                                                                                                 value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->metaKeywords) : '') ?>"/> (<?= sysTranslations::get(
                                                                        'global_optional'
                                                                    ) ?>)
                                                                    <div id="metaKeywordsCounter_<?= $oLanguage->languageId ?>" style="text-align: right;"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="withLabel"><label for="urlPart_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_seo_url') ?></label>
                                                                <div class="hasTooltip tooltip"
                                                                     title="<?= sysTranslations::get('global_seo_url_tooltip') ?> <?= CLIENT_HTTP_URL ?><?= $oProductCategory->parentCatalogProductCategoryId ? $oProductCategory->getParent()
                                                                         ->getTranslations('auto-admin')
                                                                         ->getUrlPath() : '' ?><?= sysTranslations::get('global_seo_url_tooltip_2') ?>">&nbsp;
                                                                </div>
                                                            </td>
                                                            <td><input class="default" id="urlPart_<?= $oLanguage->languageId ?>" type="text" name="urlPart[<?= $oLanguage->languageId ?>]"
                                                                       value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->getUrlPart()) : '') ?>"/></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                        <?php

                                        $sJS .= <<<EOT
initCharCounterWindowTitle({$oLanguage->languageId});
initCharCounterMetaDescription({$oLanguage->languageId});
initCharCounterMetaKeywords({$oLanguage->languageId});

EOT;
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
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
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_category_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$jsLang = empty($oCurrentUser) ? 'nl' : $oCurrentUser->getLanguage()->abbr;
$oPageLayout->addJavascript(
    '
<script>
    initTinyMCE(".tiny_MCE_default", "/admin/paginas/link-list", undefined, undefined, undefined, undefined, "' . $jsLang . '");
        
    // initiate prestatie tabs
    $(".tabs").prestatieTabs();
</script>
'
);
$oPageLayout->addJavascript($sJS);
?>