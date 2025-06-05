<script setup>
    import { ref, onMounted } from 'vue'
    import { getUserPosts } from '@/api/posts'
    import { deletePost } from '@/api/posts';
    import PostCreateForm from '@/components/PostCreateForm.vue'
    import PostUpdateForm from '@/components/PostUpdateForm.vue';
    import Button from 'primevue/button';
    import Dialog from 'primevue/dialog';
    import Toast from 'primevue/toast';
    import Paginator from 'primevue/paginator';
    import { useToast } from 'primevue/usetoast';

    const posts = ref([])
    const showCreateForm = ref(false)
    const showDeleteDialog = ref(false)
    const showModifyDialog = ref(false)
    const pagination =  ref({})
    const first = ref(0) 
    const deletePostVal = ref({
        'id' : '',
        'title' : '',
    })
    const updatePostVal = ref({
        'id' : '',
        'title' : '',
        'content': '',
        'categories': [],
    })

    const handlePosts = async(page = 0) => {
        const response = await getUserPosts(page)
        posts.value = response.data

        pagination.value = {
            total: response.meta.total,
            per_page: response.meta.per_page,
        }
    }
    //on mount gets all posts
    onMounted(  () => {
        handlePosts()
    })

    const onPageChange = (event) => {
        const page = event.page
        first.value = event.first
        handlePosts(page)
    }

    //refresh posts after new post created
    const refreshPosts = () => {
        first.value = 0 
        handlePosts(0)
    }

    const handlePostDeletion = async () => {
        await deletePost(deletePostVal.value.id)
        refreshPosts()
        showDeleteDialog.value = false
    }

    //displays success messages for post creation and modification
    const toast = useToast()
    function showSuccess(message) {
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: message,
            life: 4000
        })
    }
</script>

<template>
    <div class="p-6 max-w-5xl mx-auto min-h-screen">

        <!-- Create new post button -->
        <div class="flex gap-4 mb-4">
            <Button label="+ Create new post" severity="secondary" @click="showCreateForm = true" class="!text-gray-800" />
        </div>

        <!-- Create/Update message display -->
        <Toast/>

        <!-- Post form dialog window -->
        <Dialog v-model:visible="showCreateForm" modal maximizable header="Create new post">
            <PostCreateForm
                @closeForm="showCreateForm = false"
                @postCreated="refreshPosts"
                @createResponse="showSuccess"
            />
        </Dialog>

        <!-- Delete dialog window -->
        <Dialog v-model:visible="showDeleteDialog" modal :header=" 'Do you want to delete post : ' + deletePostVal.title + '?'">
            <div class="flex items-center justify-center">
                <Button @click="showDeleteDialog = false" label="Cancel" severity="secondary" class="mr-6"/>
                <Button @click="handlePostDeletion" label="Delete" severity="danger" />
            </div>
        </Dialog>

        <!-- Modify dialog window  -->
        <Dialog v-model:visible="showModifyDialog" maximizable modal header="Update post">
            <PostUpdateForm :form="updatePostVal"
                @postUpdated="refreshPosts"
                @closeForm="showModifyDialog = false"
                @createResponse="showSuccess"
            />
        </Dialog>

        <!-- Posts -->
         <div v-if="posts.length > 0">
            <div  class="grid grid-cols-2 gap-4">
                <div
                    v-for="post in posts"
                    :key="post.id"
                    class="border border-slate-200 rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition-shadow"
                >   
                    <div class="flex justify-end gap-2">
                        <Button @click="[showModifyDialog = true, updatePostVal.id = post.id, updatePostVal.title = post.title, updatePostVal.content = post.content, updatePostVal.categories = post.categories.map(cat => Number(cat.id))]" label="Modify" severity="secondary" class="!p-1 !text-sm"/> 
                        <Button @click="[showDeleteDialog = true, deletePostVal.id = post.id, deletePostVal.title = post.title]" label="Delete" severity="warn" class="!p-1 !text-sm"/>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">
                        <RouterLink :to="{ name: 'post.show', params: { id: post.id } }" class=" hover:underline">
                            {{ post.title }}
                        </RouterLink>
                    </h2>
                    <p class="text-sm text-slate-500 mb-1">
                        By you • {{ post.created_at }}
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
                :first="first"  
                class="mt-2"
                @page="onPageChange"
                >
            </Paginator>
         </div>
        
        <p v-else class="text-slate-600">No posts found.</p>
    </div>
</template>