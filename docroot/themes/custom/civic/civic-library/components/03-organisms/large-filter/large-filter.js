function CivicLargeFilter(el) {
  this.el = el;
  this.tagElement = this.el.querySelector('[data-large-filter-tags]');
  this.filterElement = this.el.querySelector('[data-large-filter-filters]');
  this.init();
  this.tags = [];
}

CivicLargeFilter.prototype.init = function () {
  this.filters = this.filterElement.querySelectorAll('input, select');
  this.filters.forEach((ele) => {
    ele.addEventListener('change', this.formElementListener.bind(this));
  });
};

CivicLargeFilter.prototype.formElementListener = function (e) {
  this.renderTags();
};

/**
 * Generates the selected tag data object for templating.
 */
CivicLargeFilter.prototype.getFormElementObject = function (formElement) {
  return {
    name: formElement.name,
    value: this.getFormElementValue(formElement),
    label: this.getFormElementLabel(formElement),
  };
};

/**
 * Helper to get label text or option text from a form input / select.
 */
CivicLargeFilter.prototype.getFormElementLabel = function (formElement) {
  if (formElement.nodeName === 'select') {
    return formElement.options[formElement.selectedIndex].text;
  }

  return this.parent(formElement, '.civic-form-element').querySelector('label').innerText;
};

/**
 * Helper to get form input value or selected option.
 */
CivicLargeFilter.prototype.getFormElementValue = function (formElement) {
  switch (formElement.nodeName) {
    case 'select':
      return typeof formElement.options[formElement.selectedIndex] !== 'undefined'
        ? formElement.options[formElement.selectedIndex].value : '';
    case 'checkbox':
      return formElement.getAttribute('checked') ? formElement.value : '';
    default:
      return formElement.value;
  }
};

/**
 * Traversal helper to get a parent element matching a selector.
 */
CivicLargeFilter.prototype.parent = function (element, selector) {
  while (element !== null && !element.matches(selector)) {
    element = element.parentNode;
  }
  return element;
};

/**
 * Helper to calculate which what filters are selected.
 */
CivicLargeFilter.prototype.renderTags = function () {
  let html = '';
  this.tags.forEach((tag) => {
    html += this.renderTag(tag);
  });
  this.filters.forEach((filter) => {
    const inputType = filter.nodeName;
    switch (inputType) {
      case 'SELECT':
        if (typeof filter.options[filter.selectedIndex] !== 'undefined') {
          html += this.renderTag(filter);
        }
        break;
      case 'INPUT':
      default:
        switch (filter.type) {
          case 'radio':
          case 'checkbox':
            if (filter.checked === true) {
              html += this.renderTag(filter);
            }
            break;
          default:
            if (this.getFormElementValue(filter) !== '') {
              html += this.renderTag(filter);
            }
        }
    }
  });
  this.tagElement.innerHTML = html;
};

/**
 * Render function to render a selected filter tag.
 */
CivicLargeFilter.prototype.renderTag = function (filter) {
  const label = this.getFormElementLabel(filter);
  const value = this.getFormElementValue(filter);
  const { name } = filter;
  return `<div>Name: ${name} Value: ${value} Label: ${label}</div>`;
};

document.querySelectorAll('[data-component-name="civic-large-filter"]').forEach((el) => {
  new CivicLargeFilter(el);
});
