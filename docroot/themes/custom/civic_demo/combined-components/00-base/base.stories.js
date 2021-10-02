//
// Shared stories JS helpers.
//

import { boolean } from "@storybook/addon-knobs";

export const getSlots = (names) => {
  let obj = {};
  for (var i in names) {
    obj[names[i]] = boolean('Show slots', false, 'Slots') ? '<div class="slot slot--' + names[i] + '">{{ ' + names[i] + ' }}</div>' : null;
  }
  return obj;
}
