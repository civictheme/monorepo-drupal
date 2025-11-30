// phpcs:ignoreFile
/**
 * @file
 * Menu component utilities.
 */

/* eslint-disable camelcase */

import { knobBoolean, knobNumber, randomBool, randomUrl } from '../storybook/storybook.utils';

export function generateMenuLinks(count, levels, isActiveTrail, title = 'Item', titleCb = null, currentLevel = 1, parents = []) {
  const links = [];

  title = title || 'Item';
  currentLevel = currentLevel || 1;
  parents = parents || [];

  titleCb = typeof titleCb === 'function' ? titleCb : function (itemTitle, itemIndex, itemCurrentLevel, itemIsActiveTrail, itemParents) {
    return `${itemTitle} ${itemParents.join('')}${itemIndex}`;
  };

  const activeTrailIdx = isActiveTrail ? Math.floor(Math.random() * count) : null;

  for (let i = 1; i <= count; i++) {
    const link = {
      title: titleCb(title, i, currentLevel, isActiveTrail, parents),
      url: randomUrl(),
    };

    if (activeTrailIdx === i) {
      link.in_active_trail = true;
    }

    if (currentLevel < levels) {
      link.below = generateMenuLinks(count, levels, activeTrailIdx === i, title, titleCb, currentLevel + 1, parents.concat([i]));
      link.is_expanded = randomBool(0.5);
    }

    links.push(link);
  }

  return links;
}

export default function getMenuLinks(parentKnobs = {}, titleCb = null) {
  const linksPerLevel = knobNumber(
    'Links per level',
    3,
    {
      range: true,
      min: 0,
      max: 5,
      step: 1,
    },
    parentKnobs.links_per_level,
    parentKnobs.knobTab,
  );

  const levels = knobNumber(
    'Number of levels',
    3,
    {
      range: true,
      min: 1,
      max: 5,
      step: 1,
    },
    parentKnobs.number_of_levels,
    parentKnobs.knobTab,
  );

  const activeTrail = knobBoolean('Show active trail (random)', false, parentKnobs.show_active_trail, parentKnobs.knobTab);

  return generateMenuLinks(linksPerLevel, levels, activeTrail, null, titleCb);
}
