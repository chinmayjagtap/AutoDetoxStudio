/* Auto Detox Studio — interactions */
(() => {
  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];

  /* ---------- Year ---------- */
  const yr = $('#year'); if (yr) yr.textContent = new Date().getFullYear();

  /* ---------- Nav scroll state ---------- */
  const nav = $('#nav');
  const onScroll = () => nav?.classList.toggle('scrolled', window.scrollY > 20);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ---------- Mobile menu ---------- */
  const burger = $('#burger');
  const links  = $('.nav-links');
  burger?.addEventListener('click', () => {
    const open = links.classList.toggle('open');
    burger.classList.toggle('open', open);
    burger.setAttribute('aria-expanded', open);
  });
  $$('.nav-links a').forEach(a => a.addEventListener('click', () => {
    links?.classList.remove('open');
    burger?.classList.remove('open');
    burger?.setAttribute('aria-expanded', 'false');
  }));

  /* ---------- Reveal on scroll ---------- */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
  $$('.reveal').forEach(el => io.observe(el));

  /* ---------- Stagger children of grids ---------- */
  $$('.process-grid .step').forEach((el, i) => el.style.setProperty('--i', i));
  $$('.packages-grid .pkg').forEach((el, i) => el.style.setProperty('--i', i));
  $$('.why-grid .why-item').forEach((el, i) => el.style.setProperty('--i', i));

  /* ---------- Booking iframe fallback ---------- */
  const ifr = $('#booking-iframe');
  const fb  = $('#booking-fallback');
  if (ifr && fb) {
    if (!ifr.src || ifr.src.includes('REPLACE_WITH_YOUR_SCHEDULE_ID')) {
      ifr.style.display = 'none';
      fb.classList.add('show');
    }
  }
})();
