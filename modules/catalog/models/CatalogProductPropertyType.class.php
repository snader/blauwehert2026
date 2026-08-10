<?php

class CatalogProductPropertyType extends Model
{

    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TEXT     = 'text';
    const TYPE_SELECT   = 'select';

    public  $catalogProductPropertyTypeId = null;
    public  $type;
    public  $inputTranslatable            = 1; // is open text input type translatable?
    public  $filterType;
    public  $order                        = 99999;
    public  $catalogProductPropertyTypeGroupId;
    public  $icecatFeatureId              = null; // icecat Id of the related Feature
    public  $created                      = null;
    public  $modified                     = null;
    private $aPossibleValues              = null; // association with catalogProductPropertyTypePossibleValue class
    private $aValues                      = []; // association with CatalogProductPropertyValue class
    private $oProductPropertyTypeGroup    = null; // association with CatalogProductPropertyTypeGroup class

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->type)) {
            $this->setPropInvalid('type');
        }
        if (empty($this->order)) {
            $this->setPropInvalid('order');
        }
        if (empty($this->catalogProductPropertyTypeGroupId)) {
            $this->setPropInvalid('catalogProductPropertyTypeGroupId');
        }
    }

    /**
     * get all catalogProductPropertyTypePossibleValue related to this object
     *
     * @return array CatalogProductPropertyTypePossibleValue $this->aPossibleValues
     */
    public function getPossibleValues()
    {
        if ($this->aPossibleValues === null) {
            $this->aPossibleValues = CatalogProductPropertyTypePossibleValueManager::getPossibleValuesByProductPropertyTypeId($this->catalogProductPropertyTypeId);
        }

        return $this->aPossibleValues;
    }

    /**
     * get the CatalogProductPropertyValue related to this catalogProductPropertyType and a CatalogProduct
     *
     * @global User $oCurrentUser
     *
     * @param int   $iProductId
     * @param       $mList , mixed
     *
     * @return array CatalogProductPropertyValue $this->aValues
     */
    public function getValuesByProductId($iProductId, $mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();
        if (!isset($this->aValues[$iProductId][$mList])) {
            if (is_numeric($mList)) {
                $this->aValues[$iProductId][$mList] = CatalogProductPropertyValueManager::getProductPropertyValuesByProductIdProductPropertyTypeId($iProductId, $this->catalogProductPropertyTypeId, $mList);
            } else {
                switch ($mList) {
                    case 'auto-admin':
                        $iLanguageId                        = Locales::getAdminLocale()
                            ->getLanguage()->languageId;
                        $this->aValues[$iProductId][$mList] = CatalogProductPropertyValueManager::getProductPropertyValuesByProductIdProductPropertyTypeId($iProductId, $this->catalogProductPropertyTypeId, $iLanguageId);
                        break;
                    case 'auto':
                        $this->aValues[$iProductId][$mList] = CatalogProductPropertyValueManager::getProductPropertyValuesByProductIdProductPropertyTypeId($iProductId, $this->catalogProductPropertyTypeId, Locales::language());
                    case 'all':
                        $this->aValues[$iProductId][$mList] = CatalogProductPropertyValueManager::getProductPropertyValuesByProductIdProductPropertyTypeId($iProductId, $this->catalogProductPropertyTypeId, $mList);
                        break;
                    default:
                        die('no option');
                        break;
                }
            }
        }

        return $this->aValues[$iProductId][$mList];
    }

    /**
     * get the related CatalogProductPropertyTypeGroup
     *
     * @return CatalogProductPropertyTypeGroup
     */
    public function getPropertyTypeGroup()
    {
        if ($this->oProductPropertyTypeGroup === null) {
            $this->oProductPropertyTypeGroup = CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupById($this->catalogProductPropertyTypeGroupId);
        }

        return $this->oProductPropertyTypeGroup;
    }

    /**
     * get the related CatalogProductType
     *
     * @return CatalogProductType
     */
    public function getProductType()
    {
        return $this->getPropertyTypeGroup()
            ->getProductType();
    }

    /**
     * @global User $oCurrentUser
     *                     get translations
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductPropertyTypeTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductPropertyTypeTranslationManager::getPropertyTypeTranslationsByFilter(['propertyTypeId' => $this->catalogProductPropertyTypeId, 'languageId' => $mList]);
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
                        $aTranslations = CatalogProductPropertyTypeTranslationManager::getPropertyTypeTranslationsByFilter(['propertyTypeId' => $this->catalogProductPropertyTypeId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductPropertyTypeTranslationManager::getPropertyTypeTranslationsByFilter(['propertyTypeId' => $this->catalogProductPropertyTypeId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductPropertyTypeTranslationManager::getPropertyTypeTranslationsByFilter(['propertyTypeId' => $this->catalogProductPropertyTypeId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductPropertyTypeTranslationManager::getPropertyTypeTranslationsByFilter(['propertyTypeId' => $this->catalogProductPropertyTypeId]);
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
