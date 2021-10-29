/* eslint-disable no-new, func-names */

function CivicTableOfContents(el) {
  this.elements = el;
  this.init();
}

CivicTableOfContents.prototype.init = function () {
  // Store all used names to resolve duplicates.
  const listIndex = {};

  this.elements.forEach((elToc, tocIndex) => {
    // Find all selectors in scope.
    const useJS = elToc.dataset.useJs;
    if (useJS === 'true') {
      const { anchors, anchorScope } = elToc.dataset;
      const links = [];

      // Extract links.
      document.querySelectorAll(anchorScope).forEach((elScope) => {
        elScope.querySelectorAll(anchors).forEach((elAnchor) => {
          const existingId = elAnchor.id;
          const anchorText = elAnchor.innerText;
          const anchorId = existingId || this.anchorName(anchorText);
          let count = 0;
          if (listIndex[anchorId]) {
            listIndex[anchorId]++;
            count = listIndex[anchorId];
          } else {
            listIndex[anchorId] = 1;
          }
          const correctedId = count > 0 ? `${anchorId}-${count}` : anchorId;
          links.push({
            title: anchorText,
            url: `#${correctedId}`,
          });
          elAnchor.id = correctedId;
        });
      });

      // Update TOC.
      if (links.length > 0) {
        const elContent = elToc.querySelector('.civic-table-of-contents__content');
        const elTitle = elToc.querySelector('.civic-table-of-contents__title');

        if (elContent) {
          elContent.innerHTML = '';
        }

        const titleId = `civic-table-of-contents-title-${tocIndex}`;
        elTitle.id = titleId;
        let html = `<ul class="civic-table-of-contents__links" aria-labelledby="${titleId}">`;
        links.forEach((link) => {
          html += `
            <li class="civic-table-of-contents__link-item">
              <a class="civic-table-of-contents__link" href="${link.url}">${link.title}</a>
            </li>
          `;
        });
        html += '</ul>';

        // Create and append links to DOM.
        const elTemp = document.createElement('template');
        elTemp.innerHTML = html;
        elContent.append(elTemp.content.firstChild);
      }
    }
  });
};

CivicTableOfContents.prototype.anchorName = function (str) {
  return str.toLowerCase()
    .replace(/(&\w+?;)/gim, ' ')
    .replace(/[_.~"<>%|'!*();:@&=+$,/?%#[\]{}\n`^\\]/gim, '')
    .replace(/(^\s+)|(\s+$)/gim, '')
    .replace(/\s+/gm, '-');
};

const toc = document.querySelectorAll('.civic-table-of-contents');
new CivicTableOfContents(toc);
