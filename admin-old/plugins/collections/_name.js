const html = (strings) => strings[0]

import List from '../../components/List.js'

export default {
  components: {
    List
  },

  props: {
    name: { type: String, default: "" },
  },

  data() {
    return {
      loading: false,

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
    _breadcrumbs() {
      return [
        {
          text: 'Dashboard',
          disabled: false,
          href: '/',
        },
        {
          text: 'Collections',
          disabled: false,
          href: '/collections',
        },
        {
          text: this.name,
          disabled: true,
          href: 'breadcrumbs_link_2',
        },
      ]
    },

    _itemTitle() {
      return this.collection.titleKey || 'title'
    }
  },

  async created() {
    // try {
    //   this.loading = true

    //   // Get schema
    //   const resp = await fetch(`/api/collections/${this.name}`, {
    //     method: 'GET',
    //   }).then(resp => resp.json())
    //   this.collection = resp

    //   const schema = JSON.parse(resp.schemaJson)
    //   // Convert to Object ?
    //   // schema.forEach(elem => {
    //   //   this.props[elem.name] = { type: elem.type }
    //   // })
    //   this.props = schema
    // } catch (err) {
    //   alert(err)
    // } finally {
    //   this.loading = false
    //   // Close form
    //   this.$emit('input', false)
    // }
  },

  methods: {
    onClickItem(item) {
      console.log('TODO')
      // window.open(item.url)
      // this.$router.push(`/collections/${item.name}`)
      // window.open(item.url,"_self")
    },
    onClickEdit(item) {
      console.log('TODO')
      // window.open(item.url)
      // this.$router.push(`/collections/${item.name}`)
      // window.open(item.url,"_self")
    },

    // DEBUG
    async createTable() {
      try {
        this.loading = true
        // const resp = await fetch(`/api/collections/${this.name}/create`, { 
        const resp = await fetch(`/api/collections/${this.name}/createfromjson`, {
          method: 'GET',
          // body: JSON.stringify(form) 
        })
        this.$emit('success')

      } finally {
        this.loading = false
        // Close form
        this.$emit('input', false)
      }
    }
  },

  template: html`
  <div>
    <!-- {{collection}} -->
    <!-- {{schema}} -->
    <!-- {{_itemTitle}} -->

    <v-breadcrumbs :items="_breadcrumbs"></v-breadcrumbs>

    <!-- <Form 
      v-model="dialog" 
      :props="props" 
      :form="form" 
      @success="fetch"
      :name="name"
      /> -->

    <List 
    :name="name" 
    :titleKey="_itemTitle" 
    @click:item="onClickItem"
    @click:edit="onClickEdit"
    @click:create="dialog = true"
    >
      <template slot="actions-right">
        <!-- <v-btn class="mx-2" :loading="loading" @click="createTable">create table</v-btn> -->
        <!-- <v-btn class="mx-2" @click="editCollection">change collection</v-btn> -->
      </template>
    </List>
  </div>
  `
}