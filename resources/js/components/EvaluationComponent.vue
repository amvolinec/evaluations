<template>
    <div class="list-steps" id="evaluations">
        <div class="">
            <div class="flex-xl-fill mb-3">
                <div class="p-2 bd-highlight">
                    <input class="form-control-sm" type="text" v-model="evalName" placeholder="Name">
                </div>
                <div class="p-2 bd-highlight">
                    <input class="form-control-sm" type="text" v-model="evalDate" placeholder="Date">
                </div>
                <div class="p-2 bd-highlight">
                    <input class="form-control-sm" type="text" v-model="evalClient" placeholder="Client">
                </div>
            </div>

            <input type="hidden" v-model="task">
        </div>

        <h5 class="m-1">Inquiry</h5>

        <div class="m-2">
            <button class="btn btn-sm btn-success" @click="addOption"><span>+</span></button>
        </div>

        <div class="group-line no-border" v-for="option in options">
            <textarea class="form-control eval-text" type="text" v-model="option.name" rows="5"></textarea>
        </div>

        <span v-if="time > 0" class="span-time">
            <label>Time (min): </label>
            <input class="step-time" type="text" v-model="time" disabled>
        </span>

        <h5 class="m-1">Answer</h5>

        <div class="group-line no-border" v-for="reval in revals">
            <div>
                <div class="group-text" v-bind:tesk-id="reval.id">
                    <input class="eval-txt" type="text" v-model="reval.name">
                    <input class="eval-time" type="text" v-model="reval.time" @keyup="sumTime">
                </div>
                <button class="btn btn-sm btn-outline-danger" @click="deletePoint(reval)" v-bind:reval-id="reval.id">x
                </button>
            </div>
        </div>
        <div class="mt-1" v-if="time > 0">
            <div class="time-to-add">Total time: <span class="total-time" v-text="time / 60"></span> h.</div>
            <button class="btn btn-sm btn-success" @click="storeEvaluation" v-if="edit == false">Save Evaluation
            </button>
            <button class="btn btn-sm btn-success" @click="updateEvaluation" v-if="edit">Update Evaluation</button>
            <button class="btn btn-sm btn-success" @click="emptyFields">Clear</button>
        </div>
        <div class="mt-3" v-if="message.length > 0">
            <div class="alert alert-danger" v-text="message"></div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                task: 0,
                time: 0,
                total: 0,
                edit: false,
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
            });
            this.$root.$on('loadEvaluation', (id) => {

                axios.post('/evaluations/get/', {id: id})
                    .then((r) => {
                        this.castData(r.data);
                    }).catch((error) => {
                    this.$root.fetchError(error);
                });
                this.edit = true;
            });
        },
        methods: {
            addOption() {
                this.options.push({name: ''});
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
                        this.emptyFields();
                    }).catch((error) => {
                        this.$root.fetchError(error);
                    });
                    this.edit = false;
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
                    this.emptyFields();
                }
            },
            deletePoint(reval) {
                const index = this.revals.indexOf(reval);
                if (index > -1) {
                    this.revals.splice(index, 1);
                }
                this.sumTime();
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
            }
        }
    }
</script>
