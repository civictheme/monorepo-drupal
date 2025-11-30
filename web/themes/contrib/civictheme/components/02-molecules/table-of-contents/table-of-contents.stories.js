// phpcs:ignoreFile
import { knobBoolean, knobNumber, knobRadios, knobText, randomText, shouldRender } from '../../00-base/storybook/storybook.utils';
import './table-of-contents';

export default {
  title: 'Molecules/Table Of Contents',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
  },
};

export const TableOfContents = (parentKnobs = {}) => {
  const countOfTocs = knobNumber(
    'Number of TOCs',
    1,
    {
      range: true,
      min: 1,
      max: 5,
      step: 1,
    },
    parentKnobs.number_of_tocs,
    parentKnobs.knobTab,
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

  const countOfContentItems = knobNumber(
    'Number of items',
    5,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    parentKnobs.number_of_items,
    contentKnobTab,
  );

  const htmlSelector = knobText(
    'Selector',
    'h2',
    parentKnobs.html_selector,
    contentKnobTab,
  );

  const duplicate = knobBoolean(
    'Duplicated headers',
    false,
    parentKnobs.is_duplicated_headers,
    contentKnobTab,
  );

  const wrappers = [];
  for (let i = 0; i < countOfTocs; i++) {
    const tocKnobTab = `TOC ${i + 1}`;

    const attributes = {
      'data-table-of-contents-theme': knobRadios(
        'Theme',
        {
          Light: 'light',
          Dark: 'dark',
        },
        'light',
        parentKnobs[`theme_toc_${i}`],
        tocKnobTab,
      ),
      'data-table-of-contents-title': knobText('Title', 'On this page', parentKnobs[`content_title_toc_${i}`], tocKnobTab),
      'data-table-of-contents-anchor-selector': knobText('Anchor selector', 'h2', parentKnobs[`content_anchor_selector_toc_${i}`], tocKnobTab),
      'data-table-of-contents-anchor-scope-selector': knobText('Anchor scope selector', `.ct-basic-content-${i + 1}`, parentKnobs[`content_anchor_scope_selector_toc_${i}`], tocKnobTab),
      'data-table-of-contents-position': knobRadios('Position', {
        Before: 'before',
        After: 'after',
        Prepend: 'prepend',
        Append: 'append',
      }, 'before', parentKnobs[`content_position_toc_${i}`], tocKnobTab),
    };

    const modifierClass = knobText('Additional class', '', parentKnobs[`modifier_class_toc_${i}`], tocKnobTab);
    const attributesStr = Object.keys(attributes).map((key) => (attributes[key] !== '' ? `${key}="${attributes[key]}"` : '')).join(' ');
    const attributesAdditional = knobText('Additional attributes', '', parentKnobs[`attributes_toc_${i}`], tocKnobTab);
    const content = generateContent(countOfContentItems, htmlSelector, duplicate, i + 1);

    wrappers.push(`<div class="ct-basic-content ct-basic-content-${i + 1} ct-theme-${attributes['data-table-of-contents-theme']} ${modifierClass}" ${attributesStr} ${attributesAdditional}>${content}</div>`);
  }

  const html = wrappers.join(' ');

  return shouldRender(parentKnobs) ? html : '';
};
