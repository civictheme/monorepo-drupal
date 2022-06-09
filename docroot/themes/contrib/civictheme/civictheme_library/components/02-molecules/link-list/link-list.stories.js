import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';

import CivicThemeLinkList from './link-list.twig';
import { randomLinks } from '../../00-base/base.stories';

export default {
  title: 'Molecules/Link List',
  parameters: {
    layout: 'centered',
  },
};

export const LinkList = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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
    title: text('Title', 'Optional list title', generalKnobTab),
    is_inline: boolean('Inline', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    links: randomLinks(number(
      'Number of links',
      5,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    )),
  };

  return CivicThemeLinkList({
    ...generalKnobs,
  });
};
