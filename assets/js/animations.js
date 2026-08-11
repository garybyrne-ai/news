/* Scroll-triggered reveals, word-splitting for statements, the process
   progress line, and subtle parallax. Never loaded under
   prefers-reduced-motion (see main.js). */
export function initAnimations() {
  splitWords();
  observeReveals();
  observeProcess();
  initHeroParallax();
  initScrollParallax();
}

function splitWords() {
  document.querySelectorAll('[data-split-words]').forEach((el) => {
    const words = el.textContent.trim().split(/\s+/);
    el.textContent = '';
    words.forEach((word, i) => {
      const outer = document.createElement('span');
      outer.className = 'split-word';
      outer.style.setProperty('--w', i);
      const inner = document.createElement('span');
      inner.textContent = word;
      outer.appendChild(inner);
      el.appendChild(outer);
      if (i < words.length - 1) el.appendChild(document.createTextNode(' '));
    });
  });
}

function observeReveals() {
  const items = document.querySelectorAll('[data-reveal]');
  if (!items.length) return;
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const delay = el.dataset.revealDelay;
        if (delay) el.style.setProperty('--reveal-delay', `${delay}ms`);
        el.classList.add('is-visible');
        observer.unobserve(el);
      });
    },
    { rootMargin: '0px 0px -8% 0px', threshold: 0.05 },
  );
  items.forEach((el) => observer.observe(el));
}

function observeProcess() {
  const process = document.querySelector('[data-process]');
  if (!process) return;
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          process.classList.add('is-inview');
          observer.disconnect();
        }
      });
    },
    { threshold: 0.35 },
  );
  observer.observe(process);
}

/* Mouse parallax on the hero visual (desktop pointers only). */
function initHeroParallax() {
  const scene = document.querySelector('[data-parallax-scene]');
  if (!scene || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
  const layers = scene.querySelectorAll('[data-parallax-layer]');
  let raf = null;
  let target = { x: 0, y: 0 };

  scene.closest('.section')?.addEventListener('mousemove', (e) => {
    const r = scene.getBoundingClientRect();
    target = {
      x: (e.clientX - (r.left + r.width / 2)) / r.width,
      y: (e.clientY - (r.top + r.height / 2)) / r.height,
    };
    if (!raf) raf = requestAnimationFrame(apply);
  });

  function apply() {
    layers.forEach((layer) => {
      const depth = Number(layer.dataset.parallaxLayer) || 0;
      layer.style.translate =
        `${(-target.x * depth).toFixed(1)}px ${(-target.y * depth).toFixed(1)}px`;
    });
    raf = null;
  }
}

/* Very subtle scroll parallax on large media (split section). */
function initScrollParallax() {
  const media = document.querySelectorAll('[data-parallax-img]');
  if (!media.length) return;
  let ticking = false;
  const update = () => {
    media.forEach((el) => {
      const r = el.getBoundingClientRect();
      if (r.bottom < 0 || r.top > window.innerHeight) return;
      const progress = (r.top + r.height / 2 - window.innerHeight / 2) / window.innerHeight;
      el.style.transform = `translateY(${(progress * -26).toFixed(1)}px)`;
    });
    ticking = false;
  };
  window.addEventListener('scroll', () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  }, { passive: true });
  update();
}
