<script setup>
import { getPost } from '@/api/posts'
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import Textarea from 'primevue/textarea'
import axios from 'axios'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const route = useRoute()
const postId = route.params.id
const post = ref('')
const currentUser = ref(null) 
const comment = ref('') //holds comment from form
const errors = ref([]) //holds errors from post request

//on mount gets current user
onMounted( async () => {
        const response = await getPost(postId)
        post.value = response.data
        try {
            const response = await axios.get('/api/user')
            currentUser.value = response.data 
        } catch (error) {
            console.error('User not authenticated')
        }
    }
) 

//deletes a comment
const deleteComment = async (commentId) => {
  try {
    await axios.delete(`/api/comments/${commentId}`)
    const response = await getPost(postId)
    post.value = response.data
  } catch (error) {
    console.error('Failed to delete comment:', error)
  }
}
const toast = useToast()
function showSuccess(message) {
    toast.add({
        severity: 'success',
        summary: 'Success',
        detail: message,
        life: 4000
    })
}

//posts a comment
const submitComment = async () => {
    try {
        const response = await axios.post('/api/comments', {
            post_id: postId,
            content: comment.value
        })
        comment.value = ''
        const postResponse = await getPost(postId)
        post.value = postResponse.data
        showSuccess(response.data.message)
  } catch (error) {
      if (error.response && error.response.data.errors) {
          errors.value = Object.values(error.response.data.errors).flat()
      } else {
          errors.value = ['An unexpected error occurred.']
      }
    }
}

</script>
    
<template>
    <!-- Displays post comment message -->
    <Toast/>

  <div class="bg-white border rounded-lg shadow-md p-6 space-y-4 mx-auto max-w-5xl mt-6">
    <!-- Title -->
    <h2 class="text-2xl font-semibold text-slate-800">{{ post.title }}</h2>

    <!-- Author and Date -->
    <p class="text-sm text-gray-500">
      By <span class="font-medium text-slate-700">{{ post.author }}</span> • {{ post.created_at }}
    </p>

    <!-- Content -->
    <p class="text-gray-700 leading-relaxed text-justify">
      {{ post.content }}
    </p>

    <!-- Comments -->
    <div  class="mt-4">
        <div class="flex justify-between">
            <div v-if="post.comments && post.comments.length" class="w-2/3">
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Comments ({{ post.comments_count }})</h3>
                <ul class="space-y-4 text-sm text-slate-700">
                    <li
                    v-for="comment in post.comments"
                    :key="comment.id"
                    class="border-l-4 border-blue-300 pl-3 py-1"
                    >
                    <div class="flex flex-col">
                        <p class="font-semibold">
                            <span v-if="currentUser && comment.user_id != currentUser.id">
                                {{ comment.author }}
                            </span>
                            <span v-else>
                                You
                            </span>
                            commented on {{ comment.created_at }}
                            <!-- Only show delete button if the comment author is the logged-in user -->
                            <button
                                v-if="currentUser && comment.user_id === currentUser.id"
                                @click="deleteComment(comment.id)"
                                class="text-red-600 text-sm hover:underline"
                                >
                                Delete
                            </button>
                        </p>
                        <p>{{ comment.content }}</p>
                    </div>
                        
                    </li>
                </ul>
            </div>
            <p v-else class="text-sm text-slate-700">This post has no comments</p>

            <!-- Comment form -->
            <form @submit.prevent="submitComment" class="space-y-4 max-w-sm">
                <h3 class="text-lg font-semibold text-slate-800">Add a Comment</h3>
                <Textarea
                    v-model="comment"
                    rows="3" 
                    cols="30"
                    placeholder="Write your comment..."
                    class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required
                />
                <div class="flex items-center justify-between">
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 p-1 rounded hover:bg-blue-700 transition"
                    >
                        Post Comment
                    </button>
                </div>
            </form>
        </div>
    </div>
  </div>
</template>

