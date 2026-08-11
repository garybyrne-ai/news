/* Testimonial crossfade with keyboard support. No autoplay. */
export function initReviews() {
  const root = document.querySelector('[data-reviews]');
  if (!root) return;

  const slides = [...root.querySelectorAll('[data-review]')];
  const prev = root.querySelector('[data-review-prev]');
  const next = root.querySelector('[data-review-next]');
  const counter = root.querySelector('[data-review-current]');
  if (slides.length < 2) return;

  let index = 0;
  const go = (to) => {
    index = (to + slides.length) % slides.length;
    slides.forEach((slide, i) => {
      const active = i === index;
      slide.classList.toggle('is-active', active);
      slide.toggleAttribute('aria-hidden', !active);
    });
    if (counter) counter.textContent = String(index + 1);
  };

  prev?.addEventListener('click', () => go(index - 1));
  next?.addEventListener('click', () => go(index + 1));
  root.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') go(index - 1);
    if (e.key === 'ArrowRight') go(index + 1);
  });
}
