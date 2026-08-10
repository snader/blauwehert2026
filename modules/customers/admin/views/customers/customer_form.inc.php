<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <input type="hidden" value="<?= $oCustomer->didSignOffForEmail ?>" name="didSignOffForEmail"/>
            <fieldset>
                <legend><?= sysTranslations::get('customer_client') ?></legend>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_set_online') ?>" type="radio" <?= $oCustomer->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_set_online') ?>" type="radio" <?= !$oCustomer->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oCustomer->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" style="width: 120px;"><label for="email"><?= sysTranslations::get('global_email') ?> *</label></td>
                        <td><input size="50" id="email" class="autofocus required email"
                                   data-rule-remote="<?= ADMIN_FOLDER . '/' . http_get('controller') ?>?ajax=checkEmail<?= $oCustomer->customerId === null ? '' : '&customerId=' . $oCustomer->customerId ?>"
                                   data-msg-remote="<?= sysTranslations::get('global_email_required_tooltip') ?>" data-msg-email="<?= sysTranslations::get('global_valid_email_tooltip') ?>"
                                   data-msg-required="<?= sysTranslations::get('global_email_required_tooltip') ?>" title="<?= sysTranslations::get('global_enter_email_tooltip') ?>" type="text" autocomplete="off" name="email"
                                   value="<?= _e($oCustomer->email) ?>"/></td>
                        <?php

                        $sEmailPropError = '';
                        if (!$oCustomer->isPropValid("email")) {
                            $sEmailPropError .= ($sEmailPropError == '' ? '' : ', ') . sysTranslations::get('global_field_not_completed');
                        }
                        if (!$oCustomer->isPropValid("emailExists")) {
                            $sEmailPropError .= ($sEmailPropError == '' ? '' : ', ') . sysTranslations::get('global_email_in_use_2');
                        }
                        ?>
                        <td><span class="error"><?= $sEmailPropError ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="password"><?= sysTranslations::get('global_new_password') ?> **</label></td>
                        <td><input id="password" class="<?= $oCustomer->customerId === null ? 'required pasword' : 'password' ?>" title="<?= sysTranslations::get('user_secure_password_tooltip') ?>" autocomplete="off" type="text"
                                   name="password" value=""/></td>
                        <td><em>&nbsp;(** <?= sysTranslations::get('user_password_empty') ?>)</em> <span class="error"><?= $oCustomer->isPropValid("password") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="companyName"><?= sysTranslations::get('global_company_name') ?></label></td>
                        <td colspan="2"><input size="50" id="companyName" type="text" name="companyName" value="<?= _e($oCustomer->companyName) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="companyAddress"><?= sysTranslations::get('customer_company_address') ?></label></td>
                        <td colspan="2"><input size="50" id="companyAddress" type="text" name="companyAddress" value="<?= _e($oCustomer->companyAddress) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="companyPostalCode"><?= sysTranslations::get('customer_company_postal_code') ?></label></td>
                        <td colspan="2"><input size="50" id="companyPostalCode" type="text" name="companyPostalCode" value="<?= _e($oCustomer->companyPostalCode) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="companyCity"><?= sysTranslations::get('customer_company_city') ?></label></td>
                        <td colspan="2"><input size="50" id="companyCity" type="text" name="companyCity" value="<?= _e($oCustomer->companyCity) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="companyEmail"><?= sysTranslations::get('customer_company_email') ?></label></td>
                        <td colspan="2"><input size="50" id="companyEmail" class="email" data-msg-required="<?= sysTranslations::get('global_email_required_tooltip') ?>"
                                               data-msg-email="<?= sysTranslations::get('global_valid_email_tooltip') ?>" type="text" name="companyEmail" value="<?= _e($oCustomer->companyEmail) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="companyPhone"><?= sysTranslations::get('customer_company_phone') ?></label></td>
                        <td colspan="2"><input size="50" id="companyPhone" type="text" name="companyPhone" value="<?= _e($oCustomer->companyPhone) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="companyWebsite"><?= sysTranslations::get('customer_company_website') ?></label></td>
                        <td colspan="2"><input size="50" id="companyWebsite" type="text" name="companyWebsite" value="<?= _e($oCustomer->companyWebsite) ?>"/></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('global_gender') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('global_select_gender') ?>" type="radio" <?= $oCustomer->gender == 'M' ? 'CHECKED' : '' ?> id="gender_M" name="gender" value="M"/> <label
                                    for="gender_M"><?= sysTranslations::get('global_man') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('global_select_gender') ?>" type="radio" <?= $oCustomer->gender == 'F' ? 'CHECKED' : '' ?> id="gender_F" name="gender" value="F"/> <label
                                    for="gender_F"><?= sysTranslations::get('global_woman') ?></label>
                        </td>
                        <td><span class="error"><?= $oCustomer->isPropValid("gender") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="firstName"><?= sysTranslations::get('global_first_name') ?> *</label></td>
                        <td><input size="50" id="firstName" class="required" title="<?= sysTranslations::get('global_first_name_tooltip') ?>" type="text" name="firstName" value="<?= _e($oCustomer->firstName) ?>"/></td>
                        <td><span class="error"><?= $oCustomer->isPropValid("firstName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="insertion"><?= sysTranslations::get('global_insertion') ?></label></td>
                        <td><input size="50" id="insertion" title="<?= sysTranslations::get('global_insertion_tooltip') ?>" type="text" name="insertion" value="<?= _e($oCustomer->insertion) ?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="lastName"><?= sysTranslations::get('global_surname') ?> *</label></td>
                        <td><input size="50" id="lastName" class="required" title="<?= sysTranslations::get('global_surname_tooltip') ?>" type="text" name="lastName" value="<?= _e($oCustomer->lastName) ?>"/></td>
                        <td><span class="error"><?= $oCustomer->isPropValid("lastName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="address"><?= sysTranslations::get('global_address') ?> *</label></td>
                        <td><input size="50" id="address" class="required" title="<?= sysTranslations::get('global_address_tooltip') ?>" type="text" name="address" value="<?= _e($oCustomer->address) ?>"/></td>
                        <td><span class="error"><?= $oCustomer->isPropValid("address") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="houseNumber"><?= sysTranslations::get('global_address_number') ?> *</label></td>
                        <td><input size="50" id="houseNumber" class="required number" title="<?= sysTranslations::get('global_address_number_tooltip') ?>" type="text" name="houseNumber" value="<?= _e($oCustomer->houseNumber) ?>"/></td>
                        <td><span class="error"><?= $oCustomer->isPropValid("houseNumber") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="houseNumberAddition"><?= sysTranslations::get('global_address_addition') ?></label></td>
                        <td colspan="2"><input size="50" id="houseNumberAddition" type="text" name="houseNumberAddition" value="<?= _e($oCustomer->houseNumberAddition) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="postalCode"><?= sysTranslations::get('global_postal_code') ?> *</label></td>
                        <td><input size="50" id="postalCode" class="required" title="<?= sysTranslations::get('global_postal_code_tooltip') ?>" type="text" name="postalCode" value="<?= _e($oCustomer->postalCode) ?>"/></td>
                        <td><span class="error"><?= $oCustomer->isPropValid("postalCode") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="city"><?= sysTranslations::get('global_city') ?> *</label></td>
                        <td><input size="50" id="city" class="required" title="<?= sysTranslations::get('global_city_tooltip') ?>" type="text" name="city" value="<?= _e($oCustomer->city) ?>"/></td>
                        <td><span class="error"><?= $oCustomer->isPropValid("city") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="mobilePhone"><?= sysTranslations::get('global_mobile_number') ?></label></td>
                        <td colspan="2"><input size="50" id="mobilePhone" type="text" name="mobilePhone" value="<?= _e($oCustomer->mobilePhone) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="phone"><?= sysTranslations::get('global_phone') ?></label></td>
                        <td colspan="2"><input size="50" id="phone" type="text" name="phone" value="<?= _e($oCustomer->phone) ?>"/></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="fax"><?= sysTranslations::get('global_fax') ?></label></td>
                        <td colspan="2"><input size="50" id="fax" type="text" name="fax" value="<?= _e($oCustomer->fax) ?>"/></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr>
                            <legend style="margin-left: -10px;"><?= sysTranslations::get('global_contact_settings') ?></legend>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <?= sysTranslations::get('customer_signed_group') ?>
                            <br>
                            <?php foreach (CustomerGroupManager::getAllCustomerGroups() as $oCustomerGroup) { ?>
                                <label for="customerGroup<?= $oCustomerGroup->customerGroupId ?>">
                                    <input type="checkbox" name="customerGroupIds[]" id="customerGroup<?= $oCustomerGroup->customerGroupId ?>" value="<?= $oCustomerGroup->customerGroupId ?>" <?= $oCustomer->isLinkedToCustomerGroup(
                                        $oCustomerGroup->customerGroupId
                                    ) ? 'checked="checked"' : '' ?>><?= _e($oCustomerGroup->title) ?>
                                </label>
                                <br>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('customer_contact_sms') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_contact_sms_tooltip') ?>" type="radio" <?= $oCustomer->contactBySms ? 'CHECKED' : '' ?> id="contactBySms_1" name="contactBySms"
                                   value="1"/> <label for="contactBySms_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_contact_sms_tooltip') ?>" type="radio" <?= !$oCustomer->contactBySms ? 'CHECKED' : '' ?> id="contactBySms_0" name="contactBySms"
                                   value="0"/> <label for="contactBySms_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oCustomer->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('customer_contact_email') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_contact_email_tooltip') ?>" type="radio" <?= $oCustomer->contactByEmail ? 'CHECKED' : '' ?> id="contactByEmail_1" name="contactByEmail"
                                   value="1"/> <label for="contactByEmail_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_contact_email_tooltip') ?>" type="radio" <?= !$oCustomer->contactByEmail ? 'CHECKED' : '' ?> id="contactByEmail_0"
                                   name="contactByEmail" value="0"/> <label for="contactByEmail_0"><?= sysTranslations::get('global_no') ?></label>
                            <?php if ($oCustomer->didSignOffForEmail) { ?>
                                <span class="importantMessage"><?= sysTranslations::get('customer_client_logoff') ?></span>
                            <?php } ?>
                            <?php if (is_numeric($oCustomer->bounceRuleId) && !empty($oCustomer->bounceRuleTitle) && !empty($oCustomer->bounceRuleDescription)) { ?>
                                <span class="importantMessage">(De klant staat op de bouncelijst vanwege de reden "<?= _e($oCustomer->bounceRuleTitle) ?>" en omschrijving "<?= _e($oCustomer->bounceRuleDescription) ?>)"</span>
                            <?php } ?>
                        </td>
                        <td><span class="error"><?= $oCustomer->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <?php if (is_numeric($oCustomer->bounceRuleId)) { ?>
                        <tr>
                            <td><?= sysTranslations::get('communication_reset_bounce') ?> *</td>
                            <td>
                                <input class="alignRadio required" title="<?= sysTranslations::get('communication_reset_bounce') ?>" type="radio" id="resetBounce_1" name="resetBounce" value="1"/> <label
                                        for="resetBounce_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input class="alignRadio required" title="<?= sysTranslations::get('communication_reset_bounce') ?>" type="radio" checked="checked" id="resetBounce_0" name="resetBounce" value="0"/> <label
                                        for="resetBounce_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><?= sysTranslations::get('customer_receive_verification') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_receive_verification_tooltip') ?>" type="radio" <?= $oCustomer->receiveCommunicationVerification ? 'CHECKED' : '' ?>
                                   id="receiveCommunicationVerification_1" name="receiveCommunicationVerification" value="1"/> <label for="receiveCommunicationVerification_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('customer_receive_verification_tooltip') ?>" type="radio" <?= !$oCustomer->receiveCommunicationVerification ? 'CHECKED' : '' ?>
                                   id="receiveCommunicationVerification_0" name="receiveCommunicationVerification" value="0"/> <label for="receiveCommunicationVerification_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr>
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

# create necessary javascript
$sBottomJavascript = <<<EOT
<script type="text/javascript">
    // randompassword to password field
    $("#password").randomPass();
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>