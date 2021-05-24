<template>
    <v-app id="inspire">
        <v-card
            outlined
            shaped
            tile
            elevation="2">
            <v-card-title>
                <h4>Create User</h4>
            </v-card-title>
            <v-card-text>
                <form>
                    <v-text-field
                        v-model="name"
                        :error-messages="nameErrors"
                        :counter="25"
                        label="Name"
                        required
                        @input="$v.name.$touch()"
                        @blur="$v.name.$touch()"
                    ></v-text-field>
                    <v-text-field
                        v-model="email"
                        :error-messages="emailErrors"
                        label="E-mail"
                        required
                        @input="$v.email.$touch()"
                        @blur="$v.email.$touch()"
                    ></v-text-field>
                    <v-text-field
                        v-model="password"
                        :error-messages="passwordErrors"
                        :counter="20"
                        label="Password"
                        required
                        @input="$v.password.$touch()"
                        @blur="$v.password.$touch()"
                    ></v-text-field>
                    <v-select
                        v-model="select"
                        :items="items"
                        :hint="`${select.value}, ${select.text}`"
                        item-text="text"
                        item-value="value"
                        :error-messages="selectErrors"
                        label="Role"
                        required
                        @change="$v.select.$touch()"
                        @blur="$v.select.$touch()"
                    ></v-select>
                    <v-checkbox
                        v-model="checkbox"
                        :error-messages="checkboxErrors"
                        label="Do you agree?"
                        required
                        @change="$v.checkbox.$touch()"
                        @blur="$v.checkbox.$touch()"
                    ></v-checkbox>

                    <v-btn
                        class="mr-4"
                        @click="submit"
                    >
                        submit
                    </v-btn>
                    <v-btn @click="clear">
                        clear
                    </v-btn>
                </form>
            </v-card-text>
        </v-card>
    </v-app>
</template>

<script>
import {required, email, minLength, maxLength} from 'vuelidate/lib/validators'

export default {
    name: "CreateUser",
    validations: {
        name: {required, maxLength: maxLength(25)},
        email: {required, email},
        password: {required, minLength: minLength(8), maxLength: maxLength(20)},
        select: {
            value(val){
                return val
            }
        },
        checkbox: {
            checked(val) {
                return val
            },
        },
    },

    data: () => ({
        name: '',
        email: '',
        password: '',
        select: { value: 'user', text: 'User'},
        items: [
            { value: 'super-admin', text: 'Super Admin'},
            { value: 'admin', text: 'Admin'},
            { value: 'user', text: 'User'},
        ],
        checkbox: false,
    }),

    computed: {
        checkboxErrors() {
            const errors = []
            if (!this.$v.checkbox.$dirty) return errors
            !this.$v.checkbox.checked && errors.push('You must agree to continue!')
            return errors
        },
        selectErrors() {
            const errors = []
            if (!this.$v.select.$dirty) return errors
            !this.$v.select.value && errors.push('Role is required')
            return errors
        },
        nameErrors() {
            const errors = []
            if (!this.$v.name.$dirty) return errors
            !this.$v.name.maxLength && errors.push('Name must be at most 25 characters long')
            !this.$v.name.required && errors.push('Name is required.')
            return errors
        },
        emailErrors() {
            const errors = []
            if (!this.$v.email.$dirty) return errors
            !this.$v.email.email && errors.push('Must be valid e-mail')
            !this.$v.email.required && errors.push('E-mail is required')
            return errors
        },
        passwordErrors() {
            const errors = []
            if (!this.$v.password.$dirty) return errors
            !this.$v.password.required && errors.push('Password is required')
            !this.$v.password.minLength && errors.push('Password must be at least 8 characters long')
            !this.$v.password.maxLength && errors.push('Password must be at most 20 characters long')
            return errors
        },
    },

    methods: {
        submit() {
            this.$v.$touch()
            console.log('Submitted')
            axios.post('user', {name: this.name, password: this.password, email: this.email, role: this.select})
                .then((r) => {
                    console.log(r)
                })
                .catch((error) => {
                    this.$root.fetchError(error)
                });
        },
        clear() {
            this.$v.$reset()
            this.name = ''
            this.email = ''
            this.password = ''
            this.select = { value: 'user', text: 'User'}
            this.checkbox = false
        },
    },
}
</script>

<style scoped>

</style>
