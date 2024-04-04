<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, useForm } from '@inertiajs/vue3';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from 'vue';
import DangerButton from "@/Components/DangerButton.vue";
import { reactive } from "vue";
import axios from 'axios';

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

let deck = []

let end = reactive(ref(false))

const startGame = () => {
  form.bet = parseInt(form.bet)
  if (form.bet === null || form.bet < 0 || isNaN(form.bet)) {
    form.bet = 0
  } else if (form.bet > balance.value) {
    form.bet = balance.value
  }

  postBet(form.bet)
  balance.value -= form.bet

  let cards = props.deck

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
    if (card.rank !== "A" && card.rank !== "J" && card.rank !== "Q" && card.rank !== "K") {
      player_value += parseInt(card.rank)
    } else if (card.rank === "J" || card.rank === "Q" || card.rank === "K") {
      player_value += 10
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
    if (card.rank !== "A" && card.rank !== "J" && card.rank !== "Q" && card.rank !== "K") {
      dealer_value += parseInt(card.rank)
    } else if (card.rank === "J" || card.rank === "Q" || card.rank === "K") {
      dealer_value += 10
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
      if (card.rank !== "A" && card.rank !== "J" && card.rank !== "Q" && card.rank !== "K") {
          player_value += parseInt(card.rank)
      } else if (card.rank === "J" || card.rank === "Q" || card.rank === "K") {
          player_value += 10
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

let win_amount = 0

const endGame = () => {
  end.value = true

  if(player_value <= 21) {
    if (player_value === 21 && player.length === 2) {
      if (dealer_value === 21) {
        win_amount = form.bet
      } else {
        win_amount = form.bet * 2.5
      }
    } else if (dealer_value > 21) {
      win_amount = form.bet * 2
    } else if (dealer_value < player_value) {
      win_amount = form.bet * 2
    } else if (dealer_value === player_value) {
      win_amount = form.bet
    }
  }

  postWin(win_amount)
  balance.value += win_amount

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
                            <div class="flex-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
