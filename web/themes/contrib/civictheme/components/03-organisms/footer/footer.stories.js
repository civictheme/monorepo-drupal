// phpcs:ignoreFile
import { knobBoolean, knobRadios, knobSelect, knobText, KnobValues, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import CivicThemeFooter from './footer.twig';
import '../../00-base/responsive/responsive';
import '../../00-base/collapsible/collapsible';
import { Logo } from '../../02-molecules/logo/logo.stories';
import { Navigation } from '../navigation/navigation.stories';
import CivicThemeIcon from '../../00-base/icon/icon.twig';
import { SocialLinks } from '../../02-molecules/social-links/social-links.stories';

export default {
  title: 'Organisms/Footer',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Footer = (parentKnobs = {}) => {
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
    show_logo: knobBoolean('Show logo', true, parentKnobs.show_logo, parentKnobs.knobTab),
    show_social_links: knobBoolean('Show social links', true, parentKnobs.show_social_links, parentKnobs.knobTab),
    show_middle_links: knobBoolean('Show middle links', true, parentKnobs.show_middle_links, parentKnobs.knobTab),
    show_acknowledgement: knobBoolean('Show acknowledgement', true, parentKnobs.show_acknowledgement, parentKnobs.knobTab),
    show_copyright: knobBoolean('Show copyright', true, parentKnobs.show_copyright, parentKnobs.knobTab),
    show_background_image: knobBoolean('Show background image', false, parentKnobs.show_background_image, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  const props = {
    theme,
    content_top1: knobs.show_logo ? Logo(new KnobValues({ theme })) : '',
    content_top2: knobs.show_social_links ? SocialLinks(new KnobValues({
      theme,
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
          title: 'Icon with inline SVG',
          // icon_html should take precedence.
          icon_html: CivicThemeIcon({
            symbol: 'linkedin',
            size: 'small',
          }),
          icon: 'linkedin',
          url: 'https://www.linkedin.com',
        },
      ],
    })) : '',
    content_middle1: knobs.show_middle_links ? Navigation(new KnobValues({
      title: 'Services',
      theme,
    })) : '',
    content_middle2: knobs.show_middle_links ? Navigation(new KnobValues({
      title: 'About us',
      theme,
    })) : '',
    content_middle3: knobs.show_middle_links ? Navigation(new KnobValues({
      title: 'Help',
      theme,
    })) : '',
    content_middle4: knobs.show_middle_links ? Navigation(new KnobValues({
      title: 'Resources',
      theme,
    })) : '',
    content_bottom1: knobs.show_acknowledgement ? '<div class="ct-footer__acknowledgement ct-text-regular">We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present.</div>' : '',
    content_bottom2: knobs.show_copyright ? '<div class="copyright ct-text-regular">©Commonwealth of Australia</div>' : '',
  };

  if (knobs.show_background_image) {
    props.background_image = BACKGROUNDS[knobSelect('Background', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0], parentKnobs.background_image, parentKnobs.knobTab)];
  }

  return shouldRender(parentKnobs) ? CivicThemeFooter({
    ...props,
    ...slotKnobs([
      'content_top1',
      'content_top2',
      'content_middle1',
      'content_middle2',
      'content_middle3',
      'content_middle4',
      'content_bottom1',
      'content_bottom2',
    ]),
  }) : props;
};
