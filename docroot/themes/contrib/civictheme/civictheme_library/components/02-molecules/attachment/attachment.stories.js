// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import {getSlots, randomSentence, randomUrl} from '../../00-base/base.utils';
import CivicThemeAttachment from './attachment.twig';

export default {
  title: 'Molecules/Attachment',
  parameters: {
    layout: 'fullscreen',
  },
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
      ext: 'doc',
      size: '42.88 KB',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'DOCX type document',
      ext: 'docx',
      size: '32.48 KB',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'PDF type document',
      ext: 'pdf',
      size: '42.82 KB',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'PPT type document',
      ext: 'ppt',
      size: '12.88 KB',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'XLSX type document',
      ext: 'xlsx',
      size: '34.45 KB',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'XLS type document',
      ext: 'xls',
      size: '65.67 KB',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'XLS type document',
      size: '65.67 KB',
      last_updated: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      text: 'XLS type document',
      ext: 'XLS',
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
    title: text('Title', 'Attachments', generalKnobTab),
    content: text('Content', randomSentence(), generalKnobTab),
    links: boolean('With links', true, generalKnobTab) ? links : null,
    with_background: boolean('With background', false, generalKnobTab),
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
