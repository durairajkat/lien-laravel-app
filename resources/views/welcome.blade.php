@include('partials.header')

<style>
  :root {
    --ink:#0f172a;
    --muted:#64748b;
    --blue:#2585F4;
    --blue-2:#1F7AE8;
    --panel:#f6f9fc;
    --ring:#e6edf6;
    --card:#ffffff;
    --radius:16px;
    --shadow:0 12px 28px rgba(15,23,42,.08);
  }

  * { box-sizing:border-box }
  body { margin:0;font:16px/1.5 "Roboto", Sans-serif;color:var(--ink);background:#fff }

  /* ===== HERO CAROUSEL ===== */
  .lm-carousel { position:relative;width:100%;height:520px;overflow:hidden;background:#000; }
  .slides { display:flex;transition:transform .8s ease-in-out;height:100% }
  .slide { min-width:100%;height:100%;background:center/cover no-repeat var(--bg);position:relative;display:flex;align-items:center;justify-content:center;text-align:left }
  .overlay { position:absolute;inset:0;z-index:1 }
  .overlay.light { background:rgba(255,255,255,.40) }
  .overlay.dark { background:rgba(0,0,0,.40) }

  .slide .content { position:relative;z-index:2;max-width:700px;color:var(--ink);padding:24px }
  .slide:nth-child(3) .content { color:#fff }

  .slide h1 { font-size:clamp(28px,3.4vw,52px);font-weight:800;line-height:1.15;margin:0 0 12px }
  .slide h1 .accent { color:var(--blue) }
  .slide:nth-child(3) h1 .accent { color:#49a6ff }
  .slide p { font-size:1.2rem;color:var(--muted);max-width:640px;margin:0 0 22px;line-height:1.6 }
  .slide:nth-child(3) p { color:rgba(255,255,255,.9) }

  .actions { display:flex;gap:14px;flex-wrap:wrap }
  .btn { display:inline-block;padding:12px 22px;border-radius:999px;font-weight:600;text-decoration:none }
  .btn-primary { color:#fff;background:linear-gradient(180deg,var(--blue),var(--blue-2));box-shadow:0 4px 14px rgba(37,133,244,.35) }
  .btn-ghost { color:var(--ink);background:#fff;border:1px solid #e2e8f0 }
  .slide:nth-child(3) .btn-ghost { color:#222 }

  .nav { position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.75);color:#222;border:none;border-radius:50%;width:38px;height:38px;font-size:22px;cursor:pointer;z-index:3;transition:background .25s }
  .nav:hover { background:rgba(255,255,255,.95) }
  .nav.prev { left:16px }
  .nav.next { right:16px }

  .dots { position:absolute;bottom:16px;left:50%;transform:translateX(-50%);display:flex;gap:10px;z-index:3 }
  .dots button { width:10px;height:10px;border-radius:50%;border:none;background:rgba(255,255,255,.6);cursor:pointer }
  .dots button.active { background:var(--blue) }

  /* ===== SLIDE 2 (ALT LAYOUT) ===== */
  .slide.layout-alt { background:#f8fafc;display:block; }
  .slide.layout-alt .overlay { display:none; }

  .slide.layout-alt .inner {
    max-width:1120px;
    margin:0 auto;
    height:100%;
    padding:24px;
    display:grid;
    grid-template-columns:1.05fr .95fr;
    align-items:center;
    gap:40px;
  }

  .slide.layout-alt .content { color:var(--ink);max-width:560px;padding:0; }
  .slide.layout-alt h1 { margin-bottom:10px; }
  .slide.layout-alt p { color:var(--muted); }

  .slide.layout-alt .photo {
    position:relative;
    border-radius:20px;
    background:#fff;
    box-shadow:0 18px 40px rgba(15,23,42,.10);
    overflow:hidden;
    height:440px;
    display:flex;
    align-items:center;
    justify-content:center;
  }

  .slide.layout-alt .photo .img {
    width:100%;
    height:100%;
    background:center/cover no-repeat;
    transform:scaleX(-1); /* flip horizontally */
  }

  /* ===== SLIDES 1 & 3 STANDARD LAYOUT (MATCH WIDTH WITH SLIDE 2) ===== */
  .slide.standard { justify-content:stretch; }
  .slide.standard .inner {
    max-width:1120px;
    margin:0 auto;
    height:100%;
    padding:24px;
    display:flex;
    align-items:center;
  }
  .slide.standard .content {
    max-width:860px;
    padding:0; /* override global .slide .content */
  }

  /* ===== FEATURES ===== */
  .lm-features { padding:40px 16px 64px;background:#fff }
  .lm-features__inner { max-width:1200px;margin:0 auto }
  .lm-section-title { text-align:center;margin:0 0 24px;color:var(--ink);font-size:2.2rem;font-weight:800 }
  .lm-grid { display:grid;gap:18px;grid-template-columns:repeat(3,1fr) }
  .card { background:var(--card);border:1px solid var(--ring);border-radius:var(--radius);padding:22px;box-shadow:var(--shadow);text-align:center }
  .card__icon { width:60px;height:60px;display:flex;align-items:center;justify-content:center;color:var(--blue);margin:0 auto 12px }
  .card__icon svg { width:32px;height:32px }
  .card__title { margin:0 0 8px;font-size:24px;font-weight:800;color:var(--ink) }
  .card__body { margin:0;color:var(--muted);font-size:1.2rem;line-height:1.55 }

  /* ===== CTA ===== */
  .lm-cta { background:linear-gradient(180deg,var(--blue),var(--blue-2));color:#fff;padding:48px 16px }
  .lm-cta__inner { max-width:980px;margin:0 auto;text-align:center }
  .lm-cta__title { margin:0 0 8px;font-size:clamp(22px,3vw,2.2rem);font-weight:800 }
  .lm-cta__lead { margin:0 0 18px;font-size:1.2rem;opacity:.95 }
  .btn-light { color:var(--ink);background:#fff;border-radius:999px;padding:12px 22px;font-weight:700 }

  /* ===== RESPONSIVE ===== */
  @media (max-width:980px) {
    .lm-grid { grid-template-columns:repeat(2,1fr) }
    .slide.layout-alt .inner { grid-template-columns:1fr;gap:24px; }
    .slide.layout-alt .photo { height:360px; }
    .slide.layout-alt .content { text-align:center;margin:0 auto; }
    .slide.layout-alt .actions { justify-content:center; }
  }
  @media (max-width:700px) {
    .lm-carousel { height:460px }
    .slide .content { text-align:center;padding:0 16px }
    .actions { justify-content:center }
  }
  @media (max-width:640px) {
    .lm-grid { grid-template-columns:1fr }
    .lm-cta { padding:40px 16px }
  }
</style>

<body>

  <!-- ===== HERO CAROUSEL ===== -->
  <section class="lm-carousel" aria-label="Hero">
    <div class="slides">

      <!-- Slide 1 -->
      <div class="slide standard" style="--bg:url('/images/AdobeStock_459481017_Preview.jpeg')">
        <div class="overlay light" aria-hidden="true"></div>
        <div class="inner">
          <div class="content">
            <h1><span>Professional Lien Management</span><br><span class="accent">for the Construction Industry</span></h1>
            <p>Streamline your lien management process with automated deadline calculations, multi-state project intake, and comprehensive case tracking designed specifically for construction professionals.</p>
            <div class="actions">
              <a href="/member/basic" class="btn btn-primary">Get Started Today</a>
              <a href="/about" class="btn btn-ghost">Learn More</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2 (split layout like the reference) -->
      <div class="slide layout-alt">
        <div class="inner">
          <div class="content">
            <h1>
              <span>Professional Lien Management</span><br>
              <span class="accent">for the Construction Industry</span>
            </h1>
            <p>
              Streamline your lien management process with automated deadline calculations, multi-state
              project intake, and comprehensive case tracking designed specifically for construction professionals.
            </p>
            <div class="actions">
              <a href="/member/basic" class="btn btn-primary">Get Started Today</a>
              <a href="/about" class="btn btn-ghost">Learn More</a>
            </div>
          </div>

          <figure class="photo" aria-hidden="true">
            <div class="img" style="background-image:url('/images/AdobeStock_461702410_Preview.jpeg')"></div>
          </figure>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="slide standard" style="--bg:url('/images/AdobeStock_1056776266_Preview.jpeg')">
        <div class="overlay dark" aria-hidden="true"></div>
        <div class="inner">
          <div class="content">
            <h1><span>Professional Lien Management</span><br><span class="accent">for the Construction Industry</span></h1>
            <p>Streamline your lien management process with automated deadline calculations, multi-state project intake, and comprehensive case tracking designed specifically for construction professionals.</p>
            <div class="actions">
              <a href="/member/basic" class="btn btn-primary">Get Started Today</a>
              <a href="/about" class="btn btn-ghost">Learn More</a>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Arrows -->
    <button class="nav prev" aria-label="Previous slide">&#10094;</button>
    <button class="nav next" aria-label="Next slide">&#10095;</button>

    <!-- Dots -->
    <div class="dots" aria-label="Slide indicators"></div>
  </section>

  <!-- ===== FEATURES ===== -->
  <section class="lm-features" aria-label="Features">
    <div class="lm-features__inner">
      <h2 class="lm-section-title">Everything you need for Mechanics Lien and Payment Bond Management</h2>

      <div class="lm-grid">
        <article class="card">
          <div class="card__icon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 3h6a2 2 0 0 1 2 2h1a2 2 0 0 1 2 2v11a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a2 2 0 0 1 2-2h1a2 2 0 0 1 2-2Zm1.5 0A.5.5 0 0 0 10 3.5V5h4V3.5a.5.5 0 0 0-.5-.5h-3ZM10 13l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <h3 class="card__title">Multi-State Project Intake</h3>
          <p class="card__body">Comprehensive 10-step forms for regular liens and streamlined 5-field forms for Miller Act projects.</p>
        </article>

        <article class="card">
          <div class="card__icon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5l3 2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <h3 class="card__title">Automated Deadline Calculations</h3>
          <p class="card__body">State-specific rules engine automatically calculates critical filing deadlines and notice requirements.</p>
        </article>

        <article class="card">
          <div class="card__icon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11a3 3 0 1 0-2.999-3A3 3 0 0 0 16 11Zm-8 0a3 3 0 1 0-3-3 3 3 0 0 0 3 3Zm0 2c-2.667 0-8 1.333-8 4v1h10v-1c0-2.667-5.333-4-8-4Zm8 0c-.59 0-1.247.041-1.935.122A7.48 7.48 0 0 1 18.5 17v1H24v-1c0-2.667-5.333-4-8-4Z" fill="currentColor"/></svg>
          </div>
          <h3 class="card__title">Nationwide Attorney Management</h3>
          <p class="card__body">Secure attorney and admin roles with appropriate permissions and company-based registration.</p>
        </article>

        <article class="card">
          <div class="card__icon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h6l4 4v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M13 3v4h4" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>
          </div>
          <h3 class="card__title">Mechanics Lien Management</h3>
          <p class="card__body">Secure file upload, storage, and management with support for PDF, DOCX, PNG, and JPG files.</p>
        </article>

        <article class="card">
          <div class="card__icon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h9M4 12h9M4 17h9" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="m16 7 2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <h3 class="card__title">Task &amp; Contact Management</h3>
          <p class="card__body">Track tasks, manage contacts, and monitor project progress with integrated workflow tools.</p>
        </article>

        <article class="card">
          <div class="card__icon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v3m-6 3 3 5H3l3-5Zm12 0 3 5h-6l3-5ZM4 21h16M12 6l6 3M12 6 6 9M12 11v10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <h3 class="card__title">Miller Act Compliance</h3>
          <p class="card__body">Specialized workflows for federal construction projects with Miller Act compliance and deadlines.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- ===== CTA ===== -->
  <section class="lm-cta" aria-label="Call to action">
    <div class="lm-cta__inner">
      <h3 class="lm-cta__title">Ready to Streamline Your Mechanics Lien Management?</h3>
      <p class="lm-cta__lead">Join construction professionals who trust LienManager Pro for their lien management needs</p>
      <a href="/member/basic" class="btn btn-light">Start Your Free Trial</a>
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const slidesEl = document.querySelector(".slides");
      const slides = document.querySelectorAll(".slide");
      const dotsContainer = document.querySelector(".dots");
      const prev = document.querySelector(".nav.prev");
      const next = document.querySelector(".nav.next");
      let index = 0;
      let auto;

      // Build dots
      slides.forEach((_, i) => {
        const dot = document.createElement("button");
        dot.addEventListener("click", () => show(i));
        dotsContainer.appendChild(dot);
      });
      const dots = dotsContainer.querySelectorAll("button");

      function show(i){
        index = (i + slides.length) % slides.length;
        slidesEl.style.transform = `translateX(-${index*100}%)`;
        dots.forEach((d,j)=>d.classList.toggle("active", j===index));
      }
      function nextSlide(){ show(index+1); }
      function prevSlide(){ show(index-1); }

      prev.addEventListener("click", prevSlide);
      next.addEventListener("click", nextSlide);

      function start(){ auto = setInterval(nextSlide, 6000); }
      function stop(){ clearInterval(auto); }

      document.querySelector(".lm-carousel").addEventListener("mouseenter", stop);
      document.querySelector(".lm-carousel").addEventListener("mouseleave", start);

      show(0);
      start();
    });
  </script>
</body>

@include('partials.footer')
