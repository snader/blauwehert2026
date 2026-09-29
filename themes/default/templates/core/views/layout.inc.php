<?php require_once dirname(__DIR__, 5) . '/inc/pricing.php'; ?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($oPage->title) ?></title>
  
  <!-- SEO & Search Metadata -->
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="Sander Voorn - Internationaal Biersommelier">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <link rel="canonical" href="">

  <!-- Open Graph / Social -->
  <meta property="og:locale" content="nl_NL">
  <meta property="og:type" content="website">
  <meta property="og:title" content="">
  <meta property="og:description" content="">
  <meta property="og:url" content="">
  <meta property="og:site_name" content="Wakker Bier">
  <meta property="og:image" content="..../images/hero-bierproeverij.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="">
  <meta name="twitter:description" content="">
  <meta name="twitter:image" content="..../images/hero-bierproeverij.jpg">

  <!-- Google Fonts & Stylesheet -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/themes/default/css/style.css">

  <!-- Schema.org JSON-LD Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "LocalBusiness",
        "@id": "https://wakkerbier.nl/#organization",
        "name": "Wakker Bier",
        "alternateName": "WakkerBier.nl",
        "url": "https://wakkerbier.nl/",
        "logo": "/themes/default/images/logo-wakker-bier-wit.png",
        "image": "/themes/default/images/hero-bierproeverij.jpg",
        "description": "Wakker Bier organiseert interactieve bierproeverijen en bierworkshops op locatie in heel Nederland, begeleid door gediplomeerd Internationaal Biersommelier Sander Voorn.",
        "telephone": "+31616140742",
        "email": "info@wakkerbier.nl",
        "priceRange": "Vanaf €17 per persoon",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Duikerstraat 38",
          "addressLocality": "Aalsmeer",
          "postalCode": "1432JW",
          "addressCountry": "NL"
        },
        "geo": {
          "@type": "GeoCoordinates",
          "latitude": 52.2689,
          "longitude": 4.7578
        },
        "areaServed": [
          { "@type": "Country", "name": "Nederland" },
          { "@type": "City", "name": "Amsterdam" },
          { "@type": "City", "name": "Aalsmeer" },
          { "@type": "City", "name": "Utrecht" },
          { "@type": "City", "name": "Haarlem" },
          { "@type": "City", "name": "Amstelveen" },
          { "@type": "City", "name": "Den Haag" },
          { "@type": "City", "name": "Rotterdam" },
          { "@type": "City", "name": "Alkmaar" }
        ],
        "aggregateRating": {
          "@type": "AggregateRating",
          "ratingValue": "5.0",
          "reviewCount": "650",
          "bestRating": "5",
          "worstRating": "1"
        },
        "founder": {
          "@type": "Person",
          "@id": "https://wakkerbier.nl/#founder",
          "name": "Sander Voorn",
          "jobTitle": "Internationaal Biersommelier",
          "description": "Gediplomeerd Internationaal Biersommelier via de officiële StiBON bieropleiding."
        }
      },
      {
        "@type": "Service",
        "@id": "https://wakkerbier.nl/#service-thuis",
        "name": "Bierproeverij Aan Huis",
        "provider": { "@id": "https://wakkerbier.nl/#organization" },
        "serviceType": "Bierproeverij",
        "description": "Een gezellige en interactieve bierproeverij bij u thuis met 6 speciaalbieren, proefglazen en begeleiding door biersommelier Sander Voorn.",
        "offers": {
          "@type": "Offer",
          "price": "32.50",
          "priceCurrency": "EUR",
          "priceSpecification": {
            "@type": "UnitPriceSpecification",
            "price": "32.50",
            "priceCurrency": "EUR",
            "unitText": "per persoon (vanaf 8 personen)"
          }
        }
      }
    ]
  }
  </script>
</head>
<body>



  <!-- Navbar -->
  <!-- Header -->
  <?php include getSiteSnippet('headers/header_frontend'); ?>
        <!-- /Header -->
  <!-- /.navbar -->


  <!-- Content Wrapper. Contains page content -->
 <main>

    <?php
    // include the actual page with changable content
    include_once $oPageLayout->sViewPath;
    ?>
    
 </main>
  <!-- /.content-wrapper -->

  <?php include getSiteSnippet('footers/footer_frontend'); ?>
  

>

<!-- jQuery 
<script src="../../plugins/jquery/jquery.min.js"></script>-->
<script src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/js/script.js"></script>

</body>
</html>
