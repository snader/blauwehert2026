<?php

class CatalogProductPropertyTypePossibleValueTranslation extends Model
{

    public $catalogProductPropertyTypePossibleValueTranslationId = null;
    public $catalogProductPropertyTypePossibleValueId            = null;
    public $languageId                                           = null;
    public $value                                                = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductPropertyTypePossibleValueId)) {
            $this->setPropInvalid('catalogProductPropertyTypePossibleValueId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->value)) {
            $this->setPropInvalid('value');
        }
    }

}