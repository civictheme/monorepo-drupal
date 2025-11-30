import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

import Paragraph from '../../01-atoms/paragraph/paragraph.twig';
import Button from '../../01-atoms/button/button.twig';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import Grid from '../../00-base/grid/grid.twig';

export default {
  args: (theme = 'light') => ({
    theme,
    breadcrumb: {
      links: [
        {
          text: 'Link 1',
          url: 'https://example.com/breadcrumb-1',
        },
        {
          text: 'Link 2',
          url: 'https://example.com/breadcrumb-2',
        },
        {
          text: 'Link 3',
          url: 'https://example.com/breadcrumb-2',
        },
      ],
      active_is_link: false,
    },
    site_section: 'Site section name',
    title: 'Providing visually engaging digital experiences',
    is_decorative: true,
    featured_image: {
      url: './demo/images/demo2.jpg',
      alt: 'Featured image alt text',
    },
    background_image: {
      url: Constants.BACKGROUNDS[Object.keys(Constants.BACKGROUNDS)[0]],
      alt: 'Background image alt text',
    },
    background_image_blend_mode: 'multiply',
    content_top1: '',
    content_top2: '',
    content_top3: '',
    content_middle: '',
    content: Paragraph({
      theme,
      allow_html: true,
      content: `<p>Government grade set of high quality design themes that are accessible, inclusive and provide a consistent digital experience for your citizen. </p><p>${Button({
        theme,
        text: 'Learn about our mission',
        type: 'primary',
        kind: 'link',
      }).trim()}</p>`,
    }).trim(),
    content_bottom: '',
    content_below: Grid({
      theme,
      template_column_count: 4,
      items: [
        NavigationCard({
          theme,
          title: 'Register for a workshop',
          summary: 'Est sed aliqua ullamco occaecat velit nisi in dolor excepteur.',
          icon: 'mortarboard',
        }).trim(),
        NavigationCard({
          theme,
          title: 'Register for a workshop',
          summary: 'Ea dolor enim eiusmod consectetur proident adipisicing aute dolor ad est.',
          icon: 'mortarboard',
        }).trim(),
        NavigationCard({
          theme,
          title: 'Register for a workshop',
          summary: 'Anim occaecat ex nostrud non do sunt ut nostrud mollit aliqua.',
          icon: 'mortarboard',
        }).trim(),
        NavigationCard({
          theme,
          title: 'Register for a workshop',
          summary: 'Sunt duis dolore voluptate quis do in.',
          icon: 'mortarboard',
        }).trim(),
      ],
      row_class: 'row--equal-heights-content row--vertically-spaced',
    }).trim(),
    modifier_class: '',
    attributes: '',
  }),
};
