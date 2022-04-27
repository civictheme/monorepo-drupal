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
      this.updateState(key, id, value, type, false);
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
  this.redraw(false);
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
  const event = new CustomEvent('civicLargeFilterMobileLayoutUpdated', {
    detail: {
      isDesktop: this.isDesktop,
    },
  });
  this.el.dispatchEvent(event);
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
      this.updateState(key, id, value, type, true);
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
    this.updateState(key, this.state[key].id, this.fieldTypes[type].emptyValue, type, true);
  }
};

/**
 * Clear state from all selected filters.
 */
CivicLargeFilter.prototype.clearElementClickEvent = function () {
  Object.keys(this.state).forEach((key) => {
    const { type } = this.state[key];
    this.updateState(key, this.state[key].id, this.fieldTypes[type].emptyValue, type, false);
  });
  this.redraw();
  this.el.dispatchEvent(new CustomEvent('civicLargeFilterClearAll'));
};

CivicLargeFilter.prototype.isSelectableField = function (element) {
  return !element.hasAttribute('data-large-filter-ignore');
};

/**
 * Update state of civic large filter.
 */
CivicLargeFilter.prototype.updateState = function (key, id, value, type, redraw) {
  this.state[key] = { id, type, value };
  if (redraw) {
    this.redraw();
  }
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
CivicLargeFilter.prototype.redraw = function (changeEvent = true) {
  this.redrawFilters();
  this.redrawSelected();
  this.redrawClearButton();
  if (changeEvent) {
    this.dispatchChangeEvent();
  }
};

/**
 * Redraw civic large filters.
 */
CivicLargeFilter.prototype.redrawFilters = function () {
  Object.keys(this.state).forEach((key) => {
    const entry = this.state[key];
    const el = document.getElementById(entry.id);
    const currentValue = this.fieldTypes[entry.type].getValue(el, entry.value);
    if (currentValue !== entry.value) {
      const event = new Event('change');
      this.fieldTypes[entry.type].setValue(el, entry.value);
      el.dispatchEvent(event);
    }
  });
};

/**
 * Renders filter html component.
 */
CivicLargeFilter.prototype.renderHTMLFilterItem = function (key, label, type, theme) {
  // Return a chip button template, wrapped in a list item.
  if (type !== 'input_radio') {
    return `
    <li class="civic-large-filter__tag">
      <button class="civic-button civic-theme-${theme} civic-button--chip civic-button--button civic-button--small civic-button--dismiss" data-component-name="chip" data-id="${key}">
        ${label}
        <span class="civic-button__dismiss" data-button-dismiss>
          <svg xmlns="http://www.w3.org/2000/svg" class="civic-icon  civic-icon--size-extra-small " width="24" height="24" viewBox="0 0 24 24" aria-hidden="true"><path d="M13.4099 11.9999L17.7099 7.70994C17.8982 7.52164 18.004 7.26624 18.004 6.99994C18.004 6.73364 17.8982 6.47825 17.7099 6.28994C17.5216 6.10164 17.2662 5.99585 16.9999 5.99585C16.7336 5.99585 16.4782 6.10164 16.2899 6.28994L11.9999 10.5899L7.70994 6.28994C7.52164 6.10164 7.26624 5.99585 6.99994 5.99585C6.73364 5.99585 6.47824 6.10164 6.28994 6.28994C6.10164 6.47825 5.99585 6.73364 5.99585 6.99994C5.99585 7.26624 6.10164 7.52164 6.28994 7.70994L10.5899 11.9999L6.28994 16.2899C6.19621 16.3829 6.12182 16.4935 6.07105 16.6154C6.02028 16.7372 5.99414 16.8679 5.99414 16.9999C5.99414 17.132 6.02028 17.2627 6.07105 17.3845C6.12182 17.5064 6.19621 17.617 6.28994 17.7099C6.3829 17.8037 6.4935 17.8781 6.61536 17.9288C6.73722 17.9796 6.86793 18.0057 6.99994 18.0057C7.13195 18.0057 7.26266 17.9796 7.38452 17.9288C7.50638 17.8781 7.61698 17.8037 7.70994 17.7099L11.9999 13.4099L16.2899 17.7099C16.3829 17.8037 16.4935 17.8781 16.6154 17.9288C16.7372 17.9796 16.8679 18.0057 16.9999 18.0057C17.132 18.0057 17.2627 17.9796 17.3845 17.9288C17.5064 17.8781 17.617 17.8037 17.7099 17.7099C17.8037 17.617 17.8781 17.5064 17.9288 17.3845C17.9796 17.2627 18.0057 17.132 18.0057 16.9999C18.0057 16.8679 17.9796 16.7372 17.9288 16.6154C17.8781 16.4935 17.8037 16.3829 17.7099 16.2899L13.4099 11.9999Z"></path></svg>
        </span>
      </button>
    </li>
  `;
  }
  // Radio filters are rendered as non-dismissible elements.
  return `
    <li class="civic-large-filter__tag">
      <button class="civic-button civic-theme-${theme} civic-button--chip civic-button--button civic-button--small" data-component-name="chip" data-id="${key}">
        ${label}
      </button>
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
CivicLargeFilter.prototype.dispatchChangeEvent = function () {
  if (!this.initialisedState) {
    return;
  }
  this.el.dispatchEvent(new CustomEvent('civicLargeFilterChange'));
};

document.querySelectorAll('[data-component-name="civic-large-filter"]').forEach((el) => {
  new CivicLargeFilter(el);
});
