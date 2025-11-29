// phpcs:ignoreFile
import CivicThemeHeading from './heading.twig';
import { knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Heading',
  parameters: {
    layout: 'centered',
  },
};

export const Heading = (parentKnobs = {}) => {
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
    level: knobRadios('Level', {
      1: '1',
      2: '2',
      3: '3',
      4: '4',
      5: '5',
      6: '6',
    }, '1', parentKnobs.level, parentKnobs.knobTab),
    content: knobText('Content', 'Heading content', parentKnobs.content, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeHeading(knobs) : knobs;
};
