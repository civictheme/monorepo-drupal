//
// Shared JS helpers for Storybook stories.
//

import { boolean } from '@storybook/addon-knobs';
import { LoremIpsum } from 'lorem-ipsum';

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

export const randomText = (words) => {
  const lorem = new LoremIpsum({
    sentencesPerParagraph: {
      max: 8,
      min: 4,
    },
    wordsPerSentence: {
      max: 16,
      min: 4,
    },
  });

  return lorem.generateWords(words);
};

export const getRandomInt = (min, max) => {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min) + min);
};

export const getRandomBool = (skew) => {
  skew = skew || 0.5;
  return Math.random() > skew;
};

export const randomString = (length) => randomText(length).substring(0, length).trim();

export const randomUrl = (domain) => {
  domain = domain || 'http://example.com';
  return `${domain}/${(Math.random() + 1).toString(36).substring(7)}`;
};

export const randomLinks = (count, length, domain) => {
  const links = [];
  length = length || 0;

  for (let i = 0; i < count; i++) {
    links.push({
      text: `Link ${i + 1}${length ? ` ${randomString(getRandomInt(1, length))}` : ''}`,
      url: randomUrl(domain),
      new_window: getRandomBool(),
      is_external: getRandomBool(0.8),
    });
  }

  return links;
};

export const randomTags = (count, rand) => {
  const tags = [];
  rand = rand || false;

  for (let i = 0; i < count; i++) {
    tags.push(`Topic ${i + 1}${rand ? ` ${randomString(getRandomInt(2, 5))}` : ''}`);
  }

  return tags;
};

export const demoImage = () => './assets/images/demo.png';
