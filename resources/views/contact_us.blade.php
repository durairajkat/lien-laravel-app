@include('partials.header')
<main class="lm-main">
<section class="contact-hero">
  <div class="contact-hero__inner">
    <h1>Contact Us</h1>
    <p class="lead">
      We're here to help! For any inquiries or support, please use the form below.
    </p>
  </div>
</section>

<section class="contact-band">
    <!-- Right: Contact Form -->
    <form class="contact-form" action="/contact" method="post" novalidate>
      @csrf
      <div class="form-row">
        <label for="name" class="sr-only">Your Name</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required>
      </div>

      <div class="form-row">
        <label for="email" class="sr-only">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Email Address" required>
      </div>

      <div class="form-row">
        <label for="message" class="sr-only">Your Message</label>
        <textarea id="message" name="message" placeholder="Your Message" rows="6" required></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">Send Message</button>
      </div>
    </form>
  </div>
</section>

<!-- Bottom white section: contact details on the right column -->
<section class="contact-bottom">
  <div class="contact-bottom-grid">
    <div></div>
    <div class="contact-meta">
      <p><strong>Phone:</strong> (800) 432-7799</p>
      <p><strong>Email:</strong> info@mechanicslien.com</p>
    </div>
  </div>
</section>
</main>

<style>
:root{
  --ink:#0f172a;
  --muted:#64748b;
  --blue:#1083FF;
  --blue-2:#1083FF;
  --ring:#e5ecf4;
  --panel:#f3f6fa;
  --radius:16px;
  --shadow:0 10px 25px rgba(15,23,42,.06);
  --map-overlap:20px; /* controls overlap depth */
}

/* ---------- HERO ---------- */
.contact-hero{
  padding: 72px 16px 40px;
  text-align:center;
}
.contact-hero__inner{ max-width: 920px; margin: 0 auto; }
.contact-hero h1{
  margin:0 0 12px;
  font-size: clamp(32px, 5vw, 48px);
  line-height: 1.1;
  color: var(--ink);
}
.contact-hero .lead{
  margin:0 auto;
  max-width: 720px;
  color: var(--muted);
  font-size: 18px;
}

/* ---------- CONTACT BAND (gray) ---------- */
.contact-band {
  background: var(--panel);
  border-top: 1px solid var(--ring);
  border-bottom: 1px solid var(--ring);
  padding: 48px 16px;              /* more vertical breathing room */
  display: flex;                   /* use flexbox for centering */
  justify-content: center;         /* horizontal centering */
  align-items: center;             /* vertical centering (optional) */
}

.contact-form {
  display: grid;
  gap: 14px;
  width: 100%;
  max-width: 480px;                /* controls form width */
  background: #f8fafd;             /* subtle white panel behind form (optional) */
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
}


.contact-grid{
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1.05fr 1fr;
  gap: 28px;
  align-items: start;
}

/* ---------- MAP COLUMN ---------- */
.map-card{
  background: transparent;
  border: 0;
  box-shadow: none;
  position: relative;
  overflow: visible;
}
/* ✅ Final: original font size (20px), muted gray color */
.map-label{
  margin: 0 0 12px;
  font-size: 20px;
  font-weight: 600;
  color: var(--muted);
  line-height: 1.4;
}
.map-img{
  display:block;
  width: 100%;
  height: 420px;
  object-fit: cover;
  border-radius: 18px;
  box-shadow: var(--shadow);
  position: relative;
  z-index: 3;
}
.map-overlap{ transform: translateY(var(--map-overlap)); }

/* ---------- FORM COLUMN ---------- */

.form-row input,
.form-row textarea{
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #dfe6ee;
  /*border-radius: 10px;*/
  background: #fff;
  color: var(--ink);
  font: 16px/1.4 system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
  outline: none;
}
.form-row input{ height: 44px; }
.form-row textarea{ height: 160px; resize: none; }
.form-row input::placeholder, .form-row textarea::placeholder{ color: #9aa6b2; }
.form-row input:focus, .form-row textarea:focus{
  border-color: rgba(37,133,244,.55);
  box-shadow: 0 0 0 3px rgba(37,133,244,.15);
}

.form-actions{ margin-top: 10px; }
.btn-primary{
  width: 100%;
  appearance: none; border: 0; cursor: pointer;
  padding: 12px 18px; /*border-radius: 999px;*/
  background: linear-gradient(180deg, var(--blue), var(--blue-2));
  color: #fff; font-weight: 600;
  box-shadow: 0 6px 20px rgba(37,133,244,.35);
}
.btn-primary:hover{ opacity: .95; }

/* ---------- BOTTOM WHITE SECTION ---------- */
.contact-bottom{
  background: #fff;
  margin-top: calc(var(--map-overlap) * -1);
  padding-top: calc(var(--map-overlap) + 32px);
  padding-bottom: 56px;
  position: relative;
  z-index: 1;
}
.contact-bottom-grid{
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1.05fr 1fr;
  gap: 28px;
}
.contact-meta{ color: var(--ink); font-size: 15px; }
.contact-meta p{ margin: 6px 0; line-height: 1.55; }

/* ---------- A11y ---------- */
.sr-only{
  position:absolute !important;
  height:1px; width:1px;
  overflow:hidden; clip:rect(1px,1px,1px,1px);
  white-space:nowrap; border:0; padding:0; margin:-1px;
}

html, body { height: 100%; }             /* allow body to fill viewport */
body{
  min-height: 100dvh;                     /* or 100svh for mobile safeness */
  display: flex;
  flex-direction: column;
  margin: 0;
}

.lm-header { flex: 0 0 auto; }            /* header height as-is */
.lm-main   { flex: 1 0 auto; }            /* this grows to fill space */
.lm-footer { flex: 0 0 auto; }            /* footer stays at bottom */


/* ---------- RESPONSIVE ---------- */
@media (max-width: 960px){
  .contact-grid, .contact-bottom-grid{ grid-template-columns: 1fr; }
  .map-img{ height: 320px; }
  .contact-bottom{
    margin-top: 0;
    padding-top: 24px;
  }
}
</style>


@include('partials.footer')