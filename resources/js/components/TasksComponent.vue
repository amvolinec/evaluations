<template>
    <div class="list-tasks" id="tasks" v-if="tasks.length > 0">

        <div id="bbtn" class="form-group">
            <button class="btn btn-sm btn-outline-success" v-if="!add" v-on:click="add = !add"><i aria-hidden="true" class="fa fa-plus"></i></button>
        </div>

        <div class="form-group with-btn" v-if="add">
            <input class="form-control-sm" type="text" v-model="taskToAdd">
            <input type="hidden" v-model="group">

            <div class="btn-group">
                <button class="btn btn-sm btn-success" @click="addTask"><i class="fa fa-floppy-o"
                                                                           aria-hidden="true"></i></button>
                <button class="btn btn-sm btn-secondary" v-on:click="add = false"><i class="fa fa-undo"></i>
                </button>
            </div>

        </div>


        <div class="group-line" v-for="task in tasks">
            <div>
                <div class="group-text" v-bind:class="{ 'toggle-item': task.selected }"
                     @click="changeTask(task, $event)" v-bind:tesk-id="task.id">{{ task.name }}
                </div>
                <button class="btn btn-sm btn-outline-danger" @click="deleteTask(task)" v-bind:task-id="task.id"><i
                    class="fa fa-trash-o" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            group: 0,
            tasks: [],
            taskToAdd: '',
            add: false
        }
    },
    created() {
        this.fetchTasks();
    },
    mounted() {
        this.$root.$on('groupChanged', (group) => {
            this.group = group;
            this.fetchTasks();
            this.$root.$emit('taskChanged', 0);
        })
    },
    methods: {
        addTask() {
            axios.post('/task', {
                name: this.taskToAdd,
                group_id: this.group
            })
                .then((r) => {
                    this.tasks = r.data;
                })
                .catch((error) => {
                    this.$root.fetchError(error);
                });

            this.taskToAdd = '';
        },
        changeTask(task, e) {
            if (e.ctrlKey) {
                task.selected = !task.selected;
            } else {
                this.tasks.forEach(function (entry) {
                    entry.selected = false;
                });
                task.selected = true;
            }
            this.$root.$data.task = task.id;
            this.$root.$emit('taskChanged', task.id);
        },
        fetchTasks() {
            let group = this.$root.$data.group ? this.$root.$data.group : this.group;
            this.group = group;
            axios.get('/tasks/' + group).then(response => {
                this.tasks = response.data;
            });
        },
        deleteTask(task) {
            let r = confirm("Delete this task?");
            if (r == true) {
                axios.delete('tasks', {params: {'id': task.id}})
                    .then((r) => {
                        this.fetchTasks();
                    });
            }
        }
    }
}
</script>
