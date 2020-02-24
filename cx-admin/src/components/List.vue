<script>

export default {
  props: {
    name: { type: String, default: '' },
    id: { type: String, default: '' },
    title: { type: String, default: '' },

    titleKey: { type: String, default: 'title' }
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
        const resp = await this.$service.getAll(this.name)
        this.items = resp
      } finally {
        this.loading = false
      }
    },

    async deleteItem (item) {
      try {
        this.loading = true
        const resp = await this.$service.deleteById(this.name, item.id)

        // Remove item from UI
        this.items.splice(this.items.indexOf(item), 1)
      } finally {
        this.loading = false
      }
    },

    editItem (item) {
      this.$emit('click:edit', item)
    },

    itemClick (item) {
      this.$emit('click:item', item)
    }
  }
}
</script>

<template>
  <v-container grid-list-xl>
    <h1>{{ title }}</h1>

    <slot />

    <v-row>
      <slot name="title" />
      <slot name="actions-left" />
      <v-spacer />
      <v-btn
        class="primary"
        @click="$emit('click:create')"
      >
        Create new
      </v-btn>
      <slot name="actions-right" />
    </v-row>

    <v-layout wrap>
      <!-- Grid -->
      <Grid
        :loading="loading"
        :items="items"
      >
        <template #card="{item}">
          <v-card
            class="show-actions-on-hover"
            @click="itemClick(item)"
          >
            <v-img
              class="white--text align-end"
              height="200px"
              :src="item.imageUrl || item.image || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
            />

            <v-card-title>
              <div
                class="text-truncate"
                style="width:50%"
              >
                {{ item[titleKey] || item.name || item.title || 'No title' }}
              </div>
              <v-spacer />

              <div class="actions text-right">
                <v-btn
                  icon
                  text
                  class="pull-right"
                  @click.stop="editItem(item)"
                >
                  <v-icon>edit</v-icon>
                </v-btn>
              </div>

              <div class="actions text-right">
                <v-btn
                  icon
                  text
                  class="pull-right"
                  @click.stop="deleteItem(item)"
                >
                  <v-icon>close</v-icon>
                </v-btn>
              </div>
            </v-card-title>

            <slot name="card" />
          </v-card>
        </template>
      </Grid>
    </v-layout>
  </v-container>
</template>
