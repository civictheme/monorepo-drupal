// phpcs:ignoreFile
/**
 * CivicTheme Chip component.
 */

function CivicThemeChip(el) {
  if (el.getAttribute('data-chip') === 'true') {
    return;
  }

  this.el = el;
  this.groupParentSelector = el.getAttribute('data-chip-group-parent') || null;

  this.el.addEventListener('change', this.changeEvent.bind(this));
  this.el.addEventListener('focusin', this.focusinEvent.bind(this));
  this.el.addEventListener('focusout', this.focusoutEvent.bind(this));

  // Mark as initialized.
  this.el.setAttribute('data-chip', 'true');
}

/**
 * Toggle the checked value.
 */
CivicThemeChip.prototype.setChecked = function (input, isChecked) {
  const chip = this.findChip(input);
  if (chip && !chip.hasAttribute('disabled')) {
    if (isChecked) {
      input.setAttribute('checked', 'checked');
      chip.classList.add('active');
    } else {
      input.removeAttribute('checked');
      chip.classList.remove('active');

      const dismissable = chip.hasAttribute('data-chip-dismiss');
      if (dismissable && !input.checked) {
        this.el.dispatchEvent(new CustomEvent('ct.chip.dismiss', { bubbles: true }));
      }
    }
  }
};

/**
 * Focusin event handler.
 */
CivicThemeChip.prototype.focusinEvent = function (e) {
  const chip = this.findChip(e.target);
  if (chip && !chip.hasAttribute('disabled')) {
    chip.classList.add('focus');
  }
};

/**
 * Focusout event handler.
 */
CivicThemeChip.prototype.focusoutEvent = function (e) {
  const chip = this.findChip(e.target);
  if (chip) {
    chip.classList.remove('focus');
  }
};

/**
 * Change event handler.
 */
CivicThemeChip.prototype.changeEvent = function (e) {
  const chip = this.findChip(e.target);
  if (!chip) {
    return;
  }

  const input = chip.querySelector('input');
  if (!input) {
    return;
  }

  // For radios, check current and uncheck others in this group.
  if (input.getAttribute('type') === 'radio') {
    const name = input.getAttribute('name');
    const chipContainer = (!!this.groupParentSelector && chip.closest(this.groupParentSelector)) || document;
    const radios = chipContainer.querySelectorAll(`input[type=radio][name="${name}"]`);
    radios.forEach((radio) => {
      if (radio !== input) {
        this.setChecked(radio, false);
      }
    });
    this.setChecked(input, true);
  } else {
    this.setChecked(input, input.checked);
  }
};

/**
 * Find Chip element.
 */
CivicThemeChip.prototype.findChip = function (el) {
  if (el.classList.contains('ct-chip')) {
    return el;
  }
  return el.closest('.ct-chip');
};

document.querySelectorAll('.ct-chip').forEach((el) => {
  new CivicThemeChip(el);
});
