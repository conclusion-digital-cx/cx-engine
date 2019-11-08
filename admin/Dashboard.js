const html = (strings) => strings[0]

import Grid from './components/Grid.js'
  

export default {
    components: {
        Grid
    },
    data() {
        return {
            message: 'You loaded this page on ' + new Date().toLocaleString(),
            layouts: [],
            blocks: [],
            pages: [],
            users: [],
            collections: [],
            editor: true
        }
    },
    async created() {
        let resp

        resp = await fetch('/api/layouts')
        this.layouts = await resp.json()

        resp = await fetch('/api/pages')
        this.pages = await resp.json()

        resp = await fetch('/api/collections')
        this.collections = await resp.json()

        resp = await fetch('/api/blocks')
        this.blocks = await resp.json()

        resp = await fetch('/api/users')
        this.users = await resp.json()
    },
    methods: {
        async add(item) {
            console.log(item)

            // Render
            const url = item.name || '/blocks/image.php'
            const resp = await fetch(url, { method: 'GET' })
            item.render = await resp.text()

            this.page.push(item)
        },

        deleteBlock(item) {
            this.page.splice(this.page.indexOf(item), 1);
        },

        async save(page) {
            const resp = await fetch('/api/pages', { method: 'POST' })
            const json = await resp.json()
        }
    },
    template: html`
    <div>
    <v-btn class="btn" href="/">Visit home</v-btn>

    <h1>Layouts</h1>
    <!-- <v-btn class="btn" @click="add">Add block</v-btn> -->

    <Grid :items="layouts">
        <template #card="{item}">
            <v-card :href="item.url" class="show-actions-on-hover">
                <v-card-title>
                    <div class="text-truncate" style="width:60%">
                        {{ item.name || 'No title' }}
                    </div>
                    <v-spacer />
                </v-card-title>
            </v-card>
        </template>
    </Grid>

    <h1>Pages</h1>
    <v-btn class="btn" @click="add">Add page</v-btn>

    <Grid :items="pages">
        <template #card="{item}">
            <v-card :href="item.url" class="show-actions-on-hover">
                <v-card-title>
                    <div class="text-truncate" style="width:60%">
                        <!-- {{ item.name || 'No title' }} -->
                        {{item.url}}
                    </div>
                    <v-spacer />
                </v-card-title>
            </v-card>
        </template>
    </Grid>

    <h1>Collections</h1>
    <v-btn class="btn" @click="add">Add collection</v-btn>

    <Grid :items="collections">
        <template #card="{item}">
            <v-card :to="\`/collection/\${item.name}\`" class="show-actions-on-hover">
                <v-card-title>
                    <div class="text-truncate" style="width:60%">
                        <!-- {{ item.name || 'No title' }} -->
                        {{item.name}}
                    </div>
                    <v-spacer />
                </v-card-title>
            </v-card>
        </template>
    </Grid>

    <h1>Blocks</h1>
    <!-- <v-btn class="btn" @click="add">Add block</v-btn> -->

    <Grid :items="blocks">
        <template #card="{item}">
            <v-card :href="item.url" class="show-actions-on-hover">
                <v-card-title>
                    <div class="text-truncate" style="width:60%">
                        {{ item.name || 'No title' }}
                    </div>
                    <v-spacer />
                </v-card-title>
            </v-card>
        </template>
    </Grid>

    <h1>Users</h1>
    <!-- <v-btn class="btn" @click="add">Add block</v-btn> -->

    <Grid :items="users">
        <template #card="{item}">
            <v-card :href="item.url" class="show-actions-on-hover">
                <v-card-title>
                    <div class="text-truncate" style="width:60%">
                        {{ item.username || '-No username-' }}
                    </div>
                    <v-spacer />
                </v-card-title>

                <v-card-text>
                    <div>
                        {{item.email}}
                    </div>
                </v-card-text>
            </v-card>
        </template>
    </Grid>
    </div>`
}