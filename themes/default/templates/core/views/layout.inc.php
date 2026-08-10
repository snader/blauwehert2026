
<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Artisan coffee roasters dedicated to sourcing, roasting, and serving the finest specialty coffees from around the world.">
  <title>Roast & Co.</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo CLIENT_HTTP_URL; ?>/themes/default/css/styles.css?v=2">
  <script>
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme === 'light') {
      document.documentElement.classList.remove('dark');
    } else {
      document.documentElement.classList.add('dark');
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
  

</div>
<!-- ./wrapper -->

<!-- jQuery 
<script src="../../plugins/jquery/jquery.min.js"></script>-->
<script src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/js/script.js"></script>

</body>
</html>
