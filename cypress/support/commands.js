// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

Cypress.Commands.add('login', () => {
    cy.visit('http://localhost/login')

    cy.get('input[id="email"]').type('test@mail.com');
    cy.get('input[id="password"]').type('password{enter}');

    cy.url().should('eq', 'http://localhost/')
})

Cypress.Commands.add('deposit', () => {
    cy.get('a').contains('Balance').click()
    cy.url().should('contain', '/balance')

    cy.get('.v-field__field').find('[for="deposit"]').parent().click()
    cy.get('.v-list-item-title').contains('1000').click()
    cy.get('button').contains('Deposit').click()
})
