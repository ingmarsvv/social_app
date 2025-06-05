<script setup>
import { ref, onMounted, defineEmits } from 'vue'
import axios from 'axios'
import { createPost } from '@/api/posts'

const form = ref({
  title: '',
  content: '',
  category_ids: []
})
const errors = ref([])
const categories = ref([])
const emit = defineEmits(['postCreated', 'closeForm', 'createResponse'])

const submitPost = async () => {
  errors.value = ref([])
  
  try {
      const response = await createPost(form.value)
      form.value = { title: '', content: '', category_ids: [] } 
      emit('postCreated')
      emit('closeForm')
      emit('createResponse', response.message)
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

onMounted(async () => {
    const response = await axios.get('/api/categories')
    categories.value = response.data
})
</script>

<template>
    <form @submit.prevent="submitPost" class="space-y-4 max-w-4xl min-w-lg mx-auto p-4 bg-white">
      <div>
        <label class="block font-semibold mb-1">Title</label>
        <input v-model="form.title" type="text" class="w-full p-2 border rounded" required />
      </div>
  
      <div>
        <label class="block font-semibold mb-1">Content</label>
        <textarea v-model="form.content" class="w-full p-2 border rounded" rows="10" required></textarea>
      </div>
  
      <div>
        <label class="block font-semibold mb-1">Categories</label>
        <div class="flex flex-wrap gap-2">
          <label v-for="cat in categories" :key="cat.id" class="flex items-center space-x-2">
            <input type="checkbox" :value="cat.id" v-model="form.category_ids" />
            <span>{{ cat.name }}</span>
          </label>
        </div>
      </div>
  
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Post</button>
    </form>
    <div v-if="errors.length" class="mt-4 bg-red-100 border border-red-300 text-red-700 p-3 rounded">
        <ul class="list-disc list-inside">
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
    </div>
  </template>