<?php

class CatalogBrandTranslation extends Model
{

    public $catalogBrandTranslationId = null;
    public $catalogBrandId            = null;
    public $languageId                = null;
    public $name                      = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogBrandId)) {
            $this->setPropInvalid('catalogBrandId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
    }

}