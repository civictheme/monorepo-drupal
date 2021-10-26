function CivicMobileMenu(el) {
  this.el = el;
  this.expandedClasses = {
    trigger: 'civic-header__menu-toggle--expanded',
    menu: 'civic-mobile-menu--expanded',
  };
  this.mobileMenuOpenTrigger = this.el;
  this.targetComponentName = this.mobileMenuOpenTrigger.getAttribute('data-component-target-name');
  this.mobileMenuCloseTrigger = document.querySelector('[data-component-name="civic-mobile-menu-close-trigger"]');
  this.mobileMenu = document.querySelector(`[data-component-name="${this.targetComponentName}"]`);
  this.mobilewMenuMenuLinks = this.mobileMenu.querySelectorAll('.civic-menu-item');
  this.el.expanded = this.el.classList.contains(this.expandedClasses.trigger);
  this.mobileMenuOpenTrigger.addEventListener('click', this.openEvent.bind(this));
  this.mobileMenuCloseTrigger.addEventListener('click', this.closeEvent.bind(this));
}

// eslint-disable-next-line func-names
CivicMobileMenu.prototype.openEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  this.open();
};

// eslint-disable-next-line func-names
CivicMobileMenu.prototype.closeEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  this.close();
};

// eslint-disable-next-line func-names
CivicMobileMenu.prototype.open = function () {
  this.mobileMenuOpenTrigger.expanded = true;
  this.mobileMenuOpenTrigger.setAttribute('aria-expanded', true);

  // Add required classes.
  this.mobileMenuOpenTrigger.classList.add(this.expandedClasses.trigger);
  this.mobileMenu.classList.add(this.expandedClasses.menu);
  this.mobileMenu.setAttribute('aria-hidden', false);
};

// eslint-disable-next-line func-names
CivicMobileMenu.prototype.close = function () {
  this.mobileMenuOpenTrigger.expanded = false;
  this.mobileMenuOpenTrigger.setAttribute('aria-expanded', false);
  this.mobileMenuOpenTrigger.classList.remove(this.expandedClasses.trigger);
  this.mobileMenu.classList.remove(this.expandedClasses.menu);
  this.mobileMenu.setAttribute('aria-hidden', true);
};

// Initialize CivicCollapsible on every element.
document.querySelectorAll('[data-component-name="civic-mobile-menu-open-trigger"]').forEach((mobileMenu) => {
  // eslint-disable-next-line no-new
  new CivicMobileMenu(mobileMenu);
});
