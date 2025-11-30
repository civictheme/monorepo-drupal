// phpcs:ignoreFile
/**
 * @file
 * Single Filter component.
 */
function CivicThemeSingleFilterComponent(el) {
  if (this.el) {
    return;
  }

  this.el = el;

  this.el.addEventListener('ct.single-filter.update', this.update.bind(this));

  if (!el.hasEventListener) {
    el.hasEventListener = true;
    el.querySelectorAll('input, textarea, select, [type="checkbox"], [type="radio"]').forEach((input) => {
      input.addEventListener('change', () => {
        el.dispatchEvent(new CustomEvent('ct.single-filter.update', { detail: { parent: input.parentElement } }));
      });
    });
  }

  this.activateOrDeactivateSubmitButton(el);
}

/**
 * Update the instance.
 */
CivicThemeSingleFilterComponent.prototype.update = function (el) {
  el.detail.parent.setAttribute('aria-live', 'polite');
  this.activateOrDeactivateSubmitButton(this.el);
};

CivicThemeSingleFilterComponent.prototype.activateOrDeactivateSubmitButton = function (el) {
  const buttons = el.querySelectorAll('.ct-button');
  const activeChips = el.querySelectorAll('.ct-chip.active');
  if (!activeChips.length) {
    buttons.forEach((element) => {
      element.setAttribute('disabled', 'disabled');
    });
  } else {
    buttons.forEach((element) => {
      element.removeAttribute('disabled');
    });
  }
};

document.querySelectorAll('.ct-single-filter').forEach((el) => {
  new CivicThemeSingleFilterComponent(el);
});
