<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router'
import AuthNavBar from './layouts/AuthNavBar.vue'
import { computed } from 'vue'


const route = useRoute() //gets current route

const showNav = computed(() => {
  return route.name !== 'register' && route.name !== 'login'
})

</script>

<template>
  <div class="flex flex-col min-h-screen">
    <header v-if="showNav" class="fixed top-0 left-0 w-full z-30">
      <AuthNavBar />
    </header>
    <main class="grow bg-slate-50 pt-14">
      <RouterView />
    </main>
  </div>
</template>

