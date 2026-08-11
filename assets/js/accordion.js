/* Accessible accordion with an animated height transition.
   data-single-open="1" closes siblings when a new item opens. */
export function initAccordions() {
  document.querySelectorAll('[data-accordion]').forEach(setup);
}

function setup(root) {
  const singleOpen = root.dataset.singleOpen === '1';
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const triggers = [...root.querySelectorAll('.accordion__trigger')];

  const animate = (panel, expand) => {
    if (reduced) {
      panel.hidden = !expand;
      return;
    }
    panel.hidden = false;
    const height = panel.scrollHeight;
    panel.style.overflow = 'clip';
    const animation = panel.animate(
      expand
        ? [{ height: '0px', opacity: 0 }, { height: `${height}px`, opacity: 1 }]
        : [{ height: `${height}px`, opacity: 1 }, { height: '0px', opacity: 0 }],
      { duration: 340, easing: 'cubic-bezier(.22,.8,.3,1)' },
    );
    animation.onfinish = () => {
      panel.style.overflow = '';
      if (!expand) panel.hidden = true;
    };
  };

  const toggle = (trigger, expand) => {
    const panel = document.getElementById(trigger.getAttribute('aria-controls'));
    if (!panel) return;
    trigger.setAttribute('aria-expanded', String(expand));
    animate(panel, expand);
  };

  triggers.forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const expand = trigger.getAttribute('aria-expanded') !== 'true';
      if (expand && singleOpen) {
        triggers.forEach((other) => {
          if (other !== trigger && other.getAttribute('aria-expanded') === 'true') {
            toggle(other, false);
          }
        });
      }
      toggle(trigger, expand);
    });
  });
}
