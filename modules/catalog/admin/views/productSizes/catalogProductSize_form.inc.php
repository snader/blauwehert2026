<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_size_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('global_size') ?></legend>
                <table class="withForm">
                    <tr>
                        <td class="withLabel" style="width: 116px;"><label><?= sysTranslations::get('global_translatable') ?> *</label></td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_translatable_set_online_tooltip') ?>" type="radio" <?= $oProductSize->multilingual ? 'CHECKED' : '' ?> id="multilingual_1" name="multilingual"
                                   value="1"/> <label for="multilingual_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('catalog_translatable_set_online_tooltip') ?>" type="radio" <?= !$oProductSize->multilingual ? 'CHECKED' : '' ?> id="multilingual_0" name="multilingual"
                                   value="0"/> <label for="multilingual_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oProductSize->isPropValid("multilingual") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <tr id="multilingual_yes" style="display: none;">
                        <td colspan="3">
                            <div class="clearfix">
                                <div class="tabs">
                                    <div class="tabsHolder cf unselectable"></div>
                                    <?php

                                    foreach (AdminLocales::getLanguages() as $oLanguage) {
                                        $oTranslation = $oProductSize->getTranslations($oLanguage->languageId);
                                        ?>
                                        <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                            <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                            <div class="tabContent">
                                                <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                    <tr>
                                                        <td class="withLabel" style="width: 116px;"><label for="name_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_size') ?> *</label></td>
                                                        <td><input id="name_<?= $oLanguage->languageId ?>" class="required autofocus default"
                                                                   title="<?= sysTranslations::get('global_set_name') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`" type="text"
                                                                   name="name[<?= $oLanguage->languageId ?>]" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->name) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oProductSize->isPropValid("name_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
                        <td class="withLabel" style="width: 116px;"><label for="name"><?= sysTranslations::get('global_size') ?> *</label></td>
                        <td><input id="name" class="required autofocus default" title="<?= sysTranslations::get('global_set_name') ?>" type="text" name="name_same"
                                   value="<?= (is_numeric(http_get('param2')) && $oProductSize->getTranslations('auto-admin') ? _e($oProductSize->getTranslations('auto-admin')->name) : '') ?>"/></td>
                        <td><span class="error"><?= $oProductSize->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('catalog_size_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>

<?php

$sBottomJavascript = <<<EOT
<script>
$(document).ready(function() {
    check($('input[type="radio"]:checked').val());
});
        
$('input[type="radio"]').click(function() {
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
        
        
// initiate prestatie tabs
$(".tabs").prestatieTabs();
</script>
EOT;

$oPageLayout->addJavascript($sBottomJavascript);
