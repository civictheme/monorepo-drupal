import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicIconLink from './icon-link.twig';
import { randomUrl } from '../../00-base/base.stories';

export default {
  title: 'Molecules/Icon Link',
  parameters: {
    layout: 'centered',
  },
};

export const IconLink = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const { icons } = ICONS;
  const sizes = [...new Set([
    ...SCSS_VARIABLES['civic-icon-sizes-default'],
    ...SCSS_VARIABLES['civic-icon-sizes'],
  ])];

  const defaultIcon = icons.indexOf('brands_facebook');

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    symbol: select('Symbol', icons, defaultIcon !== -1 ? icons[defaultIcon] : icons[0], generalKnobTab),
    size: radios('Size', sizes, sizes[0], generalKnobTab),
    text: text('Text', 'Go to service', generalKnobTab),
    url: text('URL', randomUrl(), generalKnobTab),
    content: text('Content', '', generalKnobTab),
    with_border: boolean('Add border', true, generalKnobTab),
    new_window: boolean('Open in a new window', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicIconLink({
    ...generalKnobs,
  });
};
