function CivicFlyout(el) {
  if (el.getAttribute('data-flyout') === 'true' || this.el) {
    return;
  }

  // Find all open triggers.
  const openTriggers = document.querySelectorAll('[data-flyout-open-trigger]');
  if (!openTriggers.length) {
    return;
  }

  // Find an open trigger.
  this.openTrigger = this.findOpenTrigger(openTriggers, el);
  if (!this.openTrigger) {
    return;
  }

  this.el = el;

  // Find "close trigger", but only search among triggers that are not a part
  // of descendant flyouts.
  this.closeTrigger = this.el.querySelector('[data-flyout-close-trigger]');
  if (this.closeTrigger && this.closeTrigger.closest('[data-flyout]') !== this.el) {
    this.closeTrigger = null;
  }

  this.closeAllTrigger = this.el.querySelector('[data-flyout-close-all-trigger]');
  this.panel = this.el.querySelector('[data-flyout-panel]');
  this.el.expanded = this.el.hasAttribute('data-flyout-expanded');
  this.duration = this.el.hasAttribute('data-flyout-duration') ? parseInt(this.el.getAttribute('data-flyout-duration'), 10) : 500;

  // Add event listener to element.
  if (this.openTrigger) {
    this.openTrigger.addEventListener('click', this.clickEvent.bind(this));
    this.openTrigger.expand = true;
  }

  if (this.closeTrigger) {
    this.closeTrigger.addEventListener('click', this.clickEvent.bind(this));
    this.closeTrigger.expand = false;
  }

  if (this.closeAllTrigger) {
    this.closeAllTrigger.addEventListener('click', this.closeAllTriggerClickEvent.bind(this));
  }

  // Mark as initialized.
  this.el.setAttribute('data-flyout', 'true');
}

CivicFlyout.prototype.findOpenTrigger = function (triggers, el) {
  // Find a trigger for the current flyout.
  for (const i in triggers) {
    if (Object.prototype.hasOwnProperty.call(triggers, i)) {
      if (triggers[i].hasAttribute('data-flyout-target')) {
        const found = document.querySelector(triggers[i].getAttribute('data-flyout-target'));
        if (found === el) {
          return triggers[i];
        }
      } else if (triggers[i].nextElementSibling && triggers[i].nextElementSibling.hasAttribute('data-flyout')) {
        // Try to get from the next element.
        const found = triggers[i].nextElementSibling;
        if (found === el) {
          return triggers[i];
        }
      }
    }
  }
  return null;
};

CivicFlyout.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  return e.currentTarget.expand ? this.expand() : this.collapse();
};

CivicFlyout.prototype.closeAllTriggerClickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  // Collapse all panels.
  document.querySelectorAll('[data-flyout-expanded]').forEach((flyout) => {
    flyout.removeAttribute('data-flyout-expanded');
  });
  document.querySelectorAll('[data-flyout-panel]').forEach((panel) => {
    panel.setAttribute('aria-hidden', true);
    setTimeout(() => {
      panel.style.visibility = null;
    }, 500);
  });
  document.querySelectorAll('[data-flyout-trigger]').forEach((trigger) => {
    trigger.setAttribute('aria-expanded', false);
  });
};

CivicFlyout.prototype.expand = function () {
  this.el.expanded = true;
  this.openTrigger.setAttribute('aria-expanded', true);
  this.panel.style.visibility = 'visible';

  // Add required classes.
  this.el.setAttribute('data-flyout-expanded', true);
  this.panel.setAttribute('aria-hidden', false);
};

CivicFlyout.prototype.collapse = function () {
  this.el.expanded = false;
  this.openTrigger.setAttribute('aria-expanded', false);
  this.el.removeAttribute('data-flyout-expanded');
  this.panel.setAttribute('aria-hidden', true);
  setTimeout(() => {
    this.panel.style.visibility = null;
  }, this.duration);
};

// Initialize CivicFlyout on every element.
document.querySelectorAll('[data-flyout]').forEach((flyout) => {
  // eslint-disable-next-line no-new
  new CivicFlyout(flyout);
});
