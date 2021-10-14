function CivicCollapsible(el) {
  if (!el) {
    return;
  }

  this.el = el;
  this.collapsibleTrigger = this.el.querySelector('.civic-collapsible__title');
  this.collapsiblePanel = this.el.querySelector('.civic-collapsible__content');
  this.el.expanded = this.el.classList.contains('civic-collapsible--expanded');
  this.isToggeling = false;

  // Add event listener to element.
  this.collapsibleTrigger.addEventListener('click', this.clickEvent.bind(this));
}

// eslint-disable-next-line func-names
CivicCollapsible.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  this.toggle();
};

// eslint-disable-next-line func-names
CivicCollapsible.prototype.toggle = function () {
  if (!this.el.expanded) {
    this.el.expanded = true;
    const currentPanel = this.collapsiblePanel;
    this.collapsibleTrigger.setAttribute('aria-expanded', true);
    this.collapsiblePanel.style.height = `${this.collapsiblePanel.scrollHeight}px`;
    setTimeout(() => {
      // Remove the fixed height after transition so it can be responsive
      currentPanel.style.height = 'auto';
    }, 500);
    this.collapsiblePanel.style.visibility = 'visible';

    // Add required classes.
    this.collapsibleTrigger.classList.add('civic-collapsible__title--expanded');
    this.el.classList.add('civic-collapsible--expanded');
    this.collapsiblePanel.classList.add('civic-collapsible__content--expanded');
    this.collapsiblePanel.setAttribute('aria-hidden', false);
  } else {
    this.el.expanded = false;
    this.collapsibleTrigger.setAttribute('aria-expanded', false);
    this.collapsibleTrigger.classList.remove('civic-collapsible__title--expanded');
    this.el.classList.remove('civic-collapsible--expanded');
    this.collapsiblePanel.classList.remove('civic-collapsible__content--expanded');
    const currentPanel = this.collapsiblePanel;
    this.collapsiblePanel.style.height = `${this.collapsiblePanel.scrollHeight}px`;
    setTimeout(() => {
      currentPanel.style.height = '';
      currentPanel.style.visibility = '';
    }, 1);

    this.collapsiblePanel.setAttribute('aria-hidden', true);
  }
};

// Initialize CivicCollapsible on every element.
document.querySelectorAll('[data-component-name="civic-collapsible"]').forEach((collapsible) => {
  // eslint-disable-next-line no-new
  new CivicCollapsible(collapsible);
});
