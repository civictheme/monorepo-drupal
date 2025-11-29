// phpcs:ignoreFile
import { knobBoolean, knobRadios, KnobValues, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import { Logo } from '../../02-molecules/logo/logo.stories';
import CivicThemeHeader from './header.twig';
import { Paragraph } from '../../01-atoms/paragraph/paragraph.stories';
import { Navigation } from '../navigation/navigation.stories';
import { Search } from '../../02-molecules/search/search.stories';
import { MobileNavigation } from '../mobile-navigation/mobile-navigation.stories';

export default {
  title: 'Organisms/Header',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Header = (parentKnobs = {}) => {
  const theme = knobRadios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    parentKnobs.theme,
    parentKnobs.knobTab,
  );

  const knobs = {
    theme,
    show_slogan: knobBoolean('Show slogan', true, parentKnobs.show_slogan, parentKnobs.knobTab),
    show_secondary_navigation: knobBoolean('Show secondary navigation', true, parentKnobs.show_secondary_navigation, parentKnobs.knobTab),
    show_logo: knobBoolean('Show logo', true, parentKnobs.show_logo, parentKnobs.knobTab),
    show_primary_navigation: knobBoolean('Show primary navigation', true, parentKnobs.show_primary_navigation, parentKnobs.knobTab),
    show_search: knobBoolean('With Search', true, parentKnobs.show_search, parentKnobs.knobTab),
  };

  let contentTop3 = '';
  if (knobs.show_secondary_navigation) {
    contentTop3 += Navigation(new KnobValues({
      theme,
      title: null,
      type: 'dropdown',
      modifier_class: 'ct-flex-justify-content-end',
    }));
  }

  let contentMiddle3Content = '';
  if (knobs.show_primary_navigation) {
    contentMiddle3Content += Navigation(new KnobValues({
      theme,
      title: null,
      type: 'drawer',
      modifier_class: 'ct-flex-justify-content-end',
    }));

    contentMiddle3Content += Search(new KnobValues({
      modifier_class: 'ct-flex-justify-content-end',
      theme,
    }));

    contentMiddle3Content += MobileNavigation(new KnobValues({ theme }));
  }

  const props = {
    theme,
    content_top2: knobs.show_slogan ? Paragraph(new KnobValues({ content: 'A design system by Salsa Digital', theme })) : '',
    content_top3: contentTop3,
    content_middle2: knobs.show_logo ? Logo(new KnobValues({
      theme,
      knobTab: 'Logo',
    })) : '',
    content_middle3: contentMiddle3Content,
  };

  return shouldRender(parentKnobs) ? CivicThemeHeader({
    ...props,
    ...slotKnobs([
      'content_top1',
      'content_top2',
      'content_top3',
      'content_middle1',
      'content_middle2',
      'content_middle3',
      'content_bottom1',
    ]),
  }) : props;
};
