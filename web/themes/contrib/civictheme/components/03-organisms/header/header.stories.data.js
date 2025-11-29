import Logo from '../../02-molecules/logo/logo.twig';
import LogoData from '../../02-molecules/logo/logo.stories.data';
import Paragraph from '../../01-atoms/paragraph/paragraph.twig';
import Navigation from '../navigation/navigation.twig';
import NavigationData from '../navigation/navigation.stories.data';
import Search from '../../02-molecules/search/search.twig';
import { MobileNavigation as MobileNavigationStory } from '../mobile-navigation/mobile-navigation.stories';
import MobileNavigationPanel from '../mobile-navigation/mobile-navigation.twig';
import MobileNavigationTrigger from '../mobile-navigation/mobile-navigation-trigger.twig';

export default {
  args: (theme = 'light', options = {}) => ({
    theme,
    content_top1: '',
    content_top2: Paragraph({
      theme,
      content: 'A design system by Salsa Digital',
    }).trim(),
    content_top3: Navigation({
      ...NavigationData.args(theme),
      name: 'secondary',
      title: null,
      type: 'dropdown',
      modifier_class: 'ct-flex-justify-content-end',
    }),
    content_middle1: '',
    content_middle2: Logo(LogoData.args(theme)),
    content_middle3: [
      Navigation({
        ...NavigationData.args(theme),
        name: 'primary',
        title: null,
        type: 'drawer',
        modifier_class: 'ct-flex-justify-content-end',
      }).trim(),
      Search({
        theme,
        text: 'Search',
        url: '/search',
        modifier_class: `ct-flex-justify-content-end ${options.mobileSearchLink ? 'hide-xxs show-m-flex' : ''}`,
      }).trim(),
      MobileNavigationTrigger({
        theme,
        icon: 'bars',
        text: 'Menu',
      }).trim(),
      MobileNavigationPanel({
        ...MobileNavigationStory.args,
        top_menu: options.mobileSearchLink
          ? MobileNavigationStory.args.top_menu.concat({
            title: 'Search',
            url: '/search',
            icon: 'magnifier',
            in_active_trail: false,
            is_expanded: false,
          })
          : MobileNavigationStory.args.top_menu,
      }).trim(),
    ].join(''),
    content_bottom1: '',
    modifier_class: '',
  }),
};
