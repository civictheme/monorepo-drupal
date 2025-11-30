// phpcs:ignoreFile
//
// Generic helper utilities for all Storybook stories.
//

export const arrayCombine = function (keys, values) {
  const obj = {};

  if (!keys || !values || keys.constructor !== Array || values.constructor !== Array) {
    return false;
  }

  if (keys.length !== values.length) {
    return false;
  }

  for (let i = 0; i < keys.length; i++) {
    obj[keys[i]] = values[i];
  }

  return obj;
};

export const objectFromArray = (array) => {
  const obj = {};
  array.forEach((item) => {
    obj[item] = item;
  });
  return obj;
};

export const capitalizeFirstLetter = (string) => string.charAt(0)
  .toUpperCase() + string.slice(1);

export const cleanCssIdentifier = function (string) {
  return string.toLowerCase()
    .replace(/_/, '-')
    .replace(/(&\w+?;)/gim, ' ')
    .replace(/[_.~"<>%|'!*();:@&=+$,/?%#[\]{}\n`^\\]/gim, '')
    .replace(/(^\s+)|(\s+$)/gim, '')
    .replace(/\s+/gm, '-');
};

export const toLabels = function (values) {
  const arr = [];
  for (const i in values) {
    arr.push(capitalizeFirstLetter(values[i].replace(/[-_]/, ' ')));
  }
  return arr;
};

export const dateIsValid = function (date) {
  return !Number.isNaN(Date.parse(date));
};

export const convertDate = (date) => new Date(date).toLocaleDateString('en-uk', {
  year: 'numeric',
  month: 'short',
  day: 'numeric',
});
