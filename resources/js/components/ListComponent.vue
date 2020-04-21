<template>
    <div class="" id="list">
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Date</th>
                <th scope="col">Client</th>
                <th scope="col">Time</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr class="group-line" v-for="item in items">
                <th scope="row">{{ item.id}}</th>
                <td>{{ item.name}}</td>
                <td>{{ item.date}}</td>
                <td>{{ item.client}}</td>
                <td>{{ sumTimes(item.items)}} val.</td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary" @click="itemLoad(item)">Load</button>
                    <a class="btn btn-sm btn-outline-dark" :href="getUri(item)" target="_blank">View</a>
                    <button class="btn btn-sm btn-outline-success" @click="itemExport(item)">Export</button>
                    <button class="btn btn-sm btn-outline-danger" @click="itemDelete(item)">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: [],
            }
        },
        created() {
            this.fetchItems();
        },
        mounted() {
            Echo.channel('new-eval')
                .listen('NewEvaluation', (e) => {
                    console.log(e);
                    this.items = e.evaluations;
                });
        },
        methods: {
            fetchItems() {
                axios.get('/evaluations').then(response => {
                    this.items = response.data;
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
                    let link = window.location.href + '/reports/' + response.data;
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
                    return parseInt(total / 60);
                } else {
                    return '';
                }
            }
        }
    }
</script>
