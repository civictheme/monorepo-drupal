// phpcs:ignoreFile
import Logo from '../../02-molecules/logo/logo.twig';
import LogoData from '../../02-molecules/logo/logo.stories.data';
import Navigation from '../navigation/navigation.twig';
import NavigationData from '../navigation/navigation.stories.data';
import SocialLinks from '../../02-molecules/social-links/social-links.twig';

export default {
  args: (theme = 'light') => ({
    theme,
    content_top1: Logo(LogoData.args(theme)),
    content_top2: SocialLinks({
      theme,
      with_border: true,
      items: [
        {
          title: 'Facebook',
          icon: 'facebook',
          url: 'https://www.facebook.com',
        },
        {
          title: 'X',
          icon: 'x',
          url: 'https://www.twitter.com',
        },
        {
          title: 'LinkedIn',
          icon: 'linkedin',
          url: 'https://www.linkedin.com',
        },
      ],
    }).trim(),
    content_middle1: Navigation({ ...NavigationData.args(theme), title: 'Services' }),
    content_middle2: Navigation({ ...NavigationData.args(theme), title: 'About us' }),
    content_middle3: Navigation({ ...NavigationData.args(theme), title: 'Help' }),
    content_middle4: Navigation({ ...NavigationData.args(theme), title: 'Resources' }),
    content_middle5: Navigation({ ...NavigationData.args(theme), title: null }),
    content_bottom1: '<div class="ct-footer__acknowledgement ct-text-regular">We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present.</div>',
    content_bottom2: '<div class="copyright ct-text-regular">©Commonwealth of Australia</div>',
    background_image: '',
    modifier_class: '',
  }),
};
