// phpcs:ignoreFile
/**
 * @file
 * Group Filter component.
 */
function CivicThemeGroupFilterComponent(el) {
  if (this.el) {
    return;
  }

  this.el = el;

  this.el.addEventListener('ct.group-filter.update', this.update.bind(this));

  if (!el.hasEventListener) {
    el.hasEventListener = true;
    el.querySelectorAll('input, textarea, select, [type="checkbox"], [type="radio"]').forEach((input) => {
      input.addEventListener('change', () => {
        el.dispatchEvent(new CustomEvent('ct.group-filter.update', { detail: { parent: input.parentElement } }));
      });
    });
  }
}

/**
 * Update the instance.
 */
CivicThemeGroupFilterComponent.prototype.update = function (el) {
  el.detail.parent.setAttribute('aria-live', 'polite');
};

document.querySelectorAll('[data-group-filter-filters]').forEach((el) => {
  new CivicThemeGroupFilterComponent(el);
});
