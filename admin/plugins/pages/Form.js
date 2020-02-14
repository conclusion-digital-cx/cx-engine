import Form from './components/Form.js'

const html = (strings) => strings[0]

export default {
    components: {
        Form
    },

    props: {
        id: { type: String, default: "" },
        value: {  },
        name: { type: String, default: "" },
    },

    computed: {
        _breadcrumbs() {
            return [
                {
                    text: 'Dashboard',
                    disabled: false,
                    href: '/',
                },
                {
                    text: 'Pages',
                    disabled: false,
                    href: '#/pages',
                },
                {
                    text: this.id ? 'Edit' : 'New',
                    disabled: true,
                }
            ]
        },
    },

    data() {
        return {
            tab: null,
        }
    },

    methods: {
        success() {
            this.$snackbar("saved")
        }
    },

    template: html`
    <div>
    <v-row>
    <v-breadcrumbs :items="_breadcrumbs" divider=">" />
      <v-spacer/>
      <!-- <v-btn class="mx-2 primary" to="/pages/new">Create new page</v-btn> -->
    </v-row>

    <v-card>
        <v-card-text>
        <Form 
        v-bind="$props" 
        @success="success"/>
        </v-card-text>
    </v-card>
    </div>
  `
}