/**
 * @file
 * Provides RESTful API functionality for Civic Alerts.
 */

(function ($, Drupal) {
  'use strict';

  function checkPageVisibility(page_visibility_string) {
    if ((typeof page_visibility_string !== 'undefined') && page_visibility_string !== false && page_visibility_string !== "") {
      var page_visibility = page_visibility_string.replace(/\*/g, "[^ ]*");
      // Replace '<front>' with "/".
      page_visibility = page_visibility.replace('<front>', '/');
      // Replace all occurrences of '/' with '\/'.
      page_visibility = page_visibility.replace('/', '\/');
      var page_visibility_rules = page_visibility.split(/\r?\n/);
      if (page_visibility_rules.length !== 0) {
        var path = window.location.pathname;
        for (var r = 0, rlen = page_visibility_rules.length; r < rlen; r++) {
          if (path === page_visibility_rules[r]) {
            return true;
          }
          else if (page_visibility_rules[r].indexOf('*') !== -1 && path.match(new RegExp('^' + page_visibility_rules[r]))) {
            return true;
          }
        }
        return false;
      }
    }
    return true;
  }

  Drupal.behaviors.AlertBannersRestBlock = {
    attach: function (context, settings) {
      // Process the Close button of each alert.
      $('.civic-alerts .civic-alert__close-icon', context).click(function (event) {
        event.stopPropagation();
        var alertId = $(event.currentTarget).attr('data-alert-id');
        this.setAlertCookie(`civic_alert_hide_id_${alertId}`);
        $('article[data-alert-id="' + alertId + '"]').remove();
      });

      // Loads the alerts for REST endpoint.
      this.getAlerts();
    },
    hasCookie(cookie) {
      return (document.cookie.split(';').some((item) => item.trim().startsWith(`${cookie}=`)));
    },
    setAlertCookie(cookie) {
      if (!this.hasCookie(cookie)) {
        document.cookie = `${cookie}=1`;
      }
    },
    getAlerts(retry = false) {
      const endpoint = '/api/civic_alerts?_format=json';
      const request = new XMLHttpRequest();
      request.open('Get', endpoint);
      request.onreadystatechange = () => {
        if (request.readyState === 4) {
          if (request.status === 200) {
            const response = JSON.parse(request.responseText);
            this.setAlerts(response);
            return;
          }
          // If failed try again once.
          if (retry === false) {
            this.getAlerts(true);
          }
        }
      };
      request.send();
    },
    setAlerts(response) {
      if (response.length) {
        // var $placeholder = $(element);
        // $placeholder.html('').addClass('civic-alerts--processed');
        let alertHtml = '';
        for (let i = 0, len = response.length; i < len; i++) {
          const alertItem = response[i];
          // Skips the alert hidden by user session.
          if (this.hasCookie(`civic_alert_hide_id_${alertItem.alert_id}`)) {
            continue;
          }
          // Determine page visibility for this alert.
          if (!checkPageVisibility(alertItem.page_visibility)) {
            // Path doesn't match, skip it.
            continue;
          }
          alertHtml += alertItem.message;
        }
        // Build the alert.
        document.querySelector('.civic-alerts').insertAdjacentHTML('beforeend', alertHtml);
      }
    },
  };

})(jQuery, Drupal);
