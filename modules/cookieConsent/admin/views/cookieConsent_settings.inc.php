<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
        <?= CSRFSynchronizerToken::field() ?>
        <div class="contentColumn">
            <fieldset>
                <input type="hidden" name="action">
                <h3><?= _e(sysTranslations::get('cc_appearance')); ?></h3>
                <table class="cc-table">
                    <tr>
                        <td><label for=""><?= sysTranslations::get('cc_enabled') ?></label></td>
                        <td>
                            <input type="radio" id="cc_enabled" name="settings[cc_enabled]" value="1" <?= (Settings::get('cc_enabled') ? 'checked' : '') ?>>&nbsp;<label for="cc_enabled"><?= sysTranslations::get(
                                    "global_yes"
                                ); ?></label><br/>
                            <input type="radio" id="cc_disabled" name="settings[cc_enabled]" value="0" <?= (!Settings::get('cc_enabled') ? 'checked' : '') ?>>&nbsp;<label for="cc_disabled"><?= sysTranslations::get("global_no"); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><label for="cc_theme"><?= sysTranslations::get('cc_theme') ?></label></td>
                        <td>
                            <select class="default" name="settings[cc_theme]" id="cc_theme">
                                <option value="landgoedvoornedvoorn" <?= (Settings::get('cc_themelandgoedvoorn'landgoedvoorn' ? "selected" : "") ?>><?= sysTranslations::glandgoedvoorn_themes_landgoedvoorn') ?></option>
                                <option value="electric" <?= (Settings::get('cc_theme') == 'electric' ? "selected" : "") ?>><?= sysTranslations::get('cc_themes_electric') ?></option>
                                <option value="sea" <?= (Settings::get('cc_theme') == 'sea' ? "selected" : "") ?>><?= sysTranslations::get('cc_themes_sea') ?></option>
                                <option value="wine" <?= (Settings::get('cc_theme') == 'wine' ? "selected" : "") ?>><?= (isDeveloper() ? sysTranslations::get("cc_themes_cornee") : sysTranslations::get("cc_themes_wine")) ?></option>
                                <option value="candy" <?= (Settings::get('cc_theme') == 'candy' ? "selected" : "") ?>><?= sysTranslations::get('cc_themes_candy') ?></option>
                                <option value="forest" <?= (Settings::get('cc_theme') == 'forest' ? "selected" : "") ?>><?= sysTranslations::get('cc_themes_forest') ?></option>
                                <option value="custom" <?= (Settings::get('cc_theme') == 'custom' ? "selected" : "") ?>><?= sysTranslations::get('cc_themes_custom') ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <?php if (moduleExists('pages')) { ?>
                <fieldset>
                    <h3><?= _e(sysTranslations::get("cc_consentpage")) ?></h3>
                    <?php if (!empty($aConsentPages)) { ?>
                        <b><?= _e(sysTranslations::get("cc_existing_pages")) ?></b>
                        <table class="sorted cc-table">
                            <thead>
                            <tr>
                                <th>Titel</th>
                                <th>Taal</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($aConsentPages as $oConsentPage) {
                                ?>
                                <tr>
                                    <td><?= $oConsentPage->title ?></td>
                                    <td><?= $oConsentPage->getLanguage()->nativeName ?></td>
                                    <td><?php

                                        if ($oPage->isEditable()) {
                                            ?>
                                            <a title="<?= _e(sysTranslations::get('pages_edit')) ?>" class="action_icon edit_icon" href="<?= ADMIN_FOLDER . '/paginas/bewerken/' . $oPage->pageId ?>"></a>
                                            <?php

                                        } else {
                                            ?>
                                            <a title="<?= _e(sysTranslations::get('pages_not_editable')) ?>" class="action_icon edit_icon grey" href="#"></a>
                                            <?php

                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php

                            } ?>
                            </tbody>

                        </table>
                    <?php }
                    if (!empty($aMissingPages)) { ?>
                        <b class="cc-warningtext"><?= _e(sysTranslations::get('cc_missing_pages')) ?></b>
                        <p>
                            <?php
                            foreach ($aMissingPages as $sMssingPageLanguage) {
                                ?>
                                &nbsp;- <?= $sMssingPageLanguage ?><br/>
                            <?php }
                            ?>
                        </p>

                    <?php } ?>
                </fieldset>
            <?php } ?>
            <fieldset id="cc_theme_editor">
                <table>
                    <tr>
                        <td>
                            <h3><?= _e(sysTranslations::get('cc_themeeditor')) ?></h3>
                        </td>
                    </tr>
                    <tr>
                        <td><label for=""><?= _e(sysTranslations::get('cc_bgcolor')) ?></label></td>
                        <td><input type="color" id="cc_bgcolor" name="settings[cc_bgcolor]" value="<?= Settings::get("cc_bgcolor"); ?>"></td>
                    </tr>
                    <tr>
                        <td><label for=""><?= _e(sysTranslations::get('cc_textcolor')) ?></label></td>
                        <td><input type="color" id="cc_textcolor" name="settings[cc_textcolor]" value="<?= Settings::get("cc_textcolor"); ?>"></td>
                    </tr>
                    <tr>
                        <td><label for=""><?= _e(sysTranslations::get('cc_btnbgcolor')) ?></label></td>
                        <td><input type="color" id="cc_btnbgcolor" name="settings[cc_btnbgcolor]" value="<?= Settings::get("cc_btnbgcolor"); ?>"></td>
                    </tr>
                    <tr>
                        <td><label for=""><?= _e(sysTranslations::get('cc_btntextcolor')) ?></label></td>
                        <td><input type="color" id="cc_btntextcolor" name="settings[cc_btntextcolor]" value="<?= Settings::get("cc_btntextcolor"); ?>"></td>
                    </tr>
                </table>

                <input type="hidden" name="action" value="save">
            </fieldset>
            <div class="right cc-save-button">
                <input type="submit" value="<?= sysTranslations::get('global_save') ?>">
            </div>
        </div>
        <div class="contentColumn">
            <fieldset>
                <h2><?= _e(sysTranslations::get("cc_preview")) ?></h2>
                <p><?= _e(sysTranslations::get('cc_preview_desc')) ?></p>
                <div class="cc-preview" id="cc_preview-box">
                    <p class="cc-preview-container" id="cc_preview-text">
                        <?= _e(SiteTranslations::get('cc_text')) ?>
                    </p>
                    <table class="cc-preview-buttons">
                        <tr>
                            <td class="cc-preview-button" id="cc_preview-dismiss"><?= _e(SiteTranslations::get('cc_dismiss')) ?></td>
                            <td class="cc-preview-button" id="cc_preview-accept"><?= _e(SiteTranslations::get('cc_accept')) ?></td>
                        </tr>
                    </table>
                </div>
            </fieldset>
        </div>

    </form>
</div>
<?php

$oPageLayout->addJavascript(
    '
    <script>
    function updatePreview(){
        $("#cc_preview-box").css("background-color", $("#cc_bgcolor").val());
        $("#cc_preview-text").css("color", $("#cc_textcolor").val());
        $("#cc_preview-dismiss").css("color", $("#cc_textcolor").val());
        $("#cc_preview-accept").css("background-color", $("#cc_btnbgcolor").val());
        $("#cc_preview-accept").css("color", $("#cc_btntextcolor").val());
    }
    
    const themes =
    {
        "landgoedvoornedvoorn": {
            "bgcolor": "#212121",
            "textcolor": "#ffffff",
            "btnbgcolor": "#e5114c",
            "btntextcolor": "#ffffff"
        },
        "electric": {
            "bgcolor": "#383b75",
            "textcolor": "#ffffff",
            "btnbgcolor": "#f1d600",
            "btntextcolor": "#000000"
        },
        "sea": {
            "bgcolor": "#252e39",
            "textcolor": "#FFFFFF",
            "btnbgcolor": "#13a7d0",
            "btntextcolor": "#FFFFFF"
        },
        "wine": {
            "bgcolor": "#aa0200",
            "textcolor": "#FFFFFF",
            "btnbgcolor": "#ff0400",
            "btntextcolor": "#FFFFFF"
        },
        "candy": {
            "bgcolor": "#3937a3",
            "textcolor": "#FFFFFF",
            "btnbgcolor": "#e62576",
            "btntextcolor": "#FFFFFF"
        },
        "forest": {
            "bgcolor": "#216942",
            "textcolor": "#b2d192",
            "btnbgcolor": "#afed71",
            "btntextcolor": "#000000"
        }        
    }
    
    if($("#cc_theme").val() == "custom"){
        $("#cc_theme_editor").show();
    } else {
        $("#cc_theme_editor").hide();
    }
    
    $("#cc_theme").on("change", function() {
        if($("#cc_theme").val() == "custom"){
            $("#cc_theme_editor").show();
        } else {
            let currentTheme = $("#cc_theme").val();
            console.log(themes[currentTheme]);
            $("#cc_bgcolor").val(themes[currentTheme].bgcolor);
            $("#cc_textcolor").val(themes[currentTheme].textcolor);
            $("#cc_btnbgcolor").val(themes[currentTheme].btnbgcolor);
            $("#cc_btntextcolor").val(themes[currentTheme].btntextcolor);
            
            $("#cc_theme_editor").hide();
            updatePreview();
        }
    });
       
    $("#cc_theme_editor").on("load change", function() {
       updatePreview();
    });

     updatePreview();
    </script>
'
);
?>