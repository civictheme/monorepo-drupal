// phpcs:ignoreFile
/**
 * @file
 * Demo Button stories.
 *
 * This file provides explanations on what makes up a Storybook story file
 * and how this displays in Storybook.
 *
 * We use the HTML version of Storybook to generate stories - an introduction to
 * this type of Storybook can be found here:
 * https://storybook.js.org/docs/html/get-started/whats-a-story
 */

// Adding knob controls to stories
// @see https://storybook.js.org/addons/storybook-addon-knobs-color-options
import { boolean, radios, text } from '@storybook/addon-knobs';

// Importing the template file for use in generating the HTML.
import CustomDemoButton from './demo-button.twig';

// Importing this component's JS library for use within this story.
import './demo-button';

//
// Story metadata.
//
// @code
// {
//   title: '(Atoms|Molecules|Organisms|Templates|Pages)/<Component Name | Component grouping>'
//   parameters: {
//     layout: '(centered|fullscreen)'
//   }
// }
// @endcode
//
export default {
  title: 'Atoms/Demo Button',
  parameters: {
    layout: 'centered',
  },
};

/**
 * The story template and knobs for showing variants of the component.
 */
export const DemoButton = (knobTab) => {
  // Define a name for a tab with most common properties, but allow to override
  // with a custom name passed in knobTab. Useful when a component's story is
  // used within another component.
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';//*

  // General knobs - all configurable component properties are listed here.
  //
  // You may use other tabs to further configure the content of the component
  // and wish to group these options separately.
  //
  // Values returned by knobs are used as properties (twig variables) when a
  // component is rendered in Storybook.
  //
  // All components should have the 'theme' key specified below with
  // 'light' and 'dark' options to control Light and Dark theme of a component
  // visual presentation.
  //
  // Components should also provide 'modifier_class' property so that custom
  // additional classes could be passed to the component, if required.
  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    text: text('Text', 'Button Text', generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  // After generating the options for the component we return the component
  // which is displayed in the story canvas.
  return CustomDemoButton({
    ...generalKnobs,
  });
};
