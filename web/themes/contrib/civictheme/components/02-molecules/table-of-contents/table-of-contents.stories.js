// phpcs:ignoreFile
import {
  boolean,
  number, radios, text,
} from '@storybook/addon-knobs';
import { randomText } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Table Of Contents',
};

export const TableOfContents = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const countOfTocs = number(
    'Number of TOCs',
    1,
    {
      range: true,
      min: 1,
      max: 5,
      step: 1,
    },
    generalKnobTab,
  );

  const generateContent = (count, selector, duplicate, index) => {
    let html = '';

    const selectorIsTag = selector.slice(0, 1) !== '.';

    for (let i = 0; i < count; i++) {
      let elHtml = '';
      if (selectorIsTag) {
        elHtml += `<${selector}>TOC ${index} Section heading ${i + 1}</${selector}>`;
      } else {
        elHtml += `<h2 class="${selector.slice(1)}">Section heading ${i + 1}</h2>`;
      }
      elHtml += `<div>${randomText(30)}</div>`;

      html += elHtml;

      if (duplicate) {
        html += elHtml;
      }
    }

    return html;
  };

  const contentKnobTab = 'Content';

  const countOfContentItems = number(
    'Number of items',
    5,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    contentKnobTab,
  );

  const htmlSelector = text(
    'Selector',
    'h2',
    contentKnobTab,
  );

  const duplicate = boolean(
    'Duplicated headers',
    false,
    contentKnobTab,
  );

  const wrappers = [];
  for (let i = 0; i < countOfTocs; i++) {
    const tocKnobTab = `TOC ${i + 1}`;

    const attributes = {
      'data-table-of-contents-theme': radios(
        'Theme',
        {
          Light: 'light',
          Dark: 'dark',
        },
        'light',
        tocKnobTab,
      ),
      'data-table-of-contents-title': text('Title', 'On this page', tocKnobTab),
      'data-table-of-contents-anchor-selector': text('Anchor selector', 'h2', tocKnobTab),
      'data-table-of-contents-anchor-scope-selector': text('Anchor scope selector', `.ct-basic-content-${i + 1}`, tocKnobTab),
      'data-table-of-contents-position': radios('Position', {
        Before: 'before',
        After: 'after',
        Prepend: 'prepend',
        Append: 'append',
      }, 'before', tocKnobTab),
    };

    const attributesAdditional = text('Additional attributes', '', tocKnobTab);

    const modifierClass = text('Additional class', '', tocKnobTab);

    const html = generateContent(countOfContentItems, htmlSelector, duplicate, i + 1);

    const attributesStr = Object.keys(attributes).map((key) => (attributes[key] !== '' ? `${key}="${attributes[key]}"` : '')).join(' ');

    wrappers.push(`<div class="ct-basic-content ct-basic-content-${i + 1} ct-theme-${attributes['data-table-of-contents-theme']} ${modifierClass}" ${attributesStr} ${attributesAdditional}>${html}</div>`);
  }

  return wrappers.join(' ');
};
