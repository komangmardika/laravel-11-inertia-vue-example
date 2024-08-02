<template>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4" style="width: 24rem;">
            <h3 class="text-center mb-4">Login</h3>
            <form @submit.prevent="login">
                <div class="form-group mb-3">
                    <label for="username">Username:</label>
                    <input type="email" id="username" class="form-control" v-model="form.username" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password:</label>
                    <input type="password" id="password" class="form-control" v-model="form.password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            <div v-if="errors && (errors.username || errors.password)" class="alert alert-danger mt-3" role="alert">
                <ul>
                    <li v-if="errors.username">{{ errors.username }}</li>
                    <li v-if="errors.password">{{ errors.password }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    setup() {
        const form = ref({
            username: '',
            password: '',
        });

        const { errors } = usePage().props.value;

        const login = () => {
            Inertia.post('/login', form.value);
        };

        return {
            form,
            login,
            errors,
        };
    },
};
</script>

<style scoped>
/* Add your custom styles here if needed */
</style>
