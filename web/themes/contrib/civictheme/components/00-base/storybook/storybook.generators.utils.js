// phpcs:ignoreFile
//
// Domain-specific generators for all Storybook stories.
//
/* eslint max-classes-per-file: 0 */

import { randomInt, randomBool, randomSentence, randomString, randomArrayItem } from './storybook.random.utils';

export const themes = () => ({
  light: 'Light',
  dark: 'Dark',
});

export const placeholder = (content = 'Content placeholder', words = 0, cssClass = 'story-placeholder') => `<div class="${cssClass}" contenteditable="true">${content}${words > 0 ? ` ${randomSentence(words)}` : ''}</div>`;

export const code = (content) => `<code>${content}</code>`;

export const demoImage = (idx) => {
  const images = [
    'demo/images/demo1.jpg',
    'demo/images/demo2.jpg',
    'demo/images/demo3.jpg',
    'demo/images/demo4.jpg',
    'demo/images/demo5.jpg',
    'demo/images/demo6.jpg',
  ];

  const maxIndex = images.length - 1;
  idx = typeof idx !== 'undefined' ? Math.max(0, Math.min(idx, maxIndex)) : null;

  return idx !== null ? images[idx] : randomArrayItem(images);
};

export const demoIcon = () => './assets/icons/megaphone.svg';

export const demoVideoPoster = () => 'demo/videos/demo_poster.png';

export const demoVideos = () => [
  {
    url: 'demo/videos/demo.webm',
    type: 'video/webm',
  },
  {
    url: 'demo/videos/demo.mp4',
    type: 'video/mp4',
  },
  {
    url: 'demo/videos/demo.avi',
    type: 'video/avi',
  },
];

export const generateItems = (count, content) => {
  const items = [];
  for (let i = 1; i <= count; i++) {
    if (typeof content === 'function') {
      items.push(content(i, count));
    } else {
      items.push(content);
    }
  }
  return items;
};

export const generateSelectOptions = (count, type = 'option') => {
  const options = [];
  for (let i = 1; i <= count; i++) {
    const disabled = randomBool(0.8);
    const option = {
      type,
      is_selected: randomBool(0.8),
      is_disabled: disabled,
      label: (type === 'optgroup' ? `Group ${i}` : randomString(randomInt(3, 8))) + (disabled ? ' (disabled)' : ''),
      value: randomString(randomInt(1, 8)),
      options: type === 'optgroup' ? generateSelectOptions(count) : null,
    };
    options.push(option);
  }
  return options;
};
