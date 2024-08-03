<template>
    <aside class="sidenav">
        <ul>
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/employee">Employee</a></li>
            <li><a href="/unit">Unit</a></li>
            <li><a href="/position">Position</a></li>
            <li><a href="/login-history">Login History</a></li>
        </ul>
    </aside>
</template>

<script>
export default {
    name: 'SideNav',
    data() {
        return {

        }
    }
};
</script>

<style scoped>
.sidenav {
    width: 200px;
    height: 92vh;
    background-color: #f4f4f4;
    padding: 1rem;
    position: fixed;
    margin-left: 8px;
    margin-top: 18px;
    border-radius: 10px;
    box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
}

.sidenav ul {
    list-style-type: none;
    padding: 0;
}

.sidenav li {
    margin: 1rem 0;
}

.sidenav a {
    text-decoration: none;
    color: #333;
    font-size: 12px;
}
</style>
