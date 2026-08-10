<?php

class CatalogProductPropertyValue extends Model
{

    public $catalogProductPropertyValueId = null;
    public $catalogProductId;
    public $catalogProductPropertyTypeId;
    public $languageId;
    public $value;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductId)) {
            $this->setPropInvalid('catalogProductId');
        }
        if (!is_numeric($this->catalogProductPropertyTypeId)) {
            $this->setPropInvalid('catalogProductPropertyTypeId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->value)) {
            $this->setPropInvalid('value');
        }
    }

}

?>