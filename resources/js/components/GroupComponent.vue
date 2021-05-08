<template>
    <div class="list-groups" id="groups">

        <div id="bbtn" class="form-group">
            <button class="btn btn-sm btn-outline-success" v-if="!add" v-on:click="add = !add">+</button>
        </div>

        <div class="form-group with-btn" v-if="add">
            <input class="form-control-sm" type="text" v-model="groupToAdd">

            <div class="btn-group">
                <button class="btn btn-sm btn-success" @click="addGroup"><i class="fa fa-floppy-o"></i></button>
                <button class="btn btn-sm btn-secondary" v-on:click="add = false"><i class="fa fa-undo"></i>
                </button>
            </div>
        </div>

        <div class="group-line" v-for="group in groups">
            <div class="group-text" v-bind:class="{ 'toggle-item': group.selected }" @click="changeGroup(group, $event)"
                 v-bind:group-id="group.id">{{ group.name }}
            </div>
            <button class="btn btn-sm btn-outline-danger" @click="deleteGroup" v-bind:group-id="group.id"><i
                class="fa fa-trash-o" aria-hidden="true"></i></button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            groups: [],
            groupToAdd: '',
            selectedGroups: [],
            add: false,
        }
    },
    created() {
        this.fetchGroups();
    },
    mounted() {
        Echo.channel('home')
            .listen('NewGroup', (e) => {
                this.groups = e.groups;
            });
    },
    methods: {
        addGroup() {
            axios.post('/group', {
                name: this.groupToAdd
            })
                .then((response) => {
                })
                .catch((error) => {
                    this.$root.fetchError(error);
                });
            this.groupToAdd = '';
        },
        changeGroup(group, e) {
            if (e.ctrlKey) {
                group.selected = !group.selected;
            } else {
                this.groups.forEach(function (entry) {
                    entry.selected = false;
                });
                group.selected = true;
            }
            this.$root.$data.group = group.id;
            this.$root.$data.task = 0;
            this.$root.$emit('groupChanged', group.id);
        },
        deleteGroup(e) {
            let r = confirm("Delete this group?");
            if (r == true) {
                axios.delete('groups', {params: {'id': e.target.getAttribute('group-id')}})
                    .then((r) => {
                        console.log(r.data);
                    });
            }
        },
        fetchGroups() {
            axios.get('/groups').then(response => {
                this.groups = response.data;
            });
        }
    }
}
</script>
