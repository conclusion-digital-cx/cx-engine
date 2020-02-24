<script>
import Grid from './Grid'
import Form from './Form'

export default {
  components: {
    Grid,
    Form
  },
  props: {
    name: { type: String, default: '' },
    id: { type: String, default: '' },
    title: { type: String, default: '' },

    titleKey: { type: String, default: 'title' },
    props: { type: Object, default () { return { } } }
  },

  data () {
    return {
      loading: true,
      items: [],
      dialog: false,
      form: {}
    }
  },

  watch: {
    $route (to, from) {
      // react to route changes...
      this.fetch()
    }
  },

  async mounted () {
    this.fetch()
  },

  methods: {
    async fetch () {
      try {
        this.loading = true
        const resp = await fetch(`/api/${this.name}`)
        this.items = await resp.json()
      } finally {
        this.loading = false
      }
    },

    async deleteItem (item) {
      try {
        this.loading = true
        const resp = await fetch(`/api/${this.name}/${item.id}`, {
          method: 'DELETE'
        })

        // Remove item from UI
        this.items.splice(this.items.indexOf(item), 1)
      } finally {
        this.loading = false
      }
    },

    editItem (item) {
      console.log(item)
      this.dialog = true
      this.form = item
    },

    itemClick (item) {
      this.$emit('itemClick', item)
    }
  }
}
</script>

<template>
  <v-container grid-list-xl>
    <h1>{{ title }}</h1>

    <Form
      v-model="dialog"
      :props="props"
      :form="form"
      :name="name"
      @success="fetch"
    />

    <!-- <slot/> -->

    <v-row>
      <v-btn class="btn" @click="dialog = true">
        Create new
      </v-btn>
      <slot name="actions-left" />
      <v-spacer />
      <slot name="actions-right" />
    </v-row>

    <v-layout wrap>
      <!-- Grid -->
      <Grid :loading="loading" :items="items">
        <template #card="{item}">
          <!-- <v-card
                  :href="item.url"
                  @click="editItem(item)"
                  class="show-actions-on-hover"> -->

          <v-card
            class="show-actions-on-hover"
            @click="itemClick(item)"
          >
            <v-card-title>
              <div class="text-truncate" style="width:50%">
                {{ item[titleKey] || item.name || item.title || 'No title' }}
              </div>
              <v-spacer />

              <div class="actions text-right">
                <v-btn icon text class="pull-right" @click.stop="editItem(item)">
                  <v-icon>edit</v-icon>
                </v-btn>
              </div>

              <div class="actions text-right">
                <v-btn icon text class="pull-right" @click.stop="deleteItem(item)">
                  <v-icon>close</v-icon>
                </v-btn>
              </div>
            </v-card-title>
          </v-card>
        </template>
      </Grid>
    </v-layout>
  </v-container>
</template>
