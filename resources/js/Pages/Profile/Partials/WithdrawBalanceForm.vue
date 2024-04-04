<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from "axios";

const user = usePage().props.auth.user;

const form = useForm({
    amount: 0,
});

const amounts = [
    0,
    1000,
    5000,
    10000,
    50000,
    100000,
    500000,
    1000000
];

const withdrawableAmounts = amounts.filter(amount => amount <= user.balance);

const postData = async () => {
    try {
        const response = await axios.post(route('balance.withdraw'), {
            "amount": form.amount,
        });
    } catch (error) {
        console.error(error);
    }

    location.reload();
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Withdraw</h2>

            <p class="mt-1 text-sm text-gray-600">
                Withdraw money from your account.
            </p>
        </header>

        <form @submit.prevent="postData" class="mt-6 space-y-6">
            <div>

                <VSelect
                    label="Withdraw amount"
                    id="withdraw"
                    class="mt-1 block w-full"
                    v-model="form.amount"
                    :items="withdrawableAmounts"
                    required
                    autocomplete="amount"
                    variant="solo"
                ></VSelect>

                <InputError class="mt-2" :message="form.errors.amount" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
