<h1><?= sysTranslations::get('review_settings') ?></h1>
<form method="POST" action="" class="validateForm" enctype="multipart/form-data">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" value="save" name="action"/>
    <table class="withForm">
        <?php foreach ($aSupportedFields AS $sSettingName => $aSettings) { ?>
            <tr>
                <td class="withLabel"><label for="<?= $sSettingName ?>"><?= sysTranslations::get('review_' . $sSettingName) ?></label> *</td>
                <td>
                    <input class="<?= isset($aSettings['validation']) ? $aSettings['validation'] : '' ?> default" title="<?= sysTranslations::get('review_' . $sSettingName . '_title') ?>" type="text" id="<?= $sSettingName ?>"
                           name="settings[<?= $sSettingName ?>]" value="<?= _e(Settings::get($sSettingName)) ?>"/>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td style="width: 220px;">&nbsp;</td>
            <td colspan="2">
                <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
            </td>
        </tr>
    </table>
</form>
