<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import axios from 'axios'
import { debounce } from 'lodash';
import Select from 'primevue/select';
import Paginator from 'primevue/paginator';
import { getPosts } from '@/api/posts';
import { getCategories } from '@/api/categories';


const posts = ref([])
const categories = ref([])
const selectedCategory = ref(0)
const searchPhrase = ref('')
const pagination =  ref({})
const additionalCategory = ref({id: 0, name: 'All categories'})

//On mount gets all posts and categories
onMounted(async () => {
  const [postRes, catRes] = await Promise.all([
    getPosts(0),
    getCategories()
  ])
  posts.value = postRes.data
  categories.value = catRes
  categories.value.unshift(additionalCategory.value)

  pagination.value = {
    total: postRes.meta.total,
    per_page: postRes.meta.per_page,
  }
})

const onPageChange = async (event) => {
  console.log(event)
  const page = event.page
  const response = await getPosts(page)
  console.log(response)
  posts.value = response.data

  pagination.value = {
    total: response.meta.total,
    per_page: response.meta.per_page,
  }
}

// Filtered posts based on category
const filteredPosts = computed(() => {
  if (!selectedCategory.value) return posts.value
  return posts.value.filter(post =>
    post.categories.some(cat => cat.id === selectedCategory.value)
  )
})

//gets posts by matching keyword in title or content
const searchByTitleOrContent = debounce( async () => {
    const response = await axios.get('/api/posts', {
        params: {
            search: searchPhrase.value,
        }
    })
    posts.value = response.data.data
}, 500)

//observes changes searchPhrase.value and triggers search function
watch(searchPhrase, () => {
    searchByTitleOrContent();
});

</script>

<template>
    <div class="p-6 max-w-5xl mx-auto min-h-screen">
        <h1 class="text-2xl font-bold mb-4 text-slate-800">Main Feed</h1>
        <div class="flex gap-10">
            <!-- Category Filter -->
            <div class="mb-6">
                <label class="block mb-2 font-semibold text-slate-700">Filter by Category:</label>
                <Select v-model="selectedCategory" :options="categories" optionLabel="name" optionValue="id" class="w-44"></Select>
            </div>
            <div>
                <label for="" class="block mb-2 font-semibold text-slate-700">Search by keyword:</label>
                <input type="text" v-model="searchPhrase" placeholder="" class="border border-slate-300 p-2 h-10 rounded bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-200">
            </div>
        </div>
  
      <!-- Posts -->
      <div v-if="filteredPosts.length > 0" >
        <div class="grid grid-cols-2 gap-4">
          <div
            v-for="post in filteredPosts"
            :key="post.id"
            class="border border-slate-200 rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition-shadow"
          >
            <h2 class="text-xl font-bold text-slate-800">
              <RouterLink :to="{ name: 'post.show', params: { id: post.id } }" class=" hover:underline">
                  {{ post.title }}
              </RouterLink>
            </h2>
            <p class="text-sm text-slate-500 mb-1">
              By {{ post.author }} • {{ post.created_at.split('T')[0] }}
            </p>
            <p class="mb-2 text-slate-700 text-justify">
              {{ post.content.substring(0, 300) }}...
            </p>
            <div class="flex flex-wrap gap-2 mb-2">
              <span
                v-for="category in post.categories"
                :key="category.id"
                class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
              >
                {{ category.name }}
              </span>
            </div>
            <p class="text-sm text-slate-600">Comments: {{ post.comments_count }}</p>
          </div>
        </div>
        <Paginator 
          :rows="pagination.per_page" 
          :totalRecords="pagination.total"  
          class="mt-2"
          @page="onPageChange"
        >
        </Paginator>
      </div>
      <p v-else class="text-slate-600">No posts found.</p>
    </div>
  </template>
  