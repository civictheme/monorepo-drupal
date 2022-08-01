// phpcs:ignoreFile
/**
 * @file
 * Table component.
 */
function CivicTable(el) {
  if (!el) {
    return;
  }

  this.el = el;

  this.init();
}

// eslint-disable-next-line func-names
CivicTable.prototype.init = function () {
  if (this.el.getAttribute('data-table') === 'true') {
    return;
  }

  this.addTitles();

  this.el.setAttribute('data-table', 'true');
};

// Add data-title attributes to cells for display on mobile.
// TODO: Add titles to cells in rows with row-scoped th cells.
// CivicTable.prototype.addRowScopedTitles.
// TODO: Add titles to cells in columns with col-scoped th cells.
// CivicTable.prototype.addColScopedTitles.
CivicTable.prototype.addTitles = function () {
  this.addTheadColumnTitles();
};

// eslint-disable-next-line func-names
CivicTable.prototype.addTheadColumnTitles = function () {
  // Determine whether column titles can be added via thead.
  const theadRows = this.el.querySelectorAll('thead tr');
  const tbodyRows = this.el.querySelectorAll('tbody tr');
  if (!(theadRows.length && tbodyRows.length)) {
    return;
  }
  const theadRow = theadRows[0];
  const theadCells = theadRow.querySelectorAll('th, td');

  tbodyRows.forEach((tbodyRow) => {
    const tbodyRowCells = tbodyRow.querySelectorAll('th, td');
    tbodyRowCells.forEach((tbodyRowCell, index) => {
      if (!tbodyRowCell.hasAttribute('data-title')) {
        tbodyRowCell.setAttribute('data-title', theadCells[index].textContent);
      }
    });
  });
};

document.querySelectorAll('.civictheme-basic-content table, .civictheme-table').forEach((table) => {
  // eslint-disable-next-line no-new
  new CivicTable(table);
});
