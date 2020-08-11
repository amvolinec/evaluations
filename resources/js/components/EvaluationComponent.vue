<template>

    <div class="card" v-bind:class="{ saved: isSaved }" id="evals">
        <div class="card-header">Evaluation</div>
        <div class="card-body">

            <div class="list-steps" id="evaluations">
                <div class="">
                    <div class="flex-xl-fill mb-3">
                        <div class="p-2 bd-highlight">
                            <input class="form-control-sm" type="text" v-model="evalName" placeholder="Name" @change="onChanged">
                        </div>
                        <div class="p-2 bd-highlight">
                            <input class="form-control-sm" type="text" v-model="evalDate" placeholder="Date" @change="onChanged">
                        </div>
                        <div class="p-2 bd-highlight">
                            <input class="form-control-sm" type="text" v-model="evalClient" placeholder="Client" @change="onChanged">
                        </div>
                    </div>

                    <input type="hidden" v-model="task">
                </div>

                <h5 class="text-center m-1">Answer</h5>
                <draggable v-model="revals" draggable=".group-line">
                    <div class="group-line no-border" v-for="reval in revals">
                        <div>
                            <div class="group-text" v-bind:tesk-id="reval.id">
                                <input class="eval-txt" type="text" v-model="reval.name" @change="onChanged">
                                <input class="eval-time" type="text" v-model="reval.time" @keyup="sumTime">
                            </div>
                            <button class="btn btn-sm btn-outline-danger" @click="deletePoint(reval)"
                                    v-bind:reval-id="reval.id"><i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </draggable>

                <div v-if="time > 0" class="span-time">
                    <label> Time (min): </label>
                    <input class="step-time" type="text" v-model="time" disabled>
                </div>

                <div class="time-to-add"><i class="fa fa-calculator" aria-hidden="true"></i> Total time: <span class="total-time" v-text="time / 60"></span> h.</div>

                <div class="mt-3" v-if="message.length > 0">
                    <div class="alert alert-danger" v-text="message"></div>
                </div>

                <h5 class="text-center m-1">Inquiry</h5>

                <div class="m-2">
                    <button class="btn btn-sm btn-success" @click="addOption"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="group-line no-border" v-for="option in options">
                    <textarea class="form-control eval-text" type="text" v-model="option.name" rows="5" @change="onChanged" oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'></textarea>
                </div>

                <div class="mt-1" v-if="time > 0">

                    <button class="btn btn-sm btn-success" @click="storeEvaluation" v-if="edit == false"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save Evaluation
                    </button>
                    <button class="btn btn-sm btn-success" @click="updateEvaluation" v-if="edit"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Update Evaluation
                    </button>
                    <button class="btn btn-sm btn-success" @click="emptyFields"><i class="fa fa-eraser" aria-hidden="true"></i> Clear</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    components: {
        draggable,
    },
    data() {
        return {
            task: 0,
            time: 0,
            total: 0,
            edit: false,
            isSaved: true,
            revals: [],
            options: [],
            evald: 0,
            evalName: 'FVTECH-',
            evalDate: '',
            evalClient: 'Telia',
            message: '',
        }
    },
    created() {
        this.evalDate = this.getDate().substring(0, 10);
    },
    mounted() {
        this.$root.$on('addEvaluation', (steps) => {
            let revals = this.revals;
            steps.forEach(function (e) {
                if (e.selected) {
                    e.selected = false;
                    let point = {name: e.name, time: e.time, step_id: e.id, group_id: e.group_id};
                    revals.push(point);
                }
            });
            this.revals = revals;
            this.sumTime();
            this.isSaved = false;
        });
        this.$root.$on('loadEvaluation', (id) => {

            axios.post('/evaluations/get/', {id: id})
                .then((r) => {
                    this.castData(r.data);
                }).catch((error) => {
                this.$root.fetchError(error);
            });
            this.edit = true;
            this.isSaved = true;
        });
    },
    methods: {
        addOption() {
            this.options.push({name: ''});
            this.isSaved = false;
        },
        storeEvaluation() {
            if (this.checkData()) {
                axios.post('/evaluation', {
                    name: this.evalName,
                    date: this.evalDate,
                    client: this.evalClient,
                    task_id: this.$root.$data.task,
                    options: this.options,
                    items: this.revals,
                }).then((response) => {
                    // this.emptyFields();
                }).catch((error) => {
                    this.$root.fetchError(error);
                });
                this.edit = false;
                this.isSaved = true;
            }
        },
        updateEvaluation() {
            if (this.checkData()) {
                axios.patch('/evaluation/' + this.evald, {
                    name: this.evalName,
                    date: this.evalDate,
                    client: this.evalClient,
                    task_id: this.$root.$data.task,
                    options: this.options,
                    items: this.revals,
                    id: this.evald
                }).catch((error) => {
                    this.$root.fetchError(error);
                });
                this.isSaved = true;
                // this.emptyFields();
            }
        },
        deletePoint(reval) {
            const index = this.revals.indexOf(reval);
            if (index > -1) {
                this.revals.splice(index, 1);
            }
            this.sumTime();
            this.isSaved = false;
        },
        getDate() {
            const event = new Date(Date.now());
            return event.toLocaleString('lt-LT', {timeZone: 'UTC'})
        }, sumTime() {
            let time = 0;
            this.revals.forEach(function (e) {
                time += parseInt(e.time);
            });
            this.time = time;
        }, emptyFields() {
            this.task = 0;
            this.time = 0;
            this.total = 0;
            this.edit = false;
            this.revals = [];
            this.options = [];
            this.evald = 0;
            this.evalName = 'FVTECH-';
            this.evalDate = '';
            this.evalOptions = '';
            this.evalClient = 'Telia';
            this.edit = false;
            this.isSaved = false;
        }, castData(d) {
            this.evald = d.id;
            this.evalName = d.name;
            this.evalDate = d.date;
            this.evalClient = d.client;
            this.task = d.task_id;
            this.revals = d.items;
            this.options = d.options;
            this.$root.$data.task = d.task_id;
            this.sumTime();
        }, checkData() {
            this.message = '';
            if (this.$root.$data.task == 0) {
                this.message += 'Select Task first!\n';
            }
            if (this.options.length == 0) {
                this.message += 'Add options!\n';
            }
            if (this.revals.length == 0) {
                this.message += 'Add items!\n';
            }
            if (this.message.length > 0) {
                return false;
            } else {
                return true;
            }
        },
        onUpdate: function (event) {
            // this.revals.splice(event.newIndex, 0, this.revals.splice(event.oldIndex, 1)[0])
        },
        onChanged: function (event) {
            this.isSaved = false;
        },
    },
    computed: {
        // revals: {
        //     get() {
        //         return this.$store.state.revals;
        //     },
        //     set(value) {
        //         this.$store.commit('updateList', value);
        //     }
        // }
    }
}
</script>
