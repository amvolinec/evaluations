<template>
    <div class="" id="list">
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col" width="10%">#</th>
                <th scope="col">Name</th>
                <th scope="col">Date</th>
                <th scope="col">Client</th>
                <th scope="col" width="90">Time</th>
                <th scope="col" width="140">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr class="group-line" v-for="item in items">
                <th scope="row">{{ item.id }}</th>
                <td>{{ item.name }}</td>
                <td>{{ item.date }}</td>
                <td>{{ item.client }}</td>
                <td class="text-right">{{ sumTimes(item.items) }} val.</td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary" @click="itemLoad(item)"><i
                        class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    <a class="btn btn-sm btn-outline-dark" :href="getUri(item)" target="_blank"><i class="fa fa-eye"
                                                                                                   aria-hidden="true"></i></a>
                    <button class="btn btn-sm btn-outline-success" @click="itemExport(item)"><i
                        class="fa fa-file-word-o" aria-hidden="true"></i></button>
                    <button class="btn btn-sm btn-outline-danger" @click="itemDelete(item)"><i class="fa fa-trash-o"
                                                                                               aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
        <pagination v-model="page" :records="records" :per-page="10" :options="options" @paginate="fetchItems"/>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            page: 1,
            records: 0,
            options: { theme: 'bootstrap4' }
        }
    },
    created() {
        this.fetchItems();
    },
    mounted() {
        Echo.channel('new-eval')
            .listen('NewEvaluation', (e) => {
                this.items = e.evaluations;
            });
    },
    methods: {
        fetchItems() {
            axios.get('/evaluations/?page=' + this.page).then(r => {
                this.items = r.data.evaluations;
                this.records = r.data.records;
            }).catch((error) => {
                this.$root.fetchError(error);
            });
        }, itemDelete(item) {
            let r = confirm("Delete this Evaluation?");
            if (r == true) {
                axios.delete('/evaluation/' + item.id).then(response => {
                }).catch((error) => {
                    this.$root.fetchError(error);
                });
            }
        }, itemExport(item) {
            axios.post('/export/' + item.id).then(response => {
                console.log(response.data);
                let link = 'reports/' + response.data;
                window.open(link);
            }).catch((error) => {
                this.$root.fetchError(error);
            });
        }, getUri(item) {
            return '/evaluation/' + item.id;
        }, itemLoad(item) {
            this.$root.$emit('loadEvaluation', item.id);
        }, sumTimes(i) {
            let total = 0;
            if (typeof i !== 'undefined') {
                i.forEach(e => total += e.time);
                return parseInt(total);
            } else {
                return '';
            }
        }
    }
}
</script>
