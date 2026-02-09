<footer class="lm-footer">
  <div class="lm-footer-wrap">
    <div class="lm-footer-left">
      <a class="lm-brand" href="/">
        <img class="lm-logo" src="/images/L-Logo.png" alt="Lien Manager logo" />
        <span class="lm-wordmark">LIEN MANAGER</span>
      </a>
    </div>

    <div class="lm-footer-right">
      <p>© {{ date('Y') }} LienManager Pro. All rights reserved.</p>
    </div>
  </div>
</footer>

<style>
:root {
  --footer-bg: #444444;
  --footer-ink: #ffffff;
  --blue: #1083FF;
}

/* Footer wrapper */
.lm-footer {
  background: var(--footer-bg);
  color: var(--footer-ink);
  padding: 28px 0;
}

.lm-footer-wrap {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Brand */
.lm-footer .lm-brand {
  display: flex;
  align-items: center;
  gap: 14px;
  text-decoration: none;
  color: var(--footer-ink);
}

/* ✅ Match header logo size (72×72px) */
.lm-footer .lm-logo {
  width: 108px;
  height: 108px;
  border-radius: 50%;
  object-fit: contain;
  filter: invert(1) brightness(1.2) contrast(1.1);
}

.lm-footer .lm-wordmark {
  font-weight: 800;
  letter-spacing: 0.28em;
  color: var(--blue);
  font-size: 20px;
  white-space: nowrap;
}

/* Right text */
.lm-footer-right p {
  margin: 0;
  font-size: 15px;
  color: rgba(255, 255, 255, 0.8);
}

@media (max-width: 768px) {
  .lm-footer-wrap {
    flex-direction: column;
    gap: 16px;
    text-align: center;
  }
}
</style>
