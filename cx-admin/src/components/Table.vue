<script>
export default {
  props: {
    name: { type: String, default: "pages" },
    status: { type: String, default: "" },
    id: { type: String, default: "" },
    headers: {
      type: Array, default: () => ([
        { text: 'Title', value: 'title' },
        { text: 'url', value: 'url' },
        { text: 'Datum', value: 'date' }
      ])
    }
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
          text: this.name,
          disabled: true,
          href: 'breadcrumbs_link_2',
        },
      ]
    },
  },

  data() {
    return {
      selection: [],
      loading: false,
      items: [],
      totalItems: 0,
      options: {},

      props: {
        id: {},
        url: {},
        body: { type: "textarea" },
        pageblock: { type: "json" }
      }
    }
  },

  watch: {
    status() {
      this.fetch()
    }
  },

  created() {
    this.fetch()
  },

  methods: {
    rowClick(item) {
      window.open(item.url)
      // window.open(item.url,"_self")
    },

    toLink(value = {}) {
      return `/pages/edit/${value.id}`
    },

    async fetch(options = {}) {
      // Get Content
      try {
        // const { type } = this
        // const endpoint = this.typeSlug // type.collectionName || type.name || this.typeSlug
        this.loading = true

        const resp = await this.$service.getWithTotalItems(this.name, {
          // 'state[!]': 'trash',
          // 'state': '',
          'state': this.status === 'published' ? 'NULL' : this.status,
          ...options
        })
        // console.log(resp)

        const { items, totalItems } = resp
        // const items = await service.get({
        //   params: {}
        // // { populate: ['company', 'user'] }
        // })
        this.totalItems = totalItems
        this.items = items
      } finally {
        this.loading = false
      }
    },


    async onClickDelete(selection = []) {
      // Get Content
      try {
        this.loading = true
        const tasks = selection.map(async (item) => {
          return item.state === 'trash' ?
            await this.$service.deleteById('pages', item.id) :
            await this.$service.updateById('pages', item.id, {
              state: 'trash'
            })
        })

        Promise.all(tasks).then(values => {
          console.log(values);
          // this.$emit('refresh')
          this.fetch()
        });

      } finally {
        this.loading = false
      }
    },
  }
}
</script>

<template>
   <div>

  <!-- <v-row>
  <v-breadcrumbs :items="_breadcrumbs" divider=">" />
    <v-spacer/>
    <v-btn class="mx-2 error" v-if="selection.length" @click="onClickDelete(selection)">Move to trash</v-btn>

    <v-btn class="mx-2 primary" to="/pages/new">Create new page</v-btn>
  </v-row> -->
<!-- {{$attrs}} -->
<v-btn class="mx-2 error" v-if="selection.length" @click="onClickDelete(selection)">Move to trash</v-btn>

  <v-data-table
  show-select
  v-model="selection"
        :loading="loading"
        :headers="headers"
        :items="loading ? [] : items"
        :server-items-length="totalItems"
        :options.sync="options"
        @click:row="rowClick"
      >
        <!-- TODO Custom table cells -->
        <template #item.title="{value, item}">
            <router-link :to="toLink(item)">{{value || "No name"}}</router-link>
        </template>
        <template #item.url="{value, item}">
            <a target="_blank" :href="toLink(item)">{{value || "No name"}}</a>
        </template>
      </v-data-table>
</div>
</template>