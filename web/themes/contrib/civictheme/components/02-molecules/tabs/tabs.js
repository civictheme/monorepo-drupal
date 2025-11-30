// phpcs:ignoreFile
/**
 * @file
 * Tabs component.
 */
function CivicThemeTabs(el, selectedIndex) {
  if (!el) {
    return;
  }

  this.el = el;
  this.links = this.el.querySelectorAll('[data-tabs-tab]');
  this.panels = this.el.querySelectorAll('[data-tabs-panel]');

  if (this.links.length === 0
    || this.panels.length === 0
    || this.links.length !== this.panels.length
  ) {
    return;
  }

  this.init(selectedIndex);
}

CivicThemeTabs.prototype.init = function () {
  this.clickListener = this.clickEvent.bind(this);

  let selected = 0;
  for (let i = 0; i < this.panels.length; i++) {
    this.links[i].addEventListener('click', this.clickListener, false);

    if (this.panels[i].classList.contains('ct-tabs__panel--selected') && !selected) {
      selected = i;
    }
  }

  this.links[selected].click();
};

CivicThemeTabs.prototype.clickEvent = function (e) {
  e.preventDefault();

  this.setSelected(e.currentTarget);
};

CivicThemeTabs.prototype.setSelected = function (current) {
  for (let i = 0; i < this.panels.length; i++) {
    const currentLink = this.links[i];
    if (currentLink === current) {
      currentLink.classList.add('ct-tabs__tab--selected');
      currentLink.setAttribute('aria-selected', true);
      this.panels[i].classList.add('ct-tabs__panel--selected');
      this.panels[i].setAttribute('aria-hidden', false);
    } else {
      currentLink.classList.remove('ct-tabs__tab--selected');
      currentLink.setAttribute('aria-selected', false);
      this.panels[i].classList.remove('ct-tabs__panel--selected');
      this.panels[i].setAttribute('aria-hidden', true);
    }
  }
};

CivicThemeTabs.prototype.destroy = function () {
  for (let i = 0; i < this.panels.length; i++) {
    this.links[i].removeAttribute('aria-selected');
    this.links[i].classList.remove('ct-tabs__tab--selected');
    this.links[i].removeEventListener('click', this.clickListener, false);

    this.panels[i].removeAttribute('aria-hidden');
    this.panels[i].classList.remove('ct-tabs__panel--selected');
  }
};

document.querySelectorAll('.ct-tabs').forEach((tabs) => {
  new CivicThemeTabs(tabs);
});
