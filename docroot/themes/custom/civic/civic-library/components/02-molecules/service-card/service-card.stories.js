import { object, radios, text } from '@storybook/addon-knobs'

import CivicServiceCard from './service-card.twig'
import './service-card.scss'

export default {
  title: 'Molecule/Service Card'
}

export const serviceCard = () => {
  const serviceCardKnobTab = 'Service Card';

  // Current component parameters.
  const serviceCardKnobs = {
    theme: radios('Theme', {
      'Light': 'light',
      'Dark': 'dark'
    }, 'light', serviceCardKnobTab),
    title: text('Title', 'Services category title across one or two lines', serviceCardKnobTab),
    links: object('Links', [
      {
        url: 'http://google.com',
        text: 'service link 1',
        new_window: false,
        is_external: false
      },
      {
        url: 'http://google.com',
        text: 'service link 2',
        new_window: false,
        is_external: false
      },
      {
        url: 'http://google.com',
        text: 'service link 3',
        new_window: false,
        is_external: false
      },
      {
        url: 'http://google.com',
        text: 'service link 4',
        new_window: false,
        is_external: false
      }
    ], serviceCardKnobTab),
    modifier_class: text('Additional class', '', serviceCardKnobTab),
  };

  return CivicServiceCard({...serviceCardKnobs});
}
