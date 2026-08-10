<?php

class NewsItem extends Model
{

    const FILES_PATH = '/uploads/files/news';

    public  $newsItemId;
    public  $languageId      = null;
    public  $windowTitle; //browser window title
    public  $metaKeywords;
    public  $metaDescription;
    public  $title;
    public  $intro;
    public  $content;
    public  $shortTitle; //for special use in f.e. menu's
    public  $date; //date the news appeared
    public  $source;
    public  $onlineFrom; //date time from when the news item is showed online
    public  $onlineTo; //date time to when the news item is showed online
    public  $online          = 1;
    public  $created;
    public  $modified;
    private $aImages         = null; //array with different lists of images
    private $aFiles          = null; //array with different lists of files
    private $aLinks          = null; //array with different lists of links
    private $aVideoLinks     = null; //array with different lists of video links
    private $aCategories     = null; //array with different lists of newsItem categories
    private $oControllerPage = null; // get page to get controller from to show item with right controller
    private $aLocales        = null; // locales for language
    private $aPages          = null; // connected pages
    private $aWhitePapers    = null; // connected pages

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
        if (empty($this->date)) {
            $this->setPropInvalid('date');
        }
        if (empty($this->onlineFrom)) {
            $this->setPropInvalid('onlineFrom');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }

        // check or at least 1 category is set
        if (class_exists('NewsItemCategoryManager')) {
            if (count($this->getCategories('all')) == 0) {
                $this->setPropInvalid('categories');
            }
        }
    }

    /**
     * check if news item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * check if news item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return true;
    }

    /**
     * check if news item is online (except with preview mode)
     *
     * @param bool $bPreviewMode
     *
     * @return bool
     */
    public function isOnline($bPreviewMode = false)
    {

        $oDateFrom = new Date($this->onlineFrom);
        $oDateTo   = new Date($this->onlineTo);
        $oDateNow  = new Date('NOW');

        $bOnline = true;
        if (!$bPreviewMode) {
            if (!($this->online && (($oDateFrom->lowerEqualTo($oDateNow) && $this->onlineFrom === null) || ($oDateFrom->lowerEqualTo($oDateNow) && $oDateNow->lowerEqualTo($oDateTo))))) {
                $bOnline = false;
            }
        }

        if (!$bPreviewMode) {
            // with categories so check if at least 1 category is online
            if (class_exists('NewsItemCategoryManager') && count($this->getCategories()) <= 0) {
                $bOnline = false;
            }
        }

        return $bOnline;
    }

    /**
     * get page to get controller part in path
     *
     * @param ACMS\Locale $oLocale
     *
     * @return Page
     */
    private function getControllerPage()
    {
        if ($this->oControllerPage === null) {
            $this->oControllerPage = PageManager::getPageByName('newsitems', $this->languageId);
        }

        return $this->oControllerPage;
    }

    /**
     * get Locales of this page
     *
     * @return $aLocales
     */
    public function getLocales()
    {
        if ($this->aLocales === null) {
            $this->aLocales = LocaleManager::getLocalesByFilter(['languageId' => $this->languageId]);
        }

        return $this->aLocales;
    }

    /**
     * get url to news item
     *
     * @return string
     */
    public function getUrlPath()
    {
        $sUrlPath = null;
        // get page for controller part
        if (moduleExists('pages') && $this->getControllerPage()) {
            $sUrlPath = $this->getControllerPage()
                    ->getUrlPath() . '/' . $this->newsItemId . '/' . prettyUrlPart($this->title);
        }

        return $sUrlPath;
    }

    /**
     * get url to page with base url
     *
     * @return string
     */
    public function getBaseUrlPath()
    {
        return getBaseUrl() . $this->getUrlPath();
    }

    /**
     * return the window title if there is one, otherwise return title
     *
     * @return string
     */
    public function getWindowTitle()
    {
        return $this->windowTitle ? $this->windowTitle : $this->title;
    }

    /**
     * return meta description if exists or a
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription ? $this->metaDescription : generateMetaDescription($this->content);
    }

    /**
     * return meta keywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * return short title but fall back on title if short does not exists
     *
     * @return string
     */
    public function getShortTitle()
    {
        return $this->shortTitle ? $this->shortTitle : $this->title;
    }

    /**
     *
     * get an array of the breadcrumbs
     *
     * @param type $iNewsItemCategoryId
     *
     * @return array
     */
    public function getCrumbles($iNewsItemCategoryId = null)
    {
        $aCrumbles = [];
        if ($this->getControllerPage()) {
            $aCrumbles[$this->getControllerPage()
                ->getShortTitle()] = $this->getControllerPage()
                ->getBaseUrlPath();
            if ($iNewsItemCategoryId && ($oNewsItemCategory = NewsItemCategoryManager::getNewsItemCategoryById($iNewsItemCategoryId))) {
                $aCrumbles[$oNewsItemCategory->name] = $oNewsItemCategory->getBaseUrlPath();
            }
            $aCrumbles[$this->getShortTitle()] = $this->getBaseUrlPath();
        }

        return $aCrumbles;
    }

    /**
     * get all images by specific list name for a newsItem
     *
     * @param string $sList
     *
     * @return Image or array Images
     */
    public function getImages($sList = 'online')
    {
        if (!isset($this->aImages[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aImages[$sList] = NewsItemManager::getImagesByFilter($this->newsItemId);
                    break;
                case 'first-online':
                    $aImages = NewsItemManager::getImagesByFilter($this->newsItemId, [], 1);
                    if (!empty($aImages)) {
                        $oImage = $aImages[0];
                    } else {
                        $oImage = null;
                    }
                    $this->aImages[$sList] = $oImage;
                    break;
                case 'all':
                    $this->aImages[$sList] = NewsItemManager::getImagesByFilter($this->newsItemId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aImages[$sList];
    }

    /**
     * get all files by specific list name for a newsItem
     *
     * @param string $sList
     *
     * @return array File
     */
    public function getFiles($sList = 'online')
    {
        if (!isset($this->aFiles[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aFiles[$sList] = NewsItemManager::getFilesByFilter($this->newsItemId);
                    break;
                case 'all':
                    $this->aFiles[$sList] = NewsItemManager::getFilesByFilter($this->newsItemId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aFiles[$sList];
    }

    /**
     * get all links by specific list name for a newsItem
     *
     * @param string $sList
     *
     * @return array Link
     */
    public function getLinks($sList = 'online')
    {
        if (!isset($this->aLinks[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aLinks[$sList] = NewsItemManager::getLinksByFilter($this->newsItemId);
                    break;
                case 'all':
                    $this->aLinks[$sList] = NewsItemManager::getLinksByFilter($this->newsItemId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aLinks[$sList];
    }

    /**
     * get all video links by specific list name for a newsItem
     *
     * @param string $sList
     *
     * @return array VideoLink
     */
    public function getVideoLinks($sList = 'online')
    {
        if (!isset($this->aVideoLinks[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aVideoLinks[$sList] = NewsItemManager::getVideoLinksByFilter($this->newsItemId);
                    break;
                case 'all':
                    $this->aVideoLinks[$sList] = NewsItemManager::getVideoLinksByFilter($this->newsItemId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aVideoLinks[$sList];
    }

    /**
     * get all news item categories by specific list name for a newsItem
     *
     * @param string $sList
     *
     * @return array NewsItemCategory
     */
    public function getCategories($sList = 'online')
    {
        if (!isset($this->aCategories[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aCategories[$sList] = NewsItemManager::getCategoriesByFilter($this->newsItemId);
                    break;
                case 'all':
                    $this->aCategories[$sList] = NewsItemManager::getCategoriesByFilter($this->newsItemId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aCategories[$sList];
    }

    /**
     * set categories
     *
     * @param array  $aNewsItemCategories
     * @param string $sList (set in specific list)
     */
    public function setCategories(array $aNewsItemCategories, $sList = 'online')
    {
        $this->aCategories[$sList] = $aNewsItemCategories;
    }

    /**
     * get product information in Stuctured Data JSON-LD
     *
     * @return string in JSON-LD format
     */
    public function getStucturedData()
    {

        # check https://developers.google.com/search/docs/guides/intro-structured-data for more information

        $aMainEntityData = [
            "@type" => "WebPage",
            "@id"   => $this->getBaseUrlPath(),
        ];

        // if the logo is not represented by this icon, please replace it with the correct logo and size.
        $aLogoData = [
            "@type"  => "ImageObject",
            "url"    => CLIENT_HTTP_URL . getSiteImage('icons/android/android-chrome-192x192.png'),
            "width"  => "192",
            "height" => "192",
        ];

        // get image
        $oImage = $this->getImages("first-online");
        if (!empty($oImage)) {
            $oImageFileCrop = $oImage->getImageFileByReference('crop_small');
        }
        if (!empty($oImageFileCrop)) {
            $aImageData = [
                "@type"  => "ImageObject",
                "url"    => CLIENT_HTTP_URL . $oImageFileCrop->link,
                "height" => $oImageFileCrop->getHeight(),
                "width"  => $oImageFileCrop->getWidth(),
            ];
        } else {
            // fallback to logo, image is required.
            $aImageData = $aLogoData;
        }

        $aAuthorData = [
            "@type" => "Website",
            // or Person
            "name"  => CLIENT_URL . (!empty($this->source) ? ', source: ' . $this->source : ''),
        ];

        $aPublisherData = [
            "@type" => "Organization",
            "name"  => CLIENT_NAME,
            "logo"  => $aLogoData,
        ];

        $sDatePublished = date(DATE_ISO8601, strtotime($this->onlineFrom));
        if (!empty($this->modified) && strtotime($this->modified) > strtotime($this->onlineFrom)) {
            $sDateModified = date(DATE_ISO8601, strtotime($this->modified));
        } else {
            $sDateModified = $sDatePublished;
        }

        $aData = [
            "@context"         => "http://schema.org",
            "@type"            => "NewsArticle",
            "mainEntityOfPage" => $aMainEntityData,
            "headline"         => _e($this->title),
            "image"            => $aImageData,
            "datePublished"    => $sDatePublished,
            "dateModified"     => $sDateModified,
            "author"           => $aAuthorData,
            "publisher"        => $aPublisherData,
            "description"      => $this->getMetaDescription(),

        ];

        return json_encode($aData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /*
     * Get language
     * @return Language
     */
    public function getLanguage()
    {
        return LanguageManager::getLanguageById($this->languageId);
    }

    public function getPages(string $sList = 'online')
    {
        if (moduleExists('pages')) {
            if (!isset($this->aPages[$sList])) {
                switch ($sList) {
                    case 'online':
                        $this->aPages[$sList] = PageManager::getPagesByFilter(['newsItemId' => $this->newsItemId]);
                        break;
                    case 'all':
                        $this->aPages[$sList] = PageManager::getPagesByFilter(
                            [
                                'newsItemId' => $this->newsItemId,
                                'showAll'    => true,
                            ]
                        );
                        break;
                    default:
                        die('no option');
                        break;
                }
            }

            return $this->aPages[$sList];
        }

        return [];
    }

    /**
     * Get all connected Whitepapers
     *
     * @param string $sList
     *
     * @return array
     */
    public function getWhitePapers(string $sList = 'online'): array
    {
        if (!isset($this->aWhitePapers[$sList])) {
            switch ($sList) {
                case 'online':
                    $this->aWhitePapers[$sList] = WhitePaperManager::getWhitePapersByFilter(['whitePaperNewsItemId' => $this->newsItemId]);
                    break;
                case 'all':
                    $this->aWhitePapers[$sList] = WhitePaperManager::getWhitePapersByFilter(
                        [
                            'whitePaperNewsItemId' => $this->newsItemId,
                            'showAll'    => true,
                        ]
                    );
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aWhitePapers[$sList];
    }
}
