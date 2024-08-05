<template>
    <Main :auth="auth">
            <h1 style="font-size: 20px">Employee</h1>
            <button class="btn btn-outline-primary mt-5" @click="showDialog">Add New Employee</button>
            <NewUserDialog
                :title="'Add New Employee'"
                :isVisible="isDialogVisible"
                @userAdded="refreshDataTable"
                @open="showDialog"
                @close="closeDialog"></NewUserDialog>
            <DataTable
                :key="dataTableKey"
                ref="dataTable"
                class="display"
                :options="dataTableOptions"
            >
                <template #column-6="action">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-primary" @click="editEmployee(action.rowData)">Edit</button>
                        <button type="button" class="btn btn-info" @click="viewEmployee(action.rowData)">View</button>
                        <button type="button" class="btn btn-danger" @click="deleteEmployee(action.rowData)">Delete</button>
                    </div>
                </template>
            </DataTable>
    </Main>
</template>

<script>
import NewUserDialog from "./Components/NewUserDialog.vue";
import TopBar from "./Components/TopBar.vue";
import SideNav from "./Components/SideNav.vue";
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
import DataTablesLib from 'datatables.net';
import 'datatables.net-dt/css/dataTables.dataTables.min.css';

DataTable.use(DataTablesLib);
import axios from 'axios';
import Main from "./Main.vue";

export default {
    name: 'Employee',
    props: {
        auth: Object,
    },
    components: {
        Main,
        NewUserDialog,
        TopBar,
        SideNav,
        DataTable,
        DataTablesCore,
        DataTablesLib
    },
    data() {
        return {
            isDialogVisible: false,
            dataTableKey: 0, // Initialize the key
            dataTableOptions: {
                processing: true,
                serverSide: true,
                ajax: async (data, callback, settings) => {
                    try {
                        const response = await axios.get('/employees-paginated', {
                            params: {
                                start: data.start,
                                length: data.length,
                                search: data.search.value
                            }
                        });
                        callback({
                            draw: data.draw,
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.data
                        });
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                },
                columns: [
                    { data: 'id', title: 'ID', className: 'text-end' },
                    { data: 'name', title: 'Name' },
                    { data: 'username', title: 'Username' },
                    { data: 'unit', title: 'Unit' },
                    { data: 'positions', title: 'Position' },
                    { data: 'join_date', title: 'Join Date' }
                ],
                paging: true,
                searching: true,
                info: false,
                lengthChange: false
            }
        }
    },
    computed: {
        user() {
            return this.auth.user;
        }
    },
    methods: {
        closeDialog($event) {
            console.log('y',$event);
            this.isDialogVisible = false;
        },
        showDialog($event) {
            console.log('x',$event)
            this.isDialogVisible = true;
        },
        async refreshDataTable() {
            this.isDialogVisible = false;
            this.dataTableKey += 1;
        },
        viewEmployee($event) {
            console.log($event);
        },
        editEmployee($event) {
            console.log($event);
        },
        deleteEmployee($event) {
            console.log($event)
        }
    },
}
</script>

<style scoped>
/deep/ .dt-search label {
    display: none; /* Hide the label only */
}
* {
   font-size: 12px;
}
</style>
