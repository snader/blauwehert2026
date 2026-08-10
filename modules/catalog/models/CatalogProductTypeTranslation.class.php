<?php

class CatalogProductTypeTranslation extends Model
{

    public $catalogProductTypeTranslationId = null;
    public $catalogProductTypeId            = null;
    public $languageId                      = null;
    public $title                           = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductTypeId)) {
            $this->setPropInvalid('catalogProductTypeId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
    }

}