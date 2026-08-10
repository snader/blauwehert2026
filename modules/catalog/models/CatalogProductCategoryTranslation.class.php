<?php

class CatalogProductCategoryTranslation extends Model
{

    public  $catalogProductCategoryTranslationId = null;
    public  $catalogProductCategoryId            = null;
    public  $languageId                          = null;
    public  $name                                = null;
    public  $content;
    public  $windowTitle; //browser window title
    public  $metaKeywords; //meta tag keywords
    public  $metaDescription; //meta tag description
    private $urlPart; // part of the url to use in stead of the name

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductCategoryId)) {
            $this->setPropInvalid('catalogProductCategoryId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
    }

    /**
     * return part of the url for this page
     *
     * @return string
     */
    public function getUrlPart()
    {
        return $this->urlPart;
    }

    /**
     * set url part for this page
     *
     * @param string $sUrlPart
     */
    public function setUrlPart($sUrlPart)
    {
        $this->urlPart = prettyUrlPart($sUrlPart);
    }

    /**
     * get url to a product optional with extension .html
     *
     * @param boolean $bWithExtension (optional) DEFAULT FALSE
     *
     * @return string
     */
    public function getUrlPath($bWithExtension = false)
    {
        $sUrlPath = PageManager::getPageByName('product_categories')->getUrlPath() . '/' . $this->catalogProductCategoryId . '/' . prettyUrlPart(!empty($this->urlPart) ? $this->urlPart : $this->name);

        if ($bWithExtension) {
            return $sUrlPath . '.html';
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
        return PageManager::getPageByName('product_categories')->getBaseUrlPath() . '/' . $this->catalogProductCategoryId . '/' . prettyUrlPart(!empty($this->urlPart) ? $this->urlPart : $this->name);
    }

    /**
     * return the window title if there is one, otherwise return title
     *
     * @return string
     */
    public function getWindowTitle()
    {
        if (!empty($this->windowTitle)) {
            return $this->windowTitle;
        } elseif (($oPage = PageManager::getPageByUrlPath('/producten'))) {
            return $oPage->getShortTitle() . ' - ' . $this->name;
        } else {
            return 'Webshop - ' . $this->name;
        }
    }

    /**
     * return meta description if exists or a
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return !empty($this->metaDescription) ? $this->metaDescription : generateMetaDescription($this->content);
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
     * generates an array of the breadcrumbs, but in reverse order
     *
     * @param array $aCrumbles
     */
    private function generateCrumbles(&$aCrumbles = [], $oProductCategory)
    {
        $aCrumbles[$this->name] = $this->getBaseUrlPath();

        $oParentCategory = $oProductCategory->getParent();
        if (!empty($oParentCategory)) {
            $oParentCategory->getTranslations()
                ->generateCrumbles($aCrumbles, $oParentCategory);
        }
    }

    /**
     * get an array of the breadcrumbs in the right order
     *
     * @return array
     */
    public function getCrumbles($oProductCategory)
    {

        // try to get /producten page and set as first crumble after home
        $oPage = PageManager::getPageByName('product_categories');
        $this->generateCrumbles($aCrumbles, $oProductCategory);
        $aCrumbles[$oPage->getShortTitle()] = $oPage->getBaseUrlPath();

        return array_reverse($aCrumbles);
    }

}