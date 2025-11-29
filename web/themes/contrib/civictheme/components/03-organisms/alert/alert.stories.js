// phpcs:ignoreFile
import { button } from '@storybook/addon-knobs';
import CivicThemeAlert from './alert.twig';
import { knobNumber, knobRadios, knobText, randomText, shouldRender } from '../../00-base/storybook/storybook.utils';
import './alert';

export default {
  title: 'Organisms/Alert',
  parameters: {
    layout: 'fullscreen',
    docs: 'Dismiss alerts by clicking on [X] button.<br/><br/>Navigate to another component and return here to assert that dismissed alerts do not appear.<br/><br/>Dismissed alerts will be revealed if their content was updated. Change payload to "Updated" to see dismissed alerts appear again.<br/><br/>Press "Clear cookie" button to clear alert dismissal settings.',
    docsPlacement: 'after',
  },
};

export const Alert = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    type: knobRadios(
      'Type',
      {
        Information: 'information',
        Error: 'error',
        Warning: 'warning',
        Success: 'success',
      },
      'information',
      parentKnobs.type,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'Site information', parentKnobs.title, parentKnobs.knobTab),
    description: knobText('Description', `Alert description ${randomText()}`, parentKnobs.description, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  const numOfAlerts = knobNumber(
    'Number of alerts',
    1,
    {
      range: true,
      min: 1,
      max: 5,
      step: 1,
    },
    parentKnobs.number_of_alerts,
    parentKnobs.knobTab,
  );

  const combinedKnobsArray = [];
  for (let i = 0; i < numOfAlerts; i++) {
    combinedKnobsArray.push({
      ...knobs,
      id: i,
    });
  }

  if (shouldRender(parentKnobs)) {
    let html = '';
    for (let i = 0; i < combinedKnobsArray.length; i++) {
      html += CivicThemeAlert(combinedKnobsArray[i]);
    }
    return html;
  }

  return combinedKnobsArray;
};

export const AlertApi = (parentKnobs = {}) => {
  const endpointType = knobRadios(
    'Payload',
    {
      Default: 'default',
      Updated: 'updated',
      Invalid: 'invalid',
    },
    'default',
    parentKnobs.endpoint_type,
    parentKnobs.knobTab,
  );

  let endpoint;
  switch (endpointType) {
    case 'updated':
      endpoint = 'api/alerts2.json';
      break;

    case 'invalid':
      endpoint = 'api/alerts3.json';
      break;

    default:
      endpoint = 'api/alerts1.json';
  }

  button('Clear cookie', () => {
    document.cookie = 'ct-alert-hide=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  }, 'General');

  return shouldRender(parentKnobs)
    ? `<div data-component-name="ct-alerts" data-alert-endpoint="${endpoint}" data-test-path="/"></div>`
    : '';
};
