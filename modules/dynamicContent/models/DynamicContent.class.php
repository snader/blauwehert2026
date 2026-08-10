<?php

class DynamicContent extends Model
{

    const TYPE_TEXT = "text";
    const TYPE_CODE = "code";
    const TYPE_HTML = "html";

    public $dynamicContentId = null;
    public $languageId       = null;
    public $name;
    public $content          = null;
    public $online           = 1;
    public $type;
    public $adminOnly        = 0;
    public $created          = null;
    public $modified         = null;

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
        if (DynamicContentManager::nameExists($this->name, $this->dynamicContentId, $this->languageId)) {
            $this->setPropInvalid('nameExists');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
        if (!is_numeric($this->adminOnly)) {
            $this->setPropInvalid('adminOnly');
        }
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
     * get the object's content
     *
     * @return string
     */
    public function getContent()
    {

        switch ($this->type) {
            case dynamicContent::TYPE_TEXT:
                $sContent = _e($this->content);
                break;
            default:
                $sContent = $this->content;
                break;
        }

        return $sContent;
    }

    /**
     *
     */
    public function write()
    {
        include getSiteSnippet('dynamicContent_' . $this->type, 'dynamicContent');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getContent();
    }
}
