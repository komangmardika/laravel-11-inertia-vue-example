<template>
    <Main :auth="auth">
            <h1 style="font-size: 20px">Employee</h1>
            <button class="btn btn-outline-primary mt-5" @click="showDialog">Add New Employee</button>

            <NewUserDialog
                :title="title"
                :isVisible="isDialogVisible"
                :add-new="modeAddNew"
                :data="data"
                @userAdded="refreshDataTable"
                @open="showDialog"
                @close="closeDialog">
            </NewUserDialog>

            <CommonDialog
                :title="dialogDeleteTitle"
                :content="'Are you sure want to delete this data?'"
                :isVisible="isConfirmDeleteVisible"
                @close="closeConfirmationDelete"
                @ok="doDelete"
            ></CommonDialog>

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
import CommonDialog from "./Components/CommonDialog.vue";

export default {
    name: 'Employee',
    props: {
        auth: Object,
    },
    components: {
        CommonDialog,
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
            isConfirmDeleteVisible: false,
            modeAddNew: 'n',
            dataTableKey: 0, // Initialize the key
            title: 'Add New Employee',
            dialogDeleteTitle: 'Delete Employee',
            data: {
            },
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
            this.isDialogVisible = false;
        },
        closeConfirmationDelete($event) {
          this.isConfirmDeleteVisible = false;
        },
        showDialog($event) {
            this.isDialogVisible = true;
            this.title = 'Add New Employee';
            this.modeAddNew = 'n';

        },
        showDialog2() {
            this.isDialogVisible = true;
        },
        async refreshDataTable() {
            this.isDialogVisible = false;
            this.dataTableKey += 1;
        },
        viewEmployee($event) {
            this.modeAddNew = 'v';
            this.data = $event;
            this.title = 'View Employee';
            this.showDialog2();
        },
        editEmployee($event) {
            this.modeAddNew = 'e';
            this.data = $event;
            this.title = 'Edit Employee';
            this.showDialog2()
        },
        deleteEmployee($event) {
            this.modeAddNew = 'd';
            this.data = $event;
            this.isConfirmDeleteVisible = true;
        },
        doDelete($event) {

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
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
