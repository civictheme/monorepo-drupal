Drupal.behaviors.civic_ajax_views = {
  // eslint-disable-next-line no-unused-vars
  attach: function attach(context, settings) {
    // eslint-disable-next-line no-undef
    const $form = jQuery(context).once('civicAjaxView');
    $form
      .find('.civic-input__element')
      .not('.civic-input--submit')
      .on('change', () => {
        console.log('test');
        $form.find('[type="submit"]').trigger('click');
      });
  },
};
