/* Chargeurie — main.js | GSAP 3.12.5 + ScrollTrigger */
/* global gsap, ScrollTrigger, chgData */

document.addEventListener('DOMContentLoaded', function () {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
  gsap.registerPlugin(ScrollTrigger);

  // ── Progress bar
  const pbar = document.getElementById('pbar');
  if (pbar) {
    ScrollTrigger.create({
      start: 0, end: 'max',
      onUpdate: self => { pbar.style.width = (self.progress * 100) + '%'; }
    });
  }

  // ── Nav scroll
  const nav = document.getElementById('nav');
  if (nav) {
    ScrollTrigger.create({
      start: 80,
      onEnter:    () => nav.classList.add('scrolled'),
      onLeaveBack:() => nav.classList.remove('scrolled'),
    });
  }

  // ── Hero
  const heroTL = gsap.timeline({
    scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: 1 }
  });
  heroTL.to('.hero-inner', { y: 120, opacity: 0.3, ease: 'none' });

  // ── Reveal section
  const revealElems = document.querySelectorAll('.reveal-elem');
  if (revealElems.length) {
    gsap.fromTo(revealElems, { opacity: 0, y: 50 }, {
      opacity: 1, y: 0, stagger: 0.15, duration: 1, ease: 'power3.out',
      scrollTrigger: { trigger: '.reveal-section', start: 'top 65%' }
    });
  }

  // ── Slide track (drag)
  const track = document.getElementById('slideTrack');
  if (track) {
    let isDragging = false, startX = 0, scrollLeft = 0;
    track.addEventListener('mousedown', e => {
      isDragging = true; startX = e.pageX - track.offsetLeft; scrollLeft = track.scrollLeft;
      track.style.cursor = 'grabbing';
    });
    document.addEventListener('mouseup', () => { isDragging = false; track.style.cursor = 'grab'; });
    track.addEventListener('mousemove', e => {
      if (!isDragging) return;
      e.preventDefault();
      const x = e.pageX - track.offsetLeft;
      track.scrollLeft = scrollLeft - (x - startX) * 1.5;
    });
  }

  // ── Slide cards fade
  gsap.fromTo('.slide-card', { opacity: 0, y: 40 }, {
    opacity: 1, y: 0, stagger: 0.1, duration: 0.8, ease: 'power3.out',
    scrollTrigger: { trigger: '.slide-section', start: 'top 70%' }
  });

  // ── Manifesto word-by-word
  const manifestoLine = document.getElementById('manifestoLine');
  if (manifestoLine) {
    const words = manifestoLine.textContent.split(' ');
    manifestoLine.innerHTML = words.map(w => '<span class="mw">' + w + '</span>').join(' ');
    gsap.fromTo('.mw', { opacity: 0.12 }, {
      opacity: 1, stagger: 0.08, ease: 'none',
      scrollTrigger: { trigger: '.manifesto', start: 'top 60%', end: 'bottom 60%', scrub: true }
    });
  }

  // ── Stats counter
  document.querySelectorAll('.stat-num').forEach(el => {
    const suffix = el.querySelector('.stat-suffix');
    const raw    = el.textContent.replace(suffix ? suffix.textContent : '', '').trim();
    const target = parseFloat(raw);
    if (isNaN(target)) return;
    gsap.fromTo({ val: 0 }, { val: target, duration: 2, ease: 'power2.out',
      scrollTrigger: { trigger: el, start: 'top 80%', once: true },
      onUpdate: function () {
        el.childNodes[0].textContent = Number.isInteger(target)
          ? Math.round(this.targets()[0].val)
          : this.targets()[0].val.toFixed(1);
      }
    });
  });

  // ── Ticker pause on hover
  const ticker = document.getElementById('ticker');
  if (ticker) {
    ticker.addEventListener('mouseenter', () => ticker.style.animationPlayState = 'paused');
    ticker.addEventListener('mouseleave', () => ticker.style.animationPlayState = 'running');
  }

  // ── AJAX Add to Cart
  document.querySelectorAll('[data-product-id]').forEach(function (card) {
    var btn = card.querySelector('.card-add');
    if (!btn || !window.chgData) return;
    btn.addEventListener('click', function (e) {
      var href = btn.getAttribute('href');
      if (href && href !== '#') return; // lien normal
      e.preventDefault();
      fetch(chgData.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'chg_add_to_cart', nonce: chgData.nonce,
          product_id: card.dataset.productId, quantity: 1
        })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          var cc = document.getElementById('cartCount');
          if (cc) {
            cc.textContent = data.data.cart_count;
            cc.style.display = 'flex';
            gsap.fromTo(cc, { scale: 1.5 }, { scale: 1, duration: 0.4, ease: 'back.out(2)' });
          }
          btn.textContent = 'Ajout\u00e9 !';
          setTimeout(() => { btn.textContent = 'Voir'; }, 2000);
        }
      })
      .catch(console.error);
    });
  });

});
