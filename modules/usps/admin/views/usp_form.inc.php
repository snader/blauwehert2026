<?php /* @var Usp $oUsp */ ?>

<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('usp_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn" style="min-height: 600px;">
        <fieldset>
            <legend><?= sysTranslations::get('usp') ?></legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('usp_online_offline_tooltip') ?>" type="radio" <?= $oUsp->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('usp_online_offline_tooltip') ?>" type="radio" <?= !$oUsp->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/>
                            <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oUsp->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="name"><?= sysTranslations::get('global_title') ?> * </label></td>
                        <td><input class="required default" id="name" type="text" autocomplete="off" name="name" value="<?= $oUsp->name ?>" title="<?= sysTranslations::get('usp_no_name') ?>"/></td>
                        <td><span class="error"><?= $oUsp->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <?php if (moduleExists('pages')) { ?>
                        <tr>
                            <td class="withLabel"><label><?= sysTranslations::get('usp_page') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('usp_page_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td>
                                <select name="pageId" class="linkChoice default">
                                    <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                    <?php

                                    function generateOptions($aPages, &$oUsp)
                                    {
                                        foreach ($aPages as $oPage) {
                                            $sLeadingChars = '';
                                            for ($iC = $oPage->level; $iC > 1; $iC--) {
                                                $sLeadingChars .= '--';
                                            }
                                            echo '<option value="' . $oPage->pageId . '" ' . ($oPage->pageId == $oUsp->pageId ? 'selected' : '') . '>' . $sLeadingChars . $oPage->getShortTitle() . '</option>';
                                            generateOptions($oPage->getSubPages('online-all'), $oUsp);
                                        }
                                    }

                                    generateOptions(PageManager::getPagesByFilter(['showAll' => 1, 'online' => 1, 'level' => 1, 'languageId' => AdminLocales::language()]), $oUsp);
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="withLabel"><label for="link"><?= sysTranslations::get('global_link') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('usp_link_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <input class="linkChoice default" title="<?= sysTranslations::get('usp_type_link') ?>" name="link" type="text" id="link" value="<?= $oUsp->link ?>"/>
                        </td>
                        <td><span class="error"><?= $oUsp->isPropValid('link') ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="textLine"><?= sysTranslations::get('usp_textline') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('usp_textline_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <input class="textLineChoice default" title="<?= sysTranslations::get('usp_type_textline') ?>" name="textLine" type="text" id="textLine" value="<?= $oUsp->textLine ?>"/>
                        </td>
                        <td><span class="error"><?= $oUsp->isPropValid('textLine') ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
    <!-- File -->
    <div class="contentColumn">
        <fieldset id="uspFiles">
            <legend><?= sysTranslations::get('global_files') ?></legend>
            <p><i><?= sysTranslations::get('add_svg_file')?></i></p>

            <?php

            if ($oUsp->uspId !== null) {
                $oFileManagerHTML->includeTemplate();
            } else {
                echo '<p><i>' . sysTranslations::get('usp_images_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>
    <!-- /File -->

    <?php
    ?>
    <?php if ($oUsp->fileId == null || $oUsp->imageId !== null) { ?>
        <!-- Image -->
        <div class="contentColumn">
            <fieldset id="uspFiles">
                <legend><?= sysTranslations::get('global_images') ?></legend>
                <?php

                if ($oUsp->uspId !== null) {
                    $oImageManagerHTML->includeTemplate();
                } else {
                    echo '<p><i>' . sysTranslations::get('usp_images_warning') . '</i></p>';
                }
                ?>
            </fieldset>
        </div>
        <!-- /Image -->
    <?php } ?>

</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('usp_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
