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
      sidebar_top_left_attributes: '',
      sidebar_top_right: '',
      sidebar_top_right_attributes: '',
      content: BasicContent(BasicContentData.args(theme)),
      content_attributes: '',
      sidebar_bottom_left: Paragraph({
        theme,
        allow_html: true,
        content: `<p>Register for events!</p><p>${Button({
          theme,
          text: 'Register',
          type: 'primary',
          kind: 'link',
        })}</p>`,
      }),
      sidebar_bottom_left_attributes: '',
      sidebar: '',
      sidebar_attributes: '',
      sidebar_bottom_right: '',
      sidebar_bottom_right_attributes: '',
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
      attributes: '',
      modifier_class: '',
    };
  },
};
