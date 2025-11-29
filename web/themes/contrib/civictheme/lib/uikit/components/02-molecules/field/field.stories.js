// phpcs:ignoreFile
import { generateItems, knobBoolean, knobNumber, knobRadios, knobText, KnobValues, randomBool, randomId, randomLink, randomName, randomSentence, shouldRender } from '../../00-base/storybook/storybook.utils';
import CivicThemeField from './field.twig';
import CivicThemeFieldset from '../../01-atoms/fieldset/fieldset.twig';
import { Select } from '../../01-atoms/select/select.stories';
import { Textfield } from '../../01-atoms/textfield/textfield.stories';
import { Textarea } from '../../01-atoms/textarea/textarea.stories';
import { Radio } from '../../01-atoms/radio/radio.stories';
import { Checkbox } from '../../01-atoms/checkbox/checkbox.stories';

export default {
  title: 'Molecules/Field',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
    storyLayoutClass: 'ct-text-regular',
  },
};

export const Field = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    type: knobRadios(
      'Type',
      {
        Textfield: 'textfield',
        Textarea: 'textarea',
        Select: 'select',
        'Select Multiple': 'select_multiple',
        Radio: 'radio',
        Checkbox: 'checkbox',
        Hidden: 'hidden',
        Color: 'color',
        Date: 'date',
        Email: 'email',
        File: 'file',
        Image: 'image',
        Month: 'month',
        Number: 'number',
        Password: 'password',
        Range: 'range',
        Search: 'search',
        Tel: 'tel',
        Time: 'time',
        Url: 'url',
        Week: 'week',
        Other: 'other',
      },
      'textfield',
      parentKnobs.type,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'Field title', parentKnobs.title, parentKnobs.knobTab),
    title_display: knobRadios(
      'Title display',
      {
        Visible: 'visible',
        Invisible: 'invisible',
        Hidden: 'hidden',
      },
      'visible',
      parentKnobs.title_display,
      parentKnobs.knobTab,
    ),
    description: knobText('Description', `Description content sample. ${randomSentence(50)}`, parentKnobs.description, parentKnobs.knobTab),
    message: knobText('Message', `Message content sample. ${randomSentence(50)}`, parentKnobs.message, parentKnobs.knobTab),
    is_required: knobBoolean('Required', false, parentKnobs.is_required, parentKnobs.knobTab),
    is_invalid: knobBoolean('Has error', false, parentKnobs.is_invalid, parentKnobs.knobTab),
    is_disabled: knobBoolean('Disabled', false, parentKnobs.is_disabled, parentKnobs.knobTab),
    orientation: knobRadios(
      'Orientation',
      {
        Horizontal: 'horizontal',
        Vertical: 'vertical',
      },
      'vertical',
      parentKnobs.orientation,
      parentKnobs.knobTab,
    ),
    is_inline: knobBoolean('Inline (for group controls)', false, parentKnobs.is_inline, parentKnobs.knobTab),
    title_width: knobRadios(
      'Fixed title width',
      {
        None: 'none',
        '15 particles': '15',
        '25 particles': '25',
      },
      'none',
      parentKnobs.title_width,
      parentKnobs.knobTab,
    ),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  if (knobs.description && knobs.description.length > 0) {
    knobs.description += ` ${randomLink()}`;
  }

  if (knobs.message && knobs.message.length > 0) {
    knobs.message += ` ${randomLink()}`;
  }

  if (knobs.title_width !== 'none') {
    knobs.modifier_class += ` ct-field--with-fixed-title--${knobs.title_width}`;
  }

  let controlKnobs = {};
  const name = randomName();
  switch (knobs.type) {
    case 'textfield':
      controlKnobs = Textfield(new KnobValues({ theme: knobs.theme }, false));
      break;

    case 'textarea':
      controlKnobs = Textarea(new KnobValues({ theme: knobs.theme }, false));
      break;

    case 'select':
      controlKnobs = Select(new KnobValues({ theme: knobs.theme }, false));
      break;

    case 'select_multiple':
      controlKnobs = Select(new KnobValues({ theme: knobs.theme, is_multiple: true }, false));
      knobs.type = 'select';
      break;

    case 'radio':
      controlKnobs.control = generateItems(knobNumber(
        'Items count',
        5,
        {
          range: true,
          min: 0,
          max: 10,
          step: 1,
        },
        parentKnobs.items_count,
        parentKnobs.knobTab,
      ), (idx, count) => ({
        ...Radio(new KnobValues({ theme: knobs.theme }, false)),
        ...{
          id: randomId(`${name}-${idx}`),
          // All radio in a group should have the same name.
          name,
          // Make the last radio checked by default.
          is_checked: idx === count - 1,
        },
      }));
      break;

    case 'checkbox':
      controlKnobs.control = generateItems(knobNumber(
        'Items count',
        5,
        {
          range: true,
          min: 0,
          max: 10,
          step: 1,
        },
        parentKnobs.items_count,
        parentKnobs.knobTab,
      ), (idx) => ({
        ...Checkbox(new KnobValues({ theme: knobs.theme }, false)),
        ...{
          id: randomId(`${name}-${idx}`),
          is_checked: randomBool(0.6),
        },
      }));
      break;

    default:
      controlKnobs = {
        id: randomId(name),
        // All radio in a group should have the same name.
        name,
      };
  }

  // Merge and override knob values from controls with the values taken from
  // the knobs of this story.
  const combinedKnobs = { ...controlKnobs, ...knobs };

  return shouldRender(parentKnobs) ? CivicThemeField(combinedKnobs) : combinedKnobs;
};

