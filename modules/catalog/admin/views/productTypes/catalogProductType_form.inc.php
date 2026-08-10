<?php

$bHasProducts = CatalogProductTypeManager::getNumberOfProductsByProductTypeId($oProductType->catalogProductTypeId) > 0;
?>
    <div id="topOptions">
        <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_back_overview_product_type') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>
            )</span>
    </div>
    <div class="cf">
        <div class="contentColumn">
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <fieldset>
                    <legend><?= sysTranslations::get('catalog_product_type') ?></legend>
                    <table class="withForm">
                        <tr>
                            <td colspan="3">
                                <div class="clearfix">
                                    <div class="tabs">
                                        <div class="tabsHolder cf unselectable"></div>
                                        <?php

                                        foreach (AdminLocales::getLanguages() as $oLanguage) {
                                            $oTranslation = $oProductType->getTranslations($oLanguage->languageId);
                                            ?>
                                            <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                                <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                                <div class="tabContent">
                                                    <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                        <tr>
                                                            <td class="withLabel" style="width: 116px;"><label for="title_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('catalog_product_type') ?> *</label></td>
                                                            <td><input class="required default" name="title[<?= $oLanguage->languageId ?>]"
                                                                       title="<?= sysTranslations::get('catalog_set_product_type_tooltip') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`"
                                                                       type="text" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->title) : '') ?>"/></td>
                                                            <td><span class="error"><?= $oProductType->isPropValid("title_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <?php

                                        }
                                        ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="withSizes"><?= sysTranslations::get('catalog_with_sizes') ?> *</label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_with_sizes_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <?php if (!$bHasProducts) { ?>
                                    <input class="alignRadio required" title="<?= sysTranslations::get('catalog_set_with_sizes') ?>" type="radio" <?= $oProductType->withSizes ? 'CHECKED' : '' ?> id="withSizes_1" name="withSizes" value="1"/>
                                    <label for="withSizes_1"><?= sysTranslations::get('global_yes') ?> </label>
                                    <input class="alignRadio required" title="<?= sysTranslations::get('catalog_unset_with_sizes') ?>" type="radio" <?= !$oProductType->withSizes ? 'CHECKED' : '' ?> id="withSizes_0" name="withSizes"
                                           value="0"/> <label for="withSizes_0"><?= sysTranslations::get('global_no') ?></label>
                                <?php } else { ?>
                                    <?= $oProductType->withSizes ? sysTranslations::get('global_yes') : sysTranslations::get('global_no') ?>
                                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_product_type_warning') ?>">&nbsp;</div>
                                <?php } ?>
                            </td>
                            <td><span class="error"><?= $oProductType->isPropValid("withSizes") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>

                        <tr>
                            <td><label for="withColors"><?= sysTranslations::get('catalog_with_colors') ?> *</label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_with_colors_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <?php if (!$bHasProducts) { ?>
                                    <input class="alignRadio required" title="<?= sysTranslations::get('catalog_set_with_colors') ?>" type="radio" <?= $oProductType->withColors ? 'CHECKED' : '' ?> id="withColors_1" name="withColors"
                                           value="1"/> <label for="withColors_1"><?= sysTranslations::get('global_yes') ?></label>
                                    <input class="alignRadio required" title="<?= sysTranslations::get('catalog_unset_with_colors') ?>" type="radio" <?= !$oProductType->withColors ? 'CHECKED' : '' ?> id="withColors_0" name="withColors"
                                           value="0"/> <label for="withColors_0"><?= sysTranslations::get('global_no') ?></label>
                                <?php } else { ?>
                                    <?= $oProductType->withColors ? sysTranslations::get('global_yes') : sysTranslations::get('global_no') ?>
                                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_product_type_warning') ?>">&nbsp;</div>
                                <?php } ?>
                            </td>
                            <td><span class="error"><?= $oProductType->isPropValid("withColors") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>

                        <!-- With Gender -->
                        <tr>
                            <td><label for="withGenders"><?= sysTranslations::get('catalog_with_genders') ?> *</label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_with_genders_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <?php if (!$bHasProducts) { ?>
                                    <input class="alignRadio required" title="<?= sysTranslations::get('catalog_set_with_genders') ?>" type="radio" <?= $oProductType->withGenders ? 'CHECKED' : '' ?> id="withGenders" name="withGenders"
                                           value="1"/> <label for="withGenders_1"><?= sysTranslations::get('global_yes') ?></label>
                                    <input class="alignRadio required" title="<?= sysTranslations::get('catalog_unset_with_genders') ?>" type="radio" <?= !$oProductType->withGenders ? 'CHECKED' : '' ?> id="withGenders_0" name="withGenders"
                                           value="0"/> <label for="withGenders_0"><?= sysTranslations::get('global_no') ?></label>
                                <?php } else { ?>
                                    <?= $oProductType->withGenders ? sysTranslations::get('global_yes') : sysTranslations::get('global_no') ?>
                                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_product_type_warning') ?>">&nbsp;</div>
                                <?php } ?>
                            </td>
                            <td><span class="error"><?= $oProductType->isPropValid("withGenders") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
        <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_back_overview_product_type') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>
            )</span>
    </div>

<?php

$sBottomJavascript = <<<EOT
<script>
// initiate prestatie tabs
$(".tabs").prestatieTabs();
</script>
EOT;

$oPageLayout->addJavascript($sBottomJavascript);