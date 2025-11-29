// phpcs:ignoreFile
import './flyout';
import FlyoutStoryTemplate from './flyout.stories.twig';
import { knobBoolean, knobNumber, knobRadios, shouldRender } from '../storybook/storybook.utils';

export default {
  title: 'Base/Utilities/Flyout',
  parameters: {
    layout: 'centered',
  },
};

export const Flyout = (parentKnobs = {}) => {
  const knobs = {
    direction: knobRadios(
      'Flyout from',
      {
        Top: 'top',
        Bottom: 'bottom',
        Left: 'left',
        Right: 'right',
      },
      'right',
      parentKnobs.direction,
      parentKnobs.knobTab,
    ),
    expanded: knobBoolean('Expanded', false, parentKnobs.expanded, parentKnobs.knobTab),
    duration: knobNumber('Duration (ms)', 500, undefined, parentKnobs.duration, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? FlyoutStoryTemplate(knobs) : knobs;
};
