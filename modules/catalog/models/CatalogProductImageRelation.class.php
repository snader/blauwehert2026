<?php

class CatalogProductImageRelation extends Model
{

    public $catalogProductId      = null;
    public $imageId               = null;
    public $catalogProductColorId = null;

    private $oCatalogProduct      = null;
    private $oCatalogProductColor = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->catalogProductId)) {
            $this->setPropInvalid('catalogProductId');
        }
        if (!is_numeric($this->imageId)) {
            $this->setPropInvalid('imageId');
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
     * get an image
     *
     * @return Image
     *
     */
    public function getImage()
    {
        return CatalogProductImageRelationManager::getCatalogProductImageRelationImageById($this->catalogProductId, $this->imageId, $this->catalogProductColorId);
    }
}

?>