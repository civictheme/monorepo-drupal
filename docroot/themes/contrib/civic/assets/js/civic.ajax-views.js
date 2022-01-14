Drupal.behaviors.civic_ajax_views = {
  // eslint-disable-next-line no-unused-vars
  attach: function attach(context, settings) {
    // eslint-disable-next-line no-undef
    const $form = jQuery('[data-civic-filter]', context).once('civicAjaxView');
    if ($form.length > 0) {
      let debounce;
      // Submit handler for both filter types.
      const submitHandler = () => {
        // We do not want to submit on every click, we want user to be able
        // to select several checkboxes or radio buttons without submitting.
        if (typeof debounce !== 'undefined') {
          clearTimeout(debounce);
        }
        debounce = setTimeout(() => {
          $form.find('[type="submit"]').trigger('click');
        }, 500);
      };
      const filterType = $form.attr('data-civic-filter-type');
      if (filterType === 'large') {
        $form
          .find('.civic-large-filter')
          // Custom event from civic large filter.
          .on('civic-large-filter-change', submitHandler);
        // Stop clear filter function from submitting form.
        $form
          .find('.civic-large-filter__clear-all')
          .on('click', (e) => { e.preventDefault(); });
      } else {
        $form
          .find('[data-component-name="filter-chip"] input')
          .on('change', submitHandler);
      }
    }
  },
};
