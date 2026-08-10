<?php

class FaqItemController extends PageController
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
     * Faq Index
     *
     * @param null $sRequestURL
     *
     * @return string|void
     */
    public function index($sRequestURL = null)
    {
        parent::index($sRequestURL);

        $oPage = $this->oPage = $this->getPage($sRequestURL);

        # Check if Page exists or is online
        if (empty($oPage) || !$oPage->isOnline()) {
            return Router::httpError('404');
        }

        $sJson = self::getJSON();
        $this->getRenderEngine()
            ->getLayout()
            ->sStructuredData = $sJson;
        $this->getRenderEngine()
            ->setVariables(['aFAQCategories' => FAQItemCategoryManager::getFAQItemCategoriesByFilter(['languageId' => Locales::language()])]);
        $this->getRenderEngine()
            ->getLayout()->sViewPath = getSiteView('faq_page_details', 'faq');
        $this->getRenderEngine()
            ->getLayout()
            ->generateCustomCrumblePath($this->oPage->getCrumbles());
    }

    /**
     * Make and set the JSON array for Google.
     * More info: https://developers.google.com/search/docs/data-types/faqpage
     */
    private function getJSON(){
        $aJson = array(
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => array()
        );
        $sAllowedTags = '<h1><h2><h3><h4><h5><h6><br><ol><ul><li><a><p><div><b><strong><i><em>';
        $aFAQItems = FAQItemManager::getFAQItemsByFilter(['languageId' => Locales::language()],null,0,$iFoundRows,['`ficfi`.`order`' => 'ASC', '`fi`.`question`' => 'ASC']);
        foreach($aFAQItems as $oFAQItem){
            if(empty(strip_tags($oFAQItem->answer))){
                continue;
            }
            $aTemp = array(
                "@type" => "Question",
                "name" => strip_tags($oFAQItem->question, $sAllowedTags),
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => strip_tags($oFAQItem->answer, $sAllowedTags),
                )
            );
            $aJson['mainEntity'][] = $aTemp;
        }
        return json_encode($aJson);
    }
}
