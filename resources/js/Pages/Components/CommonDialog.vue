<template>
    <div v-if="isVisible" class="modal" tabindex="-1" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" class="btn-close" @click="closeDialog"></button>
                </div>
                <div class="modal-body">
                    {{content}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="submitData">OK, Proceed</button>
                    <button type="button" class="btn btn-secondary" @click="closeDialog">Cancel</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

export default {
    components: {},
    props: {
        title: {
            type: String,
            default: 'Dialog Title'
        },
        content: {
            type: String,
            default: 'Dialog Title'
        },
        isVisible: {
            type: Boolean,
            default: false
        },

    },
    methods: {
        closeDialog() {
            this.$emit('close');
        },
        submitData() {
            this.$emit('ok');
        },
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
