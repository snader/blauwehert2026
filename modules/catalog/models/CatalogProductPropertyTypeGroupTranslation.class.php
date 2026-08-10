<?php

class CatalogProductPropertyTypeGroupTranslation extends Model
{

    public $catalogProductPropertyTypeGroupTranslationId = null;
    public $catalogProductPropertyTypeGroupId            = null;
    public $languageId                                   = null;
    public $title                                        = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->catalogProductPropertyTypeGroupId)) {
            $this->setPropInvalid('catalogProductPropertyTypeGroupId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
    }

}