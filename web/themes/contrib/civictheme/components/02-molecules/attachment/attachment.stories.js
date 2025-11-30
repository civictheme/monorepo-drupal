// phpcs:ignoreFile
import { convertDate, knobBoolean, knobRadios, knobText, randomSentence, randomUrl, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import CivicThemeAttachment from './attachment.twig';

export default {
  title: 'Molecules/Attachment',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Attachment = (parentKnobs = {}) => {
  const date = convertDate(null);

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
    title: knobText('Title', 'Attachments', parentKnobs.title, parentKnobs.knobTab),
    content: knobText('Content', randomSentence(), parentKnobs.content, parentKnobs.knobTab),
    files: knobBoolean('With files', true, parentKnobs.with_files, parentKnobs.knobTab) ? files : null,
    with_background: knobBoolean('With background', false, parentKnobs.with_background, parentKnobs.knobTab),
    vertical_spacing: knobRadios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      parentKnobs.vertical_spacing,
      parentKnobs.knobTab,
    ),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeAttachment({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : knobs;
};
