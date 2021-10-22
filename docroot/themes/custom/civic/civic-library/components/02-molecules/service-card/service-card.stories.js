import { object, radios, text } from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';

import CivicServiceCard from './service-card.twig';
import './service-card.scss';

export default {
  title: 'Molecule/Card',
  parameters: {
    layout: 'centered',
  },
};

export const ServiceCard = () => {
  const generalKnobTab = 'General';

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
    links: object('Links', [
      {
        url: 'http://example.com',
        text: 'Service link 1',
        new_window: false,
        is_external: false,
      },
      {
        url: 'http://example.com',
        text: 'Service link 2',
        new_window: false,
        is_external: false,
      },
      {
        url: 'http://example.com',
        text: 'Service link 3',
        new_window: false,
        is_external: false,
      },
      {
        url: 'http://example.com',
        text: 'Service link 4',
        new_window: false,
        is_external: false,
      },
    ], generalKnobTab),
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
