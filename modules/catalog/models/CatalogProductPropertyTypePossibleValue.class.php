<?php

class CatalogProductPropertyTypePossibleValue extends Model
{

    public $catalogProductPropertyTypePossibleValueId = null;
    public $multilingual;
    public $order                                     = 99999;
    public $catalogProductPropertyTypeId;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->multilingual)) {
            $this->setPropInvalid('multilingual');
        }
        if (!is_numeric($this->order)) {
            $this->setPropInvalid('order');
        }
        if (!is_numeric($this->catalogProductPropertyTypeId)) {
            $this->setPropInvalid('catalogProductPropertyTypeId');
        }
    }

    /**
     * get translations
     *
     * @global User $oCurrentUser
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductPropertyTypePossibleValueTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductPropertyTypePossibleValueTranslationManager::getPropertyTypePossibleValueTranslationsByFilter(['possibleValueId' => $this->catalogProductPropertyTypePossibleValueId, 'languageId' => $mList]);
                if (!empty($aTranslations)) {
                    $this->aTranslations[$mList] = $aTranslations[0];
                } else {
                    $this->aTranslations[$mList] = null;
                }
            } else {

                switch ($mList) {
                    case 'auto-admin':
                        $iLanguageId = Locales::getAdminLocale()
                            ->getLanguage()->languageId;
                        $aTranslations = CatalogProductPropertyTypePossibleValueTranslationManager::getPropertyTypePossibleValueTranslationsByFilter(
                            ['possibleValueId' => $this->catalogProductPropertyTypePossibleValueId, 'languageId' => $iLanguageId]
                        );
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductPropertyTypePossibleValueTranslationManager::getPropertyTypePossibleValueTranslationsByFilter(
                            ['possibleValueId' => $this->catalogProductPropertyTypePossibleValueId, 'languageId' => Locales::language()]
                        );
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductPropertyTypePossibleValueTranslationManager::getPropertyTypePossibleValueTranslationsByFilter(['possibleValueId' => $this->catalogProductPropertyTypePossibleValueId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductPropertyTypePossibleValueTranslationManager::getPropertyTypePossibleValueTranslationsByFilter(['possibleValueId' => $this->catalogProductPropertyTypePossibleValueId]);
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
