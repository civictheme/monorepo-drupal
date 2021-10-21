function CivicFlyout(el) {
  if (!el) {
    return;
  }

  this.el = el;
  this.flyoutTrigger = this.el.querySelector('.civic-flyout__trigger');
  this.flyoutClose = this.el.querySelector('.civic-flyout__close');
  this.flyoutCloseAll = this.el.querySelector('.civic-flyout__close-all');
  this.flyoutPanel = this.el.querySelector('.civic-flyout__content');
  this.el.expanded = this.el.classList.contains('civic-flyout--expanded');
  this.isToggling = false;

  // Add event listener to element.
  this.flyoutTrigger.addEventListener('click', this.clickEvent.bind(this));
  this.flyoutTrigger.expand = true
  this.flyoutClose.addEventListener('click', this.clickEvent.bind(this));
  this.flyoutClose.expand = false
  this.flyoutCloseAll.addEventListener('click', this.closeAllClickEvent.bind(this));
}

// eslint-disable-next-line func-names
CivicFlyout.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  e.currentTarget.expand ? this.expand() : this.collapse();
};

// eslint-disable-next-line func-names
CivicFlyout.prototype.closeAllClickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();
  
  // todo: add collapse for each flyout
};

// eslint-disable-next-line func-names
CivicFlyout.prototype.expand = function () {
  this.el.expanded = true;
  const currentPanel = this.flyoutPanel;
  this.flyoutTrigger.setAttribute('aria-expanded', true);
  this.flyoutTrigger.classList.add('civic-flyout__trigger--expanding');
  setTimeout(() => {
    this.flyoutTrigger.classList.remove('civic-flyout__trigger--expanding');
  }, 500);
  this.flyoutPanel.style.visibility = 'visible';

  // Add required classes.
  this.flyoutTrigger.classList.add('civic-flyout__trigger--expanded');
  this.el.classList.add('civic-flyout--expanded');
  this.flyoutPanel.classList.add('civic-flyout__content--expanded');
  this.flyoutPanel.setAttribute('aria-hidden', false);
};

// eslint-disable-next-line func-names
CivicFlyout.prototype.collapse = function () {
  this.el.expanded = false;
  this.flyoutTrigger.setAttribute('aria-expanded', false);
  this.flyoutTrigger.classList.remove('civic-flyout__trigger--expanded');
  this.el.classList.remove('civic-flyout--expanded');
  this.flyoutPanel.classList.remove('civic-flyout__content--expanded');
  const currentPanel = this.flyoutPanel;
  this.flyoutTrigger.classList.add('civic-flyout__trigger--collapsing');
  setTimeout(() => {
    this.flyoutTrigger.classList.remove('civic-flyout__trigger--collapsing');
    currentPanel.style.visibility = '';
  }, 500);

  this.flyoutPanel.setAttribute('aria-hidden', true);
};

// Initialize CivicFlyout on every element.
document.querySelectorAll('[data-component-name="civic-flyout"]').forEach((flyout) => {
  // eslint-disable-next-line no-new
  new CivicFlyout(flyout);
});
