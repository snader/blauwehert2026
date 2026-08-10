<?php

class CatalogProduct extends Model
{

    const IMAGES_PATH   = '/uploads/images/catalog/product';
    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_UNISEX = 'unisex';

    public  $catalogProductId    = null;
    private $salePrice; // sale price
    private $purchasePrice; // purchase price
    private $reducedPrice; // reduced price
    public  $catalogProductMPN; // Manufacter Part Number, general
    public  $gender              = self::GENDER_UNISEX;
    public  $online              = 1;
    public  $created             = null;
    public  $modified            = null;
    public  $catalogBrandId;
    public  $catalogProductTypeId;
    public  $taxPercentageId     = 1; // see TaxManager for actual percentages
    public  $showOnHome          = 0; // show product on homepage
    private $aImages             = null; //array with different lists of images
    private $oProductType        = null; // association with CatalogProductType class
    private $aPropertyTypeGroups = null; // association with CatalogProductPropertyTypeGroup class
    private $aPropertyTypes      = null; // association with CatalogProductPropertyType class
    private $aPropertyValues     = null; // association with CatalogProductPropertyValue class
    private $oBrand              = null; // association with CatalogBrand class
    private $aCategories         = null; // array of lists with categories
    private $aColors             = null; // array of lists with colors in wich the product is available
    private $aSizes              = null; // array of lists with sizes in wich the product is available

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->salePrice)) {
            $this->setPropInvalid('salePrice');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
        if (!is_numeric($this->catalogBrandId)) {
            $this->setPropInvalid('catalogBrandId');
        }
        if (!is_numeric($this->catalogProductTypeId)) {
            $this->setPropInvalid('catalogProductTypeId');
        }
        if (!is_numeric($this->taxPercentageId)) {
            $this->setPropInvalid('taxPercentageId');
        }
        $aCategories = $this->getCategories('all');
        if (empty($aCategories)) {
            $this->setPropInvalid('catalogProductCategory');
        }
    }

    /**
     * get all images by specific list name for a page
     *
     * @param string $sList
     *
     * @return Image or array Images
     */
    public function getImages($sList = 'online')
    {
        if (!isset($this->aImages[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aImages[$sList] = CatalogProductManager::getImagesByFilter($this->catalogProductId);
                    break;
                case 'first-online':
                    $aImages = CatalogProductManager::getImagesByFilter($this->catalogProductId, [], 1);
                    if (!empty($aImages)) {
                        $oImage = $aImages[0];
                    } else {
                        $oImage = null;
                    }
                    $this->aImages[$sList] = $oImage;
                    break;
                case 'all':
                    $this->aImages[$sList] = CatalogProductManager::getImagesByFilter($this->catalogProductId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aImages[$sList];
    }

    /**
     * get the product type
     *
     * @return CatalogProductType
     */
    public function getProductType()
    {
        if ($this->oProductType === null) {
            $this->oProductType = CatalogProductTypeManager::getProductTypeById($this->catalogProductTypeId);
        }

        return $this->oProductType;
    }

    /**
     * get all CatalogProductPropertyTypeGroup objects related to this one
     *
     * @return array CatalogProductPropertyTypeGroup
     */
    public function getPropertyTypeGroups()
    {
        if ($this->aPropertyTypeGroups === null) {
            $this->aPropertyTypeGroups = CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupsByProductTypeId($this->catalogProductTypeId);
        }

        return $this->aPropertyTypeGroups;
    }

    /**
     * get all CatalogProductPropertyType objects related to this one
     *
     * @return array CatalogProductPropertyType
     */
    public function getPropertyTypes()
    {
        if ($this->aPropertyTypes === null) {
            $this->aPropertyTypes = CatalogProductPropertyTypeManager::getProductPropertyTypesByProductTypeId($this->catalogProductTypeId);
        }

        return $this->aPropertyTypes;
    }

    /**
     * get all CatalogProductPropertyValue objects related to this one. Use getPropertyTypes() and then the CatalogProductPropertyType's getValues() function for a more structured output.
     *
     * @return array CatalogProductPropertyValue
     */
    public function getAllPropertyValues()
    {
        if ($this->aPropertyValues === null) {
            $this->aPropertyValues = CatalogProductPropertyValueManager::getAllProductPropertyValuesByProductId($this->catalogProductId);
        }

        return $this->aPropertyValues;
    }

    /**
     * get all CatalogProductPropertyValue objects related to this one. Use getPropertyTypes() and then the CatalogProductPropertyType's getValues() function for a more structured output.
     *
     * @return array CatalogProductPropertyValue
     */
    public function getPropertyValues()
    {
        if ($this->aPropertyValues === null) {
            $this->aPropertyValues = CatalogProductPropertyValueManager::getProductPropertyValuesByProductId($this->catalogProductId);
        }

        return $this->aPropertyValues;
    }

    /**
     * get all product categories by specific list name for a CatalogProduct
     *
     * @param string $sList
     *
     * @return array CatalogProductCategory
     */
    public function getCategories($sList = 'online')
    {
        if (!isset($this->aCategories[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aCategories[$sList] = CatalogProductManager::getCategoriesByFilter($this->catalogProductId);
                    break;
                case 'all':
                    $this->aCategories[$sList] = CatalogProductManager::getCategoriesByFilter($this->catalogProductId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aCategories[$sList];
    }

    public function getColorSizeRelations($sList = 'inStock')
    {
        $aFilter['catalogProductId'] = $this->catalogProductId;
        if (!isset($this->aColorSizeRelations[$sList])) {
            switch ($sList) {
                case 'inStock':
                    $this->aColorSizeRelations[$sList] = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter);
                    break;
                case 'all':
                    $aFilter['showAll']                = true;
                    $this->aColorSizeRelations[$sList] = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter);
                    break;
                case 'cheapest':
                    $this->aColorSizeRelations[$sList] = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter, 1, ['`cpcsr`.`extraPrice`' => 'ASC', '`cps`.`order`' => 'ASC']);

                    return !empty($this->aColorSizeRelations[$sList][0]) ? $this->aColorSizeRelations[$sList][0] : null;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aColorSizeRelations[$sList];
    }

    /**
     * set categories
     *
     * @param array  $aCatalogProductCategories
     * @param string $sList (set in specific list)
     */
    public function setCategories(array $aCatalogProductCategories, $sList = 'online')
    {
        $this->aCategories[$sList] = $aCatalogProductCategories;
    }

    /**
     * set property values
     *
     * @param array CatalogProductPropertyValue objects
     */
    public function setPropertyValues(array $aPropertyValues)
    {
        $this->aPropertyValues = $aPropertyValues;
    }

    /**
     * get the brand object for this product
     *
     * @return CatalogBrand
     */
    public function getBrand()
    {
        if ($this->oBrand === null) {
            $this->oBrand = CatalogBrandManager::getBrandById($this->catalogBrandId);
        }

        return $this->oBrand;
    }

    /**
     * get the related CatalogBrand's name
     *
     * @return string
     */
    public function getBrandName()
    {
        return $this->getBrand() ? $this->getBrand()
            ->getTranslations()->name : null;
    }

    /**
     * get the tax percantage of this object
     *
     * @param bool $bWithPercentageSign wether or not to return as string with %-sign default = false
     *
     * @return mixed tax percentage of this object
     */
    public function getTaxPercentage($bWithPercentageSign = false)
    {
        return TaxManager::getPercentageById($this->taxPercentageId, $bWithPercentageSign);
    }

    /**
     * get the salePrice with or without the tax included
     *
     * @param bool $bWithTax
     * @param int  $iCatalogProductColorId price based on chosen product color
     * @param int  $iCatalogProductSizeId  price based on chosen product size
     * @param bool $bWithExtraPrice
     * @param bool $bGetReducedPrice
     *
     * @return float
     */
    public function getSalePrice($bWithTax = false, $iCatalogProductColorId = null, $iCatalogProductSizeId = null, $bWithExtraPrice = true, $bGetReducedPrice = true)
    {
        $fSalePrice = $this->salePrice;

        if ($this->reducedPrice !== null && $bGetReducedPrice) {
            $fSalePrice = $this->reducedPrice;
        }

        if ($bWithExtraPrice) {
            $aFilter['catalogProductId']      = $this->catalogProductId;
            $aFilter['catalogProductColorId'] = $iCatalogProductColorId;
            $aFilter['catalogProductSizeId']  = $iCatalogProductSizeId;
            $aCheapestColorSizeRelations      = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter, 1, ['`cpcsr`.`extraPrice`' => 'ASC', '`cps`.`order`' => 'ASC']);
            if (!empty($aCheapestColorSizeRelations[0])) {
                $fSalePrice += $aCheapestColorSizeRelations[0]->extraPrice;
            }
        }

        $bTaxIncluded = intval(Settings::get('taxIncluded'));

        if ($bWithTax && !$bTaxIncluded) {
            $fSalePrice = TaxManager::addTax($fSalePrice, TaxManager::getPercentageById($this->taxPercentageId));
        } elseif (!$bWithTax && $bTaxIncluded) {
            $fSalePrice = TaxManager::subtractTax($fSalePrice, TaxManager::getPercentageById($this->taxPercentageId));
        }

        return $fSalePrice;
    }

    /**
     * get the purchasePrice with or without the tax included
     *
     * @param bool $bWithTax
     * @param int  $iCatalogProductColorId price based on chosen product color
     * @param int  $iCatalogProductSizeId  price based on chosen product size
     * @param bool $bWithExtraPrice
     * @param bool $bGetReducedPrice
     *
     * @return float
     */
    public function getPurchasePrice($bWithTax = false, $iCatalogProductColorId = null, $iCatalogProductSizeId = null, $bWithExtraPrice = false)
    {
        $fPurchasePrice = $this->purchasePrice;

        if ($bWithExtraPrice) {
            $aFilter['catalogProductId']      = $this->catalogProductId;
            $aFilter['catalogProductColorId'] = $iCatalogProductColorId;
            $aFilter['catalogProductSizeId']  = $iCatalogProductSizeId;
            $aCheapestColorSizeRelations      = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter, 1, ['`cpcsr`.`extraPrice`' => 'ASC', '`cps`.`order`' => 'ASC']);
            if (!empty($aCheapestColorSizeRelations[0])) {
                $fPurchasePrice += $aCheapestColorSizeRelations[0]->extraPrice;
            }
        }

        $bTaxIncluded = intval(Settings::get('taxIncluded'));

        if ($bWithTax && !$bTaxIncluded) {
            $fPurchasePrice = TaxManager::addTax($fPurchasePrice, TaxManager::getPercentageById($this->taxPercentageId));
        } elseif (!$bWithTax && $bTaxIncluded) {
            $fPurchasePrice = TaxManager::subtractTax($fPurchasePrice, TaxManager::getPercentageById($this->taxPercentageId));
        }

        return $fPurchasePrice;
    }

    /**
     * get the reducedPrice with or without the tax included
     *
     * @param bool $bWithTax
     * @param int  $iCatalogProductColorId price based on chosen product color
     * @param int  $iCatalogProductSizeId  price based on chosen product size
     * @param bool $bWithExtraPrice        *
     *
     * @return float
     */
    public function getReducedPrice($bWithTax = false, $iCatalogProductColorId = null, $iCatalogProductSizeId = null, $bWithExtraPrice = false)
    {
        if ($this->reducedPrice !== null) {
            $fReducedPrice = $this->reducedPrice;

            if ($bWithExtraPrice) {
                $aFilter['catalogProductId']      = $this->catalogProductId;
                $aFilter['catalogProductColorId'] = $iCatalogProductColorId;
                $aFilter['catalogProductSizeId']  = $iCatalogProductSizeId;
                $aCheapestColorSizeRelations      = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter, 1, ['`cpcsr`.`extraPrice`' => 'ASC', '`cps`.`order`' => 'ASC']);

                if (!empty($aCheapestColorSizeRelations[0])) {
                    $fReducedPrice += $aCheapestColorSizeRelations[0]->extraPrice;
                }
            }

            $bTaxIncluded = intval(Settings::get('taxIncluded'));

            if ($bWithTax && !$bTaxIncluded) {
                $fReducedPrice = TaxManager::addTax($fReducedPrice, TaxManager::getPercentageById($this->taxPercentageId));
            } elseif (!$bWithTax && $bTaxIncluded) {
                $fReducedPrice = TaxManager::subtractTax($fReducedPrice, TaxManager::getPercentageById($this->taxPercentageId));
            }

            return $fReducedPrice;
        } else {
            return null;
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
     * get all product colors by specific list name for a CatalogProduct
     *
     * @param string $sList
     *
     * @return array CatalogProduct
     */
    public function getColors($sList = 'inStock')
    {
        if (!isset($this->aColors[$sList])) {
            switch ($sList) {
                case 'inStock':
                    $this->aColors[$sList] = CatalogProductManager::getColorsByFilter($this->catalogProductId);
                    break;
                case 'all':
                    $this->aColors[$sList] = CatalogProductManager::getColorsByFilter($this->catalogProductId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aColors[$sList];
    }

    /**
     * get all product sizes by specific list name for a CatalogProduct
     *
     * @param string $sList
     *
     * @return array CatalogProduct
     */
    public function getSizes($sList = 'inStock')
    {
        if (!isset($this->aSizes[$sList])) {
            switch ($sList) {
                case 'inStock':
                    $this->aSizes[$sList] = CatalogProductManager::getSizesByFilter($this->catalogProductId);
                    break;
                case 'all':
                    $this->aSizes[$sList] = CatalogProductManager::getSizesByFilter($this->catalogProductId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aSizes[$sList];
    }

    /**
     * return if product has a color size combi that has an extra price
     *
     * @return boolean
     */
    public function hasExtraPrice()
    {
        return (bool)CatalogProductManager::getNOSizeColorRelationsByFilter($this->catalogProductId, ['withExtraPrice' => 1]);
    }

    /**
     * set Sale price
     *
     * @param int $iSalePrice
     */
    public function setSalePrice($iSalePrice)
    {
        $this->salePrice = $iSalePrice;
    }

    /**
     * set reduced price
     *
     * @param int $iReducedPrice
     */
    public function setReducedPrice($iReducedPrice)
    {
        $this->reducedPrice = $iReducedPrice;
    }

    /**
     * set purchase price
     *
     * @param int $iPurchasePrice
     */
    public function setPurchasePrice($iPurchasePrice)
    {
        $this->purchasePrice = $iPurchasePrice;
    }

    /**
     * get related products
     */
    public function getRelatedProducts()
    {
        return CatalogProductManager::getRelatedProductsByFilter($this->catalogProductId);
    }

    /**
     * save a related product
     *
     * @param int $iRelatedProduct
     */
    public function saveRelatedProduct($iRelatedProduct)
    {
        return CatalogProductManager::saveRelatedProductById($this->catalogProductId, $iRelatedProduct);
    }

    /**
     * delete a related product
     *
     * @param int $iRelatedProduct
     */
    public function deleteRelatedProduct($iRelatedProduct)
    {
        return CatalogProductManager::deleteRelatedProductById($this->catalogProductId, $iRelatedProduct);
    }

    /**
     * get previous product based on a filter
     *
     * @param array $aFilter
     * @param array $aOrderBy
     *
     * @return CatalogProduct
     */
    public function getNextProduct(array $aFilter = [], array $aOrderBy = ['catalogProductId' => 'DESC'])
    {
        $aFilter['catalogProductId>'] = $this->catalogProductId;
        $iFoundRows                   = false;
        $aProducts                    = CatalogProductManager::getProductsByFilter($aFilter, 1, null, $iFoundRows, $aOrderBy);
        if (!empty($aProducts)) {
            return $aProducts[0];
        } else {
            return null;
        }
    }

    /**
     * get previous product based on a filter
     *
     * @param array $aFilter
     * @param array $aOrderBy
     *
     * @return CatalogProduct
     */
    public function getPrevProduct(array $aFilter = [], array $aOrderBy = ['catalogProductId' => 'DESC'])
    {
        $aFilter['catalogProductId<'] = $this->catalogProductId;
        $iFoundRows                   = false;
        $aProducts                    = CatalogProductManager::getProductsByFilter($aFilter, 1, null, $iFoundRows, $aOrderBy);
        if (!empty($aProducts)) {
            return $aProducts[0];
        } else {
            return null;
        }
    }

    /**
     * @global User $oCurrentUser
     *                     get translations
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductTranslationManager::getProductTranslationsByFilter(['productId' => $this->catalogProductId, 'languageId' => $mList]);
                if (!empty($aTranslations)) {
                    $this->aTranslations[$mList] = $aTranslations[0];
                } else {
                    $this->aTranslations[$mList] = null;
                }
            } else {

                switch ($mList) {
                    case 'auto-admin':
                        $iLanguageId   = Locales::getAdminLocale()
                            ->getLanguage()->languageId;
                        $aTranslations = CatalogProductTranslationManager::getProductTranslationsByFilter(['productId' => $this->catalogProductId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductTranslationManager::getProductTranslationsByFilter(['productId' => $this->catalogProductId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductTranslationManager::getProductTranslationsByFilter(['productId' => $this->catalogProductId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductTranslationManager::getProductTranslationsByFilter(['productId' => $this->catalogProductId]);
                        break;
                    default:
                        die('no option');
                        break;
                }
            }
        }

        return $this->aTranslations[$mList];
    }

    /**
     * get product information in Stuctured Data JSON-LD
     *
     * @return string in JSON-LD format
     */
    public function getStucturedData()
    {

        # check https://developers.google.com/search/docs/guides/intro-structured-data for more information

        if (count($this->getColorSizeRelations('all')) == 0) {

            $aBrandData = [
                "@type" => "Thing",
                "name"  => $this->getBrand()
                    ->getTranslations()->name,
            ];

            // get image
            $aStructImages = [];
            $aImages       = $this->getImages();
            foreach ($aImages as $oImage) {
                $oImageFileCrop = $oImage->getImageFileByReference('crop_small');
                if (!empty($oImageFileCrop)) {
                    $aStructImages[] = CLIENT_HTTP_URL . $oImageFileCrop->link;
                }
            }

            $aSellerData = [
                "@type" => "Organization",
                "name"  => CLIENT_NAME,
            ];

            /* when using reviews
             *
             * $aAggregateRatingData = array(
             *   "@type" => "AggregateRating",
             *   "ratingValue" => "4.4",
             *   "reviewCount" => "89"
             * );
            */

            $aOfferData = [
                "@type"         => "Offer",
                "priceCurrency" => "EUR",
                "price"         => $this->getSalePrice(true),
                // "priceValidUntil" => "2020-11-05",
                "itemCondition" => "http://schema.org/NewCondition", // DamagedCondition,NewCondition,RefurbishedCondition,UsedCondition
                "availability"  => "http://schema.org/InStock",
                "seller"        => $aSellerData,
            ];

            $aData = [
                "@context"    => "http://schema.org",
                "@type"       => "Product",
                "name"        => $this->getTranslations()->name,
                "image"       => $aStructImages,
                "description" => _e(strip_tags($this->getTranslations()->description)), // $this->getTranslations()->description,
                "mpn"         => $this->catalogProductMPN,
                "brand"       => $aBrandData,
                "offers"      => $aOfferData,
            ];

        } else {


            foreach ($this->getColorSizeRelations('all') AS $oColorSizeRelation) {


                $aBrandData = [
                    "@type" => "Thing",
                    "name"  => $this->getBrand()
                        ->getTranslations()->name,
                ];

                $aSellerData = [
                    "@type" => "Organization",
                    "name"  => CLIENT_NAME,
                ];

                // I get all the Product-Image relations and I group them by color in an Array
                $aStructImages                    = [];
                $aFilter                          = [];
                $aFilter['notNullColors']         = '1';
                $aFilter['catalogProductId']      = $this->catalogProductId;
                $aFilter['catalogProductColorId'] = $oColorSizeRelation->catalogProductColorId;
                foreach (CatalogProductImageRelationManager::getCatalogProductImageRelationsByFilter($aFilter) AS $oImageProductRelation) {
                    $oImage         = ImageManager::getImageById($oImageProductRelation->imageId);
                    $oImageFileCrop = $oImage->getImageFileByReference('crop_small');
                    if (!empty($oImageFileCrop)) {
                        $aStructImages[] = CLIENT_HTTP_URL . $oImageFileCrop->link;
                    }
                }

                $sSize = '';
                if ($this->getProductType()->withSizes) {
                    $sSize = $oColorSizeRelation->catalogProductSizeId ? ' - ' . $oColorSizeRelation->getSize()
                            ->getTranslations()->name : '';
                }

                if ($this->getProductType()->withSizes || $this->getProductType()->withColors) {
                    $this->catalogProductMPN = $oColorSizeRelation->catalogProductSizeColorMPN;
                }

                $aOfferData = [
                    "@type"         => "Offer",
                    "priceCurrency" => "EUR",
                    "price"         => $this->getSalePrice(true, $oColorSizeRelation->catalogProductColorId, $oColorSizeRelation->catalogProductSizeId, true, true),
                    // "priceValidUntil" => "2020-11-05",
                    "itemCondition" => "http://schema.org/NewCondition", // DamagedCondition,NewCondition,RefurbishedCondition,UsedCondition
                    "availability"  => "http://schema.org/InStock",
                    "seller"        => $aSellerData,
                ];

                if ($this->getProductType()->withColors) {
                    $sColor = $oColorSizeRelation->catalogProductColorId ? $oColorSizeRelation->getColor()
                        ->getTranslations()->name : '';
                }

                $aData[] = [
                    "@context"    => "http://schema.org",
                    "@type"       => "Product",
                    "name"        => $this->getTranslations()->name . $sSize,
                    "image"       => $aStructImages,
                    "description" => _e(strip_tags($this->getTranslations()->description)), // $this->getTranslations()->description,
                    "mpn"         => $this->catalogProductMPN,
                    "brand"       => $aBrandData,
                    "color"       => $sColor ?? '',
                    "offers"      => $aOfferData,
                ];

            }

        }

        // Turn on output buffering with the gzhandler

        return json_encode($aData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

}
