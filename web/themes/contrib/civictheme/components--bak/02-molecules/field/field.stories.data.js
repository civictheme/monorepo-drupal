// phpcs:ignoreFile
export default {
  args: (theme = 'light', options = {}) => {
    const name = `option_group_${Math.floor(Math.random() * 1000)}`;
    const control = options.controls ? [1, 2, 3].map((i) => ({
      label: `Control item ${i}`,
      name,
      value: '',
      id: `${name}_control_item_${i}`,
      is_required: options.is_required || false,
      is_invalid: false,
      is_disabled: false,
      attributes: null,
      modifier_class: '',
    })) : null;

    const selectOptions = options.options ? [1, 2, 3].map((i) => ({
      type: 'option',
      label: `Option ${i}`,
      value: `option_${i}`,
      selected: false,
    })) : null;
    const fieldId = `field_id_${Math.floor(Math.random() * 1000)}`;
    return {
      theme,
      type: 'textfield',
      title: 'Field title',
      title_display: 'visible',
      title_size: 'regular',
      description: 'Description content sample.',
      message: {
        content: 'Message conte nt sample.',
        attributes: `id="${fieldId}--error-message"`,
      },
      is_required: options.is_required || false,
      required_text: '',
      is_invalid: false,
      is_disabled: false,
      orientation: 'vertical',
      is_inline: false,
      name,
      value: '',
      placeholder: 'Field placeholder',
      id: fieldId,
      control,
      options: selectOptions,
      attributes: null,
      modifier_class: '',
      prefix: '',
      suffix: '',
    };
  },
};
