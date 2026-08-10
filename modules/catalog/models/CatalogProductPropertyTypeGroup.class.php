<?php

class CatalogProductPropertyTypeGroup extends Model
{

    public  $catalogProductPropertyTypeGroupId = null;
    public  $order                             = 99999;
    public  $created                           = null;
    public  $modified                          = null;
    public  $catalogProductTypeId;
    private $oProductType                      = null; // association with CatalogProductType class
    private $aPropertyTypes                    = null; // association with CatalogProductPropertyType class
    private $aTranslations                     = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->order)) {
            $this->setPropInvalid('order');
        }
        if (empty($this->catalogProductTypeId)) {
            $this->setPropInvalid('catalogProductTypeId');
        }
    }

    /**
     * get the CatalogProductType
     *
     * @return CatalogProductType
     */
    public function getProductType()
    {
        if ($this->oProductType === null) {
            $this->oProductType = CatalogProductTypeManager::getProductTypeById($this->catalogProductTypeId);
        }

        return $this->oProductType;
    }

    public function getPropertyTypes()
    {
        if ($this->aPropertyTypes === null) {
            $this->aPropertyTypes = CatalogProductPropertyTypeManager::getProductPropertyTypesByProductPropertyTypeGroupId($this->catalogProductPropertyTypeGroupId);
        }

        return $this->aPropertyTypes;
    }

    /**
     * check if object is deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return CatalogProductPropertyTypeGroupManager::getNumberOfProductPropertyTypesByProductPropertyTypeGroupId($this->catalogProductPropertyTypeGroupId) == 0; // check for product relations
    }

    /**
     * @global User $oCurrentUser
     *                     get translations
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductPropertyTypeGroupTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductPropertyTypeGroupTranslationManager::getPropertyTypeGroupTranslationsByFilter(['propertyTypeGroupId' => $this->catalogProductPropertyTypeGroupId, 'languageId' => $mList]);
                if (!empty($aTranslations)) {
                    $this->aTranslations[$mList] = $aTranslations[0];
                } else {
                    $this->aTranslations[$mList] = null;
                }
            } else {

                switch ($mList) {
                    case 'auto-admin':
                        $iLanguageId   = Locales::getAdminLocale()
                            ->getLanguage()->languageId;
                        $aTranslations = CatalogProductPropertyTypeGroupTranslationManager::getPropertyTypeGroupTranslationsByFilter(['propertyTypeGroupId' => $this->catalogProductPropertyTypeGroupId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductPropertyTypeGroupTranslationManager::getPropertyTypeGroupTranslationsByFilter(['propertyTypeGroupId' => $this->catalogProductPropertyTypeGroupId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductPropertyTypeGroupTranslationManager::getPropertyTypeGroupTranslationsByFilter(['propertyTypeGroupId' => $this->catalogProductPropertyTypeGroupId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductPropertyTypeGroupTranslationManager::getPropertyTypeGroupTranslationsByFilter(['propertyTypeGroupId' => $this->catalogProductPropertyTypeGroupId]);
                        break;
                    default:
                        die('no option');
                        break;
                }
            }
        }

        return $this->aTranslations[$mList];
    }

}
