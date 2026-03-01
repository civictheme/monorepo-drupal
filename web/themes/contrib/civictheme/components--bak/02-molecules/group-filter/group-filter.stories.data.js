// phpcs:ignoreFile
import Field from '../field/field.twig';
import FieldData from '../field/field.stories.data';

export default {
  args: (theme = 'light', options = {}) => {
    let selectedFilters = {};
    if (options.selectedFilters) {
      selectedFilters = {
        show_selected_filters: true,
        selected_title: 'Selected filters:',
        selected_filters: [
          {
            text: 'Option A',
            url: '#',
            label: 'Selected filter: Option A - Click to remove filter.',
          },
          {
            text: 'Option B',
            url: '#',
            label: 'Selected filter: Option B - Click to remove filter.',
          },
          {
            text: 'Option C',
            url: '#',
            label: 'Selected filter: Option C - Click to remove filter.',
          },
        ],
        selected_clear_link: {
          text: 'Clear all',
          url: '#',
          type: 'secondary',
          size: 'regular',
          icon: 'close-outline',
          icon_placement: 'after',
        },
      };
    }
    return {
      theme,
      title: 'Filter results by:',
      filters: [
        {
          title: `Filter 1`,
          content: [
            Field({
              ...FieldData.args(theme, { controls: true }),
              type: 'checkbox',
              description: null,
              message: null,
              orientation: 'vertical',
            }),
          ],
        },
        {
          title: `Filter 2`,
          content: [
            Field({
              ...FieldData.args(theme, { controls: true }),
              type: 'radio',
              description: null,
              message: null,
              orientation: 'vertical',
            }),
            Field({
              ...FieldData.args(theme),
              description: null,
              message: null,
              orientation: 'vertical',
            }),
          ].join(''),
        },
      ],
      submit_text: 'Submit',
      form_attributes: null,
      form_hidden_fields: null,
      content_top: '',
      content_bottom: '',
      attributes: null,
      modifier_class: '',
      ...selectedFilters,
    };
  },
};
