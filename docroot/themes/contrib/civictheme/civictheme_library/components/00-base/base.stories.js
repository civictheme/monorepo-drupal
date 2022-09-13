// phpcs:ignoreFile
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
import FieldLabel from '../01-atoms/label/label.twig';
import DropdownFilter
  from '../02-molecules/dropdown-filter/dropdown-filter.twig';

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

export const capitalizeFirstLetter = (string) => string.charAt(0).toUpperCase() + string.slice(1);

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

export const randomSentence = (words) => {
  words = words || getRandomInt(5, 25);
  return capitalizeFirstLetter(randomText(words));
};

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
      is_new_window: getRandomBool(),
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

export const demoImage = (idx) => {
  const images = [
    './assets/images/demo1.jpg',
    './assets/images/demo2.jpg',
    './assets/images/demo3.jpg',
    './assets/images/demo4.jpg',
  ];

  idx = idx || Math.floor(Math.random() * images.length);

  return images[idx];
};

export const demoVideos = () => [
  {
    url: './assets/videos/demo.webm',
    type: 'video/webm',
  },
  {
    url: './assets/videos/demo.mp4',
    type: 'video/mp4',
  },
  {
    url: './assets/videos/demo.avi',
    type: 'video/avi',
  },
];

export const demoVideoPoster = () => './assets/videos/demo_poster.png';

export const demoIcon = () => './assets/icons/Brands/Dropbox.svg';

export const formElement = (inputType, options, theme, rand, itr) => {
  const isCheckboxOrRadio = inputType === 'checkbox' || inputType === 'radio';

  const formElementOptions = {
    theme,
    type: inputType,
    label: FieldLabel({
      theme,
      content: options.title ? options.title : `Input title ${itr + 1}${rand ? ` ${randomString(getRandomInt(2, 5))}` : ''}`,
      attributes: `for="form-element-${itr}"`,
      required: options.required,
    }),
    label_display: isCheckboxOrRadio ? 'after' : 'before',
    description_position: isCheckboxOrRadio ? 'after' : 'before',
    description: {
      content: options.description ? `Input description ${itr + 1}${rand ? ` ${randomText(getRandomInt(4, 10))}` : ''}` : '',
    },
    children: [],
    attributes: options.form_element_attributes,
  };
  let attributes = `id="form-element-${itr}"`;
  if (typeof options.attributes !== 'undefined') {
    attributes += options.attributes;
  }

  const inputOptions = {
    theme,
    type: inputType,
    attributes,
    required: options.required,
    value: typeof options.value !== 'undefined' ? options.value : randomString(getRandomInt(3, 8)),
  };

  switch (inputType) {
    case 'radio':
      formElementOptions.children.push(Radio(inputOptions));
      break;
    case 'checkbox':
      formElementOptions.children.push(Checkbox(inputOptions));
      break;
    case 'select':
      formElementOptions.children.push(Select({
        ...inputOptions,
        options: inputOptions.value,
      }));
      break;
    default:
      formElementOptions.children.push(Input(inputOptions));
  }

  return FormElement(formElementOptions);
};

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
    const required = [Math.floor(Math.random() * requiredOptions.length)];

    formElements.push(formElement(
      inputType,
      {
        required,
      },
      theme,
      rand,
      i,
    ));
  }

  return formElements;
};

export const dropDownFilter = (filterType, numOfOptions, theme, rand, itr) => {
  const filterOptions = {
    filter_text: `Filter text ${itr + 1}${rand ? ` ${randomString(getRandomInt(2, 5))}` : ''}`,
    filter_group: 'filter_group',
    options_title: Math.round(Math.random()) ? 'Options title (optional)' : '',
  };
  const children = [];
  let count = itr * numOfOptions;
  for (let i = 1; i <= numOfOptions; i++) {
    const options = {
      required: false,
      description: false,
      attributes: '',
      value: randomString(getRandomInt(1, 8)),
    };
    options.attributes += filterType === 'radio' ? ` name="test_${itr}"` : ` name="${randomString(getRandomInt(3, 8))}"`;
    children.push(formElement(filterType, options, theme, true, count++));
  }

  return DropdownFilter({
    theme,
    ...filterOptions,
    type: filterType,
    options: children.join(''),
  });
};

export const randomInputItems = (count, sameName = false) => {
  const name = randomString(getRandomInt(3, 8));
  const id = randomString(getRandomInt(3, 8));
  const items = [];
  for (let i = 1; i <= count; i++) {
    const item = {
      label: randomString(getRandomInt(3, 8)),
      value: randomString(getRandomInt(1, 8)),
      name: sameName ? name : randomString(getRandomInt(3, 8)),
      id: `${id}-${i}`,
    };
    items.push(item);
  }
  return items;
};

export const randomSelectOptions = (count, type = 'option') => {
  const options = [];
  for (let i = 1; i <= count; i++) {
    const option = {
      type,
      selected: false,
      label: type === 'optgroup' ? `Group ${i}` : randomString(getRandomInt(3, 8)),
      value: randomString(getRandomInt(1, 8)),
      options: type === 'optgroup' ? randomSelectOptions(count) : null,
    };
    options.push(option);
  }
  return options;
};
