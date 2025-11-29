// phpcs:ignoreFile
/**
 * @file
 * Chip component.
 */

function CivicThemeChip(el) {
  if (el.getAttribute('data-chip') === 'true') {
    return;
  }

  this.el = el;
  this.el.setAttribute('data-chip', 'true');
  this.dismissible = this.el.hasAttribute('data-chip-dismiss');

  this.el.addEventListener('click', this.clickEvent.bind(this));
  this.el.addEventListener('focusin', this.focusinEvent.bind(this));
  this.el.addEventListener('focusout', this.focusoutEvent.bind(this));

  if (this.dismissible) {
    this.el.addEventListener('click', this.dismissClickEvent.bind(this));
  }
}

/**
 * Click event handler.
 */
CivicThemeChip.prototype.clickEvent = function (e) {
  if (/input/i.test(e.target.tagName)) {
    let isChecked = false;
    const input = e.target;
    if (input.getAttribute('type') === 'checkbox') {
      isChecked = input.getAttribute('checked');
    } else if (input.getAttribute('type') === 'radio') {
      // "Uncheck" all but current radio in this group.
      const name = input.getAttribute('name');
      const radios = document.querySelectorAll(`input[type=radio][name="${name}"]`);
      for (const i in radios) {
        if (Object.prototype.hasOwnProperty.call(radios, i) && radios[i] !== input) {
          this.setChecked(radios[i], false);
        }
      }
    } else {
      return;
    }
    this.setChecked(input, !isChecked);

    if (isChecked) {
      // Dispatch custom event when click on input label.
      this.el.dispatchEvent(new CustomEvent('ct.chip.dismiss', { bubbles: true }));
    }
  }
};

/**
 * Set the checked value.
 */
CivicThemeChip.prototype.setChecked = function (input, check) {
  const chip = this.findChip(input);
  if (chip && !chip.hasAttribute('disabled')) {
    if (check) {
      input.setAttribute('checked', 'checked');
      chip.classList.add('active');
    } else {
      input.removeAttribute('checked');
      chip.classList.remove('active');
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
 * Click event handler for dismiss chip.
 */
CivicThemeChip.prototype.dismissClickEvent = function (e) {
  const chip = this.findChip(e.target);
  if (chip) {
    const input = chip.getElementsByTagName('input');
    if (input.length <= 0) {
      this.el.dispatchEvent(new CustomEvent('ct.chip.dismiss', { bubbles: true }));
    }
  }
};

/**
 * Find chip element.
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
