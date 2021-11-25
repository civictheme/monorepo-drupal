/**
 * Alert component.
 *
 * Attaches to markup with 'data-component-name="civic-alerts"' attribute.
 *
 * Available attributes:
 * - data-alert-endpoint: Alert REST configurable API endpoint.
 */

function CivicAlerts(el) {
  this.alertContainer = el;
  this.endpoint = this.alertContainer.getAttribute('data-alert-endpoint');
  if (this.endpoint !== null) {
    this.getAlerts();
  }
}

/**
 * Checks whether an alert is to be shown on a specified page.
 */
CivicAlerts.prototype.checkPageVisibility = function (pageVisibilityString) {
  if ((typeof pageVisibilityString !== 'undefined') && pageVisibilityString !== false && pageVisibilityString !== '') {
    let pageVisibility = pageVisibilityString.replace(/\*/g, '[^ ]*');
    // Replace '<front>' with "/".
    pageVisibility = pageVisibility.replace('<front>', '/');
    // Replace all occurrences of '/' with '\/'.
    // eslint-disable-next-line
    pageVisibility = pageVisibility.replace('/', '\/');
    const pageVisibilityRules = pageVisibility.split(/\r?\n/);
    if (pageVisibilityRules.length !== 0) {
      const path = window.location.pathname;
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
  }
  return true;
};

/**
 * Checks whether an alert cookie is already set.
 */
CivicAlerts.prototype.hasAlertCookie = function (cookie) {
  return (document.cookie.split(';').some((item) => item.trim().startsWith(`${cookie}=`)));
};

/**
 * Sets an alert cookie.
 */
CivicAlerts.prototype.setAlertCookie = function (cookie) {
  if (!this.hasAlertCookie(cookie)) {
    document.cookie = `${cookie}=1; SameSite=Strict`;
  }
};

/**
 * Gets alerts from endpoint.
 */
CivicAlerts.prototype.getAlerts = function (retry = false) {
  const request = new XMLHttpRequest();
  request.open('Get', this.endpoint);
  request.onreadystatechange = () => {
    if (request.readyState === 4) {
      if (request.status === 200) {
        const response = JSON.parse(request.responseText);
        this.insertAlerts(response);
        return;
      }
      // If failed try again once.
      if (retry === false) {
        this.getAlerts(true);
      }
    }
  };
  request.send();
};

/**
 * Inserts active alerts into page.
 */
CivicAlerts.prototype.insertAlerts = function (response) {
  if (response.length) {
    let alertHtml = '';
    for (let i = 0, len = response.length; i < len; i++) {
      const alertItem = response[i];
      // Skips the alert hidden by user session.
      if (this.hasAlertCookie(`civic_alert_hide_id_${alertItem.alert_id}`)) {
        continue;
      }
      // Determine page visibility for this alert.
      if (!this.checkPageVisibility(alertItem.page_visibility)) {
        // Path doesn't match, skip it.
        continue;
      }
      alertHtml += alertItem.message;
    }
    // Build the alert.
    const alertContainer = document.querySelector('.civic-alerts');
    alertContainer.insertAdjacentHTML('beforeend', alertHtml);
    this.setDismissAlertListeners();
  }
};

/**
 * Sets dismiss listeners to alerts.
 */
CivicAlerts.prototype.setDismissAlertListeners = function () {
  // Process the Close button of each alert.
  document
    .querySelectorAll('.civic-alerts .civic-alert__close-icon')
    .forEach((element) => {
      element.addEventListener('click', (event) => {
        event.stopPropagation();
        const alert = this.parents(event.currentTarget, '.civic-alert');
        if (alert !== null) {
          const alertId = alert.getAttribute('data-alert-id');
          this.setAlertCookie(`civic_alert_hide_id_${alertId}`);
          alert.parentNode.removeChild(alert);
        }
      });
    });
};

/**
 * Traversal helper to get a parent element matching a selector.
 */
CivicAlerts.prototype.parents = function (element, selector) {
  while (element !== null && !element.matches(selector)) {
    element = element.parentNode;
  }
  return element;
};

// Initialise alerts.
const alertContainer = document.querySelector('[data-component-name="civic-alerts"]');
if (alertContainer !== null) {
  new CivicAlerts(alertContainer);
}
