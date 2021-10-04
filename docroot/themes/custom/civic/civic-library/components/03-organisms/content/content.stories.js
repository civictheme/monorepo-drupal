import {
  radios,
} from '@storybook/addon-knobs';
import CivicContent from './content.twig';
import { CardContainer } from '../card-container/card-container.stories';

import './content.scss';

export default {
  title: 'Organisms/Content',
};

export const Content = () => {
  const generalKnobTab = 'General';

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
  };

  return CivicContent({
    ...generalKnobs,
    content: CardContainer,
  });
};
