// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import { getSlots, randomSentence, randomUrl } from '../../00-base/base.utils';
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

  const files = [
    {
      url: randomUrl(),
      name: 'Document.doc',
      ext: 'doc',
      size: '42.88 KB',
      created: date,
      changed: date,
      icon: 'word-file',
    },
    {
      url: randomUrl(),
      name: 'Document.doc',
      ext: 'docx',
      size: '32.48 KB',
      created: date,
      changed: date,
      icon: 'word-file',
    },
    {
      url: randomUrl(),
      name: 'Document.pdf',
      ext: 'pdf',
      size: '42.82 KB',
      created: date,
      changed: date,
      icon: 'pdf-file',
    },
    {
      url: randomUrl(),
      name: 'Document.ppt',
      ext: 'ppt',
      size: '12.88 KB',
      created: date,
      changed: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      name: 'Document.xlsx',
      ext: 'xlsx',
      size: '34.45 KB',
      created: date,
      changed: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      name: 'Document.xls',
      ext: 'xls',
      size: '65.67 KB',
      created: date,
      changed: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      name: 'Document.xls',
      size: '65.67 KB',
      created: date,
      changed: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      name: 'Document.xls',
      ext: 'XLS',
      created: date,
      changed: date,
      icon: 'download-file',
    },
    {
      url: randomUrl(),
      name: 'Document.xls',
      created: date,
      changed: date,
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
    files: boolean('With files', true, generalKnobTab) ? files : null,
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
