// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
// @todo This is a placeholder component. It is not finished!
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeSideNavigation from './side-navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Side Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const SideNavigation = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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
    title: text('Title', 'Side Navigation title', generalKnobTab),
    items: getMenuLinks('Links'),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeSideNavigation({
    ...generalKnobs,
  });
};
