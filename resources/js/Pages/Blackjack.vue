<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from 'vue';
import DangerButton from "@/Components/DangerButton.vue";
import { reactive } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import axios from 'axios';

const user = usePage().props.auth.user;
let balance = ref(user.balance)

function generate_cards() {
    let cards = []

    let card_numbers = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K']
    let card_suits = ['clubs', 'diamonds', 'hearts', 'spades']

    card_suits.forEach(suit => {
        card_numbers.forEach(number => {
            let value = number
            if (number == 'J' || number == 'Q' || number == 'K') {
                value = '10'
            }
            let character = suit.charAt(0).toUpperCase()
            cards.push([number, value, `/assets/img/cards/${number}-${character}.png`])
        })
    })

    return cards
}

let open = ref(false)

let player = reactive([])
let dealer = []

let player_value = 0
let dealer_value = 0

let deck = []

let end = reactive(ref(false))

const startGame = () => {
    form.bet = parseInt(form.bet)
    if (form.bet > user.balance) {
        form.bet = user.balance
    } else if (form.bet < 0) {
        form.bet = 0
    }
    user.balance -= form.bet

    let cards = generate_cards()

    let i = 0;
    let len = cards.length

    while(i < len) {
        let random = Math.floor(Math.random() * cards.length)
        deck.push(cards[random])
        cards.splice(random, 1)
        i++
    }

    for (let i = 0; i < 2; i++) {
        let random = Math.floor(Math.random() * deck.length)
        player.push(deck[random])
        deck.splice(random, 1)

        random = Math.floor(Math.random() * deck.length)
        dealer.push(deck[random])
        deck.splice(random, 1)
    }
    let player_aces = 0
    let dealer_aces = 0
    player.forEach(card => {
        if (card[1] != "A") {
            player_value += parseInt(card[1])
        } else {
            player_aces++
        }
    })
    for (let i = 0; i < player_aces; i++) {
        if (player_value + 11 <= 21) {
            player_value += 11
        } else {
            player_value++
        }
    }

    dealer.forEach(card => {
        if (card[1] != "A") {
            dealer_value += parseInt(card[1])
        } else {
            dealer_aces++
        }
    })
    for (let i = 0; i < dealer_aces; i++) {
        if (dealer_value + 11 <= 21) {
            dealer_value += 11
        } else {
            dealer_value++
        }
    }

    open.value = true
}

const hit = () => {
    if (player_value < 21) {
        let random = Math.floor(Math.random() * deck.length)
        player.push(deck[random])
        deck.splice(random, 1)

        player_value = 0
        let player_aces = 0
        player.forEach(card => {
            if (card[1] != "A") {
                player_value += parseInt(card[1])
            } else {
                player_aces++
            }
        })
        for (let i = 0; i < player_aces; i++) {
            if (player_value + 11 <= 21) {
                player_value += 11
            } else {
                player_value++
            }
        }
    }
}

const endGame = () => {
    end.value = true

    if(player_value <= 21) {
        if (player_value == 21 && player.length == 2) {
            if (dealer_value == 21) {
                user.balance += form.bet
            } else {
                user.balance += form.bet * 2.5
            }
        } else if (dealer_value > 21) {
            user.balance += form.bet * 2
        } else if (dealer_value < player_value) {
            user.balance += form.bet * 2
        } else if (dealer_value == player_value) {
            user.balance += form.bet
        }
    }

    postData()
}


const form = useForm({
    bet: 0,
});

const postData = async () => {
    try {
        const response = await axios.post(route('balance-game.update'), {
            "balance": user.balance,
        });
    } catch (error) {
        console.error(error);
    }

    balance.value = user.balance
};

</script>

<template>
    <Head title="Blackjack" />

    <Layout>
        <template #header>
            <div class="flex">
                <div class="flex-1">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blackjack</h2>
                </div>
                <div class="flex-1">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right" :key="balance">{{ balance }} HUF</h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-if="!open" :key="open">
                        <div class="p-6 text-gray-900 text-center">Would you like to play blackjack?</div>
                        <div class="w-[200px] mx-auto">
                            <InputLabel for="bet" value="Bet" />
                            <TextInput
                                id="bet"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="bet"
                                v-model="form.bet"
                            />
                        </div>
                        <div class="flex my-10">
                            <div class="flex-1">
                            </div>
                            <div class="flex-none">
                                <SecondaryButton class="mx-auto" @click="startGame">Play</SecondaryButton>
                            </div>
                            <div class="flex-1">
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="flex-1">
                            <div class="p-6 text-gray-900 text-center">Bet: {{ form.bet }} HUF</div>
                            <div class="w-max-content">
                                <div class="flex-1 mb-10" :key="player">
                                    <div class="p-6 text-gray-900 text-center">Player: {{ player_value }}</div>
                                    <div class="flex gap-2">
                                        <div class="flex-1" v-for="card in player">
                                            <img :src="card[2]" alt="Player card" class="w-32 h-48 mx-auto" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="p-6 text-gray-900 text-center">Dealer: {{ end ? dealer_value : '?' }}</div>
                                    <div class="flex gap-2">
                                        <div class="flex-1" v-for="card in dealer">
                                            <img v-if="end" :src="card[2]" alt="Dealer card" class="w-32 h-48 mx-auto" />
                                            <img v-else src="/assets/img/cards/BACK.png" alt="Dealer card" class="w-32 h-48 mx-auto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex my-10 gap-5">
                            <div class="flex-1">
                            </div>
                            <div class="flex-none" v-if="player_value<21 && !end">
                                <SecondaryButton class="mx-auto" @click="hit">Hit</SecondaryButton>
                            </div>
                            <div class="flex-none" v-if="!end">
                                <DangerButton class="mx-auto" @click="endGame">End Game</DangerButton>
                            </div>
                            <div class="flex-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
