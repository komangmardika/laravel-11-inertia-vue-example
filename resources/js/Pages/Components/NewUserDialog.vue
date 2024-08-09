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
                            <input
                                type="text"
                                id="name"
                                class="form-control"
                                v-model="form.name"
                                :disabled="addNew === 'v'"
                                required
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label for="username">Username:</label>
                            <input
                                type="email"
                                id="username"
                                class="form-control"
                                v-model="form.username"
                                :disabled="addNew === 'v'"
                                required
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label for="username">Password:</label>
                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                v-model="form.password"
                                :disabled="addNew === 'v'"
                                required
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label for="username">Join Date:</label>
                            <input
                                type="date"
                                id="join_date"
                                class="form-control"
                                v-model="form.join_date"
                                :disabled="addNew === 'v'"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Unit:</label>
                            <v-select
                                taggable
                                push-tags
                                :disabled="addNew === 'v'"
                                :options="units" v-model="form.unit_id" />
                        </div>

                        <div class="form-group mb-3">
                            <label>Position:</label>
                            <v-select
                                taggable
                                push-tags
                                :disabled="addNew === 'v'"
                                :options="positions"
                                v-model="selectedPosition" />
                            <button
                                type="button"
                                class="mt-3 btn btn-outline-success"
                                @click="addItem"
                                :disabled="addNew === 'v'"
                            >Add Position</button>
                        </div>

                        <div class="mb-3">
                            <ul>
                                <li v-for="(item, index) in form.positions" :key="index">
                                    {{ item }}
                                </li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button
                                v-if="['e','n'].includes(addNew)"
                                type="submit"
                                class="btn w-100 btn-outline-primary"
                            >Ok, Save It!</button>
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
        },
        data: {
            type: Object,
        },
        addNew: {
            type: String,
            default: false
        },
    },
    methods: {
        closeDialog() {
            this.$emit('close');
        },
        openDialog() {
            this.$emit('open');
        },
        addItem() {
            this.form.positions.push(this.selectedPosition.label);
            this.selectedPosition = null;
        },
    },
    watch: {
        isVisible(newVal, oldVal) {
            if(newVal) {
                this.loadUnitAndPosition();
            }
        },
        data(newVal, oldVal) {
            this.fillForm();
        }
    },
    setup(props, {emit}) {
        const { proxy } = getCurrentInstance();
        const units = ref([]);
        const selectedUnit = ref(null);
        const isDialogVisible = ref(false); // Declare as ref
        const positions = ref([]);
        const selectedPosition = ref(null);
        const toast = useToast();

        const loadUnitAndPosition = async () => {
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
        }

        onMounted(async () => {
            await loadUnitAndPosition();
        });

        const form = ref({
            name: '',
            username: '',
            password: '',
            join_date: '',
            unit_id: '',
            positions: []
        });

        const fillForm = () => {
            form.value.positions = [];
            form.value.name = props.data.name;
            form.value.username = props.data.username;
            form.value.join_date = props.data.join_date_r;
            form.value.password = '';
            form.value.unit_id = props.data.unit;
            let x = JSON.parse(props.data.position_r);
            x.forEach(y => {
                form.value.positions.push(y.position_name)
            });

        }

        const { errors } = usePage().props.value;

        const addNewUser = () => {
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
            loadUnitAndPosition,
            fillForm,
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
    margin-top: 50px;
}
* {
    font-size: 12px;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
