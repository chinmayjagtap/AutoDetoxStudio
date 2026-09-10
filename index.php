<?php
require __DIR__ . '/includes/bootstrap.php';

$packages = $pdo->query('SELECT * FROM packages ORDER BY display_order ASC')->fetchAll();
$flash = get_flash();
$today = date('Y-m-d');
$timeSlots = ['09:00 AM', '11:00 AM', '01:00 PM', '03:00 PM', '05:00 PM'];

$processSteps = [
  'Premium Touchless Foam Wash', 'Complete Interior Deep Cleaning', 'Full Steam Sanitization',
  'AC Vent Cleaning', 'Underbody Cleaning', 'Engine Bay Cleaning', 'Boot Space Cleaning',
  'Interior Plastic Restoration', 'Premium Plastic Dresser Application', 'Dashboard & Console Detailing',
  'Seat & Carpet Deep Cleaning', 'Door Pad Cleaning', 'Roof Lining Cleaning', 'Floor Mat Cleaning',
  'Odour Removal Treatment', 'Glass Cleaning & Polishing', 'Tyre & Alloy Cleaning', 'Tyre Dressing',
  'Exterior Plastic Restoration', 'Premium Liquid Wax Application', 'Paint Surface Decontamination',
  'Gap & Crevice Cleaning', 'Sanitization Treatment', 'Final Quality Inspection',
];

$services = [
  [
    'id' => 'detox', 'title' => 'Detox', 'icon' => 'droplet',
    'desc' => 'Complete touchless wash, sanitization &amp; interior-exterior detox for a showroom-fresh feel.',
    'pricing' => [
      ['label' => 'Hatchback', 'price' => 1999],
      ['label' => 'Sedan / Compact SUV', 'price' => 2499],
      ['label' => 'SUV / XUV', 'price' => 2999],
    ],
    'cta' => ['type' => 'book', 'label' => 'Book Now'],
  ],
  [
    'id' => 'rubbing', 'title' => 'Rubbing &amp; Polishing', 'icon' => 'shine',
    'desc' => 'Restore paint. Reveal the shine — swirl-free gloss correction.',
    'pricing' => [
      ['label' => 'Hatchback', 'price' => 2499],
      ['label' => 'Sedan / Compact SUV', 'price' => 2999],
      ['label' => 'SUV / XUV', 'price' => 3499],
    ],
    'cta' => ['type' => 'book', 'label' => 'Book Now'],
  ],
  [
    'id' => 'glass', 'title' => 'Glass Buffing', 'icon' => 'glass',
    'desc' => 'Crystal-clear visibility with a full 6-glass buffing treatment.',
    'pricing' => [
      ['label' => 'All 6 Glasses', 'price' => 2199],
    ],
    'cta' => ['type' => 'book', 'label' => 'Book Now'],
  ],
  [
    'id' => 'ceramic', 'title' => 'Ceramic Coating', 'icon' => 'shield',
    'flag' => '2 Yrs Warranty',
    'desc' => 'High-gloss, water &amp; dirt repellent armor with long-lasting protection.',
    'pricing' => [
      ['label' => 'Bike Ceramic Coating', 'price' => 3999, 'onwards' => true],
      ['label' => 'Car Ceramic Coating', 'price' => 10999, 'onwards' => true],
    ],
    'cta' => ['type' => 'view', 'label' => 'View Details', 'href' => '#ceramic-coating'],
  ],
  [
    'id' => 'graphene', 'title' => 'Graphene Coating', 'icon' => 'spark',
    'desc' => 'Next-generation protection with maximum performance &amp; durability.',
    'pricing' => [
      ['label' => 'Bike Graphene Coating', 'quote' => true],
      ['label' => 'Car Graphene Coating', 'quote' => true],
    ],
    'cta' => ['type' => 'view', 'label' => 'View Details', 'href' => '#graphene-coating'],
  ],
  [
    'id' => 'denting', 'title' => 'Denting &amp; Painting', 'icon' => 'wrench',
    'desc' => 'Dent-free panels &amp; factory-match paint, quoted after inspection.',
    'pricing' => [
      ['label' => 'All Vehicles', 'quote' => true, 'note' => 'Get Quote on Call'],
    ],
    'cta' => ['type' => 'quote', 'label' => 'Get Quote'],
  ],
  [
    'id' => 'underbody', 'title' => 'Underbody Coating', 'icon' => 'engine',
    'desc' => 'Anti-rust, anti-corrosion shield against monsoon splashes &amp; debris.',
    'pricing' => [
      ['label' => 'Hatchback', 'price' => 2499],
      ['label' => 'Sedan / Compact SUV', 'price' => 2999],
      ['label' => 'SUV / XUV', 'price' => 3499],
    ],
    'cta' => ['type' => 'book', 'label' => 'Book Now'],
  ],
];

