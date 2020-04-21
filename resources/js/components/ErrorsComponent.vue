<template>
    <div class="container mt-3">
        <div class="alert alert-danger" v-if="this.$root.$data.error">
            <button type="button" class="close" data-dismiss="alert" @click="closeAlert">x</button>
            <div v-text="this.$root.$data.errorMessage"></div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            closeAlert() {
                this.$root.$data.error = false;
                this.$root.$data.errorMessage = '';
            }
        }
    }
</script>
