/**
 * Adds data-title attributes to CivicTheme table cells for display on mobile.
 */
Drupal.behaviors.civictheme_table = {
  // eslint-disable-next-line no-unused-vars
  attach: function attach(context, settings) {
    const tables = document.querySelectorAll('.civictheme-basic-content table');
    tables.forEach((table) => {
      if (table.getAttribute('data-table') === 'true') {
        return;
      }

      table.setAttribute('data-table', 'true');

      // Add titles to cells via thead.
      const addTheadColumnTitles = (table) => {
        // Determine whether column titles can be added via thead.
        const theadRows = table.querySelectorAll('thead tr');
        const tbodyRows = table.querySelectorAll('tbody tr');
        if (!(theadRows.length && tbodyRows.length)) {
          return;
        }
        const theadRow = theadRows[0];
        const theadCells = theadRow.querySelectorAll('th, td');

        tbodyRows.forEach((tbodyRow) => {
          const tbodyRowCells = tbodyRow.querySelectorAll('th, td');
          tbodyRowCells.forEach((tbodyRowCell, index) => {
            if (!tbodyRowCell.hasAttribute('data-title')) {
              tbodyRowCell.setAttribute('data-title', theadCells[index].textContent)
            }
          });
        });
      };

      // Add data-title attributes to cells for display on mobile.
      // TODO: Add titles to cells in rows with row-scoped th cells.
      // const addRowScopedTitles = ($table) => {};
      // TODO: Add titles to cells in columns with col-scoped th cells.
      // const addColScopedTitles = ($table) => {};
      const addTitles = (table) => {
        addTheadColumnTitles(table);
      };

      addTitles(table);
    });
  },
};
