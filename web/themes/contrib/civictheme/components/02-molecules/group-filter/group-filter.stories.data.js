import { randomFormElements, randomInt, randomString } from '../../00-base/base.utils';

export default {
  args: (theme = 'light') => {
    const filterCount = 3;
    const filters = [];

    for (let i = 0; i < filterCount; i++) {
      filters.push({
        content: randomFormElements(1, theme, true)[0],
        title: `Filter ${randomString(randomInt(3, 8))} ${i + 1}`,
      });
    }

    return {
      theme,
      title: 'Filter search results by:',
      filters,
      submit_text: 'Apply',
      content_top: '',
      content_bottom: '',
      attributes: '',
      modifier_class: '',
    };
  },
};
