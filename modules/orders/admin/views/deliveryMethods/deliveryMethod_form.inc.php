<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('order_delivery_method_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('order_delivery_methods') ?></legend>
                <table class="withForm">
                    <tr>
                        <td colspan="3">
                            <div class="clearfix">
                                <div class="tabs">
                                    <div class="tabsHolder cf unselectable"></div>
                                    <?php

                                    foreach (AdminLocales::getLanguages() as $oLanguage) {
                                        $oTranslation = $oDeliveryMethod->getTranslations($oLanguage->languageId);
                                        ?>
                                        <div class="tabDetails cf" data-prestatietabs-name="<?= $oLanguage->code ?>">
                                            <div class="tabLabel unselectable"><?= strtoupper($oLanguage->code) ?></div>
                                            <div class="tabContent">
                                                <table cellpadding="0" cellspacing="0" border="0" style="padding: 0; margin: 0;">
                                                    <tr>
                                                        <td class="withLabel" style="width: 116px;"><label for="name_<?= $oLanguage->languageId ?>"><?= sysTranslations::get('order_delivery_methods') ?> *</label></td>
                                                        <td><input id="name_<?= $oLanguage->languageId ?>" class="required default" title="<?= sysTranslations::get('global_set_name') ?>" type="text"
                                                                   name="name[<?= $oLanguage->languageId ?>]" value="<?= (is_numeric(http_get('param2')) && $oTranslation ? _e($oTranslation->name) : '') ?>"/></td>
                                                        <td><span class="error"><?= $oDeliveryMethod->isPropValid("name_" . $oLanguage->languageId) ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
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
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_delivery_method_price_tooltip') ?>">&nbsp;</div>
                        </td>
                        <td colspan="2">
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input size="6" id="priceWithTax" class="required number" name="price" type="text" value="<?= (is_numeric($oDeliveryMethod->price) ? number_format($oDeliveryMethod->price, 2, '.', '') : '') ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="freeFromPrice"><?= sysTranslations::get('order_delivery_method_gratis') ?>(<?= (Settings::get('taxIncluded') ? sysTranslations::get('global_inc_taxes') : sysTranslations::get(
                                    'global_excl_taxes'
                                )) ?>.)</label></td>
                        <td colspan="2">
                            <?= Settings::getDefault('currency-symbol', '€') ?> <input size="6" id="freeFromPrice" name="freeFromPrice" class="number" type="text"
                                          value="<?= (is_numeric($oDeliveryMethod->freeFromPrice) ? number_format($oDeliveryMethod->freeFromPrice, 2, '.', '') : '') ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="deliveryTime"><?= sysTranslations::get('order_delivery_method_time') ?></label></td>
                        <td>
                            <input id="deliveryTime" name="deliveryTime" class="default" type="text" value="<?= $oDeliveryMethod->deliveryTime ?>"/>
                        </td>
                    </tr>
                    <?php

                    $aPaymentMethods = PaymentMethodManager::getPaymentMethodsByFilter();
                    if (!empty($aPaymentMethods)) {
                        ?>
                        <tr>
                            <td colspan="3"><h2><?= sysTranslations::get('order_delivery_method_payments_accepted') ?></h2></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php

                                # set current categories in an array to check whether to select/check the options
                                $aPaymentMethodIds = [];
                                foreach ($oDeliveryMethod->getPaymentMethods('all') AS $oPaymentMethod) {
                                    $aPaymentMethodIds[] = $oPaymentMethod->paymentMethodId;
                                }

                                echo '<ul style="list-style: none; margin: 0; padding: 0;">';
                                foreach ($aPaymentMethods as $oPaymentMethod) {
                                    echo '<li><input class="alignCheckbox required" title="' . sysTranslations::get(
                                            'order_payment_method_choose'
                                        ) . '" id="paymentMethod_' . $oPaymentMethod->paymentMethodId . '" type="checkbox" name="paymentMethodIds[]" value="' . $oPaymentMethod->paymentMethodId . '" ' . (in_array(
                                            $oPaymentMethod->paymentMethodId,
                                            $aPaymentMethodIds
                                        ) ? 'CHECKED' : '') . ' /> <label for="paymentMethod_' . $oPaymentMethod->paymentMethodId . '">' . _e($oPaymentMethod->getTranslations('auto-admin')->name) . '</label>';
                                }
                                echo '</ul>';
                                ?>
                            </td>
                            <td><span class="error"><?= $oDeliveryMethod->isPropValid("categories") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <?php

                    }
                    ?>
                    <?php if ($oCurrentUser->isAdmin()) { ?>
                        <tr>
                            <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_admin_settings') ?></h2></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="system_name"><?= sysTranslations::get('pages_unique_name') ?></label></td>
                            <td><input id="system_name" class="default" data-rule-remote="<?= ADMIN_FOLDER ?>/verzendmethoden/ajax-checkName?deliveryMethodId=<?= $oDeliveryMethod->deliveryMethodId ?>&<?= CSRFSynchronizerToken::query() ?>"
                                       title="<?= sysTranslations::get('pages_unique_name_tooltip') ?>" type="text" name="system_name" value="<?= $oDeliveryMethod->system_name ?>"/></td>
                            <td><span class="error"><?= $oDeliveryMethod->isPropValid("system_name") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
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
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('order_delivery_method_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>

<?php

$sBottomJavascript = <<<EOT
<script>
// initiate prestatie tabs
$(".tabs").prestatieTabs();
</script>
EOT;

$oPageLayout->addJavascript($sBottomJavascript);
