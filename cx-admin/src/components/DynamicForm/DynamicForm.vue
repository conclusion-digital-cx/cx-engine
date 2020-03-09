<script>
import InputDate from './InputDate.vue'
import InputDateTime from './InputDateTime.vue'
import InputSelectRelation from './InputSelectRelation.vue'

export default {
  components: {
    InputDate,
    InputDateTime,
    InputSelectRelation
  },
  props: {
    name: { type: String, default: 'collections' },

    primaryKey: { type: String, default: '_id' },
    title: { type: String, default: 'No title' },
    value: {
      type: Object,
      description: 'The form values',
      default () {
        return {
          // name: 'Test'
        }
      }
    },
    fields: { type: Array,
      default () {
        return [
          {
            name: 'name',
            type: 'string',
            formType: 'text',
            minLength: 3,
            unique: true,
            configurable: false,
            required: true
          }
        ]
      } }
  },
  data () {
    return {
      rules: {
        required: value => !!value || 'Required.',
        counter: value => value.length <= 20 || 'Max 20 characters',
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          return pattern.test(value) || 'Invalid e-mail.'
        }
      },
      loading: false
      // _form: { ...this.form }
    }
  },
  computed: {
    options () { return this.value }
  },
  methods: {
    getRules (arrayWithStrings = []) {
      return arrayWithStrings.map(elem => {
        return this.rules[elem]
      })
    }
  }
}

</script>

<template>
  <div>
    <!-- <v-text-field v-model='value.file' type="file" label="Load from a file" />
      - or - -->
    <!-- <v-text-field v-model="form.name" label="Name" placeholder="e.g. products, news or menus" /> -->

    <!-- {{ fields }} -->
    <!-- <h2>Options</h2> -->
    <div
      v-for="(field,index) in fields"
      :key="index"
    >
      <!-- <small>{{ field.type }}</small> -->
      <!-- <small>{{ field }}</small> -->

      <!-- String -->
      <template v-if="field.type === 'string'">
        <v-text-field
          v-model="options[field.name]"
          autocomplete="new-password"
          :label="field.label || field.name"
          v-bind="field"
          clearable

          :rules="getRules(field.rules)"
        />
      </template>

      <!-- Number -->
      <template v-else-if="field.type === 'number'">
        <v-text-field
          v-model="options[field.name]"
          :label="field.label || field.name"
          v-bind="field"
          clearable

          :rules="getRules(field.rules)"
          type="number"
        />
      </template>

      <!-- TextArea -->
      <template v-else-if="field.type === 'textarea'">
        <v-textarea
          v-model="options[field.name]"
          :label="field.label || field.name"
          v-bind="field"
          clearable

          :rules="getRules(field.rules)"
        />
      </template>

      <!-- Boolean -->
      <template v-else-if="field.type === 'boolean'">
        <v-switch
          v-model="options[field.name]"
          :label="field.label || field.name"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- Image -->
      <template v-else-if="field.type === 'image'">
        <v-text-field
          v-model="options[field.name]"
          :label="field.label || field.name"
          v-bind="field"
          clearable
          type="file"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- Image -->
      <template v-else-if="field.type === 'avatar'">
        <InputAvatar
          v-model="options[field.name]"
          :label="field.label || field.name"
          v-bind="field"
          clearable
          type="file"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- JSON (TODO) -->
      <template v-else-if="field.type === 'json'">
        <v-textarea
          v-model="options[field.name]"
          :label="field.label || field.name"
          v-bind="field"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- Enum -->
      <template v-else-if="field.type === 'enum'">
        <v-select
          v-model="options[field.name]"
          v-bind="field"
          :items="field.enum.map(elem=> ({value:elem, text:elem}))"
          :label="field.label || field.name"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- date -->
      <template v-else-if="field.type === 'date'">
        <InputDate
          v-model="options[field.name]"
          v-bind="field"
          clearable
          :label="field.label || field.name"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- datetime -->
      <template v-else-if="field.type === 'datetime'">
        <InputDateTime
          v-model="options[field.name]"
          v-bind="field"
          clearable
          :label="field.label || field.name"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- relation -->
      <template v-else-if="field.type === 'relation' || field.target">
        <!-- <v-select
          v-model="options[field.name]"
          v-bind="field"
          :items="[]"
          :label="field.label || field.name"
          :rules="getRules(field.rules)"
        /> -->
        <!-- {{ field }} -->
        <InputSelectRelation
          :value="options[field.name]"
          :primary-key="primaryKey"
          :label="field.label || field.name"
          v-bind="field"
          @input="options[field.name] = $event || null"
        />
      </template>

      <!-- Default -->
      <template v-else>
        <v-text-field
          v-model="options[field.name]"
          autocomplete="new-password"
          :label="field.label || field.name"
          v-bind="field"
          :rules="getRules(field.rules)"
        />
      </template>

      <!-- {{ field.type }} -->
    </div>

    <!-- Hidden submit button to support onenter -->
    <v-btn
      class="d-none"
      type="submit"
    >
      Apply
    </v-btn>

    <!-- <v-textarea v-model="value.flat" label="Paste exported data" /> -->
  </div>
</template>
