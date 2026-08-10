<?php

class CatalogProductType extends Model
{

    public $catalogProductTypeId = null;
    public $withSizes            = 0;
    public $withColors           = 0;
    public $withGenders          = 0;
    public $order                = 99999;
    public $created;
    public $modified;

    /**
     * validate object
     */
    public function validate()
    {

    }

    /**
     * check if producttype is deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return CatalogProductTypeManager::getNumberOfProductsByProductTypeId($this->catalogProductTypeId) == 0; // check for product relations
    }

    /**
     * get translations
     *
     * @global User $oCurrentUser
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductTypeTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductTypeTranslationManager::getTypeTranslationsByFilter(['typeId' => $this->catalogProductTypeId, 'languageId' => $mList]);
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
                        $aTranslations = CatalogProductTypeTranslationManager::getTypeTranslationsByFilter(['typeId' => $this->catalogProductTypeId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductTypeTranslationManager::getTypeTranslationsByFilter(['typeId' => $this->catalogProductTypeId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductTypeTranslationManager::getTypeTranslationsByFilter(['typeId' => $this->catalogProductTypeId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductTypeTranslationManager::getTypeTranslationsByFilter(['typeId' => $this->catalogProductTypeId]);
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

?>