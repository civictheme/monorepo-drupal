// phpcs:ignoreFile
/**
 * @file
 * Collapsible component.
 *
 * Attaches to markup with 'data-collapsible' attribute.
 * Available attributes:
 * - data-collapsible-trigger - trigger for the collapsible. If not provided,
 *   then the first descendant will be used.
 * - data-collapsible-panel - panel for the collapsible. If not provided,
 *   then the second descendant will be used.
 * - data-collapsible-collapsed - indicate that a starting state is collapsed.
 * - data-collapsible-duration - duration in milliseconds. Defaults to 500.
 * - data-collapsible-group-enabled-breakpoint - enable grouping at breakpoint.
 *   Needs 'data-responsive' attribute.
 */
function CivicThemeCollapsible(el) {
  // Use "data-collapsible"'s attribute value to identify if this component was
  // already initialised.
  if (el.getAttribute('data-collapsible') === 'true' || this.el) {
    return;
  }

  const trigger = this.getTrigger(el);
  const panel = this.getPanel(el);

  // Exit early if trigger or panel were not found.
  if (!trigger || !panel) {
    return;
  }

  this.el = el;
  this.trigger = trigger;
  this.panel = panel;
  this.collapsed = this.isCollapsed(el);
  this.duration = this.el.hasAttribute('data-collapsible-duration') ? this.el.getAttribute('data-collapsible-duration') : 500;
  this.group = this.el.hasAttribute('data-collapsible-group') ? this.el.getAttribute('data-collapsible-group') : null;
  this.icon = '<svg class="ct-icon" width="24" height="24" aria-hidden="true" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.6072 8.38619C18.3583 8.13884 18.0217 8 17.6709 8C17.32 8 16.9834 8.13884 16.7346 8.38619L11.9668 13.0876L7.26542 8.38619C7.01659 8.13884 6.67999 8 6.32913 8C5.97827 8 5.64167 8.13884 5.39284 8.38619C5.26836 8.50965 5.16956 8.65654 5.10214 8.81838C5.03471 8.98022 5 9.1538 5 9.32912C5 9.50445 5.03471 9.67803 5.10214 9.83987C5.16956 10.0017 5.26836 10.1486 5.39284 10.2721L11.0239 15.9031C11.1473 16.0276 11.2942 16.1264 11.4561 16.1938C11.6179 16.2612 11.7915 16.2959 11.9668 16.2959C12.1421 16.2959 12.3157 16.2612 12.4775 16.1938C12.6394 16.1264 12.7863 16.0276 12.9097 15.9031L18.6072 10.2721C18.7316 10.1486 18.8304 10.0017 18.8979 9.83987C18.9653 9.67803 19 9.50445 19 9.32912C19 9.1538 18.9653 8.98022 18.8979 8.81838C18.8304 8.65654 18.7316 8.50965 18.6072 8.38619Z" /></svg>';

  // Make sure that both trigger and a panel have required attributes set.
  this.trigger.setAttribute('data-collapsible-trigger', '');
  this.panel.setAttribute('data-collapsible-panel', '');

  if (!this.panel.hasAttribute('data-collapsible-trigger-no-icon') && !this.trigger.querySelector('.ct-collapsible__icon')) {
    const iconEl = this.htmlToElement(this.icon);
    iconEl.classList.add('ct-collapsible__icon');
    this.trigger.append(iconEl);
  }

  // Attach event listener.
  this.trigger.addEventListener('click', this.clickEvent.bind(this));
  this.trigger.addEventListener('keydown', this.keydownEvent.bind(this.trigger));
  this.trigger.addEventListener('focusout', this.focusoutEvent.bind(this));
  this.panel.addEventListener('click', (e) => e.stopPropagation());
  this.panel.addEventListener('focusout', this.focusoutEvent.bind(this));

  // Init focusable elements.
  this.initFocusableElements(this.panel);

  // Collapse if was set as initially collapsed.
  if (this.collapsed) {
    this.collapse();
  }

  this.el.addEventListener('ct.collapsible.collapse', (evt) => {
    // For some cases (like group collapse) - the animation should be disabled.
    const animate = (evt.detail && evt.detail.animate);
    const isCloseAllEvent = (evt.detail && evt.detail.closeAll);
    if ((isCloseAllEvent && this.isGroupsEnabled) || !isCloseAllEvent) {
      this.collapse(animate, evt);
    }
  });

  this.el.addEventListener('ct.collapsible.expand', () => {
    this.expand(true);
  });

  this.el.addEventListener('ct.collapsible.toggle', () => {
    if (this.isCollapsed(this.el)) {
      this.el.dispatchEvent(new CustomEvent('ct.collapsible.expand', { bubbles: true }));
    } else {
      this.el.dispatchEvent(new CustomEvent('ct.collapsible.collapse', { bubbles: true, detail: { animate: true } }));
    }
  });

  // Attach global keydown event listener to allow closing all collapsibles.
  document.addEventListener('keydown', CivicThemeCollapsible.prototype.keydownEvent);
  document.addEventListener('click', CivicThemeCollapsible.prototype.collapseAllGroups);

  // Responsive Collapsible Group.
  this.isGroupsEnabled = true;
  this.groupEnabledBreakpoint = this.el.getAttribute('data-collapsible-group-enabled-breakpoint');
  if (this.groupEnabledBreakpoint) {
    window.addEventListener('ct-responsive', (evt) => {
      const evaluationResult = evt.detail.evaluate(this.groupEnabledBreakpoint, () => {
        // Is within breakpoint.
        this.isGroupsEnabled = true;
      });
      if (evaluationResult === false) {
        // Not within breakpoint.
        this.isGroupsEnabled = false;
      }
    }, false);
  }

  // Mark as initialized.
  this.el.setAttribute('data-collapsible', 'true');
}

