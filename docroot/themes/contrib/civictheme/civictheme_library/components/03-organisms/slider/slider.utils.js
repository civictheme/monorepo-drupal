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
} from '../../00-base/base.stories';
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
  const tags = [];
  for (let i = 0; i < count; i++) {
    tags.push(Button({
      theme,
      kind: 'link',
      text: randomString(randomInt(3, 8)),
      type: ['primary', 'secondary', 'tertiary'][randomInt(0, 2)],
      url: randomUrl(),
    }));
  }
  return tags;
};

export const randomSlidesComponent = (count, theme, rand, template) => {
  const slides = [];

  const inverseTheme = theme === 'dark' ? 'dark' : 'light';

  for (let i = 0; i < count; i++) {
    const contentTop = template && template.content_top ? template.content_top : randomTagsComponent(randomInt(0, 4), theme).join(' ');
    const imagePosition = template && template.image_position ? template.image_position : 'right';
    const title = template && template.title ? template.title : `Title ${i + 1}${rand ? ` ${randomString(randomInt(5, 30))}` : ''}`;
    const randURL = randomBool() ? randomUrl() : null;
    const url = template && template.url ? template.url : randURL;
    const summary = template && template.summary ? template.summary : `Summary ${i + 1}${rand ? ` ${randomString(randomInt(5, 250))}` : ''}`;
    const links = template && template.links ? template.links : randomButtonsComponent(randomInt(0, 4), inverseTheme).join('');
    const image = template && template.image ? template.image : {
      src: demoImage(),
      alt: randomText(4),
    };
    const contentBottom = template && template.content_bottom ? template.content_bottom : '';
    const attributes = template && template.attributes ? template.attributes : 'data-component-ct-slider-slide';
    slides.push(Slide({
      theme,
      image_position: imagePosition,
      content_top: contentTop,
      title,
      url,
      summary,
      links,
      image,
      content_bottom: contentBottom,
      attributes,
    }));
  }
  return slides;
};
