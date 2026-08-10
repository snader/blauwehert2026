<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('order_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('order_order') ?></legend>
                <table class="withForm">
                    <tr>
                        <td style="width: 123px;"><label for="orderNumber"><?= sysTranslations::get('order_order_number') ?> *</label></td>
                        <td colspan="2"><input id="orderNumber" type="text" value="<?= $oOrder->orderId ?>" disabled/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="email"><?= sysTranslations::get('global_email') ?> *</label></td>
                        <td><input id="email" class="autofocus required email default" data-msg-required="<?= sysTranslations::get('global_email_required_tooltip') ?>"
                                   data-rule-email="<?= sysTranslations::get('global_valid_email_tooltip') ?>" title="<?= sysTranslations::get('global_valid_email_tooltip') ?>" type="text" autocomplete="off" name="email"
                                   value="<?= _e($oOrder->email) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("email") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="3"><h2><?= sysTranslations::get('order_invoice_info') ?></h2></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_companyName"><?= sysTranslations::get('global_company_name') ?></label></td>
                        <td colspan="2"><input class="default" id="invoice_companyName" type="text" name="invoice_companyName" value="<?= _e($oOrder->invoice_companyName) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><?= sysTranslations::get('global_gender') ?> *</td>
                        <td class="withLabel">
                            <input class="alignRadio required" title="<?= sysTranslations::get('global_select_gender') ?> (<?= sysTranslations::get('order_invoice_info') ?>)"
                                   type="radio" <?= $oOrder->invoice_gender == 'M' ? 'CHECKED' : '' ?> id="invoice_gender_M" name="invoice_gender" value="M"/> <label for="invoice_gender_M"><?= sysTranslations::get('global_man') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('global_select_gender') ?> (<?= sysTranslations::get('order_invoice_info') ?>)"
                                   type="radio" <?= $oOrder->invoice_gender == 'F' ? 'CHECKED' : '' ?> id="invoice_gender_F" name="invoice_gender" value="F"/> <label for="invoice_gender_F"><?= sysTranslations::get('global_woman') ?></label>
                        </td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_gender") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_firstName"><?= sysTranslations::get('global_first_name') ?> *</label></td>
                        <td><input id="invoice_firstName" class="required default" title="<?= sysTranslations::get('global_first_name_tooltip') ?> (<?= sysTranslations::get('order_invoice_info') ?>)" type="text" name="invoice_firstName"
                                   value="<?= _e($oOrder->invoice_firstName) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_firstName") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_insertion"><?= sysTranslations::get('global_insertion') ?></label></td>
                        <td><input id="invoice_insertion" class="default" type="text" name="invoice_insertion" value="<?= _e($oOrder->invoice_insertion) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_insertion") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_lastName"><?= sysTranslations::get('global_surname') ?> *</label></td>
                        <td><input id="invoice_lastName" class="required default" title="<?= sysTranslations::get('global_surname_tooltip') ?> (<?= sysTranslations::get('order_invoice_info') ?>)" type="text" name="invoice_lastName"
                                   value="<?= _e($oOrder->invoice_lastName) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_lastName") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_address"><?= sysTranslations::get('global_address') ?> *</label></td>
                        <td><input id="invoice_address" class="required default" title="<?= sysTranslations::get('global_address_tooltip') ?> (<?= sysTranslations::get('order_invoice_info') ?>)" type="text" name="invoice_address"
                                   value="<?= _e($oOrder->invoice_address) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_address") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_houseNumber"><?= sysTranslations::get('order_house_number') ?> *</label></td>
                        <td><input id="invoice_houseNumber" class="required number default" title="<?= sysTranslations::get('order_house_number_tooltip') ?> (<?= sysTranslations::get('order_invoice_info') ?>)" type="text"
                                   name="invoice_houseNumber" value="<?= _e($oOrder->invoice_houseNumber) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_houseNumber") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_houseNumberAddition"><?= sysTranslations::get('global_address_addition') ?></label></td>
                        <td colspan="2"><input class="default" id="invoice_houseNumberAddition" type="text" name="invoice_houseNumberAddition" value="<?= _e($oOrder->invoice_houseNumberAddition) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_postalCode"><?= sysTranslations::get('global_postal_code') ?> *</label></td>
                        <td><input id="invoice_postalCode" class="required default" title="<?= sysTranslations::get('global_postcode_tooltip') ?>  (<?= sysTranslations::get('order_invoice_info') ?>)" type="text" name="invoice_postalCode"
                                   value="<?= _e($oOrder->invoice_postalCode) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_postalCode") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_city"><?= sysTranslations::get('order_city') ?> *</label></td>
                        <td><input id="invoice_city" class="required default" title="<?= sysTranslations::get('global_city_tooltip') ?> (<?= sysTranslations::get('order_invoice_info') ?>)" type="text" name="invoice_city"
                                   value="<?= _e($oOrder->invoice_city) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("invoice_city") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="invoice_phone"><?= sysTranslations::get('global_phone') ?></label></td>
                        <td colspan="2"><input class="default" id="invoice_phone" type="text" name="invoice_phone" value="<?= _e($oOrder->invoice_phone) ?>"/></td>
                    </tr>
                    <tr>
                        <td colspan="3"><h2><?= sysTranslations::get('order_delivery_info') ?></h2></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_companyName"><?= sysTranslations::get('global_company_name') ?></label></td>
                        <td colspan="2"><input class="default" id="delivery_companyName" type="text" name="delivery_companyName" value="<?= _e($oOrder->delivery_companyName) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><?= sysTranslations::get('global_gender') ?> *</td>
                        <td class="withLabel">
                            <input class="alignRadio required" title="<?= sysTranslations::get('global_select_gender') ?> (<?= sysTranslations::get('order_delivery_info') ?>)"
                                   type="radio" <?= $oOrder->delivery_gender == 'M' ? 'CHECKED' : '' ?> id="delivery_gender_M" name="delivery_gender" value="M"/> <label for="delivery_gender_M"><?= sysTranslations::get(
                                    'global_man'
                                ) ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('global_select_gender') ?> (<?= sysTranslations::get('order_delivery_info') ?>)"
                                   type="radio" <?= $oOrder->delivery_gender == 'F' ? 'CHECKED' : '' ?> id="delivery_gender_F" name="delivery_gender" value="F"/> <label for="delivery_gender_F"><?= sysTranslations::get(
                                    'global_woman'
                                ) ?></label>
                        </td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_gender") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_firstName"><?= sysTranslations::get('global_first_name') ?> *</label></td>
                        <td><input id="delivery_firstName" class="required default" title="<?= sysTranslations::get('global_first_name_tooltip') ?> (<?= sysTranslations::get('order_delivery_info') ?>)" type="text" name="delivery_firstName"
                                   value="<?= _e($oOrder->delivery_firstName) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_firstName") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_insertion"><?= sysTranslations::get('global_insertion') ?></label></td>
                        <td><input id="delivery_insertion" class="default" type="text" name="delivery_insertion" value="<?= _e($oOrder->delivery_insertion) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_insertion") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_lastName"><?= sysTranslations::get('global_surname') ?> *</label></td>
                        <td><input id="delivery_lastName" class="required default" title="<?= sysTranslations::get('global_surname_tooltip') ?> (<?= sysTranslations::get('order_delivery_info') ?>)" type="text" name="delivery_lastName"
                                   value="<?= _e($oOrder->delivery_lastName) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_lastName") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_address"><?= sysTranslations::get('global_address') ?> *</label></td>
                        <td><input id="delivery_address" class="required default" title="<?= sysTranslations::get('global_address_tooltip') ?> (<?= sysTranslations::get('order_delivery_info') ?>)" type="text" name="delivery_address"
                                   value="<?= _e($oOrder->delivery_address) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_address") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_houseNumber"><?= sysTranslations::get('order_house_number') ?> *</label></td>
                        <td><input id="delivery_houseNumber" class="required number default" title="<?= sysTranslations::get('order_house_number') ?> (<?= sysTranslations::get('order_invoice_info') ?>)" type="text"
                                   name="delivery_houseNumber" value="<?= _e($oOrder->delivery_houseNumber) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_houseNumber") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_houseNumberAddition"><?= sysTranslations::get('global_address_addition') ?></label></td>
                        <td colspan="2"><input class="default" id="delivery_houseNumberAddition" type="text" name="delivery_houseNumberAddition" value="<?= _e($oOrder->delivery_houseNumberAddition) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_postalCode"><?= sysTranslations::get('global_postal_code') ?> *</label></td>
                        <td><input id="delivery_postalCode" class="required default" title="<?= sysTranslations::get('global_postcode_tooltip') ?> (<?= sysTranslations::get('order_delivery_info') ?>)" type="text" name="delivery_postalCode"
                                   value="<?= _e($oOrder->delivery_postalCode) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_postalCode") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="delivery_city"><?= sysTranslations::get('order_city') ?> *</label></td>
                        <td><input id="delivery_city" class="required default" title="<?= sysTranslations::get('global_city_tooltip') ?> (<?= sysTranslations::get('order_delivery_info') ?>)" type="text" name="delivery_city"
                                   value="<?= _e($oOrder->delivery_city) ?>"/></td>
                        <td><span class="error"><?= $oOrder->isPropValid("delivery_city") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
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
    <div class="contentColumn" style="margin-top:21px !important;">
        <form id="downloadPdf" method="POST" action="">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="getPdf" name="action"/>
            <input type="submit" value="<?= sysTranslations::get('order_download_pdf') ?>" name="orderPdf"/>
        </form>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('global_products') ?></legend>
            <table class="sorted" style="width: 100%; margin-bottom: 10px;">
                <thead>
                <tr>
                    <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_product_name') ?></th>
                    <th class="{sorter:false} nonSorted"><?= sysTranslations::get('catalog_brand_name') ?></th>
                    <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_amount') ?></th>
                    <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_cancelled') ?>
                        <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_cancelled_tooltip') ?>">&nbsp;</div>
                    </th>
                    <th class="{sorter:false} nonSorted" style="width: 60px;"><?= sysTranslations::get('global_price') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach ($oOrder->getProducts() as $oProduct) {
                    ?>
                    <tr>
                        <td>
                            <?= (is_numeric($oProduct->catalogProductId) ? '<a title="' . sysTranslations::get(
                                    'order_see_original'
                                ) . '" href="' . ADMIN_FOLDER . '/catalogus/bewerken/' . $oProduct->catalogProductId . '">' . $oProduct->productName . '</a>' : $oProduct->productName) ?>
                            <?php

                            if ($oProduct->productSizeName || $oProduct->productColorName) {
                                echo $oProduct->productSizeName ? ' | ' . $oProduct->productSizeName : '';
                                echo $oProduct->productColorName ? ' | ' . $oProduct->productColorName : '';
                                echo ' |';
                            }
                            ?>
                        </td>
                        <td><?= $oProduct->brandName ?></td>
                        <td><?= $oProduct->amount ?></td>
                        <td><?= $oProduct->substractedFromStock ?></td>
                        <td style="text-align: right;"><?= decimal2valuta($oProduct->getSubTotalPrice(true)) ?></td>
                    </tr>
                    <?php

                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4"><?= sysTranslations::get('global_subtotal') ?></td>
                    <td style="text-align: right;"><?= decimal2valuta($oOrder->getSubtotalProducts(true)) ?></td>
                </tr>
                <tr>
                    <td colspan="4"><?= sysTranslations::get('global_discount') ?></td>
                    <td style="text-align: right;"><?= decimal2valuta($oOrder->getDiscount(true)) ?></td>
                </tr>
                <tr>
                    <td colspan="4"><?= sysTranslations::get('order_delivery_method') ?>: <?= $oOrder->deliveryMethodName ?></td>
                    <td style="text-align: right;"><?= decimal2valuta($oOrder->getDeliveryPrice()) ?></td>
                </tr>
                <tr>
                    <td colspan="4"><?= sysTranslations::get('order_payment_method') ?>: <?= $oOrder->paymentMethodName ?></td>
                    <td style="text-align: right;"><?= decimal2valuta($oOrder->getPaymentPrice()) ?></td>
                </tr>
                <tr>
                    <td colspan="4"><b><?= sysTranslations::get('global_total') ?></b></td>
                    <td style="text-align: right;"><b><?= decimal2valuta($oOrder->getTotal(true)) ?></b></td>
                </tr>
                <tr>
                    <td colspan="4"><?= sysTranslations::get('global_vat') ?></td>
                    <td style="text-align: right;"><?= decimal2valuta($oOrder->getBTW()) ?></td>
                </tr>
                <tr>
                    <td colspan="4"><?= sysTranslations::get('global_total') ?> (<?= sysTranslations::get('global_excl_taxes') ?>)</td>
                    <td style="text-align: right;"><?= decimal2valuta($oOrder->getTotal(false)) ?></td>
                </tr>
                </tfoot>
            </table>
        </fieldset>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('order_order_status') ?></legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" name="action" value="saveStatus"/>
                <table class="withForm">
                    <tr>
                        <td class="withLabel" style="width: 116px;"><label for="status"><?= sysTranslations::get('global_status') ?>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_status_select_tooltip') ?>">&nbsp;</div>
                            </label></td>
                        <td>
                            <select id="status" class="required default" title="><?= sysTranslations::get('order_choose_status') ?>" name="status">
                                <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                <?php

                                foreach (OrderStatusManager::getAllStatuses() as $iStatus) {
                                    echo '<option value="' . $iStatus . '" ' . ($oOrder->status == $iStatus ? 'selected' : '') . '>' . OrderStatusManager::getLabelByStatus($iStatus) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oStatus->isPropValid("status") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="notifyCustomer"><?= sysTranslations::get('order_notification') ?>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_notification_tooltip') ?>">&nbsp;</div>
                            </label></td>
                        <td>
                            <input class="alignCheckbox" id="notifyCustomer" name="notifyCustomer" onclick="this.checked ? $('.commentRow').show() : $('.commentRow').hide()" type="checkbox" value="1"/> <label
                                    for="notifyCustomer"><?= sysTranslations::get('order_send_notification') ?></label>
                        </td>
                    </tr>
                    <tr class="hide commentRow">
                        <td colspan="2"><label for="comment"><?= sysTranslations::get('order_status_commentary') ?>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_status_commentary_tooltip') ?>">&nbsp;</div>
                            </label></td>
                    </tr>
                    <tr class="hide commentRow">
                        <td colspan="2">
                            <textarea class="default" name="comment"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><input type="submit" value="<?= sysTranslations::get('order_save_status') ?>" name="saveStatus"/></td>
                    </tr>
                </table>
            </form>
            <hr/>
            <h3><?= sysTranslations::get('order_status_history') ?></h3>
            <table class="sorted" style="margin-bottom: 10px;">
                <thead>
                <tr>
                    <th class="{sorter:'dateNL'}"><?= sysTranslations::get('global_date_time') ?></th>
                    <th><?= sysTranslations::get('global_status') ?></th>
                    <th><?= sysTranslations::get('global_created_by') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach ($oOrder->getStatusHistory() as $i => $oStatus) {
                    echo '<tr>';
                    echo '<td>' . ($i == 0 ? '<b>' : '') . strftime('%d-%m-%Y %H:%M:%S', strtotime($oStatus->timestamp)) . ($i == 0 ? '</b>' : '') . '</td>';
                    echo '<td>' . ($i == 0 ? '<b>' : '') . $oStatus->getLabel() . ($i == 0 ? '</b>' : '') . '</td>';
                    echo '<td>' . ($i == 0 ? '<b>' : '') . $oStatus->getCreatedBy() . ($i == 0 ? '</b>' : '') . '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('order_online_payments') ?></legend>
            <?php

            if (count($oOrder->getPaymentHistory()) > 0) {
                ?>
                <table class="sorted" style="margin-bottom: 10px;">
                    <thead>
                    <tr>
                        <th class="{sorter:'dateNL'}"><?= sysTranslations::get('order_started_payment') ?></th>
                        <th class="{sorter:'dateNL'}"><?= sysTranslations::get('order_last_update') ?></th>
                        <th><?= sysTranslations::get('order_payment_method') ?></th>
                        <th><?= sysTranslations::get('global_price') ?></th>
                        <th><?= sysTranslations::get('global_status') ?></th>
                        <th><?= sysTranslations::get('order_external_status') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($oOrder->getPaymentHistory() as $i => $oPayment) {
                        echo '<tr>';
                        echo '<td>' . ($i == 0 ? '<b>' : '') . strftime('%d-%m-%Y %H:%M:%S', strtotime($oPayment->created)) . ($i == 0 ? '</b>' : '') . '</td>';
                        echo '<td>' . ($i == 0 ? '<b>' : '') . (empty($oPayment->modified) ? '' : strftime('%d-%m-%Y %H:%M:%S', strtotime($oPayment->modified))) . ($i == 0 ? '</b>' : '') . '</td>';
                        echo '<td>' . ($i == 0 ? '<b>' : '') . $oPayment->paymentMethodName . ($i == 0 ? '</b>' : '') . '</td>';
                        echo '<td>' . ($i == 0 ? '<b>' : '') . decimal2valuta($oPayment->price) . ($i == 0 ? '</b>' : '') . '</td>';
                        echo '<td>' . ($i == 0 ? '<b>' : '') . $oPayment->getStatusLabel() . ($i == 0 ? '</b>' : '') . '</td>';
                        echo '<td>' . ($i == 0 ? '<b>' : '') . $oPayment->externalStatus . ($i == 0 ? '</b>' : '') . '</td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <?php

            } else {
                echo '<p>' . sysTranslations::get('order_no_online_payments') . '</p>';
            }
            ?>
        </fieldset>
    </div>

</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('order_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>

