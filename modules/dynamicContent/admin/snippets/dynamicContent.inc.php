<?php

// this is the backend snippet;
if (isset($oDynamicContent)) {

    switch ($oDynamicContent->type) {

        case "html":
            echo "<div class='dynamicContent " . $oDynamicContent->name . "'>";
            echo $oDynamicContent->content;
            echo "</div>";
            break;
        case "text":
            echo "<div class='dynamicContent " . $oDynamicContent->name . "'>";
            echo _e($oDynamicContent->content);
            echo "</div>";
            break;
        case "code":
            echo $oDynamicContent->content;
            break;

    }

}