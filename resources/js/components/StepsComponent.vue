<template>
    <div class="list-steps" id="steps">

        <div id="bbtn" class="form-group" v-if="!add && !edit">
            <button class="btn btn-sm btn-outline-success" v-on:click="add = !add"><i aria-hidden="true" class="fa fa-plus"></i></button>
<!--            <input class="form-control-sm" type="text" v-model="search" v-on:keyup="findElements">-->
        </div>

        <div v-if="add || edit" id="steps-outer" class="mb-2">

            <div class="form-group" v-if="edit === false" style="margin-bottom: 0">
                <div class="inline mb-2" style="width: calc(100% + 30px);">
                    <input class="form-control-sm" type="text" v-model="stepToAdd" placeholder="Name"
                           @keyup="findStep($event)">

                    <div class="drop-box" v-if="temps.length > 0 && !escaped">
                        <div class="group-line down-item" v-for="temp in temps" @click="selectTemplate(temp)"
                             v-bind:temp-id="temp.id">{{ temp.name }} {{ temp.time }}-{{ temp.average }}h
                        </div>
                    </div>

                </div>

                <input type="text" v-model="timeToAdd" placeholder="Time: 1d:1h" @keyup="checkTime"
                       @focusout="escaped=false" v-bind:class="{ 'toggle-valid': timeValid }">

                <div class="btn-group">
                    <button class="btn btn-sm btn-success" @click="addStep"><i class="fa fa-floppy-o"
                                                                               aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-sm btn-secondary" v-on:click="add = false; escaped = false"><i class="fa fa-undo"
                                                                                         aria-hidden="true"></i>
                    </button>
                </div>

            </div>

            <div class="form-group" v-if="edit === true">
                <input type="hidden" v-model="stepEdit.id">
                <input type="text" v-model="stepEdit.name" placeholder="Name">
                <input type="number" v-model="stepEdit.time" placeholder="Time (min)">

                <div class="btn-group">
                    <button class="btn btn-sm btn-success" @click="saveStep"><i class="fa fa-floppy-o"
                                                                                aria-hidden="true"></i> Save Step
                    </button>
                    <button class="btn btn-sm btn-secondary" v-on:click="edit = false"><i class="fa fa-undo"
                                                                                          aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <span v-if="time > 0" class="span-time">
            <label>Time (h): </label>
            <input class="step-time" type="text" v-model="time" disabled>
        </span>

        </div>

        <div class="steps-inner" v-on:click="add = false">
            <div class="group-line" v-for="step in steps">
                <div>
                    <div class="group-text" v-bind:class="{ 'toggle-item': step.selected }"
                         @click="changeStep(step, $event)" v-bind:tesk-id="step.id">{{ step.name }} {{ step.time }}-{{ step.average > 0 ? step.average : step.time }}h
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" @click="editStep(step)" v-bind:step-id="step.id">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" @click="deleteStep(step)" v-bind:step-id="step.id">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-1" v-if="total > 0">
            <div class="time-to-add"><i class="fa fa-calculator" aria-hidden="true"></i> Total time: <span
                class="total-time" v-text="total"></span> h.
            </div>
            <button class="btn btn-sm btn-success" @click="addToEval"><i class="fa fa-cart-plus"
                                                                         aria-hidden="true"></i> Add To Evaluation
            </button>
        </div>

        <div v-if="task === 0"><small>Select Task first...</small></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            task: 0,
            time: 0,
            total: 0,
            timeValid: false,
            edit: false,
            escaped: false,
            steps: [],
            temps: [],
            stepToAdd: '',
            timeToAdd: '',
            stepEdit: {},
            add: false,
            find: false,
            search: '',
        }
    },
    created() {
        this.fetchSteps();
    },
    mounted() {
        this.$root.$on('taskChanged', (task) => {
            this.task = task;
            this.fetchSteps();
        });
    },
    methods: {
        addStep() {
            axios.post('/step', {
                name: this.stepToAdd,
                time: this.time,
                task_id: this.task,
                group_id: this.$root.$data.group,
            })
                .then((r) => {
                    this.steps = r.data;
                })
                .catch((error) => {
                    this.$root.fetchError(error);
                });
            this.stepToAdd = '';
            this.timeToAdd = '';
            this.timeValid = false;
            this.escaped = false;
            this.time = 0;
        },
        changeStep(step, e) {
            if (e.ctrlKey) {
                step.selected = !step.selected;
            } else {
                step.selected = !step.selected;
            }
            this.sumTotal();
            this.$root.$data.step = step.id;
        },
        fetchSteps() {
            let task = this.$root.$data.task ? this.$root.$data.task : this.task;
            this.task = task;
            axios.get('/steps/' + task).then(response => {
                this.steps = response.data;
            }).catch((error) => {
                this.$root.fetchError(error);
            });
        },
        deleteStep(step) {
            let r = confirm("Delete this step?");
            if (r === true) {
                axios.post('steps', {id: step.id, task_id: this.task})
                    .then((r) => {
                        this.fetchSteps();
                    }).catch((error) => {
                    this.$root.fetchError(error);
                });
            }
        },
        checkTime() {
            this.time = 0;
            let time = this.timeToAdd.trim().split(':');
            time.forEach(e => this.addTime(e));
        },
        addTime(e) {
            if (e.indexOf('min') > -1) {
                this.time += this.removeChr(e) / 60;
            } else if (e.indexOf('d') > -1) {
                this.time += this.removeChr(e) * 8;
            } else {
                this.time += this.removeChr(e);
            }
            this.timeValid = this.time > 0;
        },
        removeChr(e) {
            return parseInt(e.replace(/\D/g, ''));
        },
        editStep(step) {
            this.stepEdit = step;
            this.edit = true;
        },
        saveStep() {
            axios.patch('/step/' + this.stepEdit.id, {
                name: this.stepEdit.name,
                time: this.stepEdit.time,
                task_id: this.task,
            });
            this.edit = false;
        }, sumTotal() {
            let total = 0;
            this.steps.forEach(e => {
                if (e.selected) {
                    total += parseInt(e.time);
                }
            });
            this.total = total;
        }, addToEval() {
            let steps = this.steps;
            this.$root.$emit('addEvaluation', steps);
        }, findStep(e) {
            if (e.keyCode === 27) {
                this.escaped = true;
                this.temps = [];
            } else {
                axios.post('/steps/find/', {name: this.stepToAdd}).then(r => {
                    this.temps = r.data;
                }).catch((error) => {
                    this.$root.fetchError(error);
                });
            }
        }, selectTemplate(temp) {
            axios.post('/step/associate/', {step_id: temp.id, task_id: this.$root.$data.task}).then(r => {
                console.log(r.data);
            }).catch((error) => {
                this.$root.fetchError(error);
            });
            this.steps.push(temp);
            this.stepToAdd = '';
            this.temps = [];
            this.escaped = false;
        }, findElements() {
            console.log(this.search)
        }
    }
}
</script>
