import CivicThemeNavigationCard from './navigation-card.twig';
import { demoImage, knobBoolean, knobRadios, knobSelect, knobNumber, knobText, randomUrl, shouldRender, slotKnobs, randomTags } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/List/Navigation Card',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
    storyLayoutIsContainer: true,
    storyLayoutIsResizable: true,
    docs: 'This component adapts to the width of its container instead of the viewport. Use the toolbar button to enable container resizing.',
    docsPlacement: 'after',
    docsSize: 'small',
  },
};

export const NavigationCard = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'Navigation card heading which runs across two or three lines', parentKnobs.title, parentKnobs.knobTab),
    summary: knobText('Summary', 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.', parentKnobs.summary, parentKnobs.knobTab),
    link: knobBoolean('With link', true, parentKnobs.with_link, parentKnobs.knobTab) ? {
      url: knobText('Link URL', randomUrl(), parentKnobs.link_url, parentKnobs.knobTab),
      is_external: knobBoolean('Link is external', false, parentKnobs.link_is_external, parentKnobs.knobTab),
      is_new_window: knobBoolean('Open in a new window', false, parentKnobs.link_is_new_window, parentKnobs.knobTab),
    } : null,
    image: knobBoolean('With image', true, parentKnobs.with_image, parentKnobs.knobTab) ? {
      url: demoImage(),
      alt: 'Image alt text',
    } : null,
    image_as_icon: knobBoolean('Image as icon', false, parentKnobs.image_as_icon, parentKnobs.knobTab),
    // This is a new property added for this extended component.
    tags: randomTags(knobNumber(
      'Number of tags',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.tags,
      parentKnobs.knobTab,
    ), true),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  const iconKnobTab = 'Icon';
  const withIcon = knobBoolean('With icon', false, parentKnobs.with_icon, parentKnobs.knobTab);
  const iconKnobs = {
    icon: withIcon ? knobSelect('Icon', Object.values(ICONS), Object.values(ICONS)[0], parentKnobs.icon, iconKnobTab) : null,
  };

  const combinedKnobs = { ...knobs, ...iconKnobs };

  return shouldRender(parentKnobs) ? CivicThemeNavigationCard({
    ...combinedKnobs,
    ...slotKnobs([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  }) : combinedKnobs;
};
