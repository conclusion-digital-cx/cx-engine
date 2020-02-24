<script>
export default {
  props: {
    value: { type: String, default: '' }
  },
  data () {
    return {
      time: null,
      date: null,
      menu: false,
      menu2: false,
      modal2: false
    }
  }
  // TODO emit propper datetime
}
</script>

<template>
  <div>
    {{ date }}
    {{ time }}
    <v-row class="ma-0">
      <!-- <v-date-picker
          v-model="options[field.name]"
          v-bind="field"
          :label="field.label || field.name"
          :rules="getRules(field.rules)"
        /> -->

      <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="290px"
      >
        <template v-slot:activator="{ on }">
          <v-text-field
            v-model="date"
            prepend-icon="event"
            v-bind="$attrs"
            readonly
            v-on="on"
          />
        </template>
        <v-date-picker v-model="date" @input="menu = false" />
      </v-menu>

      <v-menu
        ref="menu2"
        v-model="menu2"
        :close-on-content-click="false"
        :return-value.sync="time"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="290px"
      >
        <template v-slot:activator="{ on }">
          <v-text-field
            prepend-icon="access_time"
            readonly
            :value="time"
            v-bind="$attrs"
            v-on="on"
          />
        </template>
        <v-time-picker
          v-if="menu2"
          v-model="time"
          full-width
          @click:minute="$refs.menu2.save(time)"
        />
      </v-menu>
    </v-row>
  </div>
</template>
