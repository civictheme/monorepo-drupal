function CivicFlyout(el) {
  if (!el) {
    return;
  }

  this.el = el;
  this.flyoutTrigger = this.el.querySelector('.civic-flyout__trigger');
  this.flyoutPanel = this.el.querySelector('.civic-flyout__content');
  this.el.expanded = this.el.classList.contains('civic-flyout--expanded');
  this.isToggling = false;

  // Add event listener to element.
  this.flyoutTrigger.addEventListener('click', this.clickEvent.bind(this));
}

// eslint-disable-next-line func-names
CivicFlyout.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  this.toggle();
};

// eslint-disable-next-line func-names
CivicFlyout.prototype.toggle = function () {
  if (!this.el.expanded) {
    this.el.expanded = true;
    const currentPanel = this.flyoutPanel;
    this.flyoutTrigger.setAttribute('aria-expanded', true);
    this.flyoutPanel.style.height = `${this.flyoutPanel.scrollHeight}px`;
    setTimeout(() => {
      // Remove the fixed height after transition so it can be responsive
      currentPanel.style.height = 'auto';
    }, 500);
    this.flyoutPanel.style.visibility = 'visible';

    // Add required classes.
    this.flyoutTrigger.classList.add('civic-flyout__trigger--expanded');
    this.el.classList.add('civic-flyout--expanded');
    this.flyoutPanel.classList.add('civic-flyout__content--expanded');
    this.flyoutPanel.setAttribute('aria-hidden', false);
  } else {
    this.el.expanded = false;
    this.flyoutTrigger.setAttribute('aria-expanded', false);
    this.flyoutTrigger.classList.remove('civic-flyout__trigger--expanded');
    this.el.classList.remove('civic-flyout--expanded');
    this.flyoutPanel.classList.remove('civic-flyout__content--expanded');
    const currentPanel = this.flyoutPanel;
    this.flyoutPanel.style.height = `${this.flyoutPanel.scrollHeight}px`;
    setTimeout(() => {
      currentPanel.style.height = '';
      currentPanel.style.visibility = '';
    }, 1);

    this.flyoutPanel.setAttribute('aria-hidden', true);
  }
};

// Initialize CivicFlyout on every element.
document.querySelectorAll('[data-component-name="civic-flyout"]').forEach((flyout) => {
  // eslint-disable-next-line no-new
  new CivicFlyout(flyout);
});
