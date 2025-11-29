// phpcs:ignoreFile
/**
 * @file
 * Slider component utilities.
 */

import { dateIsValid, demoImage, randomBool, randomInt, randomString, randomText, randomUrl } from '../../00-base/storybook/storybook.utils';
import CivicThemeSlide from './slide.twig';
import CivicThemeTag from '../../01-atoms/tag/tag.twig';
import CivicThemeButton from '../../01-atoms/button/button.twig';

export const randomTagsComponent = (count, theme) => {
  const tags = [];
  for (let i = 0; i < count; i++) {
    tags.push(CivicThemeTag({
      theme,
      text: randomString(randomInt(3, 8)),
    }));
  }
  return tags;
};

export const randomButtonsComponent = (count, theme) => {
  const buttons = [];
  for (let i = 0; i < count; i++) {
    buttons.push(CivicThemeButton({
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
    const date = template && template.date ? template.date : '20 Jan 2023 11:00';
    const dateEnd = template && template.date_end ? template.date_end : null;
    const title = template && template.title ? template.title : `Title ${i + 1}${rand ? ` ${randomString(randomInt(5, 30))}` : ''}`;
    const url = template && template.url ? template.url : (randomBool() ? randomUrl() : null);
    const content = template && template.content ? template.content : `Content ${i + 1}${rand ? ` ${randomString(randomInt(5, 250))}` : ''}`;
    const links = template && template.links ? template.links : randomButtonsComponent(randomInt(0, 4), inverseTheme).join('');
    const attributes = template && template.attributes ? template.attributes : '';

    const dateIso = dateIsValid(date) ? new Date(date).toISOString() : null;
    const dateEndIso = dateIsValid(dateEnd) ? new Date(dateEnd).toISOString() : null;

    slides.push(CivicThemeSlide({
      theme,
      image,
      image_position: imagePosition,
      tags,
      date,
      date_iso: dateIso,
      date_end: dateEnd,
      date_end_iso: dateEndIso,
      title,
      url,
      content,
      links,
      attributes,
    }));
  }
  return slides;
};
