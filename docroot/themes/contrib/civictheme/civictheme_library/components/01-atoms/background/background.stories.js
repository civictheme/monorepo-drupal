// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
import { radios, select } from '@storybook/addon-knobs';

export default {
  title: 'Atoms/Background',
  parameters: {
    layout: 'centered',
  },
};

export const Background = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme', {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const bg = select('Background', Object.keys(BACKGROUNDS[theme]), Object.keys(BACKGROUNDS[theme])[0], generalKnobTab);

  return `<div class="story-backgrounds-wrapper story-wrapper-size--large"><img src="${BACKGROUNDS[theme][bg]}" style="max-width: 100%" alt=""/></div>`;
};
