// phpcs:ignoreFile
/**
 * Table of Contents component.
 *
 * Attaches to markup with 'data-table-of-contents-position' attribute.
 *
 * Available attributes:
 * - data-table-of-contents-position: Placed on the target element to init TOC.
 *   Values are: before, after, prepend, append. Value defaults to 'before'.
 * - data-table-of-contents-theme (optional): Theme of the TOC. Values are:
 *   light, dark. Defaults to 'light'.
 * - data-table-of-contents-anchor-selector (optional): The selector of the
 *   anchors. Defaults to 'h2'.
 * - data-table-of-contents-anchor-scope-selector (optional): The scope for
 *   the anchors. Defaults to '.ct-basic-content'.
 * - data-table-of-contents-title (optional): The title of TOC.
 */

function CivicThemeTableOfContents(el) {
  // Check if current target is already initialised.
  if (el.hasAttribute('data-table-of-contents-initialised')) {
    return;
  }

  // Get options from attributes.
  this.target = el;
  this.position = this.target.getAttribute('data-table-of-contents-position').trim();
  this.theme = this.target.hasAttribute('data-table-of-contents-theme') ? this.target.getAttribute('data-table-of-contents-theme').trim() : 'light';
  this.anchorSelector = this.target.hasAttribute('data-table-of-contents-anchor-selector') ? this.target.getAttribute('data-table-of-contents-anchor-selector').trim() : 'h2';
  this.anchorScopeSelector = this.target.hasAttribute('data-table-of-contents-anchor-scope-selector') ? this.target.getAttribute('data-table-of-contents-anchor-scope-selector').trim() : '.ct-basic-content';
  this.title = this.target.hasAttribute('data-table-of-contents-title') ? this.target.getAttribute('data-table-of-contents-title').trim() : '';

  // Normalise attribute values.
  this.position = ['before', 'after', 'prepend', 'append'].indexOf(this.position.trim()) > 0 ? this.position : 'before';
  this.theme = this.theme === 'dark' ? 'dark' : 'light';
  this.anchorSelector = this.anchorSelector !== '' ? this.anchorSelector : 'h2';
  this.anchorScopeSelector = this.anchorScopeSelector !== '' ? this.anchorScopeSelector : '.ct-basic-content';

  // Initialise component.
  this.init();

  // Mark target as initialised.
  this.target.setAttribute('data-table-of-contents-initialised', 'true');
}

CivicThemeTableOfContents.prototype.init = function () {
  let html = '';

  const links = this.findLinks(this.anchorSelector, this.anchorScopeSelector);

  if (!links.length) {
    return;
  }

  if (this.title) {
    html += this.renderTitle(this.title);
  }

  html += this.renderLinks(links);

  html = this.renderContainer(html, this.theme, this.position);

  this.place(this.target, this.position, html);
};

CivicThemeTableOfContents.prototype.findLinks = function (anchorSelector, scopeSelector) {
  const links = [];

  // Fins links within provided scope selector.
  document.querySelectorAll(scopeSelector).forEach((elScope) => {
    elScope.querySelectorAll(anchorSelector).forEach((elAnchor) => {
      // Respect existing ID.
      let anchorId = elAnchor.id || null;
      const anchorText = elAnchor.innerText;

      // Generate new ID if no existing ID.
      if (!anchorId || anchorId.length === 0) {
        anchorId = this.makeAnchorId(anchorText);
        // Check if generated ID is already present on the page.
        if (elScope.querySelectorAll(`#${anchorId}`).length) {
          // Add random 3 character suffix.
          anchorId = `${anchorId}-${Math.random().toString(36).substring(2, 5)}`;
        }
      }

      links.push({
        title: anchorText,
        url: `#${anchorId}`,
      });

      // Update anchor with the id. This will "fix" any anchors with duplicated
      // IDs, which is not a valid HTML content.
      elAnchor.id = anchorId;
    });
  });

  return links;
};

CivicThemeTableOfContents.prototype.renderTitle = function (title) {
  return `<h2 class="ct-table-of-contents__title">${title}</h2>`;
};

CivicThemeTableOfContents.prototype.renderLinks = function (links) {
  let html = '';

  html += `<ul class="ct-table-of-contents__links">`;
  for (const i in links) {
    html += `
      <li class="ct-table-of-contents__link-item">
        <a class="ct-table-of-contents__link" href="${links[i].url}">${links[i].title}</a>
      </li>
    `;
  }
  html += '</ul>';

  return html;
};

CivicThemeTableOfContents.prototype.renderContainer = function (html, theme, position) {
  return `<div class="ct-table-of-contents ct-theme-${theme} ct-table-of-contents--position-${position}">${html}</div>`;
};

CivicThemeTableOfContents.prototype.place = function (el, position, html) {
  const positionMap = {
    before: 'beforebegin',
    after: 'afterend',
    prepend: 'afterbegin',
    append: 'beforeend',
  };

  el.insertAdjacentHTML(positionMap[position], html);
};

CivicThemeTableOfContents.prototype.makeAnchorId = function (str) {
  return str.toLowerCase()
    .replace(/(&\w+?;)/gim, ' ')
    .replace(/[_.~"<>%|'!*();:@&=+$,/?%#[\]{}\n`^\\]/gim, '')
    .replace(/(^\s+)|(\s+$)/gim, '')
    .replace(/\s+/gm, '-');
};

document.querySelectorAll('[data-table-of-contents-position]').forEach((el) => {
  new CivicThemeTableOfContents(el);
});
