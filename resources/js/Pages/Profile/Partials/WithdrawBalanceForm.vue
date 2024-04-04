<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    withdraw: 0,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Withdraw</h2>

            <p class="mt-1 text-sm text-gray-600">
                Withdraw money from your account.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('balance.update'))" class="mt-6 space-y-6">
            <div>
                <!-- <InputLabel for="balance" value="Balance" />

                <TextInput
                    id="balance"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.balance"
                    autofocus
                    required
                    autocomplete="balance"
                /> -->

                <VSelect
                    label="Withdraw amount"
                    id="balance"
                    class="mt-1 block w-full"
                    v-model="form.withdraw"
                    :items="['1000', '5000', '10000']"
                    required
                    autocomplete="balance"
                    variant="solo"
                ></VSelect>

                <InputError class="mt-2" :message="form.errors.balance" />
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
