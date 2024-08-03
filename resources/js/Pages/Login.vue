<template>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 border-0 shadow-lg rounded-3" style="width: 22rem;">
            <h3 class="text-center mb-4 fw-bold">Laravel 11 with InertiaJS and Vue 3</h3>
            <form @submit.prevent="login">
                <div class="form-group mb-3">
                    <label class="fw-bold" for="username">Username:</label>
                    <input type="email" id="username" class="form-control mt-2" v-model="form.username" required>
                </div>
                <div class="form-group mb-3">
                    <label class="fw-bold" for="password">Password:</label>
                    <input type="password" id="password" class="form-control mt-2" v-model="form.password" required>
                </div>
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-outline-primary w-100 p-2">Login</button>
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
    * {
        font-size: 12px;
    }
</style>
