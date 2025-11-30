// phpcs:ignoreFile
import CivicThemeGrid from './grid.twig';
import { code, generateItems, knobBoolean, knobNumber, knobRadios, knobText, placeholder, randomSentence, shouldRender } from '../storybook/storybook.utils';

export default {
  title: 'Base/Grid',
  parameters: {
    layout: 'fullscreen',
    docs: '<div class="grid-story-docs story-grid-outlines row row--no-grow"><strong>Outline colors: </strong><br/><span class="col grid-story-docs-color-container-contained">Contained container</span><span class="col grid-story-docs-color-container-fluid">Fluid container</span><span class="col grid-story-docs-color-row">Row</span><span class="col grid-story-docs-color-template-column">Template column</span><span class="col grid-story-docs-color-auto-column">Auto column</span><span class="col grid-story-docs-color-placeholder">Placeholder</span></div>',
    docsClass: 'story-docs--conditional',
  },
};

export const Grid = () => {
  const showOutline = knobBoolean('Show outlines', false);

  let cols = [];

  let html = `<div class="story-container ${showOutline ? 'story-grid-outlines' : ''}">`;

  html += `<div class="story-container__title">Container</div>`;

  html += `<div class="story-container__subtitle">Contained container ${code('.container')}</div>`;
  cols = [12];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(cols[j], placeholder(code(Math.floor(12 / cols[j])))),
      column_attributes: `data-story-total-columns="${cols[j]}"`,
      template_column_count: cols[j],
    });
  }

  html += `<div class="story-container__subtitle">Fluid container ${code('.container-fluid')}</div>`;
  cols = [12];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(cols[j], placeholder(code(Math.floor(12 / cols[j])))),
      column_attributes: `data-story-total-columns="${cols[j]}"`,
      template_column_count: cols[j],
      is_fluid: true,
    });
  }

  html += `<div class="story-container__title">Columns</div>`;

  html += `<div class="story-container__subtitle">Template column in container ${code('.container > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = [1, 2, 3, 4, 6, 12];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(cols[j], placeholder(code(Math.floor(12 / cols[j])))),
      column_attributes: `data-story-total-columns="${cols[j]}"`,
      template_column_count: cols[j],
    });
  }

  html += `<div class="story-container__subtitle">Template column in fluid container ${code('.container-fluid > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = [1];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(cols[j], placeholder(code(Math.floor(12 / cols[j])))),
      column_attributes: `data-story-total-columns="${cols[j]}"`,
      template_column_count: cols[j],
      is_fluid: true,
    });
  }

  html += `<div class="story-container__subtitle">Auto column in container ${code('.container .row > .col')}</div>`;
  cols = [1, 2, 3, 4, 6, 12];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(cols[j], placeholder(code('auto'))),
      column_attributes: `data-story-total-columns="${cols[j]}"`,
    });
  }

  html += `<div class="story-container__title">Offsets and order</div>`;

  html += `<div class="story-container__subtitle">Template column in container ${code('.container > .row > .col-[breakpoint]-offset-[column]')}</div>`;
  cols = [1, 2, 3, 4, 6, 12];
  let offsets = [1, 2, 3, 4, 6, 8];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(1, placeholder(`width ${code(Math.floor(12 / cols[j]))}, offset ${code(offsets[j])}`)),
      column_attributes: `data-story-total-columns="1"`,
      column_class: `col-m-offset-${offsets[j]}`,
      template_column_count: cols[j],
    });
  }

  html += `<div class="story-container__subtitle">Auto column in container ${code('.container > .row > .col.col-[breakpoint]-offset-[column]')}</div>`;
  cols = [1, 2, 3, 4, 6, 12];
  offsets = [1, 2, 3, 4, 6, 8];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(1, placeholder(`width ${code('auto')}, offset ${code(offsets[j])}`)),
      column_attributes: `data-story-total-columns="1"`,
      column_class: `col-m-offset-${offsets[j]}`,
    });
  }

  html += `<div class="story-container__title">Content width</div>`;

  html += `<div class="story-container__subtitle">Filled ${code('width: 100%')}</div>`;
  cols = ['A', 'B', 'C', 'D', 'E'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    column_class: 'col',
  });

  html += `<div class="story-container__subtitle">Hugged ${code('width: auto')}</div>`;
  cols = ['A', 'B', 'C', 'D', 'E'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1], 0, 'story-placeholder--hugged')),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    column_class: 'col',
  });

  html += `<div class="story-container__subtitle">Fixed ${code('width: 184px')}</div>`;
  cols = ['A', 'B', 'C', 'D', 'E'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(`${cols[i - 1]} fixed width`, 0, 'story-placeholder--fixed')),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    column_class: 'col',
  });

  html += `<div class="story-container__title">Nested Rows (container-less)</div>`;

  html += `<div class="story-container__subtitle">Template column wraps template column ${code('.row > .col-[breakpoint]-[column] > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: false,
        template_column_count: 3,
        column_attributes: 'data-story-total-columns="3"',
      }),
      placeholder('Parent'),
    ],
    use_container: false,
    template_column_count: 2,
    column_attributes: 'data-story-total-columns="2"',
    modifier_class: 'row--no-gutters',
  });

  html += `<div class="story-container__subtitle">Template column wraps auto column ${code('.row > .col-[breakpoint]-[column] > .row > .col')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: false,
        column_attributes: 'data-story-total-columns="3"',
      }),
      placeholder('Parent'),
    ],
    use_container: false,
    template_column_count: 2,
    column_attributes: 'data-story-total-columns="2"',
    modifier_class: 'row--no-gutters',
  });

  html += `<div class="story-container__subtitle">Auto column wraps auto-column ${code('.row > .col > .row > .col')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: false,
      }),
      placeholder('Parent'),
    ],
    use_container: false,
    modifier_class: 'row--no-gutters',
  });

  html += `<div class="story-container__subtitle">Auto column wraps template column ${code('.row > .col > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: false,
        template_column_count: 3,
        column_attributes: 'data-story-total-columns="3"',
      }),
      placeholder('Parent'),
    ],
    use_container: false,
    modifier_class: 'row--no-gutters',
  });

  html += `<div class="story-container__title">Nested Containers</div>`;

  html += `<div class="story-container__subtitle">Container with template columns wraps container with template columns ${code('.container .row > .col-[breakpoint]-[column] .container > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: true,
        is_fluid: false,
        column_attributes: 'data-story-total-columns="3"',
      }),
      placeholder('Parent'),
    ],
    use_container: true,
    is_fluid: false,
    template_column_count: 2,
    column_attributes: 'data-story-total-columns="2"',
  });

  html += `<div class="story-container__subtitle">Fluid container with template columns wraps container with template columns ${code('.container-fluid .row > .col-[breakpoint]-[column] .container > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: true,
        is_fluid: false,
        column_attributes: 'data-story-total-columns="3"',
      }),
      placeholder('Parent'),
    ],
    use_container: true,
    is_fluid: true,
    template_column_count: 2,
    column_attributes: 'data-story-total-columns="2"',
  });

  html += `<div class="story-container__subtitle">Fluid container with template columns wraps container with template columns (single column) ${code('.container-fluid .row > .col-[breakpoint]-[column] .container > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: true,
        is_fluid: false,
        column_attributes: 'data-story-total-columns="3"',
      }),
    ],
    use_container: true,
    is_fluid: true,
    template_column_count: 1,
  });

  html += `<div class="story-container__subtitle">Container with template columns wraps fluid container with template columns ${code('.container .row > .col-[breakpoint]-[column] .container-fluid > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: true,
        is_fluid: true,
        column_attributes: 'data-story-total-columns="3"',
      }),
      placeholder('Parent'),
    ],
    use_container: true,
    is_fluid: false,
    template_column_count: 2,
    column_attributes: 'data-story-total-columns="2"',
  });

  html += `<div class="story-container__subtitle">Fluid container with template columns wraps fluid container with template columns ${code('.container-fluid .row > .col-[breakpoint]-[column] .container-fluid > .row > .col-[breakpoint]-[column]')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: [
      CivicThemeGrid({
        items: [
          placeholder('Nested'),
          placeholder('Nested'),
          placeholder('Nested'),
        ],
        use_container: true,
        is_fluid: true,
        column_attributes: 'data-story-total-columns="3"',
      }),
      placeholder('Parent'),
    ],
    use_container: true,
    is_fluid: true,
    template_column_count: 2,
    column_attributes: 'data-story-total-columns="2"',
  });

  html += `<div class="story-container__title">Row utilities</div>`;

  html += `<div class="story-container__subtitle">No gutters ${code('.row.row--no-gutters')}</div>`;
  cols = ['A', 'B', 'C', 'D', 'E'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    use_container: false,
    row_class: 'row row--no-gutters',
  });

  html += `<div class="story-container__subtitle">No gutters within container ${code('.container > .row.row--no-gutters')}</div>`;
  cols = ['A', 'B', 'C', 'D', 'E'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    use_container: true,
    is_fluid: false,
    row_class: 'row row--no-gutters',
  });

  html += `<div class="story-container__subtitle">No gutters within fluid container ${code('.container-fluid > .row.row--no-gutters')}</div>`;
  cols = ['A', 'B', 'C', 'D', 'E'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    use_container: true,
    is_fluid: true,
    row_class: 'row row--no-gutters',
  });

  html += `<div class="story-container__subtitle">Reversed columns ${code('.row.row--reverse')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    template_column_count: cols.length,
    row_class: 'row row--reverse',
  });

  html += `<div class="story-container__subtitle">Equal column heights by default</div>`;
  cols = [`<strong>Content should not fill - height is not 100%.</strong> ${randomSentence(5, 'A')}`, randomSentence(30, 'B'), randomSentence(5, 'C'), randomSentence(5, 'D')];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    template_column_count: cols.length,
  });

  html += `<div class="story-container__subtitle">Equal column heights propagated to content ${code('.row.row--equal-heights-content > .col-[breakpoint]-[column]')}</div>`;
  cols = [`<strong>Content should fill - height is propagated to be 100%.</strong> ${randomSentence(5, 'A')}`, randomSentence(30, 'B'), randomSentence(5, 'C'), randomSentence(5, 'D')];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    template_column_count: cols.length,
    row_class: 'row row--equal-heights-content',
  });

  html += `<div class="story-container__subtitle">Equal column heights propagated to content ${code('.row.row--equal-heights-content > .col')} - Auto column</div>`;
  cols = [`<strong>Content should fill - height is propagated to be 100%.</strong> ${randomSentence(5, 'A')}`, randomSentence(30, 'B'), randomSentence(5, 'C'), randomSentence(5, 'D')];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    row_class: 'row row--equal-heights-content',
  });

  html += `<div class="story-container__subtitle">Unequal column heights ${code('.row.row--unequal-heights > .col-[breakpoint]-[column]')}</div>`;
  cols = [randomSentence(5, 'A'), randomSentence(20, 'B'), randomSentence(5, 'C'), randomSentence(5, 'D')];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    template_column_count: cols.length,
    row_class: 'row row--unequal-heights',
  });

  html += `<div class="story-container__subtitle">Unequal column heights ${code('.row.row--unequal-heights > .col')} - Auto column</div>`;
  cols = [randomSentence(5, 'A'), randomSentence(20, 'B'), randomSentence(5, 'C'), randomSentence(5, 'D')];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    row_class: 'row row--unequal-heights',
  });

  html += `<div class="story-container__subtitle">Vertical spacing ${code('.row.row--vertically-spaced > .col-[breakpoint]-[column]')}</div>`;
  cols = [4, 3, 4];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(cols[j] + (j % 2), placeholder(code(Math.floor(12 / cols[j])))),
      column_attributes: `data-story-total-columns="${cols[j]}"`,
      template_column_count: cols[j],
      row_class: 'row row--vertically-spaced',
    });
  }

  html += `<div class="story-container__subtitle">Vertical spacing ${code('.row.row--vertically-spaced > .col')} - Autocolumn</div>`;
  cols = [4, 3, 4];
  for (let j = 0; j < cols.length; j++) {
    html += CivicThemeGrid({
      items: generateItems(cols[j] + (j % 2), placeholder(code(Math.floor(12 / cols[j])), 0, 'story-placeholder--fixed')),
      column_attributes: `data-story-total-columns="${cols[j]}"`,
      row_class: 'row row--vertically-spaced',
    });
  }

  html += `<div class="story-container__title">Column utilities</div>`;

  html += `<div class="story-container__subtitle">Reversed items ${code('.row > .col-[breakpoint]-[column].col--reverse')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => `${placeholder(`${cols[i - 1]}-1`)} ${placeholder(`${cols[i - 1]}-2`)}`),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    template_column_count: cols.length,
    column_class: 'col--reverse',
  });

  html += `<div class="story-container__subtitle">Reversed items ${code('.row > .col.col--reverse')} - Auto column</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => `${placeholder(`${cols[i - 1]}-1`)} ${placeholder(`${cols[i - 1]}-2`)}`),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    column_class: 'col col--reverse',
  });

  html += `<div class="story-container__subtitle">No grow ${code('.row > .col-[breakpoint]-[column].col--no-grow')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    template_column_count: cols.length,
    column_class: 'col--no-grow',
  });

  html += `<div class="story-container__subtitle">No grow ${code('.row > .col.col--no-grow')} - Auto column</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    column_class: 'col col--no-grow',
  });

  html += `<div class="story-container__subtitle">No gap ${code('.row > .col-[breakpoint]-[column].col--no-gap')}</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    template_column_count: cols.length,
    column_class: 'col--no-gap',
  });

  html += `<div class="story-container__subtitle">No gap ${code('.row > .col.col--no-gap')} - Auto column</div>`;
  cols = ['A', 'B', 'C', 'D'];
  html += CivicThemeGrid({
    items: generateItems(cols.length, (i) => placeholder(cols[i - 1])),
    column_attributes: `data-story-total-columns="${cols.length}"`,
    column_class: 'col col--no-gap',
  });

  html += '</div>';

  return html;
};

export const GridGenerator = (parentKnobs = {}) => {
  const knobs = {
    number_of_items: knobNumber(
      'Number of items',
      4,
      {
        range: true,
        min: 0,
        max: 15,
        step: 1,
      },
      parentKnobs.number_of_items,
      parentKnobs.knobTab,
    ),
    template_column_count: parseInt(knobRadios(
      'Template column count',
      {
        'Not set (use auto columns)': '0',
        1: '1',
        2: '2',
        3: '3',
        4: '4',
        6: '6',
        12: '12',
      },
      '12',
      parentKnobs.template_column_count,
      parentKnobs.knobTab,
    ), 10),
    render_as: knobRadios(
      'Render as',
      {
        'div > div': 'divdiv',
        'ul > li': 'ulli',
      },
      'divdiv',
      parentKnobs.render_as,
      parentKnobs.knobTab,
    ),
    container_type: knobRadios(
      'Container type',
      {
        None: '',
        Contained: 'contained',
        Fluid: 'fluid',
      },
      'contained',
      parentKnobs.container_type,
      parentKnobs.knobTab,
    ),
    fill_width: knobBoolean('Fill width', false, parentKnobs.fill_width, parentKnobs.knobTab),
    row_class: knobText('Additional row class', '', parentKnobs.row_class, 'Attributes'),
    row_attributes: knobText('Additional row attributes', '', parentKnobs.row_attributes, 'Attributes'),
    column_class: knobText('Additional column class', '', parentKnobs.column_class, 'Attributes'),
    column_attributes: knobText('Additional column attributes', '', parentKnobs.column_attributes, 'Attributes'),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, 'Attributes'),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, 'Attributes'),
  };

  const showOutlines = knobBoolean('Show outlines', false, parentKnobs.show_outlines, parentKnobs.knobTab);

  const props = {
    items: generateItems(knobs.number_of_items, () => placeholder(Math.floor(12 / (knobs.template_column_count > 0 ? knobs.template_column_count : 12)))),
    row_element: knobs.render_as === 'ulli' ? 'ul' : 'div',
    row_class: knobs.row_class,
    row_attributes: knobs.row_attributes,
    column_element: knobs.render_as === 'ulli' ? 'li' : 'div',
    column_class: knobs.column_class,
    column_attributes: `data-story-total-columns="${knobs.number_of_items}"`,
    use_container: knobs.container_type !== 'none',
    is_fluid: knobs.container_type === 'fluid',
    template_column_count: knobs.template_column_count,
    fill_width: knobs.fill_width,
    attributes: knobs.attributes,
    modifier_class: knobs.modifier_class,
  };

  return shouldRender(parentKnobs) ? `<div class="${showOutlines ? 'story-grid-outlines' : ''}">${CivicThemeGrid(props)}</div>` : knobs;
};
