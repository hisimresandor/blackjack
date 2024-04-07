describe('Deposit and withdraw money', () => {
    it('deposits money', () => {
        cy.login()

        cy.get('a').contains('Balance').click()
        cy.url().should('contain', '/balance')

        let originalBalance = '';
        cy.get('h2').contains('HUF').invoke('text').then((text) => {
            originalBalance = text.slice(0, -4);
        });

        cy.get('.v-field__field').find('[for="deposit"]').parent().click()

        cy.get('.v-list-item-title').contains('1000').click()

        cy.get('button').contains('Deposit').click()

        cy.intercept('http://localhost/balance').as('page');
        cy.wait('@page');

        let newBalance = '';
        cy.get('h2').contains('HUF').invoke('text').then((text) => {
            newBalance = text.slice(0, -4);
            expect(parseInt(newBalance)).to.be.eq(parseInt(originalBalance)+1000);
        });
    })

    it('withdraws money', () => {
        cy.login()

        cy.get('a').contains('Balance').click()
        cy.url().should('contain', '/balance')

        let originalBalance = '';
        cy.get('h2').contains('HUF').invoke('text').then((text) => {
            originalBalance = text.slice(0, -4);
        });

        cy.get('.v-field__field').find('[for="withdraw"]').parent().click()

        cy.get('.v-list-item-title').contains('1000').click()

        cy.get('button').contains('Withdraw').click()

        cy.intercept('http://localhost/balance').as('page');
        cy.wait('@page');

        let newBalance = '';
        cy.get('h2').contains('HUF').invoke('text').then((text) => {
            newBalance = text.slice(0, -4);
            expect(parseInt(newBalance)).to.be.eq(parseInt(originalBalance)-1000);
        });
    })
})
