import Table from './components/Table.js'

const html = (strings) => strings[0]

export default {
  components: {
    Table
  },

  props: {
    status: { type: String, default: "" },

    tabs: {
      type: Array, default: () => ([
        { tab: 'One', content: 'Tab 1 Content' },
        { tab: 'Two', content: 'Tab 2 Content' },
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
          text: 'Pages',
          disabled: false,
          href: '#/pages',
        },
        {
          text: this.status,
          disabled: true,
          href: 'breadcrumbs_link_2',
        },
      ]
    },
  },

  data() {
    return {
      tab: null,
    }
  },
  template: html`
    <div>
    <v-row>
    <v-breadcrumbs :items="_breadcrumbs" divider=">" />
      <v-spacer/>
      <v-btn class="mx-2 primary" to="/pages/new">Create new page</v-btn>
    </v-row>

    <v-card>
    <v-tabs
    v-model="tab"
    dark
  >
    <v-tab
      v-for="item in tabs"
      :key="item.tab"
      :to="item.path"
    >
      {{ item.tab }}
    </v-tab>
  </v-tabs>
  <!-- {{$attrs}} -->
  <!-- {{$props}} -->
  <Table :status="status"/>
  <!-- <router-view v-bind="$props" /> -->
  <!-- <v-tabs-items v-model="tab">
      <v-tab-item
        v-for="item in tabs"
        :key="item.tab"
      >
        <v-card flat>
          <component v-bind="$attrs" :is="item.component"/>
        </v-card>
      </v-tab-item>
    </v-tabs-items> -->
    </v-card>
    </div>
  `
}