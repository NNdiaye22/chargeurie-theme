/* Chargeurie — main.js */
/* global gsap, ScrollTrigger, chgData */
document.addEventListener('DOMContentLoaded', function () {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
  gsap.registerPlugin(ScrollTrigger);

  // Progress bar
  ScrollTrigger.create({
    start:0, end:'max',
    onUpdate: self => gsap.set('#pbar', { scaleX: self.progress })
  });

  // Nav scroll
  const nav = document.getElementById('nav');
  if (nav) {
    ScrollTrigger.create({
      start: 1,
      onEnter:  () => nav.classList.add('scrolled'),
      onLeaveBack: () => nav.classList.remove('scrolled')
    });
    ScrollTrigger.create({
      trigger: '.hero',
      start: 'top top',
      end: 'bottom top',
      onLeave:     () => nav.classList.add('light'),
      onEnterBack: () => nav.classList.remove('light')
    });
  }

  // Hero canvas
  const canvas = document.getElementById('heroCanvas');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    let W, H, particles = [], frame;
    function resize(){
      W = canvas.width  = canvas.offsetWidth;
      H = canvas.height = canvas.offsetHeight;
    }
    resize();
    window.addEventListener('resize', resize);
    function Particle(){
      this.reset = function(){
        this.x = W * Math.random();
        this.y = H + 20;
        this.vx = (Math.random() - .5) * .5;
        this.vy = -(Math.random() * 1.5 + .5);
        this.alpha = Math.random() * .6 + .2;
        this.r = Math.random() * 2 + .5;
        this.color = Math.random() > .5 ? '#0071e3' : '#2997ff';
      };
      this.reset();
      this.y = Math.random() * H;
    }
    for (let i = 0; i < 80; i++) particles.push(new Particle());
    function draw(){
      ctx.clearRect(0,0,W,H);
      particles.forEach(p => {
        p.x += p.vx; p.y += p.vy;
        p.alpha -= .003;
        if (p.alpha <= 0 || p.y < -20) p.reset();
        ctx.globalAlpha = p.alpha;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
        ctx.fillStyle = p.color;
        ctx.fill();
      });
      ctx.globalAlpha = 1;
      frame = requestAnimationFrame(draw);
    }
    draw();
    ScrollTrigger.create({
      trigger: '.hero',
      start: 'bottom top',
      onEnter: () => cancelAnimationFrame(frame),
      onLeaveBack: () => draw()
    });
  }

  // Scroll hint
  const sh = document.getElementById('scrollHint');
  if (sh) {
    ScrollTrigger.create({
      trigger: '.hero',
      start: '20% top',
      onEnter:     () => gsap.to(sh, { opacity:0, y:20, duration:.6 }),
      onLeaveBack: () => gsap.to(sh, { opacity:1, y:0,  duration:.6 })
    });
  }

  // Stats counter
  document.querySelectorAll('.stat-num').forEach(el => {
    const num = parseFloat(el.textContent);
    if (isNaN(num)) return;
    const suffix = el.querySelector('.stat-suffix');
    const suffixText = suffix ? suffix.outerHTML : '';
    ScrollTrigger.create({
      trigger: el,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.fromTo(el, { innerText: 0 }, {
          innerText: num, duration: 1.6, ease: 'power2.out',
          snap: { innerText: num < 10 ? .1 : 1 },
          onUpdate() { el.innerHTML = Math.round(this.targets()[0].innerText * 10) / 10 + suffixText; }
        });
      }
    });
  });

  // Manifeste
  const mt = document.getElementById('manifestoText');
  if (mt) {
    gsap.fromTo(mt,
      { opacity: 0, y: 60 },
      { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out',
        scrollTrigger: { trigger: mt, start: 'top 75%' } }
    );
  }

  // Cards stagger
  gsap.fromTo('.product-card',
    { opacity: 0, y: 80 },
    { opacity: 1, y: 0, duration: .9, ease: 'power3.out', stagger: .12,
      scrollTrigger: { trigger: '.products-grid', start: 'top 80%' } }
  );

  // Ticker
  const tw = document.getElementById('tickerWrap');
  if (tw) {
    ScrollTrigger.create({
      trigger: document.body,
      start: 100,
      onEnter:     () => gsap.to(tw, { y: -28, duration: .5, ease: 'power2.inOut' }),
      onLeaveBack: () => gsap.to(tw, { y: 0,  duration: .5, ease: 'power2.inOut' })
    });
  }

  // AJAX Add to Cart
  document.querySelectorAll('[data-product-id]').forEach(function(card) {
    var btn = card.querySelector('.card-add');
    if (!btn) return;
    btn.addEventListener('click', function(e) {
      if (!window.chgData) return;
      e.preventDefault();
      fetch(chgData.ajaxUrl, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
          action: 'chg_add_to_cart',
          nonce: chgData.nonce,
          product_id: card.dataset.productId,
          quantity: 1,
        }),
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          var cc = document.getElementById('cartCount');
          if (cc) {
            cc.textContent = data.data.cart_count;
            cc.style.display = 'flex';
            gsap.fromTo(cc, {scale:1.4}, {scale:1, duration:.3, ease:'back.out(2)'});
          }
          btn.textContent = 'Ajouté !';
          setTimeout(() => { btn.textContent = 'Voir'; }, 2000);
        }
      });
    });
  });

}); // end DOMContentLoaded
