<script>
import Form from './components/Form.vue'

export default {
  components: {
    Form
  },

  props: {
    id: { type: String, default: '' },
    value: { },
    name: { type: String, default: '' }
  },

  data () {
    return {
      tab: null
    }
  },

  computed: {
    _breadcrumbs () {
      return [
        {
          text: 'Dashboard',
          disabled: false,
          href: '/'
        },
        {
          text: 'Pages',
          disabled: false,
          href: '#/pages'
        },
        {
          text: this.id ? 'Edit' : 'New',
          disabled: true
        }
      ]
    }
  },

  methods: {
    success () {
      this.$snackbar('saved')
    }
  }
}

</script>

<template>
  <div>
    <v-row>
      <v-breadcrumbs
        :items="_breadcrumbs"
        divider=">"
      />
      <v-spacer />
      <!-- <v-btn class="mx-2 primary" to="/pages/new">Create new page</v-btn> -->
    </v-row>

    <v-card>
      <v-card-text>
        <Form
          v-bind="$props"
          @success="success"
        />
      </v-card-text>
    </v-card>
  </div>
</template>
