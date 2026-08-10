<table class="sorted">
    <thead>
    <tr class="topRow">
        <td colspan="3"><h2><?= ucfirst(sysTranslations::get('catalog_all_brands')) ?></h2>
            <div class="right">
                <a class="addBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('catalog_add_brand') ?>"
                   alt="<?= sysTranslations::get('catalog_add_brand') ?>"><?= sysTranslations::get('catalog_add_brand') ?></a>
                <br/>
                <a class="changeOrderBtn textRight" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/volgorde-wijzigen" title="<?= sysTranslations::get('global_change_order') ?>"
                   alt="<?= sysTranslations::get('global_change_order') ?>"><?= sysTranslations::get('global_change_order') ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <th class="{sorter:false} nonSorted onlineOffline">&nbsp;</th>
        <th><?= sysTranslations::get('catalog_brand_name') ?></th>
        <th class="{sorter:false} nonSorted" style="width: 60px;">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($aAllBrands AS $oBrand) {
        echo '<tr>';
        echo '<td>';
        # online offline button
        echo '<a id="brand_' . $oBrand->catalogBrandId . '_online_1" title="Merk offline zetten" class="action_icon ' . ($oBrand->online ? '' : 'hide') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oBrand->catalogBrandId . '/?online=0&' . CSRFSynchronizerToken::query() .'"></a>';
        echo '<a id="brand_' . $oBrand->catalogBrandId . '_online_0" title="Merk online zetten" class="action_icon ' . ($oBrand->online ? 'hide' : '') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get(
                'controller'
            ) . '/ajax-setOnline/' . $oBrand->catalogBrandId . '/?online=1&' . CSRFSynchronizerToken::query() .'"></a>';
        echo '</td>';
        echo '<td>' . _e($oBrand->getTranslations('auto-admin')->name) . '</td>';
        echo '<td>';
        echo '<a class="action_icon edit_icon" title="Bewerk merk" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrand->catalogBrandId . '"></a>';

        if ($oBrand->isDeletable()) {
            echo '<a class="action_icon delete_icon" title="Verwijder merk" onclick="return confirmChoice(\'' . _e($oBrand->getTranslations('auto-admin')->name) . '\');" href="' . ADMIN_FOLDER . '/' . http_get(
                    'controller'
                ) . '/verwijderen/' . $oBrand->catalogBrandId . '?' . CSRFSynchronizerToken::query() .'"></a>';
        } else {
            echo '<span class="action_icon delete_icon grey" title="Aan dit merk hangen nog producten"></span>';
        }

        echo '</td>';
        echo '</tr>';
    }
    if (empty($aAllBrands)) {
        echo '<tr><td colspan="3"><i>Er zijn geen merken om weer te geven</i></td></tr>';
    }
    ?>
    </tbody>
</table>
<?php

# create necessary javascript
$sController      = http_get('controller');
$sBrandOffline    = sysTranslations::get('catalog_brand_status_offline');
$sBrandOnline     = sysTranslations::get('catalog_brand_status_online');
$sBrandNotChanged = sysTranslations::get('catalog_brand_status_not_changed');

$sBottomJavascript = <<<EOT
<script>
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
                    $("#brand_"+dataObj.catalogBrandId+"_online_0").hide(); // hide offline button
                    $("#brand_"+dataObj.catalogBrandId+"_online_1").hide(); // hide online button
                    $("#brand_"+dataObj.catalogBrandId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)    
                        showStatusUpdate("$sBrandOffline");
                    if(dataObj.online == 1)    
                        showStatusUpdate("$sBrandOnline");                            
                } else {
                    showStatusUpdate("$sBrandNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>