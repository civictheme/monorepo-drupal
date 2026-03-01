// phpcs:ignoreFile
/**
 * CivicTheme Single Filter component.
 */

function CivicThemeSingleFilterComponent(el) {
  if (el.getAttribute('data-single-filter') === 'true') {
    return;
  }

  this.el = el;

  this.el.addEventListener('ct.single-filter.update', this.updateEvent.bind(this));

  this.el.querySelectorAll('input, textarea, select, [type="checkbox"], [type="radio"]').forEach((input) => {
    input.addEventListener('change', () => {
      el.dispatchEvent(new CustomEvent('ct.single-filter.update', { detail: { parent: input.parentElement } }));
    });
  });

  // Mark as initialized.
  this.el.setAttribute('data-single-filter', 'true');
}

/**
 * Update event handler.
 */
CivicThemeSingleFilterComponent.prototype.updateEvent = function (el) {
  el.detail.parent.setAttribute('aria-live', 'polite');
};

document.querySelectorAll('.ct-single-filter').forEach((el) => {
  new CivicThemeSingleFilterComponent(el);
});
