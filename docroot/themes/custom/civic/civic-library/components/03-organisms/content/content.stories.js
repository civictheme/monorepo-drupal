import { boolean, radios } from '@storybook/addon-knobs';
import CivicContent from './content.twig';
import { CardContainer } from '../card-container/card-container.stories';
import { Heading } from '../../01-atoms/heading/heading.stories';

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
    content_left: boolean('Sidebar', false, generalKnobTab) ? Heading : false,
  };

  return CivicContent({
    ...generalKnobs,
    content: CardContainer,
  });
};