/**
 * Destroy an instance.
 */
CivicThemeCollapsible.prototype.destroy = function (el) {
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
CivicThemeCollapsible.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  if (this.group) {
    this.closeGroup(this.group);
  }

  if (this.collapsed) {
    this.el.dispatchEvent(new CustomEvent('ct.collapsible.expand', { bubbles: true }));
  } else {
    this.el.dispatchEvent(new CustomEvent('ct.collapsible.collapse', { bubbles: true, detail: { animate: true } }));
  }
};

/**
 * Focusout event handler.
 */
CivicThemeCollapsible.prototype.focusoutEvent = function (e) {
  // Close when trigger or panel leaves a focus, but only for grouped ones.
  if (
    e.relatedTarget
    && !this.panel.contains(e.relatedTarget)
    && !this.trigger.contains(e.relatedTarget)
    && this.group
    && this.isGroupsEnabled
  ) {
    e.target.dispatchEvent(new CustomEvent('ct.collapsible.collapse', { bubbles: true }));
  }
};

/**
 * React on pressed keys.
 */
CivicThemeCollapsible.prototype.keydownEvent = function (e) {
  if (!/(32|27|38|40)/.test(e.which) || e.altKey || e.ctrlKey || e.metaKey || /input|textarea|select|object/i.test(e.target.tagName)) {
    return;
  }

  e.stopPropagation();
  e.preventDefault();

  // ESC.
  if (e.which === 27) {
    CivicThemeCollapsible.prototype.collapseAllGroups();
    return;
  }

  if (this !== document) {
    // Up.
    if (e.which === 38 && !e.shiftKey) {
      this.dispatchEvent(new CustomEvent('ct.collapsible.collapse', { bubbles: true, detail: { animate: true, keydown: true } }));
      return;
    }

    // Down.
    if (e.which === 40 && !e.shiftKey) {
      this.dispatchEvent(new CustomEvent('ct.collapsible.expand', { bubbles: true }));
    }

    // Space.
    if (e.which === 32) {
      e.target.click();
    }
  }
};

