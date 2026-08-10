<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="5"><h2><?= sysTranslations::get('order_all_payment_method') ?></h2>
            <div class="right"><a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('order_payment_method_save') ?>"
                                  alt="<?= sysTranslations::get('order_payment_method_save') ?>"><?= sysTranslations::get('order_payment_method_save') ?></a><br/><a class="changeOrderBtn textRight"
                                                                                                                                                                     href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen"
                                                                                                                                                                     title="<?= sysTranslations::get('global_change_order') ?>"
                                                                                                                                                                     alt="<?= sysTranslations::get(
                                                                                                                                                                         'global_change_order'
                                                                                                                                                                     ) ?>"><?= sysTranslations::get('global_change_order') ?></a></div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_payment_method') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_price') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_payment_method_online') ?></th>
        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('order_page_after_payment') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aPaymentMethods AS $oPaymentMethod) {
        echo '<tr>';
        echo '<td>' . _e($oPaymentMethod->getTranslations('auto-admin')->name) . '</td>';
        echo '<td>' . decimal2valuta(_e($oPaymentMethod->price)) . '</td>';
        echo '<td>' . ($oPaymentMethod->isOnlinePaymentMethod ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
        echo '<td>' . _e($oPaymentMethod->getTranslations('auto-admin')->redirectPage) . '</td>';
        echo '<td>';
        if ($oPaymentMethod->isEditable()) {
            echo '<a class="action_icon edit_icon" title="' . sysTranslations::get('order_payment_method_edit') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPaymentMethod->paymentMethodId . '"></a>';
        } else {
            echo '<span class="action_icon edit_icon" title="' . sysTranslations::get('order_payment_method_no_edit') . '"></span>';
        }

        if ($oPaymentMethod->isDeletable()) {
            echo '<a class="action_icon delete_icon" onclick="return confirmChoice(\'deze betaalmethode\');" title="' . sysTranslations::get('order_payment_method_delete') . '" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oPaymentMethod->paymentMethodId . '?'. CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon" title="' . sysTranslations::get('order_payment_method_no_delete') . '"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aPaymentMethods)) {
        echo '<tr><td colspan="5"><i>' . sysTranslations::get('order_no_payment_method') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>