<script setup>
import Layout from '@/Layouts/Layout.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import ResultModal from "@/Pages/Blackjack/Components/ResultModal.vue";
import {Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { reactive } from "vue";
import axios from 'axios';

const props = defineProps({
    balance: Object,
    deck: Object,
});

const form = useForm({
    amount: null,
});

let balance = ref(props.balance)
let open = ref(false)
let player = reactive([])
let dealer = []
let player_value = ref(0)
let dealer_value = ref(0)
let deck = props.deck
let end = reactive(ref(false))
let win = ref(0)
let win_amount = 0
let show = reactive(ref(false))

const value = async (cards) => {
    try {
        const response = await axios.get(route('blackjack.value'), {
            params: {
                cards: cards,
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

const result = async (player, dealer) => {
    try {
        const response = await axios.get(route('blackjack.result'), {
            params: {
                player: player,
                dealer: dealer,
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

const startGame = async () => {
    form.amount = parseInt(form.amount)
    if (form.amount === null || form.amount < 0 || isNaN(form.amount)) {
        form.amount = 0
    } else if (form.amount > balance.value) {
        form.amount = balance.value
    }

    postBet(form.amount)
    balance.value -= form.amount

    for (let i = 0; i < 2; i++) {
        player.push(deck[0])
        deck.splice(0, 1)
        dealer.push(deck[0])
        deck.splice(0, 1)
    }

    player_value.value = await value(player)
    dealer_value.value = await value(dealer)

  open.value = true
}

const hit = async () => {
    if (player_value.value < 21) {
        player.push(deck[0])
        deck.splice(0, 1)

        player_value.value = await value(player)
    }
}

const endGame = async () => {
    end.value = true

    win.value = await result(player, dealer)

    switch (win) {
        case 0:
            win_amount = 0
            break
        case 1:
            win_amount = form.amount
            break
        case 2:
            win_amount = form.amount * 2
            break
        case 3:
            win_amount = form.amount * 2.5
            break
    }

    postWin()
    balance.value += win_amount
    show.value = true
}

const postBet = () => {
    form.post(route('balance.bet'), {
        preserveScroll: true,
    });
};

const postWin = async () => {
    try {
        const response = await axios.post(route('balance.win'), {
            "amount": win_amount,
        });
    } catch (error) {
        console.error(error);
    }

    try {
        const response = await axios.post(route('game.store'), {
          "player_cards": player,
          "dealer_cards": dealer,
          "deck": deck,
          "bet": form.amount,
        });
    } catch (error) {
      console.error(error);
    }
};

const reloadPage = () => {
    location.reload()
}
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

        <ResultModal :show="show" :win="win" :key="win" @close="show = false" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-if="!open" :key="open">
                        <div class="p-6 text-gray-900 text-center">Would you like to play blackjack?</div>
                        <div class="w-[200px] mx-auto">
                            <VTextField
                                label="Bet"
                                variant="solo"
                                v-model="form.amount"
                                autocomplete="bet"
                                required
                                id="bet"
                            ></VTextField>
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
                            <div class="p-6 text-gray-900 text-center">Bet: {{ form.amount }} HUF</div>
                            <div class="w-max-content">
                                <div class="flex-1 mb-10" :key="player">
                                    <div class="p-6 text-gray-900 text-center">Player: {{ player_value }}</div>
                                    <div class="flex gap-2">
                                        <div class="flex-1" v-for="card in player">
                                            <img :src="card.image_url" alt="Player card" class="w-32 h-48 mx-auto" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="p-6 text-gray-900 text-center">Dealer: {{ end ? dealer_value : '?' }}</div>
                                    <div class="flex gap-2">
                                        <div class="flex-1" v-for="card in dealer">
                                            <img v-if="end" :src="card.image_url" alt="Dealer card" class="w-32 h-48 mx-auto" />
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
                            <div class="flex-none" v-if="end">
                                <PrimaryButton class="mx-auto" @click="reloadPage">New Game</PrimaryButton>
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
