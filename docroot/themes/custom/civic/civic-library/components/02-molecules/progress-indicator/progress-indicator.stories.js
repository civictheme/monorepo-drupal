import {radios, select, text} from '@storybook/addon-knobs'
import CivicProgressIndicator from "./progress-indicator.twig";
import {CivicIconSet} from "../../00-base/icons.component.js";

/**
 * Storybook Definition.
 */
export default {
  title: 'Molecule/ProgressIndicator',
  component: CivicProgressIndicator,
};

export const ProgressIndicator = () => {
  //Knob tabs order is decided on the basis of their order in story.
  //Icon component parameters.
  const sheets = Array.from(CivicIconSet.spritesheets);
  const spritesheet = select('Icon Pack', sheets, '/icons/civic-user-interface.svg', 'Theme');
  const icons = CivicIconSet.icons;

  let status = [
    'Status',
    {
      'Doing': 'doing',
      'To do': 'todo',
      'Done': 'done',
    },
  ]

  return CivicProgressIndicator({
    theme: radios(
      'Theme',
      {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
      'Theme',
    ),
    spritesheet: spritesheet,
    steps: [
      {
        title: text('Title', 'Step title 1', 'Step 1'),
        status: radios(...status, 'todo', 'Step 1'),
        symbol: select('Symbol', icons[spritesheet], 'user-interface-todo', 'Step 1'),
      },
      {
        title: text('Title', 'Step title 2', 'Step 2'),
        status: radios(...status, 'doing', 'Step 2'),
        symbol: select('Symbol', icons[spritesheet], 'user-interface-progress', 'Step 2'),
      },
      {
        title: text('Title', 'Step title 3', 'Step 3'),
        status: radios(...status, 'done', 'Step 3'),
        symbol: select('Symbol', icons[spritesheet], 'user-interface-approve', 'Step 3'),
      },
    ]
  });
}
