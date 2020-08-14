<template>
    <div class="" id="list">
        <div class="fa" v-if="!showList" @click="toggleList"><i class="fa fa-address-book
        " aria-hidden="true"></i></div>
        <div v-if="showList">
            <div class="fa" @click="toggleList"><i class="fa fa-address-book
        " aria-hidden="true"></i></div>
            <table class="table table-sm table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" width="10%">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col" width="20%">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr class="group-line" v-for="item in items">
                    <th scope="row">{{ item.id }}</th>
                    <td>{{ item.name }}</td>
                    <td>{{ item.email }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary" @click="itemLoad(item)"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        <button class="btn btn-sm btn-outline-danger" @click="itemDelete(item)"><i class="fa fa-trash-o"
                                                                                                   aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="showEdit">
                <div class="form-group">
                    <input type="hidden" v-model="editItem.id">
                    <input type="text" v-model="editItem.name">
                    <input type="text" v-model="editItem.email">
                    <button class="btn btn-sm btn-outline-success" @click="saveItem(editItem)"><i class="fa fa-save"
                                                                                               aria-hidden="true"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            fields: [],
            editItem: [],
            showEdit: false,
            showList: false,
        }
    },
    created() {
        this.fetchItems();
    },
    methods: {
        fetchItems() {
            axios.get('/users').then(response => {
                this.items = response.data.users;
                this.fields = response.data.fields;
            }).catch((error) => {
                this.$root.fetchError(error);
            });
        }, itemDelete(item) {
            let r = confirm("Delete this Evaluation?");
            if (r == true) {
                axios.delete('/users/' + item.id).then(response => {
                }).catch((error) => {
                    this.$root.fetchError(error);
                });
            }
        }, toggleList(){
            this.showList = !this.showList;
        }, itemLoad(item) {
            this.showEdit = true;
            this.editItem = item;
        }, saveItem(saveItem) {
            axios.post('/users/', saveItem).then(response => {
                this.fetchItems();
                this.showEdit = false;
            }).catch((error) => {
                this.$root.fetchError(error);
            });
        }
    }
}
</script>
