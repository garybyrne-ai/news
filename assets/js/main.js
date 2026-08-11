/* Villa Andie — entry module. Boots each feature only when its
   markup exists, and skips motion features per user preference. */
import { initNavigation } from './navigation.js';
import { initScroll } from './scroll.js';
import { initAnimations } from './animations.js';
import { initCursor } from './cursor.js';
import { initForms } from './forms.js';
import { initAccordions } from './accordion.js';
import { initLazyLoad } from './lazyload.js';
import { initReviews } from './reviews.js';

export const prefersReducedMotion = () =>
  window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export const isFinePointer = () =>
  window.matchMedia('(hover: hover) and (pointer: fine)').matches;

initNavigation();
initScroll();
initForms();
initAccordions();
initLazyLoad();
initReviews();

if (!prefersReducedMotion()) {
  initAnimations();
  if (isFinePointer()) {
    initCursor();
    initMagneticButtons();
  }
} else {
  // Reveal everything immediately when motion is reduced.
  document.querySelectorAll('.reveal').forEach((el) => el.classList.add('is-visible'));
  document.querySelectorAll('[data-process]').forEach((el) => el.classList.add('is-inview'));
}

function initMagneticButtons() {
  const MAX = 6; // px — subtle, never hurts clickability
  document.querySelectorAll('[data-magnetic]').forEach((el) => {
    el.addEventListener('mousemove', (e) => {
      const r = el.getBoundingClientRect();
      const x = ((e.clientX - r.left) / r.width - 0.5) * 2 * MAX;
      const y = ((e.clientY - r.top) / r.height - 0.5) * 2 * MAX;
      el.style.transform = `translate(${x.toFixed(1)}px, ${y.toFixed(1)}px)`;
    });
    el.addEventListener('mouseleave', () => {
      el.style.transform = '';
    });
  });
}
