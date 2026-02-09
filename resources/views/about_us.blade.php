@include('partials.header')

<section class="about-hero">
  <div class="about-hero__inner">
    <h1>About Lien Management</h1>
    <p class="lead">
      Lien Manager was built to help attorneys and legal teams handle the complexity of construction lien management with confidence.
      We combine automation, compliance tracking, and intuitive workflows, so you can focus on practicing law, not chasing deadlines.
    </p>
    <a href="/member/basic" class="cta">Get Started Today</a>
  </div>
</section>

<section class="about-panels">
  {{-- ROW 1: Text left (top-aligned), Image right (top-aligned, flipped, overlaps downward) --}}
  <div class="panel panel-size">
    <div class="panel__col text">
      <h2>Our Mission</h2>
      <p>
        We believe lien management shouldn’t be a manual, error-prone process. Our mission is to bring clarity and
        automation to every stage, from project intake to document filing. We empower legal professionals to meet
        deadlines, ensure compliance, and serve their clients with precision.
      </p>
    </div>
    <div class="panel__col media">
      <div class="media__wrap">
        <img
          src="/images/AdobeStock_461702410_Preview.jpeg"
          alt="Construction professional wearing safety gear"
          class="media__img flip overlap-down"
        >
      </div>
    </div>
  </div>

  {{-- ROW 2: Image left (overlaps upward), Text right (vertically centered) --}}
  <div class="panel panel--alt">
    <div class="panel__col media">
      <div class="media__wrap">
        <img
          src="/images/AdobeStock_688271581_Preview.jpeg"
          alt="Construction cranes over a city skyline at sunset"
          class="media__img overlap-up"
        >
      </div>
    </div>
    <div class="panel__col text text--centered">
      <h2>Trusted by Legal Teams Nationwide</h2>
      <p>
        Our clients include attorneys, paralegals, and firms specializing in construction law who rely on Lien
        Manager for accuracy, consistency, and peace of mind.
      </p>
    </div>
  </div>
</section>

<style>
:root{
  --ink:#0f172a;
  --muted:#64748b;
  --blue:#1083FF;
  --blue-2:#1083FF;
  --radius:18px;
  --shadow-strong:0 14px 35px rgba(15,23,42,.18);
  --overlap:20px; /* amount images peek across the band */
}

/* ---------- HERO ---------- */
.about-hero {
  padding: 56px 16px 28px;
  text-align:center;
}
.panel-size{
    height:300px;
}
.about-hero__inner { max-width: 900px; margin: 0 auto; }
.about-hero h1 {
  margin: 0 0 12px;
  font-size: clamp(32px, 5vw, 48px);
  line-height: 1.1;
  color: var(--ink);
}
.about-hero .lead {
  margin: 0 auto 22px;
  max-width: 800px;
  color: var(--muted);
  font-size: 18px;
}
.cta{
  display:inline-block;
  padding: 12px 22px;
  border-radius: 999px;
  background: linear-gradient(180deg, var(--blue), var(--blue-2));
  color:#fff; font-weight:600; text-decoration:none;
  box-shadow: 0 4px 18px rgba(37,133,244,.35);
  transition: opacity .2s;
}
.cta:hover{ opacity:.95; }

/* ---------- PANELS (gray band then white) ---------- */
.about-panels{
  margin-top: 32px;
  padding: 32px 0 16px;
  background: linear-gradient(#f6f9fc 0, #f6f9fc 50%, #fff 50%, #fff 100%);
  overflow: visible;
}

.panel{
  max-width: 1100px;
  margin: 0 auto 28px;
  padding: 24px 16px;
  display: grid;
  grid-template-columns: 1.1fr 1fr;
  gap: 28px;
  align-items: start;    /* Row 1: lock top edges */
  overflow: visible;
}
.panel--alt{
  grid-template-columns: 1fr 1.1fr;
  align-items: center;  /* Row 2: center the text vertically */
}

/* ---------- TEXT ---------- */
.panel__col.text{
  background: transparent;
  border: 0;
  box-shadow: none;
  padding: 0;
}
.panel__col.text h2{
  margin:0 0 12px; font-size:24px; color:var(--ink);
}
.panel__col.text p{
  margin:0; color:var(--ink); opacity:.9; line-height:1.6;
}
.text--centered{ align-self:center; }

/* ---------- MEDIA ---------- */
.panel__col.media{
  display:flex; align-items:flex-start; justify-content:center;
  position:relative; overflow:visible;
}
.panel--alt .panel__col.media{ align-items:center; }

.media__wrap{ position:relative; }

/* The image itself gets the overlap so layout top stays aligned */
.media__img{
  width:100%; max-width:420px;
  aspect-ratio:1 / 1; object-fit:cover;
  border-radius:24px; box-shadow:var(--shadow-strong);
  display:block;
}

/* flip + overlap combos */
.flip{ transform: scaleX(-1); }
.overlap-down{ transform: translateY(var(--overlap)) scaleX(var(--sx,1)); }
.overlap-up{ transform: translateY(calc(var(--overlap) * -1)); }

/* keep flip + overlap together for row 1 */
.flip.overlap-down{ --sx:-1; }

/* ---------- RESPONSIVE ---------- */
@media (max-width: 960px){
  .panel, .panel--alt{
    grid-template-columns:1fr;
    padding:20px 16px;
    align-items:start;
  }
  .panel__col.media,
  .panel--alt .panel__col.media{ align-items:center; }
  /* remove overlaps and flip on mobile for tidy stack */
  .media__img{ max-width:520px; transform:none !important; }
}
</style>


@include('partials.footer')