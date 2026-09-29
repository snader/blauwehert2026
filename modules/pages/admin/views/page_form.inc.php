<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0"><i class="fas fa-file-alt mr-2"></i><?= sysTranslations::get('pages_info') ?> <small>(<?= sysTranslations::get('for_language') . ' ' . $oLocale->getLanguage()->getTranslations()->name ?>)</small></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-8">
                <form method="POST" action="" class="validateForm" id="pageForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" value="save" name="action"/>
            <input type="hidden" value="<?= $oPage->parentPageId ?>" name="parentPageId"/>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-signature mr-2"></i><?= sysTranslations::get('pages_info') ?></h3>
                            <div class="card-tools">
                                <a class="btn btn-default btn-sm" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($oPage->isOnlineChangeable()) { ?>
                                <div class="form-group">
                                    <label><?= sysTranslations::get('global_online') ?> *</label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input required" title="<?= sysTranslations::get('pages_online_tooltip') ?>" type="radio" <?= $oPage->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/>
                                            <label class="custom-control-label" for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input required" title="<?= sysTranslations::get('pages_offline_tooltip') ?>" type="radio" <?= !$oPage->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/>
                                            <label class="custom-control-label" for="online_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                    <span class="error invalid-feedback d-block"><?= $oPage->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label for="title"><?= sysTranslations::get('global_title') ?> *</label>
                                <input id="title" class="form-control required autofocus default" title="<?= sysTranslations::get('pages_title_tooltip') ?>" minlength="3" type="text" autocomplete="off" name="title" value="<?= _e($oPage->title) ?>"/>
                                <span class="error invalid-feedback d-block"><?= $oPage->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            </div>

                            <div class="form-group">
                                <label for="shortTitle"><?= sysTranslations::get('global_menu_link') ?></label>
                                <input class="form-control default" id="shortTitle" type="text" name="shortTitle" value="<?= _e($oPage->shortTitle) ?>" minlength="3" title="<?= sysTranslations::get('pages_link_min_3_tooltip') ?>"/>
                            </div>

                            <div class="form-group">
                                <label for="intro"><?= sysTranslations::get('global_intro') ?></label>
                                <textarea name="intro" id="intro" class="form-control tiny_MCE_page tiny_MCE"><?= $oPage->intro ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="content"><?= sysTranslations::get('global_content') ?></label>
                                <textarea name="content" id="content" class="form-control tiny_MCE_page tiny_MCE"><?= $oPage->content ?></textarea>
                            </div>

                            <?php if (moduleExists('forms') && !$oPage->hideFormManagement()) { ?>
                                <div class="form-group">
                                    <label for="formId"><?= sysTranslations::get('pages_link_form') ?></label>
                                    <select class="form-control linkChoice default" name="formId" id="formId">
                                        <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                        <?php
                                        foreach (FormManager::getFormsByFilter() as $oForm) {
                                            echo '<option value="' . $oForm->formId . '" ' . ($oForm->formId == $oPage->formId ? 'selected' : '') . '>' . _e($oForm->name) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if ($oCurrentUser->isSEO()) { ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= sysTranslations::get('global_seo') ?></h3>
                            </div>
                            <div class="card-body">
                                <?php if ($oPage->pageId) {
                                    $aLocales = $oPage->getLocales();
                                ?>
                                    <div class="form-group">
                                        <label><?= sysTranslations::get('global_current_url') ?><?= (count($aLocales) > 1 ? 's' : '') ?></label>
                                        <div class="form-control-plaintext">
                                            <?php
                                            foreach ($aLocales as $oLocale) {
                                                echo getBaseUrl($oLocale) . $oPage->getUrlPath() . '<br />';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="form-group">
                                    <label for="customCanonical"><?= sysTranslations::get('global_custom_canonical') ?></label>
                                    <input class="form-control default" id="customCanonical" type="text" name="customCanonical" value="<?= _e($oPage->customCanonical) ?>"/>
                                </div>

                                <div class="form-group">
                                    <label for="windowTitle"><?= sysTranslations::get('global_window_title') ?></label>
                                    <input class="form-control default charCounterWindowTitle" id="windowTitle" type="text" maxlength="255" name="windowTitle" value="<?= _e($oPage->windowTitle) ?>"/>
                                    <div id="windowTitleCounter" class="text-right small"></div>
                                </div>

                                <div class="form-group">
                                    <label for="metaDescription"><?= sysTranslations::get('global_description') ?></label>
                                    <textarea class="form-control charCounterMetaDescription default" id="metaDescription" maxlength="255" name="metaDescription"><?= _e($oPage->metaDescription) ?></textarea>
                                    <div id="metaDescriptionCounter" class="text-right small"></div>
                                </div>

                                <div class="form-group">
                                    <label for="metaKeywords"><?= sysTranslations::get('global_keywords') ?></label>
                                    <input class="form-control default charCounterMetaKeywords" id="metaKeywords" type="text" maxlength="255" name="metaKeywords" value="<?= _e($oPage->metaKeywords) ?>"/>
                                    <div id="metaKeywordsCounter" class="text-right small"></div>
                                </div>

                                <?php if (!$oPage->getLockUrlPath()) { ?>
                                    <div class="form-group">
                                        <label for="urlPart"><?= sysTranslations::get('global_seo_url') ?></label>
                                        <input class="form-control default" id="urlPart" type="text" name="urlPart" value="<?= _e($oPage->getUrlPart()) ?>"/>
                                    </div>
                                <?php } ?>

                                <div class="form-group">
                                    <label for="urlParameters"><?= sysTranslations::get('pages_url_parameters') ?></label>
                                    <input class="form-control default" id="urlParameters" type="text" name="urlParameters" value="<?= _e($oPage->getUrlParameters()) ?>"/>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_in_menu') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_in_menu_tooltip') ?>" type="radio" <?= $oPage->getInMenu() ? 'CHECKED' : '' ?> id="inMenu_1" name="inMenu" value="1"/>
                                            <label class="custom-control-label" for="inMenu_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_in_menu_tooltip') ?>" type="radio" <?= !$oPage->getInMenu() ? 'CHECKED' : '' ?> id="inMenu_0" name="inMenu" value="0"/>
                                            <label class="custom-control-label" for="inMenu_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_in_footer') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_in_footer_tooltip') ?>" type="radio" <?= $oPage->getInFooter() ? 'CHECKED' : '' ?> id="inFooter_1" name="inFooter" value="1"/>
                                            <label class="custom-control-label" for="inFooter_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_in_footer_tooltip') ?>" type="radio" <?= !$oPage->getInFooter() ? 'CHECKED' : '' ?> id="inFooter_0" name="inFooter" value="0"/>
                                            <label class="custom-control-label" for="inFooter_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_indexable') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_indexable_tooltip') ?>" type="radio" <?= $oPage->getIndexable() ? 'CHECKED' : '' ?> id="indexable_1" name="indexable" value="1"/>
                                            <label class="custom-control-label" for="indexable_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_indexable_tooltip') ?>" type="radio" <?= !$oPage->getIndexable() ? 'CHECKED' : '' ?> id="indexable_0" name="indexable" value="0"/>
                                            <label class="custom-control-label" for="indexable_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_include_parent_in_url_path') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_include_parent_in_url_path_tooltip') ?>" type="radio" <?= $oPage->getIncludeParentInUrlPath() ? 'CHECKED' : '' ?> id="includeParentInUrlPath_1" name="includeParentInUrlPath" value="1"/>
                                            <label class="custom-control-label" for="includeParentInUrlPath_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_include_parent_in_url_path_tooltip') ?>" type="radio" <?= !$oPage->getIncludeParentInUrlPath() ? 'CHECKED' : '' ?> id="includeParentInUrlPath_0" name="includeParentInUrlPath" value="0"/>
                                            <label class="custom-control-label" for="includeParentInUrlPath_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($oCurrentUser->isAdmin()) { ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= sysTranslations::get('global_admin_settings') ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name"><?= sysTranslations::get('pages_unique_name') ?></label>
                                    <input id="name" class="form-control default" data-rule-remote="<?= ADMIN_FOLDER ?>/paginas/ajax-checkName?pageId=<?= $oPage->pageId ?>" title="<?= sysTranslations::get('pages_unique_name_tooltip') ?>" type="text" name="name" value="<?= $oPage->name ?>"/>
                                    <span class="error invalid-feedback d-block"><?= $oPage->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="controllerPath"><?= sysTranslations::get('pages_controller_path') ?> *</label>
                                    <input class="form-control required default" id="controllerPath" name="controllerPath" type="text" value="<?= _e($oPage->getControllerPath()) ?>"/>
                                    <span class="error invalid-feedback d-block"><?= $oPage->isPropValid("controllerPath") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_online_changeable') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_online_changeable_tooltip') ?>" type="radio" <?= $oPage->getOnlineChangeable() ? 'CHECKED' : '' ?> id="onlineChangeable_1" name="onlineChangeable" value="1"/>
                                            <label class="custom-control-label" for="onlineChangeable_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_online_changeable_tooltip') ?>" type="radio" <?= !$oPage->getOnlineChangeable() ? 'CHECKED' : '' ?> id="onlineChangeable_0" name="onlineChangeable" value="0"/>
                                            <label class="custom-control-label" for="onlineChangeable_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('global_editable') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_editable_tooltip') ?>" type="radio" <?= $oPage->getEditable() ? 'CHECKED' : '' ?> id="editable_1" name="editable" value="1"/>
                                            <label class="custom-control-label" for="editable_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_editable_tooltip') ?>" type="radio" <?= !$oPage->getEditable() ? 'CHECKED' : '' ?> id="editable_0" name="editable" value="0"/>
                                            <label class="custom-control-label" for="editable_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('global_deletable') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_deletable_tooltip') ?>" type="radio" <?= $oPage->getDeletable() ? 'CHECKED' : '' ?> id="deletable_1" name="deletable" value="1"/>
                                            <label class="custom-control-label" for="deletable_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_deletable_tooltip') ?>" type="radio" <?= !$oPage->getDeletable() ? 'CHECKED' : '' ?> id="deletable_0" name="deletable" value="0"/>
                                            <label class="custom-control-label" for="deletable_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_has_subpages') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('page_has_subpages_tooltip') ?>" type="radio" <?= $oPage->getMayHaveSub() ? 'CHECKED' : '' ?> id="mayHaveSub_1" name="mayHaveSub" value="1"/>
                                            <label class="custom-control-label" for="mayHaveSub_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('page_has_sub_tooltip') ?>" type="radio" <?= !$oPage->getMayHaveSub() ? 'CHECKED' : '' ?> id="mayHaveSub_0" name="mayHaveSub" value="0"/>
                                            <label class="custom-control-label" for="mayHaveSub_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_lock_path') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_lock_path_tooltip') ?>" type="radio" <?= $oPage->getLockUrlPath() ? 'CHECKED' : '' ?> id="lockUrlPath_1" name="lockUrlPath" value="1"/>
                                            <label class="custom-control-label" for="lockUrlPath_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_lock_path_tooltip') ?>" type="radio" <?= !$oPage->getLockUrlPath() ? 'CHECKED' : '' ?> id="lockUrlPath_0" name="lockUrlPath" value="0"/>
                                            <label class="custom-control-label" for="lockUrlPath_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_lock_parent') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_lock_parent_tooltip') ?>" type="radio" <?= $oPage->getLockParent() ? 'CHECKED' : '' ?> id="lockParent_1" name="lockParent" value="1"/>
                                            <label class="custom-control-label" for="lockParent_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_lock_parent_tooltip') ?>" type="radio" <?= !$oPage->getLockParent() ? 'CHECKED' : '' ?> id="lockParent_0" name="lockParent" value="0"/>
                                            <label class="custom-control-label" for="lockParent_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_no_images') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_no_images_tooltip') ?>" type="radio" <?= $oPage->getHideImageManagement() ? 'CHECKED' : '' ?> id="hideImageManagement_1" name="hideImageManagement" value="1"/>
                                            <label class="custom-control-label" for="hideImageManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_no_images_tooltip') ?>" type="radio" <?= !$oPage->getHideImageManagement() ? 'CHECKED' : '' ?> id="hideImageManagement_0" name="hideImageManagement" value="0"/>
                                            <label class="custom-control-label" for="hideImageManagement_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_no_files') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_no_files_tooltip') ?>" type="radio" <?= $oPage->getHideFileManagement() ? 'CHECKED' : '' ?> id="hideFileManagement_1" name="hideFileManagement" value="1"/>
                                            <label class="custom-control-label" for="hideFileManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_no_files_tooltip') ?>" type="radio" <?= !$oPage->getHideFileManagement() ? 'CHECKED' : '' ?> id="hideFileManagement_0" name="hideFileManagement" value="0"/>
                                            <label class="custom-control-label" for="hideFileManagement_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_no_links') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_no_links_tooltip') ?>" type="radio" <?= $oPage->getHideLinkManagement() ? 'CHECKED' : '' ?> id="hideLinkManagement_1" name="hideLinkManagement" value="1"/>
                                            <label class="custom-control-label" for="hideLinkManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_no_links_tooltip') ?>" type="radio" <?= !$oPage->getHideLinkManagement() ? 'CHECKED' : '' ?> id="hideLinkManagement_0" name="hideLinkManagement" value="0"/>
                                            <label class="custom-control-label" for="hideLinkManagement_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= sysTranslations::get('pages_no_video') ?></label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('pages_no_video_tooltip') ?>" type="radio" <?= $oPage->getHideVideoLinkManagement() ? 'CHECKED' : '' ?> id="hideVideoLinkManagement_1" name="hideVideoLinkManagement" value="1"/>
                                            <label class="custom-control-label" for="hideVideoLinkManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" title="<?= sysTranslations::get('page_has_no_video_management') ?>" type="radio" <?= !$oPage->getHideVideoLinkManagement() ? 'CHECKED' : '' ?> id="hideVideoLinkManagement_0" name="hideVideoLinkManagement" value="0"/>
                                            <label class="custom-control-label" for="hideVideoLinkManagement_0"><?= sysTranslations::get('global_no') ?></label>
                                        </div>
                                    </div>
                                </div>

                                

                                
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-12">
                    <div class="card-footer bg-white border-top-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a class="btn btn-default" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a>
                            <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </div>
                    </div>
                </div>
            </div>
                </form>
            </div>

            <div class="col-12 col-lg-4">
                <?php
                if (!empty($oPage->pageId)) {
                    /** @var $oAutocompleteManager AutocompleteManager */
                    foreach ($aAutocompleters as $oAutocompleteManager) {
                        echo $oAutocompleteManager->includeTemplate();
                    }
                }
                ?>
                <?php if (moduleExists('brandboxItems') && !$oPage->hideBrandboxManagement()) { ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><?= sysTranslations::get('brandbox_all_items') ?></h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0 sorted" style="min-width: 100%">
                                <thead>
                                <tr class="topRow">
                                    <td colspan="2" class="p-3">
                                        <form id="BB" method="POST" action="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/add-bb">
                                            <div class="form-group mb-2">
                                                <label for="brandboxItemId"><?= sysTranslations::get('brandbox') ?></label>
                                                <select name="brandboxItemId" id="brandboxItemId" class="form-control default" style="width: 100% !important;">
                                                    <option value=""><?= sysTranslations::get('brandbox_select') ?></option>
                                                    <?php
                                                    /** @var \BrandboxItem $oBrandbox */
                                                    foreach (BrandboxItemManager::getBrandboxItemsByFilter(['showAll' => true]) as $oBrandbox) {
                                                        if (in_array($oBrandbox->brandboxItemId, $oPage->getBrandboxItemIds())) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <option value="<?= $oBrandbox->brandboxItemId ?>"><?= $oBrandbox->name ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?= CSRFSynchronizerToken::field() ?>
                                            <input type="hidden" name="pageId" value="<?= $oPage->pageId ?>"/>
                                            <input type="hidden" name="action" value="add"/>
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm" title="<?= sysTranslations::get('brandbox_add_tooltip') ?>"
                                                   alt="<?= sysTranslations::get('brandbox_add_tooltip') ?>" value="<?= sysTranslations::get('global_save') ?>"/>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="{sorter:false} nonSorted p-2"><?= sysTranslations::get('global_name') ?></th>
                                    <th class="{sorter:false} nonSorted p-2" style="width: 30px;">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody class="EstateAgentTable_">
                                <?php foreach ($oPage->getBrandboxItems(true) as $oBrandbox): ?>
                                    <tr estate-agent-data="<?= $oBrandbox->brandboxItemId ?>">
                                        <td class="p-2"><?= _e($oBrandbox->name) ?></td>
                                        <td class="p-2">
                                            <a class="action_icon delete_icon" title="<?= sysTranslations::get('brandbox_delete') ?>" onclick="return confirmChoice('<?= $oBrandbox->name ?>');"
                                               href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/remove-bb/<?= $oPage->pageId ?>/<?= $oBrandbox->brandboxItemId ?>?<?= CSRFSynchronizerToken::query() ?>"></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (count($oPage->getBrandboxItems(true)) == 0): ?>
                                    <tr>
                                        <td colspan="2" class="p-3"><i><?= sysTranslations::get('brandbox_no_items') ?></i></td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                }
                if (!$oPage->hideImageManagement()) { ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><?= sysTranslations::get('global_images') ?></h3>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($oPage->pageId !== null) {
                                $oImageManagerHTML->includeTemplate();
                            } else {
                                echo '<p><i>' . sysTranslations::get('pages_images_warning') . '</i></p>';
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                if (!$oPage->hideFileManagement()) {
                ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><?= sysTranslations::get('global_files') ?></h3>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($oPage->pageId !== null) {
                                $oFileManagerHTML->includeTemplate();
                            } else {
                                echo '<p><i>' . sysTranslations::get('pages_files_warning') . '</i></p>';
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                if (!$oPage->hideLinkManagement()) {
                ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><?= sysTranslations::get('global_links') ?></h3>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($oPage->pageId !== null) {
                                $oLinkManagerHTML->includeTemplate();
                            } else {
                                echo '<p><i>' . sysTranslations::get('pages_links_warning') . '</i></p>';
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                if (!$oPage->hideVideoLinkManagement()) {
                ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><?= sysTranslations::get('global_videolinks') ?></h3>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($oPage->pageId !== null) {
                                $oVideoLinkManagerHTML->includeTemplate();
                            } else {
                                echo '<p><i>' . sysTranslations::get('pages_video_warning') . '</i></p>';
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$oPageLayout->addJavascript(
    '
<script>
    initTinyMCE(".tiny_MCE_page", "/dashboard/paginas/link-list", "/dashboard/paginas/image-list/' . $oPage->pageId . '");
</script>
'
);
?>
