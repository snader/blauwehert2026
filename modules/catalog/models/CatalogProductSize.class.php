<?php

class CatalogProductSize extends Model
{

    const sizeId_nosize = -1;

    public  $catalogProductSizeId = null;
    public  $multilingual         = 0;
    public  $order                = 99999;
    public  $created              = null;
    public  $modified             = null;
    private $aTranslations        = null;

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
    }

    /**
     * check if object is deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return CatalogProductSizeManager::isUnused($this->catalogProductSizeId);
    }

    /**
     * get translations
     *
     * @global User $oCurrentUser
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductSizeTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductSizeTranslationManager::getSizeTranslationsByFilter(['sizeId' => $this->catalogProductSizeId, 'languageId' => $mList]);
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
                        $aTranslations = CatalogProductSizeTranslationManager::getSizeTranslationsByFilter(['sizeId' => $this->catalogProductSizeId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductSizeTranslationManager::getSizeTranslationsByFilter(['sizeId' => $this->catalogProductSizeId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductSizeTranslationManager::getSizeTranslationsByFilter(['sizeId' => $this->catalogProductSizeId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductSizeTranslationManager::getSizeTranslationsByFilter(['sizeId' => $this->catalogProductSizeId]);
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