<?php

$sEditFormRule = '';
?>

    <div id="topOptions">
        <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_product_feature_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>
            )</span>
    </div>
    <div class="cf">
        <div class="contentColumn">
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <fieldset>
                    <legend><?= sysTranslations::get('catalog_product_features') ?>
                        <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_product_features_tooltip') ?>">&nbsp;</div>
                    </legend>
                    <table class="withForm">
                        <tr>
                            <td colspan="3">
                                <div class="clearfix">
                                    <div class="tabs">
                                        <div class="tabsHolder cf unselectable"></div>
                                        <?php

                                        foreach (AdminLocales::getLanguages() as $oLanguage) {
                                            $oTranslation = $oProductPropertyType->getTranslations($oLanguage->languageId);
                                            ?>
                                            <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                                <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                                <div class="tabContent">
                                                    <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                        <tr>
                                                            <td class="withLabel" style="width: 140px;"><label for="title_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('catalog_product_feature') ?> *</label></td>
                                                            <td><input id="title_<?= $oLanguage->languageId ?>" class="required autofocus default" name="title[<?= $oLanguage->languageId ?>]"
                                                                       title="<?= sysTranslations::get('catalog_product_feature_tooltip') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`"
                                                                       type="text" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->title) : '') ?>"/></td>
                                                            <td><span class="error"><?= $oProductPropertyType->isPropValid("title_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
                            <td class="withLabel" style="width: 160px;"><label for="type"><?= sysTranslations::get('catalog_input_type') ?> *</label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_input_type_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select name="type" id="type" onchange="handleTypeChange();" class="required default" title="<?= sysTranslations::get('catalog_select_entry') ?>">
                                    <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                    <option <?= $oProductPropertyType->type == CatalogProductPropertyType::TYPE_SELECT ? 'SELECTED' : '' ?> value="<?= CatalogProductPropertyType::TYPE_SELECT ?>">SELECT</option>
                                    <option <?= $oProductPropertyType->type == CatalogProductPropertyType::TYPE_CHECKBOX ? 'SELECTED' : '' ?> value="<?= CatalogProductPropertyType::TYPE_CHECKBOX ?>">CHECKBOX</option>
                                    <option <?= $oProductPropertyType->type == CatalogProductPropertyType::TYPE_TEXT ? 'SELECTED' : '' ?> value="<?= CatalogProductPropertyType::TYPE_TEXT ?>">TEXT</option>
                                </select>
                            </td>
                            <td><span class="error"><?= $oProductPropertyType->isPropValid("type") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr class="show-for-type-<?= CatalogProductPropertyType::TYPE_TEXT ?>">
                            <td class="withLabel"><label><?= sysTranslations::get('catalog_input_translatable') ?> *</label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_input_translatable_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td class="withLabel">
                                <input <?= $oProductPropertyType->inputTranslatable ? 'checked' : '' ?> class="alignRadio required" type="radio" id="inputTranslatable_1" name="inputTranslatable" value="1"/> <label
                                        for="inputTranslatable_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input <?= !$oProductPropertyType->inputTranslatable ? 'checked' : '' ?> class="alignRadio required" type="radio" id="inputTranslatable_0" name="inputTranslatable" value="0"/> <label
                                        for="inputTranslatable_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="type"><?= sysTranslations::get('catalog_type_filter') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_type_filter_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select name="filterType" id="filterType" class="default" title="<?= sysTranslations::get('catalog_type_filter_select') ?>">
                                    <option value=""><?= sysTranslations::get('catalog_no_filter') ?></option>
                                    <option <?= $oProductPropertyType->filterType == 'text' ? 'SELECTED' : '' ?> value="text">TEXT</option>
                                    <option <?= $oProductPropertyType->filterType == 'checkbox' ? 'SELECTED' : '' ?> value="checkbox">CHECKBOX</option>
                                    <option <?= $oProductPropertyType->filterType == 'select' ? 'SELECTED' : '' ?> value="select">SELECT</option>
                                    <option <?= $oProductPropertyType->filterType == 'min-max' ? 'SELECTED' : '' ?> value="min-max">MIN-MAX</option>
                                </select>
                            </td>
                            <td><span class="error"><?= $oProductPropertyType->isPropValid("type") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="catalogProductTypeId"><?= sysTranslations::get('catalog_product_type') ?> *</label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_product_type_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select id="catalogProductTypeId" class="required default" title="<?= sysTranslations::get('catalog_select_type') ?>">
                                    <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                    <?php

                                    foreach ($aProductTypes as $oProductType) {
                                        echo '<option value="' . $oProductType->catalogProductTypeId . '"' . ($oProductType->catalogProductTypeId == $iProductTypeId ? ' selected' : '') . '>' . _e(
                                                $oProductType->getTranslations('auto-admin')->title
                                            ) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><span class="error"><?= $oProductPropertyType->isPropValid("catalogProductTypeId") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="catalogProductPropertyTypeGroupId"><?= sysTranslations::get('catalog_property_group') ?> *</label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_property_group_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select class="required default catalogProductPropertyTypeGroupId" data-productpropertytypeid="" title="<?= sysTranslations::get('catalog_property_group_select') ?>" <?= !is_numeric(
                                    $iProductTypeId
                                ) ? '' : 'DISABLED style="display: none"' ?>>
                                    <option value="" SELECTED><?= sysTranslations::get('catalog_property_group_select') ?></option>
                                </select>
                                <?php

                                foreach ($aProductTypes AS $oProductType) {
                                    ?>
                                    <select class="required catalogProductPropertyTypeGroupId default" data-productpropertytypeid="<?= $oProductType->catalogProductTypeId ?>"
                                            title="<?= sysTranslations::get('catalog_property_group_choose') ?>"
                                            name="catalogProductPropertyTypeGroupId" <?= $iProductTypeId == $oProductType->catalogProductTypeId ? '' : 'DISABLED style="display: none"' ?>>
                                        <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                        <?php

                                        foreach (CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupsByProductTypeId($oProductType->catalogProductTypeId) AS $oProductPropertyTypeGroup) {
                                            echo '<option ' . ($oProductPropertyType->catalogProductPropertyTypeGroupId == $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId ? 'SELECTED' : '') . ' value="' . $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId . '">' . _e(
                                                    $oProductPropertyTypeGroup->getTranslations('auto-admin')->title
                                                ) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <?php

                                }
                                ?>
                            </td>
                            <td><span class="error"><?= $oProductPropertyType->isPropValid("catalogProductPropertyTypeGroupId") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('catalog_property_possible_values') ?>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_property_possible_values_tooltip') ?>">&nbsp;</div>
                </legend>
                <?php

                if ($oProductPropertyType->type !== null && $oProductPropertyType->type !== CatalogProductPropertyType::TYPE_TEXT) {
                    ?>
                    <div id="possibleValues">
                        <form method="POST" class="validateForm">
                            <?= CSRFSynchronizerToken::field() ?>
                            <input type="hidden" name="action" value="savePossibleValue"/>
                            <input type="hidden" name="catalogProductPropertyTypeId" value="<?= $oProductPropertyType->catalogProductPropertyTypeId ?>"/>
                            <table class="withForm">
                                <tr>
                                    <td style="width: 116px;"><label><?= sysTranslations::get('global_translatable') ?> *</label></td>
                                    <td>
                                        <input class="alignRadio required" title="<?= sysTranslations::get('catalog_translatable_set_online_tooltip') ?>" type="radio" id="multilingual_1" name="multilingual" value="1"/> <label
                                                for="multilingual_1"><?= sysTranslations::get('global_yes') ?></label>
                                        <input class="alignRadio required" title="<?= sysTranslations::get('catalog_translatable_set_online_tooltip') ?>" type="radio" id="multilingual_0" name="multilingual" value="0"/> <label
                                                for="multilingual_0"><?= sysTranslations::get('global_no') ?></label>
                                    </td>
                                </tr>

                                <tr id="multilingual_yes" style="display: none;">
                                    <td colspan="2">
                                        <div class="clearfix">
                                            <div class="tabs">
                                                <div class="tabsHolder cf unselectable"></div>
                                                <?php

                                                foreach (AdminLocales::getLanguages() as $oLanguage) {
                                                    ?>
                                                    <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                                        <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                                        <div class="tabContent">
                                                            <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                                <tr>
                                                                    <td class="withLabel" style="width: 116px;"><label for="value_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_value') ?> *</label></td>
                                                                    <td><input class="required default"
                                                                               title="<?= sysTranslations::get('catalog_property_set_value') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`"
                                                                               id="possibleValueValue_<?= $oLanguage->languageId ?>" type="text" name="value[<?= $oLanguage->languageId ?>]" value=""/></td>
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

                                <tr id="multilingual_no" style="display: none;">
                                    <td class="withLabel" style="width: 116px;"><label for="value_same"><?= sysTranslations::get('global_value') ?> *</label></td>
                                    <td><input class="required default" title="<?= sysTranslations::get('catalog_property_set_value') ?>" id="possibleValueValue_<?= $oLanguage->languageId ?>" type="text" name="value_same" value=""/></td>
                                </tr>

                                <tr>
                                    <td colspan="2"><input type="submit" value="<?= sysTranslations::get('catalog_save_value') ?>" name="savePossibleValue"/></td>
                                </tr>
                            </table>
                        </form>
                        <hr/>
                        <h3><?= sysTranslations::get('catalog_saved_values') ?>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('catalog_saved_values_tooltip') ?>">&nbsp;</div>
                        </h3>
                        <ul class="possibleValues sortable">
                            <?php

                            foreach ($oProductPropertyType->getPossibleValues() as $oProductPropertyTypePossibleValue) {
                                ?>
                                <li id="placeholder-<?= $oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId ?>"
                                    data-catalogproductpropertytypepossiblevalueid="<?= $oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId ?>" class="placeholder">
                                    <div class="possibleValuePlaceholder cf">
                                        <div class="value"><?= $oProductPropertyTypePossibleValue->getTranslations('auto-admin')->value ?></div>
                                        <?php

                                        foreach (AdminLocales::getLanguages() as $oLanguage) {
                                            echo '<div class="hide value_' . $oLanguage->languageId . '">' . $oProductPropertyTypePossibleValue->getTranslations($oLanguage->languageId)->value . '</div>';
                                            $sEditFormRule .= '$(\'#editPossibleValuesForm input[name="value_' . $oLanguage->languageId . '"]\').val(jPlaceholder.find(\'.value_' . $oLanguage->languageId . '\').html());';
                                        }
                                        ?>
                                    </div>
                                    <div class="actionsPlaceholder">
                                        <a class="action_icon edit_icon" title="<?= sysTranslations::get('catalog_edit_value') ?>" onclick="showEditPossibleValue(this);
                                            return false;" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-editPossibleValue' ?>"></a>
                                        <a class="action_icon delete_icon" title="Verwijder waarde" onclick="deletePossibleValue(this);
                                            return false;" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-deletePossibleValue?'. CSRFSynchronizerToken::query() .'' ?>"></a>
                                    </div>
                                </li>
                                <?php

                            }
                            ?>
                        </ul>
                    </div>
                    <?php

                } else {
                    echo '<p><i>' . sysTranslations::get('catalog_product_properties_warning') . '</i></p>';
                }
                ?>
            </fieldset>
        </div>
    </div>
    <div id="bottomOptions">
        <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_product_feature_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>
            )</span>
    </div>

    <div class="hide">
        <div id="editPossibleValuesForm">
            <h2><?= sysTranslations::get('catalog_change_value') ?></h2>
            <form onsubmit="savePossibleValue(this); return false;" method="POST" action="#">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" name="catalogProductPropertyTypePossibleValueId" value=""/>

                <?php

                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    ?>
                    <label for="form_possibleValueValue_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_value') ?> <?= strtoupper($oLanguage->code) ?></label><br/>
                    <input type="text" class="default" id="form_possibleValueValue_<?= $oLanguage->languageId ?>" name="value_<?= $oLanguage->languageId ?>" value=""/><br/>
                    <?php

                }
                ?>

                <input type="submit" name="" value="<?= sysTranslations::get('catalog_save_value') ?>"/>
            </form>
        </div>
        <a id="editPossibleValuesFormLink" class="fancyBoxLink" href="#editPossibleValuesForm"></a>
    </div>
<?php

# add sortable javascript initiation code
$sSaveOrderLink = ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-savePossibleValueOrder';

// Translation status
$sValueReordered = sysTranslations::get('catalog_value_reordered');
$sValueNotChanged = sysTranslations::get('catalog_value_not_changed');
$sValueSaved = sysTranslations::get('catalog_value_saved');
$sValueNotSaved = sysTranslations::get('catalog_value_not_saved');
$sValueDeleted = sysTranslations::get('catalog_value_deleted');
$sValueNotDeleted = sysTranslations::get('catalog_value_not_deleted');

$sOpeningHoursManagerJavascript = <<<EOT
// change the catalogProductTypeId-select when catalogProductPropertyTypeGroupId-select changes
$('select#catalogProductTypeId').change(function() {
    $('select.catalogProductPropertyTypeGroupId').hide();
    $('select.catalogProductPropertyTypeGroupId').prop('disabled', true);
    $('select.catalogProductPropertyTypeGroupId').val('');
    $('select.catalogProductPropertyTypeGroupId[data-productpropertytypeid=' + $(this).val() + ']').removeAttr('disabled');
    $('select.catalogProductPropertyTypeGroupId[data-productpropertytypeid=' + $(this).val() + ']').show();
});

// sort the possible values
$( "#possibleValues ul.possibleValues.sortable").sortable({
    items: '> li',
    placeholder: 'ui-state-highlight placeholder',
    forcePlaceholderSize: true,
    tolerance: 'pointer',
    update: function(event, ui) {
        var catalogProductPropertyTypePossibleValueIds = new Array();
        ui.item.closest('ul.possibleValues').find('> li').each(function(index, value){
            catalogProductPropertyTypePossibleValueIds[index] = $(value).data('catalogproductpropertytypepossiblevalueid');
        });

        $.ajax({
            type : 'POST',
            url : '$sSaveOrderLink',
            data : 'catalogProductPropertyTypePossibleValueIds=' + catalogProductPropertyTypePossibleValueIds.join(','),
            success : function(data){
                var dataObj = eval("(" + data + ")");
                if(dataObj.success === true) {
                    $("#possibleValues ul.possibleValues > li.placeholder").effect('highlight', 1500);
                    showStatusUpdate('$sValueReordered');
                } else {
                    showStatusUpdate('$sValueNotChanged');
                }
            }
        });
    }
});

/**
 * edit a PossibleValues
 * @param element to get data from
 */
function showEditPossibleValue(element){
    jElement = $(element);
    jPlaceholder = $(element).closest(".placeholder");
        
    //fill the form and show it
    $('#editPossibleValuesForm form').prop('action', jElement.prop('href'));
    //$('#editPossibleValuesForm input[name="value_39"]').val(jPlaceholder.find('.value_39').html());
    //$('#editPossibleValuesForm input[name="value_48"]').val(jPlaceholder.find('.value_48').html());
    $sEditFormRule
    $('#editPossibleValuesForm input[name="catalogProductPropertyTypePossibleValueId"]').val(jPlaceholder.data("catalogproductpropertytypepossiblevalueid"));
    $('#editPossibleValuesFormLink').click();
}

/**
 * save PossibleValues from form
 * @param element (form element)
 */
function savePossibleValue(element){
    var jForm = $(element);

    $.ajax({
        type : 'POST',
        url : jForm.prop("action"),
        data : jForm.serialize(),
        success : function(data){
            var dataObj = eval("(" + data + ")");
            if(dataObj.success === true) {
                //change value from list
                $("ul.possibleValues #placeholder-" + dataObj.catalogProductPropertyTypePossibleValueId + " .possibleValuePlaceholder .value").html(dataObj.value_39);
                $("ul.possibleValues #placeholder-" + dataObj.catalogProductPropertyTypePossibleValueId + " .possibleValuePlaceholder .value_39").html(dataObj.value_39);
                $("ul.possibleValues #placeholder-" + dataObj.catalogProductPropertyTypePossibleValueId + " .possibleValuePlaceholder .value_48").html(dataObj.value_48);
                $.fancybox.close(); //close fancybox after saving
                setTimeout(function() {
                    $("ul.possibleValues #placeholder-" + dataObj.catalogProductPropertyTypePossibleValueId).effect('highlight', 1500);
                    showStatusUpdate('$sValueSaved');
                }, 800);// timeout for closing fancybox first
            } else {
                showStatusUpdate('$sValueNotSaved');
            }
        }
    });
}

/**
 * delete a PossibleValues and on success its placeholder
 * @param element clicked <a> element
 */
function deletePossibleValue(element){
    // confirm deleting PossibleValue
    if(confirmChoice('deze waarde') !== true) {
        return false;
    }

    jElement = $(element);

    $.ajax({
        type : 'POST',
        url : jElement.prop("href"),
        data : 'catalogProductPropertyTypePossibleValueId=' + jElement.closest('.placeholder').data("catalogproductpropertytypepossiblevalueid"),
        success : function(data){
            var dataObj = eval("(" + data + ")");
            if(dataObj.success === true){
                $("ul.possibleValues #placeholder-" + dataObj.catalogProductPropertyTypePossibleValueId).hide(750, function() {
                    $(this).remove();
                });
                showStatusUpdate('$sValueDeleted');
            } else {
                showStatusUpdate('$sValueNotDeleted');
            }
        }
    });
}
        
// initiate prestatie tabs
$(".tabs").prestatieTabs();
        
$('input[name="multilingual"]').click(function() {
    check($(this).val());
});
        
function check(i) {
    if( i == 1 ) {
        $('#multilingual_yes').show();
        $('#multilingual_no').hide();
    } else {
        $('#multilingual_no').show();
        $('#multilingual_yes').hide();
    }
}
        
function handleTypeChange(){
    var type = $('#type').val();
    $('[class*=show-for-type-]').addClass('hide');
    $('.show-for-type-' + type).removeClass('hide');
}
        
// check on init
handleTypeChange();
EOT;
$oPageLayout->addJavascript('<script>' . $sOpeningHoursManagerJavascript . '</script>');
?>