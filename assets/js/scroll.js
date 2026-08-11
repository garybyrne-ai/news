/* Scroll progress bar and the hero scroll cue. Smooth scrolling itself
   is native CSS (scroll-behavior: smooth). */
export function initScroll() {
  const bar = document.getElementById('scroll-progress-bar');
  if (bar) {
    let ticking = false;
    const update = () => {
      const doc = document.documentElement;
      const max = doc.scrollHeight - window.innerHeight;
      const ratio = max > 0 ? Math.min(window.scrollY / max, 1) : 0;
      bar.style.transform = `scaleX(${ratio.toFixed(4)})`;
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

  // "Scroll to explore" — always target the section after the hero.
  document.querySelectorAll('[data-scroll-next]').forEach((cue) => {
    cue.addEventListener('click', (e) => {
      const current = cue.closest('.section');
      const next = current?.nextElementSibling;
      if (next) {
        e.preventDefault();
        next.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
}
