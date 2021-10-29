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
  // Use "data-collapsible"'s attribute value to identify if this component was
  // already initialised.
  if (el.getAttribute('data-collapsible') === 'true' || this.el) {
    return;
  }

  const trigger = el.querySelector('[data-collapsible-trigger]') || el.firstElementChild;
  const panel = el.querySelector('[data-collapsible-panel]') || el.firstElementChild.nextElementSibling;

  // Exit early if trigger or panel were not found.
  if (!trigger || !panel) {
    return;
  }

  this.el = el;
  this.trigger = trigger;
  this.panel = panel;
  this.collapsed = this.el.hasAttribute('data-collapsible-collapsed');
  this.duration = this.el.hasAttribute('data-collapsible-duration') ? this.el.getAttribute('data-collapsible-duration') : 500;
  this.group = this.el.hasAttribute('data-collapsible-group') ? this.el.getAttribute('data-collapsible-group') : null;

  // Make sure that both trigger and a panel have required attributes set.
  this.trigger.setAttribute('data-collapsible-trigger', '');
  this.panel.setAttribute('data-collapsible-panel', '');

  // Attach event listener.
  this.trigger.addEventListener('click', this.clickEvent.bind(this));
  this.trigger.addEventListener('keydown', this.keydownEvent.bind(this.trigger));

  // // Collapse if was set as initially collapsed.
  if (this.collapsed) {
    this.collapse();
  }
  //
  this.el.addEventListener('civic.collapsible.collapse', () => {
    this.collapse(true);
  });

  this.el.addEventListener('civic.collapsible.expand', () => {
    this.expand(true);
  });

  // Attach global keydown event listener to allow closing all collapsibles.
  document.addEventListener('keydown', CivicCollapsible.prototype.keydownEvent);
  document.addEventListener('click', CivicCollapsible.prototype.collapseAllGroups);

  // Mark as initialized.
  this.el.setAttribute('data-collapsible', 'true');
}

/**
 * Destroy an instance.
 */
CivicCollapsible.prototype.destroy = function (el) {
  if (el.getAttribute('data-collapsible') !== 'true' || !this.el) {
    return;
  }
  const trigger = el.querySelector('[data-collapsible-trigger]') || el.firstElementChild;
  const panel = el.querySelector('[data-collapsible-panel]') || el.firstElementChild.nextElementSibling;

  // Exit early if trigger or panel were not found.
  if (!trigger || !panel) {
    return;
  }

  this.el = el;
  this.trigger = trigger;
  this.panel = panel;

  // Remove any attached event listeners.
  // eslint-disable-next-line no-self-assign
  this.trigger.outerHTML = this.trigger.outerHTML;
  // Remove inline overrides.
  this.panel.style.height = '';
  this.panel.style.overflow = '';

  this.trigger.removeAttribute('aria-expanded');
  this.panel.removeAttribute('aria-hidden');

  // Mark as non-initialized.
  this.el.setAttribute('data-collapsible', '');

  delete this.el;
  delete this.trigger;
  delete this.panel;
  delete this.collapsed;
  delete this.duration;
  delete this.group;
};

/**
 * Click event handler.
 */
CivicCollapsible.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  if (this.group) {
    this.closeGroup(this.group);
  }

  if (this.collapsed) {
    this.el.dispatchEvent(new CustomEvent('civic.collapsible.expand', { bubbles: true }));
  } else {
    this.el.dispatchEvent(new CustomEvent('civic.collapsible.collapse', { bubbles: true }));
  }
};

/**
 * React on pressed keys.
 */
