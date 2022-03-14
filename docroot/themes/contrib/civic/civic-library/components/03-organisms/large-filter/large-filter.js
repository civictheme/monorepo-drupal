/**
 * Large filter component.
 */
function CivicLargeFilter(el) {
  // Use "data-civic-alerts"'s attribute value to identify if this component was
  // already initialised.
  if (el.getAttribute('data-civic-large-filter') === 'true') {
    return;
  }

  this.el = el;

  this.tagElement = this.el.querySelector('[data-large-filter-tags]');
  this.filterElement = this.el.querySelector('[data-large-filter-filters]');
  this.filterComponent = this.el.querySelector('[data-large-filter-element]');
  this.clearAllButton = this.el.querySelector('[data-large-filter-clear]');
  this.selectedFiltersElement = this.el.querySelector('[data-large-filter-selected-filters]');
  this.mobileSelectedFiltersElement = this.el.querySelector('[data-large-filter-mobile-selected-filters]');
  this.mobileOpenButton = this.el.querySelector('[data-large-filter-mobile-toggle]');
  this.mobileCancelButton = this.el.querySelector('[data-large-filter-mobile-cancel]');
  this.mobileOverlay = this.el.querySelector('[data-large-filter-mobile-overlay]');
  this.mobileToggleDisplay = this.el.querySelector('[data-large-filter-mobile-toggle-display]');
  this.mobileToggleSuffix = this.mobileToggleDisplay.getAttribute('data-large-filter-mobile-toggle-display-suffix');
  this.state = {};
  this.revertState = null;
  this.initialisedState = false;
  this.isDesktop = null;
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
  this.mobileOpenButton.addEventListener('click', this.mobileOpenElementClickEvent.bind(this));
  this.mobileCancelButton.addEventListener('click', this.mobileCancelElementClickEvent.bind(this));

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

  // Mobile support.
  const desktopBreakpoint = this.el.getAttribute('data-large-filter-desktop-breakpoint');
  if (desktopBreakpoint) {
    window.addEventListener('civic-responsive', (evt) => {
      let isBreakpoint = false;
      const evaluationResult = evt.detail.evaluate(desktopBreakpoint, () => {
        // Is within breakpoint.
        isBreakpoint = true;
      });
      if (evaluationResult === false) {
        // Not within breakpoint.
        isBreakpoint = false;
      }
      if (isBreakpoint !== this.isDesktop) {
        this.isDesktop = isBreakpoint;
        this.updateTagContainerPosition();
      }
    }, false);
  }

  this.initialisedState = true;

  this.el.setAttribute('data-civic-large-filter', 'true');
};

/**
 * Position tags.
 * Mobile will show tags above the filters, desktop will show below.
 */
CivicLargeFilter.prototype.updateTagContainerPosition = function () {
  const tagsContainerSelector = this.isDesktop ? '[data-large-filter-tags-container]' : '[data-large-filter-mobile-tags-container]';
  this.el.querySelector(tagsContainerSelector).appendChild(this.tagElement);

  const btnContainerSelector = this.isDesktop ? '[data-large-filter-clear-container]' : '[data-large-filter-mobile-clear-container]';
  this.el.querySelector(btnContainerSelector).appendChild(this.clearAllButton);

  const elementContainer = this.isDesktop ? '[data-large-filter-desktop-container]' : '[data-large-filter-mobile-container]';
  this.el.querySelector(elementContainer).appendChild(this.filterComponent);

  // Enable / Disable auto-submit on mobile.
  this.el.setAttribute('data-large-filter-auto-submit', this.isDesktop);
};

/**
 * Mobile open handler.
 * Remember current form state.
 * 'data-flyout-open-trigger' on button will handle opening flyout.
 */
CivicLargeFilter.prototype.mobileOpenElementClickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  this.revertState = JSON.stringify(this.state);
};

/**
 * Mobile cancel handler.
 * Revert form state to remembered state.
 * 'data-large-filter-mobile-cancel' on button will handle closing flyout.
 */
CivicLargeFilter.prototype.mobileCancelElementClickEvent = function (e) {
  e.stopPropagation();
  e.preventDefault();
  if (this.revertState) {
    this.state = JSON.parse(this.revertState);
  }
  this.redraw();
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
      <span class="civic-filter-chip-button civic-theme-${theme} civic-filter-chip-button--selected civic-filter-chip-button--non-dismissable" data-id="${key}">
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
  let count = 0;
  Object.keys(this.state).forEach((key) => {
    const entry = this.state[key];
    if (entry.value) {
      count++;
      const el = document.getElementById(entry.id);
      const label = this.fieldTypes[entry.type].getLabel(el);
      const theme = this.el.dataset.largeFilterTheme;
      html += this.renderHTMLFilterItem(key, label, entry.type, theme);
    }
  });
  this.mobileToggleDisplay.classList.toggle('civic-large-filter__mobile-toggle-display--hidden', (count === 0));
  this.mobileToggleDisplay.innerHTML = `${count} ${this.pluralize(this.mobileToggleSuffix, count)}`;
  this.tagElement.innerHTML = `<ul class="civic-large-filter__tags-list">${html}</ul>`;
};

/**
 * Pluralize.
 * Return the plural version based on count.
 * @param {string} pluralJSON
 *   A URL encoded JSON string in the format { "1": "Item", "default": "Items" }.
 * @param {number} count
 *   The counter used retrieve the plural.
 */
CivicLargeFilter.prototype.pluralize = function (pluralJSON, count) {
  const obj = JSON.parse(decodeURIComponent(pluralJSON));
  let puralStr = '';
  if (obj[count]) {
    puralStr = obj[count];
  } else if (obj.default) {
    puralStr = obj.default;
  }
  return puralStr;
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
  this.mobileSelectedFiltersElement.classList.toggle('civic-large-filter__selected-filters--hidden', !showTagPanel);
};

/**
 * Custom event allowing other JS libraries to operate on filter events.
 */
CivicLargeFilter.prototype.dispatchRedrawEvent = function () {
  if (!this.initialisedState) {
    return;
  }
  this.el.dispatchEvent(new CustomEvent('civicLargeFilterChange'));
};

document.querySelectorAll('[data-component-name="civic-large-filter"]').forEach((el) => {
  new CivicLargeFilter(el);
});
