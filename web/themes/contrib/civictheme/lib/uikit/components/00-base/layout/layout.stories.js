// phpcs:ignoreFile
import './layout';
import CivicThemeLayout from './layout.twig';
import { knobBoolean, knobRadios, knobText, placeholder, randomInt, shouldRender, slotKnobs } from '../storybook/storybook.utils';

export default {
  title: 'Base/Layout',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Layout = (parentKnobs = {}) => {
  const useLargePlaceholders = knobBoolean('Use large placeholders', false, parentKnobs.use_large_placeholders, parentKnobs.knobTab);

  const knobs = {
    sidebar_top_left: knobBoolean('Show top left sidebar', true, parentKnobs.sidebar_top_left, parentKnobs.knobTab) ? placeholder('Top left sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
    sidebar_bottom_left: knobBoolean('Show bottom left sidebar', true, parentKnobs.sidebar_bottom_left, parentKnobs.knobTab) ? placeholder('Bottom left sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
    hide_sidebar_left: knobBoolean('Hide left sidebar', false, parentKnobs.hide_sidebar_left, parentKnobs.knobTab),
    content: knobBoolean('Show content', true, parentKnobs.content, parentKnobs.knobTab) ? placeholder('Content', useLargePlaceholders ? randomInt(500, 1000) : 0) : '',
    sidebar_top_right: knobBoolean('Show top right sidebar', true, parentKnobs.sidebar_top_right, parentKnobs.knobTab) ? placeholder('Top right sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
    sidebar_bottom_right: knobBoolean('Show bottom right sidebar', true, parentKnobs.sidebar_bottom_right, parentKnobs.knobTab) ? placeholder('Bottom right sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
    hide_sidebar_right: knobBoolean('Hide right sidebar', false, parentKnobs.hide_sidebar_right, parentKnobs.knobTab),
    is_contained: knobBoolean('Is contained', false, parentKnobs.is_contained, parentKnobs.knobTab),
  };

  const showNested = knobBoolean('Show nested layout', false, parentKnobs.show_nested_layout, parentKnobs.knobTab);

  // Show nested only if parent content is shown.
  knobs.content = knobs.content && showNested ? CivicThemeLayout({
    ...{
      sidebar_top_left: knobBoolean('Show nested top left sidebar', true, parentKnobs.nested_sidebar_top_left, parentKnobs.knobTab) ? placeholder('Nested top left sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
      sidebar_top_left_attributes: 'data-story-nested-layout',
      sidebar_bottom_left: knobBoolean('Show nested bottom left sidebar', true, parentKnobs.nested_sidebar_bottom_left, parentKnobs.knobTab) ? placeholder('Nested bottom left sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
      sidebar_bottom_left_attributes: 'data-story-nested-layout',
      content: knobBoolean('Show nested content', true, parentKnobs.nested_content, parentKnobs.knobTab) ? placeholder('Nested content', useLargePlaceholders ? randomInt(500, 1000) : 0) : '',
      content_attributes: 'data-story-nested-layout',
      sidebar_top_right: knobBoolean('Show nested top right sidebar', true, parentKnobs.nested_sidebar_top_right, parentKnobs.knobTab) ? placeholder('Nested top right sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
      sidebar_top_right_attributes: 'data-story-nested-layout',
      sidebar_bottom_right: knobBoolean('Show nested bottom right sidebar', true, parentKnobs.nested_sidebar_bottom_right, parentKnobs.knobTab) ? placeholder('Nested bottom right sidebar', useLargePlaceholders ? randomInt(30, 100) : 0) : '',
      sidebar_bottom_right_attributes: 'data-story-nested-layout',
      attributes: 'data-story-nested-layout',
    },
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : knobs.content;

  const showOutlines = knobBoolean('Show outlines', false, parentKnobs.show_outlines, parentKnobs.knobTab);

  knobs.vertical_spacing = knobRadios(
    'Vertical spacing',
    {
      None: 'none',
      Top: 'top',
      Bottom: 'bottom',
      Both: 'both',
    },
    'none',
    parentKnobs.vertical_spacing,
    parentKnobs.knobTab,
  );

  const attributesTab = 'Attributes';
  knobs.sidebar_top_left_attributes = knobs.sidebar_top_left ? knobText('Top left sidebar attributes', '', parentKnobs.sidebar_top_left_attributes, attributesTab) : '';
  knobs.sidebar_bottom_left_attributes = knobs.sidebar_bottom_left ? knobText('Bottom left sidebar attributes', '', parentKnobs.sidebar_bottom_left_attributes, attributesTab) : '';
  knobs.content_attributes = knobs.content ? knobText('Content attributes', '', parentKnobs.content_attributes, attributesTab) : '';
  knobs.sidebar_top_right_attributes = knobs.sidebar_top_right ? knobText('Top right sidebar attributes', '', parentKnobs.sidebar_top_right_attributes, attributesTab) : '';
  knobs.sidebar_bottom_right_attributes = knobs.sidebar_bottom_right ? knobText('Bottom right sidebar attributes', '', parentKnobs.sidebar_bottom_right_attributes, attributesTab) : '';
  knobs.modifier_class = knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab);

  return shouldRender(parentKnobs)
    ? `<div class="${showOutlines ? 'story-layout-outlines' : ''}">${CivicThemeLayout({
      ...knobs,
      ...slotKnobs([
        'content_top',
        'content_bottom',
      ]),
    })}</div>`
    : knobs;
};
