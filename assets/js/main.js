/* Chargeurie — main.js v3 | GSAP + burger + responsive */
/* global gsap, ScrollTrigger, chgData */

document.addEventListener('DOMContentLoaded', function () {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    console.warn('Chargeurie: GSAP non chargé.');
    return;
  }
  gsap.registerPlugin(ScrollTrigger);

  // ── Barre de progression ─────────────────────────────
  ScrollTrigger.create({
    start: 0, end: 'max',
    onUpdate: self => gsap.set('#pbar', { scaleX: self.progress })
  });

  // ── Nav couleur ──────────────────────────────────────
  const nav = document.getElementById('nav');
  if (nav) {
    ScrollTrigger.create({
      trigger: '.hero', start: 'bottom top',
      onEnter:     () => nav.classList.add('scrolled', 'light'),
      onLeaveBack: () => nav.classList.remove('scrolled', 'light')
    });
    ScrollTrigger.create({
      trigger: '.hero', start: 'top 10%',
      onEnterBack: () => nav.classList.remove('scrolled', 'light')
    });
  }

  // ── Burger menu mobile ───────────────────────────────
  const burgerBtn  = document.getElementById('burgerBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  let menuOpen = false;

  if (burgerBtn && mobileMenu) {
    const mItems = gsap.utils.toArray('.mobile-links a, .mobile-cta, .mobile-meta');

    const menuTL = gsap.timeline({ paused: true })
      .to(mobileMenu, {
        clipPath: 'circle(150% at calc(100% - 48px) 52px)',
        duration: 0.75, ease: 'power4.inOut'
      })
      .to(mItems, {
        opacity: 1, y: 0,
        duration: 0.5, stagger: 0.07, ease: 'power3.out'
      }, '-=0.35');

    function openMenu() {
      menuOpen = true;
      mobileMenu.classList.add('is-open');
      mobileMenu.setAttribute('aria-hidden', 'false');
      burgerBtn.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
      gsap.to('.burger-line:nth-child(1)', { rotate: 45,  y:  6.5, duration: 0.4 });
      gsap.to('.burger-line:nth-child(2)', { opacity: 0,          duration: 0.2 });
      gsap.to('.burger-line:nth-child(3)', { rotate: -45, y: -6.5, duration: 0.4 });
      menuTL.play();
    }

    function closeMenu() {
      menuOpen = false;
      burgerBtn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
      gsap.to('.burger-line:nth-child(1)', { rotate: 0, y: 0, duration: 0.4 });
      gsap.to('.burger-line:nth-child(2)', { opacity: 1,        duration: 0.2, delay: 0.1 });
      gsap.to('.burger-line:nth-child(3)', { rotate: 0, y: 0, duration: 0.4 });
      menuTL.reverse().then(() => {
        mobileMenu.classList.remove('is-open');
        mobileMenu.setAttribute('aria-hidden', 'true');
      });
    }

    burgerBtn.addEventListener('click', () => menuOpen ? closeMenu() : openMenu());
    document.getElementById('mobileClose')?.addEventListener('click', closeMenu);
    document.querySelectorAll('.mobile-links a, .mobile-cta').forEach(l => l.addEventListener('click', closeMenu));

    // Fermer sur Escape
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && menuOpen) closeMenu(); });
  }

  // ── Hero entrance ────────────────────────────────────
  gsap.set('.hero-line',    { y: '110%' });
  gsap.set('.hero-eyebrow', { opacity: 0, y: 12 });
  gsap.set('.hero-bottom',  { opacity: 0, y: 20 });

  const heroTL = gsap.timeline({ delay: 0.15 });
  heroTL
    .to('.hero-line',    { y: '0%', duration: 1.1, stagger: 0.1, ease: 'power4.out' })
    .to('.hero-eyebrow', { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, '-=0.6')
    .to('.hero-bottom',  { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }, '-=0.4');

  // ── Soulignement bleu ────────────────────────────────
  gsap.to('.hero-underline', { width: '100%', duration: 1.1, ease: 'power4.inOut', delay: 1.1 });

  // ── Parallax hero ────────────────────────────────────
  gsap.to('.hero-eyebrow, .hero-bottom', {
    y: -40, ease: 'none',
    scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true }
  });

  // ── Scroll hint ──────────────────────────────────────
  const sh = document.getElementById('scrollHint');
  if (sh) {
    ScrollTrigger.create({
      trigger: '.hero', start: '20% top',
      onEnter:     () => gsap.to(sh, { opacity: 0, y: 20, duration: 0.6 }),
      onLeaveBack: () => gsap.to(sh, { opacity: 1, y: 0,  duration: 0.6 })
    });
  }

  // ── Ticker cache ─────────────────────────────────────
  const tw = document.getElementById('tickerWrap');
  if (tw) {
    ScrollTrigger.create({
      start: 100,
      onEnter:     () => gsap.to(tw, { y: -28, duration: 0.5, ease: 'power2.inOut' }),
      onLeaveBack: () => gsap.to(tw, { y: 0,   duration: 0.5, ease: 'power2.inOut' })
    });
  }

  // ── Canvas particules ─────────────────────────────────
  const canvas = document.getElementById('heroCanvas');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    let W, H, particles = [], frame;
    function resize() { W = canvas.width = canvas.offsetWidth; H = canvas.height = canvas.offsetHeight; }
    resize();
    window.addEventListener('resize', resize);
    function Particle() {
      this.reset = function() {
        this.x = W * Math.random(); this.y = H + 20;
        this.vx = (Math.random() - .5) * .5; this.vy = -(Math.random() * 1.5 + .5);
        this.alpha = Math.random() * .6 + .2; this.r = Math.random() * 2 + .5;
        this.color = Math.random() > .5 ? '#0071e3' : '#2997ff';
      };
      this.reset(); this.y = Math.random() * H;
    }
    for (let i = 0; i < 80; i++) particles.push(new Particle());
    function draw() {
      ctx.clearRect(0, 0, W, H);
      particles.forEach(p => {
        p.x += p.vx; p.y += p.vy; p.alpha -= .003;
        if (p.alpha <= 0 || p.y < -20) p.reset();
        ctx.globalAlpha = p.alpha;
        ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = p.color; ctx.fill();
      });
      ctx.globalAlpha = 1;
      frame = requestAnimationFrame(draw);
    }
    draw();
    ScrollTrigger.create({
      trigger: '.hero', start: 'bottom top',
      onEnter:     () => cancelAnimationFrame(frame),
      onLeaveBack: () => draw()
    });
  }

  // ── Compteurs stats ───────────────────────────────────
  document.querySelectorAll('.count-up').forEach(el => {
    const target = parseFloat(el.dataset.target);
    if (isNaN(target)) return;
    const suffix = el.querySelector('.stat-suffix');
    const suffixHTML = suffix ? suffix.outerHTML : '';
    ScrollTrigger.create({
      trigger: el, start: 'top 85%', once: true,
      onEnter: () => {
        gsap.fromTo({ val: 0 }, { val: 0 }, {
          val: target, duration: 1.6, ease: 'power2.out',
          onUpdate: function() {
            el.innerHTML = Math.round(this.targets()[0].val * 10) / 10 + suffixHTML;
          }
        });
      }
    });
  });

  gsap.from('.stat-item', {
    opacity: 0, y: 24, duration: 0.7, stagger: 0.1,
    scrollTrigger: { trigger: '.stats', start: 'top 85%' }
  });

  // ── Manifeste mot par mot ─────────────────────────────
  const words = gsap.utils.toArray('.manifesto-word');
  if (words.length) {
    const mTL = gsap.timeline({
      scrollTrigger: { trigger: '.manifesto-text', start: 'top 65%', end: 'bottom 35%', scrub: 1 }
    });
    words.forEach(w => mTL.to(w, { opacity: 1, duration: 1 }, '<+=0.25'));
  }
  gsap.to('.manifesto-sub', {
    opacity: 1, y: 0, duration: 0.9,
    scrollTrigger: { trigger: '.manifesto-sub', start: 'top 80%' }
  });

  // ── Cards produits ────────────────────────────────────
  gsap.fromTo('.product-card',
    { opacity: 0, y: 80 },
    { opacity: 1, y: 0, duration: 0.8, stagger: 0.14, ease: 'power3.out',
      scrollTrigger: { trigger: '.products-grid', start: 'top 75%' } }
  );

  // ── Reveal section ────────────────────────────────────
  gsap.from('#reveal .reveal-left h2', {
    opacity: 0, y: 40, duration: 1, ease: 'power3.out',
    scrollTrigger: { trigger: '#reveal', start: 'top 70%' }
  });
  gsap.from('#reveal .reveal-left p, #reveal .cta-row', {
    opacity: 0, y: 20, duration: 0.8, stagger: 0.15,
    scrollTrigger: { trigger: '#reveal', start: 'top 60%' }
  });
  gsap.from('.spec-row', {
    opacity: 0, y: 24, duration: 0.7, stagger: 0.1,
    scrollTrigger: { trigger: '#reveal', start: 'top 65%' }
  });

  // ── Boutons magnétiques ───────────────────────────────
  const isTouchDevice = window.matchMedia('(hover:none)').matches;
  if (!isTouchDevice) {
    document.querySelectorAll('.btn-dark, .nav-cta, .hero-cta').forEach(btn => {
      btn.addEventListener('mousemove', function(e) {
        const r  = this.getBoundingClientRect();
        const dx = (e.clientX - (r.left + r.width  / 2)) * 0.28;
        const dy = (e.clientY - (r.top  + r.height / 2)) * 0.28;
        gsap.to(this, { x: dx, y: dy, duration: 0.3, ease: 'power2.out' });
      });
      btn.addEventListener('mouseleave', function() {
        gsap.to(this, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.4)' });
      });
    });
  }

  // ── AJAX Add to Cart ──────────────────────────────────
  document.querySelectorAll('[data-product-id]').forEach(function(card) {
    const btn = card.querySelector('.card-add');
    if (!btn) return;
    btn.addEventListener('click', function(e) {
      if (!window.chgData) return;
      e.preventDefault();
      fetch(chgData.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'chg_add_to_cart', nonce: chgData.nonce,
          product_id: card.dataset.productId, quantity: 1,
        }),
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const cc = document.getElementById('cartCount');
          if (cc) {
            cc.textContent = data.data.cart_count;
            cc.style.display = 'flex';
            gsap.fromTo(cc, { scale: 1.4 }, { scale: 1, duration: 0.3, ease: 'back.out(2)' });
          }
          btn.textContent = 'Ajouté !';
          setTimeout(() => { btn.textContent = 'Voir'; }, 2000);
        }
      });
    });
  });

}); // end DOMContentLoaded
