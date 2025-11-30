// phpcs:ignoreFile
//
// Random and demo generators for all Storybook stories.
//
/* eslint max-classes-per-file: 0 */

import { LoremIpsum } from 'lorem-ipsum';

import seedrandom from 'seedrandom';
import { capitalizeFirstLetter, convertDate } from './storybook.helpers.utils';

export const randomBool = (skew) => {
  skew = skew || 0.5;
  return Math.random() > skew;
};

export const randomInt = (min = 1, max = 100) => {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min) + min);
};

export const randomId = (prefix = '') => `random-id-${prefix.length > 0 ? `${prefix}-` : ''}${randomInt(10000, 99999)}`;

export const randomArrayItem = (array) => array[Math.floor(Math.random() * array.length)];

export const randomText = (words, seed = null) => {
  seed = seed || Math.random().toString();

  const lorem = new LoremIpsum({
    sentencesPerParagraph: {
      max: 8,
      min: 4,
    },
    wordsPerSentence: {
      max: 16,
      min: 4,
    },
    random: seedrandom(seed),
  });

  return lorem.generateWords(words);
};

export const randomString = (length, seed = null) => randomText(length, seed)
  .substring(0, length)
  .trim();

export const randomName = (length = 8, seed = null) => randomText(length, seed)
  .replace(' ', '')
  .substring(0, length).trim();

export const randomSentence = (words, seed = null) => {
  words = words || randomInt(5, 25);
  return capitalizeFirstLetter(randomText(words, seed));
};

export const randomUrl = (domain) => {
  domain = domain || 'http://example.com';
  return `${domain}/${(Math.random() + 1).toString(36).substring(7)}`;
};

export const randomFutureDate = (days = 30) => {
  const now = new Date();
  const endDate = new Date(now.getTime() + days * 24 * 60 * 60 * 1000);
  const randomDate = new Date(now.getTime() + Math.random() * (endDate.getTime() - now.getTime()));

  return convertDate(randomDate);
};

export const randomLink = (text, url, isNewWindow, isExternal) => `<a href="${url || randomUrl()}"${isNewWindow ? ' target="_blank"' : ''}${isExternal ? ' rel="noopener noreferrer"' : ''}>${text || randomSentence(3)}</a>`;

export const randomLinks = (count, length, domain, prefix) => {
  const links = [];
  prefix = prefix || 'Link';
  length = length || 0;

  for (let i = 0; i < count; i++) {
    links.push({
      text: `${prefix} ${i + 1}${length ? ` ${randomString(randomInt(3, length))}` : ''}`,
      url: randomUrl(domain),
      is_new_window: randomBool(),
      is_external: randomBool(0.8),
    });
  }

  return links;
};

export const randomTags = (count, rand) => {
  const tags = [];
  rand = rand || false;

  for (let i = 0; i < count; i++) {
    tags.push(`Topic ${i + 1}${rand ? ` ${randomString(randomInt(2, 5))}` : ''}`);
  }

  return tags;
};
