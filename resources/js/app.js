/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/GroupComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('groups', require('./components/GroupComponent.vue').default);
Vue.component('tasks', require('./components/TasksComponent.vue').default);
Vue.component('steps', require('./components/StepsComponent.vue').default);
Vue.component('evaluations', require('./components/EvaluationComponent.vue').default);
Vue.component('eval-items', require('./components/ListComponent.vue').default);
Vue.component('errors', require('./components/ErrorsComponent.vue').default);
Vue.component('users', require('./components/UserComponent.vue').default);
Vue.component('find', require('./components/FindComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        group: 0,
        task: 0,
        error: false,
        errorMessage: '',
    },
    methods: {
        fetchError(error){
            if (error.response) {
                // The request was made and the server responded with a status code
                // that falls out of the range of 2xx
                console.log(error.response.data);
                this.error = true;
                // let message = '';
                // error.response.data.errors.forEach( (key, value) => {
                //     message += e;
                // });
                this.errorMessage = error.response.data.message;
                console.log(error.response.status);
                console.log(error.response.headers);
            } else if (error.request) {
                // The request was made but no response was received
                // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                // http.ClientRequest in node.js
                console.log(error.request);
            } else {
                // Something happened in setting up the request that triggered an Error
                console.log('Error', error.message);
            }
            console.log(error.config);
        }
    }
});
