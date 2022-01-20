function CivicLargeFilter(el) {
  this.el = el;
  this.tagElement = this.el.querySelector('[data-large-filter-tags]');
  this.filterElement = this.el.querySelector('[data-large-filter-filters]');
  this.clearAllButton = this.el.querySelector('[data-large-filter-clear]');
  this.selectedFiltersElement = this.el.querySelector('[data-large-filter-selected-filters]');
  this.state = {};
  this.fieldTypes = {
    input_checkbox: {
      emptyValue: false,
      setValue: (element, value) => { element.checked = value; },
      getValue: (element) => element.checked,
      getId: (element) => element.id,
      getKey: (element) => element.id,
      getLabel: (element) => element.closest('.civic-form-element').querySelector('label').innerText,
    },
    input_date: {
      emptyValue: '',
      setValue: (element, value) => { element.value = value; },
      getValue: (element) => element.value,
      getId: (element) => element.id,
      getKey: (element) => element.id,
      getLabel: (element) => `${element.closest('.civic-form-element').querySelector('label').innerText} - ${element.value}`,
    },
    input_text: {
      emptyValue: '',
      setValue: (element, value) => { element.value = value; },
      getValue: (element) => element.value,
      getId: (element) => element.id,
      getKey: (element) => element.id,
      getLabel: (element) => `${element.closest('.civic-form-element').querySelector('label').innerText} - ${element.value}`,
    },
    input_radio: {
      emptyValue: false,
      setValue: (element, value) => { element.checked = value; },
      getValue: (element) => {
        const group = document.getElementsByName(element.name);
        const selectedOptionElement = Array.from(group).filter((item) => item.checked).pop();
        return (selectedOptionElement && selectedOptionElement.checked === true) ? selectedOptionElement.value : '';
      },
      getId: (element) => element.id,
      getKey: (element) => element.name,
      getLabel: (element) => element.closest('.civic-form-element').querySelector('label').innerText,
    },
    select: {
      emptyValue: '',
      setValue: (element, value) => { element.value = value; },
      getValue: (element) => element.value,
      getId: (element) => element.id,
      getKey: (element) => element.id,
      getLabel: (element) => {
        const label = element.closest('.civic-form-element').querySelector('label').innerText;
        const value = element.value ? element.querySelector(`option[value="${element.value}"]`).innerText : '';
        return `${label} - ${value}`;
      },
    },
  };

  this.init();
}

/**
 * Civic Large Filter Initialisation.
 */
CivicLargeFilter.prototype.init = function () {
  // Add listeners.
  this.filterElement.addEventListener('change', this.filterElementChangeEvent.bind(this));
  this.tagElement.addEventListener('click', this.tagElementChangeEvent.bind(this));
  this.clearAllButton.addEventListener('click', this.clearElementClickEvent.bind(this));

  // Set state values based on current filter fields.
  this.filterElement.querySelectorAll('input, select').forEach((element) => {
    if (this.isSelectableField(element)) {
      const type = this.getElementType(element);
      if (typeof this.fieldTypes[type] === 'undefined') {
        return;
      }
      const key = this.fieldTypes[type].getKey(element);
      const id = this.fieldTypes[type].getId(element);
      const value = this.fieldTypes[type].getValue(element);
      if (type === 'input_radio' && !element.checked) {
        return;
      }
      this.updateState(key, id, value, type);
    }
  });
};

/**
 * Form filter change event listener.
 */
CivicLargeFilter.prototype.filterElementChangeEvent = function (e) {
  const element = e.target;
  if (this.isSelectableField(element)) {
    const type = this.getElementType(element);
    if (Object.keys(this.fieldTypes).indexOf(type) >= 0) {
      const key = this.fieldTypes[type].getKey(element);
      const id = this.fieldTypes[type].getId(element);
      const value = this.fieldTypes[type].getValue(element);
      if (type === 'input_radio' && !element.checked) {
        return;
      }
      this.updateState(key, id, value, type);
    }
  }
};

/**
 * Filter chips click event listener.
 */
