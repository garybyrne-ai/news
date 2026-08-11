/* Subtle custom cursor — desktop fine pointers only (gated in main.js).
   A small dot that eases toward the pointer and grows over
   interactive elements; [data-cursor="label"] shows a short verb. */
export function initCursor() {
  const cursor = document.getElementById('cursor');
  if (!cursor) return;

  const label = cursor.querySelector('.cursor__label');
  let x = -100;
  let y = -100;
  let cx = -100;
  let cy = -100;
  let visible = false;
  let running = false;

  cursor.style.display = 'flex';
  document.body.classList.add('has-cursor-fx');

  const loop = () => {
    cx += (x - cx) * 0.22;
    cy += (y - cy) * 0.22;
    cursor.style.transform =
      `translate(${(cx - cursor.offsetWidth / 2).toFixed(1)}px, ${(cy - cursor.offsetHeight / 2).toFixed(1)}px)`;
    if (Math.abs(x - cx) > 0.2 || Math.abs(y - cy) > 0.2 || visible) {
      requestAnimationFrame(loop);
    } else {
      running = false;
    }
  };

  document.addEventListener('mousemove', (e) => {
    x = e.clientX;
    y = e.clientY;
    if (!visible) {
      visible = true;
      cursor.classList.add('is-visible');
    }
    if (!running) {
      running = true;
      requestAnimationFrame(loop);
    }
  }, { passive: true });

  document.addEventListener('mouseleave', () => {
    visible = false;
    cursor.classList.remove('is-visible');
  });

  document.addEventListener('mouseover', (e) => {
    const labelled = e.target.closest('[data-cursor]');
    const interactive = labelled || e.target.closest('a, button, input, select, textarea, label');
    cursor.classList.toggle('is-hover', Boolean(interactive));
    const text = labelled?.dataset.cursor || '';
    cursor.classList.toggle('is-label', text !== '');
    if (label) label.textContent = text;
  });
}
