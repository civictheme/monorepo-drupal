// phpcs:ignoreFile
/**
 * @file
 * Tooltip component.
 */

function CivicTooltip(el) {
  if (el.getAttribute('data-tooltip') === 'true') {
    return;
  }

  this.el = el;
  this.el.setAttribute('data-tooltip', 'true');
  this.button = this.el.querySelector('[data-tooltip-button]');
  this.tooltipContent = this.el.querySelector('[data-tooltip-content]');
  this.arrow = this.el.querySelector('[data-tooltip-arrow]');
  this.close = this.el.querySelector('[data-tooltip-close]');
  this.tooltipPosition = 'auto';

  if (this.button) {
    this.tooltipPosition = this.button.getAttribute('data-tooltip-position');
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
    this.el.popper = window.Popper.createPopper(this.button, this.tooltipContent, {
      placement: this.tooltipPosition,
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

CivicTooltip.prototype.tooltipShow = function (e) {
  e.stopPropagation();
  const tooltip = this.findTooltip(e.target);
  if (tooltip) {
    tooltip.setAttribute('data-tooltip-visible', '');
    tooltip.popper.update();
  }
};

CivicTooltip.prototype.tooltipHide = function (e) {
  e.stopPropagation();
  const tooltip = this.findTooltip(e.target);
  if (tooltip) {
    tooltip.removeAttribute('data-tooltip-visible');
  }
};

/**
 * Find button element.
 */
CivicTooltip.prototype.findTooltip = function (el) {
  if (el.classList.contains('civictheme-tooltip')) {
    return el;
  }
  return el.closest('.civictheme-tooltip');
};

document.querySelectorAll('.civictheme-tooltip').forEach((el) => {
  new CivicTooltip(el);
});
