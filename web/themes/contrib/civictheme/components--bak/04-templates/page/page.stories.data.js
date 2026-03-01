// phpcs:ignoreFile
import Banner from '../../03-organisms/banner/banner.twig';
import BannerData from '../../03-organisms/banner/banner.stories.data';
import HeaderData from '../../03-organisms/header/header.stories.data';
import FooterData from '../../03-organisms/footer/footer.stories.data';
import SideNavigation from '../../03-organisms/side-navigation/side-navigation.twig';
import SideNavigationData from '../../03-organisms/side-navigation/side-navigation.stories.data';
import Button from '../../01-atoms/button/button.twig';
import Paragraph from '../../01-atoms/paragraph/paragraph.twig';
import BasicContent from '../../02-molecules/basic-content/basic-content.twig';
import BasicContentData from '../../02-molecules/basic-content/basic-content.stories.data';
import Accordion from '../../02-molecules/accordion/accordion.twig';
import List from '../../03-organisms/list/list.twig';
import ListData from '../../03-organisms/list/list.stories.data';
import Grid from '../../00-base/grid/grid.twig';

export default {
  args: (theme = 'light') => {
    const headerData = HeaderData.args(theme);
    const footerData = FooterData.args(theme);

    return {
      theme,
      vertical_spacing: 'both',
      header_theme: theme,
      header_top_1: headerData.content_top1,
      header_top_2: headerData.content_top2,
      header_top_3: headerData.content_top3,
      header_middle_1: headerData.content_middle1,
      header_middle_2: headerData.content_middle2,
      header_middle_3: headerData.content_middle3,
      header_bottom_1: headerData.content_bottom1,
      banner: Banner(BannerData.args(theme)),
      highlighted: '',
      content_top: '',
      hide_sidebar_left: false,
      hide_sidebar_right: false,
      sidebar_top_left: SideNavigation(SideNavigationData.args(theme)),
      sidebar_top_left_attributes: null,
      sidebar_top_right: '',
      sidebar_top_right_attributes: null,
      content: BasicContent(BasicContentData.args(theme)),
      content_attributes: null,
      sidebar_bottom_left: Paragraph({
        theme,
        content: `<p>Register for events!</p><p>${Button({
          theme,
          text: 'Register',
          type: 'primary',
          kind: 'link',
        })}</p>`,
      }),
      sidebar_bottom_left_attributes: null,
      sidebar: '',
      sidebar_attributes: null,
      sidebar_bottom_right: '',
      sidebar_bottom_right_attributes: null,
      content_contained: false,
      content_bottom: '',
      footer_theme: theme,
      footer_logo: '',
      footer_background_image: '',
      footer_top_1: footerData.content_top1,
      footer_top_2: footerData.content_top2,
      footer_middle_1: footerData.content_middle1,
      footer_middle_2: footerData.content_middle2,
      footer_middle_3: footerData.content_middle3,
      footer_middle_4: footerData.content_middle4,
      footer_bottom_1: footerData.content_bottom1,
      footer_bottom_2: footerData.content_bottom2,
      attributes: null,
      modifier_class: '',
    };
  },
};

export const PageFullWidthData = {
  args: (theme = 'light') => {
    const headerData = HeaderData.args(theme);
    const footerData = FooterData.args(theme);
    const accordionData = {
      theme,
      with_background: true,
      vertical_spacing: 'both',
      panels: [
        {
          title: 'Accordion title 1',
          content: 'Accordion content 1 <a href="https://example.com">Example link</a>',
          expanded: false,
        },
        {
          title: 'Accordion title 2',
          content: 'Accordion content 2 <a href="https://example.com">Example link</a>',
          expanded: false,
        },
        {
          title: 'Accordion title 3',
          content: 'Accordion content 3 <a href="https://example.com">Example link</a>',
          expanded: false,
        },
      ],
    };
    const listData = {
      theme,
      rows: Grid({
        theme,
        items: ListData.items(theme, {
          component: 'promo',
          items: [
            { title: 'Example 1', date: null, subtitle: null, tags: null },
            { title: 'Example 2 lorem ipsum dolor sit amet', date: null, subtitle: null, tags: null },
            { title: 'Example 3', date: null, subtitle: null, tags: null },
          ],
        }),
        template_column_count: 3,
        fill_width: false,
        with_background: false,
        row_class: 'row--equal-heights-content row--vertically-spaced',
      }),
      vertical_spacing: 'none',
      with_background: false,
      attributes: null,
      modifier_class: '',
    };

    return {
      theme,
      vertical_spacing: 'none',
      header_theme: theme,
      header_top_1: headerData.content_top1,
      header_top_2: headerData.content_top2,
      header_top_3: headerData.content_top3,
      header_middle_1: headerData.content_middle1,
      header_middle_2: headerData.content_middle2,
      header_middle_3: headerData.content_middle3,
      header_bottom_1: headerData.content_bottom1,
      banner: Banner({ ...BannerData.args(theme), content_below: '', is_decorative: false, featured_image: null }),
      highlighted: '',
      content_top: '',
      content_attributes: null,
      content_contained: false,
      content_bottom: '',
      footer_theme: theme,
      footer_logo: '',
      footer_background_image: '',
      footer_top_1: footerData.content_top1,
      footer_top_2: footerData.content_top2,
      footer_middle_1: footerData.content_middle1,
      footer_middle_2: footerData.content_middle2,
      footer_middle_3: footerData.content_middle3,
      footer_middle_4: footerData.content_middle4,
      footer_bottom_1: footerData.content_bottom1,
      footer_bottom_2: footerData.content_bottom2,
      attributes: null,
      modifier_class: '',
      hide_sidebar_left: true,
      hide_sidebar_right: true,
      content: [
        BasicContent({ theme, content: '<p>Text without a class sed aute in sed consequat veniam excepteur minim mollit.</p>', vertical_spacing: 'both' }),
        Accordion(accordionData),
        Accordion({ ...accordionData, with_background: false }),
        List({ ...listData, with_background: true, vertical_spacing: 'both' }),
        List({ ...listData, with_background: false, vertical_spacing: 'both' }),
      ].join(''),
    };
  },
};
