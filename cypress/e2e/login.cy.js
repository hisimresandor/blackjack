describe('Login Page', () => {
    it('logs in test user', () => {
        cy.visit('http://localhost/')
        cy.get('input[id="email"]').type('test@mail.com');
        cy.get('input[id="password"]').type('password{enter}');

        cy.url().should('eq', 'http://localhost/')
    })
})
