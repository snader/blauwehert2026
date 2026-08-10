<div style="min-width: 450px;" class="cf">
    <h2><?= sysTranslations::get('catalog_choose_image_for_color') ?> '<?= strtolower(http_get('colorName')) ?>'</h2>
    <form>
        <table class="withForm">
            <tr>
                <?php foreach ($aImageFile AS $oImageFile) { ?>
                    <td>
                        <div class="catalogProductImagePlaceHolder">
                            <div class='images'>
                                <div class='placeholder'>
                                    <div class='imagePlaceholder'>
                                        <div class='centered'>
                                            <img name="<?= $oImageFile->imageId ?>" class="catalogProductImageColorImage" src="<?= CatalogProduct::IMAGES_PATH . '/cms_thumb/' . $oImageFile->name ?>" title="<?= $oImageFile->title ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                <?php } ?>
                <?php if (count($aImageFile) == 0) { ?>
                    <div><i><?= sysTranslations::get('catalog_no_images') ?></i></div>
                <?php } ?>
            </tr>
        </table>
    </form>
</div>

<script>
    // I add the selected image to the external box
    $(".catalogProductImageColorImage").click(function () {
        $("#catalogProductImageColorImage").removeClass("hide");
        $("#catalogProductImageColorImage img").prop('src', $(this).prop('src'));
        $("#catalogProductImageColorImageId").val($(this).prop('name'))

        // I add the "selected" class to the selected image and I remove this class from the others
        $(".catalogProductImagePlaceHolder .placeholder").removeClass("selected");
        $(this).parents('.placeholder').addClass("selected");

    });
</script>  
