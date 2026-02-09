<header class="lm-header" role="banner">
  <div class="lm-wrap">
    <!-- Left: logo + wordmark -->
    <a class="lm-brand" href="/" aria-label="Lien Manager – Home">
      <img class="lm-logo" src="/images/L-Logo.png" alt="" />
      <span class="lm-wordmark" aria-hidden="true">LIEN MANAGER</span>
    </a>

    <!-- Navigation -->
    <nav class="lm-nav" aria-label="Primary">
      <ul>
        @if (Auth::check())
        <li><a href="/member">Dashboard</a></li>
        @endif
        <li><a href="/about">About Us</a></li>
        <li><a href="/contact">Contact Us</a></li>
        @if (Auth::check())
        <li><a href="/member/contact-us">Get Help</a></li>
        @endif
      </ul>
    </nav>

    <!-- Right: Login button -->
    <div class="lm-actions">
      @if (Auth::check())
      <a class="lm-login" href="/member/logout">Logout</a>
      @else
       <a class="lm-login" href="/login">Login</a>     
      @endif
    </div>
  </div>
</header>

<style>
* {
    box-sizing: border-box;
}
 
:root{
  --bg:#fafbfd;
  --header-bg:#f6f8fb; /* soft, like the mock */
  --ink:#0f172a;
  --muted:#5b6479;
  --blue:#1083FF;
  --blue-2:#1083FF;
  --border:#e6eaf2;
  --ring:#b7d3ff;
}


@media (min-width: 980px) {
    body .lm-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 980px) {
    body .lm-grid {
        grid-template-columns: repeat(1, 1fr);
    }
}


body{
  margin:0;
  background:var(--bg);
  font:16px/1.5 "Roboto", Sans-serif;
  color:var(--ink);
}

/* Header shell */
.lm-header{
  background:var(--header-bg);
  border-bottom:1px solid var(--border);
}

/* Inner container */
.lm-wrap{
  max-width:1200px;
  box-sizing: border-box;
  margin:0 auto;
  padding:20px 40px;            /* taller, airier */
  min-height:82px;              /* matches visual height in mock */
  display:flex;
  align-items:center;
  gap:40px;
}

/* Brand (logo + wordmark) */
.lm-brand{
  display:flex;
  align-items:center;
  gap:14px;
  text-decoration:none;
  color:inherit;
}

.lm-logo{
  width:108px;                   /* prominent but not huge */
  height:108px;
  border-radius:50%;
  object-fit:contain;
}

.lm-wordmark{
  font-weight:800;
  letter-spacing:.28em;         /* wide letter spacing like mock */
  color:var(--blue);
  font-size:20px;
  line-height:1;
  white-space:nowrap;
  transform:translateY(1px);    /* optical baseline tweak */
}

/* Navigation */
.lm-nav{ margin-left:auto; text-transform:uppercase; }    /* pushes nav to center-ish, actions to far right */
.lm-nav ul{
  list-style:none;
  margin:0;
  padding:0;
  display:flex;
  align-items:center;
  gap:40px;                     /* roomy like the mock */
}
.lm-nav a{
  text-decoration:none;
  color:var(--ink);
  font-weight:700;
  font-size:16px;
  letter-spacing:.01em;
  position:relative;
}
.lm-nav a::after{               /* subtle underline on hover/focus */
  content:"";
  position:absolute;
  left:0; right:0; bottom:-6px;
  height:2px;
  background:currentColor;
  opacity:0;
  transform:scaleX(.6);
  transition:opacity .15s ease, transform .15s ease;
}
.lm-nav a:hover::after,
.lm-nav a:focus-visible::after{
  opacity:1;
  transform:scaleX(1);
}
.lm-nav a:focus-visible{
  outline:none;
  box-shadow:0 0 0 3px var(--ring);
  border-radius:6px;
}

/* Right actions */
.lm-actions{ display:flex; align-items:center; }

.lm-login{
  display:inline-block;
  padding:12px 24px;
  border-radius:999px;
  background:linear-gradient(180deg,var(--blue),var(--blue-2));
  color:#fff;
  font-weight:800;
  font-size:16px;
  text-decoration:none;
  box-shadow:0 6px 16px rgba(37,133,244,.28);
  transform:translateZ(0);      /* crisp text on some browsers */
}
.lm-login:hover{ opacity:.92; }
.lm-login:active{ transform:translateY(1px); }
.lm-login:focus-visible{
  outline:none;
  box-shadow:0 0 0 3px #fff, 0 0 0 6px var(--ring);
}
.rightSec {
  
    max-width: 100%;
}
@media (max-width: 1130px ) {
    .lm-brand .lm-wordmark{
        display: none;
    }
}


/* Responsive */
@media (max-width: 980px){
  .lm-wrap{ padding:16px 24px; gap:24px; }
  .lm-wordmark{ font-size:18px; letter-spacing:.24em; }
  .lm-logo{ width:48px; height:48px; }
  .lm-nav ul{ gap:28px; }
}

@media (max-width: 720px){
  .lm-wrap{
    flex-wrap:wrap;
    gap: 12px;
    row-gap:10px;
    min-height:unset;
  }
  .lm-brand{ order:1; }
  .lm-actions{ order:2; margin-left:auto; }
  .lm-nav{
    order:2;
   
  }
  .lm-nav ul{
    justify-content:center;
    gap:12px;
    text-transform: none;
  }
  .lm-nav a{
      font-size: 12px;
      white-space: nowrap;
  }
  .lm-login{
    font-size: 12px;
    padding: 8px 12px
     
  }
  
  
}
@media (max-width: 475px){
   .lm-nav {
        order: 3;
        width: 100%;
   }
}
@media (max-width: 420px){
  .lm-wordmark{ display:none; }  /* keep the bar tidy on very small screens */
  .lm-logo{ width:44px; height:44px; }
  .lm-login{ padding:10px 18px; font-size:15px; }
  
  
}
</style>
