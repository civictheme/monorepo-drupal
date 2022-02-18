/* eslint-disable camelcase */
import { boolean, number } from '@storybook/addon-knobs';
import { getRandomBool } from '../base.stories';

export function generateMenuLinks(count, level, is_active_trail, title, title_prefix) {
  const links = [];
  title_prefix = title_prefix || '';
  title = title_prefix + (title || 'Item ');

  const active_trail_idx = is_active_trail ? Math.floor(Math.random() * count) : null;

  for (let i = 0; i < count; i++) {
    const link = {
      title: `${title}${i + 1}`,
      url: `http://example.com/${(Math.random() + 1).toString(36).substring(7)}`,
    };

    if (active_trail_idx === i) {
      link.in_active_trail = true;
    }

    if (level > 1) {
      link.below = generateMenuLinks(count, level - 1, active_trail_idx === i, link.title);
      link.is_expanded = getRandomBool(0.5);
    }

    links.push(link);
  }

  return links;
}

export default function getMenuLinks(knobTab, titlePrefix) {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  titlePrefix = titlePrefix || null;

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

  return generateMenuLinks(links_per_level, levels, activeTrail, null, titlePrefix);
}
