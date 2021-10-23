/* eslint-disable camelcase */
import { boolean, number } from '@storybook/addon-knobs';

export function generateMenuLinks(count, level, is_active_trail) {
  const links = [];

  const active_trail_idx = is_active_trail ? Math.floor(Math.random() * count) : null;

  for (let i = 0; i < count; i++) {
    const link = {
      title: `Item ${i + 1}`,
      url: `http://example.com/${(Math.random() + 1).toString(36).substring(7)}`,
    };

    if (active_trail_idx === i) {
      link.in_active_trail = true;
    }

    if (level > 1) {
      link.below = generateMenuLinks(count, level - 1, active_trail_idx === i);
      link.is_expanded = true;
    }

    links.push(link);
  }

  return links;
}

export default function getMenuLinks() {
  const links_per_level = number(
    'Links per level',
    3,
    {
      range: true,
      min: 0,
      max: 5,
      step: 1,
    },
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
  );
  const activeTrail = boolean('Show active trail (random)', false);

  return generateMenuLinks(links_per_level, levels, activeTrail);
}
