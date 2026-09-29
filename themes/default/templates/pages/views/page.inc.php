<?php
if ($oPage->name) {   

    include __DIR__ . '/' . $oPage->name . '.inc.php';
} else {
    include __DIR__ . '/page_details.inc.php';
}

?>