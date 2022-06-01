/**
 * @file
 * Accordion component.
 */
function CivicAccordion(el, selectedIndex) {
  if (!el) {
    return;
  }

  this.el = el;
  this.accordionItems = this.el.querySelectorAll('[data-accordion-list-item]');
  this.accordionTriggers = this.el.querySelectorAll('[data-accordion-header-button]');
  this.accordionPanels = this.el.querySelectorAll('[data-accordion-content]');

  if (this.accordionTriggers.length === 0
    || this.accordionTriggers.length !== this.accordionPanels.length
    || this.accordionItems.length === 0) {
    return;
  }

  this.init(selectedIndex);
}

// eslint-disable-next-line func-names
CivicAccordion.prototype.init = function () {
  this.accordionTriggersLength = this.accordionTriggers.length;
  this.expandedAccordions = new Array(this.accordionTriggersLength);
  this.multiSelectable = this.el.hasAttribute('data-multiselectable');
  this.clickListener = this.clickEvent.bind(this);
  this.keydownListener = this.keydownEvent.bind(this);
  this.focusListener = this.focusEvent.bind(this);
  this.keys = {
    prev: 38,
    next: 40,
    space: 32,
  };

  for (let i = 0; i < this.accordionTriggersLength; i += 1) {
    this.accordionTriggers[i].index = i;
    this.accordionTriggers[i].addEventListener('click', this.clickListener, false);
    this.accordionTriggers[i].addEventListener('keydown', this.keydownListener, false);
    this.accordionTriggers[i].addEventListener('focus', this.focusListener, false);

    if (this.accordionTriggers[i].hasAttribute('is-selected')) {
      this.expandedAccordions[i] = true;
    }
  }
  this.setExpanded();
  this.el.setAttribute('is-initialized', true);
};

// eslint-disable-next-line func-names
CivicAccordion.prototype.clickEvent = function (e) {
  e.preventDefault();

  this.setSelected(e.currentTarget.index, true);
  this.setExpanded(e.currentTarget.index, true);
};

// eslint-disable-next-line func-names
CivicAccordion.prototype.keydownEvent = function (e) {
  let targetIndex;

  switch (e.keyCode) {
    case this.keys.space:
    case this.keys.prev:
    case this.keys.next:
      e.preventDefault();
      break;
    default:
      return;
  }

  if (e.keyCode === this.keys.space) {
    this.setExpanded(e.currentTarget.index, true);
  } else if (e.keyCode === this.keys.prev && e.target.index > 0) {
    targetIndex = e.currentTarget.index - 1;
  } else if (e.keyCode === this.keys.next && e.target.index < this.accordionTriggersLength - 1) {
    targetIndex = e.currentTarget.index + 1;
  }

  this.setSelected(targetIndex, true);
};

// eslint-disable-next-line func-names
CivicAccordion.prototype.focusEvent = function () {
  if (this.accordionTriggersLength === 1) {
    this.setSelected(0);
  }
};

// eslint-disable-next-line func-names
CivicAccordion.prototype.setSelected = function (index, userInvoked) {
  if (index === -1) {
    return;
  }

  for (let i = 0; i < this.accordionTriggersLength; i += 1) {
    const currentButton = this.accordionTriggers[i];
    if (i === index) {
      currentButton.setAttribute('is-selected', true);
      this.accordionItems[i].setAttribute('data-accordion-list-item-expanded', true);
      currentButton.setAttribute('data-accordion-header-button-expanded', true);

      currentButton.setAttribute('data-selected', true);

      if (userInvoked) {
        currentButton.focus();
      }
    } else {
      currentButton.removeAttribute('data-accordion-header-button-expanded');
      this.accordionItems[i].removeAttribute('data-accordion-list-item-expanded');
      currentButton.setAttribute('data-selected', false);
    }
  }
};

// eslint-disable-next-line func-names
CivicAccordion.prototype.setExpanded = function (index, userInvoked) {
  let i;

  if (userInvoked) {
    if (this.multiSelectable) {
      this.expandedAccordions[index] = !this.expandedAccordions[index];
    } else {
      for (i = 0; i < this.accordionTriggersLength; i += 1) {
        if (i === index) {
          this.expandedAccordions[i] = !this.expandedAccordions[i];
        } else {
          this.expandedAccordions[i] = false;
        }
      }
    }
  }

  for (i = 0; i < this.accordionTriggersLength; i += 1) {
    if (this.expandedAccordions[i]) {
      const currentPanel = this.accordionPanels[i];
      this.accordionTriggers[i].setAttribute('aria-expanded', true);
      this.accordionPanels[i].style.height = `${this.accordionPanels[i].scrollHeight}px`;
      setTimeout(() => {
        // Remove the fixed height after transition so it can be responsive
        currentPanel.style.height = 'auto';
      }, 500);
      this.accordionPanels[i].style.visibility = 'visible';

      // Add required classes.
      this.accordionTriggers[i].setAttribute('data-accordion-header-button-expanded', true);
      this.accordionItems[i].setAttribute('data-accordion-list-item-expanded', true);
      this.accordionPanels[i].setAttribute('data-accordion-content-expanded', true);

      this.accordionPanels[i].setAttribute('aria-hidden', false);
      this.accordionPanels[i].removeAttribute('is-hidden');
    } else {
      this.accordionTriggers[i].setAttribute('aria-expanded', false);
      this.accordionTriggers[i].removeAttribute('is-expanded');
      this.accordionTriggers[i].removeAttribute('data-accordion-header-button-expanded');
      this.accordionItems[i].removeAttribute('data-accordion-list-item-expanded');
      this.accordionPanels[i].removeAttribute('data-accordion-content-expanded');
      const currentPanel = this.accordionPanels[i];
      this.accordionPanels[i].style.height = `${this.accordionPanels[i].scrollHeight}px`;
      setTimeout(() => {
        currentPanel.style.height = '';
        currentPanel.style.visibility = '';
      }, 1);

      this.accordionPanels[i].setAttribute('aria-hidden', true);
      this.accordionPanels[i].setAttribute('is-hidden', true);
    }
  }
};

// eslint-disable-next-line func-names
CivicAccordion.prototype.destroy = function () {
  this.el.removeAttribute('is-initialized');

  for (let i = 0; i < this.accordionTriggersLength; i += 1) {
    this.accordionTriggers[i].removeAttribute('aria-expanded');
    this.accordionTriggers[i].removeAttribute('data-selected');
    this.accordionTriggers[i].removeAttribute('is-expanded');

    this.accordionPanels[i].removeAttribute('aria-hidden');
    this.accordionPanels[i].removeAttribute('is-hidden');

    this.accordionTriggers[i].removeEventListener('click', this.clickListener, false);
    this.accordionTriggers[i].removeEventListener('keydown', this.keydownListener, false);
    this.accordionTriggers[i].removeEventListener('focus', this.focusListener, false);

    delete this.accordionTriggers[i].index;
  }
};

document.querySelectorAll('[data-accordion] [data-accordion-list]').forEach((accordion) => {
  // eslint-disable-next-line no-new
  new CivicAccordion(accordion);
});
