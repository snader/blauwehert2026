    <!-- HERO SECTION -->
    <section class="hero">
      <div class="container">
        <div class="hero-grid">
          <div class="hero-content">
            <div class="hero-tag">
              <span>🍺</span> Dé Interactieve Bierbeleving van Nederland
            </div>
            <h1 class="hero-title">
              Beleef een Unieke <span class="highlight">Bierproeverij</span> op Jouw Locatie
            </h1>
            <p class="hero-lead">
              Geen droge stoffige presentaties, maar een gezellige, smaakvolle ontdekkingsreis vol humor, kennis en prachtige verhalen. Begeleid door gediplomeerd <strong>Internationaal Biersommelier <?= $config['sommelier_name'] ?></strong>.
            </p>

            <div class="hero-cta-box">
              <a href="#prijscalculator" class="btn btn-primary btn-lg">
                Bereken Prijs &amp; Pakket
              </a>
              <a href="https://wa.me/<?= Settings::get('clientPhone') ?>?text=Hallo%20Sander,%20ik%20heb%20interesse%20in%20een%20bierproeverij!" class="btn btn-whatsapp btn-lg" target="_blank" rel="noopener">
                WhatsApp Direct 💬
              </a>
            </div>
            
            <?php
            if (!empty($aUsps)) { ?>
            <div class="hero-usps">
              <?php foreach ($aUsps as $oUsp) { ?>
                <div class="hero-usp-item">
                  <div class="usp-icon">✓</div>
                  <span><?= $oUsp->textLine ?></span>
                </div>
              <?php
              }
              ?>
            </div>
              <?php
            } ?>



          </div>

          <div class="hero-visual">
            <div class="hero-image-wrapper">
              <img src="/themes/default/images/hero-bierproeverij.jpg" alt="Gezellige bierproeverij met speciaalbieren en vrienden" width="580" height="480" fetchpriority="high">
            </div>
            <div class="hero-floating-card">
              <img src="/themes/default/images/sander-voorn-biersommelier.png" alt="Sander Voorn Internationaal Biersommelier" class="sommelier-avatar" width="52" height="52">
              <div class="floating-card-text">
                <h5>Sander Voorn</h5>
                <p>Gediplomeerd Internationaal Biersommelier</p>
                <div class="rating-stars">★★★★★</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- AI SEARCH DIRECT ANSWER BLOCK (GEO) -->
    <section class="section" style="padding: 40px 0 0;">
      <div class="container">
        <div class="ai-answer-box">
          <div class="ai-answer-header">
            <span class="ai-pill">Kort &amp; Krachtig Overzicht</span>
            <h2 class="ai-answer-title">Wat kun je verwachten van een bierproeverij met Wakker Bier?</h2>
          </div>
          <div class="ai-answer-content">
            <p>
              Bij <strong><?= Settings::get('clientName') ?></strong> verzorgt gediplomeerd Internationaal Biersommelier Sander Voorn complete bierproeverijen op maat in heel Nederland (bij u thuis, op het werk of op feestlocaties). Tijdens de circa 2,5 uur durende interactieve sessie proeven deelnemers 6 met zorg geselecteerde speciaalbieren, vergezeld door anekdotes over biercultuur, brouwgeheimen en eventueel foodpairing (kazen en hapjes). Sander neemt alle proefglazen, waterglazen en presentatiematerialen mee en laat de locatie na afloop weer volledig schoon achter.
            </p>
          </div>
          <div class="ai-answer-highlights">
            <div class="ai-highlight-item">
              <strong>Tarief</strong>
              Vanaf <?= format_price($packages['klassiek']['price_per_person']) ?> p.p. (excl. btw)
            </div>
            <div class="ai-highlight-item">
              <strong>Groepsgrootte</strong>
              Vanaf <?= $pricing_config['min_persons'] ?> tot 100+ personen
            </div>
            <div class="ai-highlight-item">
              <strong>Duur</strong>
              Gemiddeld 2,5 uur vol interactie
            </div>
            <div class="ai-highlight-item">
              <strong>Locatie</strong>
              Heel Nederland (op eigen locatie)
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PAKKETTEN & PRIJZEN -->
    <section class="section section-dark" id="pakketten">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Transparante Tarieven</span>
          <h2 class="section-title">Onze Populaire Bierproeverij Pakketten</h2>
          <p class="section-description">
            Kies het arrangement dat past bij jouw gezelschap. Elk pakket bevat 6 speciale bieren, professionele proefglazen en bevlogen begeleiding door Biersommelier <?= $config['sommelier_name'] ?>.
          </p>
        </div>

        <div class="packages-grid">
          <!-- Pakket 1: Klassiek -->
          <div class="package-card">
            <div class="package-header">
              <h3 class="package-name"><?= $packages['klassiek']['short_name'] ?></h3>
              <p class="package-desc"><?= $packages['klassiek']['description'] ?></p>
            </div>
            <div class="package-price-wrap">
              <div class="package-price"><?= format_price_vanaf($packages['klassiek']['price_per_person']) ?></div>
              <div class="package-price-meta">per persoon (excl. btw, vanaf <?= $pricing_config['min_persons'] ?> pers.)</div>
            </div>
            <ul class="package-features">
              <?php foreach ($packages['klassiek']['features'] as $feature): ?>
                <li><span class="check">✓</span> <?= $feature ?></li>
              <?php endforeach; ?>
            </ul>
            <a href="#prijscalculator" class="btn btn-secondary" onclick="selectPackage('klassiek')"><?= $packages['klassiek']['cta_text'] ?></a>
          </div>

          <!-- Pakket 2: Met Hapjes (Featured) -->
          <div class="package-card featured">
            <span class="featured-badge"><?= $packages['hapjes']['badge'] ?> ⭐</span>
            <div class="package-header">
              <h3 class="package-name"><?= $packages['hapjes']['short_name'] ?></h3>
              <p class="package-desc"><?= $packages['hapjes']['description'] ?></p>
            </div>
            <div class="package-price-wrap">
              <div class="package-price"><?= format_price_vanaf($packages['hapjes']['price_per_person']) ?></div>
              <div class="package-price-meta">per persoon (excl. btw, vanaf <?= $pricing_config['min_persons'] ?> pers.)</div>
            </div>
            <ul class="package-features">
              <?php foreach ($packages['hapjes']['features'] as $feature): ?>
                <li><span class="check">✓</span> <?= $feature ?></li>
              <?php endforeach; ?>
            </ul>
            <a href="#prijscalculator" class="btn btn-primary" onclick="selectPackage('hapjes')"><?= $packages['hapjes']['cta_text'] ?></a>
          </div>

          <!-- Pakket 3: Met Kaas -->
          <div class="package-card">
            <div class="package-header">
              <h3 class="package-name"><?= $packages['kaas']['short_name'] ?></h3>
              <p class="package-desc"><?= $packages['kaas']['description'] ?></p>
            </div>
            <div class="package-price-wrap">
              <div class="package-price"><?= format_price_vanaf($packages['kaas']['price_per_person']) ?></div>
              <div class="package-price-meta">per persoon (excl. btw, vanaf <?= $pricing_config['min_persons'] ?> pers.)</div>
            </div>
            <ul class="package-features">
              <?php foreach ($packages['kaas']['features'] as $feature): ?>
                <li><span class="check">✓</span> <?= $feature ?></li>
              <?php endforeach; ?>
            </ul>
            <a href="#prijscalculator" class="btn btn-secondary" onclick="selectPackage('kaas')"><?= $packages['kaas']['cta_text'] ?></a>
          </div>

        </div>
      </div>
    </section>

    <!-- INTERACTIEVE PRIJSCALCULATOR MET GROEPSKORTING -->
    <section class="section" id="prijscalculator">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Directe Indicatie</span>
          <h2 class="section-title">Bereken Jouw Bierproeverij Prijs</h2>
          <p class="section-description">
            Kies het aantal personen en je gewenste arrangement. Je ziet direct de investering inclusief automatische <strong>5% groepskorting vanaf persoon 21</strong>.
          </p>
        </div>

        <div class="calculator-container">
          <div class="calc-grid">
            <div class="calc-controls">
              <!-- Groepsgrootte -->
              <div class="calc-control-group">
                <div class="calc-label">
                  <span>1. Aantal Deelnemers</span>
                  <span id="calcPersonsCount" style="color: var(--amber-primary); font-size: 1.2rem; font-weight:700;">10</span>
                </div>
                <div class="calc-range-wrapper">
                  <input type="range" id="calcPersonsRange" class="calc-range" min="<?= $pricing_config['min_persons'] ?>" max="60" value="<?= $pricing_config['default_persons'] ?>" step="1">
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.78rem; color:var(--text-muted); margin-top:6px;">
                  <span><?= $pricing_config['min_persons'] ?> personen (min.)</span>
                  <span style="color:var(--gold-accent);">⭐ 21+ pers. (5% korting)</span>
                  <span>60+ personen</span>
                </div>
              </div>

              <!-- Arrangement keuze -->
              <div class="calc-control-group">
                <div class="calc-label">
                  <span>2. Kies Arrangement</span>
                </div>
                <div class="calc-options-grid">
                  <?php foreach ($packages as $pkgKey => $pkg): ?>
                    <button type="button" class="calc-option-btn<?= ($pkgKey === 'klassiek') ? ' active' : '' ?>" data-id="<?= $pkgKey ?>" data-price="<?= $pkg['price_per_person'] ?>" data-name="<?= htmlspecialchars($pkg['name']) ?>">
                      <span class="calc-option-title"><?= htmlspecialchars($pkg['short_name']) ?></span>
                      <span class="calc-option-sub"><?= format_price_vanaf($pkg['price_per_person']) ?> p.p. • <?= htmlspecialchars($pkg['subtitle']) ?></span>
                    </button>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <!-- Samenvatting Box -->
            <div class="calc-summary-box">
              <div>
                <h3 class="calc-summary-title">Boek een proeverij</h3>
                <div class="calc-breakdown-row">
                  <span>Gekozen pakket:</span>
                  <span id="calcSummaryPackage" style="color:#fff; font-weight:600;"><?= $packages['klassiek']['name'] ?></span>
                </div>
                <div class="calc-breakdown-row">
                  <span>Grootte groep:</span>
                  <span id="calcSummaryPersons" style="color:#fff; font-weight:600;">10 personen</span>
                </div>
                <div class="calc-breakdown-row">
                  <span>Basistarief per persoon:</span>
                  <span id="calcSummaryPerPerson" style="color:#fff; font-weight:600;"><?= format_price($packages['klassiek']['price_per_person']) ?></span>
                </div>

                <!-- Dynamische Groepskorting Rij -->
                <div class="calc-breakdown-row" id="calcDiscountRow" style="display:none; color:var(--gold-accent); background:rgba(217,119,6,0.12); padding:6px 10px; border-radius:6px; margin:4px 0;">
                  <span id="calcDiscountLabel">🎉 5% Groepskorting (persoon 21+):</span>
                  <span id="calcSummaryDiscount" style="font-weight:700;">-€0.00</span>
                </div>

                <div class="calc-breakdown-row">
                  <span>Subtotaal (excl. btw):</span>
                  <span id="calcSummarySubtotal">€260.00</span>
                </div>
                <div class="calc-breakdown-row">
                  <span>Btw (21%):</span>
                  <span id="calcSummaryVat">€54.60</span>
                </div>
                <div class="calc-breakdown-row total">
                  <span>Totaal (incl. btw):</span>
                  <span class="calc-total-amount" id="calcSummaryTotal">€314.60</span>
                </div>
                <p class="calc-note">
                  * Dit is een <u>prijsindicatie</u>; inclusief biersommelier, 6 bieren, glazen en mooi verhalen. Vanaf 21 personen geldt 5% korting op iedere extra deelnemer! Binnen regio Aalsmeer / Schiphol geen reiskosten; daarbuiten in overleg.
                </p>
              </div>

              <div style="display:flex; flex-direction:column; gap:10px;">
                <button type="button" id="calcApplyBtn" class="btn btn-primary">
                  Vul Formulier in met Deze Keuze 📝
                </button>
                <a id="calcWhatsAppBtn" href="https://wa.me/<?= $config['phone_raw'] ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
                  Vraag Direct via WhatsApp Aan 💬
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- BIERSTIJLEN & FOODPAIRING EXPLORER -->
    <section class="section" id="bierstijlen">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Smaak &amp; Kennis</span>
          <h2 class="section-title">Ontdek de Veelzijdigheid van Bierstijlen</h2>
          <p class="section-description">
            Tijdens de bierproeverijen duiken we in een breed spectrum aan stijlen. Hieronder zie je enkele favorieten die regelmatig de revue passeren.
          </p>
        </div>

        <div class="beer-styles-grid">
          <!-- Weizen -->
          <div class="beer-style-card">
            <div class="beer-style-header">
              <h3 class="beer-style-name">Hefe Weizen</h3>
              <span class="beer-tag">Fris &amp; Fruitig</span>
            </div>
            <p style="font-size: 0.9rem; color:var(--text-muted); margin-bottom:12px;">
              Duits tarwebier met herkenbare aroma's van banaan en kruidnagel door de speciale giststammen.
            </p>
            <div class="beer-specs">
              <span class="beer-spec-pill">ABV: 5.0 - 5.5%</span>
              <span class="beer-spec-pill">IBU: 12 - 15</span>
              <span class="beer-spec-pill">EBC: 6 - 12</span>
            </div>
            <div class="beer-pairing-tip">
              🧀 <strong>Pairing tip:</strong> Zachte geitenkaas of milde jonge boerenkaas.
            </div>
          </div>

          <!-- IPA -->
          <div class="beer-style-card">
            <div class="beer-style-header">
              <h3 class="beer-style-name">India Pale Ale (IPA)</h3>
              <span class="beer-tag">Hopbitter &amp; Citrus</span>
            </div>
            <p style="font-size: 0.9rem; color:var(--text-muted); margin-bottom:12px;">
              Rijk aan aromatische hoppen die tonen van tropisch fruit, grapefruit, hars en een frisse bitterheid geven.
            </p>
            <div class="beer-specs">
              <span class="beer-spec-pill">ABV: 6.0 - 7.5%</span>
              <span class="beer-spec-pill">IBU: 40 - 70</span>
              <span class="beer-spec-pill">EBC: 12 - 25</span>
            </div>
            <div class="beer-pairing-tip">
              🧀 <strong>Pairing tip:</strong> Pittige oude kaas, spicy bitterballen of blue stilton.
            </div>
          </div>

          <!-- Tripel -->
          <div class="beer-style-card">
            <div class="beer-style-header">
              <h3 class="beer-style-name">Belgische Tripel</h3>
              <span class="beer-tag">Krachtig &amp; Kruidig</span>
            </div>
            <p style="font-size: 0.9rem; color:var(--text-muted); margin-bottom:12px;">
              Goudblond, volmondig met lichte moutzoetheid, kruidigheid en een verwarmende alcoholtoets.
            </p>
            <div class="beer-specs">
              <span class="beer-spec-pill">ABV: 8.0 - 9.5%</span>
              <span class="beer-spec-pill">IBU: 25 - 35</span>
              <span class="beer-spec-pill">EBC: 8 - 14</span>
            </div>
            <div class="beer-pairing-tip">
              🧀 <strong>Pairing tip:</strong> Abdijkaas, droge worst of geroosterde noten.
            </div>
          </div>

          <!-- Stout / Porter -->
          <div class="beer-style-card">
            <div class="beer-style-header">
              <h3 class="beer-style-name">Imperial Stout</h3>
              <span class="beer-tag">Koffie &amp; Chocolade</span>
            </div>
            <p style="font-size: 0.9rem; color:var(--text-muted); margin-bottom:12px;">
              Diepzwart bier gebrouwen met donker gebrande mouten, resulterend in espresso- en pure chocoladetonen.
            </p>
            <div class="beer-specs">
              <span class="beer-spec-pill">ABV: 8.5 - 11.0%</span>
              <span class="beer-spec-pill">IBU: 45 - 65</span>
              <span class="beer-spec-pill">EBC: 80 - 140</span>
            </div>
            <div class="beer-pairing-tip">
              🍫 <strong>Pairing tip:</strong> Pure chocolade (70%+), blauwaderkaas of stoofvlees.
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- REVIEWS & ERVARINGEN -->
    <section class="section section-dark" id="reviews">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Beoordelingen</span>
          <h2 class="section-title">Wat Zeggen Deelnemers Over Onze Bierproeverijen?</h2>
          <p class="section-description">
            Met meer dan <strong><?= $config['review_count'] ?> tevreden bierproevers</strong> en een gemiddelde waardering van <strong>5.0 sterren</strong> garanderen wij een geslaagde middag of avond.
          </p>
        </div>

        <div class="reviews-grid">
          <div class="review-card">
            <div class="rating-stars" style="margin-bottom:14px;">★★★★★</div>
            <p class="review-text">
              "Sander neemt je mee in een verhaal over bieren, geschiedenis en smaken. Enthousiast deelt hij zijn rijke kennis over bieren en serveert hapjes per bier, waardoor smaken versterken of verfijnen. Letterlijk en figuurlijk een heerlijke ervaring!"
            </p>
            <div class="review-author-info">
              <div>
                <div class="author-name">Leonie Pastoors</div>
                <div class="author-meta">Papenveer • Vriendengroep</div>
              </div>
              <span style="font-size:0.8rem; color:var(--success-green); font-weight:700;">✓ Geverifieerd</span>
            </div>
          </div>

          <div class="review-card">
            <div class="rating-stars" style="margin-bottom:14px;">★★★★★</div>
            <p class="review-text">
              "Wat een onverwacht gezellige bierproeverij! Ik ben zelf helemaal geen echte bierdrinker, maar zelfs dan is dit ontzettend leuk om te doen. Sander vertelde op een leuke en enthousiaste manier over alle biertjes en bij ieder bier zat een heerlijk passend hapje."
            </p>
            <div class="review-author-info">
              <div>
                <div class="author-name">Carolien</div>
                <div class="author-meta">Aalsmeer • Familieproeverij</div>
              </div>
              <span style="font-size:0.8rem; color:var(--success-green); font-weight:700;">✓ Geverifieerd</span>
            </div>
          </div>

          <div class="review-card">
            <div class="rating-stars" style="margin-bottom:14px;">★★★★★</div>
            <p class="review-text">
              "Inmiddels heeft Sander een 4e uitverkochte proeverij bij Eetcafé 't Juffertje verzorgd voor 50 bierliefhebbers. En voor de 4e keer was het zeer geslaagd! Sander is een absolute aanrader met fantastische verhalen en mooie bieren."
            </p>
            <div class="review-author-info">
              <div>
                <div class="author-name">Eetcafé 't Juffertje</div>
                <div class="author-meta">Noordeinde • Publieksevent (50 pers.)</div>
              </div>
              <span style="font-size:0.8rem; color:var(--success-green); font-weight:700;">✓ Geverifieerd</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="section" id="faq">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Vraag &amp; Antwoord</span>
          <h2 class="section-title">Veelgestelde Vragen Over Bierproeverijen</h2>
          <p class="section-description">
            Vind snel antwoord op vragen over de organisatie, tarieven, locaties en mogelijkheden.
          </p>
        </div>

        <div class="city-search-box" style="margin-bottom: 24px;">
          <input type="text" id="faqSearchInput" class="city-search-input" placeholder="Zoek in veelgestelde vragen...">
          <span class="city-search-icon">🔍</span>
        </div>

        <div class="faq-filter-group">
          <button type="button" class="faq-filter-btn active" data-category="all">Alles</button>
          <button type="button" class="faq-filter-btn" data-category="proeverij">De Proeverij</button>
          <button type="button" class="faq-filter-btn" data-category="tarieven">Tarieven &amp; Boeking</button>
          <button type="button" class="faq-filter-btn" data-category="locatie">Locatie &amp; Praktisch</button>
        </div>

        <div class="faq-list">
          <div class="faq-item" data-category="proeverij">
            <button class="faq-question" type="button">
              <span>Wat houdt een bierproeverij van Wakker Bier precies in?</span>
              <span class="faq-icon">▼</span>
            </button>
            <div class="faq-answer">
              Een bierproeverij bij Wakker Bier is een interactieve en ontspannen ontdekkingsreis door de wereld van bier. Onder leiding van gediplomeerd Biersommelier Sander Voorn proef je 6 verschillende speciaalbieren. Je leert hoe je bier beoordeelt op geur, kleur en smaak, en hoort de bijzondere verhalen achter de brouwers en bierstijlen.
            </div>
          </div>

          <div class="faq-item" data-category="tarieven">
            <button class="faq-question" type="button">
              <span>Wat zijn de kosten per persoon?</span>
              <span class="faq-icon">▼</span>
            </button>
            <div class="faq-answer">
              De basis bierproeverij (6 bieren, proefglazen, verhalen) begint <?= format_price_vanaf($packages['klassiek']['price_per_person']) ?> p.p. (excl. btw). Uitbreiding met bijpassende hapjes <?= format_price_vanaf($packages['hapjes']['price_per_person']) ?> p.p., met ambachtelijke kazen <?= format_price_vanaf($packages['kaas']['price_per_person']) ?> p.p. Dit geldt bij een minimale groepsgrootte van <?= $pricing_config['min_persons'] ?> personen. Vanaf 21 personen geldt bovendien een automatische 5% groepskorting!
            </div>
          </div>

          <div class="faq-item" data-category="locatie">
            <button class="faq-question" type="button">
              <span>Wat moeten wij zelf regelen op de locatie?</span>
              <span class="faq-icon">▼</span>
            </button>
            <div class="faq-answer">
              Vrijwel niets! Sander neemt alle bieren, speciale proefglazen, waterglazen en presentatiemateriaal mee. Het enige wat je nodig hebt is een tafel met stoelen voor je gasten en wat water om glazen te spoelen. Na afloop gaat alles weer mee retour.
            </div>
          </div>

          <div class="faq-item" data-category="proeverij">
            <button class="faq-question" type="button">
              <span>Kunnen mensen die minder bier drinken of alcoholvrij willen ook meedoen?</span>
              <span class="faq-icon">▼</span>
            </button>
            <div class="faq-answer">
              Zeker! In overleg kunnen we uitstekende alcoholvrije of alcoholarme kwaliteitsbieren serveren. Bovendien merken we regelmatig dat mensen die dachten geen bierliefhebber te zijn, juist enorm verrast worden door de subtiele smaken van speciaalbier.
            </div>
          </div>

          <div class="faq-item" data-category="locatie">
            <button class="faq-question" type="button">
              <span>Komt Wakker Bier naar onze woonplaats?</span>
              <span class="faq-icon">▼</span>
            </button>
            <div class="faq-answer">
              Ja, wij verzorgen bierproeverijen door heel Nederland. Van Amsterdam, Utrecht, Haarlem en Aalsmeer tot Rotterdam, Den Haag, Alkmaar, Amersfoort en verder.
            </div>
          </div>
        </div>
      </div>
    </section>

    

    <!-- CONTACT & BOEKINGSFORMULIER -->
    <section class="section" id="contact">
      <div class="container">
        <div class="section-header">
          <span class="section-subtitle">Vrijblijvend Contact</span>
          <h2 class="section-title">Reserveer Jouw Datum of Vraag Informatie Aan</h2>
          <p class="section-description">
            Vul onderstaand formulier in en <?= $config['sommelier_name'] ?> neemt binnen 24 uur persoonlijk contact met je op. Direct antwoord nodig? Stuur gerust een WhatsApp bericht!
          </p>
        </div>

        <div class="contact-grid">
          <!-- Info kolom -->
          <div class="contact-info-card">
            <h3 style="font-size: 1.3rem; margin-bottom: 20px; color:#fff;">Contactgegevens</h3>
            
            <div class="contact-detail-row">
              <div class="contact-icon-box">📞</div>
              <div>
                <strong style="color:#fff; display:block;">Telefoon &amp; WhatsApp:</strong>
                <a href="tel:<?= $config['phone_raw'] ?>"><?= $config['phone_display'] ?></a>
              </div>
            </div>

            <div class="contact-detail-row">
              <div class="contact-icon-box">✉️</div>
              <div>
                <strong style="color:#fff; display:block;">E-mailadres:</strong>
                <a href="mailto:<?= $config['email'] ?>"><?= $config['email'] ?></a>
              </div>
            </div>

            <div class="contact-detail-row">
              <div class="contact-icon-box">🏠</div>
              <div>
                <strong style="color:#fff; display:block;">Locatie &amp; Werkgebied:</strong>
                <span><?= $config['address_street'] ?>, <?= $config['address_zip'] ?> <?= $config['address_city'] ?><br><small style="color:var(--text-muted);">(Proeverijen vinden plaats op jouw eigen locatie in heel Nederland)</small></span>
              </div>
            </div>

            <div class="contact-detail-row">
              <div class="contact-icon-box">📜</div>
              <div>
                <strong style="color:#fff; display:block;">KVK &amp; Btw:</strong>
                <span>KVK: 82157219 • BTW: NL003333769B95</span>
              </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-light);">
              <a href="https://wa.me/<?= $config['phone_raw'] ?>?text=Hallo%20Sander,%20ik%20wil%20graag%20de%20beschikbaarheid%20voor%20een%20bierproeverij%20checken." class="btn btn-whatsapp" style="width: 100%;" target="_blank" rel="noopener">
                <span>💬</span> WhatsApp Sander Direct
              </a>
            </div>
          </div>

          <!-- Formulier kolom -->
          <div class="contact-form">
            <div id="formSuccessMessage" style="display:none; background:rgba(16, 185, 129, 0.15); border:1px solid var(--success-green); border-radius:var(--radius-sm); padding:16px; margin-bottom:20px; color:#fff;">
              ✅ <strong>Bedankt voor je aanvraag!</strong> Sander neemt binnen 24 uur contact met je op. Wil je direct contact? Klik dan hieronder om via WhatsApp te schakelen:
              <a id="formDirectWhatsApp" href="#" target="_blank" class="btn btn-whatsapp btn-sm" style="display:none; margin-top:10px;">Open WhatsApp Bericht</a>
            </div>

            <form id="bookingForm">
              <div class="form-row">
                <div class="form-group">
                  <label for="formNaam" class="form-label">Je Naam *</label>
                  <input type="text" id="formNaam" class="form-control" placeholder="Bijv. Mark Jansen" required>
                </div>
                <div class="form-group">
                  <label for="formEmail" class="form-label">Je E-mailadres *</label>
                  <input type="email" id="formEmail" class="form-control" placeholder="naam@voorbeeld.nl" required>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="formTelefoon" class="form-label">Telefoonnummer *</label>
                  <input type="tel" id="formTelefoon" class="form-control" placeholder="06 - 12345678" required>
                </div>
                <div class="form-group">
                  <label for="formAantal" class="form-label">Aantal Personen</label>
                  <input type="number" id="formAantal" class="form-control" placeholder="Bijv. 12" min="<?= $pricing_config['min_persons'] ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="formPakket" class="form-label">Gewenst Pakket</label>
                  <select id="formPakket" class="form-control">
                    <?php foreach ($packages as $pkgKey => $pkg): ?>
                      <option value="<?= htmlspecialchars($pkg['name']) ?>"><?= htmlspecialchars($pkg['name']) ?> (<?= format_price($pkg['price_per_person']) ?> p.p.)</option>
                    <?php endforeach; ?>
                    <option value="Bierproeverij op het Werk / Teambuilding">Bierproeverij op het Werk (Op Maat)</option>
                    <option value="Maatwerk / Grote Groep">Maatwerk / Overig</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="formDatum" class="form-label">Voorkeursdatum (indicatie)</label>
                  <input type="date" id="formDatum" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="formBericht" class="form-label">Plaats / Locatie &amp; Eventuele wensen</label>
                <textarea id="formBericht" class="form-control" rows="4" placeholder="Bijv. Bierproeverij bij ons thuis in Amsterdam voor een 40e verjaardag. Graag ook 2 alcoholvrije opties."><?= isset($_GET["locatie"]) ? htmlentities($_GET["locatie"]) : '' ?></textarea>
              </div>

              <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                Vrijblijvende Aanvraag Versturen 🚀
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
