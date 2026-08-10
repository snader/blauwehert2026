<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('order_payment_method_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('order_payment_method'); ?></legend>
                <table class="withForm">
                    <tr>
                        <td colspan="3">
                            <div class="clearfix">
                                <div class="tabs">
                                    <div class="tabsHolder cf unselectable"></div>
                                    <?php

                                    foreach (AdminLocales::getLanguages() as $oLanguage) {
                                        $oTranslation = $oPaymentMethod->getTranslations($oLanguage->languageId);
                                        ?>
                                        <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                            <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                            <div class="tabContent">
                                                <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                    <tr>
                                                        <td class="withLabel" style="width: 180px;"><label for="name_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('order_payment_method') ?> *</label></td>
                                                        <td><input id="name_<?= $oLanguage->languageId ?>" class="required default" title="<?= sysTranslations::get('global_set_name') ?>" type="text"
                                                                   name="name[<?= $oLanguage->languageId ?>]" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->name) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oPaymentMethod->isPropValid("name_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="withLabel"><label for="redirectPage_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('order_payment_method_url') ?> *</label></td>
                                                        <td><input id="redirectPage_<?= $oLanguage->languageId ?>" class="required default" title="<?= sysTranslations::get('order_payment_method_url_tooltip') ?>" type="text"
                                                                   name="redirectPage[<?= $oLanguage->languageId ?>]" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->redirectPage) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oPaymentMethod->isPropValid("redirectPage_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
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
                        <td class="withLabel"><label for="priceWithTax"><?= sysTranslations::get('global_price') ?> (<?= sysTranslations::get('global_inc_taxes') ?>)</label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_payment_method_costs') ?>">&nbsp;</div>
                        </td>
                        <td colspan="2">
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input size="6" id="priceWithTax" class="required number" name="price" type="text" value="<?= (is_numeric($oPaymentMethod->price) ? number_format($oPaymentMethod->price, 2, '.', '') : '') ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><label><?= sysTranslations::get('order_payment_method_is_online') ?> *</label></td>
                        <td>
                            <input class="alignRadio" id="isOnlinePaymentMethod_1" name="isOnlinePaymentMethod" <?= $oPaymentMethod->isOnlinePaymentMethod ? 'checked' : '' ?> type="radio" value="1"/> <label
                                    for="isOnlinePaymentMethod_1">ja</label> <input class="alignRadio" id="isOnlinePaymentMethod_0" name="isOnlinePaymentMethod" <?= !$oPaymentMethod->isOnlinePaymentMethod ? 'checked' : '' ?>
                                                                                    style="margin-left: 10px;" type="radio" value="0"/> <label for="isOnlinePaymentMethod_0">nee</label>
                        </td>
                    </tr>

                    <?php if ($oCurrentUser->isAdmin()) { ?>
                        <tr>
                            <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_admin_settings') ?></h2></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="system_name"><?= sysTranslations::get('pages_unique_name') ?></label></td>
                            <td><input id="system_name" class="default" data-rule-remote="<?= ADMIN_FOLDER ?>/betaalmethoden/ajax-checkName?paymentMethodId=<?= $oPaymentMethod->paymentMethodId ?>&<?= CSRFSynchronizerToken::query() ?>"
                                       title="<?= sysTranslations::get('pages_unique_name_tooltip') ?>" type="text" name="system_name" value="<?= $oPaymentMethod->system_name ?>"/></td>
                            <td><span class="error"><?= $oPaymentMethod->isPropValid("system_name") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
                        </tr>
                    <?php } ?>

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
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('order_payment_method_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>

<?php

$sBottomJavascript = <<<EOT
<script>
// initiate prestatie tabs
$(".tabs").prestatieTabs();
</script>
EOT;

$oPageLayout->addJavascript($sBottomJavascript);