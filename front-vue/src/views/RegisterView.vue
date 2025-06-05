<script setup>
import axios from 'axios'
import { ref } from 'vue'

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})
const message = ref('')
const errors = ref([])

const register = async () => {

    errors.value = []
    message.value = ''
    try {
        // 1. Ensure CSRF cookie is set
        await axios.get('/sanctum/csrf-cookie')

        // 2. Send registration request
        const response = await axios.post('/register', form.value)
        console.log('Register response:', response.status)
        console.log('Response: ', response)
        if (response.status == '204'){
            form.value.name = ''
            form.value.email = ''
            form.value.password = ''
            form.value.password_confirmation = ''

            message.value = "Registration successful"
        }
    } catch (error) {
        if (error.response && error.response.data.errors) {
            console.log(error)
            errors.value = Object.values(error.response.data.errors).flat()
            console.error('Validation errors:', error.response.data.errors)
        } else {
            console.error('Unexpected error:', error.message)
            errors.value = ['An unexpected error occurred.']
        }
    }
}
</script>

<template>
    <div class="flex items-center justify-center bg-gray-100 h-screen">
        <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Create an Account</h2>
            <form @submit.prevent="register" class="space-y-5">
                <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input v-model="form.name" type="text" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

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
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>
                <p>
                    Already have an account,
                    <router-link :to="{name: 'login'}" class="text-blue-500 hover:underline-offset-1 font-medium">
                        sign in!
                    </router-link> 
                </p>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                Sign up
                </button>
            </form>
            <div v-if="message" class="mt-4 bg-green-100 border border-green-300 text-green-700 p-3 rounded">
                <p>{{ message }}</p>
            </div>
            <div v-if="errors.length" class="mt-4 bg-red-100 border border-red-300 text-red-700 p-3 rounded">
                <ul class="list-disc list-inside">
                    <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>