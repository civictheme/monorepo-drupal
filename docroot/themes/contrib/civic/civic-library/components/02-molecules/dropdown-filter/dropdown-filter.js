function CivicDropdownFilter(el) {
  if (!el) {
    return;
  }

  this.el = el;
  this.dropdownFilterItems = this.el.querySelectorAll('.civic-form-element--checkbox,.civic-form-element--radio');

  if (this.dropdownFilterItems.length >= 8) {
    this.init();
  }
};

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

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.filterBasedOnInput = function (e) {
  let query = this.searchInput.value.toLowerCase();
  let _this = this;

  this.dropdownFilterItems.forEach(function(item) {
    if (item.querySelector('label').innerHTML.toLowerCase().includes(query)) {
      _this.showItem(item);
    } else {
      _this.hideItem(item);
    }
  });
};

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.showItem = function (item) {
  item.classList.remove('civic-dropdown-filter__item-hidden');
  item.classList.add('civic-dropdown-filter__item-visible');
};

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.hideItem = function (item) {
  item.classList.remove('civic-dropdown-filter__item-visible');
  item.classList.add('civic-dropdown-filter__item-hidden');
};

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.keyupEvent = function (e) {
  this.filterBasedOnInput();
};

document.querySelectorAll('.civic-dropdown-filter fieldset.civic-form-element--radio, .civic-dropdown-filter fieldset.civic-form-element--checkbox').forEach((dropdownFilter) => {
  // eslint-disable-next-line no-new
  new CivicDropdownFilter(dropdownFilter);
});
