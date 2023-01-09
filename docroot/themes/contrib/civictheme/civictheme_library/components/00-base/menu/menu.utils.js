// phpcs:ignoreFile
/**
 * @file
 * Menu component utilities.
 */

/* eslint-disable camelcase */

import { boolean, number } from '@storybook/addon-knobs';
import {
  randomBool,
  randomUrl,
} from '../base.utils';

export function generateMenuLinks(count, levels, isActiveTrail, title, titleCb, currentLevel, parents) {
  const links = [];

  currentLevel = currentLevel || 1;
  parents = parents || [];
  title = title || 'Item';

  titleCb = typeof titleCb === 'function' ? titleCb : function (itemTitle, itemIndex, itemCurrentLevel, itemIsActiveTrail, itemParents) {
    return `${itemTitle} ${itemParents.join('')}${itemIndex}`;
  };

  const active_trail_idx = isActiveTrail ? Math.floor(Math.random() * count) : null;

  for (let i = 1; i <= count; i++) {
    const link = {
      title: titleCb(title, i, currentLevel, isActiveTrail, parents),
      url: randomUrl(),
    };

    if (active_trail_idx === i) {
      link.in_active_trail = true;
    }

    if (currentLevel < levels) {
      link.below = generateMenuLinks(count, levels, active_trail_idx === i, title, titleCb, currentLevel + 1, parents.concat([i]));
      link.is_expanded = randomBool(0.5);
    }

    links.push(link);
  }

  return links;
}

export default function getMenuLinks(knobTab, titleCb) {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const links_per_level = number(
    'Links per level',
    3,
    {
      range: true,
      min: 0,
      max: 5,
      step: 1,
    },
    generalKnobTab,
  );
  const levels = number(
    'Number of levels',
    3,
    {
      range: true,
      min: 1,
      max: 5,
      step: 1,
    },
    generalKnobTab,
  );
  const activeTrail = boolean('Show active trail (random)', false, generalKnobTab);

  return generateMenuLinks(links_per_level, levels, activeTrail, null, titleCb);
}