export const FieldExamples = (parentKnobs = {}) => {
  const inputTypes = [
    'textfield',
    'textarea',
    'select',
    'radio',
    'checkbox',
  ];

  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    title_width: knobRadios(
      'Fixed label width',
      {
        None: 'none',
        '15 particles': '15',
        '25 particles': '25',
      },
      'none',
      parentKnobs.title_width,
      parentKnobs.title_width,
    ),
    is_required: knobBoolean('Required', false, parentKnobs.is_required, parentKnobs.knobTab),
    is_invalid: knobBoolean('Has error', false, parentKnobs.is_invalid, parentKnobs.knobTab),
    is_disabled: knobBoolean('Disabled', false, parentKnobs.is_disabled, parentKnobs.knobTab),
    with_description: knobBoolean('With description', false, parentKnobs.description, parentKnobs.knobTab) ? randomSentence(50, 'field-description') : null,
    with_message: knobBoolean('With message', false, parentKnobs.message, parentKnobs.knobTab) ? randomSentence(50, 'field-message') : null,
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  if (knobs.title_width !== 'none') {
    knobs.modifier_class += ` ct-field--with-fixed-title--${knobs.title_width}`;
  }

  const fieldsets = [
    CivicThemeFieldset({
      theme: knobs.theme,
      fields: inputTypes.map((type) => Field(new KnobValues({
        theme: knobs.theme,
        type,
        orientation: 'vertical',
        description: knobs.with_description,
        message: knobs.with_message,
        is_required: knobs.is_required,
        is_invalid: knobs.is_invalid,
        is_disabled: knobs.is_disabled,
        modifier_class: knobs.modifier_class,
        attributes: knobs.attributes,
      }))).join(''),
    }),
    CivicThemeFieldset({
      theme: knobs.theme,
      fields: inputTypes.map((type) => Field(new KnobValues({
        theme: knobs.theme,
        type,
        orientation: 'horizontal',
        title_width: knobs.title_width,
        description: knobs.with_description,
        message: knobs.with_message,
        is_required: knobs.is_required,
        is_invalid: knobs.is_invalid,
        is_disabled: knobs.is_disabled,
        modifier_class: knobs.modifier_class,
        attributes: knobs.attributes,
      }))).join(''),
    }),
  ];

  return fieldsets.join('');
};
