<template>
    <Main :auth="auth">
        <h1 style="font-size: 20px">Units</h1>
        <DataTable
            ref="dataTable"
            class="display"
            :options="dataTableOptions"
        >
            <template #column-3="action">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" @click="editUnit(action.rowData)">Edit</button>
                    <button type="button" class="btn btn-info" @click="viewUnit(action.rowData)">View</button>
                    <button type="button" class="btn btn-danger" @click="deleteUnit(action.rowData)">Delete</button>
                </div>
            </template>
        </DataTable>
    </Main>
</template>

<script>
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
    name: 'Unit',
    props: {
        auth: Object,
    },
    components: {
        Main,
        TopBar,
        SideNav,
        DataTable,
        DataTablesCore,
        DataTablesLib
    },
    data() {
        return {
            dataTableOptions: {
                processing: true,
                serverSide: true,
                ajax: async (data, callback, settings) => {
                    try {
                        const response = await axios.get('/units-paginated', {
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
                    { data: 'name', title: 'Unit' },
                    { data: 'created_at', title: 'Created At' },
                ],
                paging: true,
                searching: true,
                info: false,
                lengthChange: false
            }
        }
    },
    methods: {
        viewUnit($event) {
            console.log($event);
        },
        editUnit($event) {
            console.log($event);
        },
        deleteUnit($event) {
            console.log($event);
        }
    }
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
