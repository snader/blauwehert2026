<?php

class FAQItemCategory extends Model
{

    public  $faqItemCategoryId;
    public  $languageId      = null;
    public  $name;
    public  $online          = 1;
    public  $order           = 99999;
    private $aFAQItems       = []; //array with different lists of FAQitems
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
     * get all FAQ items by specific list name for a category
     *
     * @param string $sList
     *
     * @return FAQItem or array FAQItem
     */
    public function getFAQItems($sList = 'online')
    {
        if (!isset($this->aFAQItems[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aFAQItems[$sList] = FAQItemCategoryManager::getFAQItemsByFilter($this->faqItemCategoryId);
                    break;
                case 'all':
                    $this->aFAQItems[$sList] = FAQItemCategoryManager::getFAQItemsByFilter($this->faqItemCategoryId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aFAQItems[$sList];
    }

    /**
     * check if object is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return count($this->getFAQItems('all')) == 0;
    }

    public function isOnline()
    {
        return $this->online && count($this->getFAQItems()) > 0;
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
            $this->oControllerPage = PageManager::getPageByName('faqitems', $this->languageId);
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

}

?>