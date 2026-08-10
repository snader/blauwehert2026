<?php

class Review extends Model
{

    const IMAGES_PATH = '/uploads/images/reviews';

    public  $reviewId;
    public  $localeId;
    public  $importId; // Import id
    public  $title;
    public  $author;
    public  $review;
    public  $link;
    public  $reference;
    public  $rating;
    public  $online  = 0;
    public  $order   = 99999;
    public  $created;
    public  $modified;
    public  $oLocale = null;
    private $aImages = null; // association with Image class

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->localeId)) {
            $this->setPropInvalid('localeId');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
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
        return $this->title;
    }

    /**
     * get all images by specific list name for a review
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
                    $this->aImages[$sList] = ReviewManager::getImagesByFilter($this->reviewId);
                    break;
                case 'first-online':
                    $aImages = ReviewManager::getImagesByFilter($this->reviewId, [], 1);
                    if (!empty($aImages)) {
                        $oImage = $aImages[0];
                    } else {
                        $oImage = null;
                    }
                    $this->aImages[$sList] = $oImage;
                    break;
                case 'all':
                    $this->aImages[$sList] = ReviewManager::getImagesByFilter($this->reviewId, ['showAll' => true]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aImages[$sList];
    }

    /**
     * get the latest Locale related to this object
     *
     * @return ACMS\Locale
     */
    public function getLocale()
    {
        if ($this->oLocale === null) {
            $this->oLocale = LocaleManager::getLocaleById($this->localeId);
        }

        return $this->oLocale;
    }

}

?>