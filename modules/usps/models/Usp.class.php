<?php

class Usp extends Model
{

    const FILES_PATH  = '/uploads/files/usps';
    const IMAGES_PATH = '/uploads/images/usps';

    public  $uspId      = null;
    public  $languageId = null;
    public  $pageId     = null;
    public  $textLine;
    public  $name;
    public  $link;
    public  $online     = 1;
    public  $order      = 99999;
    public  $created    = null;
    public  $modified   = null;
    public  $fileId     = null;
    public  $imageId    = null;
    private $oFile      = null; // association with File class
    private $oImage     = null; // association with Image class

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
     * get this object's File
     *
     * @param bool $bSHowAll
     *
     * @return \File|null
     */
    public function getFile($bSHowAll = true)
    {
        if ($this->oFile === null) {
            $this->oFile = FileManager::getFileById($this->fileId, $bSHowAll);
        }

        return $this->oFile;
    }

    /**
     * get this object's Image
     *
     * @param bool $bShowAll
     *
     * @return \Image|null
     */
    public function getImage($bShowAll = true)
    {
        if ($this->oImage === null) {
            $this->oImage = ImageManager::getImageById($this->imageId, $bShowAll);
        }

        return $this->oImage;
    }
}
