<nav id="navigation" class="main-nav" aria-label="Hoofdnavigatie">
    <?php
    $aUrl = [];

    function makeListTree($aPages)
    {
        global $aUrl;
        if (count($aPages) > 0) {
            echo '<ul class="nav-list">
            ';
            foreach ($aPages AS $oPage) {
                $iLevel = $oPage->level;

                if ($iLevel == 1) {
                    $aUrl[$iLevel] = '/' . http_get('controller');
                } else {
                    $aUrl[$iLevel] = $aUrl[$iLevel - 1] . '/' . http_get('param' . ($iLevel - 1));
                }

                // check if controller equals page urlPath be aware of special treathment for homepage!!
                echo '<li><a class="nav-link' . ($aUrl[$oPage->level] == $oPage->getUrlPath() || '/' . http_get('controller') == $oPage->getUrlPath() . 'home' ? ' active' : '') . '" href="' . $oPage->getBaseUrlPath() . '">' . $oPage->getShortTitle() . '</a>
                ';
                makeListTree($oPage->getSubPages()); //call function recursive
                echo '</li>
                ';
            }
            echo '</ul>';
        }
    }

    makeListTree(PageManager::getPagesByFilter(['level' => 1, 'inMenu' => 1, 'languageId' => Locales::language()]));
    ?>
</nav>

