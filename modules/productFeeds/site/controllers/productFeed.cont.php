<?php

# Product-feed xml
if (http_get('param1') == 'google') {

    $aProducts = CatalogProductManager::getProductsByFilter();

    # Include the template
    include_once getSiteView('xml-product-feed-google', 'productFeeds');
} else {
    showHttpError('404');
}