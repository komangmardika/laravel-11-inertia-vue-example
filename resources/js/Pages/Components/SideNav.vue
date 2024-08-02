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
};
</script>

<style scoped>
.sidenav {
    width: 200px;
    height: 100vh;
    background-color: #f4f4f4;
    padding: 1rem;
    position: fixed;
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
