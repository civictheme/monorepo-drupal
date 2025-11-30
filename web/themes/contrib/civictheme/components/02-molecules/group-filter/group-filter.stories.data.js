import Field from '../field/field.twig';
import FieldData from '../field/field.stories.data';

export default {
  args: (theme = 'light') => ({
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
    form_attributes: '',
    form_hidden_fields: '',
    content_top: '',
    content_bottom: '',
    attributes: '',
    modifier_class: '',
  }),
};
