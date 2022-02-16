function CivicDropdownFilter(el, selectedIndex) {
  if (!el) {
    return;
  }

  this.el = el;
  this.dropdownFilterItems = this.el.querySelectorAll('.civic-form-element--checkbox,.civic-form-element--radio');

  if (this.dropdownFilterItems.length <= 4) {
    return;
  }

  this.init();
}

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.init = function () {
  let search = document.createElement('div');
  search.classList.add('civic-dropdown-filter__search');

  let searchInput = document.createElement('input');
  searchInput.classList.add('civic-dropdown-filter__search__input', 'civic-input__element', 'civic-theme-light', 'civic-input--default', 'civic-input--text');
  searchInput.setAttribute('placeholder', 'Text goes here.');
  searchInput.setAttribute('value', '');
  searchInput.setAttribute('type', 'text');
  search.append(searchInput);

  this.searchInput = searchInput;
  this.el.prepend(search);
  this.keyupListener = this.keyupEvent.bind(this);
  this.searchInput.addEventListener('keyup', this.keyupListener, false);

  this.el.classList.add('is-initialized');
};


CivicDropdownFilter.prototype.filterBasedOnInput = function (e) {
  let query = this.searchInput.value;

  let hits = [];
  // console.log(this.dropdownFilterItems.childNodes);
  hits = [this.dropdownFilterItems].filter(item => item.querySelectorAll('label').innerHTML.includes(query));

  // for (let i = 0; i < this.dropdownFilterItems; i += 1) {
  //   // this.accordionTriggers[i].index = i;
  //   // this.accordionTriggers[i].addEventListener('click', this.clickListener, false);
  //   // this.accordionTriggers[i].addEventListener('keydown', this.keydownListener, false);
  //   // this.accordionTriggers[i].addEventListener('focus', this.focusListener, false);

  //   // if (this.accordionTriggers[i].classList.contains('is-selected')) {
  //   //   this.expandedAccordions[i] = true;
  //   // }
  // }
  console.log(hits);
}

CivicDropdownFilter.prototype.showItem = function (item) {

}

CivicDropdownFilter.prototype.hideItem = function (item) {

}

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.keyupEvent = function (e) {
  this.filterBasedOnInput();
};

document.querySelectorAll('.civic-dropdown-filter fieldset.civic-form-element--radio, .civic-dropdown-filter fieldset.civic-form-element--checkbox').forEach((dropdownFilter) => {
  // eslint-disable-next-line no-new
  new CivicDropdownFilter(dropdownFilter);
});
