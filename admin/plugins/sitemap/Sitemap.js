const html = (strings) => strings[0]

export default {
  created() {
    this.fetch()
  },
  methods: {
    async fetch() {
      try {
        this.loading = true
        const resp = await fetch(`/api/${this.name}`)
        this.items = await resp.json()
      } finally {
        this.loading = false
      }
    },
  },
  data: () => ({
    name: 'pages',
    items: [
      {
        id: 1,
        name: 'Applications :',
        children: [
          { id: 2, name: 'Calendar : app' },
          { id: 3, name: 'Chrome : app' },
          { id: 4, name: 'Webstorm : app' },
        ],
      },
      {
        id: 5,
        name: 'Documents :',
        children: [
          {
            id: 6,
            name: 'vuetify :',
            children: [
              {
                id: 7,
                name: 'src :',
                children: [
                  { id: 8, name: 'index : ts' },
                  { id: 9, name: 'bootstrap : ts' },
                ],
              },
            ],
          },
          {
            id: 10,
            name: 'material2 :',
            children: [
              {
                id: 11,
                name: 'src :',
                children: [
                  { id: 12, name: 'v-btn : ts' },
                  { id: 13, name: 'v-card : ts' },
                  { id: 14, name: 'v-window : ts' },
                ],
              },
            ],
          },
        ],
      },
      {
        id: 15,
        name: 'Downloads :',
        children: [
          { id: 16, name: 'October : pdf' },
          { id: 17, name: 'November : pdf' },
          { id: 18, name: 'Tutorial : html' },
        ],
      },
      {
        id: 19,
        name: 'Videos :',
        children: [
          {
            id: 20,
            name: 'Tutorials :',
            children: [
              { id: 21, name: 'Basic layouts : mp4' },
              { id: 22, name: 'Advanced techniques : mp4' },
              { id: 23, name: 'All about app : dir' },
            ],
          },
          { id: 24, name: 'Intro : mov' },
          { id: 25, name: 'Conference introduction : avi' },
        ],
      },
    ],
  }),

  template: html`
    <v-treeview
    selectable
    open-on-click
    hoverable 
    :items="items"
    dense
    >
    <template #label="{item}">
      <div class="d-flex">
      #{{item.id}} {{item.url}}
      <v-spacer/>
      <v-btn outlined :to="item.url" target="_blank">open</v-btn>
</div>
    </template>
  </v-treeview>
     `
}
