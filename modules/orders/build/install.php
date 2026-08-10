<?php

// Database checks

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'bestellingen'    => [
        'module'     => 'orders',
        'controller' => 'order',
    ],
    'verzendmethoden' => [
        'module'     => 'orders',
        'controller' => 'deliveryMethod',
    ],
    'betaalmethoden'  => [
        'module'     => 'orders',
        'controller' => 'paymentMethod',
    ],
];

$aNeededClassRoutes = [
    'DeliveryMethod'                   => [
        'module' => 'orders',
    ],
    'DeliveryMethodManager'            => [
        'module' => 'orders',
    ],
    'DeliveryMethodTranslation'        => [
        'module' => 'orders',
    ],
    'DeliveryMethodTranslationManager' => [
        'module' => 'orders',
    ],
    'Order'                            => [
        'module' => 'orders',
    ],
    'OrderManager'                     => [
        'module' => 'orders',
    ],
    'OrderPayment'                     => [
        'module' => 'orders',
    ],
    'OrderPaymentManager'              => [
        'module' => 'orders',
    ],
    'OrderProduct'                     => [
        'module' => 'orders',
    ],
    'OrderProductManager'              => [
        'module' => 'orders',
    ],
    'OrderStatus'                      => [
        'module' => 'orders',
    ],
    'OrderStatusManager'               => [
        'module' => 'orders',
    ],
    'PaymentMethod'                    => [
        'module' => 'orders',
    ],
    'PaymentMethodManager'             => [
        'module' => 'orders',
    ],
    'PaymentMethodTranslation'         => [
        'module' => 'orders',
    ],
    'PaymentMethodTranslationManager'  => [
        'module' => 'orders',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededModulesForMenu = [
    [
        'name'          => 'bestellingen',
        'icon'          => 'fa-check-square-o',
        'linkName'      => 'orders_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'orders_full'],
        ],
    ],
    [
        'name'             => 'verzendmethoden',
        'icon'             => 'fa-truck',
        'linkName'         => 'order_delivery_methods_menu',
        'parentModuleName' => 'bestellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'orderDeliveryMethods_full'],
        ],
    ],
    [
        'name'             => 'betaalmethoden',
        'icon'             => 'fa-credit-card',
        'linkName'         => 'order_payment_methods_menu',
        'parentModuleName' => 'bestellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'orderPaymentMethods_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'orders_orders', 'text' => 'Bestellingen'],
        ['label' => 'orders_menu', 'text' => 'Bestellingen'],
        ['label' => 'order_payment_methods_menu', 'text' => 'Betaalmethoden'],
        ['label' => 'order_delivery_methods_menu', 'text' => 'Verzendmethoden'],
        ['label' => 'order_address', 'text' => 'Adres'],
        ['label' => 'order_order_name', 'text' => 'Naam'],
        ['label' => 'order_delivery_method_drag', 'text' => 'Sleep de namen om de volgorde te veranderen'],
        ['label' => 'order_download_pdf', 'text' => 'Download PDF'],
        ['label' => 'order_order', 'text' => 'Bestelling'],
        ['label' => 'order_filter_orders', 'text' => 'Filter bestellingen'],
        ['label' => 'order_delivery_method_change_order', 'text' => 'Verzendmethoden volgorde wijzigen'],
        ['label' => 'order_payment_method_choose', 'text' => 'Kies tenminste één betaalwijze'],
        ['label' => 'order_delivery_method_payments_accepted', 'text' => 'Geaccepteerde betaalmethoden voor deze verzendwijze'],
        [
            'label' => 'order_delivery_method_price_tooltip',
            'text'  => 'Vul hier de verzendkosten in. De verzendkosten zijn altijd incl. BTW omdat<br />de BTW naar rato wordt verdeeld op basis van de BTW-percentages van de gekochte producten.',
        ],
        ['label' => 'order_delivery_method_back_overview', 'text' => 'Terug naar het overzicht'],
        ['label' => 'order_delivery_method_not_deleted', 'text' => 'Verzendmethode kan niet worden verwijderd'],
        ['label' => 'order_delivery_method_deleted', 'text' => 'Verzendmethode is verwijderd'],
        ['label' => 'order_delivery_method_not_saved', 'text' => 'Verzendmethode is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'order_delivery_method_saved', 'text' => 'Verzendmethode is opgeslagen'],
        ['label' => 'order_delivery_methods', 'text' => 'Verzendmethoden'],
        ['label' => 'order_not_saved', 'text' => 'Bestelling kan niet worden opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'order_saved', 'text' => 'Bestelling opgeslagen'],
        ['label' => 'order_payment_method_not_deleted', 'text' => 'Betaalmethode kan niet worden werwijderd'],
        ['label' => 'order_payment_method_deleted', 'text' => 'Betaalmethode is verwijderd'],
        ['label' => 'order_payment_method_not_saved', 'text' => 'Betaalmethode is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'order_payment_method_saved', 'text' => 'Betaalmethode is opgeslagen'],
        ['label' => 'order_no_delivery_method', 'text' => 'Er zijn geen verzendmethoden om weer te geven'],
        ['label' => 'order_delivery_method_no_delete', 'text' => 'Verzendmethode kan niet verwijderd worden'],
        ['label' => 'order_delivery_method_delete', 'text' => 'Verwijder verzendmethode'],
        ['label' => 'order_delivery_method_no_edit', 'text' => 'Verzendmethode kan niet bewerkt worden'],
        ['label' => 'order_delivery_method_edit', 'text' => 'Bewerk verzendmethode'],
        ['label' => 'order_delivery_method_payments', 'text' => 'Betaalwijzen'],
        ['label' => 'order_delivery_method_time', 'text' => 'Levertijd'],
        ['label' => 'order_delivery_method_gratis', 'text' => 'Gratis vanaf'],
        ['label' => 'order_delivery_method_add', 'text' => 'Verzendmethode toevoegen'],
        ['label' => 'order_delivery_method_all', 'text' => 'Alle verzendmethoden'],
        ['label' => 'order_payment_method_back_overview', 'text' => 'Terug naar het overzicht'],
        ['label' => 'order_payment_method_url_tooltip', 'text' => 'Vul de url in waar de gebruiker na toe gaat na het bevestigen van de bestelling'],
        ['label' => 'order_payment_method_url', 'text' => 'Betaalmethode-url'],
        ['label' => 'order_payment_method_is_online', 'text' => 'Is online betaalmethode'],
        ['label' => 'order_payment_method_costs', 'text' => 'Vul hier de betaalkosten in. De betaalkosten zijn altijd incl. BTW omdat<br />de BTW naar rato wordt verdeeld op basis van de BTW percentages van de gekochte producten.'],
        ['label' => 'order_payment_method_drag', 'text' => 'Sleep de regels om de volgorde aan te passen'],
        ['label' => 'order_payment_method_change_order', 'text' => 'Betaalmethoden volgorde wijzigen'],
        ['label' => 'order_no_payment_method', 'text' => 'Er zijn geen betaalmethoden om weer te geven'],
        ['label' => 'order_payment_method_no_delete', 'text' => 'Betaalmethode kan niet verwijderd worden'],
        ['label' => 'order_payment_method_delete', 'text' => 'Verwijder betaalmethode'],
        ['label' => 'order_payment_method_no_edit', 'text' => 'Betaalmethode kan niet bewerkt worden'],
        ['label' => 'order_payment_method_edit', 'text' => 'Bewerk betaalmethode'],
        ['label' => 'order_page_after_payment', 'text' => 'Pagina na bestellen'],
        ['label' => 'order_payment_method_online', 'text' => 'Online methode'],
        ['label' => 'order_payment_method_save', 'text' => 'Betaalmethode toevoegen'],
        ['label' => 'order_all_payment_method', 'text' => 'Alle betaalmethoden'],
        ['label' => 'order_no_online_payments', 'text' => 'Er zijn geen online betalingen voor deze bestelling.'],
        ['label' => 'order_external_status', 'text' => 'Status extern'],
        ['label' => 'order_last_update', 'text' => 'Laatste update'],
        ['label' => 'order_started_payment', 'text' => 'Betaling gestart'],
        ['label' => 'order_online_payments', 'text' => 'Online betalingen'],
        ['label' => 'order_status_history', 'text' => 'Statusgeschiedenis'],
        ['label' => 'order_save_status', 'text' => 'Status bewerken'],
        ['label' => 'order_status_commentary_tooltip', 'text' => 'Vul een eventuele opmerking om mee te sturen met de statusnotificatie'],
        ['label' => 'order_status_commentary', 'text' => 'Eventuele opmerking over status'],
        ['label' => 'order_send_notification', 'text' => 'stuur notificatie'],
        ['label' => 'order_notification_tooltip', 'text' => 'Vink deze optie aan om de klant een e-mail te sturen over de status wijziging'],
        ['label' => 'order_notification', 'text' => 'Notificatie'],
        ['label' => 'order_choose_status', 'text' => 'Kies een status'],
        [
            'label' => 'order_status_select_tooltip',
            'text'  => 'Wijzig hier de status om de statusgeschiedenis bij te werken<br />- `betaald`: voorraad wordt afgeboekt als dat nog niet gedaan is<br />- `geannuleerd`: voorraad wordt bijgeboekt als dat nog niet gedaan is',
        ],
        ['label' => 'order_order_status', 'text' => 'Bestellingstatus'],
        ['label' => 'order_see_original', 'text' => 'Bekijk het originele product'],
        [
            'label' => 'order_cancelled_tooltip',
            'text'  => 'Aantal producten dat al afgeboekt is van de voorraad.<br />- wordt automatisch gedaan als de bestelling de status `betaald` krijgt<br />- status `geanuleerd` boekt de voorraad automatisch bij',
        ],
        ['label' => 'order_cancelled', 'text' => 'Afgeboekt'],
        ['label' => 'order_house_number_tooltip', 'text' => 'Vul een geldig huisnummer in (alleen nummers)'],
        ['label' => 'order_google_maps', 'text' => 'Klik hier om de locatie te bekijken in Google Maps'],
        ['label' => 'order_location', 'text' => 'Locatie'],
        ['label' => 'order_delivery_info', 'text' => 'Aflevergegevens'],
        ['label' => 'order_invoice_info', 'text' => 'Factuurgegevens'],
        ['label' => 'order_back_overview', 'text' => 'Terug naar het bestellingen overzicht'],
        ['label' => 'order_unit_price', 'text' => 'Prijs per stuk'],
        ['label' => 'order_product_name', 'text' => 'Artikelnaam'],
        ['label' => 'order_invoice_amount', 'text' => 'Factuurbedrag'],
        ['label' => 'order_delivery_cost', 'text' => 'Verzendkosten'],
        ['label' => 'order_delivery_method', 'text' => 'Aflevermethode'],
        ['label' => 'order_payment_status', 'text' => 'Betaalstatus'],
        ['label' => 'order_city', 'text' => 'Plaats'],
        ['label' => 'order_house_number', 'text' => 'Huisnummer'],
        ['label' => 'order_street', 'text' => 'Straat'],
        ['label' => 'order_delivery_address', 'text' => 'Bezorgadres'],
        ['label' => 'order_invoice_address', 'text' => 'Factuuradres'],
        ['label' => 'order_no_orders', 'text' => 'Er zijn geen bestellingen om weer te geven met dit filter'],
        ['label' => 'order_edit_order', 'text' => 'Bewerk bestelling'],
        ['label' => 'order_payment_method', 'text' => 'Betaalmethode'],
        ['label' => 'order_updated', 'text' => 'Bijgewerkt'],
        ['label' => 'order_created', 'text' => 'Aangemaakt'],
        ['label' => 'order_export', 'text' => 'Exporteer bestellingen'],
        ['label' => 'order_all_orders', 'text' => 'Alle bestellingen'],
        ['label' => 'order_status_tooltip', 'text' => 'Zoek op een of meer statussen van bestellingen<br />- houd de Ctrl-toets ingedrukt om meerdere statussen aan te kunnen klikken'],
        ['label' => 'order_order_number_tooltip', 'text' => 'Zoek op het ordernummer van een bestelling'],
        ['label' => 'order_order_number', 'text' => 'Ordernummer'],
        ['label' => 'settings_pdf_order_logo', 'text' => 'Order PDF-logo'],
        ['label' => 'settings_defaultEmailOrders', 'text' => 'Standaard bestellingenadres'],
        ['label' => 'settings_defaultEmailOrders_tooltip', 'text' => 'Vul het standaard bestellingenadres in'],
        ['label' => 'global_system', 'text' => 'Systeem'],
        ['label' => 'global_created_by', 'text' => 'Gewijzigd door'],
    ],
    'es' => [
        ['label' => 'orders_orders', 'text' => 'Bestellingen'],
        ['label' => 'orders_menu', 'text' => 'Pedidos'],
        ['label' => 'order_delivery_method_drag', 'text' => 'Arrastre los nombres para cambiar el orden'],
        ['label' => 'order_download_pdf', 'text' => 'Descargar PDF'],
        ['label' => 'order_order', 'text' => 'Pedido'],
        ['label' => 'order_filter_orders', 'text' => 'Filtrar pedidos'],
        ['label' => 'order_delivery_method_change_order', 'text' => 'Cambiar el orden de los métodos de envío'],
        ['label' => 'order_payment_method_choose', 'text' => 'Elija por lo menos una forma de pago'],
        ['label' => 'order_delivery_method_payments_accepted', 'text' => 'Seleccionar formas de pago para este método de envío'],
        ['label' => 'order_delivery_method_price_tooltip', 'text' => 'Por favor, ingrese el coste del envío. Los gastos de envío son siempre con el IVA incluido.'],
        ['label' => 'order_delivery_method_back_overview', 'text' => 'Volver a la descripción de los métodos del envío'],
        ['label' => 'order_delivery_method_not_deleted', 'text' => 'El método del envío ha sido eliminado'],
        ['label' => 'order_delivery_method_deleted', 'text' => 'El método de envío se ha eliminado'],
        ['label' => 'order_delivery_method_not_saved', 'text' => 'Método de envío no se ha guardado, no todos los campos están (correctamente) completados'],
        ['label' => 'order_delivery_method_saved', 'text' => 'El método del envío ha sido guardado'],
        ['label' => 'order_delivery_methods', 'text' => 'Métodos de envío'],
        ['label' => 'order_not_saved', 'text' => 'No se puede guardar el pedido, no todos los campos están (correctamente) completados'],
        ['label' => 'order_saved', 'text' => 'Pedido guardado'],
        ['label' => 'order_payment_method_not_deleted', 'text' => 'La forma de pago no puede se puede editar'],
        ['label' => 'order_payment_method_deleted', 'text' => 'Forma de pago eliminado'],
        ['label' => 'order_payment_method_not_saved', 'text' => 'La forma de pago no se guarda, no todos los campos están (correctamente) completados'],
        ['label' => 'order_payment_method_saved', 'text' => 'Forma de pago guardada'],
        ['label' => 'order_no_delivery_method', 'text' => 'No existen envíos métodos para mostrar'],
        ['label' => 'order_delivery_method_no_delete', 'text' => 'El método de envío no se puede eliminar'],
        ['label' => 'order_delivery_method_delete', 'text' => 'Eliminar el método de envío'],
        ['label' => 'order_delivery_method_no_edit', 'text' => 'El método de envío no se puedan editar'],
        ['label' => 'order_delivery_method_edit', 'text' => 'Editar el método de envío'],
        ['label' => 'order_delivery_method_payments', 'text' => 'Métodos de pago'],
        ['label' => 'order_delivery_method_time', 'text' => 'Tiempo de entrega'],
        ['label' => 'order_delivery_method_gratis', 'text' => 'Gratis a partir de'],
        ['label' => 'order_delivery_method_add', 'text' => 'Añada el método de envío'],
        ['label' => 'order_delivery_method_all', 'text' => 'Todos los métodos de envío'],
        ['label' => 'order_payment_method_back_overview', 'text' => 'Volver a la descripción de los métodos de pago'],
        ['label' => 'order_payment_method_url_tooltip', 'text' => 'Introduzca la url donde el usuario irá después de confirmar el pedido'],
        ['label' => 'order_payment_method_url', 'text' => 'Url de la forma de pago'],
        ['label' => 'order_payment_method_is_online', 'text' => 'La forma de pago está activada'],
        [
            'label' => 'order_payment_method_costs',
            'text'  => 'Ingrese el importe cobrado por esta forma de pago.<br/>  El importe es siempre con IVA ya que el IVA después de que se divide la relación basada en las tasas de impuestos de los productos comprados.',
        ],
        ['label' => 'order_payment_method_drag', 'text' => 'Arrastre los métodos de pago para cambiar el orden'],
        ['label' => 'order_payment_method_change_order', 'text' => 'Cambiar el orden de los métodos de pago'],
        ['label' => 'order_no_payment_method', 'text' => 'No existen métodos de pago para mostrar'],
        ['label' => 'order_payment_method_no_delete', 'text' => 'La forma de pago no se puede eliminar'],
        ['label' => 'order_payment_method_delete', 'text' => 'Eliminar la forma de pago'],
        ['label' => 'order_payment_method_no_edit', 'text' => 'No se puede modificar la forma de pago'],
        ['label' => 'order_payment_method_edit', 'text' => 'Editar forma de pago'],
        ['label' => 'order_page_after_payment', 'text' => 'Página a la que redireccionar después de realizar el pago'],
        ['label' => 'order_payment_method_online', 'text' => 'Método en línea'],
        ['label' => 'order_payment_method_save', 'text' => 'Añada una forma de pago'],
        ['label' => 'order_all_payment_method', 'text' => 'Todos los métodos de pago'],
        ['label' => 'order_no_online_payments', 'text' => 'No hay ningún pago en línea para este pedido.'],
        ['label' => 'order_external_status', 'text' => 'Estado externo'],
        ['label' => 'order_last_update', 'text' => 'Última actualización'],
        ['label' => 'order_started_payment', 'text' => 'Pago iniciado'],
        ['label' => 'order_online_payments', 'text' => 'Pagos en línea'],
        ['label' => 'order_status_history', 'text' => 'Historial de estados del pedido'],
        ['label' => 'order_save_status', 'text' => 'Editar estado'],
        ['label' => 'order_status_commentary_tooltip', 'text' => 'Escribe un comentario que irá junto con la notificación del estado'],
        ['label' => 'order_status_commentary', 'text' => 'Comentarios sobre el estado del pedido'],
        ['label' => 'order_send_notification', 'text' => 'Enviar notificación'],
        ['label' => 'order_notification_tooltip', 'text' => 'Enviar un correo electrónico al cliente sobre el cambio de estado'],
        ['label' => 'order_notification', 'text' => 'Notificación'],
        ['label' => 'order_choose_status', 'text' => 'Elija un estado'],
        ['label' => 'order_status_select_tooltip', 'text' => 'Seleccionar el estado <br/>-\'pagado\': El stock se descuenta al marcar este estado<br/>-\'cancelado\': El stock no se descuenta al marcar este estado'],
        ['label' => 'order_order_status', 'text' => 'Estado del pedido'],
        ['label' => 'order_see_original', 'text' => 'Ver el producto original'],
        [
            'label' => 'order_cancelled_tooltip',
            'text'  => 'El número de productos que se reducen del stock <br/>-Se reducen automáticamente si el pedido llega al estado \'pagado\' <br/>-El estado \'cancelado\' actualiza el stock automáticamente',
        ],
        ['label' => 'order_cancelled', 'text' => 'Pedido cancelado'],
        ['label' => 'order_house_number_tooltip', 'text' => 'Introduzca un número válido utilizando sólo números'],
        ['label' => 'order_google_maps', 'text' => 'Haga clic aquí para ver la ubicación en Google Maps'],
        ['label' => 'order_location', 'text' => 'Ubicación'],
        ['label' => 'order_delivery_info', 'text' => 'Detalles del envío'],
        ['label' => 'order_invoice_info', 'text' => 'Información de facturación'],
        ['label' => 'order_back_overview', 'text' => 'Volver a la descripción de los pedidos'],
        ['label' => 'order_unit_price', 'text' => 'Precio por unidad'],
        ['label' => 'order_product_name', 'text' => 'Nombre del artículo'],
        ['label' => 'order_invoice_amount', 'text' => 'Importe de la factura'],
        ['label' => 'order_delivery_cost', 'text' => 'Gastos de envío'],
        ['label' => 'order_delivery_method', 'text' => 'Método de envío'],
        ['label' => 'order_payment_status', 'text' => 'Estado del pago'],
        ['label' => 'order_city', 'text' => 'Ciudad'],
        ['label' => 'order_house_number', 'text' => 'Número de casa'],
        ['label' => 'order_street', 'text' => 'Calle'],
        ['label' => 'order_delivery_address', 'text' => 'Dirección de entrega'],
        ['label' => 'order_invoice_address', 'text' => 'Dirección de facturación'],
        ['label' => 'order_no_orders', 'text' => 'No hay ninguna orden para mostrar con este filtro'],
        ['label' => 'order_edit_order', 'text' => 'Editar pedido'],
        ['label' => 'order_payment_method', 'text' => 'Forma de pago'],
        ['label' => 'order_updated', 'text' => 'Actualizado'],
        ['label' => 'order_created', 'text' => 'Creado'],
        ['label' => 'order_export', 'text' => 'Exportar listado de pedidos'],
        ['label' => 'order_all_orders', 'text' => 'Todos los pedidos'],
        ['label' => 'order_status_tooltip', 'text' => 'Seleccione uno o más estados <br/> Mantenga pulsado la tecla Ctrl para seleccionar varios estados'],
        ['label' => 'order_order_number_tooltip', 'text' => 'El número de pedido'],
        ['label' => 'order_order_number', 'text' => 'Número de pedido'],
        ['label' => 'settings_pdf_order_logo', 'text' => 'Logo para la factura'],
        ['label' => 'settings_defaultEmailOrders', 'text' => 'Default email orders'],
        ['label' => 'settings_defaultEmailOrders_tooltip', 'text' => 'Fill the default email orders'],
        ['label' => 'global_system', 'text' => 'Sistema'],
        ['label' => 'global_created_by', 'text' => 'Adaptado por'],
    ],
    'en' => [
        ['label' => 'orders_orders', 'text' => 'Bestellingen'],
        ['label' => 'orders_menu', 'text' => 'Orders'],
        ['label' => 'order_delivery_method_drag', 'text' => 'Drag and drop the names to change the order'],
        ['label' => 'order_download_pdf', 'text' => 'Download PDF'],
        ['label' => 'order_order', 'text' => 'Order'],
        ['label' => 'order_filter_orders', 'text' => 'Filter orders'],
        ['label' => 'order_delivery_method_change_order', 'text' => 'Change delivery methods order'],
        ['label' => 'order_payment_method_choose', 'text' => 'Choose at least 1 payment method'],
        ['label' => 'order_delivery_method_payments_accepted', 'text' => 'Accepted payment methods for this delivery method'],
        ['label' => 'order_delivery_method_price_tooltip', 'text' => 'Please enter the delivery cost. VAT are always included for the delivery cost.<br/>'],
        ['label' => 'order_delivery_method_back_overview', 'text' => 'Back to the delivery methods overview'],
        ['label' => 'order_delivery_method_not_deleted', 'text' => 'Delivery method cannot be deleted'],
        ['label' => 'order_delivery_method_deleted', 'text' => 'Delivery method deleted'],
        ['label' => 'order_delivery_method_not_saved', 'text' => 'Delivery method is not saved, not all fields are (correctly) filled in'],
        ['label' => 'order_delivery_method_saved', 'text' => 'Delivery method is saved'],
        ['label' => 'order_delivery_methods', 'text' => 'Delivery Methods'],
        ['label' => 'order_not_saved', 'text' => 'Order cannot be saved, not all fields are (corrrectly) filled in'],
        ['label' => 'order_saved', 'text' => 'Order saved'],
        ['label' => 'order_payment_method_not_deleted', 'text' => 'Payment method not deleted'],
        ['label' => 'order_payment_method_deleted', 'text' => 'Payment method has been deleted'],
        ['label' => 'order_payment_method_not_saved', 'text' => 'Payment method is not saved, not all fields are filled in (right)'],
        ['label' => 'order_payment_method_saved', 'text' => 'Payment method is stored'],
        ['label' => 'order_no_delivery_method', 'text' => 'There are not delivery methods to display'],
        ['label' => 'order_delivery_method_no_delete', 'text' => 'Delivery method cannot be removed'],
        ['label' => 'order_delivery_method_delete', 'text' => 'Delete delivery method'],
        ['label' => 'order_delivery_method_no_edit', 'text' => 'Delivery method cannot be edited'],
        ['label' => 'order_delivery_method_edit', 'text' => 'Edit delivery method'],
        ['label' => 'order_delivery_method_payments', 'text' => 'Payment Methods'],
        ['label' => 'order_delivery_method_time', 'text' => 'Delivery Time'],
        ['label' => 'order_delivery_method_gratis', 'text' => 'Free from'],
        ['label' => 'order_delivery_method_add', 'text' => 'Add delivery method'],
        ['label' => 'order_delivery_method_all', 'text' => 'All delivery methods'],
        ['label' => 'order_payment_method_back_overview', 'text' => 'Back to the payment methods overview'],
        ['label' => 'order_payment_method_url_tooltip', 'text' => 'Enter the url where the user after going after confirming the order'],
        ['label' => 'order_payment_method_url', 'text' => 'Payment method url'],
        ['label' => 'order_payment_method_is_online', 'text' => 'Payment method is online'],
        ['label' => 'order_payment_method_costs', 'text' => 'Enter the payment fee. The payment rate is <br/> always VAT included because the VAT is proportionally based on the tax rates of the purchased products.'],
        ['label' => 'order_payment_method_drag', 'text' => 'Drag and drop to change the rules order'],
        ['label' => 'order_payment_method_change_order', 'text' => 'Change of payment order'],
        ['label' => 'order_no_payment_method', 'text' => 'There are not payment methods to display'],
        ['label' => 'order_payment_method_no_delete', 'text' => 'Payment method cannot be removed'],
        ['label' => 'order_payment_method_delete', 'text' => 'Delete payment method'],
        ['label' => 'order_payment_method_no_edit', 'text' => 'Payment method cannot be edited'],
        ['label' => 'order_payment_method_edit', 'text' => 'Edit payment method'],
        ['label' => 'order_page_after_payment', 'text' => 'Page after ordering'],
        ['label' => 'order_payment_method_online', 'text' => 'Online method'],
        ['label' => 'order_payment_method_save', 'text' => 'Add payment method'],
        ['label' => 'order_all_payment_method', 'text' => 'All payment methods'],
        ['label' => 'order_no_online_payments', 'text' => 'There are not online payments for this order.'],
        ['label' => 'order_external_status', 'text' => 'Remote Status'],
        ['label' => 'order_last_update', 'text' => 'Last update'],
        ['label' => 'order_started_payment', 'text' => 'Payment started'],
        ['label' => 'order_online_payments', 'text' => 'Online payments'],
        ['label' => 'order_status_history', 'text' => 'Status History'],
        ['label' => 'order_save_status', 'text' => 'Edit Status'],
        ['label' => 'order_status_commentary_tooltip', 'text' => 'Enter a comment to along with the status notification'],
        ['label' => 'order_status_commentary', 'text' => 'Any comment about status'],
        ['label' => 'order_send_notification', 'text' => 'send notification'],
        ['label' => 'order_notification_tooltip', 'text' => 'Check to send an email to the customer about the status change'],
        ['label' => 'order_notification', 'text' => 'Notification'],
        ['label' => 'order_choose_status', 'text' => 'Choose a status'],
        ['label' => 'order_status_select_tooltip', 'text' => 'Change the status to update the status history < br/>-\' paid \': stock is set off if it is not done is < br/>-\' cancelled \': inventory is acquired if it is not done is'],
        ['label' => 'order_order_status', 'text' => 'Order status'],
        ['label' => 'order_see_original', 'text' => 'View the original product'],
        ['label' => 'order_cancelled_tooltip', 'text' => 'Number of products deducted from the stock. <br/>-It is\r\ndeducted automatically if the order gets the status \'paid\' < br/>-Canceled status update the stock automatically'],
        ['label' => 'order_cancelled', 'text' => 'Order canceled'],
        ['label' => 'order_house_number_tooltip', 'text' => 'Please enter a valid house number using only numbers'],
        ['label' => 'order_google_maps', 'text' => 'Click here to view the location in Google Maps'],
        ['label' => 'order_location', 'text' => 'Location'],
        ['label' => 'order_delivery_info', 'text' => 'Delivery details'],
        ['label' => 'order_invoice_info', 'text' => 'Invoice information'],
        ['label' => 'order_back_overview', 'text' => 'Back to orders overview'],
        ['label' => 'order_unit_price', 'text' => 'Price per piece'],
        ['label' => 'order_product_name', 'text' => 'Article name'],
        ['label' => 'order_invoice_amount', 'text' => 'Invoice amount'],
        ['label' => 'order_delivery_cost', 'text' => 'Delivery costs'],
        ['label' => 'order_delivery_method', 'text' => 'Delivery method'],
        ['label' => 'order_payment_status', 'text' => 'Payment status'],
        ['label' => 'order_city', 'text' => 'City'],
        ['label' => 'order_house_number', 'text' => 'House number'],
        ['label' => 'order_street', 'text' => 'Street'],
        ['label' => 'order_delivery_address', 'text' => 'Delivery address'],
        ['label' => 'order_invoice_address', 'text' => 'Invoice address'],
        ['label' => 'order_no_orders', 'text' => 'There are not orders to display with this filter.'],
        ['label' => 'order_edit_order', 'text' => 'Edit order'],
        ['label' => 'order_payment_method', 'text' => 'Payment Method'],
        ['label' => 'order_updated', 'text' => 'Updated'],
        ['label' => 'order_created', 'text' => 'Created'],
        ['label' => 'order_export', 'text' => 'Export orders'],
        ['label' => 'order_all_orders', 'text' => 'All orders'],
        ['label' => 'order_status_tooltip', 'text' => 'Search on one or more < br/> statuses of orders - hold down the Ctrl key to multiple States to be able to click'],
        ['label' => 'order_order_number_tooltip', 'text' => 'Search the order number of an order'],
        ['label' => 'order_order_number', 'text' => 'Order Number'],
        ['label' => 'settings_pdf_order_logo', 'text' => 'Order PDF logo'],
        ['label' => 'settings_defaultEmailOrders', 'text' => 'Default email orders'],
        ['label' => 'settings_defaultEmailOrders_tooltip', 'text' => 'Fill the default email orders'],
        ['label' => 'global_system', 'text' => 'System'],
        ['label' => 'global_created_by', 'text' => 'Changed by'],
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
    ],
];

if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPageCart = PageManager::getPageByName('shoppingcart', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `shoppingcart`';
        if ($bInstall) {
            $oPageCart             = new Page();
            $oPageCart->languageId = DEFAULT_LANGUAGE_ID;
            $oPageCart->name       = 'shoppingcart';
            $oPageCart->title      = 'Winkelwagen';
            $oPageCart->content    = '<p>Dit is de inhoud van de winkelwagen</p>';
            $oPageCart->shortTitle = 'Winkelwagen';
            $oPageCart->forceUrlPath('/winkelwagen');
            $oPageCart->setControllerPath('/modules/orders/site/controllers/order.cont.php');
            $oPageCart->setOnlineChangeable(0);
            $oPageCart->setDeletable(0);
            $oPageCart->setMayHaveSub(0);
            $oPageCart->setLockUrlPath(1);
            $oPageCart->setLockParent(1);
            $oPageCart->setHideImageManagement(1);
            $oPageCart->setHideFileManagement(1);
            $oPageCart->setHideLinkManagement(1);
            $oPageCart->setHideVideoLinkManagement(1);
            if ($oPageCart->isValid()) {
                PageManager::savePage($oPageCart);
            } else {
                _d($oPageCart->getInvalidProps());
                die('Can\'t create page `shoppingcart`');
            }
        }
    }

    // add shopping cart order page
    if (!empty($oPageCart)) {
        if (!($oPageOrder = PageManager::getPageByName('shoppingcart_order', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `shoppingcart_order`';
            if ($bInstall) {
                $oPageOrder               = new Page();
                $oPageOrder->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageOrder->parentPageId = $oPageCart->pageId;
                $oPageOrder->name         = 'shoppingcart_order';
                $oPageOrder->title        = 'Bestellen';
                $oPageOrder->content      = '<p>Vul hier uw persoonlijke gegevens in voor het plaatsen van de bestelling. Als u nog niet bent ingelogd en u heeft al een account dan kunt u aan de linker kant inloggen. Als u geen account heeft kunt u rechts direct uw gegevens invullen of u een <a href="/account">account aanmaken</a></p>';
                $oPageOrder->shortTitle   = 'Bestellen';
                $oPageOrder->forceUrlPath($oPageCart->getUrlPath() . '/bestellen');
                $oPageOrder->setControllerPath('/modules/orders/site/controllers/order.cont.php');
                $oPageOrder->setIndexable(0);
                $oPageOrder->setOnlineChangeable(0);
                $oPageOrder->setDeletable(0);
                $oPageOrder->setMayHaveSub(0);
                $oPageOrder->setLockUrlPath(1);
                $oPageOrder->setLockParent(1);
                $oPageOrder->setHideImageManagement(1);
                $oPageOrder->setHideFileManagement(1);
                $oPageOrder->setHideLinkManagement(1);
                $oPageOrder->setHideVideoLinkManagement(1);
                if ($oPageOrder->isValid()) {
                    PageManager::savePage($oPageOrder);
                } else {
                    _d($oPageOrder->getInvalidProps());
                    die('Can\'t create page `shoppingcart_order`');
                }
            }
        }
    }

    // add shopping cart order page
    if (!empty($oPageCart)) {
        if (!($oPageOrder = PageManager::getPageByName('shoppingcart_order_placed', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `shoppingcart_order_placed`';
            if ($bInstall) {
                $oPageOrder               = new Page();
                $oPageOrder->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageOrder->parentPageId = $oPageCart->pageId;
                $oPageOrder->name         = 'shoppingcart_order_placed';
                $oPageOrder->title        = 'Bestelling geplaatst';
                $oPageOrder->content      = '<p>Uw bestellig is geplaats. U krijgt een e-mail op het aangegeven e-mailadres. Als u deze niet ontvangt kijk dan in uw SPAM. Nog steeds niet gevonden of u wilt meer informatie? Neem contact met ons op.</p>';
                $oPageOrder->shortTitle   = 'Bestelling geplaatst';
                $oPageOrder->forceUrlPath($oPageCart->getUrlPath() . '/bestelling-geplaatst');
                $oPageOrder->setControllerPath('/modules/orders/site/controllers/order.cont.php');
                $oPageOrder->setIndexable(0);
                $oPageOrder->setOnlineChangeable(0);
                $oPageOrder->setDeletable(0);
                $oPageOrder->setMayHaveSub(0);
                $oPageOrder->setLockUrlPath(1);
                $oPageOrder->setLockParent(1);
                $oPageOrder->setHideImageManagement(1);
                $oPageOrder->setHideFileManagement(1);
                $oPageOrder->setHideLinkManagement(1);
                $oPageOrder->setHideVideoLinkManagement(1);
                if ($oPageOrder->isValid()) {
                    PageManager::savePage($oPageOrder);
                } else {
                    _d($oPageOrder->getInvalidProps());
                    die('Can\'t create page `shoppingcart_order_placed`');
                }
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oNewPageCart = PageManager::getPageByName('shoppingcart', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `shoppingcart` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create shoppingcart page
                $oNewPageCart             = new Page();
                $oNewPageCart->languageId = $oLocale->languageId;
                $oNewPageCart->name       = 'shoppingcart';
                $oNewPageCart->title      = 'Shopping cart';
                $oNewPageCart->content    = '<p>This is the content of the shopping cart</p>';
                $oNewPageCart->shortTitle = 'Shopping cart';
                $oNewPageCart->forceUrlPath('/shopping-cart');
                $oNewPageCart->setControllerPath('/modules/orders/site/controllers/order.cont.php');
                $oNewPageCart->setOnlineChangeable(0);
                $oNewPageCart->setDeletable(0);
                $oNewPageCart->setMayHaveSub(0);
                $oNewPageCart->setLockUrlPath(1);
                $oNewPageCart->setLockParent(1);
                $oNewPageCart->setHideImageManagement(1);
                $oNewPageCart->setHideFileManagement(1);
                $oNewPageCart->setHideLinkManagement(1);
                $oNewPageCart->setHideVideoLinkManagement(1);
                if ($oNewPageCart->isValid()) {
                    PageManager::savePage($oNewPageCart);
                } else {
                    _d($oNewPageCart->getInvalidProps());
                    die('Can\'t create page `shoppingcart`');
                }
            }
        }

        if (!($oNewPageOrder = PageManager::getPageByName('shoppingcart_order', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `shoppingcart_order` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create order page
                $oNewPageOrder               = new Page();
                $oNewPageOrder->languageId   = $oLocale->languageId;
                $oNewPageOrder->parentPageId = $oNewPageCart->pageId;
                $oNewPageOrder->name         = 'shoppingcart_order';
                $oNewPageOrder->title        = 'Order';
                $oNewPageOrder->content      = '<p>Fill in your personal information before placing the order. If you are not already logged in and you already have an account, please login at the left side. If you do not have an account, you can directly enter your information, or you can create an <a href="/' . $oLocale->getURLPrefix(
                    ) . '/account">account</a></p>';
                $oNewPageOrder->shortTitle   = 'Order';
                $oNewPageOrder->forceUrlPath('/shopping-cart/order');
                $oNewPageOrder->setControllerPath('/modules/orders/site/controllers/order.cont.php');
                $oNewPageOrder->setIndexable(0);
                $oNewPageOrder->setOnlineChangeable(0);
                $oNewPageOrder->setDeletable(0);
                $oNewPageOrder->setMayHaveSub(0);
                $oNewPageOrder->setLockUrlPath(1);
                $oNewPageOrder->setLockParent(1);
                $oNewPageOrder->setHideImageManagement(1);
                $oNewPageOrder->setHideFileManagement(1);
                $oNewPageOrder->setHideLinkManagement(1);
                $oNewPageOrder->setHideVideoLinkManagement(1);
                if ($oNewPageOrder->isValid()) {
                    PageManager::savePage($oNewPageOrder);
                } else {
                    _d($oNewPageOrder->getInvalidProps());
                    die('Can\'t create page `shoppingcart_order`');
                }
            }
        }

        if (!($oNewPageOrderPlaced = PageManager::getPageByName('shoppingcart_order_placed', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `shoppingcart_order_placed` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create order placed page
                $oNewPageOrderPlaced               = new Page();
                $oNewPageOrderPlaced->languageId   = $oLocale->languageId;
                $oNewPageOrderPlaced->parentPageId = $oNewPageCart->pageId;
                $oNewPageOrderPlaced->name         = 'shoppingcart_order_placed';
                $oNewPageOrderPlaced->title        = 'Order placed';
                $oNewPageOrderPlaced->content      = '<p>Your order has been placed. You will receive an email to the specified email address. If you do not receive the email check your SPAM. Still didn\'t recieve the message or do you want more info? Contact us.</p>';
                $oNewPageOrderPlaced->shortTitle   = 'Order placed';
                $oNewPageOrderPlaced->forceUrlPath('/shopping-cart/order-placed');
                $oNewPageOrderPlaced->setControllerPath('/modules/orders/site/controllers/order.cont.php');
                $oNewPageOrderPlaced->setIndexable(0);
                $oNewPageOrderPlaced->setOnlineChangeable(0);
                $oNewPageOrderPlaced->setDeletable(0);
                $oNewPageOrderPlaced->setMayHaveSub(0);
                $oNewPageOrderPlaced->setLockUrlPath(1);
                $oNewPageOrderPlaced->setLockParent(1);
                $oNewPageOrderPlaced->setHideImageManagement(1);
                $oNewPageOrderPlaced->setHideFileManagement(1);
                $oNewPageOrderPlaced->setHideLinkManagement(1);
                $oNewPageOrderPlaced->setHideVideoLinkManagement(1);
                if ($oNewPageOrderPlaced->isValid()) {
                    PageManager::savePage($oNewPageOrderPlaced);
                } else {
                    _d($oNewPageOrderPlaced->getInvalidProps());
                    die('Can\'t create page `shoppingcart_order_placed`');
                }
            }
        }
    }
}

// check settings
if (moduleExists('core')) {
    if (!($oSetting1 = SettingManager::getSettingByName('loginRequiredBeforeOrder'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `loginRequiredBeforeOrder`';
        if ($bInstall) {
            $oSetting1        = new Setting();
            $oSetting1->name  = 'loginRequiredBeforeOrder';
            $oSetting1->value = '0';
            if ($oSetting1->isValid()) {
                SettingManager::saveSetting($oSetting1);
            } else {
                _d($oSetting1->getInvalidProps());
                die('Can\'t create setting `loginRequiredBeforeOrder`');
            }
        }
    }

    if (!($oSetting2 = SettingManager::getSettingByName('defaultEmailOrders'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `defaultEmailOrders`';
        if ($bInstall) {
            $oSetting2        = new Setting();
            $oSetting2->name  = 'defaultEmailOrders';
            $oSetting2->value = 'name@domain.ext';
            if ($oSetting2->isValid()) {
                SettingManager::saveSetting($oSetting2);
            } else {
                _d($oSetting2->getInvalidProps());
                die('Can\'t create setting `defaultEmailOrders`');
            }
        }
    }

    if (!($oSetting3 = SettingManager::getSettingByName('orderLogoImage'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `orderLogoImage`';
        if ($bInstall) {
            $oSetting3        = new Setting();
            $oSetting3->name  = 'orderLogoImage';
            $oSetting3->value = null;
            if ($oSetting3->isValid()) {
                SettingManager::saveSetting($oSetting3);
            } else {
                _d($oSetting3->getInvalidProps());
                die('Can\'t create setting `orderLogoImage`');
            }
        }
    }
}

if (moduleExists('templates') && $oDb->tableExists('template_groups')) {
    if (!($oTemplateGroupOrders = TemplateGroupManager::getTemplateGroupByName('orders'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing template group `orders`';
        if ($bInstall) {
            $oTemplateGroupOrders                    = new TemplateGroup();
            $oTemplateGroupOrders->name              = 'orders';
            $oTemplateGroupOrders->templateGroupName = 'Bestelling';
            $oTemplateGroupOrders->templateVariables = '
[order_orderId]
[order_email]
[order_firstName]
[order_lastName]
[order_fullName]
[order_generalInfo]
[order_invoiceInfo]
[order_deliveryInfo]
[order_productsInfo]
[order_customerGoogleMapsLink]
[order_status]
            ';
            if ($oTemplateGroupOrders->isValid()) {
                TemplateGroupManager::saveTemplateGroup($oTemplateGroupOrders);
            } else {
                _d($oTemplateGroupOrders->getInvalidProps());
                die('Can\'t create template group `orders`');
            }
        }
    }
}

if (!empty($oTemplateGroupOrders)) {
    if (!($oTemplate1 = TemplateManager::getTemplateByName('order_created_owner', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing template `order_created_owner`';
        if ($bInstall) {
            $oTemplate1                  = new Template();
            $oTemplate1->languageId      = DEFAULT_LANGUAGE_ID;
            $oTemplate1->description     = 'Bestelling gemaakt (naar eigen)';
            $oTemplate1->type            = Template::TYPE_EMAIL;
            $oTemplate1->templateGroupId = $oTemplateGroupOrders->templateGroupId;
            $oTemplate1->subject         = '[CLIENT_NAME] | bestelling gemaakt #[order_orderId]';
            $oTemplate1->template        = '
<p>Er is zojuist een bestelling geplaatst op [CLIENT_URL]. Hieronder leest u de bestelgegevens.</p>
<p>&nbsp;</p>
<p>Algemene gegevens<br />Ordernummer: [order_orderId]<br />Status: [order_status]</p>
<p>Factuurgegevens<br />Bedrijfsnaam: [order_invoice_companyName]<br />Geslacht: [order_invoice_gender]<br />Voornaam: [order_invoice_firstName]<br />Tussenvoegsel: [order_invoice_insertion]<br />Achternaam: [order_invoice_lastName]<br />Adres: [order_invoice_fullAddress]<br />Postcode: [order_invoice_postalCode]<br />Plaats: [order_invoice_city]<br />Telefoon: [order_invoice_phone]</p>
<p>Verzendgegevens<br />Bedrijfsnaam: [order_delivery_companyName]<br />Geslacht: [order_delivery_gender]<br />Voornaam: [order_delivery_firstName]<br />Tussenvoegsel: [order_delivery_insertion]<br />Achternaam: [order_delivery_lastName]<br />Adres: [order_delivery_fullAddress]<br />Postcode: [order_delivery_postalCode]<br />Plaats: [order_delivery_city]</p>
<p>Producten<br />[order_productsInfo]</p>
<p>Met vriendelijke groet,</p>
<p>[CLIENT_NAME]</p>
            ';
            $oTemplate1->name            = 'order_created_owner';
            $oTemplate1->setEditable(1);
            $oTemplate1->setDeletable(0);
            if ($oTemplate1->isValid()) {
                TemplateManager::saveTemplate($oTemplate1);
            } else {
                _d($oTemplate1->getInvalidProps());
                die('Can\'t create template `order_created_owner`');
            }
        }
    }

    if (!($oTemplate2 = TemplateManager::getTemplateByName('order_created_customer', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing template `order_created_customer`';
        if ($bInstall) {
            $oTemplate2                  = new Template();
            $oTemplate2->languageId      = DEFAULT_LANGUAGE_ID;
            $oTemplate2->description     = 'Bestelling gemaakt (naar klant)';
            $oTemplate2->type            = Template::TYPE_EMAIL;
            $oTemplate2->templateGroupId = $oTemplateGroupOrders->templateGroupId;
            $oTemplate2->subject         = '[CLIENT_NAME] | bestelling gemaakt #[order_orderId]';
            $oTemplate2->template        = '
<p>Beste [order_invoice_firstName],</p>
<p>Bedankt voor uw bestelling op [CLIENT_URL]. Hieronder leest u de bestelgegevens.</p>
<p><strong>Algemene gegevens</strong></p>
<table style="height: 47px;" width="294">
<tbody>
<tr>
<td>Ordernummer:</td>
<td>[order_orderId]</td>
</tr>
<tr>
<td>Status:</td>
<td>[order_status]</td>
</tr>
</tbody>
</table>
<p><strong>Factuurgegevens</strong></p>
<table width="300">
<tbody>
<tr>
<td>Bedrijfsnaam:</td>
<td>[order_invoice_companyName]</td>
</tr>
<tr>
<td>Geslacht:</td>
<td>[order_invoice_gender]</td>
</tr>
<tr>
<td>Voornaam:</td>
<td>[order_invoice_firstName]</td>
</tr>
<tr>
<td>Tussenvoegsel:</td>
<td>[order_invoice_insertion]</td>
</tr>
<tr>
<td>Achternaam:</td>
<td>[order_invoice_lastName]</td>
</tr>
<tr>
<td>Adres:</td>
<td>[order_invoice_fullAddress]</td>
</tr>
<tr>
<td>Postcode:</td>
<td>[order_invoice_postalCode]</td>
</tr>
<tr>
<td>Plaats:</td>
<td>[order_invoice_city]</td>
</tr>
<tr>
<td>Telefoon:</td>
<td>[order_invoice_phone]</td>
</tr>
</tbody>
</table>
<p><strong>Verzendgegevens</strong></p>
<table width="300">
<tbody>
<tr>
<td>Bedrijfsnaam:</td>
<td>[order_delivery_companyName]</td>
</tr>
<tr>
<td>Geslacht:</td>
<td>[order_delivery_gender]</td>
</tr>
<tr>
<td>Voornaam:</td>
<td>[order_delivery_firstName]</td>
</tr>
<tr>
<td>Tussenvoegsel:</td>
<td>[order_delivery_insertion]</td>
</tr>
<tr>
<td>Achternaam:</td>
<td>[order_delivery_lastName]</td>
</tr>
<tr>
<td>Adres:</td>
<td>[order_delivery_fullAddress]</td>
</tr>
<tr>
<td>Postcode:</td>
<td>[order_delivery_postalCode]</td>
</tr>
<tr>
<td>Plaats:</td>
<td>[order_delivery_city]</td>
</tr>
</tbody>
</table>
<p><strong>Producten</strong><br />[order_productsInfo]</p>
<p>Mocht u nog vragen hebben kunt u contact met ons op nemen.</p>
<p>Met vriendelijke groet,</p>
<p>[CLIENT_NAME]</p>
<p>&nbsp;</p>
            ';
            $oTemplate2->name            = 'order_created_customer';
            $oTemplate2->setEditable(1);
            $oTemplate2->setDeletable(0);
            if ($oTemplate2->isValid()) {
                TemplateManager::saveTemplate($oTemplate2);
            } else {
                _d($oTemplate2->getInvalidProps());
                die('Can\'t create template `order_created_customer`');
            }
        }
    }

    if (!($oTemplate3 = TemplateManager::getTemplateByName('order_status_changed', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing template `order_status_changed`';
        if ($bInstall) {
            $oTemplate3                  = new Template();
            $oTemplate3->languageId      = DEFAULT_LANGUAGE_ID;
            $oTemplate3->description     = 'Bestelling status wijziging';
            $oTemplate3->type            = Template::TYPE_EMAIL;
            $oTemplate3->templateGroupId = $oTemplateGroupOrders->templateGroupId;
            $oTemplate3->subject         = '[CLIENT_NAME] | update bestelling #[order_orderId]';
            $oTemplate3->template        = '
<p>Uw bestelling met nummer #[order_orderId] heeft de status `[order_status]` gekregen. Indien u vragen heeft kunt u contact met ons opnemen.</p>
<p>[special_comment]</p>
<p>Met vriendelijke groet,</p>
<p>[CLIENT_NAME]</p>
            ';
            $oTemplate3->name            = 'order_status_changed';
            $oTemplate3->setEditable(1);
            $oTemplate3->setDeletable(0);
            if ($oTemplate3->isValid()) {
                TemplateManager::saveTemplate($oTemplate3);
            } else {
                _d($oTemplate3->getInvalidProps());
                die('Can\'t create template `order_status_changed`');
            }
        }
    }

    // check if extra language is installed
    $aLocales = LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]);
    if (count($aLocales) > 0) {
        foreach ($aLocales as $oLocale) {
            if (!($oTemplate1 = TemplateManager::getTemplateByName('order_created_owner', $oLocale->languageId))) {
                $aLogs[$sModuleName]['errors'][] = 'Missing template `order_created_owner` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
                if ($bInstall) {
                    $oTemplate1                  = new Template();
                    $oTemplate1->languageId      = $oLocale->languageId;
                    $oTemplate1->description     = 'Bestelling gemaakt (naar eigen)';
                    $oTemplate1->type            = Template::TYPE_EMAIL;
                    $oTemplate1->templateGroupId = $oTemplateGroupOrders->templateGroupId;
                    $oTemplate1->subject         = '[CLIENT_NAME] | order placed #[order_orderId]';
                    $oTemplate1->template        = '
<p>An order has just been placed on [CLIENT_URL]. Below you will read the order information.</p>
<p>&nbsp;</p>
<p>General data<br />Order number: [order_orderId]<br />Status: [order_status]</p>
<p>Invoice data<br />Company name: [order_invoice_companyName]<br />Gender: [order_invoice_gender]<br />First name: [order_invoice_firstName]<br />Insertion: [order_invoice_insertion]<br />Last name: [order_invoice_lastName]<br />Address: [order_invoice_fullAddress]<br />Postal code: [order_invoice_postalCode]<br />City: [order_invoice_city]<br />Phone: [order_invoice_phone]</p>
<p>Delivery data<br />Company name: [order_delivery_companyName]<br />Gender: [order_delivery_gender]<br />First name: [order_delivery_firstName]<br />Insertion: [order_delivery_insertion]<br />Last name: [order_delivery_lastName]<br />Address: [order_delivery_fullAddress]<br />Postal code: [order_delivery_postalCode]<br />City: [order_delivery_city]</p>
<p>Products<br />[order_productsInfo]</p>
<p>Kind regards,</p>
<p>[CLIENT_NAME]</p>
            ';
                    $oTemplate1->name            = 'order_created_owner';
                    $oTemplate1->setEditable(1);
                    $oTemplate1->setDeletable(0);
                    if ($oTemplate1->isValid()) {
                        TemplateManager::saveTemplate($oTemplate1);
                    } else {
                        _d($oTemplate1->getInvalidProps());
                        die('Can\'t create template `order_created_owner` for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                    }
                }
            }

            if (!($oTemplate2 = TemplateManager::getTemplateByName('order_created_customer', $oLocale->languageId))) {
                $aLogs[$sModuleName]['errors'][] = 'Missing template `order_created_customer` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
                if ($bInstall) {
                    $oTemplate2                  = new Template();
                    $oTemplate2->languageId      = $oLocale->languageId;
                    $oTemplate2->description     = 'Bestelling gemaakt (naar klant)';
                    $oTemplate2->type            = Template::TYPE_EMAIL;
                    $oTemplate2->templateGroupId = $oTemplateGroupOrders->templateGroupId;
                    $oTemplate2->subject         = '[CLIENT_NAME] | order placed #[order_orderId]';
                    $oTemplate2->template        = '
<p>Dear [order_invoice_firstName],</p>
<p>Thank you for your order [CLIENT_URL]. Below you will read the order information.</p>
<p><strong>General data</strong></p>
<table style="height: 47px;" width="294">
<tbody>
<tr>
<td>Order number:</td>
<td>[order_orderId]</td>
</tr>
<tr>
<td>Status:</td>
<td>[order_status]</td>
</tr>
</tbody>
</table>
<p><strong>Invoice data</strong></p>
<table width="300">
<tbody>
<tr>
<td>Company name:</td>
<td>[order_invoice_companyName]</td>
</tr>
<tr>
<td>Gender:</td>
<td>[order_invoice_gender]</td>
</tr>
<tr>
<td>First name:</td>
<td>[order_invoice_firstName]</td>
</tr>
<tr>
<td>Insertion:</td>
<td>[order_invoice_insertion]</td>
</tr>
<tr>
<td>Last name:</td>
<td>[order_invoice_lastName]</td>
</tr>
<tr>
<td>Address:</td>
<td>[order_invoice_fullAddress]</td>
</tr>
<tr>
<td>Postal code:</td>
<td>[order_invoice_postalCode]</td>
</tr>
<tr>
<td>City:</td>
<td>[order_invoice_city]</td>
</tr>
<tr>
<td>Phone:</td>
<td>[order_invoice_phone]</td>
</tr>
</tbody>
</table>
<p><strong>Delivery data</strong></p>
<table width="300">
<tbody>
<tr>
<td>Company name:</td>
<td>[order_delivery_companyName]</td>
</tr>
<tr>
<td>Gender:</td>
<td>[order_delivery_gender]</td>
</tr>
<tr>
<td>First name:</td>
<td>[order_delivery_firstName]</td>
</tr>
<tr>
<td>Insertion:</td>
<td>[order_delivery_insertion]</td>
</tr>
<tr>
<td>Last name:</td>
<td>[order_delivery_lastName]</td>
</tr>
<tr>
<td>Address:</td>
<td>[order_delivery_fullAddress]</td>
</tr>
<tr>
<td>Postal code:</td>
<td>[order_delivery_postalCode]</td>
</tr>
<tr>
<td>City:</td>
<td>[order_delivery_city]</td>
</tr>
</tbody>
</table>
<p><strong>Products</strong><br />[order_productsInfo]</p>
<p>Do you have any questions? Do not hesitate to contact us.</p>
<p>Kind regards,</p>
<p>[CLIENT_NAME]</p>
<p>&nbsp;</p>
            ';
                    $oTemplate2->name            = 'order_created_customer';
                    $oTemplate2->setEditable(1);
                    $oTemplate2->setDeletable(0);
                    if ($oTemplate2->isValid()) {
                        TemplateManager::saveTemplate($oTemplate2);
                    } else {
                        _d($oTemplate2->getInvalidProps());
                        die('Can\'t create template `order_created_customer` for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                    }
                }
            }

            if (!($oTemplate3 = TemplateManager::getTemplateByName('order_status_changed', $oLocale->languageId))) {
                $aLogs[$sModuleName]['errors'][] = 'Missing template `order_status_changed` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
                if ($bInstall) {
                    $oTemplate3                  = new Template();
                    $oTemplate3->languageId      = $oLocale->languageId;
                    $oTemplate3->description     = 'Bestelling status wijziging';
                    $oTemplate3->type            = Template::TYPE_EMAIL;
                    $oTemplate3->templateGroupId = $oTemplateGroupOrders->templateGroupId;
                    $oTemplate3->subject         = '[CLIENT_NAME] | update order #[order_orderId]';
                    $oTemplate3->template        = '
<p>Your order with number #[order_orderId] has been given the status `[order_status]`. Do you have any questions? Do not hesitate to contact us.</p>
<p>[special_comment]</p>
<p>Kind regards,</p>
<p>[CLIENT_NAME]</p>
            ';
                    $oTemplate3->name            = 'order_status_changed';
                    $oTemplate3->setEditable(1);
                    $oTemplate3->setDeletable(0);
                    if ($oTemplate3->isValid()) {
                        TemplateManager::saveTemplate($oTemplate3);
                    } else {
                        _d($oTemplate3->getInvalidProps());
                        die('Can\'t create template `order_status_changed` for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                    }
                }
            }
        }
    }
}

if (!$oDb->tableExists('orders')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `orders`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `orders` (
          `orderId` int(11) NOT NULL AUTO_INCREMENT,
          `customerId` int(11) DEFAULT NULL,
          `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `invoice_companyName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `invoice_gender` enum(\'U\',\'M\',\'F\') COLLATE utf8_unicode_ci NOT NULL DEFAULT \'U\',
          `invoice_firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `invoice_insertion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `invoice_lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `invoice_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `invoice_houseNumber` int(11) NOT NULL,
          `invoice_houseNumberAddition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `invoice_postalCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `invoice_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `invoice_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `delivery_companyName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `delivery_gender` enum(\'U\',\'M\',\'F\') COLLATE utf8_unicode_ci NOT NULL DEFAULT \'U\',
          `delivery_firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `delivery_insertion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `delivery_lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `delivery_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `delivery_houseNumber` int(11) NOT NULL,
          `delivery_houseNumberAddition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `delivery_postalCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `delivery_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `deliveryMethodId` int(11) DEFAULT NULL,
          `deliveryMethodName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `deliveryPrice` decimal(10,4) NOT NULL,
          `paymentMethodId` int(11) DEFAULT NULL,
          `paymentMethodName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `paymentPrice` decimal(10,4) NOT NULL,
          `totalDiscount` decimal(10,2) NOT NULL DEFAULT \'0.00\',
          `totalPriceWithoutTax` decimal(10,2) NOT NULL,
          `totalPriceWithTax` decimal(10,2) NOT NULL,
          `couponId` int(11) DEFAULT NULL,
          `taxIncluded` tinyint(1) NOT NULL DEFAULT \'0\',
          `status` int(11) NOT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`orderId`),
          KEY `fk_orders_customerId` (`customerId`),
          KEY `fk_orders_deliveryMethodId` (`deliveryMethodId`),
          KEY `fk_orders_paymentMethodId` (`paymentMethodId`),
          KEY `fk_coupon` (`couponId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('order_payments')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `order_payments`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `order_payments` (
          `orderPaymentId` int(11) NOT NULL AUTO_INCREMENT,
          `price` decimal(10,2) NOT NULL,
          `status` int(11) NOT NULL,
          `externalStatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `externalPaymentReference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `response` text COLLATE utf8_unicode_ci,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          `orderId` int(11) NOT NULL,
          `paymentMethodId` int(11) DEFAULT NULL,
          `paymentMethodName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`orderPaymentId`),
          KEY `fk_orderPayments_orderId` (`orderId`),
          KEY `fk_orderPayments_paymentMethodId` (`paymentMethodId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('order_products')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `order_products`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `order_products` (
          `orderProductId` int(11) NOT NULL AUTO_INCREMENT,
          `orderId` int(11) NOT NULL,
          `catalogProductId` int(11) DEFAULT NULL,
          `brandName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `productName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `amount` int(11) NOT NULL DEFAULT \'1\',
          `originalPrice` decimal(10,4) NOT NULL,
          `salePrice` decimal(10,4) NOT NULL,
          `purchasePrice` decimal(10,4) NOT NULL,
          `taxPercentage` int(11) NOT NULL,
          `catalogProductSizeId` int(11) DEFAULT NULL,
          `productSizeName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `catalogProductColorId` int(11) DEFAULT NULL,
          `productColorName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `substractedFromStock` int(11) NOT NULL DEFAULT \'0\',
          PRIMARY KEY (`orderProductId`),
          KEY `fk_orderProducts_orderId` (`orderId`),
          KEY `fk_orderProducts_catalogProductId` (`catalogProductId`),
          KEY `catalogProductSizeId` (`catalogProductSizeId`),
          KEY `catalogProductColorId` (`catalogProductColorId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('order_statuses')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `order_statuses`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `order_statuses` (
          `orderStatusId` int(11) NOT NULL AUTO_INCREMENT,
          `status` int(11) NOT NULL,
          `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `orderId` int(11) NOT NULL,
          `createdBy` VARCHAR(255) NOT NULL,
          PRIMARY KEY (`orderStatusId`),
          KEY `fk_orderStatuses_orderId` (`orderId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('delivery_methods')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `delivery_methods`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `delivery_methods` (
          `deliveryMethodId` int(11) NOT NULL AUTO_INCREMENT,
          `price` decimal(10,4) NOT NULL,
          `order` int(11) NOT NULL DEFAULT \'99999\',
          `freeFromPrice` decimal(10,4) DEFAULT NULL,
          `deliveryTime` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `system_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`deliveryMethodId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('delivery_method_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `delivery_method_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `delivery_method_translations` (
          `deliveryMethodTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `deliveryMethodId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`deliveryMethodTranslationId`),
          UNIQUE KEY `deliveryMethodId_languageId` (`deliveryMethodId`,`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('delivery_method_payment_method_relations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `delivery_method_payment_method_relations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `delivery_method_payment_method_relations` (
          `deliveryMethodId` int(11) NOT NULL,
          `paymentMethodId` int(11) NOT NULL,
          PRIMARY KEY (`deliveryMethodId`,`paymentMethodId`),
          KEY `paymentMethodId` (`paymentMethodId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('payment_methods')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `payment_methods`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `payment_methods` (
          `paymentMethodId` int(11) NOT NULL AUTO_INCREMENT,
          `price` decimal(10,4) NOT NULL,
          `isOnlinePaymentMethod` int(1) NOT NULL DEFAULT \'0\',
          `order` int(11) NOT NULL DEFAULT \'99999\',
          `system_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`paymentMethodId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('payment_method_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `payment_method_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `payment_method_translations` (
          `paymentMethodTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `paymentMethodId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `redirectPage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`paymentMethodTranslationId`),
          UNIQUE KEY `paymentMethodId_languageId` (`paymentMethodId`,`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

// check orders constraints
if ($oDb->tableExists('orders')) {
    if ($oDb->tableExists('customers')) {
        // check customers constraint
        if (!$oDb->constraintExists('orders', 'customerId', 'customers', 'customerId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `orders`.`customerId` => `customers`.`customerId`';
            if ($bInstall) {
                $oDb->addConstraint('orders', 'customerId', 'customers', 'customerId', 'SET NULL', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('delivery_methods')) {
        // check delivery_methods constraint
        if (!$oDb->constraintExists('orders', 'deliveryMethodId', 'delivery_methods', 'deliveryMethodId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `orders`.`deliveryMethodId` => `delivery_methods`.`deliveryMethodId`';
            if ($bInstall) {
                $oDb->addConstraint('orders', 'deliveryMethodId', 'delivery_methods', 'deliveryMethodId', 'SET NULL', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('payment_methods')) {
        // check payment_methods constraint
        if (!$oDb->constraintExists('orders', 'paymentMethodId', 'payment_methods', 'paymentMethodId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `orders`.`paymentMethodId` => `payment_methods`.`paymentMethodId`';
            if ($bInstall) {
                $oDb->addConstraint('orders', 'paymentMethodId', 'payment_methods', 'paymentMethodId', 'SET NULL', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('coupons')) {
        // check coupons constraint
        if (!$oDb->constraintExists('orders', 'couponId', 'coupons', 'couponId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `orders`.`couponId` => `coupons`.`couponId`';
            if ($bInstall) {
                $oDb->addConstraint('orders', 'couponId', 'coupons', 'couponId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check order_payments constraints
if ($oDb->tableExists('order_payments')) {
    if ($oDb->tableExists('orders')) {
        // check orders constraint
        if (!$oDb->constraintExists('order_payments', 'orderId', 'orders', 'orderId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `order_payments`.`orderId` => `orders`.`orderId`';
            if ($bInstall) {
                $oDb->addConstraint('order_payments', 'orderId', 'orders', 'orderId', 'CASCADE', 'CASCADE');
            }
        }
    }
    if ($oDb->tableExists('payment_methods')) {
        // check payment_methods constraint
        if (!$oDb->constraintExists('order_payments', 'paymentMethodId', 'payment_methods', 'paymentMethodId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `order_payments`.`paymentMethodId` => `payment_methods`.`paymentMethodId`';
            if ($bInstall) {
                $oDb->addConstraint('order_payments', 'paymentMethodId', 'payment_methods', 'paymentMethodId', 'SET NULL', 'CASCADE');
            }
        }
    }
}

// check order_payments constraints
if ($oDb->tableExists('order_products')) {
    if ($oDb->tableExists('orders')) {
        // check orders constraint
        if (!$oDb->constraintExists('order_products', 'orderId', 'orders', 'orderId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `order_products`.`orderId` => `orders`.`orderId`';
            if ($bInstall) {
                $oDb->addConstraint('order_products', 'orderId', 'orders', 'orderId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('catalog_products')) {
        // check catalog_products constraint
        if (!$oDb->constraintExists('order_products', 'catalogProductId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `order_products`.`catalogProductId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('order_products', 'catalogProductId', 'catalog_products', 'catalogProductId', 'SET NULL', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('catalog_product_sizes')) {
        // check catalog_product_sizes constraint
        if (!$oDb->constraintExists('order_products', 'catalogProductSizeId', 'catalog_product_sizes', 'catalogProductSizeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `order_products`.`catalogProductSizeId` => `catalog_product_sizes`.`catalogProductSizeId`';
            if ($bInstall) {
                $oDb->addConstraint('order_products', 'catalogProductSizeId', 'catalog_product_sizes', 'catalogProductSizeId', 'SET NULL', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('catalog_product_colors')) {
        // check catalog_product_colors constraint
        if (!$oDb->constraintExists('order_products', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `order_products`.`catalogProductColorId` => `catalog_product_colors`.`catalogProductColorId`';
            if ($bInstall) {
                $oDb->addConstraint('order_products', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId', 'SET NULL', 'CASCADE');
            }
        }
    }
}

// check order_statuses constraints
if ($oDb->tableExists('order_statuses')) {
    if ($oDb->tableExists('orders')) {
        // check order_statuses constraint
        if (!$oDb->constraintExists('order_statuses', 'orderId', 'orders', 'orderId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `order_statuses`.`orderId` => `orders`.`orderId`';
            if ($bInstall) {
                $oDb->addConstraint('order_statuses', 'orderId', 'orders', 'orderId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check delivery_method_payment_method_relations constraints
if ($oDb->tableExists('delivery_method_payment_method_relations')) {
    if ($oDb->tableExists('delivery_methods')) {
        // check delivery_methods constraint
        if (!$oDb->constraintExists('delivery_method_payment_method_relations', 'deliveryMethodId', 'delivery_methods', 'deliveryMethodId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `delivery_method_payment_method_relations`.`deliveryMethodId` => `delivery_methods`.`deliveryMethodId`';
            if ($bInstall) {
                $oDb->addConstraint('delivery_method_payment_method_relations', 'deliveryMethodId', 'delivery_methods', 'deliveryMethodId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('payment_methods')) {
        // check payment_methods constraint
        if (!$oDb->constraintExists('delivery_method_payment_method_relations', 'paymentMethodId', 'payment_methods', 'paymentMethodId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `delivery_method_payment_method_relations`.`paymentMethodId` => `payment_methods`.`paymentMethodId`';
            if ($bInstall) {
                $oDb->addConstraint('delivery_method_payment_method_relations', 'paymentMethodId', 'payment_methods', 'paymentMethodId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check payment_method_translations constraints
if ($oDb->tableExists('payment_method_translations')) {
    if ($oDb->tableExists('payment_methods')) {
        // check payment_method_translations constraint
        if (!$oDb->constraintExists('payment_method_translations', 'paymentMethodId', 'payment_methods', 'paymentMethodId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `payment_method_translations`.`paymentMethodId` => `payment_methods`.`paymentMethodId`';
            if ($bInstall) {
                $oDb->addConstraint('payment_method_translations', 'paymentMethodId', 'payment_methods', 'paymentMethodId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check language constraint
        if (!$oDb->constraintExists('payment_method_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `payment_method_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('payment_method_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check delivery_method_translations constraints
if ($oDb->tableExists('delivery_method_translations')) {
    if ($oDb->tableExists('delivery_methods')) {
        // check delivery_method_translations constraint
        if (!$oDb->constraintExists('delivery_method_translations', 'deliveryMethodId', 'delivery_methods', 'deliveryMethodId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `delivery_method_translations`.`deliveryMethodId` => `delivery_methods`.`deliveryMethodId`';
            if ($bInstall) {
                $oDb->addConstraint('delivery_method_translations', 'deliveryMethodId', 'delivery_methods', 'deliveryMethodId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check delivery_method_translations constraint
        if (!$oDb->constraintExists('delivery_method_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `delivery_method_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('delivery_method_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

# create payment methods
if (moduleExists('orders') && $oDb->tableExists('payment_methods') && $oDb->tableExists('payment_method_translations')) {

    # create main payment method
    if (!($oPaymentMethodIDEAL = PaymentMethodManager::getPaymentMethodByName('ideal'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing payment method `ideal`';
        if ($bInstall) {
            $oPaymentMethodIDEAL                        = new PaymentMethod();
            $oPaymentMethodIDEAL->price                 = 0;
            $oPaymentMethodIDEAL->isOnlinePaymentMethod = 1;
            $oPaymentMethodIDEAL->system_name           = 'ideal';
            if ($oPaymentMethodIDEAL->isValid()) {
                PaymentMethodManager::savePaymentMethod($oPaymentMethodIDEAL);
            } else {
                _d($oPaymentMethodIDEAL->getInvalidProps());
                die('Can\'t create payment method `ideal`');
            }
        }
    }

    # create payment method translations
    foreach (LocaleManager::getLocalesByFilter(['showAll' => true]) as $oLocale) {
        if ($oPaymentMethodIDEAL && !($oPaymentMethodTranslation = PaymentMethodTranslationManager::getPaymentMethodTranslationsByFilter(
                ['payementMethodId' => $oPaymentMethodIDEAL->paymentMethodId, 'languageId' => $oLocale->languageId]
            ))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing payment method translation `ideal` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oPaymentMethodTranslation                  = new PaymentMethodTranslation();
                $oPaymentMethodTranslation->paymentMethodId = $oPaymentMethodIDEAL->paymentMethodId;
                $oPaymentMethodTranslation->languageId      = $oLocale->languageId;
                $oPaymentMethodTranslation->name            = 'iDEAL';
                $oPaymentMethodTranslation->redirectPage    = ($oLocale->languageId == DEFAULT_LANGUAGE_ID ? '/kassa' : '/en/checkout');
                if ($oPaymentMethodTranslation->isValid()) {
                    PaymentMethodTranslationManager::savePaymentMethodTranslation($oPaymentMethodTranslation);
                } else {
                    _d($oPaymentMethodTranslation->getInvalidProps());
                    die('Can\'t create payment method translation for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                }
            }
        }
    }
}

# create delivery methods
if (moduleExists('orders') && $oDb->tableExists('delivery_methods') && $oDb->tableExists('delivery_method_translations')) {

    # create main delivery method
    if (!($oDeliveryMethodDeliver1 = DeliveryMethodManager::getDeliveryMethodByName('deliver'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing delivery method `deliver`';
        if ($bInstall) {
            $oDeliveryMethodDeliver1                = new DeliveryMethod();
            $oDeliveryMethodDeliver1->price         = 4.95;
            $oDeliveryMethodDeliver1->freeFromPrice = 50;
            $oDeliveryMethodDeliver1->system_name   = 'deliver';
            if ($oDeliveryMethodDeliver1->isValid()) {
                DeliveryMethodManager::saveDeliveryMethod($oDeliveryMethodDeliver1);
            } else {
                _d($oDeliveryMethodDeliver1->getInvalidProps());
                die('Can\'t create delivery method `deliver`');
            }
        }
    }

    if (!($oDeliveryMethodDeliver2 = DeliveryMethodManager::getDeliveryMethodByName('pick_up'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing delivery method `pick_up`';
        if ($bInstall) {
            $oDeliveryMethodDeliver2                = new DeliveryMethod();
            $oDeliveryMethodDeliver2->price         = 0;
            $oDeliveryMethodDeliver2->freeFromPrice = 0;
            $oDeliveryMethodDeliver2->system_name   = 'pick_up';
            if ($oDeliveryMethodDeliver2->isValid()) {
                DeliveryMethodManager::saveDeliveryMethod($oDeliveryMethodDeliver2);
            } else {
                _d($oDeliveryMethodDeliver2->getInvalidProps());
                die('Can\'t create delivery method `pick_up`');
            }
        }
    }

    # create delivery method translations
    foreach (LocaleManager::getLocalesByFilter(['showAll' => true]) as $oLocale) {
        if ($oDeliveryMethodDeliver1 && !($oDeliveryMethodTranslation = DeliveryMethodTranslationManager::getDeliveryMethodTranslationsByFilter(
                ['deliveryMethodId' => $oDeliveryMethodDeliver1->deliveryMethodId, 'languageId' => $oLocale->languageId]
            ))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing delivery method translation `deliver` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oDeliveryMethodTranslation                   = new DeliveryMethodTranslation();
                $oDeliveryMethodTranslation->deliveryMethodId = $oDeliveryMethodDeliver1->deliveryMethodId;
                $oDeliveryMethodTranslation->languageId       = $oLocale->languageId;
                $oDeliveryMethodTranslation->name             = ($oLocale->languageId == DEFAULT_LANGUAGE_ID ? 'Bezorgen' : 'Deliver');
                if ($oDeliveryMethodTranslation->isValid()) {
                    DeliveryMethodTranslationManager::saveDeliveryMethodTranslation($oDeliveryMethodTranslation);
                } else {
                    _d($oDeliveryMethodTranslation->getInvalidProps());
                    die('Can\'t create delivery method translation for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                }
            }
        }

        if ($oDeliveryMethodDeliver2 && !($oDeliveryMethodTranslation = DeliveryMethodTranslationManager::getDeliveryMethodTranslationsByFilter(
                ['deliveryMethodId' => $oDeliveryMethodDeliver2->deliveryMethodId, 'languageId' => $oLanguage->languageId]
            ))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing delivery method translation `pick_up` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oDeliveryMethodTranslation                   = new DeliveryMethodTranslation();
                $oDeliveryMethodTranslation->deliveryMethodId = $oDeliveryMethodDeliver2->deliveryMethodId;
                $oDeliveryMethodTranslation->languageId       = $oLocale->languageId;
                $oDeliveryMethodTranslation->name             = ($oLocale->languageId == DEFAULT_LANGUAGE_ID ? 'Afhalen' : 'Pick up');
                if ($oDeliveryMethodTranslation->isValid()) {
                    DeliveryMethodTranslationManager::saveDeliveryMethodTranslation($oDeliveryMethodTranslation);
                } else {
                    _d($oDeliveryMethodTranslation->getInvalidProps());
                    die('Can\'t create delivery method translation for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                }
            }
        }
    }

    # create relations
    if ($oDb->tableExists('delivery_method_payment_method_relations')) {
        if (isset($oPaymentMethodIDEAL) && isset($oDeliveryMethodDeliver1) && isset($oDeliveryMethodDeliver2)) {
            if ($bInstall) {
                $sQuery = ' INSERT IGNORE INTO `delivery_method_payment_method_relations`(`deliveryMethodId`,`paymentMethodId`) VALUES 
                                (' . $oDeliveryMethodDeliver1->deliveryMethodId . ',' . $oPaymentMethodIDEAL->paymentMethodId . '),
                                (' . $oDeliveryMethodDeliver2->deliveryMethodId . ',' . $oPaymentMethodIDEAL->paymentMethodId . ');';

                $oDb->query($sQuery, QRY_NORESULT);
            }
        }
    }
}
