// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import { getSlots, randomUrl } from '../../00-base/base.stories';
import CivicThemeAttachment from './attachment.twig';

export default {
  title: 'Molecules/Attachment',
};

export const Attachment = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const date = new Date().toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  const links = [
    {
      url: randomUrl(),
      text: 'DOC type document',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'DOCX type document',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'PDF type document',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'PPT type document',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'XLSX type document',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'XLS type document',
      last_updated: date,
      icon: 'download-file',
    },
  ];

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
    title: text('Title', 'Event name which runs across two or three lines', generalKnobTab),
    summary: text('Summary', 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..', generalKnobTab),
    links: boolean('With links', true, generalKnobTab) ? links : null,
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeAttachment({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
