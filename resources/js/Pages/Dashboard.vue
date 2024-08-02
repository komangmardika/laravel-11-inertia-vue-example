<template>
    <Main :auth="auth">
        <h1 style="font-size: 20px">Dashboard</h1>
        <div class="row mt-5">
            <div class="col-3">
                <div class="form-group mb-3">
                    <label for="start">From:</label>
                    <input type="date" v-model="start" id="start" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group mb-3">
                    <label for="start">Until:</label>
                    <input type="date" v-model="end" id="end" class="form-control">
                </div>
            </div>
            <div class="col-3">
                <button class="btn btn-primary mt-3" @click="applyFilter">Filter</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-6">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                Total Employees
                            </div>
                            <div class="card-body">
                                <b v-if="dashboardData.data">{{dashboardData.data.employee}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                Total of Logged In Users
                            </div>
                            <div class="card-body">
                                <b v-if="dashboardData.data">{{dashboardData.data.loggedIn}} times</b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                Total Unit
                            </div>
                            <div class="card-body">
                                <b v-if="dashboardData.data">{{dashboardData.data.unit}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                Total Position
                            </div>
                            <div class="card-body">
                                <b v-if="dashboardData.data">{{dashboardData.data.position}}</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <b class="mb-4">Top 10 Logged In</b>
                <table class="mt-4 table table-bordered table-striped" v-if="dashboardData.data">
                    <thead>
                        <tr>
                            <th style="text-align: right">No.</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Logged In At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dashboardData.data.top10LoggedIn" :key="index">
                            <td style="text-align: right">{{index + 1}}.</td>
                            <td>{{item.name}}</td>
                            <td>{{item.username}}</td>
                            <td>{{ formattingDate(item.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>




    </Main>
</template>

<script>
import TopBar from "./Components/TopBar.vue";
import SideNav from "./Components/SideNav.vue";
import Main from "./Main.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: 'Dashboard',
    props: {
        auth: Object
    },
    components: {
        Main,
        TopBar,
        SideNav

    },
    methods: {
      formattingDate(isoString) {
          const date = new Date(isoString);

          const year = date.getFullYear();
          const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
          const day = String(date.getDate()).padStart(2, '0');
          const hours = String(date.getHours()).padStart(2, '0');
          const minutes = String(date.getMinutes()).padStart(2, '0');
          const seconds = String(date.getSeconds()).padStart(2, '0');

          return `${day}/${month}/${year}`;
      }
    },
    setup() {
        const dashboardData = ref({});
        const start = ref('1900-01-01');

        const formatDate =  (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        };

        const end = ref(formatDate(new Date()));

        const applyFilter = async () => {
            dashboardData.value = await axios.get(`/dashboard-data?start=${start.value}&end=${end.value}`);
        };

        onMounted(async () => {
            await applyFilter();
        });

        return {
            dashboardData,
            applyFilter,
            start,
            end
        };
    },
    computed: {
        user() {
            return this.auth.user;
        }
    },

};
</script>

<style scoped>
* {
    font-size: 12px;
}
</style>
