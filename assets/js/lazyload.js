/* Native loading="lazy" does the heavy lifting; this adds a gentle
   fade-in once each lazy image has actually decoded. */
export function initLazyLoad() {
  document.querySelectorAll('img[loading="lazy"]').forEach((img) => {
    if (img.complete) {
      img.classList.add('is-loaded');
      return;
    }
    img.style.opacity = '0';
    img.style.transition = 'opacity .5s ease';
    img.addEventListener('load', () => {
      img.style.opacity = '1';
      img.classList.add('is-loaded');
    }, { once: true });
    img.addEventListener('error', () => {
      img.style.opacity = '1';
    }, { once: true });
  });
}
