<?php

class NewsItemController extends PageController
{
    /**
     * Init
     */
    public function init()
    {
        parent::init();

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();
    }

    /**
     * News Index
     *
     * @param null $sRequestURL
     *
     * @return \CoreController|string
     */
    public function index($sRequestURL = null)
    {
        if ($mResult = parent::index($sRequestURL)) {
            return $mResult;
        }

        // Check what kind of page to show.
        if (is_numeric(Request::param('ID'))) {
            // Show NewsItem Detail
            return $this->newsItem(Request::param('ID'));
        } elseif (($oPageArchive = PageManager::getPageByName('newsitems_archive')) && $oPageArchive->getUrlPath() == getCurrentUrlPath()) {
            // Show Archive Page
            return $this->archive($oPageArchive);
        } elseif (Request::param('ID')) {
            // Show Category Page
            return $this->category(Request::param('ID'));
        }

        $iPerPage = 3;
        $this->newsOverview($iPerPage, $this->oPage->getBaseUrlPath());

        $aNewsItemCategoriesForMenu = [];
        if (class_exists('NewsItemCategoryManager')) {
            $aNewsItemCategoriesForMenu = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['languageId' => Locales::language()]);
        }

        $this->getRenderEngine()
            ->setVariables(['aNewsItemCategoriesForMenu' => $aNewsItemCategoriesForMenu, 'sArchiveLink' => $this->getArchiveLink()]);
        $this->getRenderEngine()
            ->getLayout()->sViewPath = getSiteView('newsItems_overview', 'newsItems');
    }

    /**
     * NewsItem Detail Page
     *
     * @param $iNewsItemId
     */
    protected function newsItem($iNewsItemId)
    {
        $oNewsItem = NewsItemManager::getNewsItemById($iNewsItemId);

        $bPreviewMode = ((Request::getVar('preview') == 1 && UserManager::getCurrentUser()) ? true : false);

        if (empty($oNewsItem) || !$oNewsItem->isOnline($bPreviewMode)) {
            return Router::httpError('404');
        }

        $iCategoryId = Request::getVar('categoryId');

        if (is_numeric($iCategoryId)) {
            $iNewsItemCategoryId = $iCategoryId;
        } else {
            $iNewsItemCategoryId = null;
        }

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oPageLayout->sWindowTitle     = $oNewsItem->getWindowTitle();
        $oPageLayout->sMetaDescription = $oNewsItem->getMetaDescription();
        $oPageLayout->sMetaKeywords    = $oNewsItem->getMetaKeywords();
        $oPageLayout->generateCustomCrumblePath($oNewsItem->getCrumbles($iNewsItemCategoryId));
        $oPageLayout->sOGType         = 'article';
        $oPageLayout->sOGTitle        = $oNewsItem->getWindowTitle();
        $oPageLayout->sOGDescription  = $oNewsItem->getMetaDescription();
        $oPageLayout->sOGUrl          = getCurrentUrl();
        $oPageLayout->sStructuredData = $oNewsItem->getStucturedData();

        if (($oImage = $oNewsItem->getImages('first-online')) && ($oImageFile = $oImage->getImageFileByReference('crop_small'))) {
            $oPageLayout->sOGImage       = getBaseUrl() . $oImageFile->link;
            $oPageLayout->sOGImageWidth  = $oImageFile->getWidth();
            $oPageLayout->sOGImageHeight = $oImageFile->getHeight();
        }

        # Always set canonical to itself to prevent duplicate content
        $oPageLayout->sCanonical = $oNewsItem->getBaseUrlPath();

        $aImages = $oNewsItem->getImages();
        $aFiles  = $oNewsItem->getFiles();
        $aLinks  = $oNewsItem->getLinks();
        $aVideos = $oNewsItem->getVideoLinks();

        if ($iNewsItemCategoryId && ($oNewsItemCategory = NewsItemCategoryManager::getNewsItemCategoryById($iNewsItemCategoryId))) {
            $sBackLink = $oNewsItemCategory->getBaseUrlPath();
        } else {
            $oControllerPage = PageManager::getPageByName('newsitems');
            if ($oControllerPage) {
                $sBackLink = $oControllerPage->getBaseUrlPath();
            } else {
                $sBackLink = null;
            }
        }
        $this->getRenderEngine()
            ->setVariables(
                [
                    'oNewsItem'    => $oNewsItem,
                    'aImages'      => $aImages,
                    'aFiles'       => $aFiles,
                    'aLinks'       => $aLinks,
                    'aVideos'      => $aVideos,
                    'sBackLink'    => $sBackLink,
                    'aWhitePapers' => moduleExists('whitePapers') ? $oNewsItem->getWhitePapers() : null,
                    'aErrors'      => Session::get('aErrors') ?: null,
                ]
            );
        $oPageLayout->sViewPath = getSiteView('newsItem_details', 'newsItems');
    }

    /**
     * Archive Page
     *
     * @param Page $oPage
     */
    protected function archive(Page $oPage)
    {
        parent::index();

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $iPerPage = 6;
        $this->newsOverview($iPerPage, $oPage->getBaseUrlPath());

        $oPageLayout->sViewPath = getSiteView('newsItems_archive', 'newsItems');
    }

    /***
     * @param      $iPerPage
     * @param      $sBaseUrlPath
     * @param null $iCategoryId
     */
    protected function newsOverview($iPerPage, $sBaseUrlPath, $iCategoryId = null)
    {
        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $iCurrPage = Request::getVar('page') ?: 1;
        if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
            Router::redirect($sBaseUrlPath);
        }
        $iStart    = (($iCurrPage - 1) * $iPerPage);

        $aNewsItems = NewsItemManager::getNewsItemsByFilter(['newsItemCategoryId' => $iCategoryId, 'languageId' => Locales::language()], $iPerPage, $iStart, $iFoundRows);

        $iPageCount = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

        // page is greater than max page count, redirect to main page
        if ($iPageCount > 0 && $iCurrPage > $iPageCount) {
            Router::redirect($sBaseUrlPath);
        }

        // pagecount is greater than 1 and not last page
        if ($iPageCount > 1 && $iCurrPage < $iPageCount) {
            $oPageLayout->sRelNext = $sBaseUrlPath . '?page=' . ($iCurrPage + 1);
        }

        // pagecount is greater than 1 and not first page
        if ($iPageCount > 1 && $iCurrPage > 1) {
            $oPageLayout->sRelPrev = $sBaseUrlPath;
            // is second page, previous is url without page
            if (($iCurrPage - 1) > 1) {
                $oPageLayout->sRelPrev .= '?page=' . ($iCurrPage - 1);
            }
        }

        // always set canonical to first page
        $oPageLayout->sCanonical = $sBaseUrlPath;

        $this->getRenderEngine()
            ->setVariables(
                [
                    'aNewsItems'   => $aNewsItems,
                    'iPageCount'   => $iPageCount,
                    'iCurrPage'    => $iCurrPage,
                    'sArchiveLink' => $this->getArchiveLink(),
                ]
            );
    }

    /**
     * Category Page
     *
     * @param $sCategory
     */
    protected function category($sCategory)
    {
        if (!class_exists('NewsItemCategoryManager')) {
            Router::redirect('/' . Request::getVar('controller'));
        }

        $oPageLayout = $this->getRenderEngine()
            ->getLayout();

        $oNewsItemCategory = NewsItemCategoryManager::getNewsItemCategoryByUrlPart($sCategory, Locales::language());
        if (!$oNewsItemCategory || !$oNewsItemCategory->isOnline()) {
            Router::redirect('/' . Request::getVar('controller'));
        }

        $iPerPage = 6;
        $this->newsOverview($iPerPage, $oNewsItemCategory->getBaseUrlPath(), $oNewsItemCategory->newsItemCategoryId);

        $aNewsItemCategoriesForMenu = [];
        if (class_exists('NewsItemCategoryManager')) {
            $aNewsItemCategoriesForMenu = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['languageId' => Locales::language()]);
        }

        $oPageLayout->sWindowTitle     = $oNewsItemCategory->getWindowTitle();
        $oPageLayout->sMetaDescription = $oNewsItemCategory->getMetaDescription();
        $oPageLayout->sMetaKeywords    = $oNewsItemCategory->getMetaKeywords();
        $oPageLayout->generateCustomCrumblePath($oNewsItemCategory->getCrumbles());
        $oPageLayout->sViewPath = getSiteView('newsItemCategory_overview', 'newsItems');

        $this->getRenderEngine()
            ->setVariables(
                [
                    'sArchiveLink' => $this->getArchiveLink(), 'oNewsItemCategory' => $oNewsItemCategory,
                    'aNewsItemCategoriesForMenu' => $aNewsItemCategoriesForMenu
                ]
            );
    }

    /**
     * Get Archive URL
     *
     * @return null|string
     */
    protected function getArchiveLink()
    {
        if (($oPageArchive = PageManager::getPageByName('newsitems_archive'))) {
            $sArchiveLink = $oPageArchive->getBaseUrlPath();
        } else {
            $sArchiveLink = null;
        }

        return $sArchiveLink;
    }
}