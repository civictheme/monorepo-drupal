// phpcs:ignoreFile
import { demoImage, knobBoolean, knobRadios, knobSelect, knobText, KnobValue, KnobValues, objectFromArray, randomSentence, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import { Breadcrumb } from '../../02-molecules/breadcrumb/breadcrumb.stories';
import CivicThemeBanner from './banner.twig';
import CivicThemeParagraph from '../../01-atoms/paragraph/paragraph.twig';
import CivicThemeButton from '../../01-atoms/button/button.twig';
import CivicThemeNavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import CivicThemeGrid from '../../00-base/grid/grid.twig';

export default {
  title: 'Organisms/Banner',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Banner = (parentKnobs = {}) => {
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

  const title = knobText('Title', 'Providing visually engaging digital experiences', parentKnobs.title, parentKnobs.knobTab);

  const showBgImage = knobBoolean('Show background image', true, parentKnobs.show_background_image, parentKnobs.knobTab);

  const knobs = {
    theme,
    title,
    background_image: showBgImage ? knobSelect('Background image', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0], parentKnobs.background_image, 'Background') : '',
    background_image_blend_mode: showBgImage ? knobSelect(
      'Blend mode',
      objectFromArray(SCSS_VARIABLES['ct-background-blend-modes']),
      'multiply',
      parentKnobs.background_image_blend_mode,
      'Background',
    ) : null,
    show_featured_image: knobBoolean('Show featured image', true, parentKnobs.show_featured_image, parentKnobs.knobTab),
    is_decorative: knobBoolean('Decorative', true, parentKnobs.is_decorative, parentKnobs.knobTab),
    show_site_section: knobBoolean('Show site section', true, parentKnobs.show_site_section, parentKnobs.knobTab),
    show_breadcrumb: knobBoolean('Show breadcrumb', true, parentKnobs.show_breadcrumb, parentKnobs.knobTab),
    show_content_text: knobBoolean('Show content text', true, parentKnobs.show_content_text, parentKnobs.knobTab),
    show_content_below: knobBoolean('Show content below', false, parentKnobs.show_content_below, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  const props = {
    title: knobs.title,
    theme,
    is_decorative: knobs.is_decorative,
    modifier_class: knobs.modifier_class,
  };

  if (knobs.background_image) {
    props.background_image = {
      url: BACKGROUNDS[knobs.background_image],
      alt: knobText('Alt text', 'Background image alt text', parentKnobs.background_image_alt_text, 'Background'),
    };
    props.background_image_blend_mode = knobs.background_image_blend_mode;
  }

  if (knobs.show_featured_image) {
    props.featured_image = {
      url: demoImage(0),
      alt: 'Featured image alt text',
    };
  }

  if (knobs.show_site_section) {
    props.site_section = 'Site section name';
  }

  if (knobs.show_breadcrumb) {
    props.breadcrumb = Breadcrumb(new KnobValues({
      theme,
      count_of_links: new KnobValue(),
      knobTab: 'Breadcrumb',
    }, false, parentKnobs.breadcrumb));
  }

  if (knobs.show_content_text) {
    const button = CivicThemeButton({
      theme,
      text: 'Learn about our mission',
      type: 'primary',
      kind: 'link',
    });

    let content = '';

    content += CivicThemeParagraph({
      theme,
      allow_html: true,
      content: `<p>Government grade set of high quality design themes that are accessible, inclusive and provide a consistent digital experience for your citizen. </p><p>${button}</p>`,
    });

    props.content = content;
  }

  if (knobs.show_content_below) {
    let contentBelow = '';

    const cards = [];
    for (let i = 0; i < 4; i++) {
      cards.push(CivicThemeNavigationCard({
        theme,
        title: 'Register for a workshop',
        summary: randomSentence(7 + i, `Register for a workshop${i}`),
        icon: 'mortarboard',
      }));
    }

    contentBelow = CivicThemeGrid({
      theme,
      template_column_count: 4,
      items: cards,
      row_class: 'row--equal-heights-content row--vertically-spaced',
    });

    props.content_below = contentBelow;
  }

  return shouldRender(parentKnobs) ? CivicThemeBanner({
    ...props,
    ...slotKnobs([
      'content_top1',
      'content_top2',
      'content_top3',
      'content_middle',
      'content',
      'content_bottom',
    ]),
  }) : props;
};
