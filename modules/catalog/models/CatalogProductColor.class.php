<?php

class CatalogProductColor extends Model
{

    const colorId_nocolor = -1;

    public  $catalogProductColorId = null;
    public  $order                 = 99999;
    public  $created               = null;
    public  $modified              = null;
    private $aTranslations         = null;

    /**
     * validate object
     */
    public function validate()
    {
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
        return CatalogProductColorManager::isUnused($this->catalogProductColorId);
    }

    /**
     * @global User $oCurrentUser
     *                     get translations
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductColorTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductColorTranslationManager::getColorTranslationsByFilter(['colorId' => $this->catalogProductColorId, 'languageId' => $mList]);
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
                        $aTranslations = CatalogProductColorTranslationManager::getColorTranslationsByFilter(['colorId' => $this->catalogProductColorId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductColorTranslationManager::getColorTranslationsByFilter(['colorId' => $this->catalogProductColorId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductColorTranslationManager::getColorTranslationsByFilter(['colorId' => $this->catalogProductColorId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductColorTranslationManager::getColorTranslationsByFilter(['colorId' => $this->catalogProductColorId]);
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
