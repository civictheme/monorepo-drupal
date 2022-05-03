/**
 * @file
 *
 * Demo button story.
 *
 * This file provides explanations on what makes up a storybook story file
 * and how this displays in storybook.
 *
 * We use the HTML version of storybook to generate stories - an introduction to
 * this type of storybook can be found here:
 * https://storybook.js.org/docs/html/get-started/whats-a-story
 */

/**
 * Adding knob controls to stories
 * @see https://storybook.js.org/addons/storybook-addon-knobs-color-options
 * for documentation and options
 */
import { boolean, radios, text } from '@storybook/addon-knobs';

/**
 * Importing the template file in for use in generating the html for storybook.
 */
import CustomDemoButton from './demo-button.twig';

/**
 * Importing the Component JS library for use within the story.
 */
import './demo-button';

/**
 * Provides meta data to storybook, allowing a variety of layout options
 * for the story to be passed to storybook.
 *
 * Below is an outline of the options that can be passed to storybook.
 * @code
 * {
 *   title: '(Atoms|Molecules|Organisms|Templates|Pages)/<Component Name | Component grouping>'
 *   parameters: {
 *     layout: '(centered|fullscreen)'
 *   }
 * }
 * @endcode
 */
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
  /**
   * CivicTheme follows a naming system that non-categorised knobs are shown
   * on the general tab.
   *
   * Note the use of the different controls available for configuring
   * components.
   */
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  /**
   * General knobs - all configurable options that modify the component should
   * be shown in the general tab.
   *
   * You may use other tabs to further configure the content of the component
   * and wish to group these options separately.
   *
   * All components should have the `theme` key specified below with the options
   * light and dark to control light and dark themed components.
   *
   * Components should also generally specify a `modifier_class` so that
   * different classes can be passed to the component in the form ]
   * `<component class>--<modifier>` to provide different presentation scss
   * styles.
   */
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
    modifier_class: [
      'civictheme-button--demo',
      radios(
        'Type',
        {
          Primary: 'civictheme-button--primary',
          'Primary Accent': 'civictheme-button--primary-accent',
          Secondary: 'civictheme-button--secondary',
          'Secondary Accent': 'civictheme-button--secondary-accent',
        },
        'civictheme-button--primary',
        generalKnobTab,
      ),
      radios(
        'Size',
        {
          Large: 'civictheme-button--large',
          Normal: 'civictheme-button--normal',
          Small: 'civictheme-button--small',
        },
        'civictheme-button--normal',
        generalKnobTab,
      ),
    ].join(' '),
    text: text('Text', 'Button Text', generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
  };

  /**
   * After generating the options for the component we return the component
   * which is displayed in the story canvas.
   */
  return CustomDemoButton({
    ...generalKnobs,
  });
};
