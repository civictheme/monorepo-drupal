import { text, boolean, radios, select, object } from '@storybook/addon-knobs'

import CivicServiceCard from './service-card.twig'
import './service-card.scss'

export default {
  title: 'Molecule/Cards'
}


export const serviceCard = () => {

  // Knob tab names.
  const serviceCard = 'service card';

  // Current component parameters.  
  const serviceCardParams = {
    theme: radios('Theme', {
      'Light': 'light',
      'Dark': 'dark'
    }, 'light', serviceCard),
    title: text('Title', 'Services category title across one or two lines', serviceCard),
    links: object('Links', [
      { url: "http://google.com", text: "service link 1", new_window: false, is_external: false },
      { url: "http://google.com", text: "service link 2", new_window: false, is_external: false },
      { url: "http://google.com", text: "service link 3", new_window: false, is_external: false },
      { url: "http://google.com", text: "service link 4", new_window: false, is_external: false }
    ], serviceCard),
    modifier_class: text('Additional class', '', serviceCard),
  };

  return CivicServiceCard({ ...serviceCardParams });
}
