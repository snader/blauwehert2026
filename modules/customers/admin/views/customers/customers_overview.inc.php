<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?></legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><label for="name"><?= sysTranslations::get('global_name') ?></label>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('customer_filter_name_tooltip') ?>">&nbsp;</div>
                </td>
                <td><input class="default" id="name" type="text" name="customerFilter[name]" value="<?= $aCustomerFilter['name'] ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterCustomers" value="<?= sysTranslations::get('customer_filter_customer') ?>"/> <input type="submit" name="resetFilter" value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>
<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="11"><h2><?= sysTranslations::get('customer_found') ?> (<?= (count($aCustomers) != 0 ? ($iStart + 1) : $iStart) . '-' . ($iStart + count($aCustomers)) ?>/<?= $iFoundRows ?>)</h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('customer_add') ?>"
                                  alt="<?= sysTranslations::get('customer_add') ?>"><?= sysTranslations::get('customer_add') ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline"></th>
        <th><?= sysTranslations::get('global_first_name') ?></th>
        <th><?= sysTranslations::get('global_insertion') ?></th>
        <th><?= sysTranslations::get('global_surname') ?></th>
        <th><?= sysTranslations::get('global_email') ?></th>
        <th><?= sysTranslations::get('global_mobile_number') ?></th>
        <th><?= sysTranslations::get('global_may_receive_email') ?></th>
        <th><?= sysTranslations::get('global_may_receive_textmessage') ?></th>
        <th><?= sysTranslations::get('customer_locked') ?></th>
        <th><?= sysTranslations::get('customer_lockedReason') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aCustomers AS $oCustomer) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        echo '<a id="customer_' . $oCustomer->customerId . '_online_1" title="Klant offline zetten" class="action_icon ' . ($oCustomer->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oCustomer->customerId . '/?online=0&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="customer_' . $oCustomer->customerId . '_online_0" title="Klant online zetten" class="action_icon ' . ($oCustomer->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oCustomer->customerId . '/?online=1&'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . _e($oCustomer->firstName) . '</td>';
        echo '<td>' . _e($oCustomer->insertion) . '</td>';
        echo '<td>' . _e($oCustomer->lastName) . '</td>';
        echo '<td>' . _e($oCustomer->email) . '</td>';
        echo '<td>' . _e($oCustomer->mobilePhone) . '</td>';
        echo '<td>' . ($oCustomer->contactByEmail ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
        echo '<td>' . ($oCustomer->contactBySms ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
        echo '<td>' . ($oCustomer->locked ? Date::strToDate($oCustomer->locked)
                ->format('%d-%m-%Y %H:%M:%S') : '') . '</td>';
        if ($oCustomer->locked) {
            echo '<td>' . ($oCustomer->lockedReason ? sysTranslations::get($oCustomer->lockedReason) . ' <a class="action_icon unlock_icon" href="#" title="' . _e(
                        sysTranslations::get('customer_unlock')
                    ) . '" onclick="unlock(' . $oCustomer->customerId . ', \'' . _e($oCustomer->getFullName()) . '\'); return false;"></a>' : '') . '</td>';
        } else {
            echo '<td>' . ($oCustomer->lockedReason ? $oCustomer->lockedReason : '') . '</td>';
        }
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('customer_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId . '"></a>';
        echo '<a class="action_icon delete_icon" title="' . sysTranslations::get('customer_delete') . '" onclick="return confirmChoice(\'' . _e($oCustomer->getFullName()) . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/verwijderen/' . $oCustomer->customerId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aCustomers)) {
        echo '<tr><td colspan="11"><i>' . sysTranslations::get('customer_no_customers') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="11">
            <form method="POST">
                <?= generatePaginationHTML($iPageCount, $iCurrPage) ?>
                <input type="hidden" name="setPerPage" value="1"/>
                <select name="perPage" onchange="$(this).closest('form').submit();">
                    <option value="<?= $iNrOfRecords ?>"><?= sysTranslations::get('global_all') ?></option>
                    <option <?= $iPerPage == 10 ? 'SELECTED' : '' ?> value="10">10</option>
                    <option <?= $iPerPage == 25 ? 'SELECTED' : '' ?> value="25">25</option>
                    <option <?= $iPerPage == 50 ? 'SELECTED' : '' ?> value="50">50</option>
                    <option <?= $iPerPage == 100 ? 'SELECTED' : '' ?> value="100">100</option>
                </select> <?= sysTranslations::get('global_per_page') ?>
            </form>
        </td>
    </tr>
    </tfoot>
</table>

<div style="display: none;">
    <div id="unlockCustomerForm" style="width: 500px;">
        <form method="POST">
            <?= CSRFSynchronizerToken::field() ?>
            <input class="locked_customerId" name="customerId" type="hidden" value=""/>
            <input name="action" type="hidden" value="unlockCustomer"/>
            <table class="withForm">
                <tr>
                    <td colspan="2">
                        <h2 style="margin-bottom: 5px;"><?= sysTranslations::get('customer_unlock') ?> `<span class="locked_name"></span>`</h2>
                    </td>
                </tr>
                <tr>
                    <td class="withLabel" style="width: 120px;"><label for="unlock_reason"><?= sysTranslations::get('customer_unlock_reason') ?></label></td>
                    <td>
                        <input id="unlock_reason" name="unlockReason" style="width: 300px;" type="text" value=""/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
    function unlock(customerId, name) {
        $('.locked_customerId').val(customerId);
        $('.locked_name').html(name);
        $.fancybox.open({href: '#unlockCustomerForm'});
        return false;
    }
</script>

<?php

# create necessary javascript
$sController                = http_get('controller');
$sCustomersOnline           = sysTranslations::get('customer_online');
$sCustomersOffline          = sysTranslations::get('customer_offline');
$sCustomersStatusNotChanged = sysTranslations::get('customer_status_not_changed');

$sBottomJavascript = <<<EOT
<script type="text/javascript">
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: $(this).prop('href'),
            data: 'ajax=1',
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if (dataObj.success == true){
                    $("#customer_"+dataObj.customerId+"_online_0").hide(); // hide offline button
                    $("#customer_"+dataObj.customerId+"_online_1").hide(); // hide online button
                    $("#customer_"+dataObj.customerId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sCustomersOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sCustomersOnline");                            
                } else {
                    showStatusUpdate("$sCustomersStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>