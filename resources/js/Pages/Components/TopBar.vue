<template>
    <header class="top-bar">
        Welcome, <b>{{username}}</b> - <span @click="logout">logout</span>
    </header>
</template>

<script>
import axios from 'axios';
import {useRouter} from "vue-router";

export default {
    name: 'TopBar',
    props: {
        username: {
            type: String,
            default: 'Dialog Title'
        },
    },
    setup() {
        const router = useRouter();

        const logout = async () => {
            try {
                await axios.post('/logout');
                window.location.reload(); // Redirect to login page or homepage
            } catch (error) {
                console.error('Logout failed:', error);
            }
        };

        return {
            logout,
        };
    }

};
</script>

<style scoped>
.top-bar {
    background-color: #4a5568;
    color: white;
    padding: 1rem;
    text-align: right;
    height: 50px;
    font-size: 12px;
}
</style>
