// phpcs:ignoreFile
/**
 * CivicTheme Table of Contents component stories.
 */

import Component from './table-of-contents.twig';
import BasicContent from '../basic-content/basic-content.twig';

const meta = {
  title: 'Molecules/Table Of Contents',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    anchor_selector: {
      control: { type: 'text' },
    },
    scope_selector: {
      control: { type: 'text' },
    },
    position: {
      control: { type: 'radio' },
      options: ['before', 'after', 'prepend', 'append'],
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const TableOfContents = {
  args: {
    theme: 'light',
    title: 'On this page',
    links: [
      {
        title: 'Link 1',
        url: '/',
      },
      {
        title: 'Link 2',
        url: '/',
      },
      {
        title: 'Link 3',
        url: '/',
      },
    ],
    position: 'before',
    modifier_class: '',
    attributes: null,
  },
};

export const TableOfContentsAutomatic = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    title: 'On this page',
    anchor_selector: 'h2',
    scope_selector: '.ct-basic-content',
    position: 'before',
    content: BasicContent({ content: `
      <h2>Heading 2 - 1</h2>
      <p>Ex cillum irure id occaecat aliquip in cillum aute occaecat ullamco in dolore nulla et veniam sed consectetur ut excepteur eu eiusmod excepteur nulla id mollit veniam deserunt ea nostrud.</p>
      <h3>Heading 3</h3>
      <h4>Heading 4</h4>
      <h5>Heading 5</h5>
      <h6>Heading 6</h6>
      <p>Consectetur veniam exercitation voluptate reprehenderit in esse est magna minim sunt cupidatat reprehenderit ut pariatur cillum do aute adipisicing commodo voluptate quis in tempor eu irure velit esse non dolore officia sit cupidatat officia fugiat mollit eu.</p>
      <h2>Heading 2 - 2</h2>
      <p>Est incididunt irure eu elit eiusmod incididunt occaecat labore aute in ad non irure sunt ad ut nostrud commodo do fugiat fugiat tempor occaecat mollit sunt in id sed commodo enim occaecat eu proident nostrud fugiat cupidatat.</p>
      <h2>Heading 2 - 3</h2>
      <p>Nulla sed cupidatat irure quis veniam ut in in pariatur do minim adipisicing minim exercitation magna eiusmod culpa tempor.</p>
      <h2>&nbsp;</h2>
    ` }),
    modifier_class: '',
    attributes: null,
  },
};
