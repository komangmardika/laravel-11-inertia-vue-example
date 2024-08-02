<template>
    <Main :auth="auth">
        <h1 style="font-size: 20px">Login Histories</h1>
        <DataTable
            ref="dataTable"
            class="display"
            :options="dataTableOptions"
        >
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
    name: 'Position',
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
                        const response = await axios.get('/login-histories-paginated', {
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
                    {data: 'id', title: 'ID'},
                    {data: 'name', title: 'Name'},
                    {data: 'username', title: 'Username'},
                    {data: 'created_at', title: 'Logged In At'},
                ],
                paging: true,
                searching: true,
                info: false,
                lengthChange: false
            }
        }
    },
}
</script>

<style scoped>
.margin-left-content {
    margin-left: 220px;
    margin-top: 10px;
    width: calc(100% - 250px);
}

/deep/ .dt-search label {
    display: none; /* Hide the label only */
}

* {
    font-size: 12px;
}
</style>
