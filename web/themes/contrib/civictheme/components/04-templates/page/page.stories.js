// phpcs:ignoreFile
import { knobBoolean, knobRadios, KnobValues, slotKnobs } from '../../00-base/storybook/storybook.utils';
import CivicThemePage from './page.twig';
import { Banner } from '../../03-organisms/banner/banner.stories';
import { Footer } from '../../03-organisms/footer/footer.stories';
import { Header } from '../../03-organisms/header/header.stories';
import { BasicContent } from '../../02-molecules/basic-content/basic-content.stories';
import { SideNavigation } from '../../03-organisms/side-navigation/side-navigation.stories';
import CivicThemeButton from '../../01-atoms/button/button.twig';
import CivicThemeParagraph from '../../01-atoms/paragraph/paragraph.twig';

export default {
  title: 'Templates/Page',
  parameters: {
    layout: 'fullscreen',
  },
};

export const ContentPage = (parentKnobs = {}) => {
  const theme = knobRadios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'dark',
    parentKnobs.theme,
    parentKnobs.knobTab,
  );

  const knobs = {
    show_sidebar_top_left: knobBoolean('Show top left sidebar', true, parentKnobs.show_sidebar_top_left, parentKnobs.knobTab),
    show_sidebar_bottom_left: knobBoolean('Show bottom left sidebar', true, parentKnobs.show_sidebar_bottom_left, parentKnobs.knobTab),
    show_sidebar_top_right: knobBoolean('Show top right sidebar', false, parentKnobs.show_right_top_rail, parentKnobs.knobTab),
    show_sidebar_bottom_right: knobBoolean('Show bottom right sidebar', false, parentKnobs.show_right_bottom_rail, parentKnobs.knobTab),
  };

  const props = {};

  props.theme = theme;

  props.banner = Banner(new KnobValues({
    theme,
    breadcrumb: { count_of_links: 3 },
  }));

  const headerValues = Header(new KnobValues({
    knobTab: 'Header',
    theme,
  }, false));
  props.header_theme = theme;
  props.header_top_1 = headerValues.content_top1;
  props.header_top_2 = headerValues.content_top2;
  props.header_top_3 = headerValues.content_top3;
  props.header_middle_1 = headerValues.content_middle1;
  props.header_middle_2 = headerValues.content_middle2;
  props.header_middle_3 = headerValues.content_middle3;
  props.header_bottom_1 = headerValues.content_bottom1;

  props.content = BasicContent(new KnobValues({
    theme,
  }));
  props.vertical_spacing = 'both';

  if (knobs.show_sidebar_top_left) {
    props.sidebar_top_left = SideNavigation(new KnobValues({
      theme,
    }));
  }

  if (knobs.show_sidebar_bottom_left) {
    const button = CivicThemeButton({
      theme,
      text: 'Register',
      type: 'primary',
      kind: 'link',
    });

    let content = '';

    content += CivicThemeParagraph({
      theme,
      allow_html: true,
      content: `<p>Register for events!</p><p>${button}</p>`,
    });

    props.sidebar_bottom_left = content;
  }

  if (knobs.show_sidebar_top_right) {
    props.sidebar_top_right = SideNavigation(new KnobValues({
      theme,
    }));
  }

  if (knobs.show_sidebar_bottom_right) {
    const button = CivicThemeButton({
      theme,
      text: 'Register',
      type: 'primary',
      kind: 'link',
    });

    let content = '';

    content += CivicThemeParagraph({
      theme,
      allow_html: true,
      content: `<p>Register for events!</p><p>${button}</p>`,
    });

    props.sidebar_bottom_right = content;
  }

  const footerValues = Footer(new KnobValues({
    theme,
  }, false));

  props.footer_theme = theme;
  props.footer_logo = footerValues.logo;
  props.footer_background_image = footerValues.background_image;
  props.footer_top_1 = footerValues.content_top1;
  props.footer_top_2 = footerValues.content_top2;
  props.footer_middle_1 = footerValues.content_middle1;
  props.footer_middle_2 = footerValues.content_middle2;
  props.footer_middle_3 = footerValues.content_middle3;
  props.footer_middle_4 = footerValues.content_middle4;
  props.footer_bottom_1 = footerValues.content_bottom1;
  props.footer_bottom_2 = footerValues.content_bottom2;

  return CivicThemePage({
    ...props,
    ...slotKnobs([
      'header_top_1',
      'header_top_2',
      'header_top_3',
      'header_middle_1',
      'header_middle_2',
      'header_middle_3',
      'header_bottom_1',
      'banner',
      'highlighted',
      'content_top',
      'sidebar_top_left',
      'sidebar_top_right',
      'content',
      'sidebar_bottom_left',
      'sidebar_bottom_right',
      'is_contained',
      'content_bottom',
      'footer_top_1',
      'footer_top_2',
      'footer_middle_1',
      'footer_middle_2',
      'footer_middle_3',
      'footer_middle_4',
      'footer_bottom_1',
      'footer_bottom_2',
    ]),
  });
};
