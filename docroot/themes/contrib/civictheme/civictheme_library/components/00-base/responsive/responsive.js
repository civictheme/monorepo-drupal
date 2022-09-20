// phpcs:ignoreFile
/**
 * @file
 * Responsive component.
 *
 * Emits a 'ct-responsive' event on breakpoint change allowing
 * components to delay initialisation by providing 'data-responsive' attribute
 * with an operator and breakpoint name.
 *
 * For example: a component with `data-responsive=">=m"` attribute will
 * delay its initialisation to happen only when current screen size is equal
 * or more than medium ('m') breakpoint.
 */
function CivicThemeResponsive() {
  const queries = this.getMediaQueries();
  for (const breakpoint in queries) {
    const query = queries[breakpoint];
    // Store matched media queries in global scope as this component is a
    // singleton.
    window.civicthemeResponsive = window.civicthemeResponsive || {};
    // Only proceed if this query was not processed previously.
    if (!(query in window.civicthemeResponsive)) {
      window.civicthemeResponsive[query] = window.matchMedia(query);
      // Support for Safari 13.
      const hasEventListener = (window.civicthemeResponsive[query].addEventListener !== undefined);
      if (hasEventListener) {
        window.civicthemeResponsive[query]
          .addEventListener('change', this.mediaQueryChange.bind(this, breakpoint));
      } else {
        window.civicthemeResponsive[query]
          .addListener(this.mediaQueryChange.bind(this, breakpoint));
      }
    }
    // Call event handler on init.
    this.mediaQueryChange(breakpoint, { matches: window.civicthemeResponsive[query].matches });
  }
}

/**
 * Breakpoints map.
 */
CivicThemeResponsive.prototype.breakpoints = {
  xxs: '0px',
  xs: '368px',
  s: '576px',
  m: '768px',
  l: '992px',
  xl: '1280px',
  xxl: '1440px',
};

/**
 * Get an object of media queries.
 *
 * @return object
 *   Keys are breakpoint names, and values a media queries.
 */
CivicThemeResponsive.prototype.getMediaQueries = function () {
  const queries = {};

  const firstBp = Object.keys(this.breakpoints)[0];
  let lastBp = firstBp;
  for (const breakpoint in this.breakpoints) {
    if (breakpoint === firstBp) {
      continue;
    }
    const min = this.breakpoints[lastBp];
    const max = `${Math.max(parseFloat(this.breakpoints[breakpoint]) - 0.02, 0)}px`;
    if (lastBp === firstBp) {
      queries[lastBp] = `screen and (max-width: ${max})`;
    } else {
      queries[lastBp] = `screen and (min-width: ${min}) and (max-width: ${max})`;
    }
    lastBp = breakpoint;
  }
  queries[lastBp] = `screen and (min-width: ${this.breakpoints[lastBp]})`;

  return queries;
};

/**
 * Event handler for the media query change event.
 *
 * @param {string} breakpoint
 *   The breakpoint name for which this event was fired.
 * @param {Event} evt
 *   The media query change event.
 */
CivicThemeResponsive.prototype.mediaQueryChange = function (breakpoint, evt) {
  if (!evt.matches) {
    return;
  }
  // Fire a custom event that other components can subscribe to.
  window.dispatchEvent(new CustomEvent('ct-responsive', {
    bubbles: true,
    detail: {
      breakpoint,
      evaluate: CivicThemeResponsive.prototype.evaluate,
    },
  }));
};

/**
 * Evaluate breakpoint expression and attach or detach component.
 *
 * @param {string} breakpointExpr
 *   The breakpoint expression.
 * @param {object} func
 *   Function or class constructor.
 * @param {object} el
 *   Element to be passed to the constructor.
 *
 * @return {*}
 *   Attached object or false if expression did not match.
 */
CivicThemeResponsive.prototype.evaluate = function (breakpointExpr, func, el) {
  if (CivicThemeResponsive.prototype.matchExpr(breakpointExpr, this.breakpoint)) {
    // eslint-disable-next-line new-cap
    return new func(el);
  }
  if (typeof func.prototype.destroy !== 'undefined') {
    func.prototype.destroy(el);
    return true;
  }
  return false;
};

/**
 * Match breakpoint expression to the passed breakpoint.
 *
 * Used by the listeners to decide when to respond to a query.
 *
 * @param {string} breakpointExpr
 *   The breakpoint expression. E.g. '>=m', '<s' etc.
 *   Supported operators are: <, >, =, >=, <=, <>. Defaults to '>='.
 *   Breakpoint names are matched to the
 *   CivicThemeResponsive.prototype.breakpoints.
 *
 * @param {string} breakpoint
 *   Currently active breakpoint.
 *
 * @return {boolean}
 *   True if expression matches current breakppint, false otherwise.
 */
CivicThemeResponsive.prototype.matchExpr = function (breakpointExpr, breakpoint) {
  const names = Object.keys(CivicThemeResponsive.prototype.breakpoints);
  // Parse breakpoint expression into name and operator.
  const regex = `^(<|>|=|>=|<=|<>)?(${names.join('|')})$`;
  const matches = breakpointExpr.match(new RegExp(regex, 'i'));

  // If not matched (malformed expression) or not exactly expected number of
  // matches - consider as a non-match.
  if (!matches || matches.length < 2 || matches.length > 3) {
    return false;
  }

  // Can be with or without an operator, i.e. '>=m' or 'm'.
  const parsedOperator = matches[1] || '>=';
  const parsedBreakpoint = matches[2];

  const compFunctions = {
    '>': (parsed, current) => names.indexOf(current) > names.indexOf(parsed),
    '>=': (parsed, current) => names.indexOf(current) >= names.indexOf(parsed),
    '<': (parsed, current) => names.indexOf(current) < names.indexOf(parsed),
    '<=': (parsed, current) => names.indexOf(current) <= names.indexOf(parsed),
    '<>': (parsed, current) => names.indexOf(current) !== names.indexOf(parsed),
    '=': (parsed, current) => names.indexOf(current) === names.indexOf(parsed),
  };

  return compFunctions[parsedOperator](parsedBreakpoint, breakpoint);
};

if (document.querySelectorAll('[data-responsive]').length) {
  // CivicThemeResponsive needs to run after all ct-responisve
  // event listeners have been added.
  // Delay the execution until after other components have been initialized.
  // Using setTimeout as an interim solution because:
  // - DOMContentLoad won't work on prod-site due to being double wrapped in a
  //   DOMLoad event.
  // - window 'load' event won't work on storybook as it's not called per
  //   component page change.
  setTimeout(() => {
    // Init if there is at least a single component with data-responsive
    // attribute on the page.
    new CivicThemeResponsive();
  }, 10);
}
