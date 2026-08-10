<div class="cf">
    <div class="contentColumn">
        <div class="errorBox" <?= !empty($aErrors) ? 'style="display:block;"' : '' ?>>
            <div class="title">De volgende fouten zijn gevonden</div>
            <ul>
                <?php

                if (!empty($aErrors)) {
                    foreach ($aErrors AS $sField => $sError) {
                        echo '<li><label for="' . $sField . '" style="display: block;">' . $sError . '</label></li>';
                    }
                }
                ?>
            </ul>
        </div>
        <?php if (!empty($aLogs)) { ?>
            <div class="import-messages">
                <h2>Resultaat <?= $iSaved . '/' . $iTotal ?> opgeslagen</h2>
                <ul style="margin-left: 15px;">
                    <li>Totaal rijen: <?= $iTotal ?></li>
                    <li>Totaal errors: <?= $iErrors ?></li>
                    <li>Totaal waarschuwingen: <?= $iWarnings ?></li>
                    <li>Totaal opgeslagen: <?= $iSaved ?></li>
                </ul>
                <?php

                # genereate errors summery
                $sErrorContent = '';
                foreach ($aLogs AS $iRow => $aErrors) {
                    if (isset($aErrors['errors'])) {
                        $sErrorContent .= '<div class="row ' . ($iRow == 2 ? 'first' : '') . '">' . "\n";
                        foreach ($aErrors AS $sType => $aMsgs) {
                            $sErrorContent .= '<div class="type-' . $sType . '">' . "\n";
                            foreach ($aMsgs AS $sMsg) {
                                $sErrorContent .= '<div class="msg">' . $sMsg . '</div>' . "\n";
                            }
                            $sErrorContent .= '</div>' . "\n";
                        }
                        $sErrorContent .= '</div>' . "\n";
                    }
                }

                # write error summery overview
                if (!empty($sErrorContent)) {
                    echo '<br>';
                    echo '<h2 class="errorColor">Errors</h2>';
                    echo $sErrorContent;
                    echo '<br>';
                }

                # total overview
                echo '<h2>Totaal overzicht import</h2>';
                foreach ($aLogs AS $iRow => $aErrors) {
                    echo '<div class="row ' . ($iRow == 2 ? 'first' : '') . '">';
                    foreach ($aErrors AS $sType => $aMsgs) {
                        echo '<div class="type-' . $sType . '">';
                        foreach ($aMsgs AS $sMsg) {
                            echo '<div class="msg">' . $sMsg . '</div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
        <form method="POST" enctype="multipart/form-data">
            <?= CSRFSynchronizerToken::field() ?>
            <input name="action" type="hidden" value="import"/>
            <fieldset>
                <legend>Klanten importeren in klantgroepen</legend>

                <table class="withForm">
                    <tr>
                        <td class="withLabel" style="width: 180px;"><label for="file">Bestand (.xls of .xlsx)</label></td>
                        <td>
                            <input name="file" type="file"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td>Kies te koppelen groep</td>
                        <td>
                            <?php

                            $aCustomerGroups = CustomerGroupManager::getAllCustomerGroups();
                            foreach ($aCustomerGroups as $oCustomerGroup) {
                                ?>
                                <input class="alignCheckbox" id="customerGroup-<?= $oCustomerGroup->customerGroupId ?>" name="customerGroupIds[]" type="checkbox" value="<?= $oCustomerGroup->customerGroupId ?>">
                                <label for="customerGroup-<?= $oCustomerGroup->customerGroupId ?>"><?= _e($oCustomerGroup->title) ?></label>
                                <br>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2">
                            <input type="submit" value="Importeer" name="save"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>

        <br>
        <h2>Ondersteunde kolommen</h2>|
        <?php

        foreach ($aImportFields AS $aField) {
            $bRequiredColumn = in_array($aField['column'], $aRequiredColumns);

            if ($bRequiredColumn) {
                echo '<b><u>' . $aField['column'] . '</u></b> | ';
            } else {
                echo $aField['column'] . ' | ';
            }
        }
        ?>
        <br>

    </div>
</div>