describe('Deposit and withdraw money', () => {
    it('plays blackjack', () => {
        cy.login()
        cy.deposit()

        cy.visit('http://localhost/')

        cy.get('input[id="bet"]').type('1000')
        cy.get('button').contains('Play').click()

        cy.get('button').contains('End Game').click()
    })
})
