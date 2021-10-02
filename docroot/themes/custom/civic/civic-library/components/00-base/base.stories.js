//
// Shared stories JS helpers.
//

import { boolean } from '@storybook/addon-knobs';

export const getSlots = (names) => {
  const obj = {};
  for (const i in names) {
    obj[names[i]] = boolean('Show story-slots', false, 'Slots') ? `<div class="story-slot story-slot--${names[i]}">{{ ${names[i]} }}</div>` : null;
  }
  return obj;
};
