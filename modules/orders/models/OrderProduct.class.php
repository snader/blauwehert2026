<?php

class OrderProduct extends Model
{

    public  $orderProductId        = null;
    public  $orderId;
    public  $catalogProductId      = null;
    public  $brandName             = null;
    public  $productName;
    public  $amount                = 1;
    private $originalPrice; // Original price
    private $salePrice; // Sale price
    private $purchasePrice; // Purchase price
    public  $catalogProductSizeId  = CatalogProductSize::sizeId_nosize; // size id of chosen product (default is ID of size record)
    public  $productSizeName; // color id of chosen product
    public  $catalogProductColorId = CatalogProductColor::colorId_nocolor; // size name of chosen product (default is ID of nocolor record)
    public  $productColorName; // color name of chosen product
    public  $substractedFromStock  = 0; // amount already substracted from stock (when order is canceled, stock will be added again when already substracted)
    private $taxPercentage         = null; // tax percentage of this object
    private $oProduct              = null; // association with CatalogProduct class

    /**
     * validate object
     */

    public function validate()
    {
        if (!empty($this->catalogProductId) && !is_numeric($this->catalogProductId)) {
            $this->setPropInvalid('catalogProductId');
        }
        if (empty($this->productName)) {
            $this->setPropInvalid('productName');
        }
        if (!is_numeric($this->amount)) {
            $this->setPropInvalid('amount');
        }
        //if (!is_numeric($this->salePrice) || $this->salePrice < 0)
        //  $this->setPropInvalid('salePrice');
        if (!is_numeric($this->taxPercentage)) {
            $this->setPropInvalid('taxPercentage');
        }
        if (!is_numeric($this->catalogProductSizeId)) {
            $this->setPropInvalid('catalogProductSizeId');
        }
        if (!is_numeric($this->catalogProductColorId)) {
            $this->setPropInvalid('catalogProductColorId');
        }
        if (!is_numeric($this->substractedFromStock)) {
            $this->setPropInvalid('substractedFromStock');
        }
    }

    /**
     * set this object's properties by CatalogProduct and color size relation
     *
     * @param CatalogProduct $oCatalogProduct
     */
    public function setOrderProduct(CatalogProduct $oCatalogProduct, CatalogProductSizeColorRelation $oSizeColorRelation)
    {
        $this->catalogProductId      = $oCatalogProduct->catalogProductId;
        $this->brandName             = $oCatalogProduct->getBrandName();
        $this->productName           = $oCatalogProduct->getTranslations()->name;
        $this->originalPrice         = $oCatalogProduct->getSalePrice(Settings::get('taxIncluded'), $oSizeColorRelation->catalogProductColorId, $oSizeColorRelation->catalogProductSizeId, true, false);
        $this->salePrice             = $oCatalogProduct->getSalePrice(Settings::get('taxIncluded'), $oSizeColorRelation->catalogProductColorId, $oSizeColorRelation->catalogProductSizeId, true);
        $this->purchasePrice         = $oCatalogProduct->getPurchasePrice(Settings::get('taxIncluded'), $oSizeColorRelation->catalogProductColorId, $oSizeColorRelation->catalogProductSizeId, true);
        $this->taxPercentage         = TaxManager::getPercentageById($oCatalogProduct->taxPercentageId);
        $this->catalogProductSizeId  = $oSizeColorRelation->catalogProductSizeId;
        $this->productSizeName       = $oSizeColorRelation->catalogProductSizeId != CatalogProductSize::sizeId_nosize ? $oSizeColorRelation->getSize()
            ->getTranslations()->name : '';
        $this->catalogProductColorId = $oSizeColorRelation->catalogProductColorId;
        $this->productColorName      = $oSizeColorRelation->catalogProductColorId != CatalogProductColor::colorId_nocolor ? $oSizeColorRelation->getColor()
            ->getTranslations()->name : '';
    }

    /**
     * get the related CatalogProduct
     *
     * @return CatalogProduct
     */
    public function getProduct()
    {
        if ($this->oProduct === null) {
            $this->oProduct = CatalogProductManager::getProductById($this->catalogProductId);
        }

        return $this->oProduct;
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
        if ($bWithPercentageSign) {
            return (string)$this->taxPercentage . '%';
        } else {
            return $this->taxPercentage;
        }
    }

    /**
     * get the originalPrice with or without tax included
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getOriginalPrice($bWithTax = false)
    {
        $bTaxIncluded = intval(Settings::get('taxIncluded'));

        if ($bWithTax && !$bTaxIncluded) {
            return TaxManager::addTax($this->originalPrice, $this->getTaxPercentage());
        } elseif (!$bWithTax && $bTaxIncluded) {
            return TaxManager::subtractTax($this->originalPrice, $this->getTaxPercentage());
        } else {
            return $this->originalPrice;
        }
    }

    /**
     * get the salePrice with or without tax included
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getSalePrice($bWithTax = false)
    {
        $bTaxIncluded = intval(Settings::get('taxIncluded'));

        if ($bWithTax && !$bTaxIncluded) {
            return TaxManager::addTax($this->salePrice, $this->getTaxPercentage());
        } elseif (!$bWithTax && $bTaxIncluded) {
            return TaxManager::subtractTax($this->salePrice, $this->getTaxPercentage());
        } else {
            return $this->salePrice;
        }
    }

    /**
     * get the purchasePrice with or without tax included
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getPurchasePrice($bWithTax = false)
    {
        $bTaxIncluded = intval(Settings::get('taxIncluded'));

        if ($bWithTax && !$bTaxIncluded) {
            return TaxManager::addTax($this->purchasePrice, $this->getTaxPercentage());
        } elseif (!$bWithTax && $bTaxIncluded) {
            return TaxManager::subtractTax($this->purchasePrice, $this->getTaxPercentage());
        } else {
            return $this->purchasePrice;
        }
    }

    /**
     * get the total price of this object
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getSubTotalPrice($bWithTax = false)
    {
        return round($this->amount * $this->getSalePrice($bWithTax), 2);
    }

    /**
     * get the total original price of this object
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getSubTotalOriginalPrice($bWithTax = false)
    {
        return round($this->amount * $this->getOriginalPrice($bWithTax), 2);
    }

    /**
     * get the tax price of the order's total price
     *
     * @return float
     */
    public function getBTW()
    {
        return $this->getSubTotalPrice(true) - $this->getSubTotalPrice(false);
    }
}
