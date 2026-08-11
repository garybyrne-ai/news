/* Header state, mobile menu, active-section highlighting (nav links +
   side dots) driven by IntersectionObserver. */
export function initNavigation() {
  const header = document.getElementById('site-header');
  const burger = document.getElementById('nav-burger');
  const menu = document.getElementById('mobile-menu');
  const mobileCta = document.getElementById('mobile-cta');
  const sections = [...document.querySelectorAll('.section[id]')];

  /* Scrolled state */
  const onScroll = () => {
    header?.classList.toggle('is-scrolled', window.scrollY > 24);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* Mobile menu */
  if (burger && menu) {
    menu.querySelectorAll('.mobile-menu__list a').forEach((a, i) => {
      a.style.setProperty('--menu-i', i);
    });
    let lastFocus = null;
    const setOpen = (open) => {
      burger.setAttribute('aria-expanded', String(open));
      burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      if (open) {
        lastFocus = document.activeElement;
        menu.hidden = false;
        requestAnimationFrame(() => menu.classList.add('is-open'));
        document.body.style.overflow = 'hidden';
      } else {
        menu.classList.remove('is-open');
        document.body.style.overflow = '';
        setTimeout(() => { menu.hidden = true; }, 400);
        lastFocus?.focus?.();
      }
    };
    burger.addEventListener('click', () =>
      setOpen(burger.getAttribute('aria-expanded') !== 'true'));
    menu.addEventListener('click', (e) => {
      if (e.target.closest('[data-menu-link]')) setOpen(false);
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && burger.getAttribute('aria-expanded') === 'true') setOpen(false);
    });
  }

  /* Active section — nav links, side dots, header contrast */
  if (sections.length) {
    const links = new Map(
      [...document.querySelectorAll('[data-nav-link]')].map((a) => [a.dataset.navLink, a]),
    );
    const dots = new Map(
      [...document.querySelectorAll('[data-dot]')].map((a) => [a.dataset.dot, a]),
    );
    const darkSections = new Set(
      sections.filter((s) => s.classList.contains('bg-dark') || s.classList.contains('bg-gradient'))
        .map((s) => s.id),
    );

    const activate = (id) => {
      links.forEach((a, key) => {
        const active = key === id;
        a.classList.toggle('is-active', active);
        if (active) a.setAttribute('aria-current', 'true');
        else a.removeAttribute('aria-current');
      });
      dots.forEach((a, key) => a.classList.toggle('is-active', key === id));
      document.body.classList.toggle('on-dark-section', darkSections.has(id));
      header?.classList.toggle('on-light', !darkSections.has(id));
    };

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) activate(entry.target.id);
        });
      },
      { rootMargin: '-40% 0px -55% 0px' },
    );
    sections.forEach((s) => observer.observe(s));
  }

  /* Hide the mobile CTA bar while the contact form is in use */
  if (mobileCta) {
    const form = document.querySelector('[data-contact-form]');
    form?.addEventListener('focusin', () => mobileCta.classList.add('is-hidden'));
    form?.addEventListener('focusout', () => {
      setTimeout(() => {
        if (!form.contains(document.activeElement)) mobileCta.classList.remove('is-hidden');
      }, 120);
    });
  }
}
