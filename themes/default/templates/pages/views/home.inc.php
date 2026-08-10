

    
    <section class="hero section-padding section-hero">
      <div class="container hero-grid">
        <div class="hero-copy" data-animate>
          <span class="eyebrow">Specialty Coffee Roasters</span>
          <h1><?= $oPage->title ?></h1>
          <p>From bean to cup, we obsess over every detail. Experience specialty coffee the way it was meant to be — fresh-roasted, expertly brewed, and served with care.</p>
          <div class="hero-actions">
            <a href="#menu" class="btn btn-primary">View Our Menu</a>
            <a href="/shop" class="btn btn-secondary">Order Online</a>
          </div>
        </div>

        <div class="hero-visual" data-animate data-delay="100">
          <div class="hero-card">
            <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/hero-coffee.jpg" alt="Specialty coffee">
            <div class="hero-glow hero-glow-accent"></div>
            <div class="hero-glow hero-glow-soft"></div>
          </div>
        </div>
      </div>

      <a href="#menu" class="scroll-link" data-animate>
        <span>Scroll</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 14l-7 7-7-7"></path>
          <path d="M12 3v18"></path>
        </svg>
      </a>
    </section>

    <section id="menu" class="section-padding section-menu">
      <div class="container">
        <div class="section-header" data-animate>
          <h2>Our Menu</h2>
          <p>Handcrafted drinks and fresh-baked goods, made with love</p>
        </div>

        <div class="menu-categories">
          <article class="category-card" data-animate data-delay="0">
            <div class="category-header">
              <div>
                <h3>Espresso Drinks</h3>
                <p>Crafted with our signature house blend</p>
              </div>
            </div>
            <div class="item-grid">
              <article class="menu-item">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/espresso.jpg" alt="Espresso">
                <div>
                  <div class="item-title">
                    <h4>Espresso</h4>
                    <span>$3.50</span>
                  </div>
                  <p>Rich, bold, and perfectly extracted</p>
                </div>
              </article>
              <article class="menu-item">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/cortado.jpg" alt="Cortado">
                <div>
                  <div class="item-title">
                    <h4>Cortado</h4>
                    <span>$4.50</span>
                  </div>
                  <p>Equal parts espresso and steamed milk</p>
                </div>
              </article>
              <article class="menu-item popular-card">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/cappuccino.jpg" alt="Cappuccino">
                <div>
                  <div class="item-title">
                    <h4>Cappuccino</h4>
                    <span>$5.00</span>
                  </div>
                  <p>Velvety foam with a double shot</p>
                  <span class="badge">Popular</span>
                </div>
              </article>
            </div>
          </article>

          <article class="category-card" data-animate data-delay="100">
            <div class="category-header">
              <div>
                <h3>Pour Overs</h3>
                <p>Single-origin beans, brewed to order</p>
              </div>
            </div>
            <div class="item-grid">
              <article class="menu-item">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/ethiopian.jpg" alt="Ethiopian Yirgacheffe">
                <div>
                  <div class="item-title">
                    <h4>Ethiopian Yirgacheffe</h4>
                    <span>$6.00</span>
                  </div>
                  <p>Floral, citrus, and tea-like</p>
                  <div class="tag-row">
                    <span>Bergamot</span>
                    <span>Jasmine</span>
                    <span>Lemon</span>
                  </div>
                </div>
              </article>
              <article class="menu-item popular-card">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/colombian.jpg" alt="Colombian Huila">
                <div>
                  <div class="item-title">
                    <h4>Colombian Huila</h4>
                    <span>$5.50</span>
                  </div>
                  <p>Caramel, red apple, and milk chocolate</p>
                  <div class="tag-row">
                    <span>Popular</span>
                    <span>Caramel</span>
                    <span>Apple</span>
                    <span>Chocolate</span>
                  </div>
                </div>
              </article>
              <article class="menu-item">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/guatemalan.jpg" alt="Guatemalan Antigua">
                <div>
                  <div class="item-title">
                    <h4>Guatemalan Antigua</h4>
                    <span>$5.50</span>
                  </div>
                  <p>Smoky, cocoa, and spice</p>
                  <div class="tag-row">
                    <span>Cocoa</span>
                    <span>Smoke</span>
                    <span>Spice</span>
                  </div>
                </div>
              </article>
            </div>
          </article>

          <article class="category-card" data-animate data-delay="200">
            <div class="category-header">
              <div>
                <h3>Cold Drinks</h3>
                <p>Refreshing and smooth</p>
              </div>
            </div>
            <div class="item-grid">
              <article class="menu-item popular-card">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/cold-brew.jpg" alt="Cold Brew">
                <div>
                  <div class="item-title">
                    <h4>Cold Brew</h4>
                    <span>$5.00</span>
                  </div>
                  <p>18-hour steeped perfection</p>
                  <span class="badge">Popular</span>
                </div>
              </article>
              <article class="menu-item">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/iced-latte.jpg" alt="Iced Latte">
                <div>
                  <div class="item-title">
                    <h4>Iced Latte</h4>
                    <span>$5.50</span>
                  </div>
                  <p>Espresso over ice with cold milk</p>
                </div>
              </article>
              <article class="menu-item">
                <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/nitro.jpg" alt="Nitro Cold Brew">
                <div>
                  <div class="item-title">
                    <h4>Nitro Cold Brew</h4>
                    <span>$6.00</span>
                  </div>
                  <p>Creamy, cascading, on tap</p>
                </div>
              </article>
            </div>
          </article>
        </div>

        <div class="section-cta" data-animate>
          <a href="/menu" class="btn btn-secondary outline">View Full Menu</a>
        </div>
      </div>
    </section>

    <section id="story" class="section-padding section-story">
      <div class="container story-grid">
        <div class="story-image" data-animate>
          <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/our-story.jpg" alt="Our story">
          <div class="story-stats">
            <div>
              <span>15+</span>
              <p>Years Roasting</p>
            </div>
            <div>
              <span>12</span>
              <p>Origin Countries</p>
            </div>
            <div>
              <span>3</span>
              <p>Portland Locations</p>
            </div>
            <div>
              <span>50k+</span>
              <p>Happy Customers</p>
            </div>
          </div>
        </div>

        <div class="story-copy" data-animate data-delay="200">
          <span class="eyebrow accent">Est. 2009</span>
          <h2>From Portland, With Love</h2>
          <p>What started as a small cart at the Saturday Market has grown into three beloved locations across Portland. But our mission remains the same: to source exceptional coffees, roast them with care, and share them with our community.</p>
          <p>Every bag we roast tells a story — of the farmers who grew it, the land that nurtured it, and the hands that brought it to your cup. We believe coffee should be more than just caffeine; it should be an experience.</p>
          <a href="/about" class="btn btn-primary">Learn More About Us</a>
        </div>
      </div>
    </section>

    <section id="process" class="section-padding section-process">
      <div class="container">
        <div class="section-header" data-animate>
          <h2>From Origin to Cup</h2>
          <p>Our commitment to quality at every step</p>
        </div>

        <div class="process-timeline">
          <div class="timeline-line" aria-hidden="true"></div>
          <article class="process-step" data-animate data-delay="0">
            <div class="step-marker">1</div>
            <div class="step-content">
              <div class="step-meta">
                <span class="step-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.055 11H5a2 2 0 0 1 2 2v1a2 2 0 0 0 2 2 2 2 0 0 1 2 2v2.945M8 3.935V5.5A2.5 2.5 0 0 0 10.5 8h.5a2 2 0 0 1 2 2 2 2 0 1 0 4 0 2 2 0 0 1 2-2h1.064M15 20.488V18a2 2 0 0 1 2-2h3.064M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"></path></svg>
                </span>
                <span>Step 1</span>
              </div>
              <h3>Sourcing</h3>
              <p>We partner directly with farmers across the coffee belt, ensuring fair prices and sustainable practices.</p>
            </div>
          </article>
          <article class="process-step" data-animate data-delay="100">
            <div class="step-marker">2</div>
            <div class="step-content">
              <div class="step-meta">
                <span class="step-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.657 18.657A8 8 0 0 1 6.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0 1 20 13a7.975 7.975 0 0 1-2.343 5.657z"></path><path d="M9.879 16.121A3 3 0 1 0 12.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                </span>
                <span>Step 2</span>
              </div>
              <h3>Roasting</h3>
              <p>Small-batch roasting in our Portland facility, where we develop each bean to its full potential.</p>
            </div>
          </article>
          <article class="process-step" data-animate data-delay="200">
            <div class="step-marker">3</div>
            <div class="step-content">
              <div class="step-meta">
                <span class="step-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.428 15.428a2 2 0 0 0-1.022-.547l-2.387-.477a6 6 0 0 0-3.86.517l-.318.158a6 6 0 0 1-3.86.517L6.05 15.21a2 2 0 0 0-1.806.547M8 4h8l-1 1v5.172a2 2 0 0 0 .586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 0 0 9 10.172V5L8 4z"></path></svg>
                </span>
                <span>Step 3</span>
              </div>
              <h3>Cupping</h3>
              <p>Rigorous quality control through daily cupping sessions to ensure consistency and excellence.</p>
            </div>
          </article>
          <article class="process-step" data-animate data-delay="300">
            <div class="step-marker">4</div>
            <div class="step-content">
              <div class="step-meta">
                <span class="step-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.318 6.318a4.5 4.5 0 0 0 0 6.364L12 20.364l7.682-7.682a4.5 4.5 0 0 0-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 0 0-6.364 0z"></path></svg>
                </span>
                <span>Step 4</span>
              </div>
              <h3>Serving</h3>
              <p>From our hands to yours, every cup is crafted with care by our trained baristas.</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="section-padding section-product">
      <div class="container">
        <div class="section-header" data-animate>
          <h2>Take It Home</h2>
          <p>Fresh-roasted beans delivered to your door</p>
        </div>

        <div class="product-grid">
          <article class="product-card" data-animate data-delay="0">
            <span class="badge badge-shelf">Bestseller</span>
            <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/house-blend.jpg" alt="House Blend">
            <div class="product-copy">
              <h3>House Blend</h3>
              <p>Our signature blend with notes of chocolate, caramel, and toasted nuts</p>
              <div class="product-footer">
                <span>$18.00 / 12oz bag</span>
                <button class="btn btn-small">Add to Cart</button>
              </div>
            </div>
          </article>

          <article class="product-card secondary" data-animate data-delay="100">
            <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/single-origin.jpg" alt="Single Origin - Ethiopia">
            <div class="product-copy">
              <h3>Single Origin - Ethiopia</h3>
              <p>Light roast with bright citrus and floral notes</p>
              <div class="product-footer">
                <span>$22.00 / 12oz bag</span>
                <button class="btn btn-small">Add to Cart</button>
              </div>
            </div>
          </article>

          <article class="product-card" data-animate data-delay="200">
            <img src="<?php echo CLIENT_HTTP_URL; ?>/themes/default/images/decaf-blend.jpg" alt="Decaf Blend">
            <div class="product-copy">
              <h3>Decaf Blend</h3>
              <p>All the flavor, none of the buzz</p>
              <div class="product-footer">
                <span>$19.00 / 12oz bag</span>
                <button class="btn btn-small">Add to Cart</button>
              </div>
            </div>
          </article>
        </div>

        <div class="section-cta" data-animate>
          <a href="/shop" class="btn btn-secondary outline">Shop All Coffee</a>
        </div>
      </div>
    </section>

    <section class="section-padding">
      <div class="container">
        <div class="section-header" data-animate>
          <h2>What People Are Saying</h2>
          <p>Join thousands of happy coffee lovers</p>
        </div>

        <div class="testimonial-grid">
          <article class="testimonial-card" data-animate data-delay="0">
            <div class="stars">
              <span>★★★★★</span>
            </div>
            <blockquote>
              <p>"The best coffee I've ever had. Their Ethiopian pour-over is transcendent."</p>
            </blockquote>
            <div class="reviewer">
              <div class="reviewer-icon">M</div>
              <div>
                <p class="reviewer-name">Michael Chen</p>
                <p class="reviewer-role">Coffee Enthusiast</p>
              </div>
            </div>
          </article>

          <article class="testimonial-card" data-animate data-delay="100">
            <div class="stars">
              <span>★★★★★</span>
            </div>
            <blockquote>
              <p>"Finally, a coffee shop that takes their craft seriously. Worth every penny."</p>
            </blockquote>
            <div class="reviewer">
              <div class="reviewer-icon">S</div>
              <div>
                <p class="reviewer-name">Sarah Williams</p>
                <p class="reviewer-role">Food Blogger</p>
              </div>
            </div>
          </article>

          <article class="testimonial-card" data-animate data-delay="200">
            <div class="stars">
              <span>★★★★★</span>
            </div>
            <blockquote>
              <p>"I've been a subscriber for 2 years. The beans arrive fresh, and the variety keeps me excited."</p>
            </blockquote>
            <div class="reviewer">
              <div class="reviewer-icon">D</div>
              <div>
                <p class="reviewer-name">David Park</p>
                <p class="reviewer-role">Home Barista</p>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="section-padding section-subscribe">
      <div class="container subscribe-card" data-animate>
        <div class="subscribe-content">
          <div class="subscribe-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8"></path>
              <path d="M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div>
            <h2>Join the Club</h2>
            <p>Get exclusive offers, brewing tips, and first access to new roasts. Plus, 10% off your first order.</p>
          </div>
        </div>
        <form class="subscribe-form" id="subscribe-form">
          <input type="email" name="email" placeholder="Enter your email" required>
          <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
        <p class="subscribe-note">Join 5,000+ coffee lovers. Unsubscribe anytime.</p>
      </div>
    </section>
