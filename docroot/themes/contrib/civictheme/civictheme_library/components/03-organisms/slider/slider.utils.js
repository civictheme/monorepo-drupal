// phpcs:ignoreFile
/**
 * @file
 * Slider component utilities.
 */

import {
  demoImage,
  randomBool,
  randomInt,
  randomString,
  randomText,
  randomUrl,
} from '../../00-base/base.utils';
import Slide from './slide.twig';
import Tag from '../../01-atoms/tag/tag.twig';
import Button from '../../01-atoms/button/button.twig';

export const randomTagsComponent = (count, theme) => {
  const tags = [];
  for (let i = 0; i < count; i++) {
    tags.push(Tag({
      theme,
      text: randomString(randomInt(3, 8)),
    }));
  }
  return tags;
};

export const randomButtonsComponent = (count, theme) => {
  const buttons = [];
  for (let i = 0; i < count; i++) {
    buttons.push(Button({
      theme,
      kind: 'button',
      text: randomString(randomInt(3, 8)),
      type: ['primary', 'secondary', 'tertiary'][randomInt(0, 2)],
      url: randomUrl(),
    }));
  }
  return buttons;
};

export const randomSlidesComponent = (count, theme, rand, template) => {
  const slides = [];

  const inverseTheme = theme === 'dark' ? 'dark' : 'light';

  for (let i = 0; i < count; i++) {
    const image = template && template.image ? template.image : {
      url: demoImage(),
      alt: randomText(4),
    };
    const imagePosition = template && template.image_position ? template.image_position : 'right';
    const tags = template && template.tags ? template.tags : {};
    const title = template && template.title ? template.title : `Title ${i + 1}${rand ? ` ${randomString(randomInt(5, 30))}` : ''}`;
    const url = template && template.url ? template.url : (randomBool() ? randomUrl() : null);
    const content = template && template.content ? template.content : `Content ${i + 1}${rand ? ` ${randomString(randomInt(5, 250))}` : ''}`;
    const links = template && template.links ? template.links : randomButtonsComponent(randomInt(0, 4), inverseTheme).join('');
    const attributes = template && template.attributes ? template.attributes : 'data-slider-slide';

    slides.push(Slide({
      theme,
      image,
      image_position: imagePosition,
      tags,
      title,
      url,
      content,
      links,
      attributes,
    }));
  }
  return slides;
};
