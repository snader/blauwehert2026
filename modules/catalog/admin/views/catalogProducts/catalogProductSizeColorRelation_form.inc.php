<div style="min-width: 450px;" class="cf">
    <h2><?= sysTranslations::get('catalog_stock_updating') ?></h2>
    <form method="POST" action="<?= getCurrentUrl() ?>" class="validateForm">
        <?= CSRFSynchronizerToken::field() ?>
        <input type="hidden" name="action" value="saveSizeColorRelation"/>
        <table class="withForm">
            <tr>
                <td class="withLabel"><?= sysTranslations::get('catalog_stock') ?></td>
                <td><input class="numbersOnly" type="text" name="stock" size="3" value="<?= $oCatalogProductSizeColorRelation->stock ?>"/></td>
            </tr>
            <?php if ($oCatalogProductSizeColorRelation->getProduct()
                    ->getProductType()->withSizes || $oCatalogProductSizeColorRelation->getProduct()
                    ->getProductType()->withColors) { ?>
                <tr>
                    <td class="withLabel"><?= sysTranslations::get('catalog_extra_price') ?></td>
                    <td><input class="priceFloatOnly" type="text" name="extraPrice" size="3" value="<?= $oCatalogProductSizeColorRelation->extraPrice ?>"/></td>
                </tr>

                <tr>
                    <td class="withLabel"><?= sysTranslations::get('catalog_mpn') ?> </td>
                    <td><input type="text" name="catalogProductSizeColorMPN" size="50" value="<?= $oCatalogProductSizeColorRelation->catalogProductSizeColorMPN ?>"/></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3">
                    <input type="submit" value="<?= sysTranslations::get('catalog_stock_updating') ?>" name="save"/>
                </td>
            </tr>
        </table>
    </form>
</div>