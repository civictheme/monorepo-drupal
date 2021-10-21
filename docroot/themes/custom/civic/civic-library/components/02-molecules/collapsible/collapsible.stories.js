import {
  radios, text, boolean,
} from '@storybook/addon-knobs';

import CivicCollapsible from './collapsible.twig';
import './collapsible';

export default {
  title: 'Molecule/Collapsible',
  parameters: {
    layout: 'centered',
  },
};

export const Collapsible = () => {
  const content = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur harum magnam modi obcaecati vitae voluptatibus! Accusamus atque deleniti, distinctio esse facere, nam odio officiis omnis porro quibusdam quis repudiandae veritatis.';

  const html = CivicCollapsible({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    title: text('Title', 'Collapsible block title'),
    content: text('Content', content),
    modifier_class: text('Additional class', ''),
    expanded: boolean('Expanded', true),
  });

  return `<div class="story-wrapper-size--medium">${html}</div>`;
};
