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
        return (selectedOptionElement && selectedOptionElement.checked === true);
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
        const value = element.value ? element.querySelector('option[value="' + element.value + '"]').innerText : '';
        return `${label} - ${value}`;
      },
    },
  };

  this.filterElementChangeEvent = this.filterElementChange.bind(this);
  this.tagElementChangeEvent = this.tagElementChange.bind(this);
  this.clearElementClickEvent = this.clearElementClick.bind(this);

  this.init();
}

CivicLargeFilter.prototype.init = function () {
  // Add listeners.
  this.filterElement.addEventListener('change', this.filterElementChangeEvent);
  this.tagElement.addEventListener('click', this.tagElementChangeEvent);
  this.clearAllButton.addEventListener('click', this.clearElementClickEvent);

  // Set state values based on current filter fields.
  this.filterElement.querySelectorAll('input, select').forEach((element) => {
    if (this.isSelectableField(element)) {
      const type = this.getElementType(element);
      const key = this.fieldTypes[type].getKey(element);
      const id = this.fieldTypes[type].getId(element);
      const value = this.fieldTypes[type].getValue(element);
      this.updateState(key, id, value, type);
    }
  });
};

CivicLargeFilter.prototype.filterElementChange = function (e) {
  const element = e.target;
  if (this.isSelectableField(element)) {
    const type = this.getElementType(element);
    if (Object.keys(this.fieldTypes).indexOf(type) >= 0) {
      const key = this.fieldTypes[type].getKey(element);
      const id = this.fieldTypes[type].getId(element);
      const value = this.fieldTypes[type].getValue(element);
      this.updateState(key, id, value, type);
    }
  }
};

CivicLargeFilter.prototype.tagElementChange = function (e) {
  if (e.target.nodeName === 'BUTTON') {
    const key = e.target.dataset.id;
    const { type } = this.state[key];
    this.updateState(key, this.state[key].id, this.fieldTypes[type].emptyValue, type);
  }
};

CivicLargeFilter.prototype.clearElementClick = function () {
  Object.keys(this.state).forEach((key) => {
    const { type } = this.state[key];
    this.updateState(key, this.state[key].id, this.fieldTypes[type].emptyValue, type);
  });
};

CivicLargeFilter.prototype.isSelectableField = function (element) {
  return !element.hasAttribute('data-large-filter-ignore');
};

CivicLargeFilter.prototype.updateState = function (key, id, value, type) {
  this.state[key] = { id, type, value };
  this.redraw();
};

CivicLargeFilter.prototype.getElementType = function (el) {
  let returnType = null;
  if (el) {
    const tag = el.nodeName.toLowerCase();
    returnType = (tag === 'input') ? `${tag}_${el.type}` : tag;
  }
  return returnType;
};

CivicLargeFilter.prototype.redraw = function () {
  this.redrawFilters();
  this.redrawSelected();
  this.redrawClearButton();
};

CivicLargeFilter.prototype.redrawFilters = function () {
  Object.keys(this.state).forEach((key) => {
    const entry = this.state[key];
    const el = document.getElementById(entry.id);
    this.fieldTypes[entry.type].setValue(el, entry.value);
  });
};

CivicLargeFilter.prototype.getHTMLFilterItem = function (key, label, theme) {
  // Return a filter-chip-button template, wrapped in a list item.
  return `
    <li class="civic-large-filter__tag">
      <div class="civic-filter-chip civic-theme-${theme} civic-filter-chip--selected" data-component-name="filter-chip">
        <button class="civic-filter-chip__wrapper civic-filter-chip__wrapper--button" data-id="${key}">
          <span class="civic-filter-chip__text">${label}</span>
          <span class="civic-filter-chip__selected-icon"></span>
        </button>
      </div>
    </li>
  `;
};

CivicLargeFilter.prototype.redrawSelected = function () {
  let html = '';
  Object.keys(this.state).forEach((key) => {
    const entry = this.state[key];
    if (entry.value) {
      const el = document.getElementById(entry.id);
      const label = this.fieldTypes[entry.type].getLabel(el);
      const theme = this.el.dataset.largeFilterTheme;
      html += this.getHTMLFilterItem(key, label, theme);
    }
  });
  this.tagElement.innerHTML = html;
};

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

document.querySelectorAll('[data-component-name="civic-large-filter"]').forEach((el) => {
  new CivicLargeFilter(el);
});
