// phpcs:ignoreFile
/**
 * @file
 * Chip component.
 */

function CivicChip(el) {
  if (el.getAttribute('data-chip') === 'true') {
    return;
  }

  this.el = el;
  this.el.setAttribute('data-chip', 'true');
  this.dismissChip = this.el.querySelector('[data-chip-dismiss]');

  this.el.addEventListener('click', this.clickEvent.bind(this));
  this.el.addEventListener('focusin', this.focusinEvent.bind(this));
  this.el.addEventListener('focusout', this.focusoutEvent.bind(this));

  if (this.dismissChip) {
    this.dismissChip.addEventListener('click', this.dismissClickEvent.bind(this));
  }
}

/**
 * Click event handler.
 */
CivicChip.prototype.clickEvent = function (e) {
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
      this.el.dispatchEvent(new CustomEvent('civictheme.chip.dismiss', {bubbles: true}));
    }
  }
};

/**
 * Set the checked value.
 */
CivicChip.prototype.setChecked = function (input, check) {
  const chip = this.findChip(input);
  if (chip && !chip.hasAttribute('disabled')) {
    if (check) {
      input.setAttribute('checked', 'checked');
      input.value = 1;
      chip.classList.add('active');
    } else {
      input.removeAttribute('checked');
      input.value = 0;
      chip.classList.remove('active');
    }
  }
};

/**
 * Focusin event handler.
 */
CivicChip.prototype.focusinEvent = function (e) {
  const chip = this.findChip(e.target);
  if (chip && !chip.hasAttribute('disabled')) {
    chip.classList.add('focus');
  }
};

/**
 * Focusout event handler.
 */
CivicChip.prototype.focusoutEvent = function (e) {
  const chip = this.findChip(e.target);
  if (chip) {
    chip.classList.remove('focus');
  }
};

/**
 * Click event handler for dismiss chip.
 */
CivicChip.prototype.dismissClickEvent = function (e) {
  const chip = this.findChip(e.target);
  if (chip) {
    if (!chip.classList.contains('civictheme-chip--input')) {
      chip.remove();
      this.el.dispatchEvent(new CustomEvent('civictheme.chip.dismiss', { bubbles: true }));
    }
  }
};

/**
 * Find chip element.
 */
CivicChip.prototype.findChip = function (el) {
  if (el.classList.contains('civictheme-chip')) {
    return el;
  }
  return el.closest('.civictheme-chip');
};

document.querySelectorAll('.civictheme-chip').forEach((el) => {
  new CivicChip(el);
});
