// phpcs:ignoreFile
/**
 * @file
 * Tooltip component.
 */

function CivicThemeTooltip(el) {
  if (el.getAttribute('data-tooltip') === 'true') {
    return;
  }

  this.el = el;
  this.el.setAttribute('data-tooltip', 'true');
  this.button = this.el.querySelector('[data-tooltip-button]');
  this.content = this.el.querySelector('[data-tooltip-content]');
  this.arrow = this.el.querySelector('[data-tooltip-arrow]');
  this.close = this.el.querySelector('[data-tooltip-close]');
  this.position = 'auto';

  if (this.button) {
    // Generate unique id for the tooltip content.
    let prefix = 'tooltip';
    do {
      prefix += Math.floor(Math.random() * 10000);
    } while (document.getElementById(prefix));
    this.content.setAttribute('id', prefix);
    this.button.setAttribute('aria-describedby', prefix);

    this.position = this.button.getAttribute('data-tooltip-position') || 'auto';
    this.button.addEventListener('click', this.tooltipShow.bind(this));
    this.button.addEventListener('focusin', this.tooltipShow.bind(this));
    this.button.addEventListener('focusout', this.tooltipHide.bind(this));
    this.button.addEventListener('mouseenter', this.tooltipShow.bind(this));
    this.button.addEventListener('mouseleave', this.tooltipHide.bind(this));
    this.close.addEventListener('focusin', this.tooltipHide.bind(this));
    this.close.addEventListener('click', this.tooltipHide.bind(this));
  }

  if (typeof Popper !== 'undefined') {
    // Pass the button, the tooltip, and some options, and Popper will do the
    // magic positioning for you:
    this.el.popper = window.Popper.createPopper(this.button, this.content, {
      placement: this.position,
      modifiers: [
        {
          name: 'arrow',
          options: {
            element: this.arrow,
            padding: 12,
          },
        },
        {
          name: 'offset',
          options: {
            offset: [0, 36],
          },
        },
        {
          name: 'flip',
          options: {
            fallbackPlacements: ['top', 'bottom'],
          },
        },
      ],
    });
  }
}

/**
 * Show event handler.
 */
CivicThemeTooltip.prototype.tooltipShow = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  const tooltip = this.findTooltip(e.target);
  if (tooltip) {
    tooltip.setAttribute('data-tooltip-visible', '');
    tooltip.popper.update();
  }
};

/**
 * Hide event handler.
 */
CivicThemeTooltip.prototype.tooltipHide = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  const tooltip = this.findTooltip(e.target);
  if (tooltip) {
    tooltip.removeAttribute('data-tooltip-visible');
  }
};

/**
 * Find button element.
 */
CivicThemeTooltip.prototype.findTooltip = function (el) {
  if (el.classList.contains('ct-tooltip')) {
    return el;
  }
  return el.closest('.ct-tooltip');
};

/**
 * Destroy an instance.
 */
CivicThemeTooltip.prototype.destroy = function (el) {
  if (el.getAttribute('data-tooltip') !== 'true' || !this.el) {
    return;
  }

  const button = el.querySelector('[data-tooltip-button]');
  const content = el.querySelector('[data-tooltip-content]');

  // Exit early if button or content were not found.
  if (!button || !content) {
    return;
  }

  this.el = el;
  this.button = button;
  this.content = content;

  // Remove any attached event listeners.
  // eslint-disable-next-line no-self-assign
  this.button.outerHTML = this.button.outerHTML;

  // Mark as non-initialized.
  this.el.setAttribute('data-tooltip', '');

  delete this.el;
  delete this.button;
  delete this.content;
  delete this.arrow;
  delete this.close;
  delete this.position;
};

document.querySelectorAll('.ct-tooltip').forEach((el) => {
  new CivicThemeTooltip(el);
});
