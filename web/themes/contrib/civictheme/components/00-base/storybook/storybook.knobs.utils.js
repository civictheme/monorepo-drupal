// phpcs:ignoreFile
//
// Centralised utilities for all Storybook stories.
//
/* eslint max-classes-per-file: 0 */

import { boolean, color, date as dateKnob, number, optionsKnob, radios, select, text } from '@storybook/addon-knobs';

/**
 * Knob wrappers are poor man's story Args with additional functionality.
 *
 * The use case is to allow re-using the same pre-defined story knobs within
 * the child component in the parent component's story. But also allow the
 * parent component story to override the value of the knob or completely
 * suppress the knob from being shown.
 *
 * The wrapper provides a capability for a parent story to call a child
 * component's story:
 * 1. Without any values passed - would render a child component with its
 *    default knob values and no knobs shown.
 * 2. With some values passed - would render a child component with these
 *    values (unspecified values would use the knobs' default values) and
 *    no knobs shown.
 * 3. Without **knob values** passed and wanting to see **all** the knobs -
 *    would render a child component with its default knob values and show
 *    **all** the knobs.
 * 4. Without **knob values** passed and wanting to see **some** knobs - would
 *    render a child component with their default knob values and
 *    show **only the knobs for passed values**.
 * 5. With some **knob values** passed and wanting to see the knobs - would
 *    render a child component with **these knob values** and show the knobs
 *    only for these passed values.
 *
 * @code
 * // Render a child component with its default knob values and no knobs
 * // shown. All the component's properties will use the values set on
 * // the knobs in the child component's story.
 * const component1 = MyComponent(new KnobValues());
 *
 * // Render a child component with `title` set to `My title` and no knobs
 * // shown. Unspecified component's properties will use the values set on
 * // the knobs in the child component's story.
 * const component2 = MyComponent(new KnobValues({
 *   title: 'My title',
 * }));
 *
 * // Render a child component with its default knob values and show
 * // **all** the knobs. The values of the knobs will use the values set on
 * // the knobs in the child component's story.
 * const component3 = MyComponent();
 *
 * // Render a child component with a value from the `Theme` knob set in the
 * // child component's story and show the `Theme` knob with **that** value.
 * // Unspecified component's properties will use the values set on
 * // the knobs in the child component's story.
 * const component3 = MyComponent(new KnobValues({
 *   theme: new KnobValue(),
 * }));
 *
 * // Render a child component with a value `dark` and show the `Theme` knob
 * // with the value `dark`.
 * // Unspecified component's properties will use the values set on
 * // the knobs in the child component's story.
 * const component3 = MyComponent(new KnobValues({
 *   theme: new KnobValue('dark'),
 * }));
 * @endcode
 */

/**
 * Knob value container.
 *
 * If the value is set to null, then the default value of the knob is used and
 * the knob is shown.
 *
 * If the value is set to anything else, then this value is used and the knob is
 * shown.
 *
 * If the value is set to null, and useDefault is set to true, then the default
 * value of the knob is used and the knob is not shown.
 */
export class KnobValue {
  constructor(value = null, useDefault = false) {
    this.value = value;
    this.useDefault = useDefault;
  }

  getValue() {
    return this.value;
  }

  isUsingDefault() {
    return this.useDefault;
  }
}

/**
 * Container for the knob values passed to the stories.
 */
export class KnobValues {
  constructor(knobs = {}, shouldRender = true, parentKnobs = {}) {
    this.knobs = knobs;
    this.parentKnobs = parentKnobs;
    this.shouldRender = shouldRender;

    /* eslint no-constructor-return: 0 */
    return new Proxy(this, {
      get: (target, prop) => {
        if (prop === 'shouldRender') {
          return target.shouldRender;
        }

        if (prop in target.parentKnobs) {
          return target.parentKnobs[prop];
        }

        if (prop in target.knobs) {
          return target.knobs[prop];
        }

        if (prop === 'knobTab') {
          return 'General';
        }

        return new KnobValue(null, true);
      },
    });
  }
}

/**
 * Process values passed to the knob and return a value or render a knob.
 */
export const processKnob = (name, defaultValue, parent, group, knobCallback) => {
  // If parent is undefined, use the default value and render the knob.
  if (parent === undefined) {
    return knobCallback(name, defaultValue, group);
  }

  // If parent is null, a scalar value or an object, use it's value.
  if (parent === null || !(parent instanceof KnobValue)) {
    return parent;
  }

  // If parent is a KnobValue instance set to use the default value, return the
  // default value.
  if (parent && parent.isUsingDefault()) {
    return defaultValue;
  }

  // If parent is a KnobValue instance with a null value, use the default value
  // and render the knob.
  if (parent.getValue() === null) {
    return knobCallback(name, defaultValue, group);
  }

  // Use the value from the KnobValue instance.
  return knobCallback(name, parent.getValue(), group);
};

export const knobText = (name, value, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => text(knobName, knobValue, knobGroup));

export const knobRadios = (name, options, value, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => radios(knobName, options, knobValue, knobGroup));

export const knobBoolean = (name, value, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => boolean(knobName, knobValue, knobGroup));

export const knobNumber = (name, value, options, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => number(knobName, knobValue, options, knobGroup));

export const knobSelect = (name, options, value, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => select(knobName, options, knobValue, knobGroup));

export const knobColor = (name, value, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => color(knobName, knobValue, knobGroup));

export const knobOptions = (name, options, value, optionsObj, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => optionsKnob(knobName, options, knobValue, optionsObj, knobGroup));

export const knobDate = (name, value, parent, group = 'General') => processKnob(name, value, parent, group, (knobName, knobValue, knobGroup) => dateKnob(knobName, knobValue, knobGroup));

/**
 * Render a component if none of the parentKnobs are of KnobValue class.
 *
 * Allows to re-use stories to collect the values without rendering the
 * component.
 *
 * Do not optimize this function - it is laid out in a way that is easy to
 * understand and follow the logic.
 */
export const shouldRender = (parentKnobs) => {
  if (parentKnobs === null || typeof parentKnobs !== 'object') return true;

  if (Object.keys(parentKnobs).length === 0 && parentKnobs.constructor === Object) {
    return true;
  }

  // If the parentKnobs are of KnobValues class, then check the shouldRender
  // flag.
  if (parentKnobs instanceof KnobValues) {
    return parentKnobs.shouldRender;
  }

  let showKnobs = false;
  const knobsValues = Object.values(parentKnobs);

  for (let i = 0; i < knobsValues.length; i++) {
    const value = knobsValues[i];
    if (value instanceof KnobValue) {
      if (value.getValue() !== null) {
        showKnobs = true;
        break;
      }

      if (value.getValue() == null && !value.isUsingDefault()) {
        showKnobs = true;
        break;
      }
    }
  }

  return !showKnobs;
};

export const slotKnobs = (names) => {
  const showSlots = boolean('Show story slots', false, 'Slots');
  const obj = {};

  if (showSlots) {
    for (const i in names) {
      obj[names[i]] = `<div class="story-slot story-slot--${names[i]}"><code>{{ ${names[i]} }}</code></div>`;
    }
  }

  return obj;
};
