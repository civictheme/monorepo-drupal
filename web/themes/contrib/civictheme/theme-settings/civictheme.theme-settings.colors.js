/**
 * @file
 * CivicTheme theme settings colour picker integration.
 *
 * Embeds the UI Kit colour picker (dist/colour-picker/ui/index.html, same
 * origin) and syncs it with the form: the stored recipe loads into the
 * picker, and every picker change writes the recipe JSON and the emitted
 * overrides CSS back into the form fields that Drupal saves. All colour
 * computation happens inside the picker's engine - this file only moves
 * strings between the iframe and the form.
 */
(function (Drupal, once) {
  'use strict';

  var POLL_INTERVAL_MS = 100;
  var POLL_ATTEMPTS = 300;

  /**
   * Store the picker state into the form fields.
   */
  function sync(picker, recipeField, cssField) {
    recipeField.value = JSON.stringify(picker.getRecipe(), null, 2);
    // Drop the `//` banner lines: they are valid in the .scss drop-in the
    // picker exports, but the stored string is served as plain CSS where
    // `//` comments would break parsing of the first block.
    var css = picker.getOverridesCss()
      .split('\n')
      .filter(function (line) {
        return line.trim().indexOf('//') !== 0;
      })
      .join('\n')
      .replace(/^\n+/, '');
    // An identity recipe emits a comment-only stylesheet - store nothing so
    // no overrides file is attached to the site.
    cssField.value = css.indexOf('{') === -1 ? '' : css;
  }

  /**
   * Connect the booted picker to the form.
   */
  function init(picker, recipeField, cssField, status) {
    var stored = recipeField.value.trim();
    if (stored) {
      try {
        picker.setRecipe(JSON.parse(stored));
      }
      catch (error) {
        // Keep the stored fields untouched: the picker shows stock colours
        // and any change made in it will overwrite the fields below.
        status.textContent = Drupal.t('The stored colour recipe could not be loaded into the picker: @message', { '@message': error.message });
        picker.onChange(function () {
          sync(picker, recipeField, cssField);
          status.textContent = '';
        });
        return;
      }
    }
    picker.onChange(function () {
      sync(picker, recipeField, cssField);
    });
    sync(picker, recipeField, cssField);
    status.textContent = '';
  }

  /**
   * Poll the iframe for the picker embed API.
   *
   * Polling covers both boot orders: the `civicthemecolourpicker:ready`
   * event may fire before this script can subscribe to the iframe window.
   */
  function connect(iframe, recipeField, cssField, status) {
    var attempts = 0;
    status.textContent = Drupal.t('Loading the colour picker…');
    var timer = setInterval(function () {
      attempts++;
      var picker = null;
      try {
        picker = iframe.contentWindow && iframe.contentWindow.CivicThemeColourPicker;
      }
      catch (error) {
        picker = null;
      }
      if (picker) {
        clearInterval(timer);
        init(picker, recipeField, cssField, status);
      }
      else if (attempts >= POLL_ATTEMPTS) {
        clearInterval(timer);
        status.textContent = Drupal.t('The colour picker could not be loaded. Rebuild the theme (npm run dist) and check that dist/colour-picker exists, or paste recipe JSON and overrides CSS produced by "npx @civictheme/colour-picker" into the Recipe data fields.');
      }
    }, POLL_INTERVAL_MS);
  }

  Drupal.behaviors.civicthemeColourPicker = {
    attach: function (context) {
      once('civictheme-colour-picker', '[data-civictheme-colour-picker]', context).forEach(function (iframe) {
        var recipeField = document.querySelector('[data-civictheme-colour-recipe]');
        var cssField = document.querySelector('[data-civictheme-colour-css]');
        var status = document.querySelector('[data-civictheme-colour-picker-status]');
        if (!recipeField || !cssField || !status) {
          return;
        }
        connect(iframe, recipeField, cssField, status);
      });
    }
  };
})(Drupal, once);
