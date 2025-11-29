// phpcs:ignoreFile
import CivicThemeMessage from './message.twig';
import { knobRadios, knobText, randomText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Organisms/Message',
};

export const Message = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    type: knobRadios(
      'Type',
      {
        Information: 'information',
        Error: 'error',
        Warning: 'warning',
        Success: 'success',
      },
      'information',
      parentKnobs.type,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'The information on this page is currently being updated.', parentKnobs.title, parentKnobs.knobTab),
    description: knobText('Summary', `Message description ${randomText()}`, parentKnobs.description, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeMessage(knobs) : knobs;
};
