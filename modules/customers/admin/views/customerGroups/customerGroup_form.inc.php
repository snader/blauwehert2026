<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('customergroup_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" action="" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <fieldset>
                <legend><?= sysTranslations::get('customer_group') ?></legend>
                <table class="withForm">
                    <tr>
                        <td class="withLabel"><label for="title"><?= sysTranslations::get('global_name') ?> *</label></td>
                        <td><input size="50" id="title" class="required" title="<?= sysTranslations::get('customer_group_title_tooltip') ?>" type="text" name="title" value="<?= _e($oCustomerGroup->title) ?>"/></td>
                        <td><span class="error"><?= $oCustomerGroup->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <?php if ($oCurrentUser->isAdmin()) { ?>
                        <tr>
                            <td class="withLabel"><label for="name"><?= sysTranslations::get('customer_group_unique_name') ?></label></td>
                            <td><input id="name" class="default" data-rule-remote="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/ajax-checkName?customerGroupId=<?= $oCustomerGroup->customerGroupId ?>"
                                       title="<?= sysTranslations::get('customer_group_unique_name_tooltip') ?>" type="text" name="name" value="<?= $oCustomerGroup->name ?>"/></td>
                            <td><span class="error"><?= $oCustomerGroup->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
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
<?php if (is_numeric($oCustomerGroup->customerGroupId)) { ?>
    <table class="sorted" style="margin-top: 20px">
        <thead>
        <tr class="topRow">
            <td colspan="6"><h2><?= sysTranslations::get('customer_group_online_customers') ?></h2></td>
        </tr>
        <tr>
            <th><?= sysTranslations::get('global_name') ?></th>
            <th><?= sysTranslations::get('global_email') ?></th>
            <th><?= sysTranslations::get('global_mobile_number') ?></th>
            <th><?= sysTranslations::get('customer_customer_email') ?></th>
            <th><?= sysTranslations::get('customer_customer_sms') ?></th>
            <th class="{sorter:false} nonSorted" style="width: 30px;">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($aClients as $oClient) { ?>
            <tr>
                <td><?= $oClient->getFullName() ?></td>
                <td><?= $oClient->email ?></td>
                <td><?= $oClient->mobilePhone ?></td>
                <td><?= $oClient->contactByEmail == 1 ? sysTranslations::get('global_yes') : sysTranslations::get('global_no') ?></td>
                <td><?= $oClient->contactBySms == 1 ? sysTranslations::get('global_yes') : sysTranslations::get('global_no') ?></td>
                <td><a class="action_icon edit_icon" title="<?= sysTranslations::get('customer_edit') ?>" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oClient->customerId ?>"></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('customergroup_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>