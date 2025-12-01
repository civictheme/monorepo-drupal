export default {
  args: (theme = 'light') => ({
    theme,
    content_top: '',
    title: 'Filter results by:',
    form_attributes: '',
    form_hidden_fields: '',
    items: [
      {
        text: 'Option A',
        name: 'option_name',
        is_selected: false,
        attributes: '',
      },
      {
        text: 'Option B',
        name: 'option_name',
        is_selected: false,
        attributes: '',
      },
      {
        text: 'Option C',
        name: 'option_name',
        is_selected: false,
        attributes: '',
      },
    ],
    submit_text: 'Apply',
    reset_text: 'Clear all',
    content_bottom: '',
    is_multiple: false,
    attributes: '',
    modifier_class: '',
  }),
};
