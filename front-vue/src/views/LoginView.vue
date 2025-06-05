<script setup>
import axios from 'axios'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { signIn } = useAuth()
const errors = ref([])

const form = ref({
    email: '',
    password: '',
})

const handleSubmit = async () => {
    errors.value = []
    errors.value = await signIn(form)
}

</script>

<template>
    <div class="flex items-center justify-center bg-gray-100 h-screen">
        <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h2>
            <form @submit.prevent="handleSubmit" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" required
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="form.password" type="password" required
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>
                <div>
                    <p>
                        Don't have an account,
                        <router-link :to="{name: 'register'}" class="text-blue-500 hover:underline-offset-1 font-medium">
                            register!
                        </router-link> 
                    </p>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                    Log in
                </button>
            </form>
            <div v-if="errors.length" class="mt-4 bg-red-100 border border-red-300 text-red-700 p-3 rounded">
                <ul class="list-disc list-inside">
                    <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>