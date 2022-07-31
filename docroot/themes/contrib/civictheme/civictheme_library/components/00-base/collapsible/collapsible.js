// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
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
function CivicCollapsible(el) {
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

  // Make sure that both trigger and a panel have required attributes set.
  this.trigger.setAttribute('data-collapsible-trigger', '');
  this.panel.setAttribute('data-collapsible-panel', '');

  // Attach event listener.
  this.trigger.addEventListener('click', this.clickEvent.bind(this));
  this.trigger.addEventListener('keydown', this.keydownEvent.bind(this.trigger));
  this.trigger.addEventListener('focusout', this.focusoutEvent.bind(this));
  this.panel.addEventListener('click', (e) => e.stopPropagation());
  // @todo - These lines have been disabled as they break the CivicLargeFilter dropdowns.
  // this.panel.addEventListener('focusout', this.focusoutEvent.bind(this));
  // this.panel.addEventListener('focusin', this.focusinEvent.bind(this));

  // Collapse if was set as initially collapsed.
  if (this.collapsed) {
    this.collapse();
  }

  this.el.addEventListener('civictheme.collapsible.collapse', (evt) => {
    // For some cases (like group collapse) - the animation should be disabled.
    const animate = (evt.detail && evt.detail.animate);
    const isCloseAllEvent = (evt.detail && evt.detail.closeAll);
    if ((isCloseAllEvent && this.isGroupsEnabled) || !isCloseAllEvent) {
      this.collapse(animate);
    }
  });

  this.el.addEventListener('civictheme.collapsible.expand', () => {
    this.expand(true);
  });

  this.el.addEventListener('civictheme.collapsible.toggle', () => {
    if (this.isCollapsed(this.el)) {
      this.el.dispatchEvent(new CustomEvent('civictheme.collapsible.expand', { bubbles: true }));
    } else {
      this.el.dispatchEvent(new CustomEvent('civictheme.collapsible.collapse', { bubbles: true, detail: { animate: true } }));
    }
  });

  // Attach global keydown event listener to allow closing all collapsibles.
  document.addEventListener('keydown', CivicCollapsible.prototype.keydownEvent);
  document.addEventListener('click', CivicCollapsible.prototype.collapseAllGroups);

  // Responsive Collapsible Group.
  this.isGroupsEnabled = true;
  this.groupEnabledBreakpoint = this.el.getAttribute('data-collapsible-group-enabled-breakpoint');
  if (this.groupEnabledBreakpoint) {
    window.addEventListener('civictheme-responsive', (evt) => {
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
    this.el.dispatchEvent(new CustomEvent('civictheme.collapsible.expand', { bubbles: true }));
  } else {
    this.el.dispatchEvent(new CustomEvent('civictheme.collapsible.collapse', { bubbles: true, detail: { animate: true } }));
  }
};

/**
 * Focusin event handler.
 */
CivicCollapsible.prototype.focusinEvent = function (e) {
  const focusable = this.findFocusable(e.relatedTarget, e.target);
  if (focusable) {
    focusable.focus();
  }
};

/**
 * Focusout event handler.
 */
CivicCollapsible.prototype.focusoutEvent = function (e) {
  // Close when trigger or panel leaves a focus, but only for grouped ones.
  if (
    e.relatedTarget
    && !this.panel.contains(e.relatedTarget)
    && !this.trigger.contains(e.relatedTarget)
    && this.group
    && this.isGroupsEnabled
  ) {
    e.target.dispatchEvent(new CustomEvent('civictheme.collapsible.collapse', { bubbles: true }));
  }
};

/**
 * React on pressed keys.
 */
CivicCollapsible.prototype.keydownEvent = function (e) {
  if (!/(32|27|38|40)/.test(e.which) || e.altKey || e.ctrlKey || e.metaKey || /input|textarea|select|object/i.test(e.target.tagName)) {
    return;
  }

  e.stopPropagation();
  e.preventDefault();

  // ESC.
  if (e.which === 27) {
    CivicCollapsible.prototype.collapseAllGroups();
    return;
  }

  if (this !== document) {
    // Up.
    if (e.which === 38 && !e.shiftKey) {
      this.dispatchEvent(new CustomEvent('civictheme.collapsible.collapse', { bubbles: true, detail: { animate: true } }));
      return;
    }

    // Down.
    if (e.which === 40 && !e.shiftKey) {
      this.dispatchEvent(new CustomEvent('civictheme.collapsible.expand', { bubbles: true }));
    }

    // Space.
    if (e.which === 32) {
      this.dispatchEvent(new CustomEvent('civictheme.collapsible.toggle', { bubbles: true }));
    }
  }
};

/**
 * Close "other" instances in the group.
 */
CivicCollapsible.prototype.closeGroup = function (group) {
  if (this.isGroupsEnabled) {
    const currentEl = this.el;
    // eslint-disable-next-line prefer-template
    document.querySelectorAll('[data-collapsible-group=' + group + ']:not([data-collapsible-collapsed])').forEach((el) => {
      if (el !== currentEl) {
        el.dispatchEvent(new CustomEvent('civictheme.collapsible.collapse', { bubbles: true }));
      }
    });
  }
};

/**
 * Close all grouped instances on the page.
 */
CivicCollapsible.prototype.collapseAllGroups = function () {
  document.querySelectorAll('[data-collapsible-group]').forEach((el) => {
    el.dispatchEvent(new CustomEvent('civictheme.collapsible.collapse', { bubbles: true, detail: { closeAll: true } }));
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

  if (this.isCollapsed(t.el)) {
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

  if (!this.isCollapsed(t.el)) {
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

/**
 * Check if the collapsible is collapsed.
 */
CivicCollapsible.prototype.isCollapsed = function (el) {
  return el.hasAttribute('data-collapsible-collapsed');
};

/**
 * Get trigger element.
 */
CivicCollapsible.prototype.getTrigger = function (el) {
  return el.querySelector('[data-collapsible-trigger]') || el.firstElementChild || null;
};

/**
 * Get panel element.
 */
CivicCollapsible.prototype.getPanel = function (el) {
  return el.querySelector('[data-collapsible-panel]') || this.getTrigger(el).nextElementSibling || null;
};

/**
 * Find next or previous element in the DOM, based on elements position.
 */
CivicCollapsible.prototype.findFocusable = function (el, nextEl) {
  const documentElements = Array.from(document.querySelectorAll('*'));
  // Decide the direction of tabbing based on the position of the elements in
  // DOM.
  if (documentElements.indexOf(el) < documentElements.indexOf(nextEl)) {
    return this.findNextFocusable(el);
  }

  return this.findPreviousFocusable(el);
};

/**
 * Find next focusable element in DOM.
 */
CivicCollapsible.prototype.findNextFocusable = function (el) {
  const focusable = this.allFocusable();

  for (let i = 0; i < focusable.length; i++) {
    if (focusable[i] === el) {
      return focusable[i + 1];
    }
  }

  return null;
};

/**
 * Find previous focusable element in DOM.
 */
CivicCollapsible.prototype.findPreviousFocusable = function (el) {
  const focusable = this.allFocusable();

  for (let i = 0; i < focusable.length; i++) {
    if (focusable[i] === el) {
      return i > 0 ? focusable[i - 1] : null;
    }
  }

  return null;
};

/**
 * Get all focusable elements.
 */
CivicCollapsible.prototype.allFocusable = function () {
  const focusable = [];

  // Limit a set of supported focusable elements.
  const elements = Array.from(document.querySelectorAll('input,select,textarea,button,object,area,a'));
  for (let i = 0; i < elements.length; i++) {
    if (this.isFocusable(elements[i])) {
      focusable.push(elements[i]);
    }
  }

  return focusable;
};

/**
 * Check if the element is focusable.
 */
CivicCollapsible.prototype.isFocusable = function (element) {
  const nodeName = element.nodeName.toLowerCase();
  const tabIndex = element.getAttribute('tabindex');
  const isTabIndexNaN = Number.isNaN(tabIndex);

  // Get all parents of an element.
  function getParents(el) {
    const parents = [];
    while (el) {
      parents.unshift(el);
      el = el.parentElement;
    }
    return parents;
  }

  // Check if an element itself is visible.
  function elIsVisible(el) {
    if (!(el instanceof Element)) {
      throw Error('DomUtil: el is not an element.');
    }

    const style = getComputedStyle(el);

    if (style.display === 'none'
      || style.visibility !== 'visible'
      || style.opacity < 0.1
      || (
        el.offsetWidth
        + el.offsetHeight
        + el.getBoundingClientRect().height
        + el.getBoundingClientRect().width === 0)
    ) {
      return false;
    }

    const elemCenter = {
      x: el.getBoundingClientRect().left + el.offsetWidth / 2,
      y: el.getBoundingClientRect().top + el.offsetHeight / 2,
    };
    if (elemCenter.x < 0
      || elemCenter.y < 0
      || elemCenter.x > (document.documentElement.clientWidth || window.innerWidth)
      || elemCenter.y > (document.documentElement.clientHeight || window.innerHeight)
    ) {
      return false;
    }

    let pointContainer = document.elementFromPoint(elemCenter.x, elemCenter.y);
    while (pointContainer) {
      if (pointContainer === el) {
        return true;
      }
      pointContainer = pointContainer.parentNode;
    }

    return false;
  }

  // Check if an element is visible.
  function isVisible(el) {
    const parents = getParents(el);
    // Visible if the element and all of it's parents are visible. If at least
    // one of the parents is not visible - the element is not visible.
    return elIsVisible(el) && parents.filter((elem) => elIsVisible(elem)).length === parents.length;
  }

  // Special handling for image maps.
  if (nodeName === 'area') {
    const map = element.parentNode;
    const mapName = map.name;
    if (!element.href || !mapName || map.nodeName.toLowerCase() !== 'map') {
      return false;
    }
    const img = document.querySelectorAll(`img[usemap=#${mapName}]`)[0];
    return !!img && isVisible(img);
  }

  // One of the supported elements.
  if (/^(input|select|textarea|button|object)$/.test(nodeName)) {
    return !element.disabled && isVisible(element) && (isTabIndexNaN || tabIndex >= 0);
  }

  // Or a visible link with a href.
  return (nodeName === 'a' ? element.href || !isTabIndexNaN : !isTabIndexNaN) && isVisible(element) && (isTabIndexNaN || tabIndex >= 0);
};

document.querySelectorAll('[data-collapsible]').forEach((el) => {
  // Delay initialisation if should be responsive.
  const breakpointExpr = el.getAttribute('data-responsive');
  if (breakpointExpr) {
    window.addEventListener('civictheme-responsive', (evt) => {
      evt.detail.evaluate(breakpointExpr, CivicCollapsible, el);
    }, false);
    return;
  }

  new CivicCollapsible(el);
});
