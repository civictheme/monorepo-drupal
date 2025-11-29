// phpcs:ignoreFile
/**
 * @file
 * Layout component.
 */
function CivicThemeLayout(el) {
  this.el = el;
  this.grid = el.querySelector(':scope > .ct-layout__inner');
  const gridStyle = getComputedStyle(this.grid);

  if (gridStyle.gridTemplateRows === 'masonry' || this.grid.hasAttribute('data-masonry')) {
    return;
  }

  this.grid.setAttribute('data-masonry', true);

  this.stl = this.grid.querySelector(':scope > .ct-layout__sidebar_top_left');
  this.str = this.grid.querySelector(':scope > .ct-layout__sidebar_top_right');
  this.sbl = this.grid.querySelector(':scope > .ct-layout__sidebar_bottom_left');
  this.sbr = this.grid.querySelector(':scope > .ct-layout__sidebar_bottom_right');

  // Only enable masonry if all 4 elements are present.
  if (this.stl && this.str && this.sbl && this.sbr) {
    // Prepare redraw variables.
    this.gap = parseFloat(gridStyle.gridRowGap);
    // Items include all children of the grid, not just the 4 sidebar regions.
    this.items = Array.from(this.grid.children);
    this.height = 0;

    // Listen for redraw events.
    this.resizeObserver = new ResizeObserver(() => {
      requestAnimationFrame(() => {
        this.masonryRedraw();
      });
    });

    // Observe all children of the grid items rather than the items themselves:
    // this allows us to detect changes in the height of the children rather
    // tnan of the grid items as their height will not change when children
    // combined heights is less than a single grid row height.
    this.items.forEach((item) => {
      Array.from(item.children).forEach((child) => {
        this.resizeObserver.observe(child);
      });
    });

    this.masonryRedraw();
  }
}

/**
 * Position element in relation to it's above element.
 */
CivicThemeLayout.prototype.masonryPositionElement = function (el, aboveEl, gap) {
  const aboveChildIdx = aboveEl.children.length - 1;
  const aboveChild = (aboveChildIdx >= 0) ? aboveEl.children[aboveChildIdx] : null;
  const aboveBottom = aboveChild ? aboveChild.getBoundingClientRect().bottom : aboveEl.getBoundingClientRect().top;
  const currentTop = el.getBoundingClientRect().top;
  el.style.marginTop = `${aboveBottom + gap - currentTop}px`;
};

/**
 * Reposition grid elements.
 */
CivicThemeLayout.prototype.masonryRedraw = function () {
  // Calculate the new height of all children.
  //
  // Although masonry layout is applied only if the element has the
  // CSS variable --js-masonry-enabled set and we could have check for this
  // variable to preserve height reclaulation, this variable can be assigned
  // within a specific media query. Therefore, we need to calculate the height
  // in case --js-masonry-enabled is assigned to the element after the viewport
  // has been resized.
  const newHeight = this.items.reduce((totalHeight, item) => {
    const childrenHeight = Array.from(item.children).reduce((childTotal, child) => childTotal + child.getBoundingClientRect().height, 0);
    return totalHeight + childrenHeight;
  }, 0);

  // Proceed only if the height has changed.
  if (newHeight !== this.height) {
    this.height = newHeight;

    // Clear existing positioning.
    this.sbl.style.removeProperty('margin-top');
    this.sbr.style.removeProperty('margin-top');

    // Set new position (if masonry css has been applied).
    if (getComputedStyle(this.grid).getPropertyValue('--js-masonry-enabled')) {
      this.masonryPositionElement(this.sbl, this.stl, this.gap);
      this.masonryPositionElement(this.sbr, this.str, this.gap);
    }
  }
};

document.querySelectorAll('.ct-layout').forEach((layout) => {
  // eslint-disable-next-line no-new
  new CivicThemeLayout(layout);
});
