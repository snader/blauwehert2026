<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="6"><h2><?= sysTranslations::get('order_delivery_method_all') ?></h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('order_delivery_method_add') ?>"
                                  alt="<?= sysTranslations::get('order_delivery_method_add') ?>"><?= sysTranslations::get('order_delivery_method_add') ?></a><br/><a class="changeOrderBtn textRight"
                                                                                                                                                                     href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen"
                                                                                                                                                                     title="<?= sysTranslations::get('global_change_order') ?>"
                                                                                                                                                                     alt="<?= sysTranslations::get(
                                                                                                                                                                         'global_change_order'
                                                                                                                                                                     ) ?>"><?= sysTranslations::get('global_change_order') ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_delivery_method') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_price') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_delivery_method_gratis') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_delivery_method_time') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_delivery_method_payments') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aDeliveryMethods AS $oDeliveryMethod) {
        echo '<tr>';
        echo '<td>' . _e($oDeliveryMethod->getTranslations('auto-admin')->name) . '</td>';
        echo '<td>' . decimal2valuta(_e($oDeliveryMethod->price)) . '</td>';
        echo '<td>' . ($oDeliveryMethod->freeFromPrice ? decimal2valuta(_e($oDeliveryMethod->freeFromPrice)) . ' ' . (Settings::get('taxIncluded') ? '(' . sysTranslations::get('global_inc_taxes') . ')' : '(' . sysTranslations::get(
                        'global_excl_taxes'
                    ) . ')') : '') . '</td>';
        echo '<td>' . ($oDeliveryMethod->deliveryTime ? $oDeliveryMethod->deliveryTime : '') . '</td>';
        echo '<td>| ';
        foreach ($oDeliveryMethod->getPaymentMethods('all') AS $oPaymentMethod) {
            echo $oPaymentMethod->getTranslations('auto-admin')->name . ' | ';
        }
        echo '</td>';
        echo '<td>';
        if ($oDeliveryMethod->isEditable()) {
            echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('order_delivery_method_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oDeliveryMethod->deliveryMethodId . '"></a>';
        } else {
            echo '<span class="action_icon edit_icon" title="' . sysTranslations::get('order_delivery_method_no_edit') . '"></span>';
        }

        if ($oDeliveryMethod->isDeletable()) {
            echo '<a class="action_icon delete_icon" onclick="return confirmChoice(\'deze verzendmethode\');" title="' . sysTranslations::get('order_delivery_method_delete') . '" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oDeliveryMethod->deliveryMethodId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon" title="' . sysTranslations::get('order_delivery_method_no_delete') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aDeliveryMethods)) {
        echo '<tr><td colspan="6"><i>' . sysTranslations::get('order_no_delivery_method') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>