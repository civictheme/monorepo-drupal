import chaiString from 'chai-string';

chai.use(chaiString);

describe('Search', () => {

  beforeEach(() => {
    cy.setUp('standard').applyRecipe();
    // Uninstall core search module.
    cy.drupalLogin('admin');
    cy.visit('/admin/modules/uninstall');
    cy.findByLabelText('Search').check();
    cy.findByDisplayValue('Uninstall').click();
    cy.contains('The following modules will be completely uninstalled from your site', {
      selector: 'form.confirmation',
      exact: false,
    });
    // Click uninstall on confirm page.
    cy.findByDisplayValue('Uninstall').click();
    cy.drupalLogout();
  });

  after(() => {
    cy.tearDown();
  });

  it('does not show excluded or unpublished content', () => {
    const createPage = (title, published, excluded) => {
      cy.visit('/node/add/page');
      cy.findByLabelText('Title').type(title);
      cy.findByText('Search API Exclude', {
        selector: 'details:not(open) > summary',
        exact: false,
      }).click();
      if (excluded) {
        cy.findByLabelText('Prevent this node from being indexed.').check();
      }
      if (!published) {
        cy.findByLabelText('Published').uncheck();
      }
      cy.findByDisplayValue('Save').click();
    };

    cy.drupalLogin('admin');
    createPage('Drupal journey', true, false);
    createPage('Excluded published page', true, true);
    createPage('Excluded unpublished page', false, true);
    createPage('Not excluded unpublished page', false, false);
    cy.drupalLogout();
    cy.visit('/search');
    cy.get('.view-search .views-row').should('have.length', 1)
      .eq(0)
      .find('h2')
      .should('contain.text', 'Drupal journey');
  });

})
