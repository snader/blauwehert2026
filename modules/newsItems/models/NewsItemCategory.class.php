<?php

class NewsItemCategory extends Model
{

    public  $newsItemCategoryId;
    public  $languageId      = null;
    public  $windowTitle; //browser window title
    public  $metaKeywords;
    public  $metaDescription;
    public  $name;
    private $urlPart; // part of the url fe /nieuws/urlpart
    private $urlPartText; // text to use for url part
    public  $online          = 1;
    public  $order           = 99999;
    public  $created;
    public  $modified;
    private $aNewsItems      = []; //array with different lists of newsitems
    private $oControllerPage = null; // get page to get controller from to show item with right controller
    private $aLocales        = null; // locales for language

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
    }

    /**
     * get url to category optional with extension .html
     *
     * @return string
     */
    public function getUrlPath()
    {
        // get page for controller part
        if ($this->getControllerPage()) {
            $sUrlPath = $this->getControllerPage()
                    ->getUrlPath() . '/' . $this->getUrlPart();
        } else {
            $sUrlPath = null;
        }

        return $sUrlPath;
    }

    /**
     * get page to get controller part in path
     *
     * @return Page
     */
    private function getControllerPage()
    {
        if ($this->oControllerPage === null) {
            $this->oControllerPage = PageManager::getPageByName('newsitems', $this->languageId);
        }

        return $this->oControllerPage;
    }

    /**
     * get Locales of this page
     *
     * @return $aLocales
     */
    public function getLocales()
    {
        if ($this->aLocales === null) {
            $this->aLocales = LocaleManager::getLocalesByFilter(['languageId' => $this->languageId]);
        }

        return $this->aLocales;
    }

    /**
     * get url to category from base
     *
     * @return string
     */
    public function getBaseUrlPath()
    {
        return getBaseUrl() . $this->getUrlPath();
    }

    /**
     * return the window title if there is one, otherwise return name
     *
     * @return string
     */
    public function getWindowTitle()
    {
        return $this->windowTitle ? $this->windowTitle : $this->name;
    }

    /**
     * return meta description if exists or a
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * return meta keywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     *
     * get an array of the breadcrumbs
     *
     * @return array
     */
    public function getCrumbles()
    {
        $aCrumbles = [];
        if ($this->getControllerPage()) {
            $aCrumbles[$this->getControllerPage()
                ->getShortTitle()]  = $this->getControllerPage()
                ->getBaseUrlPath();
            $aCrumbles[$this->name] = $this->getBaseUrlPath();
        }

        return $aCrumbles;
    }

    /**
     * get all news items by specific list name for a category
     *
     * @param string $sList
     *
     * @return NewsItem or array NewsItem
     */
    public function getNewsItems($sList = 'online')
    {
        if (!isset($this->aNewsItems[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aNewsItems[$sList] = NewsItemCategoryManager::getNewsItemsByFilter($this->newsItemCategoryId);
                    break;
                case 'all':
                    $this->aNewsItems[$sList] = NewsItemCategoryManager::getNewsItemsByFilter($this->newsItemCategoryId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aNewsItems[$sList];
    }

    /**
     * check if object is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return count($this->getNewsItems('all')) == 0;
    }

    /**
     * set url part text
     *
     * @param string $sUrlPartText
     */
    public function setUrlPartText($sUrlPartText)
    {
        $this->urlPartText = prettyUrlPart($sUrlPartText);
    }

    /**
     * return url part text
     *
     * @return string
     */
    public function getUrlPartText()
    {
        return $this->urlPartText;
    }

    /**
     * set url part
     *
     * @param string $sUrlPart
     */
    public function setUrlPart($sUrlPart)
    {
        $this->urlPart = prettyUrlPart($sUrlPart);
    }

    /**
     * return urlPart
     *
     * @return string
     */
    public function getUrlPart()
    {
        return $this->urlPart;
    }

    /**
     * return generated urlPart
     *
     * @return string
     */
    public function generateUrlPart()
    {
        return $this->urlPartText ? $this->urlPartText : prettyUrlPart($this->name);
    }

    public function isOnline()
    {
        return $this->online;
    }

}
