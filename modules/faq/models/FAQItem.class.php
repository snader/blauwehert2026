<?php

class FAQItem extends Model
{

    public  $faqItemId;
    public  $languageId      = null;
    public  $question;
    public  $answer;
    public  $online          = 1;
    private $aCategories     = null; //array with different lists of FAQItemItem categories
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
        if (empty($this->question)) {
            $this->setPropInvalid('question');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }

        // check or at least 1 category is set
        if (class_exists('FAQItemCategoryManager')) {
            if (count($this->getCategories('all')) == 0) {
                $this->setPropInvalid('categories');
            }
        }
    }

    /**
     * check if news item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * check if news item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return true;
    }

    public function isOnline()
    {

        $bOnline = true;
        if (!($this->online)) {
            $bOnline = false;
        }

        // with categories so check if at least 1 category is online
        if (class_exists('FAQItemCategoryManager') && count($this->getCategories()) <= 0) {
            $bOnline = false;
        }

        return $bOnline;
    }

    /**
     * get page to get controller part in path
     *
     * @param ACMS\Locale $oLocale
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
     * get url to faq item
     *
     * @return string
     */
    public function getUrlPath()
    {
        // get page for controller part
        if ($this->getControllerPage()) {
            $sUrlPath = $this->getControllerPage()
                    ->getUrlPath() . '/' . $this->faqItemId . '/' . prettyUrlPart($this->question);
        } else {
            $sUrlPath = null;
        }

        return $sUrlPath;
    }

    /**
     * get url to page with base url
     *
     * @return string
     */
    public function getBaseUrlPath()
    {
        return getBaseUrl() . $this->getUrlPath();
    }

    /**
     * get all news item categories by specific list name for a FAQItemItem
     *
     * @param string $sList
     *
     * @return array FAQItemCategory
     */
    public function getCategories($sList = 'online')
    {
        if (!isset($this->aCategories[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aCategories[$sList] = FAQItemManager::getCategoriesByFilter($this->faqItemId);
                    break;
                case 'all':
                    $this->aCategories[$sList] = FAQItemManager::getCategoriesByFilter($this->faqItemId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aCategories[$sList];
    }

    /**
     * set categories
     *
     * @param array  $aFAQItemCategories
     * @param string $sList (set in specific list)
     */
    public function setCategories(array $aFAQItemCategories, $sList = 'online')
    {
        $this->aCategories[$sList] = $aFAQItemCategories;
    }

}

?>