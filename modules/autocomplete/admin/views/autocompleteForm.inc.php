<?php
/** @var AutocompleteManager $oAutocompleteManager */

?>
    <div class="contentColumn">
        <fieldset>
            <legend><?= $oAutocompleteManager->title ?></legend>
            <input type="text" class="default" id="autocomplete-<?= $oAutocompleteManager->getInstanceId() ?>" data-disable-default-on-after-select="true"/>

            <div class="table<?= $oAutocompleteManager->getInstanceId() ?>"></div>
        </fieldset>
    </div>
    <br />
<?php

$oPageLayout->addJavascript(
    "
<script>
    function parseAutocompleteResult(result) {
        var payload = result;
        if (typeof JSend !== 'undefined' && result && typeof result.status !== 'undefined') {
            try {
                var jsend = JSend.parse(result);
                payload = jsend.getData();
            } catch (e) {
                payload = null;
            }
        }
        return payload;
    }

    function setupAutocompleteTable(tableSelector) {
        var \$table = $(tableSelector);
        if (!\$table.length) {
            return;
        }

        \$table.find('table').each(function () {
            var \$innerTable = $(this);
            \$innerTable.addClass('sorted').removeClass('sorted-delayed');

            if ($.fn && $.fn.tablesorter) {
                if (typeof setTableSorterStuff === 'function') {
                    setTableSorterStuff();
                }
                if (!\$innerTable.hasClass('tablesorter-initialized')) {
                    \$innerTable.tablesorter();
                    \$innerTable.addClass('tablesorter-initialized');
                }
                return;
            }

            \$innerTable.find('thead th').each(function () {
                var \$th = $(this);
                if (\$th.hasClass('nonSorted')) {
                    return;
                }
                \$th.css('cursor', 'pointer').off('click.autocompleteSort').on('click.autocompleteSort', function () {
                    var \$body = \$innerTable.find('tbody');
                    var rows = \$body.find('tr').get();
                    var asc = \$th.data('sort-direction') !== 'asc';
                    \$th.data('sort-direction', asc ? 'asc' : 'desc');

                    rows.sort(function (a, b) {
                        var aText = $(a).children('td').first().text().trim().toLowerCase();
                        var bText = $(b).children('td').first().text().trim().toLowerCase();
                        if (aText < bText) {
                            return asc ? -1 : 1;
                        }
                        if (aText > bText) {
                            return asc ? 1 : -1;
                        }
                        return 0;
                    });

                    $.each(rows, function (_, row) {
                        \$body.append(row);
                    });
                });
            });
        });
    }

    function saveRelation" . $oAutocompleteManager->getInstanceId() . "(event, ui){
        $.ajax({
            method: 'post',
            url: '" . $oAutocompleteManager->getAddUrl() . "' + event.item.value,
            data: { instanceId: '" . $oAutocompleteManager->getInstanceId() . "', " . CSRFSynchronizerToken::FIELD . ": '" . CSRFSynchronizerToken::get() . "' },
            success: function (result) {
                var payload = parseAutocompleteResult(result);
                if (payload && payload.html) {
                    if (typeof alertify !== 'undefined') {
                        alertify.success('" . sysTranslations::get('autocomplete_add_success') . "');
                    }
                    $('.table" . $oAutocompleteManager->getInstanceId() . "').html(payload.html);
                    setupAutocompleteTable('.table" . $oAutocompleteManager->getInstanceId() . "');
                }
            }
        });
    }
    
    function getDataTable" . $oAutocompleteManager->getInstanceId() . "(){
        $.ajax({
            method: 'post',
            url: '" . $oAutocompleteManager->getDataTableUrl() . "',
            data: { filter: '" . $oAutocompleteManager->getDataFilter() . "', instanceId: '" . $oAutocompleteManager->getInstanceId() . "'},
            success: function (result) {
                var payload = parseAutocompleteResult(result);
                if (payload && payload.html) {
                    $('.table" . $oAutocompleteManager->getInstanceId() . "').html(payload.html);
                    setupAutocompleteTable('.table" . $oAutocompleteManager->getInstanceId() . "');
                }
            }
        });
    }
    
    function deleteDataRelation" . $oAutocompleteManager->getInstanceId() . "(linkedItemId){
        $.ajax({
            method: 'post',
            url: '" . $oAutocompleteManager->getRemoveUrl() . "' + linkedItemId,
            data: { instanceId: '" . $oAutocompleteManager->getInstanceId() . "', " . CSRFSynchronizerToken::FIELD . ": '" . CSRFSynchronizerToken::get() . "'  },
            success: function (result) {
                var payload = parseAutocompleteResult(result);
                if (payload && payload.html) {
                    $('.table" . $oAutocompleteManager->getInstanceId() . "').html(payload.html);
                    setupAutocompleteTable('.table" . $oAutocompleteManager->getInstanceId() . "');
                }
            }
        });
    }
    
    $(document).on('click', '.table" . $oAutocompleteManager->getInstanceId() . " .unlinkItem', function(){
        var deleteEvent = $(this);
        if (confirmChoice('" . sysTranslations::get('autocomplete_this_link') . "')) {
            deleteDataRelation" . $oAutocompleteManager->getInstanceId() . "($(deleteEvent).data('linked-id'));
            if (typeof alertify !== 'undefined') {
                alertify.success('" . sysTranslations::get('autocomplete_delete_success') . "');
            }
        } 
    });
   
    
    getDataTable" . $oAutocompleteManager->getInstanceId() . "();
    
    setDefaultAutocomplete(
        '#autocomplete-" . $oAutocompleteManager->getInstanceId() . "',
        '" . $oAutocompleteManager->getGetUrl() . "',
        '" . $oAutocompleteManager->getFilter() . "',
        null,
        saveRelation" . $oAutocompleteManager->getInstanceId() . "
    );
    
</script>
"
);
