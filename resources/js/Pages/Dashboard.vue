<template>
    <div class="container mt-5">
        <div>Welcome to the Dashboard, <b>{{user.name}}</b></div>
        <p>This page is protected and only accessible to authenticated users.</p>

        <button class="btn btn-primary" @click="showDialog">Add New User</button>
    </div>
    <NewUserDialog :title="'Add New User'" :isVisible="isDialogVisible" @close="closeDialog">
        <form @submit.prevent="addNewUser">
            <div class="form-group mb-3">
                <label for="name">Full Name:</label>
                <input type="text" id="name" class="form-control" v-model="form.name" required>
            </div>

            <div class="form-group mb-3">
                <label for="username">Username:</label>
                <input type="email" id="username" class="form-control" v-model="form.username" required>
            </div>

            <div class="form-group mb-3">
                <label for="username">Password:</label>
                <input type="password" id="password" class="form-control" v-model="form.password" required>
            </div>

            <div class="form-group mb-3">
                <label for="username">Join Date:</label>
                <input type="date" id="join_date" class="form-control" v-model="form.join_date" required>
            </div>

            <div class="form-group mb-3">
                <label>Unit:</label>
                <v-select
                    taggable
                    push-tags
                    placeholder="Type and press enter to add a new option"
                    :options="units" v-model="form.unit_id" />
            </div>

            <div class="form-group mb-3">
                <label>Position:</label>
                <v-select
                    taggable
                    push-tags
                    :options="positions"
                    v-model="selectedPosition" />
                <button type="button" class="mt-3 btn btn-danger" @click="addItem">Add Position</button>
            </div>

            <div class="mb-3">
                <ul>
                    <li v-for="(item, index) in form.positions" :key="index">
                        {{ item }}
                    </li>
                </ul>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Add New User</button>
            </div>
        </form>
    </NewUserDialog>
</template>

<script>
import NewUserDialog from "./Components/NewUserDialog.vue";
import {ref, onMounted} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import axios from 'axios';
import 'vue-select/dist/vue-select.css'; // Import CSS
import vSelect from 'vue-select';

export default {
    name: 'Dashboard',
    props: {
        auth: Object
    },
    components: {
      NewUserDialog,
      vSelect

    },
    computed: {
      user() {
          return this.auth.user;
      }
    },
    methods: {
        showDialog() {
            this.isDialogVisible = true;
        },
        closeDialog() {
            this.isDialogVisible = false;
        },
        addItem() {
            this.form.positions.push(this.selectedPosition.label);
            this.selectedPosition = null;
        },
    },
    setup() {

        const units = ref([]);
        const selectedUnit = ref(null);
        const isDialogVisible = ref(false); // Declare as ref
        const positions = ref([]);
        const selectedPosition = ref(null);

        onMounted(async () => {
            const responseUnit = await axios.get('/units');
            console.log(responseUnit);
            units.value = responseUnit.data.map(unit => ({
                id: unit.unit_name,
                label: unit.unit_name
            }));

            const responsePosition = await axios.get('/positions');
            console.log(responsePosition);
            positions.value = responsePosition.data.map(position => ({
                id: position.position_name,
                label: position.position_name
            }));
        });

        const form = ref({
            name: '',
            username: '',
            password: '',
            join_date: '',
            unit_id: '',
            positions: []
        });

        const { errors } = usePage().props.value;

        const addNewUser = () => {
            form.value.unit_id = form.value.unit_id.label;
            axios.post('/add-new-user', form.value).then(() => {
                form.value.name = '';
                form.value.username = '';
                form.value.password = '';
                form.value.join_date = '';
                form.value.unit_id = '';
                form.value.positions = [];

                selectedPosition.value = [];
                isDialogVisible.value = false;
            }, errors => {
                console.log(errors);
            });


        };

        return {
            form,
            addNewUser,
            errors,
            units,
            positions,
            selectedUnit,
            selectedPosition,
            isDialogVisible
        };
    },
};
</script>

<style scoped>
/* Add your custom styles here if needed */
</style>
