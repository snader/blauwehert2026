<?php /* @var Usp $oUsp */ ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0"><i class="fas fa-star mr-2"></i><?= sysTranslations::get('usp') ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-8">
                <form method="POST" action="" class="validateForm" id="uspForm">
                    <?= CSRFSynchronizerToken::field() ?>
                    <input type="hidden" value="save" name="action"/>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i><?= sysTranslations::get('usp') ?></h3>
                            <div class="card-tools">
                                <a class="btn btn-default btn-sm" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('usp_back_overview') ?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?= sysTranslations::get('global_online') ?> *</label>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input required" title="<?= sysTranslations::get('usp_online_offline_tooltip') ?>" type="radio" <?= $oUsp->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/>
                                        <label class="custom-control-label" for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input required" title="<?= sysTranslations::get('usp_online_offline_tooltip') ?>" type="radio" <?= !$oUsp->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/>
                                        <label class="custom-control-label" for="online_0"><?= sysTranslations::get('global_no') ?></label>
                                    </div>
                                </div>
                                <span class="error invalid-feedback d-block"><?= $oUsp->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            </div>

                            <div class="form-group">
                                <label for="name"><?= sysTranslations::get('global_title') ?> *</label>
                                <input class="form-control required default" id="name" type="text" autocomplete="off" name="name" value="<?= $oUsp->name ?>" title="<?= sysTranslations::get('usp_no_name') ?>"/>
                                <span class="error invalid-feedback d-block"><?= $oUsp->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            </div>

                            <?php if (moduleExists('pages')) { ?>
                                <div class="form-group">
                                    <label for="pageId"><?= sysTranslations::get('usp_page') ?></label>
                                    <select name="pageId" id="pageId" class="form-control linkChoice default">
                                        <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                        <?php
                                        function generateOptions($aPages, &$oUsp)
                                        {
                                            foreach ($aPages as $oPage) {
                                                $sLeadingChars = '';
                                                for ($iC = $oPage->level; $iC > 1; $iC--) {
                                                    $sLeadingChars .= '--';
                                                }
                                                echo '<option value="' . $oPage->pageId . '" ' . ($oPage->pageId == $oUsp->pageId ? 'selected' : '') . '>' . $sLeadingChars . $oPage->getShortTitle() . '</option>';
                                                generateOptions($oPage->getSubPages('online-all'), $oUsp);
                                            }
                                        }

                                        generateOptions(PageManager::getPagesByFilter(['showAll' => 1, 'online' => 1, 'level' => 1, 'languageId' => AdminLocales::language()]), $oUsp);
                                        ?>
                                    </select>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label for="link"><?= sysTranslations::get('global_link') ?></label>
                                <input class="form-control linkChoice default" title="<?= sysTranslations::get('usp_type_link') ?>" name="link" type="text" id="link" value="<?= $oUsp->link ?>"/>
                                <span class="error invalid-feedback d-block"><?= $oUsp->isPropValid('link') ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            </div>

                            <div class="form-group">
                                <label for="textLine"><?= sysTranslations::get('usp_textline') ?></label>
                                <input class="form-control textLineChoice default" title="<?= sysTranslations::get('usp_type_textline') ?>" name="textLine" type="text" id="textLine" value="<?= $oUsp->textLine ?>"/>
                                <span class="error invalid-feedback d-block"><?= $oUsp->isPropValid('textLine') ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <a class="btn btn-default" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('usp_back_overview') ?></a>
                                <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title"><?= sysTranslations::get('global_files') ?></h3>
                    </div>
                    <div class="card-body">
                        <p><i><?= sysTranslations::get('add_svg_file') ?></i></p>
                        <?php
                        if ($oUsp->uspId !== null) {
                            $oFileManagerHTML->includeTemplate();
                        } else {
                            echo '<p><i>' . sysTranslations::get('usp_images_warning') . '</i></p>';
                        }
                        ?>
                    </div>
                </div>

                <?php if ($oUsp->fileId == null || $oUsp->imageId !== null) { ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><?= sysTranslations::get('global_images') ?></h3>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($oUsp->uspId !== null) {
                                $oImageManagerHTML->includeTemplate();
                            } else {
                                echo '<p><i>' . sysTranslations::get('usp_images_warning') . '</i></p>';
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?php

# define and add javascript to bottom
$sBottomJavascript = <<<EOT
<script type="text/javascript">
        $('.linkChoice').change(function(){
            var filled = null;
            $('.linkChoice').each(function(index, element){
                if($(element).val() != ''){
                    filled = element;
                }
            });
            if(filled === null){
                $('.linkChoice').prop('disabled', false);
            }else{
                $('.linkChoice').not(filled).prop('disabled', true);
            }
        });
        
        $('#link').keyup(function(){
            $(this).change();
        });
        
        $('#link').change();
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
