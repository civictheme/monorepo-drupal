// phpcs:ignoreFile
import { knobBoolean, knobRadios, knobSelect, knobText, randomUrl, shouldRender } from '../../00-base/storybook/storybook.utils';
import CivicThemeTag from './tag.twig';

export default {
  title: 'Atoms/Tag',
  parameters: {
    layout: 'centered',
  },
};

export const Tag = (parentKnobs = {}) => {
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
    type: knobRadios(
      'Type',
      {
        Primary: 'primary',
        Secondary: 'secondary',
        Tertiary: 'tertiary',
      },
      'primary',
      parentKnobs.type,
      parentKnobs.knobTab,
    ),
    content: knobText('Content', 'Tag content', parentKnobs.content, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  const iconKnobTab = 'Icon';
  const withIcon = knobBoolean('With icon', false, parentKnobs.with_icon, parentKnobs.knobTab);
  const iconKnobs = {
    icon: withIcon ? knobSelect('Icon', Object.values(ICONS), Object.values(ICONS)[0], parentKnobs.icon, iconKnobTab) : null,
    icon_placement: withIcon ? knobRadios(
      'Position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      parentKnobs.icon_placement,
      iconKnobTab,
    ) : null,
  };

  const withLink = knobBoolean('With link', false, parentKnobs.with_link, parentKnobs.knobTab);

  const linkKnobTab = 'Link';
  const linkKnobs = {
    url: withLink ? knobText('URL', randomUrl(), parentKnobs.link_url, linkKnobTab) : null,
    is_external: withLink ? knobBoolean('Is external', false, parentKnobs.link_is_external, linkKnobTab) : null,
    is_new_window: withLink ? knobBoolean('Open in a new window', false, parentKnobs.link_is_new_window, linkKnobTab) : null,
  };

  const combinedKnobs = { ...knobs, ...iconKnobs, ...linkKnobs };

  return shouldRender(parentKnobs) ? CivicThemeTag(combinedKnobs) : combinedKnobs;
};