CivicCollapsible.prototype.keydownEvent = function (e) {
  if (!/(38|40|27)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return;

  e.preventDefault();
  e.stopPropagation();

  // ESC.
  if (e.which === 27) {
    CivicCollapsible.prototype.collapseAllGroups();
    return;
  }

  if (this !== document) {
    // Up.
    if (e.which === 38) {
      this.dispatchEvent(new CustomEvent('civic.collapsible.collapse', { bubbles: true }));
      return;
    }

    // Down.
    if (e.which === 40) {
      this.dispatchEvent(new CustomEvent('civic.collapsible.expand', { bubbles: true }));
    }
  }
};

/**
 * Close "other" instances in the group.
 */
CivicCollapsible.prototype.closeGroup = function (group) {
  const currentEl = this.el;
  document.querySelectorAll(`[data-collapsible-group="${group}"]:not([data-collapsible-collapsed])`).forEach((el) => {
    if (el !== currentEl) {
      el.dispatchEvent(new CustomEvent('civic.collapsible.collapse', { bubbles: true }));
    }
  });
};

/**
 * Close all grouped instances on the page.
 */
CivicCollapsible.prototype.collapseAllGroups = function () {
  document.querySelectorAll('[data-collapsible-group]').forEach((el) => {
    el.dispatchEvent(new CustomEvent('civic.collapsible.collapse', { bubbles: true }));
  });
};

/**
 * Collapse panel.
 *
 * @param {boolean} animate
 *   Flag to collapse with animation.
 */
CivicCollapsible.prototype.collapse = function (animate) {
  const t = this;

  if (t.el.hasAttribute('data-collapsible-collapsed')) {
    return;
  }

  // Helper to set attributes after collapsing.
  const setAttributes = function (obj) {
    obj.panel.style.transition = '';
    obj.panel.style.overflow = 'hidden';
    obj.el.setAttribute('data-collapsible-collapsed', '');
    obj.panel.setAttribute('aria-hidden', true);
    obj.trigger.setAttribute('aria-expanded', false);
    obj.collapsed = true;
  };

  if (animate && t.duration > 0) {
    // Support already set transitions.
    const transition = t.panel.style.transition || `height ${t.duration}ms ease-out`;
    // Reset transition and set overflow before animation starts.
    t.panel.style.transition = '';
    t.panel.style.overflow = 'hidden';
    // Get height before animation starts.
    const h = t.panel.scrollHeight;
    requestAnimationFrame(() => {
      // Prepare for animation by setting initial values.
      t.panel.style.transition = transition;
      t.panel.style.height = `${h}px`;
      // Set progress state.
      t.el.setAttribute('data-collapsible-collapsing', '');
      requestAnimationFrame(() => {
        // Register an event listener to fire at the end of the transition.
        t.panel.addEventListener('transitionend', function () {
          // Remove the event listener straight away.
          // eslint-disable-next-line no-caller, no-restricted-properties
          t.panel.removeEventListener('transitionend', arguments.callee);
          // Remove progress state.
          t.el.removeAttribute('data-collapsible-collapsing');
          // Set all required attributes.
          setAttributes(t);
        });
        // Finally, change the height, triggering the transition.
        t.panel.style.height = '0px';
      });
    });
  } else {
    // Store current transition before it will be reset.
    const transition = t.panel.style;
    setAttributes(t);
    // Restore transition.
    t.panel.style.transition = transition;
  }
};

/**
 * Expand panel.
 *
 * @param {boolean} animate
 *   Flag to expand with animation.
 */
CivicCollapsible.prototype.expand = function (animate) {
  const t = this;

  if (!t.el.hasAttribute('data-collapsible-collapsed')) {
    return;
  }

  // Helper to set attributes after collapsing.
  const setAttributes = function (obj) {
    obj.panel.style.transition = '';
    obj.panel.style.overflow = '';
    obj.panel.style.height = '';
    obj.panel.setAttribute('aria-hidden', false);
    obj.trigger.setAttribute('aria-expanded', true);
    obj.el.removeAttribute('data-collapsible-collapsed');
    obj.collapsed = false;
  };

  if (animate && t.duration > 0) {
    // Get height before animation starts.
    const h = t.panel.scrollHeight;

    // Set progress state.
    t.el.setAttribute('data-collapsible-collapsing', '');
    requestAnimationFrame(() => {
      // Prepare for animation by setting initial values.
      t.panel.style.transition = t.panel.style.transition || `height ${t.duration}ms ease-out`;

      requestAnimationFrame(() => {
        // Register an event listener to fire at the end of the transition.
        t.panel.addEventListener('transitionend', function () {
          // Remove the event listener straight away.
          // eslint-disable-next-line no-caller, no-restricted-properties
          t.panel.removeEventListener('transitionend', arguments.callee);
          // Set all required attributes.
          setAttributes(t);
          // Remove progress state.
          t.el.removeAttribute('data-collapsible-collapsing');
        });
        // Finally, change the height, triggering the transition.
        t.panel.style.height = `${h}px`;
      });
    });
  } else {
    const transition = t.panel.style;
    setAttributes(t);
    t.panel.style.transition = transition;
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
