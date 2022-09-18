// phpcs:ignoreFile
/**
 * Alert component.
 *
 * Attaches to markup with 'data-component-name="ct-alerts"' attribute.
 *
 * Available attributes:
 * - data-alert-endpoint: Alert REST configurable API endpoint.
 */
function CivicThemeAlert(el) {
  // Use "data-ct-alerts"'s attribute value to identify if this
  // component was already initialised.
  if (el.getAttribute('data-ct-alerts') === 'true' || this.container) {
    return;
  }

  this.container = el;
  this.endpoint = this.container.getAttribute('data-alert-endpoint');
  if (this.endpoint !== null) {
    this.getAll();
  }

  // Mark as initialized.
  this.container.setAttribute('data-ct-alerts', 'true');
}

/**
 * Gets alerts from endpoint.
 */
CivicThemeAlert.prototype.getAll = function () {
  const { endpoint } = this;
  const request = new XMLHttpRequest();
  request.open('get', endpoint);
  request.onreadystatechange = () => {
    if (request.readyState === 4 && request.status === 200) {
      try {
        const response = JSON.parse(request.responseText);
        const html = this.filter(response);
        this.insert(html);
      } catch (e) {
        // Do nothing.
      }
    }
  };
  request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  request.send();
};

/**
 * Filters out alerts not to show ie dismissed, page-specific alerts.
 */
CivicThemeAlert.prototype.filter = function (response) {
  let html = '';

  if (response.length) {
    for (let i = 0; i < response.length; i++) {
      const item = response[i];

      if (!this.isValidResponse(item)) {
        continue;
      }

      // Skip the alert hidden by the user session.
      if (this.hasCookieValue(item.id, item.message)) {
        continue;
      }

      // Skip the alert not matching visibility rules.
      if (!this.isVisible(item.visibility)) {
        continue;
      }

      html += item.message;
    }
  }

  return html;
};

/**
 * Checks whether an alert is to be shown on a specified page.
 */
CivicThemeAlert.prototype.isVisible = function (visibilityString) {
  if ((typeof visibilityString === 'undefined') || visibilityString === false || visibilityString === '') {
    return true;
  }

  let pageVisibility = visibilityString.replace(/\*/g, '[^ ]*');
  // Replace '<front>' with "/".
  pageVisibility = pageVisibility.replace('<front>', '/');
  // Replace all occurrences of '/' with '\/'.
  // eslint-disable-next-line
  pageVisibility = pageVisibility.replace('/', '\/');

  const pageVisibilityRules = pageVisibility.split(/\r?\n/);
  if (pageVisibilityRules.length !== 0) {
    const path = this.urlPath();

    for (let r = 0, rlen = pageVisibilityRules.length; r < rlen; r++) {
      if (path === pageVisibilityRules[r]) {
        return true;
      }

      if (pageVisibilityRules[r].indexOf('*') !== -1 && path.match(new RegExp(`^${pageVisibilityRules[r]}`))) {
        return true;
      }
    }
    return false;
  }

  return true;
};

/**
 * Check if response object is valid.
 */
CivicThemeAlert.prototype.isValidResponse = function (item) {
  return typeof item === 'object' && 'id' in item && 'message' in item && 'visibility' in item;
};

/**
 * Get the cookie name.
 */
CivicThemeAlert.prototype.getCookieName = function () {
  return 'ct-alert-hide';
};

/**
 * Check if cookie has value.
 */
CivicThemeAlert.prototype.hasCookieValue = function (id, message) {
  const cookie = this.getCookie();
  return id in cookie && cookie[id] === this.hashString(this.removeHtml(message));
};

/**
 * Sets an cookie value.
 */
CivicThemeAlert.prototype.setCookieValue = function (id, message) {
  const cookie = this.getCookie();
  cookie[id] = this.hashString(this.removeHtml(message));
  this.setCookie(cookie);
};

/**
 * Get cookie value.
 */
CivicThemeAlert.prototype.getCookie = function () {
  let cookie = {};

  const values = document.cookie.split(';').filter((item) => item.trim().startsWith(`${this.getCookieName()}=`));
  if (values.length !== 1) {
    return cookie;
  }

  const stringValues = values[0].trim().replace(`${this.getCookieName()}=`, '');
  if (typeof stringValues !== 'string') {
    return cookie;
  }

  try {
    cookie = JSON.parse(stringValues);
  } catch (e) {
    cookie = {};
  }

  return cookie;
};

/**
 * Set a cookie.
 */
CivicThemeAlert.prototype.setCookie = function (value) {
  document.cookie = `${this.getCookieName()}=${JSON.stringify(value)}; SameSite=Strict; Path=/`;
};

/**
 * Simple HTML remover.
 */
CivicThemeAlert.prototype.removeHtml = function (string) {
  return string
    .replace(/(\r\n|\n|\r)/g, '')
    .replace(/\s/g, '')
    .replace(/(&nbsp;|<([^>]+)>)/ig, '')
    .trim();
};

/**
 * Hash string.
 */
CivicThemeAlert.prototype.hashString = function (string) {
  let hash = 0;
  let i;
  let
    chr;
  if (string.length === 0) return hash;
  for (i = 0; i < string.length; i++) {
    chr = string.charCodeAt(i);
    // eslint-disable-next-line no-bitwise
    hash = ((hash << 5) - hash) + chr;
    // eslint-disable-next-line no-bitwise
    hash |= 0;
  }
  return hash;
};

/**
 * Insert alerts into container.
 */
CivicThemeAlert.prototype.insert = function (html) {
  // Build the alert.
  this.container.insertAdjacentHTML('afterbegin', html);
  this.setDismissListeners();
};

/**
 * Sets dismiss listeners to alerts.
 */
CivicThemeAlert.prototype.setDismissListeners = function () {
  // Process the Close button of each alert.
  document
    .querySelectorAll('[data-alert-dismiss-trigger]')
    .forEach((el) => {
      el.addEventListener('click', (event) => {
        event.stopPropagation();
        const parent = this.getParentElement(event.currentTarget, '[data-component-name="ct-alert"]');
        this.dismiss(parent);
      });
    });
};

/**
 * Dismisses an alert and adds cookie to dismiss for session.
 */
CivicThemeAlert.prototype.dismiss = function (element) {
  if (element !== null) {
    const parent = this.getParentElement(element, '[data-component-name="ct-alerts"]');
    if (parent) {
      parent.removeChild(element);
    }
    const id = element.getAttribute('data-alert-id');
    if (id) {
      this.setCookieValue(id, element.outerHTML);
    }
  }
};

/**
 * Get a parent element matching a selector.
 */
CivicThemeAlert.prototype.getParentElement = function (element, selector) {
  while (element !== null && !element.matches(selector)) {
    element = element.parentNode;
  }
  return element;
};

/**
 * Get current path from URL or data attribute.
 *
 * 'data-test-path' attribute is used for testing of this component within
 * Storybook.
 */
CivicThemeAlert.prototype.urlPath = function () {
  return this.container.getAttribute('data-test-path') || window.location.pathname;
};

/**
 * Initialise component.
 */
document.querySelectorAll('[data-component-name="ct-alerts"]').forEach((el) => {
  new CivicThemeAlert(el);
});
