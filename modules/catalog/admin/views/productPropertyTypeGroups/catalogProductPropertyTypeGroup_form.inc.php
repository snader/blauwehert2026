<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_property_group_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('catalog_property_group') ?>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_property_group_tooltip_set') ?>">&nbsp;</div>
                </legend>
                <table class="withForm">
                    <tr>
                        <td class="withLabel"><label for="catalogProductTypeId"><?= sysTranslations::get('catalog_product_type') ?> *</label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_product_type_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <select id="catalogProductTypeId" class="required default" title="<?= sysTranslations::get('catalog_select_type') ?>" name="catalogProductTypeId">
                                <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                <?php

                                foreach (CatalogProductTypeManager::getAllProductTypes() as $oProductType) {
                                    echo '<option value="' . $oProductType->catalogProductTypeId . '"' . ($oProductType->catalogProductTypeId == $oProductPropertyTypeGroup->catalogProductTypeId ? ' selected' : '') . '>' . _e(
                                            $oProductType->getTranslations('auto-admin')->title
                                        ) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oProductPropertyTypeGroup->isPropValid("type") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="clearfix">
                                <div class="tabs">
                                    <div class="tabsHolder cf unselectable"></div>
                                    <?php

                                    foreach (AdminLocales::getLanguages() as $oLanguage) {
                                        $oTranslation = $oProductPropertyTypeGroup->getTranslations($oLanguage->languageId);
                                        ?>
                                        <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                            <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                            <div class="tabContent">
                                                <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                    <tr>
                                                        <td class="withLabel" style="width: 125px;"><label for="title_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_name') ?> *</label></td>
                                                        <td><input id="title_<?= $oLanguage->languageId ?>" class="required autofocus default" name="title[<?= $oLanguage->languageId ?>]"
                                                                   title="<?= sysTranslations::get('global_set_name') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`" type="text"
                                                                   value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->title) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oProductPropertyTypeGroup->isPropValid("title_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_property_group_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>

<?php

$sBottomJavascript = <<<EOT
<script>
// initiate prestatie tabs
$(".tabs").prestatieTabs();
</script>
EOT;

$oPageLayout->addJavascript($sBottomJavascript);