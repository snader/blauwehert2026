<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('global_color') ?></legend>
                <table class="withForm">
                    <tr>
                        <td colspan="3">
                            <div class="clearfix">
                                <div class="tabs">
                                    <div class="tabsHolder cf unselectable"></div>
                                    <?php

                                    foreach (AdminLocales::getLanguages() as $oLanguage) {
                                        $oTranslation = $oProductColor->getTranslations($oLanguage->languageId);
                                        ?>
                                        <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                            <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                            <div class="tabContent">
                                                <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                    <tr>
                                                        <td class="withLabel" style="width: 116px;"><label for="name_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('global_color') ?> *</label></td>
                                                        <td><input id="name_<?= $oLanguage->languageId ?>" class="required autofocus default"
                                                                   title="<?= sysTranslations::get('global_set_name') ?> <?= sysTranslations::get('global_for_language') ?> `<?= strtoupper($oLanguage->code) ?>`" type="text"
                                                                   name="name[<?= $oLanguage->languageId ?>]" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->name) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oProductColor->isPropValid("name_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>

<?php

$sBottomJavascript = <<<EOT
<script>
// initiate prestatie tabs
$(".tabs").prestatieTabs();
</script>
EOT;

$oPageLayout->addJavascript($sBottomJavascript);