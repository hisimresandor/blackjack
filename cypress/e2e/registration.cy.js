describe('Registration', () => {
    it('registrates test user', () => {
        cy.visit('http://localhost/register')
        cy.get('input[id="name"]').type('Test User');
        cy.get('input[id="email"]').type('test@mail.com');
        cy.get('input[id="password"]').type('password');
        cy.get('input[id="password_confirmation"]').type('password{enter}');

        cy.url().should('eq', 'http://localhost/')
    })
})
