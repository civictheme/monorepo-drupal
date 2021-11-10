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
        var alert_id = $(event.currentTarget).attr('data-alert-id');
        $.cookie('civic_alert_hide_id_' + alert_id, true);
        $('article[data-alert-id="' + alert_id + '"]').remove();
      });

      // Loads the alerts for REST endpoint.
      $('.civic-alerts:not(.civic-alerts--processed)', context).once('civic-alerts--load').each(function (index, element) {
        var endpoint = $(element).attr('data-alert-endpoint');
        if ((typeof endpoint == 'undefined') || !endpoint || endpoint.length === 0) {
          endpoint = '/api/civic_alerts?_format=json';
        }
        $.getJSON(endpoint, function (response) {
          if (response.length) {
            var $placeholder = $(element);
            $placeholder.html('').addClass('civic-alerts--processed');
            for (var i = 0, len = response.length; i < len; i++) {
              var alert_item = response[i];
              var alert_id = response[i].alert_id;
              // Skips the alert hidden by user session.
              if (typeof $.cookie('civic_alert_hide_id_' + alert_id) !== 'undefined') {
                continue;
              }

              // Determine page visibility for this alert.
              if (!checkPageVisibility(alert_item.page_visibility)) {
                // Path doesn't match, skip it.
                continue;
              }

              // Build the alert.
              var $alert = $('<article role="article" data-alert-id="' + alert_id + '"><div class="civic-alert__content"></div></article>');
              // Set alert type and priority.
              if ((typeof alert_item.alert_type !== 'undefined') && (alert_item.alert_type !== "")) {
                $alert.attr('data-alert-type', alert_item.alert_type);
              }

              // Sets the message.
              if (typeof alert_item.message !== 'undefined') {
                var alert_message = $(alert_item.message);
                alert_message.find('.civic-alert__close-icon').attr('data-alert-id', alert_id);
                alert_message.appendTo($alert.find('.civic-alert__content'));
              }
              $alert.appendTo($placeholder);
            }
            Drupal.behaviors.AlertBannersRestBlock.attach(context, settings);
          }
        });
      });
    }
  };

})(jQuery, Drupal);
