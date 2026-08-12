<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-file-alt"></i>&nbsp;&nbsp;<?= sysTranslations::get('pages_menu') ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= sysTranslations::get('pages_all') ?></h3>
                    <div class="card-tools">
                        <?php include_once getAdminSnippet('localeSelect'); ?>
                        <a class="btn btn-default btn-sm" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/toevoegen"><?= sysTranslations::get('pages_add_main') ?></a>
                        <a class="btn btn-default btn-sm" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/structuur-wijzigen"><?= sysTranslations::get('pages_change_structure') ?></a>
                        <a class="btn btn-default btn-sm" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/copy-structure"><?= sysTranslations::get('pages_copy_structure') ?></a>
                    </div>
                </div>
                <div class="card-body">

<?php
# list pages in a Bootstrap table
function renderPageRows($aPages, $iLevel, $iMaxLevels)
{
    foreach ($aPages AS $oPage) {
        $sComment = '';
        if ($oPage->name == 'home') {
            $sComment .= ($sComment != '' ? ', ' : '') . sysTranslations::get('global_homepage');
        }
        if (!$oPage->getInMenu()) {
            $sComment .= ($sComment != '' ? ', ' : '') . sysTranslations::get('global_not_shown_in_menu');
        }
        if (!$oPage->getIndexable()) {
            $sComment .= ($sComment != '' ? ', ' : '') . sysTranslations::get('global_not_indexable');
        }

        $sTitle = ($oPage->getShortTitle() ? $oPage->getShortTitle() : $oPage->title);
        if ($sComment) {
            $sTitle .= ' <span class="text-muted">(' . $sComment . ')</span>';
        }

        echo '<tr>';
        echo '<td class="align-middle" style="padding-left: ' . (($iLevel - 1) * 20) . 'px;">' . $sTitle . '</td>';
        echo '<td class="align-middle text-right">';

        if ($iLevel < $iMaxLevels && $oPage->mayHaveSub()) {
            echo '<a class="btn btn-info btn-sm" title="' . sysTranslations::get('pages_add_sub') . '" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/toevoegen?parentPageId=' . $oPage->pageId . '"><i class="fas fa-plus"></i></a> ';
        }

        if ($oPage->isOnlineChangeable()) {
            echo '<a id="page_' . $oPage->pageId . '_online_1" pageTitle="' . _e($oPage->title) . '" title="' . sysTranslations::get('pages_offline_tooltip') . '" class="btn btn-success btn-sm ' . ($oPage->online ? '' : 'd-none') . ' offline_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oPage->pageId . '/?online=0&' . CSRFSynchronizerToken::query() . '"><i class="fas fa-eye"></i></a> ';
            echo '<a id="page_' . $oPage->pageId . '_online_0" pageTitle="' . _e($oPage->title) . '" title="' . sysTranslations::get('pages_online_tooltip') . '" class="btn btn-secondary btn-sm ' . ($oPage->online ? 'd-none' : '') . ' online_icon" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/ajax-setOnline/' . $oPage->pageId . '/?online=1&' . CSRFSynchronizerToken::query() . '"><i class="fas fa-eye-slash"></i></a> ';
        } else {
            echo '<button class="btn btn-default btn-sm" title="' . sysTranslations::get('pages_not_offline') . '" disabled><i class="fas fa-eye"></i></button> ';
        }

        if ($oPage->isEditable()) {
            echo '<a title="' . sysTranslations::get('pages_edit') . '" class="btn btn-primary btn-sm" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPage->pageId . '"><i class="fas fa-pencil-alt"></i></a> ';
        } else {
            echo '<button title="' . sysTranslations::get('pages_not_editable') . '" class="btn btn-default btn-sm" disabled><i class="fas fa-pencil-alt"></i></button> ';
        }

        if ($oPage->isDeletable()) {
            echo '<a onclick="return confirmChoice(\'' . _e($oPage->title) . '\');" class="btn btn-danger btn-sm" href="' . ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oPage->pageId . '?' . CSRFSynchronizerToken::query() . '"><i class="fas fa-trash"></i></a>';
        } else {
            echo '<button class="btn btn-default btn-sm" title="' . sysTranslations::get('pages_not_deletable') . '" disabled><i class="fas fa-trash"></i></button>';
        }

        echo '</td>';
        echo '</tr>';

        renderPageRows($oPage->getSubPages('all'), $iLevel + 1, $iMaxLevels);
    }
}

?>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th><?= sysTranslations::get('pages_name') ?: 'Pagina' ?></th>
                <th style="width: 320px;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($aAllLevel1Pages) == 0) {
                echo '<tr><td colspan="2"><i>' . sysTranslations::get('pages_no_pages') . '</i></td></tr>';
            } else {
                renderPageRows($aAllLevel1Pages, 1, $iMaxLevels);
            }
            ?>
        </tbody>
    </table>
</div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<?php

$sPageOnlineMsg     = sysTranslations::get('pages_online');
$sPageOfflineMsg    = sysTranslations::get('pages_offline');
$sPageNotChangedMsg = sysTranslations::get('pages_not_changed');
# add ajax code for online/offline handling
$sNestedSortableJavascript = <<<EOT
<script>
    $("a.online_icon, a.offline_icon").click(function(e){
        $.ajax({
            type: "GET",
            url: this.href,
            data: "ajax=1",
            async: true,
            success: function(data){
                var dataObj = eval('(' + data + ')');

                /* On success */
                if(dataObj.success == true){
                    $("#page_"+dataObj.pageId+"_online_0").hide(); // hide offline button
                    $("#page_"+dataObj.pageId+"_online_1").hide(); // hide online button
                    $("#page_"+dataObj.pageId+"_online_"+dataObj.online).css('display', 'inline-block'); // show button based on online value
                    if(dataObj.online == 0)
                        showStatusUpdate("$sPageOfflineMsg");
                    if(dataObj.online == 1)
                        showStatusUpdate("$sPageOnlineMsg");
                }else{
                        showStatusUpdate("$sPageNotChangedMsg");
                }
            }
        });
        e.preventDefault();
    });
</script>
EOT;
$oPageLayout->addJavascript($sNestedSortableJavascript);
?>