<template>
    <div v-if="isVisible" class="modal" tabindex="-1" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" class="btn-close" @click="closeDialog"></button>
                </div>
                <div class="modal-body">
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
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import {ref, onMounted, getCurrentInstance} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import axios from 'axios';
import 'vue-select/dist/vue-select.css'; // Import CSS
import vSelect from 'vue-select';
import toast from "bootstrap/js/src/toast.js";
import { useToast } from 'vue-toastification';

export default {
    components: {vSelect},
    props: {
        title: {
            type: String,
            default: 'Dialog Title'
        },
        isVisible: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        closeDialog() {
            this.$emit('close');
        },
        addItem() {
            this.form.positions.push(this.selectedPosition.label);
            this.selectedPosition = null;
        },
    },
    setup(props, {emit}) {
        const { proxy } = getCurrentInstance();
        const units = ref([]);
        const selectedUnit = ref(null);
        const isDialogVisible = ref(false); // Declare as ref
        const positions = ref([]);
        const selectedPosition = ref(null);
        const toast = useToast();

        onMounted(async () => {
            const responseUnit = await axios.get('/units');
            units.value = responseUnit.data.map(unit => ({
                id: unit.unit_name,
                label: unit.unit_name
            }));

            const responsePosition = await axios.get('/positions');
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
                toast.success('User added successfully!');
                selectedPosition.value = [];
                isDialogVisible.value = false;
                emit('userAdded');

            }, errors => {
                console.log(errors);
                toast.error('Failed to add user');
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
}
</script>

<style scoped>
.modal {
    display: block;
    background: rgba(0, 0, 0, 0.5);
}
* {
    font-size: 12px;
}
</style>
