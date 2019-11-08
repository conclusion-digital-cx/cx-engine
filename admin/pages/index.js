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
        id: { },
        url: {},
        body: { type: "textarea" },
        pageblock: { type: "json" }
      }
    }
  },
  methods: {
    itemClick(item) {
      window.open(item.url)
      // window.open(item.url,"_self")
    }
  },

  template: html`
  <List :id="id" 
  name="pages" 
  title-key="url" 
  @itemClick="itemClick"
  :props="props" />
  `
}