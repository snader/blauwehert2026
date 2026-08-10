<?php

class CatalogProductPropertyTypeTranslation extends Model
{

    public $catalogProductPropertyTypeTranslationId = null;
    public $catalogProductPropertyTypeId            = null;
    public $languageId                              = null;
    public $title                                   = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductPropertyTypeId)) {
            $this->setPropInvalid('catalogProductPropertyTypeId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
    }

}