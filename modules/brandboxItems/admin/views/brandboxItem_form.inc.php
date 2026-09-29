<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('brandbox_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn" style="min-height: 600px;">
        <fieldset>
            <legend><?= sysTranslations::get('brandbox_item') ?></legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('brandbox_online_offline_tooltip') ?>" type="radio" <?= $oBrandboxItem->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('brandbox_online_offline_tooltip') ?>" type="radio" <?= !$oBrandboxItem->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/>
                            <label for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oBrandboxItem->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="name"><?= sysTranslations::get('global_name') ?> * </label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('brandbox_name_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td><input class="required default" id="name" type="text" autocomplete="off" name="name" value="<?= $oBrandboxItem->name ?>" title="<?= sysTranslations::get('brandbox_no_name') ?>"/> <span
                                    style="font-size: 11px; font-style: italic;">(<?= sysTranslations::get('brandbox_not_show') ?>)</span></td>
                        <td><span class="error"><?= $oBrandboxItem->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <?php if (moduleExists('pages')) { ?>
                        <tr>
                            <td class="withLabel"><label><?= sysTranslations::get('brandbox_link_page') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('brandbox_link_page_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select name="pageId" class="linkChoice default">
                                    <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                    <?php

                                    function generateOptions($aPages, &$oBrandboxItem)
                                    {
                                        foreach ($aPages as $oPage) {
                                            $sLeadingChars = '';
                                            for ($iC = $oPage->level; $iC > 1; $iC--) {
                                                $sLeadingChars .= '--';
                                            }
                                            echo '<option value="' . $oPage->pageId . '" ' . ($oPage->pageId == $oBrandboxItem->pageId ? 'selected' : '') . '>' . $sLeadingChars . $oPage->getShortTitle() . '</option>';
                                            generateOptions($oPage->getSubPages('online-all'), $oBrandboxItem);
                                        }
                                    }

                                    generateOptions(PageManager::getPagesByFilter(['showAll' => 1, 'online' => 1, 'level' => 1, 'languageId' => AdminLocales::language()]), $oBrandboxItem);
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (moduleExists('newsItems')) { ?>
                        <tr>
                            <td class="withLabel"><label><?= sysTranslations::get('brandbox_link_news') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('brandbox_link_news_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select class="linkChoice default" name="newsItemId" id="newsItemId">
                                    <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                    <?php

                                    $aNewsItems = NewsItemManager::getNewsItemsByFilter(['languageId' => AdminLocales::language()]);
                                    if (count($aNewsItems) > 0) {
                                        foreach ($aNewsItems as $oNewsItem) {
                                            echo '<option value="' . $oNewsItem->newsItemId . '" ' . ($oNewsItem->newsItemId == $oBrandboxItem->newsItemId ? 'selected' : '') . '>' . $oNewsItem->title . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="withLabel"><label for="link"><?= sysTranslations::get('global_link') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('brandbox_link_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <input class="linkChoice default" title="<?= sysTranslations::get('brandbox_type_link') ?>" name="link" type="text" id="link" value="<?= $oBrandboxItem->link ?>"/>
                        </td>
                        <td><span class="error"><?= $oBrandboxItem->isPropValid('link') ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="line1"><?= sysTranslations::get('brandbox_rule') ?> 1</label></td>
                        <td colspan="2"><input class="default" id="line1" type="text" autocomplete="off" name="line1" value="<?= $oBrandboxItem->line1 ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="line2"><?= sysTranslations::get('brandbox_rule') ?> 2</label></td>
                        <td colspan="2"><input class="default" id="line2" type="text" autocomplete="off" name="line2" value="<?= $oBrandboxItem->line2 ?>"/></td>
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
    <!-- Image -->
    <div class="contentColumn">
        <fieldset id="brandboxImages">
            <legend><?= sysTranslations::get('global_images') ?></legend>
            <?php if ($oBrandboxItem->brandboxItemId !== null) { ?>
                <?php $oImageManagerHTML->includeTemplate(); ?>
            <?php } else { ?>
                <p><i><?= sysTranslations::get('brandbox_images_warning') ?></i></p>
            <?php } ?>
        </fieldset>
    </div>
    <!-- /Image -->
    <!-- Video link -->
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('global_videolinks') ?></legend>
            <?php if ($oBrandboxItem->brandboxItemId !== null) { ?>
                <br/><b><?= sysTranslations::get('brandbox_video_info') ?></b><br/>
                <small><?= sysTranslations::get('global_videolinks_info') ?></small><br/><br/>
                <?php $oVideoLinkManagerHTML->includeTemplate(); ?>
            <?php } else { ?>
                <p><i><?= sysTranslations::get('brandbox_video_warning') ?></i></p>
            <?php } ?>
        </fieldset>
    </div>
    <!-- /Video link -->

</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('brandbox_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# define and add javascript to bottom
$sBottomJavascript = <<<EOT
<script type="text/javascript">
        $('.linkChoice').change(function(){
            var filled = null;
            $('.linkChoice').each(function(index, element){
                if($(element).val() != ''){
                    filled = element;
                }
            });
            if(filled === null){
                $('.linkChoice').prop('disabled', false);
            }else{
                $('.linkChoice').not(filled).prop('disabled', true);
            }
        });
        
        $('#link').keyup(function(){
            $(this).change();
        });
        
        $('#link').change();
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>