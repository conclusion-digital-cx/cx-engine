<script>

export default {
  props: {
    name: { type: String, default: '' }
  },

  data () {
    return {
      loading: false,
      items: [],

      // Add / edit
      form: {},
      dialog: false,

      collection: {
        titleKey: 'title'
      },
      schema: [],

      props: {
        // id: {},
        // scheme: { type: "textarea" },
      }
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
          text: 'Collections',
          disabled: false,
          href: '/collections'
        },
        {
          text: this.name,
          disabled: true,
          href: 'breadcrumbs_link_2'
        }
      ]
    },

    _itemTitle () {
      return this.collection.titleKey || 'title'
    }
  },

  async created () {
    this.items = await this.$service.getAll(this.name)
  },

  methods: {
    onClickItem (item) {
      console.log('TODO')
      // window.open(item.url)
      // this.$router.push(`/collections/${item.name}`)
      // window.open(item.url,"_self")
    },
    onClickEdit (item) {
      console.log('TODO')
      // window.open(item.url)
      // this.$router.push(`/collections/${item.name}`)
      // window.open(item.url,"_self")
    },

    // DEBUG
    async createTable () {
      try {
        this.loading = true
        // const resp = await fetch(`/api/collections/${this.name}/create`, {
        const resp = await fetch(`/api/collections/${this.name}/createfromjson`, {
          method: 'GET'
          // body: JSON.stringify(form)
        })
        this.$emit('success')
      } finally {
        this.loading = false
        // Close form
        this.$emit('input', false)
      }
    }
  }
}
</script>

<template>
  <div>
    <!-- {{collection}} -->
    <!-- {{schema}} -->
    <!-- {{_itemTitle}} -->

    <v-breadcrumbs :items="_breadcrumbs" />

    <!-- <Form
      v-model="dialog"
      :props="props"
      :form="form"
      @success="fetch"
      :name="name"
      /> -->

    <Grid
      :name="name"
      :title-key="_itemTitle"
      @click:item="onClickItem"
      @click:edit="onClickEdit"
      @click:create="dialog = true"
    >
      <template slot="actions-right">
        <!-- <v-btn class="mx-2" :loading="loading" @click="createTable">create table</v-btn> -->
        <!-- <v-btn class="mx-2" @click="editCollection">change collection</v-btn> -->
      </template>
    </Grid>
  </div>
</template>
