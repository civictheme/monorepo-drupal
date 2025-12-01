// phpcs:ignoreFile
/**
 * @file
 * Menu component utilities.
 */

/* eslint-disable camelcase */

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
      url: 'http://www.civictheme.io',
    };

    if (activeTrailIdx === i) {
      link.in_active_trail = true;
    }

    if (currentLevel < levels) {
      link.below = generateMenuLinks(count, levels, activeTrailIdx === i, title, titleCb, currentLevel + 1, parents.concat([i]));
      link.is_expanded = Math.random() > 0.5;
    }

    links.push(link);
  }

  return links;
}
