<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('dynamiccontent_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn" style="min-height: 600px;">
        <fieldset>
            <legend><?= sysTranslations::get('dynamiccontent') ?></legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *&nbsp;</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('dynamiccontent_online_offline_tooltip') ?>" type="radio" <?= $oDynamicContent->online ? 'CHECKED' : '' ?> id="online_1" name="online"
                                   value="1"/> <label for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('dynamiccontent_online_offline_tooltip') ?>" type="radio" <?= !$oDynamicContent->online ? 'CHECKED' : '' ?> id="online_0" name="online"
                                   value="0"/> <label for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oDynamicContent->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <?php

                    if ($oCurrentUser->isAdmin()) {
                        ?>
                        <tr>
                            <td style="width: 150px;"><?= sysTranslations::get('global_admin_only') ?> *
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('dynamiccontent_admin_only_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <input class="alignRadio required" title="<?= sysTranslations::get('dynamiccontent_adminonly_tooltip') ?>" type="radio" <?= $oDynamicContent->adminOnly ? 'CHECKED' : '' ?> id="adminonly_1" name="adminOnly"
                                       value="1"/> <label for="adminonly_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input class="alignRadio required" title="<?= sysTranslations::get('dynamiccontent_adminonly_tooltip') ?>" type="radio" <?= !$oDynamicContent->adminOnly ? 'CHECKED' : '' ?> id="adminonly_0" name="adminOnly"
                                       value="0"/> <label for="adminonly_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oDynamicContent->isPropValid("adminOnly") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="withLabel"><label for="name"><?= sysTranslations::get('global_name') ?> * </label></td>
                        <?php

                        $sNamePropError = '';
                        if (!$oDynamicContent->isPropValid("name")) {
                            $sNamePropError .= ($sNamePropError == '' ? '' : ', ') . "" . sysTranslations::get('global_field_not_completed');
                        }
                        if (!$oDynamicContent->isPropValid("nameExists")) {
                            $sNamePropError .= ($sNamePropError == '' ? '' : ', ') . "" . sysTranslations::get('dynamice_content_name_in_use');
                        }
                        if (!empty($sNamePropError)) {
                            $sNamePropError = '<span class="error">' . $sNamePropError . '</span>';
                        }
                        ?>
                        <td colspan="2"><input class="required default" <?= (!$oCurrentUser->isAdmin() ? 'readonly ' : '') ?>id="name" type="text" autocomplete="off" name="name" value="<?= $oDynamicContent->name ?>"
                                               title="<?= sysTranslations::get('dynamiccontent_no_name') ?>"/><?= $sNamePropError ?></td>


                    </tr>
                    <?php

                    if ($oCurrentUser->isAdmin()) {
                        ?>
                        <tr>
                            <td class="withLabel"><label for="type"><?= ucfirst(sysTranslations::get('global_type')) ?> *
                                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('dynamiccontent_admin_only_tooltip') ?>">&nbsp;</div>
                                </label></td>
                            <td colspan="2">
                                <select name="type" class="typeChoice">
                                    <option <?= $oDynamicContent->type == "text" ? 'selected' : '' ?> value="text">Tekst (geen opmaak, alleen enters)</option>
                                    <option <?= $oDynamicContent->type == "html" ? 'selected' : '' ?> value="html">Tekst met basic HTML</option>
                                    <option <?= $oDynamicContent->type == "code" ? 'selected' : '' ?> value="code">Code (HTML/script)</option>
                                </select>

                            </td>
                        </tr>
                    <?php } else { ?>
                        <input type="hidden" class="typeChoice" value="<?= $oDynamicContent->type ?>" name="type">


                    <?php } ?>
                    <tr>
                        <td colspan="3"><label for="content"><?= sysTranslations::get('global_content') ?></label></td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea style="width:100%;min-height:250px;" name="content" id="content"><?= $oDynamicContent->content ?></textarea></td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>

</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('dynamiccontent_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# define and add javascript to bottom
$sBottomJavascript = <<<EOT
<script type="text/javascript">
        
    function doEditor() {
        if ($('.typeChoice').val() == "html") {
            initTinyMCE("#content", "/admin/paginas/link-list", null);
        } else {
            tinymce.remove();
        }
    }
       
    $('.typeChoice').change(function(){            
        doEditor();       
    });
    
    doEditor();
              
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
