const html = (strings) => strings[0]

import List from '../components/List.js'

export default {
  components: {
    List
  },

  props: {
    name: { type: String, default: "" },
    id: { type: String, default: "" }
  },

  data() {
    return {
      props: {
        // id: {},
        name: { type: "text", placeholder: "e.g. products, news or menus" },
        titleKey: { type: "text", placeholder: "specify the key of the card titles e.g. title" },
        // schema: { type: "textarea" },
        schemaJson: { type: "textarea", placeholder: 'e.g. { "title": :{"type":"text"}, }' },
      }
    }
  },
  methods: {
    itemClick(item) {
      // window.open(item.url)
      this.$router.push(`/collections/${item.name}?id=${item.id}`)
      // window.open(item.url,"_self")
    }
  },

  template: html`
  <div>

  <h1>
collections
</h1>

  <List :id="id" 
  :name="name" 
  item-title="url" 
  @itemClick="itemClick"
  :props="props" />
  </div>
  `
}