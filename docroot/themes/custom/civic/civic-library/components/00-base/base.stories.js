//
// Shared stories JS helpers.
//

import { boolean } from '@storybook/addon-knobs';

export const getSlots = (names) => {
  const showSlots = boolean('Show story-slots', false, 'Slots');
  const obj = {};

  if (showSlots) {
    for (const i in names) {
      obj[names[i]] = `<div class="story-slot story-slot--${names[i]}">{{ ${names[i]} }}</div>`;
    }
  }

  return obj;
};

export const randomUrl = (domain) => {
  domain = domain || 'http://example.com';
  return `${domain}/${(Math.random() + 1).toString(36).substring(7)}`;
};

export const randomLinks = (count, domain) => {
  const links = [];

  for (let i = 0; i < count; i++) {
    links.push({
      text: `Link ${i + 1}`,
      url: randomUrl(domain),
    });
  }

  return links;
};
