<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, useForm } from '@inertiajs/vue3';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from 'vue';
import DangerButton from "@/Components/DangerButton.vue";
import { reactive } from "vue";
import axios from 'axios';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ResultModal from "@/Pages/Blackjack/Components/ResultModal.vue";

const props = defineProps({
  balance: Object,
  deck: Object,
});

const form = useForm({
  bet: null,
});

let balance = ref(props.balance)
let open = ref(false)
let player = reactive([])
let dealer = []
let player_value = 0
let dealer_value = 0
let deck = props.deck
let end = reactive(ref(false))
let win = ref(0)
let win_amount = 0
let show = reactive(ref(false))

const value = (cards) => {
    let value = 0
    let aces = 0
    cards.forEach(card => {
        if (card.rank !== "A" && card.rank !== "J" && card.rank !== "Q" && card.rank !== "K") {
            value += parseInt(card.rank)
        } else if (card.rank === "J" || card.rank === "Q" || card.rank === "K") {
            value += 10
        } else {
            aces++
        }
    })
    for (let i = 0; i < aces; i++) {
        if (value + 11 <= 21) {
            value += 11
        } else {
            value++
        }
    }
    return value
}

const startGame = () => {
  form.bet = parseInt(form.bet)
  if (form.bet === null || form.bet < 0 || isNaN(form.bet)) {
    form.bet = 0
  } else if (form.bet > balance.value) {
    form.bet = balance.value
  }

  postBet(form.bet)
  balance.value -= form.bet


  for (let i = 0; i < 2; i++) {
    let random = Math.floor(Math.random() * deck.length)
    player.push(deck[random])
    deck.splice(random, 1)

    random = Math.floor(Math.random() * deck.length)
    dealer.push(deck[random])
    deck.splice(random, 1)
  }

    player_value = value(player)
    dealer_value = value(dealer)

  open.value = true
}

const hit = () => {
  if (player_value < 21) {
    let random = Math.floor(Math.random() * deck.length)
    player.push(deck[random])
    deck.splice(random, 1)

        player_value = value(player)
    }
}

const endGame = () => {
  end.value = true

  if(player_value <= 21) {
    if (player_value === 21 && player.length === 2) {
      if (dealer_value === 21) {
        win_amount = form.bet
        win = 1
      } else {
        win_amount = form.bet * 2.5
        win = 2
      }
    } else if (dealer_value > 21) {
      win_amount = form.bet * 2
        win = 2
    } else if (dealer_value < player_value) {
      win_amount = form.bet * 2
        win = 2
    } else if (dealer_value === player_value) {
      win_amount = form.bet
        win = 1
    }
  }

  postWin(win_amount)
  balance.value += win_amount
    show.value = true
    console.log(win)
}

const postBet = async (amount) => {
  try {
    const response = await axios.post(route('balance.bet'), {
      "amount": amount
    });
  } catch (error) {
    console.error(error);
  }
};

const postWin = async (amount) => {
  try {
    const response = await axios.post(route('balance.win'), {
      "amount": amount
    });
  } catch (error) {
    console.error(error);
  }

  try {
    const response = await axios.post(route('game.store'), {
      "player_cards": player,
      "dealer_cards": dealer,
      "deck": deck,
      "bet": form.bet,
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
                                v-model="form.bet"
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
                            <div class="p-6 text-gray-900 text-center">Bet: {{ form.bet }} HUF</div>
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