import {
  boolean, radios, text,
} from '@storybook/addon-knobs';
import { getSlots, randomUrl } from '../../00-base/base.stories';

import CivicServiceCard from './service-card.twig';

export default {
  title: 'Molecules/Card/Service Card',
  parameters: {
    layout: 'centered',
  },
};

export const ServiceCard = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  // Current component parameters.
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
    title: text('Title', 'Services category title across one or two lines', generalKnobTab),
    links: boolean('With links', true, generalKnobTab) ? [
      {
        url: randomUrl(),
        text: 'Service link 1',
        new_window: false,
        is_external: false,
      },
      {
        url: randomUrl(),
        text: 'Service link 2',
        new_window: false,
        is_external: false,
      },
      {
        url: randomUrl(),
        text: 'Service link 3',
        new_window: false,
        is_external: false,
      },
      {
        url: randomUrl(),
        text: 'Service link 4',
        new_window: false,
        is_external: false,
      },
    ] : null,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const html = CivicServiceCard({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });

  return `<div class="story-wrapper-size--small">${html}</div>`;
};
