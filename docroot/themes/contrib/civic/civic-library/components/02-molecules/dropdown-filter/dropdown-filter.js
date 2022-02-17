function CivicDropdownFilter(el) {
  if (!el || el.hasAttribute('data-dropdown-filter-is-initialized')) {
    return;
  }

  this.el = el;
  this.el.setAttribute('data-dropdown-filter-searchable', '');
  this.dropdownFilterItemSelector = this.el.getAttribute('data-dropdown-filter-item-selector') ?? '.civic-form-element--checkbox,.civic-form-element--radio';
  this.dropdownFilterItems = this.el.querySelectorAll(this.dropdownFilterItemSelector);
  this.placeholderText = this.el.getAttribute('data-dropdown-filter-placeholder-text') ?? 'Filter by keyword';
  this.threshold = this.el.getAttribute('data-dropdown-filter-threshold') ?? 4;

  if (this.dropdownFilterItems.length >= this.threshold) {
    this.init();
  }
};

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.init = function () {
  let search = document.createElement('div');
  search.classList.add('civic-dropdown-filter__search', 'civic-input', 'civic-theme-light');

  let searchInput = document.createElement('input');
  searchInput.classList.add('civic-dropdown-filter__search__input', 'civic-input__element', 'civic-input--default', 'civic-input--text');
  searchInput.setAttribute('placeholder', this.placeholderText);
  searchInput.setAttribute('value', '');
  searchInput.setAttribute('type', 'text');
  search.append(searchInput);

  this.searchInput = searchInput;
  this.el.prepend(search);
  this.keyupListener = this.keyupEvent.bind(this);
  this.searchInput.addEventListener('keyup', this.keyupListener, false);

  this.el.setAttribute('data-dropdown-filter-is-initialized', '');
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
  item.setAttribute('data-dropdown-filter-item-visible', '');
  item.removeAttribute('data-dropdown-filter-item-hidden');
};

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.hideItem = function (item) {
  item.setAttribute('data-dropdown-filter-item-hidden', '');
  item.removeAttribute('data-dropdown-filter-item-visible');
};

// eslint-disable-next-line func-names
CivicDropdownFilter.prototype.keyupEvent = function (e) {
  this.filterBasedOnInput();
};

document.querySelectorAll('.civic-dropdown-filter fieldset.civic-form-element--radio, .civic-dropdown-filter fieldset.civic-form-element--checkbox').forEach((dropdownFilter) => {
  // eslint-disable-next-line no-new
  new CivicDropdownFilter(dropdownFilter);
});
