import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const user = ref(null)
const authenticated = ref(false)

export function useAuth() {
    
    //fetches user from laravel backend
    const fetchUser = async () => {
        if (!authenticated.value) {
            try {
                const response = await axios.get('/api/user')
                user.value = response.data
                authenticated.value = true
            } catch (error) {
                if (error.response?.status != '401'){
                    console.error('Unexpected error: ', error)
                }
                user.value = null
            }
        }
        return user.value
    }

    const router = useRouter()
    
    //signs in, gets csrf cookie
    const signIn = async (form) => {
        const errors = ref([])
      
        try {
            await axios.get('/sanctum/csrf-cookie')
            const loginResponse = await axios.post('/login', form.value)
        
            console.log("Login response: ", loginResponse)
        
            await fetchUser()
            router.push({ name: 'main' })
        } catch (error) {
            console.log("Login error: ", error)
        
            if (error.response?.data?.errors) {
                errors.value = Object.values(error.response.data.errors).flat()
            } else {
                errors.value = ['Login failed. Please try again.']
            }
        }
        return errors.value
      }
      
    //Log out function
    const logOut = async () => {
        await axios.get('/sanctum/csrf-cookie')
        try {
            await axios.post('/logout')
            user.value = null
            authenticated.value = false
            router.push({ name: 'login' })
        } catch (error) {
            console.error('Logout failed:', error);
        }
    }

    const isAuthenticated = () => {
        return !!user.value
    }

    return {
        user,
        fetchUser,
        logOut,
        signIn,
        isAuthenticated,
    }
}

