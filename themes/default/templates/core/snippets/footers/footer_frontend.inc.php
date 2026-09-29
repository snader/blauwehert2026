

  <!-- SITE FOOTER -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div>
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
            <img src="/themes/default/images/logo-wakker-bier-wit.png" alt="Wakker Bier" width="40" height="40">
            <span style="font-family:var(--font-heading); font-size:1.3rem; font-weight:800; color:#fff;">WAKKER BIER</span>
          </div>
          <p style="font-size:0.9rem; line-height:1.6; margin-bottom:16px;">
            Wakker Bier is het initiatief van Internationaal Biersommelier Sander Voorn ter bevordering van de beleving van speciaalbier in Nederland.
          </p>
          <div class="rating-stars">★★★★★</div>
          <span style="font-size:0.82rem; color:var(--text-muted); display:block; margin-top:4px;">5.0 van 5 op basis van 100+ deelnemers</span>
        </div>

        <div>
          <h4 class="footer-title">Bierproeverijen</h4>
          <ul class="footer-links">
            <li><a href="/bierproeverij-thuis">Bierproeverij Thuis</a></li>
            <li><a href="/bierproeverij-zakelijk">Bierproeverij op het Werk</a></li>
            <li><a href="/#pakketten">Bier &amp; Hapjes Pairing</a></li>
            <li><a href="/#pakketten">Bier &amp; Kaas Proeverij</a></li>
            <li><a href="/agenda">Agenda Publieke Events</a></li>
          </ul>
        </div>

        <div>
          <h4 class="footer-title">Informatie &amp; Regio's</h4>
          <ul class="footer-links">
            <li><a href="/over-biersommelier">Over Sander Voorn</a></li>
            <li><a href="/locaties">Bierproeverij Amsterdam</a></li>
            <li><a href="/locaties">Bierproeverij Utrecht</a></li>
            <li><a href="/locaties">Bierproeverij Haarlem</a></li>
            <li><a href="/locaties">Bierproeverij Aalsmeer</a></li>
            <li><a href="/#faq">Veelgestelde Vragen</a></li>
          </ul>
        </div>

        <div>
          <h4 class="footer-title">Contact</h4>
          <p style="margin-bottom:8px;"><strong>Sander Voorn</strong></p>
          <p style="margin-bottom:8px;">📞 <a href="tel:<?= Settings::get('clientPhone') ?>"><?= Settings::get('clientPhone') ?></a></p>
          <p style="margin-bottom:8px;">✉️ <a href="mailto:<?= Settings::get('clientEmail') ?>"><?= Settings::get('clientEmail') ?></a></p>
          <p style="margin-bottom:8px;">📍 <?= Settings::get('clientAddress') ?></p>
          <p style="font-size:0.8rem; color:var(--text-muted); margin-top:12px;">KVK: 82157219 | BTW: NL003333769B95</p>
        </div>
      </div>

      <div class="footer-bottom">
        <div>&copy; <?= date('Y') ?> Wakker Bier (WakkerBier.nl). Alle rechten voorbehouden.</div>
        <div style="display:flex; gap:16px;">
          <a href="/contact">Contact</a>
          <a href="/sitemap.xml">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- FLOATING WHATSAPP BUTTON -->
  <a href="https://wa.me/<?= Settings::get('clientPhone') ?>?text=Hallo%20Sander,%20ik%20heb%20een%20vraag%20over%20een%20bierproeverij!" class="floating-whatsapp" target="_blank" rel="noopener" aria-label="Chat direct via WhatsApp met Biersommelier Sander Voorn">
    <span class="floating-whatsapp-tooltip">Direct WhatsApp contact met Sander</span>
    <img src="/themes/default/images/whatsapp-logo.png" alt="WhatsApp" width="34" height="34">
  </a>