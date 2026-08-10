
<div class="container-fluid">

  <div class="row">
    <div class="col-lg-6 col-md-12">
      <?php
      include_once getAdminSnippet('tanks_dashboard', 'tanks');
      ?>
    </div>
    <div class="col-lg-6 col-md-12">
      <?php
      // replace brewlog_alerts and dryhop_alerts with a single alert snippet that shows all alerts, grouped by type
      include_once getAdminSnippet('dashboard_alerts', 'brewlogs');
      
      include_once getAdminSnippet('product_alerts', 'products');    
      
      ?>

    </div>
  </div>

</div>

