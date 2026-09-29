<?php

class BrandboxItem extends Model
{

    public  $brandboxItemId = null;
    public  $languageId     = null;
    public  $link;
    public  $pageId;
    public  $newsItemId;
    public  $line1          = null;
    public  $line2          = null;
    public  $online         = 1;
    public  $name;
    public  $order          = 99999;
    public  $created        = null;
    public  $modified       = null;
    public  $imageId        = null;
    public  $mediaId        = null;
    private $oPage          = null; // association with Page class
    private $oNewsItem      = null; // association with NewsItem class
    private $oImage         = null; // association with Image class
    private $oMedia         = null; // association with Media class

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
    }

    /**
     * get the related Page
     *
     * @return Page
     */
    public function getPage()
    {
        if ($this->oPage === null) {
            $this->oPage = PageManager::getPageById($this->pageId);
        }

        return $this->oPage;
    }

    /**
     * get the related NewsItem
     *
     * @return NewsItem
     */
    public function getNewsItem()
    {
        if ($this->oNewsItem === null) {
            $this->oNewsItem = NewsItemManager::getNewsItemById($this->newsItemId);
        }

        return $this->oNewsItem;
    }

    /**
     * get the object's link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * get the object's title
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * get this object's Image
     *
     * @return Image $this->oImage
     */
    public function getImage()
    {
        if ($this->oImage === null) {
            $this->oImage = ImageManager::getImageById($this->imageId);
        }

        return $this->oImage;
    }

    /**
     * get this object's Media
     *
     * @return \VimeoLink|\VideoLink|null
     */
    public function getVideoLinks()
    {
        if ($this->oMedia === null) {
            $this->oMedia = VideoLinkManager::getVideoLinkById($this->mediaId);
        }

        return $this->oMedia;
    }

    /**
     * return link location
     *
     * @return mixed
     */
    public function getLinkLocation()
    {
        if ($this->pageId) {
            if ($this->getPage() && $this->getPage()->online) {
                return $this->getPage()
                    ->getBaseUrlPath();
            }
        } elseif ($this->newsItemId) {
            if ($this->getNewsItem() && $this->getNewsItem()
                    ->isOnline()) {
                return $this->getNewsItem()
                    ->getBaseUrlPath();
            }
        } elseif ($this->link) {
            return addHttp($this->link);
        }

        return false;
    }

}
