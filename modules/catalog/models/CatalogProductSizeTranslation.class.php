<?php

class CatalogProductSizeTranslation extends Model
{

    const sizeId_nosize = -1;

    public $catalogProductSizeTranslationId = null;
    public $catalogProductSizeId            = null;
    public $languageId                      = null;
    public $name                            = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductSizeId)) {
            $this->setPropInvalid('catalogProductSizeId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
    }

}