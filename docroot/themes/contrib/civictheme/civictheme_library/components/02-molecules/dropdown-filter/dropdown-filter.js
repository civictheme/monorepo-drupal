/**
 * @file
 * Dropdown Filter component.
 *
 * Provides a search input to assist in finding radio/checkbox options.
 */
function CivicDropdownFilterSearchable(el) {
  if (!el || el.hasAttribute('data-dropdown-filter-searchable')) {
    return;
  }

  this.el = el;

  // Threshold determines when the search input is added to dropdown filter.
  this.searchBoxThreshold = this.el.getAttribute('data-dropdown-filter-search-item-threshold') ? parseInt(this.el.getAttribute('data-dropdown-filter-search-item-threshold'), 10) : 10;
  this.labelText = this.el.getAttribute('data-dropdown-filter-search-label') ? this.el.getAttribute('data-dropdown-filter-search-label') : '';

  this.fieldset = this.el.querySelector('[data-dropdown-filter-fieldset]');
  if (this.fieldset !== null) {
    this.items = this.fieldset.querySelectorAll('[data-dropdown-filter-item]');
    // Add a search box to the dropdown filter if there are more options than the threshold.
    if (this.items.length >= this.searchBoxThreshold) {
      this.init();
    }
  }

  if (this.el.hasAttribute('data-responsive')) {
    this.isDesktop = null;
    const swapBreakpoint = this.el.getAttribute('data-dropdown-filter-inline-change-breakpoint');
    window.addEventListener('civictheme-responsive', (evt) => {
      let isBreakpoint = false;
      const evaluationResult = evt.detail.evaluate(swapBreakpoint, () => {
        // Is within breakpoint.
        isBreakpoint = true;
      });
      if (evaluationResult === false) {
        // Not within breakpoint.
        isBreakpoint = false;
      }
      if (isBreakpoint !== this.isDesktop) {
        this.isDesktop = isBreakpoint;
        this.el.classList.toggle('civictheme-dropdown-filter--inline', !this.isDesktop);
      }
    }, false);
  }
}

/**
 * Initialised the dropdown filter search component.
 */
CivicDropdownFilterSearchable.prototype.init = function () {
  this.searchEl = this.createSearchElement();
  this.searchEl.addEventListener('keyup', this.filterKeyUpListener.bind(this), false);
  this.el.setAttribute('data-dropdown-filter-searchable', '');
};

/**
 * Create and search input to dropdown filter.
 */
CivicDropdownFilterSearchable.prototype.createSearchElement = function () {
  // Create the search box container.
  const search = document.createElement('div');
  const themeClass = this.el.getAttribute('class').includes('civictheme-theme-light') ? 'civictheme-theme-light' : 'civictheme-theme-dark';
  search.classList.add('civictheme-dropdown-filter__search', 'civictheme-input', themeClass);

  const searchFieldName = this.generateSearchFieldName();
  // Create the filter search input and add it to the dropdown filter.
  let searchLabel = false;
  if (this.labelText) {
    searchLabel = document.createElement('label');
    searchLabel.setAttribute('for', searchFieldName);
    searchLabel.classList.add('civictheme-label', themeClass);
    searchLabel.innerHTML = this.labelText;
  }

  const searchInput = document.createElement('input');
  searchInput.classList.add('civictheme-dropdown-filter__search__input', 'civictheme-input__element', 'civictheme-input--default', 'civictheme-input--text', themeClass);
  searchInput.setAttribute('value', '');
  searchInput.setAttribute('type', 'text');
  // Attribute - data-large-filter-ignore - is used by large filter to ignore
  // form element when drawing the filters in a large filter.
  searchInput.setAttribute('data-large-filter-ignore', '');
  searchInput.setAttribute('id', searchFieldName);
  searchInput.setAttribute('name', searchFieldName);

  if (searchLabel) {
    search.append(searchLabel);
  }
  search.append(searchInput);

  // Add the search box container to the dropdown filter.
  this.fieldset.prepend(search);

  return searchInput;
};

/**
 * Helper to generate a unique id for search element.
 */
CivicDropdownFilterSearchable.prototype.generateSearchFieldName = function () {
  // Generate first based on fieldset ID then otherwise generate a unique id.
  if (this.fieldset.hasAttribute('id')) {
    return `${this.fieldset.getAttribute('id')}__search`;
  }
  let searchFieldName = `dropdown_filter__search__${Math.floor((Math.random() * 1000) + 1)}`;
  while (document.querySelector(`#${searchFieldName}`) !== null) {
    searchFieldName = `dropdown_filter__search__${Math.floor((Math.random() * 1000) + 1)}`;
  }

  return searchFieldName;
};

/**
 * KeyUp event listener to filter options based on search.
 */
CivicDropdownFilterSearchable.prototype.filterKeyUpListener = function () {
  const query = this.searchEl.value.toLowerCase();
  const dropdownFilter = this;

  this.items.forEach((item) => {
    if (item.querySelector('label').innerHTML.toLowerCase().includes(query)) {
      dropdownFilter.showItem(item);
    } else {
      dropdownFilter.hideItem(item);
    }
  });
};

/**
 * Show filter option.
 */
CivicDropdownFilterSearchable.prototype.showItem = function (item) {
  item.setAttribute('data-dropdown-filter-search-item--visible', '');
  item.removeAttribute('data-dropdown-filter-search-item--hidden');
};

/**
 * Hide filter option
 */
CivicDropdownFilterSearchable.prototype.hideItem = function (item) {
  item.setAttribute('data-dropdown-filter-search-item--hidden', '');
  item.removeAttribute('data-dropdown-filter-search-item--visible');
};

document.querySelectorAll('[data-component-name="civictheme-dropdown-filter"]').forEach((dropdownFilter) => {
  new CivicDropdownFilterSearchable(dropdownFilter);
});
