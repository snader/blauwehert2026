Dynamic Content module
Current version: v1.0.2
Created by: Sander Voorn

=========================

Module to create dynamic content records for variable use in the front- and/or backend.
Each record can be edited by Admin; other users can edit if needed and put a record online/offline.

There are three types of Dynamice content: text (plain text), html and code (can contain scripts and custom html). Each type has his own snippet and class.

Below an example for implementation:

=========================

Controller example:

# Get dynamic content items
if (moduleExists('dynamicContent')) {
    $oDynamicContent = DynamicContentManager::getDynamicContentByName("record-name", Locales::language()); 
    // add stylesheet if needed   
    $oPageLayout->addStylesheet(getSiteCss('dynamicContent.min', 'dynamicContent')); 
}


View example:

# Show dynamic content record
if (isset($oDynamicContent)) { $oDynamicContent->write(); }
