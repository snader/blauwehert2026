<!-- HERO THUIS -->
    <section class="hero" style="min-height: 60vh;">
      <div class="container">
        <div class="hero-grid">
          <div>
            <div class="hero-tag"><span>🏠</span> Gewoon bij jou aan de keukentafel</div>
            <h1 class="hero-title"><?= $oPage->intro ?></h1>
            <p class="hero-lead">
              <?= $oPage->content ?>
            </p>
            <div class="hero-cta-box">
              <a href="#thuis-reserveren" class="btn btn-primary btn-lg">Reserveer Jouw Datum</a>
              <a href="https://wa.me/<?= $config['phone_raw'] ?>?text=Hallo%20Sander,%20ik%20wil%20graag%20een%20bierproeverij%20thuis%20boeken." class="btn btn-whatsapp btn-lg" target="_blank" rel="noopener">WhatsApp Direct 💬</a>
            </div>
          </div>
          <div>
            <div class="hero-image-wrapper">
              <img src="/themes/default/images/bierproeverij-gezellig.jpg" alt="Bierproeverij thuis met vrienden" width="580" height="420">
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- HOE WERKT HET THUIS -->
    <section class="section section-dark">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Stap voor Stap</span>
          <h2 class="section-title">Hoe Werkt een Bierproeverij aan Huis?</h2>
          <p class="section-description">
            Geen stress, geen afwas en geen voorbereiding. Zo eenvoudig en leuk is het:
          </p>
        </div>

        <div class="packages-grid">
          <div class="package-card">
            <div class="package-header">
              <span style="font-size:2rem; margin-bottom:10px; display:block;">1️⃣</span>
              <h3 class="package-name">Jij regelt de gasten</h3>
              <p class="package-desc">Verzamel minimaal <?= $pricing_config['min_persons'] ?> dorstige vrienden, familieleden of buren rond de keukentafel of in de tuin.</p>
            </div>
          </div>

          <div class="package-card featured">
            <div class="package-header">
              <span style="font-size:2rem; margin-bottom:10px; display:block;">2️⃣</span>
              <h3 class="package-name">Sander neemt alles mee</h3>
              <p class="package-desc">Speciaalbieren, officiële proefglazen, waterglazen, visuals en eventuele hapjes (kaas).</p>
            </div>
          </div>

          <div class="package-card">
            <div class="package-header">
              <span style="font-size:2rem; margin-bottom:10px; display:block;">3️⃣</span>
              <h3 class="package-name">2,5 uur Puur Genieten</h3>
              <p class="package-desc">6 bieren proeven, verhalen, humor en ontdekken. Na afloop gaat alles weer leeg mee retour!</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FORMULIER DIRECT -->
    <section class="section" id="thuis-reserveren">
      <div class="container container-narrow">
        <div class="section-header">
          <span class="section-subtitle">Vrijblijvende Aanvraag</span>
          <h2 class="section-title">Boek Jouw Bierproeverij aan Huis</h2>
          <p class="section-description">Vul je gegevens in en <?= $config['sommelier_name'] ?> neemt binnen 24 uur contact met je op.</p>
        </div>

        <div class="contact-form">
          <form id="bookingFormThuis">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Naam *</label>
                <input type="text" class="form-control" required placeholder="Je naam">
              </div>
              <div class="form-group">
                <label class="form-label">Telefoonnummer *</label>
                <input type="tel" class="form-control" required placeholder="06 - 12345678">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">E-mailadres *</label>
                <input type="email" class="form-control" required placeholder="naam@voorbeeld.nl">
              </div>
              <div class="form-group">
                <label class="form-label">Woonplaats</label>
                <input type="text" class="form-control" placeholder="Bijv. Aalsmeer / Amsterdam">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Aantal Personen</label>
                <input type="number" class="form-control" min="<?= $pricing_config['min_persons'] ?>" value="<?= $pricing_config['default_persons'] ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Arrangement</label>
                <select class="form-control">
                  <?php foreach ($packages as $pkg): ?>
                    <option><?= htmlspecialchars($pkg['name']) ?> (<?= format_price_vanaf($pkg['price_per_person']) ?> p.p.)</option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%; margin-top:10px;">
              Verstuur Aanvraag 🍻
            </button>
          </form>
        </div>
      </div>
    </section>