<template v-slot:default>
    <div>
        <v-app id="inspire">
            <v-simple-table height="300px">
                <thead>
                <tr>
                    <th class="text-left">
                        Name
                    </th>
                    <th class="text-left">
                        Required Groups
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in scenarios"
                    :key="item.id"
                >
                    <td>{{ item.name }}</td>
                    <td>{{ item.groups_names }}</td>
                </tr>
                </tbody>
            </v-simple-table>
        </v-app>
    </div>
</template>

<script>
export default {
    name: "Scenario",
    data() {
        return {
            scenarios: []
        }
    }, mounted() {
        this.getScenarios()
    }, methods: {
        getScenarios() {
            axios.get('/scenario')
                .then((r) => {
                    console.log(r.data)
                    this.scenarios = r.data
                })
        }
    }
}
</script>

<style scoped>

</style>