$fallbackGallery = [
  ['img' => 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=1000&q=85', 'label' => 'Showroom Shine', 'sub' => 'Exterior Detail', 'type' => 'image'],
  ['img' => 'https://images.unsplash.com/photo-1592840062661-a5a7f78e2056?auto=format&fit=crop&w=1000&q=85', 'label' => 'Exterior Detail', 'sub' => 'Foam & Polish', 'type' => 'image'],
  ['img' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1000&q=85', 'label' => 'Studio Portfolio', 'sub' => 'Full Detox', 'type' => 'image'],
  ['img' => 'https://images.unsplash.com/photo-1518987048-93e29699e79a?auto=format&fit=crop&w=1000&q=85', 'label' => 'Studio Session', 'sub' => 'Full Detox', 'type' => 'image'],
  ['img' => 'https://images.unsplash.com/photo-1519245659620-e859806a8d3b?auto=format&fit=crop&w=1000&q=85', 'label' => 'Detailing Bay', 'sub' => 'Behind The Scenes', 'type' => 'image'],
  ['img' => 'https://images.unsplash.com/photo-1547038577-da80abbc4f19?auto=format&fit=crop&w=1000&q=85', 'label' => 'Weekend Ready', 'sub' => 'Paint Correction', 'type' => 'image'],
];
try {
  $galleryItems = $pdo->query("SELECT id, media_type AS type, file_path AS img, title AS label, subtitle AS sub FROM gallery_media WHERE is_active=1 ORDER BY RAND() LIMIT 18")->fetchAll();
} catch (Throwable $e) {
  $galleryItems = [];
}
if (!$galleryItems) $galleryItems = $fallbackGallery;
shuffle($galleryItems);

try {
  $siteSettings = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (Throwable $e) {
  $siteSettings = [];
}
$enquiryEnabled = ($siteSettings['enquiry_popup_enabled'] ?? '1') === '1';
$enquiryTitle = $siteSettings['enquiry_popup_title'] ?? 'Need help choosing the right service?';
$enquiryMessage = $siteSettings['enquiry_popup_message'] ?? 'Leave your details and our team will call you with the right recommendation.';

$reviews = [
  ['name' => 'Rohan Kulkarni', 'text' => 'The 23-step process is no gimmick — my Creta genuinely looked showroom-new. The steam sanitization got rid of a smell I\'d given up on.', 'img' => 24],
  ['name' => 'Ananya Deshpande', 'text' => 'Booked the Sedan package for my City. Professional team, spotless interior, and they finished exactly on time. Worth every rupee.', 'img' => 47],
  ['name' => 'Vikram Shah', 'text' => 'Best detailing studio in PCMC, hands down. The engine bay and underbody cleaning are things other car washes just skip.', 'img' => 33],
  ['name' => 'Sneha Patil', 'text' => 'My Swift\'s paint looks glossier than the day I bought it. The liquid wax finish is next level. Already booked my next slot.', 'img' => 45],
  ['name' => 'Aditya Rane', 'text' => 'Booked the SUV package for my XUV700 — engine bay, underbody, interior, everything covered. Transparent pricing, no upsell pressure.', 'img' => 15],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
  <meta name="theme-color" content="#0C1E3D" />
  <title>Auto Detox Studio — India's First 23-Step Car Detox Studio</title>
  <meta name="description" content="India's First Car Detox Studio. A 23-step deep cleaning, sanitization & restoration process for hatchbacks, sedans and SUVs. Near Rajyog Petrol Pump, Walhekarwadi Road, PCMC." />
  <meta property="og:title" content="Auto Detox Studio — India's First 23-Step Car Detox" />
  <meta property="og:description" content="Deep detox your car — not just a wash. 23-step complete car detox process." />
  <meta property="og:type" content="website" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg" />
</head>
<body>

  <!-- LOADER -->
  <div class="loader" id="loader">
    <div class="loader-mark">
      AUTO DETOX STUDIO
      <div class="loader-bar"></div>
    </div>
  </div>

  <div class="cursor-glow" id="cursorGlow"></div>

  <!-- QUICK ENQUIRY POPUP -->
  <?php if ($enquiryEnabled): ?>
  <div class="enquiry-overlay" id="enquiryOverlay" role="dialog" aria-modal="true" aria-labelledby="enquiryTitle">
    <div class="enquiry-modal">
      <button type="button" class="enquiry-close" id="enquiryClose" aria-label="Close enquiry">&times;</button>
      <span class="eyebrow">// Quick Enquiry</span>
      <h3 id="enquiryTitle"><?= h($enquiryTitle) ?></h3>
      <p><?= h($enquiryMessage) ?></p>
      <form action="enquiry.php" method="post" class="enquiry-form">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
        <div class="bf-row"><label for="eq-name">Name *</label><input id="eq-name" name="name" required maxlength="120" placeholder="Your name" /></div>
        <div class="bf-row"><label for="eq-phone">Phone *</label><input id="eq-phone" name="phone" required pattern="[6-9][0-9]{9}" maxlength="10" placeholder="10-digit mobile number" /></div>
        <div class="bf-row"><label for="eq-service">Service</label><select id="eq-service" name="service"><option value="">Select a service</option><?php foreach ($services as $svc): ?><option><?= h(strip_tags($svc['title'])) ?></option><?php endforeach; ?></select></div>
        <div class="bf-row"><label for="eq-message">Message</label><textarea id="eq-message" name="message" rows="3" maxlength="500" placeholder="Tell us what your car needs..."></textarea></div>
        <button class="btn btn-primary btn-block" type="submit">Send Enquiry</button>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <!-- NAV -->
  <header class="nav" id="nav">
    <a href="#top" class="brand" aria-label="Auto Detox Studio">
      <span class="brand-mark">
        <img src="assets/AutoDetoxStudioLogo.jpeg" alt="Auto Detox Studio" width="46" height="46" />
      </span>
      <span class="brand-text">
        <strong>AUTO <em>DETOX</em></strong>
        <small>STUDIO</small>
      </span>
    </a>
    <nav class="nav-links" id="navLinks" aria-label="Primary">
      <a href="#top" class="nav-link" data-section="top">Home</a>
      <a href="#services" class="nav-link" data-section="services">Services</a>
      <a href="#packages" class="nav-link" data-section="packages">Packages</a>
      <a href="#gallery" class="nav-link" data-section="gallery">Gallery</a>
      <a href="#reviews" class="nav-link" data-section="reviews">Reviews</a>
      <a href="#why" class="nav-link" data-section="why">About Us</a>
      <a href="#contact" class="nav-link" data-section="contact">Contact</a>
    </nav>
    <span class="magnetic nav-cta">
      <a href="#book" class="btn btn-primary">Book Appointment</a>
    </span>
    <button class="burger" id="burger" aria-label="Menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </header>

  <!-- HERO -->
  <section class="hero" id="top">
    <div class="hero-grid">
      <div class="hero-copy">
        <span class="badge reveal">
          <span class="dot"></span> Drive Clean. Drive Healthy.
        </span>

        <h1 class="hero-title reveal">
          Auto Detox Studio
          <span class="thin">Premium Car &amp; Bike Detailing</span>
        </h1>

        <p class="hero-sub reveal">
          Ceramic Coating • Graphene Coating • Paint Care • Detailing — professional-grade
          products and advanced steam technology for a genuine showroom-quality finish.
        </p>

        <div class="badge-row reveal" style="margin-bottom:30px;">
          <span class="offer-badge gold">
            <svg viewBox="0 0 24 24"><path fill="currentColor" d="m12 2 3.1 6.3 7 1-5 4.9 1.2 6.9L12 17.8 5.7 21l1.2-6.9-5-4.9 7-1z"/></svg>
            Monsoon Special
          </span>
          <span class="offer-badge red">
            <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7m0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5"/></svg>
            Flat 50% Off*
          </span>
        </div>

        <div class="hero-ctas reveal">
          <span class="magnetic"><a href="#book" class="btn btn-primary">
            Book Your Slot
            <svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M5 12h12.2l-4.6-4.6L14 6l7 6-7 6-1.4-1.4 4.6-4.6H5z"/></svg>
          </a></span>
          <span class="magnetic"><a href="#services" class="btn btn-ghost">View Services</a></span>
        </div>

        <ul class="hero-features reveal">
          <li><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c-3 5-6 8-6 12a6 6 0 0 0 12 0c0-4-3-7-6-12"/></svg>Removes Dirt</li>
          <li><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5z"/></svg>Kills Germs</li>
          <li><svg viewBox="0 0 24 24"><path fill="currentColor" d="M4 12h11a3 3 0 1 0-3-3h-2a5 5 0 1 1 5 5H4zm0 4h15a3 3 0 1 1-3 3h-2a5 5 0 1 0 5-5H4z"/></svg>Neutralises Odours</li>
        </ul>
      </div>

      <!-- Hero image is now used as the full-section background; the separate right-side image card has been removed. -->
    </div>
  </section>

  <!-- SUB-STRIP / PROCESS MARQUEE -->
  <div class="sub-strip" id="process">
    <div class="sub-strip-track">
      <?php for ($i = 0; $i < 2; $i++): ?>
        <span>
          <?php foreach ($processSteps as $idx => $step): ?>
            <b><?= str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) ?></b> <?= h($step) ?><?= $idx < count($processSteps) - 1 ? ' &nbsp;•&nbsp; ' : '' ?>
          <?php endforeach; ?>
        </span>
      <?php endfor; ?>
    </div>
  </div>

  <!-- STATS -->
  <section class="stats">
    <div class="stats-grid">
      <div class="stat-card reveal">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="M3 12l1.5-5A2 2 0 0 1 6.4 5.5h11.2a2 2 0 0 1 1.9 1.5L21 12M3 12v5a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-5M3 12h18M7 15.5h.01M17 15.5h.01"/></svg></div>
        <div class="stat-num" data-count="500"><span class="val">0</span><span class="suffix">+</span></div>
        <div class="stat-label">Cars Detailed</div>
      </div>
      <div class="stat-card reveal reveal-delay-1">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none"><path fill="currentColor" d="m12 2 3.1 6.3 7 1-5 4.9 1.2 6.9L12 17.8 5.7 21l1.2-6.9-5-4.9 7-1z"/></svg></div>
        <div class="stat-num" data-count="5" data-decimal="1"><span class="val">0.0</span></div>
        <div class="stat-label">Google Rating</div>
      </div>
      <div class="stat-card reveal reveal-delay-2">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg></div>
        <div class="stat-num" data-count="100"><span class="val">0</span><span class="suffix">%</span></div>
        <div class="stat-label">Customer Satisfaction</div>
      </div>
      <div class="stat-card reveal reveal-delay-3">
        <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="M12 2 3 6v6c0 5 4 9 9 10 5-1 9-5 9-10V6zm0 4a3 3 0 1 1 0 6 3 3 0 0 1 0-6"/></svg></div>
        <div class="stat-num"><span class="val">Premium</span></div>
        <div class="stat-label">Products</div>
      </div>
    </div>
  </section>

  <!-- BEFORE / AFTER -->
  <section class="ba section">
    <div class="ba-grid">
      <div class="ba-copy reveal">
        <span class="eyebrow">See the Difference</span>
        <h2><span class="line">From Dull</span><span class="line accent">To Showroom.</span></h2>
        <p>Drag the slider to compare the finish before and after professional detailing.</p>
        <div class="ba-info-grid"><span><b>23</b><small>Detailing steps</small></span><span><b>3–6h</b><small>Typical service time</small></span><span><b>100%</b><small>Finish inspected</small></span></div>
      </div>
      <div class="ba-slider reveal" id="baSlider">
        <img class="ba-after" src="https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1400&q=85" alt="Car after detailing" />
        <img class="ba-before" id="baBefore" src="https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?auto=format&fit=crop&w=1400&q=85" alt="Car before detailing" />
        <span class="ba-label before-l">Before</span>
        <span class="ba-label after-l">After</span>
        <div class="ba-handle" id="baHandle">
          <div class="ba-handle-grip">
            <svg viewBox="0 0 24 24"><path fill="currentColor" d="M15 6l-6 6 6 6"/></svg>
            <svg viewBox="0 0 24 24"><path fill="currentColor" d="M9 6l6 6-6 6"/></svg>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICES -->
  <section class="services section" id="services">
    <div class="section-head reveal">
      <span class="eyebrow">// What We Offer</span>
      <h2>Our <span class="accent">Services</span></h2>
      <div class="divider"></div>
      <p class="section-lead">Precision detailing. Professional results.</p>
    </div>

    <div class="services-grid">
      <?php foreach ($services as $i => $s): ?>
        <article class="svc-card reveal" style="--i:<?= $i ?>" id="svc-<?= h($s['id']) ?>">
          <div class="svc-top">
            <div class="svc-ic">
              <?php if ($s['icon'] === 'droplet'): ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c-3 5-6 8-6 12a6 6 0 0 0 12 0c0-4-3-7-6-12"/></svg>
              <?php elseif ($s['icon'] === 'engine'): ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 8a4 4 0 1 0 4 4 4 4 0 0 0-4-4m0 2a2 2 0 1 1-2 2 2 2 0 0 1 2-2m-1-6h2v2h-2zm0 16h2v2h-2zM3 11h2v2H3zm16 0h2v2h-2zM5.6 5.6l1.4 1.4-1.4 1.4-1.4-1.4zm11 11 1.4 1.4-1.4 1.4-1.4-1.4zm0-11 1.4-1.4 1.4 1.4-1.4 1.4zm-11 11 1.4-1.4 1.4 1.4-1.4 1.4z"/></svg>
              <?php elseif ($s['icon'] === 'shine'): ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="M11 2c1.1 4.4 3.2 6.5 7.6 7.6-4.4 1.1-6.5 3.2-7.6 7.6-1.1-4.4-3.2-6.5-7.6-7.6C7.8 8.5 9.9 6.4 11 2m7.5 12.5c.5 2 1.5 3 3.5 3.5-2 .5-3 1.5-3.5 3.5-.5-2-1.5-3-3.5-3.5 2-.5 3-1.5 3.5-3.5"/></svg>
              <?php elseif ($s['icon'] === 'shield'): ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5z"/></svg>
              <?php elseif ($s['icon'] === 'glass'): ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="M4 4h16v16H4V4m2 2v5h5V6H6m7 0v5h5V6h-5M6 13v5h5v-5H6m7 0v5h5v-5h-5"/></svg>
              <?php elseif ($s['icon'] === 'wrench'): ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="M22 6.4a5.5 5.5 0 0 1-7.3 5.2l-6.6 6.6a2 2 0 1 1-2.8-2.8l6.6-6.6A5.5 5.5 0 0 1 19.4 2a5.4 5.4 0 0 1 2.2.5l-3.9 3.9 1.7 1.7L23.4 4.2c.3.7.5 1.4.5 2.2z"/></svg>
              <?php else: ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="m12 2 2.2 6.8H21l-5.6 4.2 2.1 6.8L12 15.8 6.5 19.8l2.1-6.8L3 8.8h6.8z"/></svg>
              <?php endif; ?>
            </div>
            <?php if (!empty($s['flag'])): ?><span class="svc-flag"><?= h($s['flag']) ?></span><?php endif; ?>
          </div>

          <h3 class="svc-title"><?= $s['title'] ?></h3>
          <p class="svc-desc"><?= $s['desc'] ?></p>

          <div class="svc-pricing">
            <?php foreach ($s['pricing'] as $row): ?>
              <div class="svc-price-row<?= !empty($row['quote']) ? ' quote' : '' ?>">
                <span><?= h($row['label']) ?></span>
                <?php if (!empty($row['quote'])): ?>
                  <strong><?= h($row['note'] ?? 'Get Quote') ?></strong>
                <?php else: ?>
                  <strong>₹<?= number_format($row['price']) ?><?php if (!empty($row['onwards'])): ?> <small>onwards</small><?php endif; ?></strong>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <?php if ($s['cta']['type'] === 'book'): ?>
            <span class="magnetic svc-cta"><a href="#book" class="btn btn-outline-gold btn-sm btn-block js-book-service" data-pkg-preselect="1"><?= h($s['cta']['label']) ?></a></span>
          <?php elseif ($s['cta']['type'] === 'view'): ?>
            <span class="magnetic svc-cta"><a href="<?= h($s['cta']['href']) ?>" class="btn btn-outline-gold btn-sm btn-block"><?= h($s['cta']['label']) ?></a></span>
          <?php else: ?>
            <span class="magnetic svc-cta"><a href="https://wa.me/917744028466?text=<?= rawurlencode('Hi, I would like a quote for ' . html_entity_decode(strip_tags($s['title'])) . '.') ?>" target="_blank" rel="noopener" class="btn btn-offer btn-sm btn-block"><?= h($s['cta']['label']) ?></a></span>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CONFIGURATOR -->
  <section class="configurator section">
    <div class="cfg-wrap">
      <div class="section-head center reveal" style="margin-left:auto;margin-right:auto;">
        <span class="eyebrow" style="justify-content:center;width:100%;">// Service Finder</span>
        <h2>What Does Your <span class="accent">Car Need?</span></h2>
        <div class="divider" style="margin-left:auto;margin-right:auto;"></div>
        <p class="section-lead" style="margin-left:auto;margin-right:auto;">Tell us what you want to improve — we'll recommend the right services.</p>
      </div>

      <div class="cfg-options reveal" id="cfgOptions">
        <button class="cfg-opt active" data-need="wash">
          <svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="M12 2c-3 5-6 8-6 12a6 6 0 0 0 12 0c0-4-3-7-6-12"/></svg>
          <span>Detox Wash</span>
        </button>
        <button class="cfg-opt" data-need="shine">
          <svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>
          <span>Shine &amp; Polish</span>
        </button>
        <button class="cfg-opt" data-need="coating">
          <svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5z"/></svg>
          <span>Coating</span>
        </button>
        <button class="cfg-opt" data-need="protect">
          <svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="M12 8a4 4 0 1 0 4 4 4 4 0 0 0-4-4m0 2a2 2 0 1 1-2 2 2 2 0 0 1 2-2m-1-6h2v2h-2zm0 16h2v2h-2zM3 11h2v2H3zm16 0h2v2h-2z"/></svg>
          <span>Underbody</span>
        </button>
        <button class="cfg-opt" data-need="restore">
          <svg viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="1.6" d="M22 6.4a5.5 5.5 0 0 1-7.3 5.2l-6.6 6.6a2 2 0 1 1-2.8-2.8l6.6-6.6A5.5 5.5 0 0 1 19.4 2a5.4 5.4 0 0 1 2.2.5l-3.9 3.9 1.7 1.7L23.4 4.2c.3.7.5 1.4.5 2.2z"/></svg>
          <span>Denting &amp; Paint</span>
        </button>
      </div>

      <div class="cfg-form reveal">
        <div class="cfg-field"><label for="cfgVehicle">Vehicle</label><select id="cfgVehicle"><option>Hatchback</option><option>Sedan</option><option>SUV / XUV</option><option>Bike</option></select></div>
        <div class="cfg-field"><label for="cfgConcern">Main concern</label><select id="cfgConcern"><option value="shine">Dull paint / scratches</option><option value="wash">Deep cleaning / odour</option><option value="coating">Long-term paint protection</option><option value="protect">Underbody protection</option><option value="restore">Dent / paint repair</option></select></div>
        <div class="cfg-field"><label for="cfgGoal">Priority</label><select id="cfgGoal"><option>Best finish</option><option>Maximum protection</option><option>Quick turnaround</option><option>Best value</option></select></div>
      </div>

      <div class="cfg-result reveal" id="cfgResult">
        <div>
          <div class="cfg-result-title">Recommended For You</div>
          <div class="cfg-services" id="cfgServices"></div>
        </div>
        <span class="magnetic"><a href="#book" class="btn btn-primary">Get Recommendation</a></span>
      </div>
    </div>
  </section>

  <!-- CERAMIC COATING -->
  <section class="section coating-section" id="ceramic-coating">
    <div class="section-head reveal">
      <span class="eyebrow">// Paint Protection</span>
      <h2>Ceramic <span class="accent">Coating</span></h2>
      <div class="divider"></div>
      <p class="section-lead">Ultimate gloss. Ultimate protection.</p>
    </div>

    <div class="coating-grid reveal">
      <div>
        <div class="badge-row" style="margin-bottom:24px;">
          <span class="offer-badge red">Limited Period Offer</span>
          <span class="offer-badge gold">2 Years Warranty</span>
          <span class="offer-badge navy">Starting From ₹10,999</span>
        </div>

        <div class="coating-plans">
          <div class="coating-plan">
            <div class="cp-label">Car Ceramic Coating</div>
            <div class="cp-price">₹10,999<small>*onwards</small></div>
          </div>
          <div class="coating-plan">
            <div class="cp-label">Bike Ceramic Coating</div>
            <div class="cp-price">₹3,999<small>*onwards</small></div>
          </div>
        </div>

        <div class="coating-features">
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> High Gloss Finish</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> Water &amp; Dirt Repellent</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> UV &amp; Weather Protection</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> Scratch Resistance</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> Long Lasting Protection</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> 2 Years Warranty</div>
        </div>

        <div class="coating-ctas">
          <span class="magnetic"><a href="#book" class="btn btn-primary js-book-service" data-pkg-preselect="1">Book Your Slot</a></span>
          <span class="magnetic"><a href="tel:+917744028466" class="btn btn-outline-gold">Call For Details</a></span>
        </div>
      </div>

      <div class="coating-visual">
        <img src="https://images.unsplash.com/photo-1583267746897-2cf415887172?auto=format&fit=crop&w=1000&q=80" alt="Ceramic coated car with high gloss finish" loading="lazy" />
        <div class="coating-visual-badge">
          <span>Signature Finish</span>
          <strong>Ceramic Grade</strong>
        </div>
      </div>
    </div>
  </section>

  <!-- GRAPHENE COATING -->
  <section class="section coating-section alt" id="graphene-coating">
    <div class="section-head reveal">
      <span class="eyebrow">// Advanced Protection</span>
      <h2>Graphene <span class="accent">Coating</span></h2>
      <div class="divider"></div>
      <p class="section-lead">Next-generation protection. Maximum performance.</p>
    </div>

    <div class="coating-grid reverse reveal">
      <div class="coating-visual">
        <img src="https://images.unsplash.com/photo-1614200187524-dc4b892acf16?auto=format&fit=crop&w=1000&q=80" alt="Graphene coated car finish" loading="lazy" />
        <div class="coating-visual-badge">
          <span>Next Generation</span>
          <strong>Graphene Grade</strong>
        </div>
      </div>

      <div>
        <div class="badge-row" style="margin-bottom:24px;">
          <span class="offer-badge navy">Next-Gen Formula</span>
        </div>

        <div class="coating-plans">
          <div class="coating-plan">
            <div class="cp-label">Car Graphene Coating</div>
            <div class="cp-price" style="color:var(--red);font-size:19px;">Get Quote</div>
          </div>
          <div class="coating-plan">
            <div class="cp-label">Bike Graphene Coating</div>
            <div class="cp-price" style="color:var(--red);font-size:19px;">Get Quote</div>
          </div>
        </div>

        <div class="coating-features">
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> Superior Hardness</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> Extreme Hydrophobicity</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> Heat &amp; UV Resistant</div>
          <div class="coating-feature"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg> Long-Term Durability</div>
        </div>

        <div class="coating-ctas">
          <span class="magnetic"><a href="https://wa.me/917744028466?text=Hi%2C%20I%20would%20like%20a%20quote%20for%20Graphene%20Coating." target="_blank" rel="noopener" class="btn btn-offer">Get Quote</a></span>
          <span class="magnetic"><a href="tel:+917744028466" class="btn btn-outline-gold">Call Now</a></span>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY CHOOSE -->
  <section class="why section" id="why">
    <div class="why-grid">
      <div>
        <div class="reveal">
          <span class="eyebrow">// Why Choose</span>
          <h2>Why Choose<br /><span class="accent">Auto Detox Studio?</span></h2>
          <div class="divider"></div>
        </div>
        <div class="why-list">
          <div class="why-item reveal">
            <div class="why-item-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M6 2h12l4 6-10 14L2 8zm2.5 2L5 8h4zm3 0L9 8h6zm3 0-2 4h4zm3.5 0-1.5 4h4z"/></svg></div>
            <div><h4>Premium Products</h4><p>Only professional-grade detailing chemicals and equipment touch your car.</p></div>
          </div>
          <div class="why-item reveal">
            <div class="why-item-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4m0 2c-4 0-8 2-8 6v2h16v-2c0-4-4-6-8-6"/></svg></div>
            <div><h4>Trained Professionals</h4><p>Every technician is trained on the full 23-step process, start to finish.</p></div>
          </div>
          <div class="why-item reveal">
            <div class="why-item-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M7 3c2 3 2 5 0 8s-2 5 0 8h2c-2-3-2-5 0-8s2-5 0-8zm6 0c2 3 2 5 0 8s-2 5 0 8h2c-2-3-2-5 0-8s2-5 0-8z"/></svg></div>
            <div><h4>Advanced Techniques</h4><p>Steam sanitization and modern decontamination methods, not just soap and water.</p></div>
          </div>
          <div class="why-item reveal">
            <div class="why-item-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="m9 16.2-3.5-3.5L4 14.2 9 19l11-11-1.5-1.5z"/></svg></div>
            <div><h4>Satisfaction Guaranteed</h4><p>Not happy with a spot? We'll make it right before you leave.</p></div>
          </div>
        </div>
      </div>

      <div class="why-visual reveal">
        <img src="https://images.unsplash.com/photo-1616422285623-13ff0162193c?auto=format&fit=crop&w=1000&q=80" alt="Luxury car in a dark detailing studio" />
        <div class="why-visual-badge">
          <span>Signature Process</span>
          <strong>23 Steps</strong>
        </div>
      </div>
    </div>
  </section>

  <!-- GALLERY -->
  <section class="gallery section" id="gallery">
    <div class="gallery-head reveal">
      <div>
        <span class="eyebrow">// Our Recent Work</span>
        <h2>Car <span class="accent">Transformation</span></h2>
        <div class="divider"></div>
      </div>
      <div class="gallery-nav">
        <button class="gallery-arrow" id="galleryPrev" aria-label="Previous"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M15 6l-6 6 6 6"/></svg></button>
        <button class="gallery-arrow" id="galleryNext" aria-label="Next"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M9 6l6 6-6 6"/></svg></button>
      </div>
    </div>

    <div class="gallery-track-wrap reveal" id="galleryTrackWrap">
      <div class="gallery-track" id="galleryTrack">
        <?php foreach ($galleryItems as $g): ?>
          <div class="gallery-item" data-type="<?= h($g['type'] ?? 'image') ?>" data-src="<?= h($g['img']) ?>" data-title="<?= h(strip_tags($g['label'])) ?>">
            <?php if (($g['type'] ?? 'image') === 'video'): ?>
              <video src="<?= h($g['img']) ?>" muted playsinline preload="metadata"></video><span class="gallery-play">▶</span>
            <?php else: ?>
              <img src="<?= h($g['img']) ?>" alt="<?= h(strip_tags($g['label'])) ?>" draggable="false" loading="lazy" />
            <?php endif; ?>
            <div class="gallery-overlay"><small><?= h(strip_tags($g['sub'] ?? '')) ?></small><strong><?= h(strip_tags($g['label'] ?? 'Gallery')) ?></strong></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="gallery-filters reveal">
      <button class="gallery-filter active" data-filter="all">All</button><button class="gallery-filter" data-filter="image">Photos</button><button class="gallery-filter" data-filter="video">Videos</button>
      <button class="gallery-filter gallery-shuffle" id="galleryShuffle">↻ Shuffle Gallery</button>
    </div>
    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true"><button id="galleryLightboxClose" aria-label="Close">&times;</button><div id="galleryLightboxContent"></div></div>
  </section>

  <!-- PACKAGES (dynamic, from database) -->
  <section class="packages section" id="packages">
    <div class="section-head reveal">
      <span class="eyebrow">// Special Introductory Pricing</span>
      <h2>Choose Your <span class="accent">Package</span></h2>
      <div class="divider"></div>
      <p class="section-lead">Same complete 23-step detox process. Priced by vehicle size. <b>Prices shown after 50% discount.</b></p>
    </div>

    <div class="packages-grid">
      <?php foreach ($packages as $i => $p): ?>
        <article class="pkg-card <?= $p['is_featured'] ? 'featured' : '' ?> reveal" style="--i:<?= $i ?>">
          <?php if ($p['is_featured']): ?><span class="pkg-badge">Most Popular</span><?php endif; ?>
          <div class="pkg-tag"><?= h($p['tag']) ?></div>
          <h3><?= h($p['name']) ?></h3>
          <p class="pkg-eg"><?= h($p['examples']) ?></p>
          <div class="pkg-price">
            <span class="cur">₹</span><span class="amt"><?= h(number_format((float) $p['price'])) ?></span>
          </div>
          <div class="pkg-strike">₹<?= h(number_format((float) $p['strike_price'])) ?></div>
          <div class="pkg-note"><?= h($p['note']) ?></div>
          <ul>
            <?php foreach (preg_split('/\r?\n/', trim($p['features'] ?? '')) as $feature): ?>
              <?php if (trim($feature) !== ''): ?><li><?= h($feature) ?></li><?php endif; ?>
            <?php endforeach; ?>
          </ul>
          <span class="magnetic"><a href="#book" class="btn <?= $p['is_featured'] ? 'btn-primary' : 'btn-outline-gold' ?> btn-block js-book-pkg" data-pkg-id="<?= (int) $p['id'] ?>">Book <?= h($p['name']) ?></a></span>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="reviews section" id="reviews">
    <div class="section-head center reveal" style="margin-left:auto;margin-right:auto;text-align:center;">
      <span class="eyebrow" style="justify-content:center;width:100%;">// Testimonials</span>
      <h2>What Our <span class="accent">Clients Say</span></h2>
      <div class="divider" style="margin-left:auto;margin-right:auto;"></div>
      <p class="booking-intro">Choose your package, pick a convenient slot, share your vehicle details and confirm in under a minute.</p>
    </div>

    <div class="reviews-wrap reveal">
      <div class="reviews-track-wrap">
        <div class="reviews-track reviews-compact" id="reviewsTrack">
          <?php foreach ($reviews as $r): ?>
            <div class="review-card">
              <div class="review-inner">
                <div class="review-stars">
                  <?php for ($s = 0; $s < 5; $s++): ?><svg viewBox="0 0 24 24"><path fill="currentColor" d="m12 2 3.1 6.3 7 1-5 4.9 1.2 6.9L12 17.8 5.7 21l1.2-6.9-5-4.9 7-1z"/></svg><?php endfor; ?>
                </div>
                <p class="review-text">"<?= h($r['text']) ?>"</p>
                <div class="review-person">
                  <img src="https://i.pravatar.cc/150?img=<?= (int) $r['img'] ?>" alt="<?= h($r['name']) ?>" />
                  <div class="review-person-meta">
                    <strong><?= h($r['name']) ?></strong>
                    <span>
                      <svg viewBox="0 0 24 24"><path fill="currentColor" d="M21.8 12.3c0-.7-.1-1.4-.2-2H12v3.8h5.5a4.7 4.7 0 0 1-2 3.1v2.6h3.3c1.9-1.8 3-4.4 3-7.5"/><path fill="currentColor" d="M12 22c2.7 0 5-.9 6.7-2.4l-3.3-2.6c-.9.6-2.1 1-3.4 1-2.6 0-4.8-1.7-5.6-4.1H3v2.6A10 10 0 0 0 12 22"/><path fill="currentColor" d="M6.4 13.9a6 6 0 0 1 0-3.8V7.5H3a10 10 0 0 0 0 9z"/><path fill="currentColor" d="M12 5.9c1.5 0 2.8.5 3.8 1.5l2.9-2.9C16.9 2.7 14.6 1.8 12 1.8A10 10 0 0 0 3 7.5l3.4 2.6c.8-2.4 3-4.2 5.6-4.2"/></svg>
                      Google Review
                    </span>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="reviews-nav">
        <button class="gallery-arrow" id="reviewPrev" aria-label="Previous review"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M15 6l-6 6 6 6"/></svg></button>
        <div class="reviews-dots" id="reviewDots"></div>
        <button class="gallery-arrow" id="reviewNext" aria-label="Next review"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M9 6l6 6-6 6"/></svg></button>
      </div>
    </div>
  </section>

  <!-- BOOKING -->
  <section class="booking section" id="book">
    <button type="button" class="booking-modal-close" id="bookingModalClose" aria-label="Close booking">&times;</button>
    <div class="section-head center reveal" style="margin-left:auto;margin-right:auto;text-align:center;">
      <span class="eyebrow" style="justify-content:center;width:100%;">// Reserve Your Slot</span>
      <h2>Book Your <span class="accent">Appointment</span></h2>
      <div class="divider" style="margin-left:auto;margin-right:auto;"></div>
    </div>

    <?php if ($flash): ?>
      <div class="booking-flash booking-flash-<?= h($flash['type']) ?> reveal"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="steps-bar reveal" id="stepsBar">
      <div class="step-pill active" data-step="1"><span class="num">01</span><span class="label">Service</span></div>
      <div class="step-line"></div>
      <div class="step-pill" data-step="2"><span class="num">02</span><span class="label">Date &amp; Time</span></div>
      <div class="step-line"></div>
      <div class="step-pill" data-step="3"><span class="num">03</span><span class="label">Details</span></div>
      <div class="step-line"></div>
      <div class="step-pill" data-step="4"><span class="num">04</span><span class="label">Confirm</span></div>
    </div>

    <form class="booking-frame reveal" method="post" action="book.php" id="bookingForm">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>" />
      <input type="hidden" name="package_id" id="bf-package" required />
      <input type="hidden" name="preferred_time" id="bf-time" required />

      <!-- STEP 1: SERVICE -->
      <div class="bstep active" data-step="1">
        <h3>Select Your Package</h3>
        <div class="svc-pick-grid" id="svcPickGrid">
          <?php foreach ($packages as $p): ?>
            <button type="button" class="svc-pick js-svc-pick" data-pkg-id="<?= (int) $p['id'] ?>" data-pkg-name="<?= h($p['name']) ?>" data-pkg-price="<?= (int) $p['price'] ?>">
              <div class="pk-tag"><?= h($p['tag']) ?></div>
              <h4><?= h($p['name']) ?></h4>
              <div class="pk-price">₹<?= h(number_format((float) $p['price'])) ?> <s>₹<?= h(number_format((float) $p['strike_price'])) ?></s></div>
            </button>
          <?php endforeach; ?>
        </div>
        <div class="bstep-nav">
          <span></span>
          <span class="magnetic"><button type="button" class="btn btn-primary js-next" data-next="2">Continue</button></span>
        </div>
      </div>

      <!-- STEP 2: DATE & TIME -->
      <div class="bstep" data-step="2">
        <h3>Pick Date &amp; Time</h3>
        <div class="bf-row">
          <label for="bf-date">Preferred Date *</label>
          <input id="bf-date" type="date" name="preferred_date" required min="<?= h($today) ?>" />
        </div>
        <div class="bf-row">
          <label>Available Time Slots *</label>
          <div class="slots-grid" id="slotsGrid">
            <?php foreach ($timeSlots as $slot): ?>
              <button type="button" class="slot-btn js-slot" data-slot="<?= h($slot) ?>"><?= h($slot) ?></button>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="bstep-nav">
          <span class="magnetic"><button type="button" class="btn btn-ghost small-back js-back" data-back="1">Back</button></span>
          <span class="magnetic"><button type="button" class="btn btn-primary js-next" data-next="3">Continue</button></span>
        </div>
      </div>

      <!-- STEP 3: DETAILS -->
      <div class="bstep" data-step="3">
        <h3>Your Details</h3>
        <div class="bf-row">
          <label for="bf-name">Full Name *</label>
          <input id="bf-name" type="text" name="name" required maxlength="120" />
        </div>
        <div class="bf-row-group">
          <div class="bf-row">
            <label for="bf-phone">Phone *</label>
            <input id="bf-phone" type="tel" name="phone" required pattern="[6-9][0-9]{9}" maxlength="10" placeholder="10-digit mobile number" />
          </div>
          <div class="bf-row">
            <label for="bf-email">Email</label>
            <input id="bf-email" type="email" name="email" maxlength="150" />
          </div>
        </div>
        <div class="bf-row">
          <label for="bf-car">Vehicle Model</label>
          <input id="bf-car" type="text" name="car_model" maxlength="100" placeholder="e.g. Swift, City, Creta" />
        </div>
        <div class="bf-row">
          <label for="bf-notes">Notes (optional)</label>
          <textarea id="bf-notes" name="notes" rows="3" maxlength="500"></textarea>
        </div>
        <div class="bstep-nav">
          <span class="magnetic"><button type="button" class="btn btn-ghost small-back js-back" data-back="2">Back</button></span>
          <span class="magnetic"><button type="button" class="btn btn-primary js-next" data-next="4">Continue</button></span>
        </div>
      </div>

      <!-- STEP 4: CONFIRM -->
      <div class="bstep" data-step="4">
        <h3>Confirm Your Booking</h3>
        <div class="confirm-summary" id="confirmSummary">
          <div class="confirm-row"><span>Package</span><span id="cs-package">—</span></div>
          <div class="confirm-row"><span>Date</span><span id="cs-date">—</span></div>
          <div class="confirm-row"><span>Time</span><span id="cs-time">—</span></div>
          <div class="confirm-row"><span>Name</span><span id="cs-name">—</span></div>
          <div class="confirm-row"><span>Phone</span><span id="cs-phone">—</span></div>
          <div class="confirm-row"><span>Vehicle</span><span id="cs-car">—</span></div>
        </div>
        <div class="bstep-nav">
          <span class="magnetic"><button type="button" class="btn btn-ghost small-back js-back" data-back="3">Back</button></span>
          <span class="magnetic"><button type="submit" class="btn btn-primary">Confirm Booking</button></span>
        </div>
      </div>
    </form>

    <div class="booking-side-note reveal">
      <div class="bsn-item"><b>Duration</b>3–6 hrs depending on package</div>
      <div class="bsn-item"><b>Where</b>Near Rajyog Petrol Pump, Walhekarwadi Road, PCMC</div>
      <div class="bsn-item"><b>Confirmation</b>We call/message you to confirm your slot</div>
      <div class="bsn-item"><b>Reschedule</b>Free, anytime — just call us</div>
    </div>
  </section>

  <!-- LOCATION / CONTACT -->
  <section class="location section" id="contact">
    <div class="section-head reveal">
      <span class="eyebrow">// Visit The Studio</span>
      <h2>Location &amp; <span class="accent">Contact</span></h2>
      <div class="divider"></div>
    </div>

    <div class="location-grid">
      <div class="loc-cards reveal">
        <div class="loc-card">
          <div class="loc-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7m0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5"/></svg></div>
          <div><h4>Studio Location</h4><p>Auto Detox Studio, Near Rajyog Petrol Pump, Walhekarwadi Road, PCMC</p></div>
        </div>
        <div class="loc-card">
          <div class="loc-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M6.6 10.8a15.9 15.9 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 9 9 0 0 0 2.8.45 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 9 9 0 0 0 .45 2.8 1 1 0 0 1-.25 1z"/></svg></div>
          <div><h4>Phone</h4><a href="tel:+917744028466">+91 77440 28466</a><a href="tel:+917020364562">+91 70203 64562</a></div>
        </div>
        <div class="loc-card">
          <div class="loc-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M4 4h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1m1 3v.5l7 4.5 7-4.5V7l-7 4.5z"/></svg></div>
          <div><h4>Email</h4><a href="mailto:autodetoxstudio@gmail.com">autodetoxstudio@gmail.com</a></div>
        </div>
        <div class="loc-card">
          <div class="loc-ic"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2m1 10h4.5v2H11V6h2z"/></svg></div>
          <div><h4>Opening Hours</h4><p>Mon – Sun: 9:00 AM – 7:00 PM</p></div>
        </div>

        <div class="loc-ctas">
          <span class="magnetic"><a href="https://maps.google.com/?q=Rajyog+Petrol+Pump+Walhekarwadi+Road+PCMC" target="_blank" rel="noopener" class="btn btn-primary btn-sm">Get Directions</a></span>
          <span class="magnetic"><a href="tel:+917744028466" class="btn btn-ghost btn-sm">Call Now</a></span>
          <span class="magnetic"><a href="https://wa.me/917744028466" target="_blank" rel="noopener" class="btn btn-outline-gold btn-sm">WhatsApp</a></span>
        </div>
      </div>

      <div class="loc-map reveal">
        <iframe src="https://www.google.com/maps?q=Rajyog%20Petrol%20Pump%2C%20Walhekarwadi%20Road%2C%20Pimpri-Chinchwad&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Auto Detox Studio location"></iframe>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-grid">
      <div>
        <div class="brand brand-lg" style="margin-bottom:18px;">
          <span class="brand-mark big">
            <img src="assets/AutoDetoxStudioLogo.jpg" alt="Auto Detox Studio" width="60" height="60" />
          </span>
          <span class="brand-text">
            <strong>AUTO <em>DETOX</em></strong>
            <small>STUDIO — INDIA'S FIRST</small>
          </span>
        </div>
        <p class="muted">Step in. Feel the difference.<br />Drive clean. Drive healthy.</p>
        <div class="socials" style="margin-top:20px;">
          <a href="https://instagram.com/autodetoxstudio" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c2.7 0 3.05.01 4.12.06 1.06.05 1.79.22 2.43.47a4.9 4.9 0 0 1 1.77 1.15 4.9 4.9 0 0 1 1.15 1.77c.25.64.42 1.37.47 2.43.05 1.07.06 1.42.06 4.12s-.01 3.05-.06 4.12c-.05 1.06-.22 1.79-.47 2.43a4.9 4.9 0 0 1-1.15 1.77 4.9 4.9 0 0 1-1.77 1.15c-.64.25-1.37.42-2.43.47-1.07.05-1.42.06-4.12.06s-3.05-.01-4.12-.06c-1.06-.05-1.79-.22-2.43-.47a4.9 4.9 0 0 1-1.77-1.15 4.9 4.9 0 0 1-1.15-1.77c-.25-.64-.42-1.37-.47-2.43C2.01 15.05 2 14.7 2 12s.01-3.05.06-4.12c.05-1.06.22-1.79.47-2.43a4.9 4.9 0 0 1 1.15-1.77A4.9 4.9 0 0 1 5.45.53C6.09.28 6.82.11 7.88.06 8.95.01 9.3 0 12 0m0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10m0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6m5.2-2.9a1.17 1.17 0 1 0 0-2.34 1.17 1.17 0 0 0 0 2.34"/></svg></a>
          <a href="https://facebook.com/autodetoxstudio" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M13.5 21v-8h2.7l.4-3.1h-3.1V8c0-.9.25-1.5 1.55-1.5H17V3.6C16.7 3.55 15.7 3.5 14.5 3.5c-2.4 0-4 1.45-4 4.1V10H7.8v3.1h2.7v8z"/></svg></a>
          <button class="float-enquiry" id="floatEnquiry" type="button">Quick Enquiry</button>

  <a href="https://wa.me/917744028466" target="_blank" rel="noopener" aria-label="WhatsApp"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M20 4A10 10 0 0 0 3.6 15.8L2 22l6.3-1.6A10 10 0 1 0 20 4M12 20a8 8 0 0 1-4.1-1.1l-.3-.2-3.7 1 1-3.6-.2-.3A8 8 0 1 1 12 20"/></svg></a>
          <a href="https://youtube.com/@autodetoxstudio" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M21.6 7.2a2.5 2.5 0 0 0-1.76-1.77C18.25 5 12 5 12 5s-6.25 0-7.84.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.76 1.77C5.75 19 12 19 12 19s6.25 0 7.84-.43a2.5 2.5 0 0 0 1.76-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8M10 15V9l5.2 3z"/></svg></a>
        </div>
      </div>

      <div>
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#top">Home</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#gallery">Gallery</a></li>
          <li><a href="#reviews">Reviews</a></li>
          <li><a href="#why">About Us</a></li>
        </ul>
      </div>

      <div>
        <h4>Services</h4>
        <ul>
          <?php foreach (array_slice($services, 0, 5) as $s): ?>
            <li><a href="#svc-<?= h($s['id']) ?>"><?= $s['title'] ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div>
        <h4>Contact</h4>
        <ul>
          <li><a href="tel:+917744028466">+91 77440 28466</a></li>
          <li><a href="tel:+917020364562">+91 70203 64562</a></li>
          <li><a href="mailto:autodetoxstudio@gmail.com">autodetoxstudio@gmail.com</a></li>
          <li><a href="my-bookings.php">Check My Booking Status</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bar">
      <span>© <span id="year"></span> Auto Detox Studio. All rights reserved.</span>
      <span class="tag-line">STEP IN · FEEL THE DIFFERENCE · <b>DRIVE HEALTHY</b></span>
    </div>
  </footer>

  <a href="https://wa.me/917744028466" target="_blank" rel="noopener" class="float-cta" aria-label="WhatsApp Auto Detox Studio">
    <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M20 4A10 10 0 0 0 3.6 15.8L2 22l6.3-1.6A10 10 0 1 0 20 4M12 20a8 8 0 0 1-4.1-1.1l-.3-.2-3.7 1 1-3.6-.2-.3A8 8 0 1 1 12 20m4.6-6c-.3-.1-1.5-.7-1.7-.8s-.4-.1-.5.1-.6.8-.8 1-.3.1-.5 0a6.6 6.6 0 0 1-3.3-2.9c-.3-.4.3-.4.8-1.3.1-.2 0-.3 0-.4l-.7-1.7c-.2-.5-.4-.4-.5-.4h-.5a.9.9 0 0 0-.7.3 2.9 2.9 0 0 0-.9 2.1 5 5 0 0 0 1 2.7 11.4 11.4 0 0 0 4.4 3.9c2.6 1 2.6.7 3.1.7a2.7 2.7 0 0 0 1.7-1.2 2.1 2.1 0 0 0 .1-1.2s-.4-.2-.6-.3"/></svg>
    <span>Chat</span>
  </a>

  <div class="mobile-book-bar">
    <span class="magnetic" style="width:100%"><a href="#book" class="btn btn-primary btn-block">Book Now</a></span>
  </div>

  <script>
    window.__services = <?= json_encode($services, JSON_UNESCAPED_SLASHES) ?>;
  </script>
  <script src="js/main.js" defer></script>
</body>
</html>
