import {
  radios, text, boolean,
} from '@storybook/addon-knobs';

import CivicFlyout from './flyout.twig';
import './flyout.scss';
import './flyout';

export default {
  title: 'Molecule/Flyout',
  parameters: {
    layout: 'centered',
  },
};

export const Flyout = () => {
  const content = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur harum magnam modi obcaecati vitae voluptatibus! Accusamus atque deleniti, distinctio esse facere, nam odio officiis omnis porro quibusdam quis repudiandae veritatis.';

  const html = CivicFlyout({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    flyout_from: radios(
      'Flyout from',
      {
        Right: 'from-right',
        Left: 'from-left',
        Top: 'from-top',
        Bottom: 'from-bottom',
      },
      'from-right',
    ),
    trigger: text('Trigger', 'Open Flyout'),
    content: text('Content', content),
    modifier_class: text('Additional class', ''),
    expanded: boolean('Expanded', false),
  });

  return `<div class="story-wrapper-size--medium">${html}</div>`;
};
