<script>
export default {
  props: {
    name: { type: String, default: '' },
    id: { type: String, default: '' }
  },

  data () {
    return {
      props: {
        // id: {},
        name: { type: 'text', placeholder: 'e.g. products, news or menus' },
        titleKey: { type: 'text', placeholder: 'specify the key of the card titles e.g. title' },
        // schema: { type: "textarea" },
        schemaJson: { type: 'textarea', placeholder: 'e.g. { "title": :{"type":"text"}, }' }
      }
    }
  },
  methods: {
    onClickItem (item) {
      this.$router.push(`/collections/${item.name}?id=${item.id}`)
    },
    onClickEdit (item) {
      this.$router.push(`/collections/edit/${item.name}`)
    },
    onClickCreate (item) {
      this.$router.push(`/collections/new`)
    }
  }
}

</script>

<template>
  <div>
    <List
      :id="id"
      :name="name"
      item-title="url"
      :props="props"
      @click:item="onClickItem"
      @click:edit="onClickEdit"
      @click:create="onClickCreate"
    >
      <template #title>
        Collections
      </template>
    </List>

    <router-view />
  </div>
</template>
