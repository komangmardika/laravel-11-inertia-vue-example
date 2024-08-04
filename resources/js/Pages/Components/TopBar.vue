<template>
    <header class="top-bar d-flex justify-content-between">
        <div >Laravel 11 with InertiaJS and Vue 3</div>
        <div>
            Welcome, <b>{{username}}</b> - <span @click="logout">logout</span>
        </div>

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
    box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
    z-index: 9999;
    position: fixed;
    top: 0;
    width: 100%;
}
</style>
