// phpcs:ignoreFile
/**
 * @file
 * Popover component.
 *
 * Attaches to markup with 'data-popover' attribute.
 * Available attributes:
 * - data-popover-trigger - trigger for the popover. If not provided,
 *   then the first descendant will be used.
 * - data-popover-content - content for the popover. If not provided,
 *   then the second descendant will be used.
 * - data-popover-visible - indicate that a popover is visible.
 */

function CivicThemePopover(el) {
  if (el.getAttribute('data-popover') === 'true' || this.el) {
    return;
  }

  const trigger = el;
  const content = this.getContent(el);

  // Exit early if trigger or content were not found.
  if (!trigger || !content) {
    return;
  }

  this.el = el;
  this.el.setAttribute('data-popover', 'true');
  this.el.setAttribute('aria-live', 'polite');
  this.button = trigger;
  this.content = content;

  const triggerEvent = 'click';

  if (this.button) {
    this.triggerEvent = this.button.getAttribute('data-popover-trigger-event') || triggerEvent;

    if (this.triggerEvent === 'click') {
      this.button.addEventListener('click', this.togglePopover.bind(this));
    } else if (this.triggerEvent === 'hover') {
      this.button.addEventListener('mouseenter', this.showPopover.bind(this));
      this.button.addEventListener('focusin', this.showPopover.bind(this));
      this.button.addEventListener('mouseleave', this.hidePopover.bind(this));
    }

    this.button.addEventListener('focusout', this.hidePopover.bind(this));
    this.content.addEventListener('mouseleave', this.hidePopover.bind(this));

    document.addEventListener('click', () => {
      // Check if the filter list parent element exist.
      const isClosest = this.findPopover(el);
      document.querySelectorAll('[data-popover-content]').forEach((elClose) => {
        if (elClose !== isClosest) {
          this.hidePopover(elClose);
        }
      });
    });
  }

  if (typeof Popper !== 'undefined') {
    // Pass the button, the Popover, and some options, and Popper will do the
    // magic positioning for you:
    this.content.popper = window.Popper.createPopper(this.button, this.content, {
      placement: 'auto-start',
      modifiers: [
        {
          name: 'offset',
          options: {
            offset: [0, 12],
          },
        },
      ],
    });
  }
}

/**
 * Close content element.
 */
CivicThemePopover.prototype.closeContent = function () {
  document.querySelectorAll('[data-popover-content]').forEach((el) => {
    el.removeAttribute('data-popover-visible');
  });
};

/**
 * Get content element.
 */
CivicThemePopover.prototype.getContent = function (el) {
  return document.querySelector(`[data-popover-id=${el.getAttribute('data-popover-trigger-id')}]`) || el.querySelector('[data-popover-content]') || el.nextElementSibling || null;
};

/**
 * Destroy an instance.
 */
CivicThemePopover.prototype.destroy = function (el) {
  if (el.getAttribute('data-popover') !== 'true' || !this.el) {
    return;
  }

  const trigger = el;
  const content = this.getContent(el);

  // Exit early if trigger or content were not found.
  if (!trigger || !content) {
    return;
  }

  this.el = el;
  this.trigger = trigger;
  this.content = content;

  // Remove any attached event listeners.
  // eslint-disable-next-line no-self-assign
  this.trigger.outerHTML = this.trigger.outerHTML;

  // Mark as non-initialized.
  this.el.setAttribute('data-popover', '');

  delete this.el;
  delete this.trigger;
  delete this.content;
  delete this.triggerEvent;
};

/**
 * Toggle event handler.
 */
CivicThemePopover.prototype.togglePopover = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();
  const popover = this.findPopover(e.target);
  if (popover && popover.hasAttribute('data-popover-visible')) {
    this.hidePopover(e);
  } else {
    this.closeContent();
    this.showPopover(e);
  }
};

/**
 * Show event handler.
 */
CivicThemePopover.prototype.showPopover = function (e) {
  if (e.target) {
    e.stopPropagation();
    e.preventDefault();
    e.stopImmediatePropagation();
  }

  const popover = this.findPopover(e.target || e);
  if (popover) {
    this.closeContent();
    popover.setAttribute('data-popover-visible', '');
    popover.popper.update();
  }
};

/**
 * Hide Event handler.
 */
CivicThemePopover.prototype.hidePopover = function (e) {
  if (e.target) {
    e.stopPropagation();
    e.preventDefault();
    e.stopImmediatePropagation();
  }

  const popover = this.findPopover(e.target || e);
  if (popover) {
    popover.removeAttribute('data-popover-visible');
  }
};

/**
 * Find button element.
 */
CivicThemePopover.prototype.findPopover = function (el) {
  const popover = this.getContent(el);
  return popover;
};

document.querySelectorAll('[data-popover-trigger]').forEach((el) => {
  new CivicThemePopover(el);
});
