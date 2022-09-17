// phpcs:ignoreFile
/**
 * Handles views exposed filter forms providing auto-submit functionality.
 */
Drupal.behaviors.civictheme_ajax_views = {
  // eslint-disable-next-line no-unused-vars
  attach: function attach(context, settings) {
    // eslint-disable-next-line no-undef
    const $form = jQuery('[data-civictheme-filter]', context).once('civicthemeAjaxView');
    if ($form.length === 0) {
      return;
    }
    let debounce;
    // Button submit handler for both large and basic filter types.
    const buttonSubmitHandler = () => {
      let $filter = $form.find('[data-component-name="civictheme-group-filter"]');
      let isAutosubmit;
      if ($filter.length > 0) {
        isAutosubmit = typeof $filter.attr('data-group-filter-auto-submit') !== 'undefined' && $filter.attr('data-group-filter-auto-submit') === 'true';
      } else {
        $filter = $form.find('[data-component-name="civictheme-basic-filter"]');
        isAutosubmit = $filter.length > 0;
      }
      if (isAutosubmit === true) {
        // We do not want to submit on every click, we want user to be able
        // to select several checkboxes or radio buttons without submitting.
        const timeout = $form.attr('data-civictheme-filter-ajax-submit-timeout') !== null ? Number($form.attr('data-civictheme-filter-ajax-submit-timeout')) : 500;
        if (timeout > 0) {
          if (typeof debounce !== 'undefined') {
            clearTimeout(debounce);
          }
          debounce = setTimeout(() => {
            $form.find('[type="submit"]').trigger('click');
          }, timeout);
        }
        else {
          $form.find('[type="submit"]').trigger('click');
        }
      }
    };
    const filterType = $form.attr('data-civictheme-filter-type');
    const ajaxForm = $form.attr('data-civictheme-filter-ajax') === 'true';
    if (filterType === 'large') {
      if (ajaxForm) {
        // Attach reload of view results in with redrawing of filters for
        // ajax forms.
        $form
          .find('[data-component-name="civictheme-group-filter"]')
          // Custom event from civictheme large filter.
          .on('civicthemeLargeFilterChange', buttonSubmitHandler);
        // Stop clear filter function from submitting form.
        $form
          .find('[data-group-filter-clear]')
          .on('click', (e) => e.preventDefault());
        $form
          .find('[data-component-name="chip"]')
          .on('click', (e) => e.preventDefault());
      } else {
        // For non-ajax forms add click listener to dismissable filter chips
        // so page is reloaded with correct view results when clicked.
        // Other than this rely on clicking apply button to update view
        // results.
        $form
          .find('[data-component-name="civictheme-group-filter"]')
          // Custom event from civictheme large filter.
          .on('civicthemeLargeFilterChange', () => {
            // CivicTheme large filter redraws selected filters on each change in
            // the dropdown, when it redraws them we wish to re-add the button
            // submit handler to them so dismissing a filter chip reloads the
            // page.
            $form
              .find('[data-component-name="chip"]')
              .on('click', buttonSubmitHandler);
          });
      }
    } else {
      $form
        .find('[data-component-name="chip"] input')
        .on('change', buttonSubmitHandler);
      $form
        .find('button[data-component-name="chip"]')
        .on('click', buttonSubmitHandler);
    }
  },
};
