/* eslint-disable no-new, func-names */

function CivicTableOfContents(el) {
  this.el = el;
  this.init();
}

CivicTableOfContents.prototype.init = function () {
  // Find all selectors in scope.
  const selectors = 'h2';
  const scope = '.toc-scope';
  const links = [];
  const linkNames = {};

  // Extract links.
  document.querySelectorAll(scope).forEach((elScope) => {
    elScope.querySelectorAll(selectors).forEach((elHeading) => {
      const existingId = elHeading.id;
      const anchorText = elHeading.innerText;
      const anchorId = existingId || this.anchorName(anchorText);
      let count = 0;
      if (linkNames[anchorId]) {
        linkNames[anchorId]++;
        count = linkNames[anchorId];
      } else {
        linkNames[anchorId] = 1;
      }
      const correctedId = count > 0 ? `${anchorId}-${count}` : anchorId;
      links.push({
        text: anchorText,
        url: `#${correctedId}`,
      });
      elHeading.id = correctedId;
    });
  });

  // Update TOC.
  if (links.length > 0) {
    const linkWrapper = this.el.querySelector('.civic-table-of-contents__links');
    if (linkWrapper) {
      linkWrapper.remove();
    }
    const titleId = 'civic-table-of-contents-title';
    let html = `<ul class="civic-table-of-contents__links" aria-labelledby="${titleId}">`;
    links.forEach((link) => {
      html += `
        <li class="civic-table-of-contents__link-item">
          <a class="civic-table-of-contents__link" href="${link.url}">${link.text}</a>
        </li>
      `;
    });
    html += '</ul>';
    const temp = document.createElement('template');
    temp.innerHTML = html;
    this.el.append(temp.content.firstChild);
  }
};

CivicTableOfContents.prototype.anchorName = function (str) {
  return str.toLowerCase()
    .replace(/(&\w+?;)/gim, ' ')
    .replace(/[_.~"<>%|'!*();:@&=+$,/?%#[\]{}\n`^\\]/gim, '')
    .replace(/(^\s+)|(\s+$)/gim, '')
    .replace(/\s+/gm, '-');
};

document.querySelectorAll('.civic-table-of-contents').forEach((toc) => {
  new CivicTableOfContents(toc);
});
