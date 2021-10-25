import { boolean, radios } from '@storybook/addon-knobs';
import CivicContent from './content.twig';
import { BasicContent } from '../../02-molecules/basic-content/basic-content.stories';
// @todo Replace with 'side-navigation' component once it is ready.
import { Navigation } from '../navigation/navigation.stories';

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
    content_left: boolean('Sidebar', false, generalKnobTab) ? Navigation : false,
  };

  return CivicContent({
    ...generalKnobs,
    content: BasicContent,
  });
};
