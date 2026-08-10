<div class="cf" id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">Terug naar het producten overzicht</a><span class="backBtnInfo"> (zonder opslaan)</span>
    <?php

    if (!empty($oProductPrev) || !empty($oProductNext)) {
        echo '<div style="float: right; line-height: 1.5em;">';
        if ($oProductPrev) {
            echo '<a href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductPrev->catalogProductId . '">&laquo; vorige</a> | ';
        }
        echo '<b style="font-size: 1.5em;">' . $oProduct->catalogProductId . '</b>';
        if ($oProductNext) {
            echo ' | <a href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductNext->catalogProductId . '">volgende &raquo;</a>';
        }
        echo '</div>';
    }
    ?>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('catalog_product') ?></legend>
                <table class="withForm" style="width: 100%;">
                    <tr>
                        <td style="width: 120px;"><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_online_offline_tooltip') ?>" type="radio" <?= $oProduct->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_online_offline_tooltip') ?>" type="radio" <?= !$oProduct->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('catalog_homepage') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_set_homepage') ?>" type="radio" <?= $oProduct->showOnHome ? 'CHECKED' : '' ?> id="showOnHome_1" name="showOnHome" value="1"/> <label
                                    for="showOnHome_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_set_homepage') ?>" type="radio" <?= !$oProduct->showOnHome ? 'CHECKED' : '' ?> id="showOnHome_0" name="showOnHome" value="0"/> <label
                                    for="showOnHome_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="catalogProductTypeId"><?= sysTranslations::get('catalog_product_type') ?> *</label></td>
                        <td>
                            <select id="catalogProductTypeId" <?= $oProduct->catalogProductId && $oProduct->catalogProductTypeId ? 'DISABLED' : '' ?> class="required default" title="<?= sysTranslations::get('catalog_select_type') ?>"
                                    name="catalogProductTypeId">
                                <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                <?php

                                foreach (CatalogProductTypeManager::getAllProductTypes() as $oProductType) {
                                    echo '<option data-withgenders="' . $oProductType->withGenders . '" value="' . $oProductType->catalogProductTypeId . '"' . ($oProductType->catalogProductTypeId == $oProduct->catalogProductTypeId ? ' selected' : '') . '>' . _e(
                                            $oProductType->getTranslations('auto-admin')->title
                                        ) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("catalogProductTypeId") ? '' : 'Veld niet (juist) ingevuld' ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="catalogBrandId"><?= sysTranslations::get('global_brand') ?> *</label></td>
                        <td>
                            <select id="catalogBrandId" class="required default" title="<?= sysTranslations::get('catalog_select_brand') ?> " name="catalogBrandId">
                                <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                <?php

                                foreach (CatalogBrandManager::getAllBrands() as $oBrand) {
                                    echo '<option value="' . $oBrand->catalogBrandId . '"' . ($oBrand->catalogBrandId == $oProduct->catalogBrandId ? ' selected' : '') . '>' . _e(
                                            $oBrand->getTranslations('auto-admin')->name
                                        ) . '</option>' . "\n";
                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("catalogBrandId") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <!-- Gender -->
                    <tr id="catalogProductGender">
                        <td style="width: 120px;"><?= sysTranslations::get('global_gender') ?> *</td>
                        <td>
                            <?php

                            foreach (CatalogProductManager::getAllGenders() as $key => $sGender) {
                                echo '<input class="alignRadio required" title="' . sysTranslations::get(
                                        'global_gender'
                                    ) . '" type="radio"' . ($oProduct->gender == $sGender ? "CHECKED" : "") . ' id="gender_' . $key . '" name="gender" value="' . $sGender . '" />';
                                echo ' <label for="gender_' . $key . '" style="margin-right: 5px;">' . CatalogProductManager::getLabelByGender($sGender) . '</label> ';
                            }
                            ?>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <!-- MPN -->
                    <tr>
                        <td class="withLabel"><?= sysTranslations::get('catalog_general_mpn') ?>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_general_mpn_tooltip') ?> ">&nbsp;</div>
                        </td>
                        <td><input type="text" class="default" name="catalogProductMPN" size="50" value="<?= $oProduct->catalogProductMPN ?>"/></td>
                    </tr>

                    <!-- Sale Price -->
                    <tr>
                        <td class="withLabel"><label for="salePriceWithTax"><?= sysTranslations::get('global_price') ?> *</label></td>
                        <td colspan="2">
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input <?= intval(Settings::get('taxIncluded')) ? '' : 'disabled' ?> size="6" id="salePriceWithTax" name="salePrice" class="priceFloatOnly required "
                                                                                                                                                     type="text"
                                                                                                                                                     value="<?= number_format(
                                                                                                                                                         $oProduct->getSalePrice(true, null, null, false, false),
                                                                                                                                                         2,
                                                                                                                                                         '.',
                                                                                                                                                         ''
                                                                                                                                                     ) ?>"/>
                            <span>(<?= sysTranslations::get('global_inc_taxes') ?>)</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="withLabel"><label for="salePrice"><?= sysTranslations::get('global_price') ?> *</label></td>
                        <td>
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input <?= intval(Settings::get('taxIncluded')) ? 'disabled' : '' ?> size="6" id="salePriceWithoutTax" name="salePrice" class="priceFloatOnly required"
                                                                                                                                                     title="Vul de prijs in" type="text"
                                                                                                                                                     name="salePriceWithoutTax" value="<?= number_format(
                                $oProduct->getSalePrice(false, null, null, false, false),
                                2,
                                '.',
                                ''
                            ) ?>"/>
                            <span>(<?= sysTranslations::get('global_excl_taxes') ?>)</span>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("salePrice") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <!-- Reduced Price -->
                    <tr>
                        <td class="withLabel"><label for="reducedPriceWithTax"><?= sysTranslations::get('catalog_reduced_price') ?> </label></td>
                        <td colspan="2">
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input <?= intval(Settings::get('taxIncluded')) ? '' : 'disabled' ?> size="6" id="reducedPriceWithTax" name="reducedPrice" class="priceFloatOnly" type="text"
                                                                                                                                                     value="<?= ($oProduct->getReducedPrice(true) !== null) ? number_format(
                                                                                                                                                         $oProduct->getReducedPrice(true),
                                                                                                                                                         2,
                                                                                                                                                         '.',
                                                                                                                                                         ''
                                                                                                                                                     ) : '' ?>"/>
                            <span>(<?= sysTranslations::get('global_inc_taxes') ?>)</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="withLabel"><label for="reducedPrice"><?= sysTranslations::get('catalog_reduced_price') ?> </label></td>
                        <td>
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input <?= intval(Settings::get('taxIncluded')) ? 'disabled' : '' ?> size="6" id="reducedPriceWithoutTax" name="reducedPrice" class="priceFloatOnly"
                                                                                                                                                     data-rule-min="0.001" title="Vul de prijs in"
                                                                                                                                                     type="text" name="formattedReducedPrice"
                                                                                                                                                     value="<?= ($oProduct->getReducedPrice(false) !== null) ? number_format(
                                                                                                                                                         $oProduct->getReducedPrice(false),
                                                                                                                                                         2,
                                                                                                                                                         '.',
                                                                                                                                                         ''
                                                                                                                                                     ) : '' ?>"/>
                            <span>(<?= sysTranslations::get('global_excl_taxes') ?>)</span>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("reducedPrice") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <!-- Pruchase Price -->
                    <tr>
                        <td class="withLabel"><label for="purchasePriceWithTax"><?= sysTranslations::get('catalog_purchase_price') ?> *</label></td>
                        <td colspan="2">
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input <?= intval(Settings::get('taxIncluded')) ? '' : 'disabled' ?> size="6" id="purchasePriceWithTax" name="purchasePrice" class="priceFloatOnly required "
                                                                                                                                                     type="text"
                                                                                                                                                     value="<?= number_format(
                                                                                                                                                         $oProduct->getPurchasePrice(true, null, null, false, false),
                                                                                                                                                         2,
                                                                                                                                                         '.',
                                                                                                                                                         ''
                                                                                                                                                     ) ?>"/>
                            <span>(<?= sysTranslations::get('global_inc_taxes') ?>)</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="withLabel"><label for="purchasePrice"><?= sysTranslations::get('catalog_purchase_price') ?> *</label></td>
                        <td>
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input <?= intval(Settings::get('taxIncluded')) ? 'disabled' : '' ?> size="6" id="purchasePriceWithoutTax" name="purchasePrice" class="priceFloatOnly required"
                                                                                                                                                     title="Vul de prijs in" type="text"
                                                                                                                                                     name="purchasePriceWithoutTax" value="<?= number_format(
                                $oProduct->getPurchasePrice(false, null, null, false, false),
                                2,
                                '.',
                                ''
                            ) ?>"/>
                            <span>(<?= sysTranslations::get('global_excl_taxes') ?>)</span>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("purchasePrice") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <!-- Tax percent -->
                    <tr>
                        <td class="withLabel"><label for="taxPercentageId"><?= sysTranslations::get('catalog_taxes_percentage') ?> *</label></td>
                        <td>
                            <select id="taxPercentageId" class="required" title="<?= sysTranslations::get('catalog_select_tax_percentage') ?>" name="taxPercentageId">
                                <?php

                                foreach (TaxManager::getAllPercentageIds() as $iTaxPercentageId) {
                                    echo '<option value="' . $iTaxPercentageId . '"' . ($iTaxPercentageId == $oProduct->taxPercentageId ? ' selected' : '') . '>' . TaxManager::getPercentageById($iTaxPercentageId, true) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oProduct->isPropValid("reducedPrice") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <!-- Categories -->
                    <tr>
                        <td colspan="3">
                            <b><?= sysTranslations::get('catalog_categories') ?> *</b> <span class="error"><?= $oProduct->isPropValid("catalogProductCategory") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            <div class="autoCheckParent">
                                <?php

                                function makeCategoryListTree(array $aProductCategories = [], array $aProductCategoryIds = [])
                                {
                                    echo '<ul class="nestedCheckboxes">';
                                    foreach ($aProductCategories AS $oProductCategory) {
                                        echo '<li><input id="productCategory_' . $oProductCategory->catalogProductCategoryId . '"' . ($oProductCategory->level == 1 ? ' class="required"' : '') . ' title="' . sysTranslations::get(
                                                'catalog_select_one_category'
                                            ) . '"  type="checkbox" name="catalogProductCategoryIds[]" value="' . $oProductCategory->catalogProductCategoryId . '" ' . (in_array(
                                                $oProductCategory->catalogProductCategoryId,
                                                $aProductCategoryIds
                                            ) ? 'CHECKED' : '') . ' /> <label for="productCategory_' . $oProductCategory->catalogProductCategoryId . '">' . _e($oProductCategory->getTranslations('auto-admin')->name) . '</label>';
                                        makeCategoryListTree($oProductCategory->getSubCategories('all'), $aProductCategoryIds); //call function recursive
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                }

                                # set current categories in an array to check whether to select/check the options
                                $aProductCategoryIds = [];
                                foreach ($oProduct->getCategories('all') AS $oProductCategory) {
                                    $aProductCategoryIds[] = $oProductCategory->catalogProductCategoryId;
                                }

                                makeCategoryListTree(CatalogProductCategoryManager::getProductCategoriesByFilter(['showAll' => true, 'level' => 1]), $aProductCategoryIds);
                                ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <div class="clearfix">
                                <div class="tabs">
                                    <div class="tabsHolder cf unselectable"></div>
                                    <?php

                                    $sJS = '';
                                    foreach (AdminLocales::getLanguages() as $oLanguage) {
                                        $oTranslation = $oProduct->getTranslations($oLanguage->languageId);
                                        ?>
                                        <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                            <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                            <div class="tabContent">
                                                <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">

                                                    <!-- Name -->
                                                    <tr>
                                                        <td class="withLabel" style="width: 150px;"><label for="name_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_name') ?> *</label></td>
                                                        <td><input id="name_<?= $oLanguage->languageId ?>" class="required default"
                                                                   title="<?= sysTranslations::get('global_set_name') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`" type="text"
                                                                   name="name[<?= $oLanguage->languageId ?>]" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->name) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oProduct->isPropValid("name_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                                                    </tr>

                                                    <!-- Google Category -->
                                                    <tr>
                                                        <td class="withLabel"><label for="googleCategory_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('catalog_google_category') ?></label></td>
                                                        <td><input class="default google_cat" data-languageid="<?= $oLanguage->languageId ?>" type="text" id="googleCategory_<?= $oLanguage->languageId ?>"
                                                                   name="googleCategory[<?= $oLanguage->languageId ?>]"
                                                                   title="<?= (is_numeric(http_get('param2')) && !empty($oTranslation->googleCategory) ? $oTranslation->googleCategory : sysTranslations::get(
                                                                       'catalog_select_google_category'
                                                                   )) ?>" value="<?= (is_numeric(http_get('param2')) && !empty($oTranslation->googleCategory) ? $oTranslation->googleCategory : '') ?>"/></td>
                                                    </tr>

                                                    <!-- Description -->
                                                    <tr>
                                                        <td colspan="3"><label for="description_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_description') ?></label></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><textarea name="description[<?= $oLanguage->languageId ?>]" id="description_<?= $oLanguage->languageId ?>" class="tiny_MCE_default tiny_MCE"><?= (is_numeric(
                                                                    http_get('param2')
                                                                ) && $oTranslation ? _e($oTranslation->description) : '') ?></textarea></td>
                                                    </tr>

                                                    <?php if ($oCurrentUser->isSEO()) { ?>
                                                        <tr>
                                                            <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_seo') ?> </h2></td>
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
                                                            <td><label for="metaDescription_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_description') ?></label>
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
                                                                <div class="inline-block">
                                                                    <input class="default charCounterMetaKeywords_<?= $oLanguage->languageId ?>" id="metaKeywords_<?= $oLanguage->languageId ?>" type="text" maxlength="255"
                                                                           name="metaKeywords[<?= $oLanguage->languageId ?>]"
                                                                           value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->metaKeywords) : '') ?>"/> (<?= sysTranslations::get('global_optional') ?>)
                                                                    <div id="metaKeywordsCounter_<?= $oLanguage->languageId ?>" style="text-align: right;"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="withLabel"><label for="urlPart_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_seo_url') ?></label>
                                                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_seo_url_tooltip') ?> <?= CLIENT_HTTP_URL ?><?= sysTranslations::get('global_seo_url_tooltip_2') ?>">
                                                                    &nbsp;
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
                            <input id="saveProductButton" type="button" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>

    <!-- Images -->
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('global_images') ?></legend>
            <?php

            if ($oProduct->catalogProductId !== null) {
                $oImageManagerHTML->includeTemplate();
            } else {
                echo '<p><i>' . sysTranslations::get('catalog_images_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>

    <!-- Proguct-Images-Color managament -->
    <?php $aCatalogProductColors = CatalogProductColorManager::getProductColorsByFilter() ?>
    <?php if ($oProduct->catalogProductId !== null && $oProduct->getProductType()->withColors && !empty($aCatalogProductColors)) { ?>
        <?php //if ($oProduct->catalogProductId !== null && $oProduct->getProductType()->withColors) { ?>
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('catalog_image_color_relation') ?>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_image_color_relation_tooltip') ?>">&nbsp;</div>
                </legend>
                <form name="productImageColorManagement" method="POST" class="validateForm">
                    <?= CSRFSynchronizerToken::field() ?>
                    <input type="hidden" name="action" value="addProductImageRelation"/>
                    <table style="margin-top: 5px;" class="withForm">
                        <tr>
                            <td class="withLabel"><?= ucfirst(sysTranslations::get('global_color')) ?>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_select_color') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select name="catalogProductColorId" class="catalogProductColor">
                                    <?php

                                    foreach ($aCatalogProductColors AS $key => $oCatalogProductColor) {
                                        if ($key == 0) {
                                            $colorId   = $oCatalogProductColor->catalogProductColorId;
                                            $colorName = $oCatalogProductColor->getTranslations('auto-admin')->name;
                                        }
                                        echo '<option value="' . $oCatalogProductColor->catalogProductColorId . '">' . $oCatalogProductColor->getTranslations('auto-admin')->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a class="fancyBoxLink fancybox.ajax" href=<?= ADMIN_FOLDER . '/' . http_get(
                                    'controller'
                                ) . '/bewerken-afbeelding-kleur-relatie?catalogProductId=' . $oProduct->catalogProductId . '&colorId=' . $colorId . '&colorName=' . $colorName ?>><?= sysTranslations::get('global_select_image') ?></a>
                            </td>
                        </tr>
                        <tr id="catalogProductImageColorImage" class="hide">
                            <td>
                                <div class='images'>
                                    <div class='placeholder'>
                                        <div class='imagePlaceholder'>
                                            <div class='centered'>
                                                <img/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="catalogProductImageColorImageId" name="imageId"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" name="addImageColorBtn" value="<?= sysTranslations::get('catalog_save_image_color_relation') ?>"/></td>
                        </tr>
                    </table>

                </form>

                <hr>
                <table class="sorted" style="margin-top: 5px; margin-bottom: 10px; width: 100%;">
                    <thead>
                    <tr class="topRow">
                        <td colspan="6">
                            <h2><?= sysTranslations::get('catalog_image_color_relations') ?>
                                <div style="float: right;"></div>
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <th><?= ucfirst(sysTranslations::get('global_color')) ?></th>
                        <th><?= ucfirst(sysTranslations::get('global_image')) ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $aFilter                     = [];
                    $aFilter['notNullColors']    = '1';
                    $aFilter['catalogProductId'] = $oProduct->catalogProductId;
                    $aImageProductRelationsGroup = [];

                    // I get all the Product-Image relations and I group them by color in an Array
                    foreach (CatalogProductImageRelationManager::getCatalogProductImageRelationsByFilter($aFilter) AS $key => $oImageProductRelation) {
                        $oImage     = ImageManager::getImageById($oImageProductRelation->imageId);
                        $oImageFile = $oImage->getImageFileByReference('detail');

                        $aImageProductRelationsGroup[$oImageProductRelation->catalogProductColorId][$key] = $oImageFile;
                    }

                    // I show all the colors and images
                    if (count($aImageProductRelationsGroup)) {
                        foreach ($aImageProductRelationsGroup as $catalogProductColorId => $oImageProductRelationGroup) {
                            ?>
                            <tr>
                                <td> <?= CatalogProductColorManager::getProductColorById($catalogProductColorId)
                                        ->getTranslations('auto-admin')->name ?></td>
                                <td>
                                    <?php foreach ($oImageProductRelationGroup as $oImageProductRelation) { ?>
                                        <div class='images-50'>
                                            <div class='placeholder-50'>
                                                <div class='imagePlaceholder-50'>
                                                    <div class='centered-50'>
                                                        <img src="<?= CatalogProduct::IMAGES_PATH . '/cms_thumb/' . $oImageProductRelation->name ?>" title="<?= $oImageProductRelation->title ?>"/>
                                                    </div>
                                                </div>
                                                <div class='actionsPlaceholder'>
                                                    <a onclick="return confirmChoice('deze koppeling');" class="action_icon delete_icon" href="<?= ADMIN_FOLDER . '/' . http_get(
                                                        'controller'
                                                    ) . '/verwijder-afbeeldingen-kleur-relatie?catalogProductId=' . $oProduct->catalogProductId . '&catalogProductImageId=' . $oImageProductRelation->imageId ?>"></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php

                    } else {
                        echo '<tr><td colspan="5"><i>' . sysTranslations::get('catalog_no_images') . ' </i></td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </fieldset>
        </div>
    <?php } ?>

    <!-- Stock managament -->
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('catalog_stock_management') ?>
                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_stock_management_tooltip') ?>">&nbsp;</div>
            </legend>
            <?php if ($oProduct->catalogProductId !== null) { ?>
                <?php if (!(!$oProduct->getProductType()->withSizes && !$oProduct->getProductType()->withColors && count($oProduct->getColorSizeRelations('all')) > 0)) { ?>
                    <form method="POST" class="validateForm">
                        <?= CSRFSynchronizerToken::field() ?>
                        <input type="hidden" name="action" value="addSizeColorRelation"/>
                        <table style="margin-top: 5px;" class="withForm">
                            <?php if ($oProduct->getProductType()->withSizes) { ?>
                                <tr>
                                    <td class="withLabel" style="width: 90px;"><?= ucfirst(sysTranslations::get('global_size')) ?>
                                        <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_stock_size_tooltip') ?> ">&nbsp;</div>
                                    </td>
                                    <td>
                                        <select name="catalogProductSizeId">
                                            <?php

                                            foreach (CatalogProductSizeManager::getProductSizesByFilter() AS $oCatalogProductSize) {
                                                echo '<option value="' . $oCatalogProductSize->catalogProductSizeId . '">' . $oCatalogProductSize->getTranslations('auto-admin')->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($oProduct->getProductType()->withColors) { ?>
                                <tr>
                                    <td class="withLabel"><?= ucfirst(sysTranslations::get('global_color')) ?>
                                        <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_stock_color_tooltip') ?>">&nbsp;</div>
                                    </td>
                                    <td>
                                        <select name="catalogProductColorId">
                                            <?php

                                            foreach (CatalogProductColorManager::getProductColorsByFilter() AS $oCatalogProductColor) {
                                                echo '<option value="' . $oCatalogProductColor->catalogProductColorId . '">' . $oCatalogProductColor->getTranslations('auto-admin')->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td class="withLabel"><?= sysTranslations::get('catalog_stock') ?>
                                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_stock_tooltip') ?>">&nbsp;</div>
                                </td>
                                <td><input class="numbersOnly" type="text" name="stock" size="3" value=""/></td>
                            </tr>
                            <?php if ($oProduct->getProductType()->withSizes || $oProduct->getProductType()->withColors) { ?>
                                <tr>
                                    <td class="withLabel"><?= sysTranslations::get('catalog_extra_price') ?>
                                        <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_extra_price_tooltip') ?>">&nbsp;</div>
                                    </td>
                                    <td><input class="priceFloatOnly" type="text" name="extraPrice" size="3" value="0.00"/></td>
                                </tr>
                            <?php } ?>
                            <?php if (($oProduct->getProductType()->withColors) || ($oProduct->getProductType()->withSizes)) { ?>
                                <tr>
                                    <td class="withLabel"><?= sysTranslations::get('catalog_mpn') ?>
                                        <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_general_mpn_tooltip') ?> ">&nbsp;</div>
                                    </td>
                                    <td><input type="text" name="catalogProductSizeColorMPN" size="50"/></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2"><input type="submit" name="addStockBtn" value="<?= sysTranslations::get('catalog_add_stock') ?>"/></td>
                            </tr>
                        </table>
                    </form>
                    <hr/>
                <?php } ?>
                <table class="sorted" style="margin-top: 5px; margin-bottom: 10px; width: 100%;">
                    <thead>
                    <tr class="topRow">
                        <td colspan="6">
                            <h2><?= sysTranslations::get('catalog_added_stock') ?>
                                <div style="float: right;"></div>
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <?php if ($oProduct->getProductType()->withSizes) { ?>
                            <th><?= sysTranslations::get('global_size') ?></th><?php } ?>
                        <?php if ($oProduct->getProductType()->withColors) { ?>
                            <th><?= sysTranslations::get('global_color') ?></th><?php } ?>
                        <th><?= sysTranslations::get('catalog_stock') ?></th>
                        <?php if ($oProduct->getProductType()->withSizes || $oProduct->getProductType()->withColors) { ?>
                            <th><?= sysTranslations::get('catalog_extra_price') ?></th><?php } ?>
                        <?php if ($oProduct->getProductType()->withSizes || $oProduct->getProductType()->withColors) { ?>
                            <th><?= sysTranslations::get('catalog_mpn') ?></th><?php } ?>
                        <th class="{sorter: false} nonSorted" style="width: 60px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($oProduct->getColorSizeRelations('all') AS $oColorSizeRelation) {
                        echo '<tr>';
                        if ($oProduct->getProductType()->withSizes) {
                            echo '<td>' . ($oColorSizeRelation->catalogProductSizeId ? $oColorSizeRelation->getSize()
                                    ->getTranslations('auto-admin')->name : '') . '</td>';
                        }
                        if ($oProduct->getProductType()->withColors) {
                            echo '<td>' . ($oColorSizeRelation->catalogProductColorId ? $oColorSizeRelation->getColor()
                                    ->getTranslations('auto-admin')->name : '') . '</td>';
                        }
                        echo '<td>' . ($oColorSizeRelation->stock === null ? 'onbeperkt' : $oColorSizeRelation->stock) . '</td>';
                        if ($oProduct->getProductType()->withSizes || $oProduct->getProductType()->withColors) {
                            echo '<td>' . $oColorSizeRelation->extraPrice . '</td>';
                        }
                        if ($oProduct->getProductType()->withSizes || $oProduct->getProductType()->withColors) {
                            echo '<td>' . $oColorSizeRelation->catalogProductSizeColorMPN . '</td>';
                        }
                        echo '<td><a class="action_icon edit_icon fancyBoxLink fancybox.ajax" href="' . ADMIN_FOLDER . '/' . http_get(
                                'controller'
                            ) . '/bewerken-maat-kleur-relatie?catalogProductId=' . $oColorSizeRelation->catalogProductId . '&catalogProductSizeId=' . $oColorSizeRelation->catalogProductSizeId . '&catalogProductColorId=' . $oColorSizeRelation->catalogProductColorId . '"></a><a onclick="return confirmChoice(\'deze regel\');" class="action_icon delete_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                                'controller'
                            ) . '/verwijder-maat-kleur-relatie?catalogProductId=' . $oColorSizeRelation->catalogProductId . '&catalogProductSizeId=' . $oColorSizeRelation->catalogProductSizeId . '&catalogProductColorId=' . $oColorSizeRelation->catalogProductColorId . '"></a></td>';
                        echo '</tr>';
                    }
                    if (count($oProduct->getColorSizeRelations('all')) == 0) {
                        echo '<tr><td colspan="6"><i>' . sysTranslations::get('catalog_no_size_color_relations') . '</i></td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <?php

            } else {
                echo '<p><i>' . sysTranslations::get('catalog_color_size_relation_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>

    <div class="contentColumn">
        <form method="POST">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" name="action" value="saveProductPropertyValues"/>
            <fieldset>
                <legend><?= sysTranslations::get('catalog_type_properties') ?></legend>
                <?php

                $a = [];
                if ($oProduct->catalogProductId === null || $oProduct->catalogProductTypeId === null) {
                    echo '<p><i>' . sysTranslations::get('catalog_type_warning') . '</i></p>';
                } else {
                    if (count($oProduct->getPropertyTypes()) > 0) {
                        # create the HTML for each CatalogProductPropertyTypeGroup related to this CatalogProduct
                        foreach ($oProduct->getPropertyTypeGroups() as $oPropertyTypeGroup) {
                            if (count($oPropertyTypeGroup->getPropertyTypes()) > 0) {
                                ?>
                                <h3><?= $oPropertyTypeGroup->getTranslations('auto-admin')->title ?></h3>
                                <table class="withForm " style="margin-bottom: 10px;">
                                    <?php

                                    # create HTML for each related CatalogProductPropertyType
                                    foreach ($oPropertyTypeGroup->getPropertyTypes() AS $oPropertyType) {
                                        # define the values to check for
                                        $aPropertyTypeValues = [];
                                        if (count($oPropertyType->getValuesByProductId($oProduct->catalogProductId, 'auto-admin')) > 0) {
                                            foreach ($oPropertyType->getValuesByProductId($oProduct->catalogProductId, 'auto-admin') AS $oPropertyValue) {
                                                $aPropertyTypeValues[] = $oPropertyValue->value;
                                            }
                                        }

                                        # create the HTML for a single text-input
                                        if ($oPropertyType->type == 'text') {
                                            if ($oPropertyType->inputTranslatable) {
                                                ?>
                                                <tr>
                                                    <td style="width: 140px;" class="withLabel"><label for="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId ?>"><?= $oPropertyType->getTranslations(
                                                                'auto-admin'
                                                            )->title ?></label></td>
                                                    <td colspan="2">
                                                        <div class="tabs">
                                                            <div class="tabsHolder cf unselectable"></div>
                                                            <?php

                                                            foreach (AdminLocales::getLanguages() as $oLanguage) {
                                                                ?>
                                                                <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                                                    <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                                                    <div class="tabContent">
                                                                        <?php $aValues = $oPropertyType->getValuesByProductId($oProduct->catalogProductId, $oLanguage->languageId) ?>
                                                                        <input class="default" id="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId ?>-<?= $oLanguage->languageId ?>" type="text"
                                                                               name="openFields[<?= $oPropertyType->catalogProductPropertyTypeId ?>][<?= $oLanguage->languageId ?>]"
                                                                               value="<?= (!empty($aValues) ? _e($aValues[0]->value) : '') ?>"/><br/>
                                                                    </div>
                                                                </div>
                                                                <?php

                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php

                                            } else {
                                                ?>
                                                <tr>
                                                    <td style="width: 140px;" class="withLabel"><label for="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId ?>"><?= $oPropertyType->getTranslations(
                                                                'auto-admin'
                                                            )->title ?></label></td>
                                                    <td colspan="2">
                                                        <input class="default" id="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId ?>" type="text" name="openFields[<?= $oPropertyType->catalogProductPropertyTypeId ?>][same]"
                                                               value="<?= (!empty($aPropertyTypeValues) ? _e($aPropertyTypeValues[0]) : '') ?>"/><br/>
                                                    </td>
                                                </tr>
                                                <?php

                                            }
                                        } # create the HTML for checkboxes
                                        elseif ($oPropertyType->type == 'checkbox' && count($oPropertyType->getPossibleValues()) > 0) {
                                            ?>
                                            <tr>
                                                <td style="width: 140px;"><?= $oPropertyType->getTranslations('auto-admin')->title ?></td>
                                                <td colspan="3">
                                                    <?php

                                                    foreach ($oPropertyType->getPossibleValues() AS $i => $oPossibleValue) {
                                                        ?>
                                                        <input class="alignCheckbox" id="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId . '-' . $i ?>" type="checkbox"
                                                               name="propertyTypes[<?= $oPropertyType->catalogProductPropertyTypeId ?>][]" value="<?= $oPossibleValue->catalogProductPropertyTypePossibleValueId ?>"<?= (in_array(
                                                            $oPossibleValue->getTranslations('auto-admin')->value,
                                                            $aPropertyTypeValues
                                                        ) ? ' checked' : '') ?> /> <label for="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId . '-' . $i ?>"><?= _e(
                                                                $oPossibleValue->getTranslations('auto-admin')->value
                                                            ) ?></label><br/>
                                                        <?php

                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php

                                        } # create the HTML for a select field
                                        elseif ($oPropertyType->type == 'select' && count($oPropertyType->getPossibleValues()) > 0) {
                                            ?>
                                            <tr>
                                                <td style="width: 140px;" class="withLabel"><label for="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId ?>"><?= _e(
                                                            $oPropertyType->getTranslations('auto-admin')->title
                                                        ) ?></label></td>
                                                <td colspan="2">
                                                    <select class="default" id="propertyType-<?= $oPropertyType->catalogProductPropertyTypeId ?>" name="propertyTypes[<?= $oPropertyType->catalogProductPropertyTypeId ?>][]">
                                                        <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                                        <?php

                                                        foreach ($oPropertyType->getPossibleValues() as $oPossibleValue) {
                                                            echo '<option value="' . $oPossibleValue->catalogProductPropertyTypePossibleValueId . '"' . (in_array(
                                                                    $oPossibleValue->getTranslations('auto-admin')->value,
                                                                    $aPropertyTypeValues
                                                                ) ? ' selected' : '') . '>' . _e($oPossibleValue->getTranslations('auto-admin')->value) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php

                                        }
                                    }
                                    ?>
                                </table>
                                <?php

                            }
                        }
                        ?>
                        <input type="submit" value="Opslaan" name="save" style="margin-bottom: 5px;"/>
                        <?php

                    } else {
                        echo '<p><i>' . sysTranslations::get('catalog_no_type') . '</i></p>';
                    }
                }
                ?>
            </fieldset>
        </form>
    </div>

    <!-- Related Products -->
    <div class="contentColumn">
        <form method="POST">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" name="action" value="relatedProducts"/>
            <fieldset>
                <legend><?= sysTranslations::get('catalog_related_products') ?></legend>
                <?php

                if ($oProduct->catalogProductId === null || $oProduct->catalogProductTypeId === null) {
                    echo '<p><i>' . sysTranslations::get('catalog_related_products_warning') . '</i></p>';
                } else {
                    ?>
                    <table class="withForm " style="margin-bottom: 10px;">
                        <tr>
                            <td class="withLabel"><label for="relatedProduct"><?= sysTranslations::get('catalog_related_product') ?></label></td>
                            <td>
                                <input class="default" type="text" id="relatedProduct" name="relatedProduct" title="<?= sysTranslations::get('catalog_related_products') ?>"/>
                                <input type="hidden" id="relatedProductId" name="relatedProductId"/>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <div id="relatedProducts">
                        <?php

                        $aRelatedProducts = $oProduct->getRelatedProducts();
                        include getAdminSnippet('catalogProductRelated', 'catalog');
                        ?>
                    </div>
                <?php } ?>
            </fieldset>
        </form>
    </div>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$sAdminCoreFolder        = getAdminPath('');
$sSuccessfullyRemoved    = sysTranslations::get('catalog_status_removed');
$sSuccessfullyAdded      = sysTranslations::get('catalog_status_added');
$sNotValidGoogleCategory = sysTranslations::get('catalog_not_valid_google_category');
$sBottomJavascript       = <<<EOT
<script>
    $('input.priceFloatOnly').autoNumeric({
        aSep: '',
        altDec: ','
    });

    $('input.numbersOnly').autoNumeric({
        aSep: '',
        mDec: '0'
    });

    //-------------------------------------------------------------------
    // update the salePriceWithTax if the salePriceWithoutTax is updated
    //-------------------------------------------------------------------
    function onChangeSalePriceWithoutTax() {
        $.ajax({
            type: "GET",
            url: '?ajax=addTax',
            data: 'salePrice=' + $('#salePriceWithoutTax').val() + '&taxPercentageId=' + $('select#taxPercentageId').val(),
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $('input#salePriceWithoutTax').val(dataObj.formattedSalePriceWithoutTax);
                    $('input#salePriceWithTax').val(dataObj.formattedSalePriceWithTax);
                } else{
                    $('input#salePriceWithTax').val('');
                    $('input#salePriceWithoutTax').val('');
                }
            }
        });
    }
    $('input#salePriceWithoutTax:enabled').change(onChangeSalePriceWithoutTax);

    //-------------------------------------------------------------------
    // update the salePriceWithoutTax if the salePriceWithTax is updated
    //-------------------------------------------------------------------
    function onChangeSalePriceWithTax() {
        $.ajax({
            type: "GET",
            url: '?ajax=subtractTax',
            data: 'salePrice=' + $('#salePriceWithTax').val() + '&taxPercentageId=' + $('select#taxPercentageId').val(),
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $('input#salePriceWithoutTax').val(dataObj.formattedSalePriceWithoutTax);
                    $('input#salePriceWithTax').val(dataObj.formattedSalePriceWithTax);
                } else{
                    $('input#salePriceWithTax').val('');
                    $('input#salePriceWithoutTax').val('');
                }
            }
        });
    }
    $('input#salePriceWithTax:enabled').change(onChangeSalePriceWithTax);

    //---------------------------------------------------------------------------
    // update the purchasePriceWithTax if the purchasePriceWithoutTax is updated
    //---------------------------------------------------------------------------
    function onChangePurchasePriceWithoutTax() {
        $.ajax({
            type: "GET",
            url: '?ajax=addTax',
            data: 'salePrice=' + $('#purchasePriceWithoutTax').val() + '&taxPercentageId=' + $('select#taxPercentageId').val(),
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $('input#purchasePriceWithoutTax').val(dataObj.formattedSalePriceWithoutTax);
                    $('input#purchasePriceWithTax').val(dataObj.formattedSalePriceWithTax);
                } else{
                    $('input#purchasePriceWithTax').val('');
                    $('input#purchasePriceWithoutTax').val('');
                }
            }
        });
    }
    $('input#purchasePriceWithoutTax:enabled').change(onChangePurchasePriceWithoutTax);

    //---------------------------------------------------------------------------
    // update the purchasePriceWithoutTax if the purchasePriceWithTax is updated
    //---------------------------------------------------------------------------
    function onChangePurchasePriceWithTax() {
        $.ajax({
            type: "GET",
            url: '?ajax=subtractTax',
            data: 'salePrice=' + $('#purchasePriceWithTax').val() + '&taxPercentageId=' + $('select#taxPercentageId').val(),
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $('input#purchasePriceWithoutTax').val(dataObj.formattedSalePriceWithoutTax);
                    $('input#purchasePriceWithTax').val(dataObj.formattedSalePriceWithTax);
                } else{
                    $('input#purchasePriceWithTax').val('');
                    $('input#purchasePriceWithoutTax').val('');
                }
            }
        });
    }
    $('input#purchasePriceWithTax:enabled').change(onChangePurchasePriceWithTax);

    //---------------------------------------------------------------------------
    // update the reducedPriceWithTax if the reducedPriceWithoutTax is updated
    //----------------------------------------------------------------------------
    function onChangeReducedPriceWithoutTax() {
        $.ajax({
            type: "GET",
            url: '?ajax=addTax',
            data: 'salePrice=' + $('#reducedPriceWithoutTax').val() + '&taxPercentageId=' + $('select#taxPercentageId').val(),
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $('input#reducedPriceWithoutTax').val(dataObj.formattedSalePriceWithoutTax);
                    $('input#reducedPriceWithTax').val(dataObj.formattedSalePriceWithTax);
                } else{
                    $('input#reducedPriceWithTax').val('');
                    $('input#reducedPriceWithoutTax').val('');
                }
            }
        });
    }
    $('input#reducedPriceWithoutTax:enabled').change(onChangeReducedPriceWithoutTax);

    //--------------------------------------------------------------------------
    // update the reducedPriceWithoutTax if the reducedPriceWithTax is updated
    //--------------------------------------------------------------------------
    function onChangeReducedPriceWithTax() {
        $.ajax({
            type: "GET",
            url: '?ajax=subtractTax',
            data: 'salePrice=' + $('#reducedPriceWithTax').val() + '&taxPercentageId=' + $('select#taxPercentageId').val(),
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $('input#reducedPriceWithoutTax').val(dataObj.formattedSalePriceWithoutTax);
                    $('input#reducedPriceWithTax').val(dataObj.formattedSalePriceWithTax);
                } else{
                    $('input#reducedPriceWithTax').val('');
                    $('input#reducedPriceWithoutTax').val('');
                }
            }
        });
    }
    $('input#reducedPriceWithTax:enabled').change(onChangeReducedPriceWithTax);

    //-----------------------------------------------------------
    // update prices info if the tax percentage has been changed
    //-----------------------------------------------------------
    $('select#taxPercentageId').change(function() {
        if ($('input#salePriceWithoutTax').prop('disabled')) {
            onChangeSalePriceWithTax();
            onChangeReducedPriceWithTax();
            onChangePurchasePriceWithTax();
        }
        else {
            onChangeSalePriceWithoutTax();
            onChangeReducedPriceWithoutTax();
            onChangePurchasePriceWithoutTax();
        }
    });

    //-------------------------------------
    // auto check and uncheck parent/child
    //-------------------------------------
    $('.autoCheckParent input[type="checkbox"]').click(function(){
        var element = this;
        if($(element).prop('checked')){
            while($(element).closest('ul').parent().find('> input[type="checkbox"]').size() == 1){
                element = $(element).closest('ul').parent().find('> input[type="checkbox"]');
                $(element).prop('checked', true);
            }
        }else{
            $(element).closest('li').find('input[type="checkbox"]').prop('checked', false);
        }
   });

    //--------------------------------
    // Submit form
    //--------------------------------
    $('input#saveProductButton').click(function() {
        var thisForm = $(this).closest('form');
        thisForm.submit();
    });
        
function getGoogleCategories(iLanguageId) {
    $.ajax({
        type: "POST",
        url: '?ajax=googleCategories&languageId=' + iLanguageId,
        dataType: "json",
        success: function( data ) {
            googleCategories = data;
    
            //for (i=0; i<googleLanguages.length; i++) {
                $('#googleCategory_' + iLanguageId).autocomplete({
                    source: data
                });
        
                // When I get the Categories, I add a rule to check if the category is correct
                $.validator.addMethod("googleCategory",
                function(value,element,params){
                    return this.optional(element) || params.indexOf(value) > 0;
                });

                $("#googleCategory_" + iLanguageId).rules("add",{
                        googleCategory: googleCategories,
                        messages: {
                            googleCategory: "$sNotValidGoogleCategory"
                        }
                });

                $("#googleCategory_" + iLanguageId).blur(function(){
                    $(this).prop('title',$(this).val());
                });
            //}
        }
    });        
}

$( document ).ready(function() {
    //--------------------------------
    // Google Categories    
    //--------------------------------
    var googleCategories = [];
    
    $('.google_cat').keyup(function(){
        getGoogleCategories($(this).data('languageid'));
    });
    
        
    //-------------------------------- 
    // Gender     
    //-------------------------------- 
    function setWithGenders(){
            var withGenders = $("#catalogProductTypeId option:selected").data("withgenders");
            if (withGenders) {
                $('#catalogProductGender').show();
            }else{
                $('#catalogProductGender').hide();
            }
    }
    
    setWithGenders();
    $('#catalogProductTypeId').change(function(){  
       setWithGenders();
    });
    
    //--------------------------------   
    // Product-color-image href change
    //-------------------------------- 
    $('form[name="productImageColorManagement"] select.catalogProductColor').change(function(o){
      $('form[name="productImageColorManagement"] .fancyBoxLink').prop('href', function(i,a){
        return a.replace( /(colorId=)[0-9]+/ig, "colorId="+o.target.value);
      });
    });
});

//-------------------------------- 
// Related Products Autocomplete   
//-------------------------------- 
function relatedProductAutocomplete(){
    if(!$('#relatedProduct').size())
        return false;
    $.ajax({
        type: "POST",
        url: '?ajax=related-products-autoComplete',
        dataType: "json",
        success: function( data ) {  
                $( "#relatedProduct" ).autocomplete({
                        minLength: 2,
                        source: data,
                        focus: function( event, ui ) {
                                $( "#relatedProduct" ).val( ui.item.value );
                                $( "#relatedProductId" ).val( ui.item.relatedProductid );
                                return false;
                        },
                        select: function( event, ui ) {
                                $( "#relatedProduct" ).val( ui.item.value);
                                $( "#relatedProductId" ).val( ui.item.relatedProductid);
                                saveRelatedProduct();
                                return false;
                        }
                })
                .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                        return $( "<li></li>" )
                                .data( "item.autocomplete", item )
                                .append( "<a><table><tr><td style='width:65px'><img src='" + item.link + "' width='50px' /></td><td style='vertical-align:middle'>" + item.value + "</td></tr></table></a>" )      
                                .appendTo( ul );
                };
        }
    });     
}
relatedProductAutocomplete();

//-------------------------------- 
// Related Products List   
//-------------------------------- 
function getRelatedProducts(){
    $.ajax({
        type: "POST",
        async: false,
        url: '?ajax=relatedProducts&catalogProductId=' + '$oProduct->catalogProductId',
        dataType: "json",
        success: function( data ) {
            $('#relatedProducts').html(data.html) ;
            relatedProductAutocomplete();
        }
    });
}

//--------------------------------   
// Delete related product
//-------------------------------- 
function deleteRelatedProduct(relatedProductId){
    if(confirmChoice('deze koppeling')){
        $.ajax({
            type: "POST",
            url: '?ajax=related-product-delete&relatedProductId=' + relatedProductId,
            dataType: "json",
            success: function( data ) {
                getRelatedProducts();
                relatedProductAutocomplete();
                showStatusUpdate('$sSuccessfullyRemoved');
            }
        });
    }
}

//--------------------------------   
// Save related product
//-------------------------------- 
function saveRelatedProduct(){
    $.ajax({
        type: "POST",
        url: '?ajax=related-product-save&relatedProductId=' + $( "#relatedProductId" ).val(),
        dataType: "json",
        success: function( data ) {
            getRelatedProducts();
            showStatusUpdate('$sSuccessfullyAdded');
        }
    });
}
        
// initiate prestatie tabs
$(".tabs").prestatieTabs();
        
function check(i, iPropertyTypeId) {
    if( i == 1 ) {
        $('#multilingual_yes_' + iPropertyTypeId).show();
        $('#multilingual_no_' + iPropertyTypeId).hide();
    } else {
        $('#multilingual_no_' + iPropertyTypeId).show();
        $('#multilingual_yes_' + iPropertyTypeId).hide();
    }
}
</script>

EOT;
$oPageLayout->addJavascript('<script src="' . $sAdminCoreFolder . 'plugins/autoNumeric-1.9.43.js"></script>');
$oPageLayout->addJavascript($sBottomJavascript);
$jsLang = empty($oCurrentUser) ? 'nl' : $oCurrentUser->getLanguage()->abbr;
$oPageLayout->addJavascript(
    '
<script>
    //--------------------------------   
    // Add Tiny Editor
    //-------------------------------- 
    initTinyMCE(".tiny_MCE_default", "/admin/paginas/link-list", "/admin/catalogus/image-list/' . $oProduct->catalogProductId . '", undefined, undefined, undefined, "' . $jsLang . '");
</script>
'
);

$oPageLayout->addJavascript($sJS);

$sJS2 = '';
foreach ($a as $iPropertyTypeId => $aValues) {
    $sJS2 .= <<<EOT
<script>
$('input[name="multilingual_{$iPropertyTypeId}"]').click(function() {
    check($(this).val(), {$iPropertyTypeId});
});
</script>
EOT;
}

$oPageLayout->addJavascript($sJS2);

//_d($a);
?>