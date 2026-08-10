<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>" method="POST">
    <input type="hidden" name="filterForm" value="1"/>
    <fieldset style="margin-bottom: 20px;">
        <legend><?= sysTranslations::get('global_filter') ?> </legend>
        <table class="withForm">
            <tr>
                <td class="withLabel" style="width: 116px;"><label for="orderId"><?= sysTranslations::get('order_order_number') ?></label>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_order_number_tooltip') ?>">&nbsp;</div>
                </td>
                <td><input class="default" type="text" id="orderId" name="orderFilter[orderId]" value="<?= $aOrderFilter['orderId'] ?>"/></td>
            </tr>
            <tr>
                <td class="withLabel"><label for="statuses"><?= sysTranslations::get('global_status') ?></label>
                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('order_status_tooltip') ?>">&nbsp;</div>
                </td>
                <td>
                    <select class="default" multiple size="6" name="orderFilter[statuses][]" id="statuses">
                        <?php

                        foreach (OrderStatusManager::getAllStatuses() as $iStatus) {
                            echo '<option value="' . $iStatus . '" ' . (in_array($iStatus, $aOrderFilter['statuses']) ? 'selected' : '') . '>' . OrderStatusManager::getLabelByStatus($iStatus) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="filterOrders" value="<?= sysTranslations::get('order_filter_orders') ?>"/> <input type="submit" name="resetFilter" value="<?= sysTranslations::get('global_reset_filter') ?>"/></td>
            </tr>
        </table>
    </fieldset>
</form>
<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="10">
            <h2><?= sysTranslations::get('order_all_orders') ?></h2>
            <div class="right">
                <a class="exportExcelBtn textRight" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/export?withtax=1&'. CSRFSynchronizerToken::query() ?>" title="Exporteer bestellingen (incl.BTW)" alt="Exporteer bestellingen (incl.BTW)"><?= sysTranslations::get(
                        'order_export'
                    ) ?> <?= sysTranslations::get('global_inc_taxes') ?></a><br/>
                <a class="exportExcelBtn textRight" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/export?withtax=0&'. CSRFSynchronizerToken::query() ?>" title="Exporteer bestellingen (excl.BTW)" alt="Exporteer bestellingen (excl.BTW)"><?= sysTranslations::get(
                        'order_export'
                    ) ?> <?= sysTranslations::get('global_excl_taxes') ?></a><br/>
            </div>
        </td>
    </tr>
    <tr>
        <th>#</th>
        <th class="{sorter:'dateNL'}"><?= sysTranslations::get('order_created') ?></th>
        <th class="{sorter:'dateNL'}"><?= sysTranslations::get('order_updated') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_first_name') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_insertion') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_surname') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_status') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_payment_method') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_price') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 30px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aOrders AS $oOrder) {
        echo '<tr>';
        echo '<td>' . $oOrder->orderId . '</td>';
        echo '<td>' . strftime('%d-%m-%Y %H:%M:%S', strtotime($oOrder->created)) . '</td>';
        echo '<td>' . strftime('%d-%m-%Y %H:%M:%S', strtotime($oOrder->modified)) . '</td>';
        echo '<td>' . _e($oOrder->invoice_firstName) . '</td>';
        echo '<td>' . _e($oOrder->invoice_insertion) . '</td>';
        echo '<td>' . _e($oOrder->invoice_lastName) . '</td>';
        echo '<td>' . OrderStatusManager::getLabelByStatus($oOrder->status) . '</td>';
        echo '<td>' . $oOrder->getPaymentMethod()
                ->getTranslations('auto-admin')->name . '</td>';
        echo '<td>' . decimal2valuta($oOrder->getTotal(true)) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('order_edit_order') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oOrder->orderId . '"></a>';
        echo '</td>';
        echo '</tr>';
    }
    if (empty($aOrders)) {
        echo '<tr><td colspan="10"><i>' . sysTranslations::get('order_no_orders') . '</i></td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr class="bottomRow">
        <td colspan="10">
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