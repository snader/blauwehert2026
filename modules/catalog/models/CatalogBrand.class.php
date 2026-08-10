<?php

class CatalogBrand extends Model
{

    public  $catalogBrandId = null;
    public  $multilingual   = 0;
    public  $online         = 1;
    public  $order          = 99999;
    public  $created        = null;
    public  $modified       = null;
    private $aTranslations  = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->multilingual)) {
            $this->setPropInvalid('multilingual');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
    }

    /**
     * check if object is deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return CatalogBrandManager::getNumberOfProductsByBrandId($this->catalogBrandId) == 0; // check for product relations
    }

    /**
     * @global User $oCurrentUser
     *                     get translations
     *
     * @param       $mList , mixed
     *
     * @return CatalogBrandTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogBrandTranslationManager::getBrandTranslationsByFilter(['brandId' => $this->catalogBrandId, 'languageId' => $mList]);
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
                        $aTranslations = CatalogBrandTranslationManager::getBrandTranslationsByFilter(['brandId' => $this->catalogBrandId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogBrandTranslationManager::getBrandTranslationsByFilter(['brandId' => $this->catalogBrandId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogBrandTranslationManager::getBrandTranslationsByFilter(['brandId' => $this->catalogBrandId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogBrandTranslationManager::getBrandTranslationsByFilter(['brandId' => $this->catalogBrandId]);
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
