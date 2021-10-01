import { boolean, radios, select, text } from '@storybook/addon-knobs'

import CivicTag from './tag.twig'
import './tag.scss';

export default {
  title: 'Atom/Tag',
  parameters: {
    layout: 'centered',
  },
}

export const Tag = () => {
  const tagKnobTab = 'Tag';
  const iconKnobTab = 'Icon';

  const tagParams = {
    theme: radios(
      'Theme', {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
      tagKnobTab
    ),
    text: text('Text', 'Button Text', tagKnobTab),
    hidden_value: text('Hidden value', 'Hidden value', tagKnobTab),
    modifier_class: text('Additional class', '', tagKnobTab)
  };

  const colors = CIVIC_VARIABLES['civic-default-colors']
  const icons = CIVIC_ICON.icons

  const iconParams = {
    icon: boolean('With icon', false, iconKnobTab),
    icon_placement: radios(
      'Icon position', {
        'Before': 'before',
        'After': 'after',
      },
      'before',
      iconKnobTab
    ),
    symbol: select('Symbol', icons, 'business_calendar', iconKnobTab),
    icon_color: select('Color', colors, 'primary', iconKnobTab)
  }

  return CivicTag({...tagParams, ...iconParams});
}
