function CivicDropdown(el) {
  if (!el) {
    return;
  }
  this.el = el;
  this.dropDownTrigger = this.el.querySelector('[data-component-name="civic-dropdown-trigger"]');
  this.dropDownMenu = this.el.querySelector('[data-component-name="civic-dropdown-menu"]');
  this.el.expanded = this.el.classList.contains('civic-dropdown--expanded');
  this.isToggling = false;

  // Add event listener to element.
  this.dropDownTrigger.addEventListener('click', this.clickEvent.bind(this));
  document.addEventListener('dropdownClose', this.dropDownCloseEvent.bind(this));
}

// eslint-disable-next-line func-names
CivicDropdown.prototype.clickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  e.stopImmediatePropagation();

  if (!this.el.expanded) {
    this.open(e);
  } else {
    this.close();
  }
};

// Only 1 dropdown can be open at one time.
CivicDropdown.prototype.dropDownCloseEvent = function (e) {
  if (e.dropdownTrigger !== this.dropDownTrigger) {
    this.close();
  }
};

// eslint-disable-next-line func-names
CivicDropdown.prototype.open = function () {
  document.dispatchEvent(new Event('dropdownClose', { dropdownTrigger: this.dropDownTrigger }));
  this.el.expanded = true;
  this.dropDownTrigger.setAttribute('aria-expanded', true);
  this.dropDownTrigger.classList.add('civic-dropdown__trigger--expanded');
  this.el.classList.add('civic-dropdown--expanded');
  this.dropDownMenu.classList.add('civic-dropdown__menu--expanded');
  this.dropDownMenu.setAttribute('aria-hidden', false);
};

// eslint-disable-next-line func-names
CivicDropdown.prototype.close = function () {
  this.el.expanded = false;
  this.dropDownTrigger.setAttribute('aria-expanded', false);
  this.dropDownTrigger.classList.remove('civic-dropdown__trigger--expanded');
  this.el.classList.remove('civic-dropdown--expanded');
  this.dropDownMenu.classList.remove('civic-dropdown__menu--expanded');
  this.dropDownMenu.setAttribute('aria-hidden', true);
};

// Initialize CivicCollapsible on every element.
document.querySelectorAll('[data-component-name="civic-dropdown"]').forEach((dropdown) => {
  // eslint-disable-next-line no-new
  new CivicDropdown(dropdown);
});
