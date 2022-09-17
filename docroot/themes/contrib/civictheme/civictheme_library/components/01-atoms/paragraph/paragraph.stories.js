// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeParagraph from './paragraph.twig';

export default {
  title: 'Atoms/Paragraph',
  parameters: {
    layout: 'centered',
    knobs: {
      escapeHTML: false,
    },
  },
};

export const Paragraph = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    content: text('Content', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur harum magnam modi obcaecati vitae voluptatibus! Accusamus atque deleniti, distinctio esse facere, nam odio officiis omnis porro quibusdam quis repudiandae veritatis.', generalKnobTab),
    size: radios(
      'Size',
      {
        'Extra Large': 'extra-large',
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
      },
      'regular',
      generalKnobTab,
    ),
    allow_html: boolean('Allow HTML in text', false, generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeParagraph({
    ...generalKnobs,
  });
};
