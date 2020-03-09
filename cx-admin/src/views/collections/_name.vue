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
    }
  }
}
</script>

<template>
  <div>
    <!-- {{collection}} -->
    <!-- {{schema}} -->
    <!-- {{_itemTitle}} -->
    <!-- {{ items }} -->
    <v-breadcrumbs :items="_breadcrumbs" />

    <!-- <Form
      v-model="dialog"
      :props="props"
      :form="form"
      @success="fetch"
      :name="name"
      /> -->

    <Grid
      :items="items"
      :title-key="_itemTitle"
      @click:item="onClickItem"
      @click:edit="onClickEdit"
      @click:create="dialog = true"
    />
  </div>
</template>
