<?php

class CatalogProductTranslation extends Model
{

    public  $catalogProductTranslationId = null;
    public  $catalogProductId            = null;
    public  $languageId                  = null;
    public  $name                        = null;
    public  $description                 = null;
    public  $windowTitle; //browser window title
    public  $metaKeywords; //meta tag keywords
    public  $metaDescription; //meta tag description
    private $urlPart; // part of the url to use in stead of the name
    public  $googleCategory              = null; // Google Category

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductId)) {
            $this->setPropInvalid('catalogProductId');
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
        $sUrlPath = PageManager::getPageByName('products')->getUrlPath() . '/' . $this->catalogProductId . '/' . prettyUrlPart(!empty($this->urlPart) ? $this->urlPart : $this->name);

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
        return PageManager::getPageByName('products')->getBaseUrlPath() . '/' . $this->catalogProductId . '/' . prettyUrlPart(!empty($this->urlPart) ? $this->urlPart : $this->name);
    }

    /**
     * return the window title if there is one, otherwise return name and  brand
     *
     * @return string
     */
    public function getWindowTitle($oCatalogProduct)
    {
        return !empty($this->windowTitle) ? $this->windowTitle : $this->name . ' - ' . $oCatalogProduct->getBrand()
                ->getTranslations()->name;
    }

    /**
     * return meta description if exists or a
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return !empty($this->metaDescription) ? $this->metaDescription : generateMetaDescription($this->description);
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
     * get an array of the breadcrumbs
     *
     * @param int $iCatalogProductCategoryId (get crumbles based on product in a category)
     *
     * @return array
     */
    public function getCrumbles($iCatalogProductCategoryId = null)
    {
        $aCrumbles = [];
        if ($iCatalogProductCategoryId) {
            $oCatalogProductCategory = CatalogProductCategoryManager::getProductCategoryById($iCatalogProductCategoryId);
            if ($oCatalogProductCategory) {
                $aCrumbles              = $oCatalogProductCategory->getCrumbles();
                $aCrumbles[$this->name] = $this->getBaseUrlPath() . '?catId=' . $iCatalogProductCategoryId;
            }
        } else {
            $oPage = PageManager::getPageByName('products');
            $aCrumbles = [$oPage->getShortTitle() => $oPage->getBaseUrlPath(), $this->name => $this->getBaseUrlPath()];
        }

        return $aCrumbles;
    }

}