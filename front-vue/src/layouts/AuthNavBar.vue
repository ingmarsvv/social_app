<script setup>
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth.js'
import {useRoute } from 'vue-router'

const route = useRoute()
const { logOut, isAuthenticated, user } = useAuth()

</script>

<template>
    <nav class="bg-gray-700">
        <div class="mx-auto max-w-5xl px-2 sm:px-6 lg:px-8">
            <div class="flex justify-between">
                <div>
                    <ul class="flex gap-6 h-full items-center">
                        <li>
                            <RouterLink
                                :class="{'bg-slate-50 font-semibold text-slate-800' : route.name == 'main'}" 
                                :to = "{ name: 'main' }"
                                class = "text-gray-100 text-2xl p-2 hover:bg-gray-300 rounded-md">
                                Main Feed
                            </RouterLink>
                        </li>
                        <li>
                            <RouterLink
                                :class="{'bg-slate-50 font-semibold text-slate-700 ' : route.name == 'profile'}" 
                                :to = "{ name: 'profile' }"
                                class = "text-gray-100 text-2xl p-2 hover:bg-gray-300 rounded-md">
                                My Profile
                            </RouterLink>
                        </li>
                    </ul>
                </div>
                <div class="flex items-center gap-6 justify-end h-16 ">
                    <div v-if="isAuthenticated && user" class="text-gray-100 text-lg p-2">
                        Hello, {{ user.name }}!
                    </div>
                    <div>
                        <button @click="logOut" class="text-gray-900 text-xl p-1 bg-white rounded-md hover:bg-gray-300">Log out</button>
                    </div>
                </div>


            </div>
        </div>
    </nav>
</template>