/**
 * Close "other" instances in the group.
 */
CivicThemeCollapsible.prototype.closeGroup = function (group) {
  if (this.isGroupsEnabled) {
    const currentEl = this.el;
    // eslint-disable-next-line prefer-template
    document.querySelectorAll('[data-collapsible-group=' + group + ']:not([data-collapsible-collapsed])').forEach((el) => {
      if (el !== currentEl) {
        el.dispatchEvent(new CustomEvent('ct.collapsible.collapse', { bubbles: true, detail: { closeGroup: true } }));
      }
    });
  }
};

/**
 * Close all grouped instances on the page.
 */
CivicThemeCollapsible.prototype.collapseAllGroups = function () {
  document.querySelectorAll('[data-collapsible-group]').forEach((el) => {
    el.dispatchEvent(new CustomEvent('ct.collapsible.collapse', { bubbles: true, detail: { closeAll: true } }));
  });
};

/**
 * Collapse panel.
 *
 * @param {boolean} animate
 *   Flag to collapse with animation.
 */
CivicThemeCollapsible.prototype.collapse = function (animate, evt) {
  const t = this;

  if (this.isCollapsed(t.el)) {
    return;
  }

  if (evt && evt.target) {
    if (evt.detail.keydown && !evt.detail.closeGroup) {
      if (evt.target.closest('[data-collapsible="true"]') !== t.el) {
        return;
      }
    } else if (evt.currentTarget !== t.el || evt.target !== t.el) {
      return;
    }
  }

  t.disableElementsFocus(t.panel);

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
CivicThemeCollapsible.prototype.expand = function (animate) {
  const t = this;

  if (!this.isCollapsed(t.el)) {
    return;
  }

  t.enableElementsFocus(t.panel);

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
    t.panel.style.display = '';
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

/**
 * Check if the collapsible is collapsed.
 */
CivicThemeCollapsible.prototype.isCollapsed = function (el) {
  return el.hasAttribute('data-collapsible-collapsed');
};

/**
 * Get trigger element.
 */
CivicThemeCollapsible.prototype.getTrigger = function (el) {
  return el.querySelector('[data-collapsible-trigger]') || el.firstElementChild || null;
};

/**
 * Get panel element.
 */
CivicThemeCollapsible.prototype.getPanel = function (el) {
  return el.querySelector('[data-collapsible-panel]') || this.getTrigger(el).nextElementSibling || null;
};

/**
 * Init focusable elements within a panel.
 */
CivicThemeCollapsible.prototype.initFocusableElements = function (panel) {
  this.disableElementsFocus(panel);
};

/**
 * Disable elements focus.
 */
CivicThemeCollapsible.prototype.disableElementsFocus = function (parent) {
  this.getFocusableElements(parent).forEach((el) => {
    el.setAttribute('tabindex', -1);
  });
};

/**
 * Enable elements focus.
 */
CivicThemeCollapsible.prototype.enableElementsFocus = function (parent) {
  this.getFocusableElements(parent).forEach((el) => {
    el.removeAttribute('tabindex');
  });
};

/**
 * Get focusable elements within an element.
 */
CivicThemeCollapsible.prototype.getFocusableElements = function (el) {
  return el.querySelectorAll('input, select, textarea, button, object, area, a');
};

/**
 * Convert HTML to a DOM element.
 */
CivicThemeCollapsible.prototype.htmlToElement = function (html) {
  const template = document.createElement('template');
  template.innerHTML = html.trim();
  return template.content.firstChild;
};

document.querySelectorAll('[data-collapsible]').forEach((el) => {
  // Delay initialisation if should be responsive.
  const breakpointExpr = el.getAttribute('data-responsive');
  if (breakpointExpr) {
    window.addEventListener('ct-responsive', (evt) => {
      evt.detail.evaluate(breakpointExpr, CivicThemeCollapsible, el);
    }, false);
    return;
  }

  new CivicThemeCollapsible(el);
});
