<template>

    <div class="card" v-bind:class="{ saved: isSaved }" id="evals">
        <div class="card-header">4. Evaluation #ID {{ evald }} v.{{ version }}</div>
        <div class="card-body">

            <div class="list-steps row" id="evaluations">
                <div class="col-md-12">
                    <div class="">
                        <div class="flex-xl-fill mb-3">
                            <div class="p-1 bd-highlight">
                                <input class="form-control-sm" type="text" v-model="evalName" placeholder="Name"
                                       @change="onChanged">
                            </div>
                            <div class="p-1 bd-highlight">
                                <input class="form-control-sm" type="text" v-model="evalDate" placeholder="Date"
                                       @change="onChanged">
                            </div>
                            <div class="p-1 bd-highlight">
                                <input class="form-control-sm" type="text" v-model="evalClient" placeholder="Client"
                                       @change="onChanged">
                            </div>
                        </div>

                        <input type="hidden" v-model="task">
                    </div>

                    <h5 class="text-center m-1">Answer:</h5>
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


                    <div class="d-flex flex-row mt-3">

                        <div class="btn-group p-2">
                            <button class="btn btn-sm btn-outline-success p-2"><i class="fa fa-calculator"
                                                                                  v-on:click="showPopup"
                                                                                  aria-hidden="true"></i></button>
                        </div>

                        <div class="p-2"><span class="total-time"></span> Total time:</div>
                        <div class="p-2" v-if="!popup"><span class="total-time" v-text="time"></span> h.</div>
                        <div class="p-2" v-if="popup"><input class="form-control-sm" type="text" v-model="timeTemp">
                        </div>

                        <div class="btn-group p-2">
                            <button class="btn btn-sm btn-success" v-if="popup" @click="recalc"><i
                                class="fa fa-floppy-o"
                                aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary" v-if="popup" v-on:click="popup = false">
                                <i class="fa fa-undo" aria-hidden="true"></i></button>
                        </div>

                        <div class="p-2" v-if="!popup && versions.length > 0">
                            <select name="version" id="version" v-model="version">
                                <option v-for="o in versions" v-bind:value="o.version">v.{{ o.version }} - {{ o.sum }}
                                    h
                                </option>
                            </select>
                            <button class="btn btn-sm btn-outline-success" v-if="version > 0" @click="restore()"><i
                                class="fa fa-undo"
                                aria-hidden="true"></i></button>
                        </div>

                    </div>

                    <div v-if="popup" class="ml-4 mb-3">
                        <small>change the value or specify a percentage change ( +10% ; -20% )</small>
                    </div>

                    <div class="mt-3" v-if="message.length > 0">
                        <div class="alert alert-danger" v-text="message"></div>
                    </div>


                </div>

                <div class="col-md-12">
                    <h5 class="text-center m-1">Inquiry:</h5>

                    <div class="btm-group m-2">
                        <button class="btn btn-sm btn-outline-success" v-on:click="addOption"><i
                            class="fa fa-plus"
                            aria-hidden="true"></i></button>
                        <button class="btn btn-sm btn-success" v-if="editOption" @click="onChanged"><i
                            class="fa fa-floppy-o"
                            aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary" v-if="editOption" v-on:click="editOption = false">
                            <i class="fa fa-undo" aria-hidden="true"></i></button>
                    </div>

                    <div class="group-line no-border">
                        <ol>
                            <li v-for="option in options">
                                <span v-if="!option.edit">{{ option.name }}</span>
                                <textarea class="form-control eval-text" type="text" v-if="option.edit"
                                          v-model="option.name"
                                          rows="5"></textarea>
                                <button class="btn btn-sm btn-outline-secondary" v-if="!option.edit"
                                        @click="option.edit=true"><i
                                    class="fa fa-pencil"
                                    aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" v-if="!option.edit"
                                        @click="deleteOption(option)"><i
                                    class="fa fa-trash-o"
                                    aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-sm btn-success" v-if="option.edit" @click="option.edit=false"><i
                                    class="fa fa-floppy-o"
                                    aria-hidden="true"></i>
                                </button>

                            </li>
                        </ol>
                    </div>

                    <div class="form-group">
                        <textarea class="form-control eval-text" type="text" v-if="editOption" v-model="newOption"
                                  rows="5"
                                  oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'></textarea>
                    </div>

                    <div class="mt-3 ml-3" v-if="time > 0">
                        <button class="btn btn-sm btn-success" @click="storeEvaluation" v-if="edit===false"><i
                            class="fa fa-floppy-o" aria-hidden="true"></i> Save Evaluation
                        </button>
                        <button class="btn btn-sm btn-success" @click="updateEvaluation" v-if="edit"><i
                            class="fa fa-cloud-upload" aria-hidden="true"></i> Update Evaluation
                        </button>
                        <button class="btn btn-sm btn-success" @click="emptyFields"><i class="fa fa-eraser"
                                                                                       aria-hidden="true"></i> Clear
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" @click="itemClone()">
                            <i class="fa fa-clone" aria-hidden="true"></i> Clone
                        </button>
                    </div>

                </div>

                <div class="col-md-12">
                    <h5 class="text-center m-1">Specifications:</h5>
                    <ckeditor v-model="specification" :config="editorConfig"></ckeditor>
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
            popup: false,
            timeTemp: "",
            newOption: '',
            editOption: false,
            diff: 0,
            newTime: 0,
            versions: [],
            version: 1,
            specification: '',
            editorConfig: {}
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
                    let point = {
                        name: e.name,
                        time: e.average === 0 ? e.time : e.average,
                        step_id: e.id,
                        group_id: e.group_id
                    };
                    revals.push(point);
                }
            });
            this.revals = revals;
            this.sumTime();
            this.isSaved = false;
        });
        this.$root.$on('loadEvaluation', (id) => {
            this.get(id);
        });
    },
    methods: {
        get(id) {
            axios.post('/evaluations/get/', {id: id})
                .then((r) => {
                    this.castData(r.data);
                })
                .catch((error) => {
                    this.$root.fetchError(error);
                });
            this.edit = true;
            this.isSaved = true;
        },
        addOption() {
            this.options.push({name: '', edit: false});
            this.isSaved = false;
            // this.editOption = true;
        },
        storeEvaluation() {
            if (this.checkData()) {
                axios.post('/evaluation', {
                    name: this.evalName,
                    date: this.evalDate,
                    client: this.evalClient,
                    task_id: this.$root.$data.task,
                    version: this.version,
                    options: this.options,
                    items: this.revals,
                    specification: this.specification,
                }).then((r) => {
                    this.evalId = r.data;
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
                    id: this.evald,
                    specification: this.specification,
                }).then((r) => {

                    console.log(r.data);
                    this.version = r.data.version;
                    this.versions = r.data.versions;

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
            this.specification = '';

        }, castData: function (r) {

            let d = r.eval;

            this.versions = r.versions;

            this.evald = d.id;
            this.evalName = d.name;
            this.evalDate = d.date;
            this.evalClient = d.client;
            this.task = d.task_id;
            this.revals = d.items;
            this.options = d.options;
            this.version = d.version;
            this.specification = d.specification;
            this.$root.$data.task = d.task_id;
            this.sumTime();

        }, checkData() {

            this.message = '';

            if (this.$root.$data.task === 0) {
                this.message += 'Select Task first!\n';
            }
            if (this.options.length === 0) {
                this.message += 'Add options!\n';
            }
            if (this.revals.length === 0) {
                this.message += 'Add items!\n';
            }

            return this.message.length <= 0;
        },
        onUpdate: function (event) {
            // this.revals.splice(event.newIndex, 0, this.revals.splice(event.oldIndex, 1)[0])
        },
        onChanged: function (event) {

            this.editOption = false;
            this.isSaved = false;
            this.newOption = '';

        }, itemClone() {

            axios.post('/clone/', {'id': this.evald, 'options': this.options, 'items': this.revals}).then(r => {
                console.log(r.data);
            }).catch((error) => {
                this.$root.fetchError(error);
            });

        }, showPopup() {

            this.popup = true;
            this.timeTemp = this.time;

        }, recalc() {
            this.popup = false;
            this.timeTemp = this.timeTemp.trim();
            this.newTime = 0;
            let sign = this.timeTemp.substring(0, 1);
            let oldVal = parseInt(this.time);

            if (sign === '+' || sign === '-') {
                this.newTime = parseInt(this.timeTemp.substring(0, this.timeTemp.length - 1))
                this.newTime = oldVal + (this.newTime * 0.01 * oldVal)
            } else {
                this.newTime = parseInt(this.timeTemp);
            }
            this.diff = this.newTime - oldVal;

            this.createRevision();

            console.log('new value ' + this.newTime + ' sign ' + sign);

        }, deleteOption(option) {

            let result = confirm("Do you really want to delete: " + option.name.substring(0, 50) + "... ?");

            if (result === false) {
                return false;
            }

            const index = this.options.indexOf(option);

            if (index > -1) {
                this.options.splice(index, 1);
            }

            this.isSaved = false;

        }, storeRevision() {

            axios.post('/evaluations/revision/' + this.evald, {
                id: this.evald,
                items: this.revals,
                options: this.options
            }).then((r) => {

                if (r.data.status === 'success') {

                    this.version = r.data.version;
                    this.resolveDiff();
                }

            }).catch((error) => {
                this.$root.fetchError(error);
            });

        }, createRevision() {

            if (this.diff === 0) return false;

            this.storeRevision();

            this.isSaved = false;

        }, resolveDiff() {

            if (typeof this.diff !== 'number' || this.diff === 0) {
                console.log('Error with type diff ' + (typeof this.diff) + ' or diff = 0')
                return
            }

            let v = this.diff > 0 ? 1 : -1;

            do {
                for (let i = 0; i < this.revals.length; i++) {

                    if (this.diff !== 0) {

                        if (i > 0) {
                            this.revals[i].time += v;
                            this.diff -= v;
                        }

                    } else {
                        this.time = this.newTime
                        return true;
                    }

                }
            }
            while (this.diff !== 0);

            this.time = this.newTime
            this.isSaved = false;

        }, restore() {

            axios.get('/evaluations/' + this.evald + '/revision/' + this.version).then((r) => {

                console.log(r.data);
                this.revals = r.data.items;
                // this.version = r.data.version;

                this.sumTime();

                // this.get(this.evald);

            }).catch((error) => {

                this.$root.fetchError(error);

            });
        }
    }, computed: {}
}
</script>
