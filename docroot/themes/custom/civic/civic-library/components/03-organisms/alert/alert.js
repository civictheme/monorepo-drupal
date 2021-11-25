/**
 * Alert component.
 *
 * Attaches to markup with 'data-component-name="civic-alerts"' attribute.
 *
 * Available attributes:
 * - data-alert-endpoint: Alert REST configurable API endpoint.
 */

function CivicAlert(el) {
  this.alertContainer = el;
  this.endpoint = this.alertContainer.getAttribute('data-alert-endpoint');
  if (this.endpoint !== null) {
    this.getAlerts();
  }
}

/**
 * Checks whether an alert is to be shown on a specified page.
 */
CivicAlert.prototype.isVisible = function (pageVisibilityString) {
  if ((typeof pageVisibilityString === 'undefined') || pageVisibilityString === false || pageVisibilityString === '') {
    return true;
  }

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

  return true;
};

/**
 * Checks whether an alert cookie is already set.
 */
CivicAlert.prototype.hasCookie = function (cookie) {
  return document.cookie.split(';').some((item) => item.trim().startsWith(`${cookie}=`));
};

/**
 * Sets an alert cookie.
 */
CivicAlert.prototype.setCookie = function (cookie) {
  if (!this.hasCookie(cookie)) {
    document.cookie = `${cookie}=1; SameSite=Strict`;
  }
};

/**
 * Gets alerts from endpoint.
 */
CivicAlert.prototype.getAlerts = function (retry = false) {
  const request = new XMLHttpRequest();
  request.open('Get', this.endpoint);
  request.onreadystatechange = () => {
    if (request.readyState === 4) {
      if (request.status === 200) {
        const response = JSON.parse(request.responseText);
        const alerts = this.filterAlerts(response);
        this.insertAlerts(alerts);
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
 * Filters out alerts not to show ie dismissed, page-specific alerts.
 */
CivicAlert.prototype.filterAlerts = function (response) {
  let alertHtml = '';
  if (response.length) {
    for (let i = 0, len = response.length; i < len; i++) {
      const alertItem = response[i];
      // Skips the alert hidden by user session.
      if (this.hasCookie(`civic_alert_hide_id_${alertItem.alert_id}`)) {
        continue;
      }
      // Determine page visibility for this alert.
      if (!this.isVisible(alertItem.page_visibility)) {
        // Path doesn't match, skip it.
        continue;
      }
      alertHtml += alertItem.message;
    }
  }
  return alertHtml;
};

/**
 * Insert alerts into container.
 */
CivicAlert.prototype.insertAlerts = function (html) {
  // Build the alert.
  this.alertContainer.insertAdjacentHTML('beforeend', html);
  this.setDismissAlertListeners();
};

/**
 * Sets dismiss listeners to alerts.
 */
CivicAlert.prototype.setDismissAlertListeners = function () {
  // Process the Close button of each alert.
  document
    .querySelectorAll('[data-alert-dismiss]')
    .forEach((el) => {
      el.addEventListener('click', (event) => {
        event.stopPropagation();
        const alert = this.parent(event.currentTarget, '[data-component-name="civic-alert"]');
        this.dismissAlert(alert);
      });
    });
};

/**
 * Dismisses an alert and adds cookie to dismiss for session.
 */
CivicAlert.prototype.dismissAlert = function (alert) {
  if (alert !== null) {
    const alertId = alert.getAttribute('data-alert-id');
    this.setCookie(`civic_alert_hide_id_${alertId}`);
    alert.parentNode.removeChild(alert);
  }
};

/**
 * Traversal helper to get a parent element matching a selector.
 */
CivicAlert.prototype.parent = function (element, selector) {
  while (element !== null && !element.matches(selector)) {
    element = element.parentNode;
  }
  return element;
};

// Initialise alerts.
document.querySelectorAll('[data-component-name="civic-alerts"]').forEach((el) => {
  new CivicAlert(el);
});
