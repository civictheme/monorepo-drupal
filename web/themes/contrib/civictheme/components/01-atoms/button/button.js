// phpcs:ignoreFile
/**
 * @file
 * Button component.
 */
function CivicThemeButton(el) {
  if (el.getAttribute('data-button') === 'true') {
    return;
  }

  this.el = el;
  this.el.setAttribute('data-button', 'true');
  this.dismissButton = this.el.querySelector('[data-button-dismiss]');
  this.keyboardFocused = false;

  this.el.addEventListener('click', this.clickEvent.bind(this));
  this.el.addEventListener('focusin', this.focusinEvent.bind(this));
  this.el.addEventListener('focusout', this.focusoutEvent.bind(this));

  document.addEventListener('mousedown', this.mousedownEvent.bind(this));
  document.addEventListener('keydown', this.keydownEvent.bind(this));

  if (this.dismissButton) {
    this.dismissButton.addEventListener('click', this.dismissClickEvent.bind(this));
  }
}

/**
 * Click event handler.
 */
CivicThemeButton.prototype.clickEvent = function (e) {
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
  }
};

/**
 * Keydown event handler.
 */
CivicThemeButton.prototype.keydownEvent = function (e) {
  if (e.key === 'Tab' || e.key.indexOf('Arrow') === 0) {
    this.keyboardFocused = true;
  }
};

/**
 * Keydown event handler.
 */
CivicThemeButton.prototype.mousedownEvent = function () {
  this.keyboardFocused = false;
};

/**
 * Set the checked value.
 */
CivicThemeButton.prototype.setChecked = function (input, check) {
  const button = this.findButton(input);
  if (button && !button.hasAttribute('disabled')) {
    if (check) {
      input.setAttribute('checked', 'checked');
      button.classList.add('active');
    } else {
      input.removeAttribute('checked');
      button.classList.remove('active');
    }
  }
};

/**
 * Focusin event handler.
 */
CivicThemeButton.prototype.focusinEvent = function (e) {
  const button = this.findButton(e.target);
  if (button && !button.hasAttribute('disabled') && this.keyboardFocused) {
    button.classList.add('focus');
  }
};

/**
 * Focusout event handler.
 */
CivicThemeButton.prototype.focusoutEvent = function (e) {
  const button = this.findButton(e.target);
  if (button) {
    button.classList.remove('focus');
  }
};

/**
 * Click event handler for dismiss button.
 */
CivicThemeButton.prototype.dismissClickEvent = function (e) {
  const button = this.findButton(e.target);
  if (button) {
    button.remove();
    this.el.dispatchEvent(new CustomEvent('ct.button.dismiss', { bubbles: true }));
  }
};

/**
 * Find button element.
 */
CivicThemeButton.prototype.findButton = function (el) {
  if (el.classList.contains('ct-button')) {
    return el;
  }
  return el.closest('.ct-button');
};

document.querySelectorAll('.ct-button').forEach((el) => {
  new CivicThemeButton(el);
});
