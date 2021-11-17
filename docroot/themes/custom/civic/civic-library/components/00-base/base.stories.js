//
// Shared JS helpers for Storybook stories.
//

import { boolean } from '@storybook/addon-knobs';
import { LoremIpsum } from 'lorem-ipsum';
import Input from '../01-atoms/input/input.twig';
import Select from '../01-atoms/select/select.twig';
import Checkbox from '../01-atoms/checkbox/checkbox.twig';
import Radio from '../01-atoms/radio/radio.twig';
import FormElement from '../03-organisms/form-element/form-element.twig';
import Label from '../01-atoms/label/label.twig';

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

export const randomFormElements = (count, theme, rand) => {
  rand = rand || false;

  const inputTypes = [
    'text',
    'textarea',
    'tel',
    'password',
    'radio',
    'checkbox',
    'select',
  ];

  const requiredOptions = ['required', ''];

  const formElements = [];
  for (let i = 0; i < count; i++) {
    const inputType = inputTypes[Math.floor(Math.random() * inputTypes.length)];
    const isCheckboxOrRadio = inputType === 'checkbox' || inputType === 'radio';
    const required = requiredOptions[Math.floor(Math.random() * requiredOptions.length)];

    const formElementOptions = {
      theme,
      type: inputType,
      label: Label({
        title: `Input title ${i + 1}${rand ? ` ${randomString(getRandomInt(2, 5))}` : ''}`,
        attributes: `for="form-element-${i}"`,
        title_position: isCheckboxOrRadio ? 'after' : 'before',
        required,
      }),
      label_display: isCheckboxOrRadio ? 'after' : 'before',
      description_display: isCheckboxOrRadio ? 'after' : 'before',
      description: {
        content: `Input description ${i + 1}${rand ? ` ${randomText(getRandomInt(2, 8))}` : ''}`,
      },
      children: [],
    };
    const inputOptions = {
      theme,
      type: inputType,
      attributes: `id="form-element-${i}"`,
      required,
    };

    switch (inputType) {
      case 'radio':
        formElementOptions.children.push(Radio(inputOptions));
        break;
      case 'checkbox':
        formElementOptions.children.push(Checkbox(inputOptions));
        break;
      case 'select':
        formElementOptions.children.push(Select(inputOptions));
        break;
      default:
        formElementOptions.children.push(Input(inputOptions));
    }
    formElements.push(FormElement(formElementOptions));
  }

  return formElements;
};
