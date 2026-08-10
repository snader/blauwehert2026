<?php

class CatalogProductCategory extends Model
{

    const MAX_LEVELS = 2;

    public  $catalogProductCategoryId = null;
    public  $online                   = 1;
    public  $order                    = 99999;
    public  $parentCatalogProductCategoryId;
    public  $level                    = 1;
    public  $created                  = null;
    public  $modified                 = null;
    private $lockParent               = 0; // lock parent category
    private $aSubCategories           = null;  // array with different lists of sub categories
    private $oParentCategory          = null; // references CatalogProductCategory class
    private $aTranslations            = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
    }

    /**
     * calculate level and set in object
     */
    public function setLevel()
    {
        if ($this->parentCatalogProductCategoryId) {
            $this->level = $this->getParent()->level + 1;
        } else {
            $this->level = 1;
        }
    }

    /**
     * just returns integer, DO NOT USE FOR LOCK PARENT CHECKING
     * return value of lockParent
     *
     * @return int
     */
    public function getLockParent()
    {
        return $this->lockParent;
    }

    /**
     * check to lock parent
     *
     * @return boolean
     */
    public function lockParent()
    {
        return $this->lockParent;
    }

    /**
     * return all subcategories for this category
     */
    public function getSubCategories($sList = 'online')
    {
        if (!isset($this->aSubCategories[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aSubCategories[$sList] = CatalogProductCategoryManager::getProductCategoriesByFilter(['parentCatalogProductCategoryId' => $this->catalogProductCategoryId]);
                    break;
                case 'all':
                    $this->aSubCategories[$sList] = CatalogProductCategoryManager::getProductCategoriesByFilter(['parentCatalogProductCategoryId' => $this->catalogProductCategoryId, 'showAll' => 1]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aSubCategories[$sList];
    }

    /**
     * return parent category
     *
     * @return CatalogProductCategory
     */
    public function getParent()
    {
        if ($this->oParentCategory === null) {
            $this->oParentCategory = CatalogProductCategoryManager::getProductCategoryById($this->parentCatalogProductCategoryId);
        }

        return $this->oParentCategory;
    }

    /**
     * check if object is deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return count($this->getSubCategories('all')) == 0 && count(
                CatalogProductManager::getProductsByFilter(['catalogProductCategoryId' => $this->catalogProductCategoryId, 'showAll' => true])
            ) == 0; // check for product relations and subcategories
    }

    /**
     * @global User $oCurrentUser
     *                     get translations
     *
     * @param       $mList , mixed
     *
     * @return CatalogProductCategoryTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = CatalogProductCategoryTranslationManager::getCategoryTranslationsByFilter(['categoryId' => $this->catalogProductCategoryId, 'languageId' => $mList]);
                if (!empty($aTranslations)) {
                    $this->aTranslations[$mList] = $aTranslations[0];
                } else {
                    $this->aTranslations[$mList] = null;
                }
            } else {

                switch ($mList) {
                    case 'auto-admin':
                        $iLanguageId   = Locales::getAdminLocale()
                            ->getLanguage()->languageId; // UserManager::getCurrentUser()->getLanguage()->languageId;
                        $aTranslations = CatalogProductCategoryTranslationManager::getCategoryTranslationsByFilter(['categoryId' => $this->catalogProductCategoryId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = CatalogProductCategoryTranslationManager::getCategoryTranslationsByFilter(['categoryId' => $this->catalogProductCategoryId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = CatalogProductCategoryTranslationManager::getCategoryTranslationsByFilter(['categoryId' => $this->catalogProductCategoryId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = CatalogProductCategoryTranslationManager::getCategoryTranslationsByFilter(['categoryId' => $this->catalogProductCategoryId]);
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