// phpcs:ignoreFile
import {
  button, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeAlert from './alert.twig';

export default {
  title: 'Organisms/Alert',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Alert = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
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
    type: radios(
      'Type',
      {
        Information: 'information',
        Error: 'error',
        Warning: 'warning',
        Success: 'success',
      },
      'information',
      generalKnobTab,
    ),
    title: text('Title', 'Site information', generalKnobTab),
    description: text('Description', 'Alert description filium morte multavit si sine causa, nollem me tamen laudandis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus vel elit laoreet, dignissim arcu sit amet, vulputate risus.', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const numOfAlerts = number(
    'Number of alerts',
    1,
    {
      range: true,
      min: 1,
      max: 5,
      step: 1,
    },
    generalKnobTab,
  );
  let html = '';
  for (let i = 0; i < numOfAlerts; i++) {
    html += CivicThemeAlert({
      ...generalKnobs,
      id: i,
    });
  }

  return html;
};

export const AlertApi = () => {
  const endpointType = radios(
    'Payload',
    {
      Default: 'default',
      Updated: 'updated',
      Invalid: 'invalid',
    },
    'default',
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
  });

  let docs = '';
  docs += 'Dismiss alerts by clicking on [X] button.<br/><br/>';
  docs += 'Navigate to another component and return here to assert that dismissed alerts do not appear.<br/><br/>';
  docs += 'Dismissed alerts will be revealed if their content was updated. Change payload to "Updated" to see dismissed alerts appear again.<br/><br/>';
  docs += 'Press "Clear cookie" button to clear alert dismissal settings.';

  return `<div data-component-name="ct-alerts" data-alert-endpoint="${endpoint}" data-test-path="/"></div><div class="docs-container docs-container--large"><div class="docs-container__content">${docs}</div></div>`;
};
