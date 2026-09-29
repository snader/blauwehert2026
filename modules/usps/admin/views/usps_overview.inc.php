<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0"><i class="fas fa-star mr-2"></i><?= sysTranslations::get('usp_all_items') ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= sysTranslations::get('usp_all_items') ?></h3>
                    <div class="card-tools">
                        <?php include_once getAdminSnippet('localeSelect'); ?>
                        <a class="btn btn-primary btn-sm" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/toevoegen" title="<?= sysTranslations::get('usp_add') ?>">
                            <?= sysTranslations::get('usp_add') ?>
                        </a>
                        <a class="btn btn-default btn-sm" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/volgorde-wijzigen&lang=1" title="<?= sysTranslations::get('global_change_order') ?>">
                            <?= sysTranslations::get('global_change_order') ?>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered sorted withActionIcons mb-0">
                            <thead>
                            <tr>
                                <th class="{sorter:false} nonSorted onlineOffline" style="width: 70px;"></th>
                                <th><?= sysTranslations::get('global_title') ?></th>
                                <th class="{sorter:false} nonSorted text-right" style="width: 140px;">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody id="tableContents">
                            <?php
                            foreach ($aAllUsps AS $oUsp) { ?>
                                <tr data-id="<?= $oUsp->uspId ?>" class="movable">
                                    <td class="align-middle text-center">
                                        <a id="usp_<?= $oUsp->uspId ?>_online_1" title="<?= sysTranslations::get('usp_set_offline') ?>" class="btn btn-success btn-xs <?= ($oUsp->online ? '' : 'd-none') ?> online_icon"
                                           href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() ?>/ajax-setOnline/<?= $oUsp->uspId ?>/?online=0&<?= CSRFSynchronizerToken::query() ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a id="usp_<?= $oUsp->uspId ?>_online_0" title="<?= sysTranslations::get('usp_set_online') ?>" class="btn btn-secondary btn-xs <?= ($oUsp->online ? 'd-none' : '') ?> offline_icon"
                                           href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() ?>/ajax-setOnline/<?= $oUsp->uspId ?>/?online=1&<?= CSRFSynchronizerToken::query() ?>">
                                            <i class="fas fa-eye-slash"></i>
                                        </a>
                                    </td>
                                    <td class="align-middle"><?= $oUsp->name ?></td>
                                    <td class="align-middle text-right">
                                        <a class="btn btn-primary btn-sm" title="<?= sysTranslations::get('usp_edit') ?>" href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId ?>">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm" title="<?= sysTranslations::get('usp_delete') ?>" onclick="return confirmChoice('<?= $oUsp->name ?>')" href="<?= ADMIN_FOLDER . '/' . Request::getControllerSegment() ?>/verwijderen/<?= $oUsp->uspId ?>?<?= CSRFSynchronizerToken::query() ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                            if (empty($aAllUsps)) { ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4"><i><?= sysTranslations::get('usp_no_items') ?></i></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

# create necessary javascript
$sController             = Request::getControllerSegment();
$uspOnline           = sysTranslations::get('usp_online');
$uspOffline          = sysTranslations::get('usp_offline');
$uspStatusNotChanged = sysTranslations::get('usp_status_not_changed');
$sBottomJavascript       = <<<EOT
<script type="text/javascript">
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
                    $("#usp_"+dataObj.uspId+"_online_0").hide();
                    $("#usp_"+dataObj.uspId+"_online_1").hide();
                    $("#usp_"+dataObj.uspId+"_online_"+dataObj.online).css('display', 'inline-block');
                    if(dataObj.online == 0)
                        showStatusUpdate("$uspOffline");
                    if(dataObj.online == 1)
                        showStatusUpdate("$uspOnline");
                } else {
                    showStatusUpdate("$uspStatusNotChanged");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
