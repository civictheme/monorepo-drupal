// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
/**
 * @file
 * Tabs component.
 */
function CivicTabs(el, selectedIndex) {
  if (!el) {
    return;
  }

  this.el = el;
  this.links = this.el.querySelectorAll('.civictheme-tabs__links .civictheme-link');
  this.panels = this.el.querySelectorAll('.civictheme-tabs__panels__panel');

  if (this.links.length === 0
    || this.panels.length === 0
    || this.links.length !== this.panels.length
  ) {
    return;
  }

  this.init(selectedIndex);
}

CivicTabs.prototype.init = function () {
  this.expandedTabs = new Array(this.panels.length);
  this.clickListener = this.clickEvent.bind(this);

  let selected = 0;
  for (let i = 0; i < this.panels.length; i++) {
    this.links[i].addEventListener('click', this.clickListener, false);

    if (this.panels[i].classList.contains('selected') && !selected) {
      selected = i;
    }
  }

  this.links[selected].click();
};

CivicTabs.prototype.clickEvent = function (e) {
  e.preventDefault();

  this.setSelected(e.currentTarget);
};

CivicTabs.prototype.setSelected = function (current) {
  for (let i = 0; i < this.panels.length; i++) {
    const currentLink = this.links[i];
    if (currentLink === current) {
      currentLink.classList.add('selected');
      currentLink.setAttribute('aria-selected', true);
      this.panels[i].classList.add('selected');
      this.panels[i].setAttribute('aria-hidden', false);
    } else {
      currentLink.classList.remove('selected');
      currentLink.setAttribute('aria-selected', false);
      this.panels[i].classList.remove('selected');
      this.panels[i].setAttribute('aria-hidden', true);
    }
  }
};

CivicTabs.prototype.destroy = function () {
  for (let i = 0; i < this.panels.length; i++) {
    this.links[i].removeAttribute('aria-selected');
    this.links[i].classList.remove('selected');
    this.links[i].removeEventListener('click', this.clickListener, false);

    this.panels[i].removeAttribute('aria-hidden');
    this.panels[i].classList.remove('selected');
  }
};

document.querySelectorAll('.civictheme-tabs').forEach((tabs) => {
  new CivicTabs(tabs);
});
