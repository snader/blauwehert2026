<?php

class CatalogProductColorTranslation extends Model
{

    const colorId_nocolor = -1;

    public $catalogProductColorTranslationId = null;
    public $catalogProductColorId            = null;
    public $languageId                       = null;
    public $name                             = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductColorId)) {
            $this->setPropInvalid('catalogProductColorId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
    }

}