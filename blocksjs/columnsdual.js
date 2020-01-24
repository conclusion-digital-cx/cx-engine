const html = (strings) => strings[0]

export default {
    name: 'columnsdual',

    props: {
        value: { type: Array, default: () => ([
            // two blocks
            [], []
        ]) },
        components: { type: Array, default: () => ([]) },

        blocks: {
            type: Array, default: () => ([
                {}, {}
            ])
        }
    },

    data() {
        return {
            content: "",
            // blocks: [
            //     [], []
            // ]
        }
    },

    watch: {
        // blocks: {
        //     handler(newValue) {
        //         console.log("pageblock change", newValue)
        //         this.$emit('input', newValue)
        //     },
        //     deep: true
        // },
        value(newValue) {
            console.log(newValue)
            // this.blocks = newValue

            // Tell parent
            this.$emit('input', newValue)
        }
    },

    methods: {
        onEnter() {
            this.$emit('done', this.content)
        },
        // onInput(blockIndex, $event) {
        //     console.log("blockIndex", blockIndex)
        //     this.$emit('update:blocks', this.blocks)
        // },
    },

    template: html`
    <div class="row">
        <div class="xs6">
            <div class="col">
                <editable :components="components" v-model="value[0]"/>
            </div>
        </div>
        <div class="xs6">
            <div class="col">
                <editable :components="components" v-model="value[1]"/>
            </div>
        </div>
        <!-- TODO: Dynamic -->
    </div>
    `
}