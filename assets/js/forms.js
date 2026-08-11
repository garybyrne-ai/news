/* Progressive enhancement for the enquiry form: fetch submission with
   inline status. Falls back to a normal POST + redirect without JS. */
export function initForms() {
  const form = document.querySelector('[data-contact-form]');
  if (!form) return;

  const status = form.querySelector('[data-form-status]');
  const submit = form.querySelector('[type="submit"]');
  const submitLabel = form.querySelector('[data-submit-label]');

  const show = (message, ok) => {
    if (!status) return;
    status.innerHTML = '';
    const p = document.createElement('p');
    p.className = ok ? 'form-status__ok' : 'form-status__err';
    p.textContent = message;
    status.appendChild(p);
    status.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  };

  form.addEventListener('submit', async (e) => {
    if (!form.reportValidity()) return;
    e.preventDefault();

    submit.disabled = true;
    const original = submitLabel?.textContent;
    if (submitLabel) submitLabel.textContent = 'Sending…';

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { Accept: 'application/json' },
      });
      const data = await response.json();
      if (data.ok) {
        show(data.message, true);
        form.reset();
      } else {
        show(data.error || 'Something went wrong — please try again.', false);
      }
    } catch {
      show('Could not send right now — please try again in a moment.', false);
    } finally {
      submit.disabled = false;
      if (submitLabel) submitLabel.textContent = original;
    }
  });
}
