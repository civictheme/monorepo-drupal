/* eslint-disable no-new, func-names */

/**
 * Collapsible utility component.
 *
 * Attaches to markup with 'data-collapsible' attribute.
 * Available attributes:
 * - data-collapsible-trigger - trigger for the collapsible. If not provided,
 *   then the first descendant will be used.
 * - data-collapsible-panel - panel for the collapsible. If not provided,
 *   then the second descendant will be used.
 * - data-collapsible-collapsed - indicate that a starting state is collapsed.
 * - data-collapsible-duration - duration in milliseconds. Defaults to 500.
 */
function CivicCollapsible(el) {
  // Use 'data-collapsible''s attribute value to identify if this component was
  // already initialised.
  if (el.getAttribute('data-collapsible') === 'true' || this.el) {
    return;
  }

  this.el = el;
  this.trigger = this.el.querySelector('[data-collapsible-trigger]') || this.el.firstElementChild;
  this.panel = this.el.querySelector('[data-collapsible-panel]') || this.el.firstElementChild.nextElementSibling;
  this.duration = this.el.hasAttribute('data-collapsible-duration') ? this.el.getAttribute('data-collapsible-duration') : 500;
  this.el.collapsed = this.el.hasAttribute('data-collapsible-collapsed');

  // Make sure that both trigger and a panel have required attributes sethis.
  this.trigger.setAttribute('data-collapsible-trigger', '');
  this.panel.setAttribute('data-collapsible-panel', '');

  // If trigger was found.
  if (this.trigger) {
    // Attach even listener.
    this.trigger.addEventListener('click', this.clickEvent.bind(this));
    // Collapse if was set as initially collapsed.
    if (this.el.collapsed) {
      this.collapse();
    }
  }
  // Mark as initialized.
  this.el.setAttribute('data-collapsible', 'true');
}

CivicCollapsible.prototype.destroy = function (el) {
  this.el = el;
  this.trigger = this.el.querySelector('[data-collapsible-trigger]') || this.el.firstElementChild;
  // Remove any attached event listeners.
  // eslint-disable-next-line no-self-assign
  this.trigger.outerHTML = this.trigger.outerHTML;
  this.panel = this.el.querySelector('[data-collapsible-panel]') || this.el.firstElementChild.nextElementSibling;
  this.panel.style.height = '';

  this.trigger.removeAttribute('aria-expanded');
  this.panel.removeAttribute('aria-hidden');

  // Mark as non-initialized.
  this.el.setAttribute('data-collapsible', '');

  delete this.el;
  delete this.trigger;
  delete this.panel;

  return true;
};

/**
 * Click event handler.
 */
CivicCollapsible.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  this.toggle();
};

/**
 * Collapse panel.
 *
 * @param {boolean} animate
 *   Flag to collapse with animation.
 */
CivicCollapsible.prototype.collapse = function (animate) {
  this.el.collapsed = true;
  this.el.setAttribute('data-collapsible-collapsed', '');
  this.panel.setAttribute('aria-hidden', true);
  this.trigger.setAttribute('aria-expanded', false);

  if (animate && this.duration > 0) {
    const transition = this.panel.style.transition || `height ${this.duration}ms ease-out`;
    this.panel.style.transition = '';
    this.panel.style.overflow = 'hidden';

    const h = this.panel.scrollHeight;
    requestAnimationFrame(() => {
      this.panel.style.transition = transition;
      this.panel.style.height = `${h}px`;
      requestAnimationFrame(() => {
        this.panel.style.height = `${0}px`;
      });
    });
  } else {
    const { transition } = this.panel.style;
    this.panel.style.transition = '';
    this.panel.style.overflow = 'hidden';
    this.panel.style.height = `${0}px`;
    this.panel.style.transition = transition;
  }
};

/**
 * Expand panel.
 *
 * @param {boolean} animate
 *   Flag to expand with animation.
 */
CivicCollapsible.prototype.expand = function (animate) {
  this.el.collapsed = false;
  this.el.removeAttribute('data-collapsible-collapsed');
  this.panel.setAttribute('aria-hidden', false);
  this.trigger.setAttribute('aria-expanded', true);
  this.panel.style.display = 'block';

  if (animate && this.duration > 0) {
    const h = this.panel.scrollHeight;
    this.panel.style.transition = this.panel.style.transition || `height ${this.duration}ms ease-out`;
    this.panel.style.height = `${h}px`;

    const t = this;
    this.panel.addEventListener('transitionend', function () {
      // eslint-disable-next-line no-caller, no-restricted-properties
      t.panel.removeEventListener('transitionend', arguments.callee);
      if (!t.el.collapsed) {
        t.panel.style.height = '';
        t.panel.style.overflow = '';
      }
    });
  } else {
    const { transition } = this.panel.style;
    this.panel.style.transition = '';
    this.panel.style.overflow = '';
    this.panel.style.height = '';
    this.panel.style.transition = transition;
  }
};

/**
 * Toggle between states.
 */
CivicCollapsible.prototype.toggle = function () {
  if (this.el.collapsed) {
    this.expand(true);
  } else {
    this.collapse(true);
  }
};

document.querySelectorAll('[data-collapsible]').forEach((el) => {
  // Delay initialisation if should be responsive.
  const breakpointExpr = el.getAttribute('data-responsive');
  if (breakpointExpr) {
    window.addEventListener('civic-responsive', (evt) => {
      evt.detail.evaluate(breakpointExpr, CivicCollapsible, el);
    }, false);
    return;
  }

  new CivicCollapsible(el);
});
