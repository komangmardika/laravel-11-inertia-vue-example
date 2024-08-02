<template>
    <div class="wrapper">
        <TopBar :username="user.name" />
        <SideNav />

        <div class="margin-left-content">
            <slot></slot>
        </div>

    </div>
</template>

<script>
import TopBar from "./Components/TopBar.vue";
import SideNav from "./Components/SideNav.vue";

export default {
    name: 'Main',
    props: {
        auth: Object
    },
    components: {
        TopBar,
        SideNav

    },
    data() {
        return {

        }
    },
    computed: {
        user() {
            return this.auth.user;
        }
    },
    methods: {

    }

};
</script>

<style scoped>
.margin-left-content {
    margin-left: 220px;
    margin-top: 10px;
    width: calc(100% - 250px);
}
</style>
