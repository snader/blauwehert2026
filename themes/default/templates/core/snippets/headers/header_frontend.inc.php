<!-- TOP TRUST BAR -->
  <div class="top-bar">
    <div class="container">
      <div class="top-bar-inner">
        <div class="top-bar-links">
          <a href="tel:<?= Settings::get('clientPhone') ?>" class="top-bar-link">
            <span>📞</span> <?= Settings::get('clientPhone') ?>
          </a>
          <a href="mailto:<?= Settings::get('clientEmail') ?>" class="top-bar-link">
            <span>✉️</span> <?= Settings::get('clientEmail') ?>
          </a>
          <span class="top-bar-link">
            <span>📍</span> Beschikbaar in heel Nederland
          </span>
        </div>
        <div class="top-bar-badges">
          <span class="badge-trust">🎓 StiBON Internationaal Biersommelier</span>
          <span class="badge-trust">⭐ 4,9 / 5 (<?= 450 ?>+ Deelnemers)</span>
        </div>
      </div>
    </div>
  </div>

<!-- SITE HEADER -->
  <header class="site-header">
    <div class="container">
      <div class="header-inner">
        <a href="/" class="site-logo" aria-label="Wakker Bier Homepage">
          <img src="/themes/default/images/logo-wakker-bier-wit.png" alt="Wakker Bier Logo" width="52" height="52">
          <div class="site-logo-text">
            <span class="logo-main"><?= strtoupper(Settings::get('clientName')) ?></span>
            <span class="logo-sub">Bierproeverijen</span>
          </div>
        </a>
        <?php include getSiteSnippet('navigation'); ?>
        <div class="header-cta-group">
          <a href="/#prijscalculator" class="btn btn-primary btn-sm">Offerte Berekenen</a>
          <button class="mobile-menu-toggle" aria-label="Menu openen">☰</button>
        </div>
      </div>
    </div>
  </header>