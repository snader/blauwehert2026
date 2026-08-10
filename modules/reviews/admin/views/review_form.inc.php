<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('review_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('review') ?></legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('review_online_offline_tooltip') ?>" type="radio" <?= $oReview->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('review_online_offline_tooltip') ?>" type="radio" <?= !$oReview->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oReview->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="localeId"><?= sysTranslations::get('review_localeId') ?> * </label></td>
                        <td>
                            <select class="required" title="<?= sysTranslations::get('review_localeId_title') ?>" id="localeId" name="localeId">
                                <?php

                                foreach (LocaleManager::getLocalesByFilter(['showAll' => true]) AS $oLocale) {
                                    ?>
                                    <option value="<?= $oLocale->localeId ?>" <?= ($oLocale->localeId == $oReview->localeId) ? 'selected' : '' ?>><?= _e($oLocale->getCountry()->nativeName) ?> - <?= _e(
                                            $oLocale->getLanguage()->nativeName
                                        ) ?></option>
                                    <?php

                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oReview->isPropValid("localeId") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="title"><?= sysTranslations::get('global_title') ?> * </label></td>
                        <td><input class="required default" id="title" type="text" autocomplete="off" name="title" value="<?= _e($oReview->title) ?>" title="<?= sysTranslations::get('review_no_title') ?>"/></td>
                        <td><span class="error"><?= $oReview->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="author"><?= sysTranslations::get('review_author') ?> </label></td>
                        <td><input class="default" id="author" type="text" name="author" value="<?= _e($oReview->author) ?>"/></td>
                        <td><span class="error"><?= $oReview->isPropValid("author") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="rating">Waardering *</label></td>
                        <td>
                            <select class="default required" id="rating" name="rating" title="Selecteer een waardering">
                                <option value="">maak een keuze</option>
                                <?php for ($iC = Settings::get('reviewsRatingMin'); $iC <= Settings::get('reviewsRatingMax'); $iC++) { ?>
                                    <option <?= $iC == _e($oReview->rating) ? 'selected' : '' ?> value="<?= $iC ?>"><?= $iC . ' ' . ($iC == 1 ? sysTranslations::get('review_star') : sysTranslations::get('review_stars')) ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oReview->isPropValid("rating") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="link"><?= sysTranslations::get('global_link') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('review_link_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td>
                            <input class="default" name="link" type="text" id="link" value="<?= _e($oReview->link) ?>"/>
                        </td>
                        <td><span class="error"><?= $oReview->isPropValid('link') ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="reference"><?= sysTranslations::get('review_reference') ?> </label></td>
                        <td><input class="default" id="reference" type="text" name="reference" value="<?= _e($oReview->reference) ?>"/></td>
                        <td><span class="error"><?= $oReview->isPropValid("reference") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="3"><label for="review"><?= sysTranslations::get('review_review') ?></label></td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="review" id="review" class="tiny_MCE"><?= _e($oReview->review) ?></textarea></td>
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
        <fieldset id="reviewImages">
            <legend><?= sysTranslations::get('global_images') ?></legend>
            <?php

            if ($oReview->reviewId !== null) {
                $oImageManagerHTML->includeTemplate();
            } else {
                echo '<p><i>' . sysTranslations::get('review_images_warning') . '</i></p>';
            }
            ?>
        </fieldset>
    </div>
    <!-- /Image -->
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('review_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$oPageLayout->addJavascript(
    '
<script>
    initTinyMCE(".tiny_MCE", "/admin/paginas/link-list");
</script>
'
);
?>