CivicLargeFilter.prototype.tagElementChangeEvent = function (e) {
  if (e.target.nodeName === 'BUTTON') {
    const key = e.target.dataset.id;
    const { type } = this.state[key];
    this.updateState(key, this.state[key].id, this.fieldTypes[type].emptyValue, type);
  }
};

/**
 * Clear state from all selected filters.
 */
CivicLargeFilter.prototype.clearElementClickEvent = function () {
  Object.keys(this.state).forEach((key) => {
    const { type } = this.state[key];
    this.updateState(key, this.state[key].id, this.fieldTypes[type].emptyValue, type);
  });
};

CivicLargeFilter.prototype.isSelectableField = function (element) {
  return !element.hasAttribute('data-large-filter-ignore');
};

/**
 * Update state of civic large filter.
 */
CivicLargeFilter.prototype.updateState = function (key, id, value, type) {
  this.state[key] = { id, type, value };
  this.redraw();
};

/**
 * Gets the filter form element type.
 */
CivicLargeFilter.prototype.getElementType = function (el) {
  let returnType = null;
  if (el) {
    const tag = el.nodeName.toLowerCase();
    returnType = (tag === 'input') ? `${tag}_${el.type}` : tag;
  }
  return returnType;
};

/**
 * Redraw civic large filter on event or initialisation.
 */
CivicLargeFilter.prototype.redraw = function () {
  this.redrawFilters();
  this.redrawSelected();
  this.redrawClearButton();
  this.dispatchRedrawEvent();
};

/**
 * Redraw civic large filters.
 */
CivicLargeFilter.prototype.redrawFilters = function () {
  Object.keys(this.state).forEach((key) => {
    const entry = this.state[key];
    const el = document.getElementById(entry.id);
    this.fieldTypes[entry.type].setValue(el, entry.value);
  });
};

/**
 * Renders filter html component.
 */
CivicLargeFilter.prototype.renderHTMLFilterItem = function (key, label, type, theme) {
  // Return a filter-chip-button template, wrapped in a list item.
  if (type !== 'input_radio') {
    return `
    <li class="civic-large-filter__tag">
      <button class="civic-filter-chip-button civic-theme-${theme} civic-filter-chip-button--selected" data-id="${key}" data-civic-filter-chip>
        <span class="civic-filter-chip-button__text">${label}</span>
        <span class="civic-filter-chip-button__selected-icon"></span>
      </button>
    </li>
  `;
  }
  // Radio filters are rendered as non-dismissible elements.
  return `
    <li class="civic-large-filter__tag">
      <span class="civic-filter-chip-button civic-theme-${theme} civic-filter-chip-button--selected" data-id="${key}">
        <span class="civic-filter-chip-button__text">${label}</span>
      </span>
    </li>
  `;
};

/**
 * Redraw selected filters.
 */
CivicLargeFilter.prototype.redrawSelected = function () {
  let html = '';
  Object.keys(this.state).forEach((key) => {
    const entry = this.state[key];
    if (entry.value) {
      const el = document.getElementById(entry.id);
      const label = this.fieldTypes[entry.type].getLabel(el);
      const theme = this.el.dataset.largeFilterTheme;
      html += this.renderHTMLFilterItem(key, label, entry.type, theme);
    }
  });
  this.tagElement.innerHTML = `<ul class="civic-large-filter__tags-list">${html}</ul>`;
};

/**
 * Redraw clear button.
 */
CivicLargeFilter.prototype.redrawClearButton = function () {
  // Hide button if no elements set.
  let showTagPanel = false;
  Object.keys(this.state).forEach((key) => {
    if (this.state[key].value) {
      showTagPanel = true;
    }
  });
  this.selectedFiltersElement.classList.toggle('civic-large-filter__selected-filters--hidden', !showTagPanel);
};

/**
 * Custom event allowing other JS libraries to operate on filter events.
 */
CivicLargeFilter.prototype.dispatchRedrawEvent = function () {
  const event = new CustomEvent('civic.largeFilter.change');
  this.el.dispatchEvent(event);
};

document.querySelectorAll('[data-component-name="civic-large-filter"]').forEach((el) => {
  new CivicLargeFilter(el);
});
