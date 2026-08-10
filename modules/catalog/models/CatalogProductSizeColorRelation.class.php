<?php

class CatalogProductSizeColorRelation extends Model
{

    public  $catalogProductId           = null;
    public  $catalogProductSizeId       = CatalogProductSize::sizeId_nosize;
    public  $catalogProductColorId      = CatalogProductColor::colorId_nocolor;
    public  $catalogProductSizeColorMPN = null;
    public  $stock                      = 0;
    public  $extraPrice                 = 0;
    private $oCatalogProduct            = null;
    private $oCatalogProductSize        = null;
    private $oCatalogProductColor       = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->catalogProductId)) {
            $this->setPropInvalid('catalogProductId');
        }
        if (!is_numeric($this->extraPrice)) {
            $this->setPropInvalid('extraPrice');
        }
    }

    /**
     *
     * @return CatalogProductColor
     */
    public function getColor()
    {
        if ($this->oCatalogProductColor === null) {
            $this->oCatalogProductColor = CatalogProductColorManager::getProductColorById($this->catalogProductColorId);
        }

        return $this->oCatalogProductColor;
    }

    /**
     *
     * @return CatalogProductSize
     */
    public function getSize()
    {
        if ($this->oCatalogProductSize === null) {
            $this->oCatalogProductSize = CatalogProductSizeManager::getProductSizeById($this->catalogProductSizeId);
        }

        return $this->oCatalogProductSize;
    }

    /**
     *
     * @return CatalogProduct
     */
    public function getProduct()
    {
        if ($this->oCatalogProduct === null) {
            $this->oCatalogProduct = CatalogProductManager::getProductById($this->catalogProductId);
        }

        return $this->oCatalogProduct;
    }

    /**
     * Return the Product-size-color MPN, if is NULL, return the general Product MPN
     *
     * @return catalogProductSizeColorMPN
     */
    public function getMPN()
    {
        if ($this->catalogProductSizeColorMPN === null) {
            $this->catalogProductSizeColorMPN = CatalogProductManager::getProductById($this->catalogProductId)->catalogProductMPN;
        }

        return $this->catalogProductSizeColorMPN;
    }
}

?>