/* Auto Detox Studio — interactions */
(() => {
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];

  /* ---------- Loader ---------- */
  window.addEventListener('load', () => {
    setTimeout(() => $('#loader')?.classList.add('done'), 700);
  });

  /* ---------- Year ---------- */
  const yr = $('#year'); if (yr) yr.textContent = new Date().getFullYear();

  /* ---------- Nav scroll state ---------- */
  const nav = $('#nav');
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 20);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ---------- Mobile menu ---------- */
  const burger = $('#burger');
  const links = $('.nav-links');
  burger?.addEventListener('click', () => {
    const open = links.classList.toggle('open');
    burger.classList.toggle('open', open);
    burger.setAttribute('aria-expanded', open);
  });
  $$('.nav-links a').forEach(a => a.addEventListener('click', () => {
    links.classList.remove('open'); burger.classList.remove('open');
  }));

  /* ---------- Reveal on scroll ---------- */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
  $$('.reveal').forEach(el => io.observe(el));

  /* Stagger service cards */
  $$('.services-grid .svc').forEach((el, i) => el.style.setProperty('--i', i));

  /* ---------- Animated counters ---------- */
  const counterIO = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el = e.target;
      const target = parseFloat(el.dataset.count);
      const dur = 1600;
      const t0 = performance.now();
      const decimals = (String(target).split('.')[1] || '').length;
      const tick = (now) => {
        const p = Math.min(1, (now - t0) / dur);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = (target * eased).toFixed(decimals);
        if (p < 1) requestAnimationFrame(tick);
        else el.textContent = target.toFixed(decimals);
      };
      requestAnimationFrame(tick);
      counterIO.unobserve(el);
    });
  }, { threshold: 0.5 });
  $$('[data-count]').forEach(el => counterIO.observe(el));

  /* ---------- Service tilt + spotlight ---------- */
  $$('.svc').forEach(card => {
    card.addEventListener('mousemove', (ev) => {
      const r = card.getBoundingClientRect();
      const x = ev.clientX - r.left, y = ev.clientY - r.top;
      card.style.setProperty('--mx', x + 'px');
      card.style.setProperty('--my', y + 'px');
      const rx = ((y / r.height) - .5) * -6;
      const ry = ((x / r.width) - .5) * 6;
      card.style.transform = `translateY(-6px) perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg)`;
    });
    card.addEventListener('mouseleave', () => { card.style.transform = ''; });
  });

  /* ---------- Hero shimmer text data-text ---------- */
  $$('.shimmer').forEach(el => el.setAttribute('data-text', el.textContent));

  /* ---------- Parallax orbs ---------- */
  const orbs = $$('.orb');
  window.addEventListener('mousemove', (e) => {
    const cx = (e.clientX / window.innerWidth) - .5;
    const cy = (e.clientY / window.innerHeight) - .5;
    orbs.forEach((o, i) => {
      const k = (i + 1) * 14;
      o.style.translate = `${cx * k}px ${cy * k}px`;
    });
  });

  /* ---------- Booking iframe fallback ---------- */
  const ifr = $('#booking-iframe');
  const fb = $('#booking-fallback');
  if (ifr && fb) {
    if (!ifr.src || ifr.src.includes('REPLACE_WITH_YOUR_SCHEDULE_ID')) {
      ifr.style.display = 'none';
      fb.classList.add('show');
    }
  }

  /* ---------- Background canvas: water drops + particles ---------- */
  const canvas = $('#bg-canvas');
  if (canvas && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const ctx = canvas.getContext('2d');
    let w, h, dpr, particles = [];
    const COUNT = 60;

    const resize = () => {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = canvas.width = window.innerWidth * dpr;
      h = canvas.height = window.innerHeight * dpr;
      canvas.style.width = window.innerWidth + 'px';
      canvas.style.height = window.innerHeight + 'px';
    };
    resize();
    window.addEventListener('resize', resize);

    const reset = (p) => {
      p.x = Math.random() * w;
      p.y = Math.random() * h;
      p.r = (Math.random() * 1.6 + 0.4) * dpr;
      p.vx = (Math.random() - .5) * .15 * dpr;
      p.vy = (Math.random() * .25 + .05) * dpr;
      p.a = Math.random() * .5 + .15;
      p.hue = Math.random() < .5 ? 200 : 210;
    };
    for (let i = 0; i < COUNT; i++) { const p = {}; reset(p); particles.push(p); }

    const loop = () => {
      ctx.clearRect(0, 0, w, h);
      // soft connections
      for (let i = 0; i < particles.length; i++) {
        const p = particles[i];
        p.x += p.vx; p.y += p.vy;
        if (p.y > h + 10 || p.x < -10 || p.x > w + 10) reset(p), p.y = -10;

        ctx.beginPath();
        const grd = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r * 6);
        grd.addColorStop(0, `hsla(${p.hue},100%,70%,${p.a})`);
        grd.addColorStop(1, 'hsla(210,100%,70%,0)');
        ctx.fillStyle = grd;
        ctx.arc(p.x, p.y, p.r * 6, 0, Math.PI * 2);
        ctx.fill();

        ctx.beginPath();
        ctx.fillStyle = `hsla(${p.hue},100%,85%,${Math.min(1, p.a + .3)})`;
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fill();
      }
      requestAnimationFrame(loop);
    };
    loop();
  }
})();
