<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import DepositBalanceForm from "@/Pages/Balance/Partials/DepositBalanceForm.vue";
import WithdrawBalanceForm from "@/Pages/Balance/Partials/WithdrawBalanceForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
let balance = ref(user.balance)

</script>

<template>
    <Head title="Profile" />

    <Layout>
        <template #header>
            <div class="flex">
                <div class="flex-1">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
                </div>
                <div class="flex-1">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right" :key="balance">{{ balance }} HUF</h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DepositBalanceForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <WithdrawBalanceForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </Layout>
</template>
