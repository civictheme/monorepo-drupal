// phpcs:ignoreFile
export default {
  args: (theme = 'light') => ({
    theme,
    content_top: '',
    title: 'Filter results by:',
    form_attributes: null,
    form_hidden_fields: null,
    items: [
      {
        text: 'Option A',
        name: 'option_name',
        is_selected: false,
        attributes: null,
      },
      {
        text: 'Option B',
        name: 'option_name',
        is_selected: false,
        attributes: null,
      },
      {
        text: 'Option C',
        name: 'option_name',
        is_selected: false,
        attributes: null,
      },
    ],
    submit_text: 'Apply',
    reset_text: 'Clear all',
    content_bottom: '',
    is_multiple: false,
    attributes: null,
    modifier_class: '',
  }),
};
