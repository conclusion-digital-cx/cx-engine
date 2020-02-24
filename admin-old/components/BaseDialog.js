const html = (strings) => strings[0]

export default {
  props: {
    width: { type: Number, default: 500 },
    value: { type: Boolean },
    title: { type: String, default: '' }
  },

  template: html`<v-dialog
  scrollable
  v-bind="$attrs"
  :value="value"
  :width="width"
  @input="$emit('input',$event)"
>
  <v-card>
    <v-card-title
      class="headline lighten-2"
      primary-title
    >
      {{ title }}
      <v-spacer />
      <v-btn icon @click="$emit('input',false)">
        <v-icon>close</v-icon>
      </v-btn>
    </v-card-title>

    <v-divider />

    <v-card-text style="max-height:100%">
      <slot />
    </v-card-text>

    <v-divider />

    <v-card-actions>
      <slot name="actions" />
    </v-card-actions>
  </v-card>
</v-dialog>`
}
