const html = (strings) => strings[0]

export default {
    name: 'banner',
    props: {
        value: { type: String }
    },

    methods: {
        onEnter(e) {
            this.$emit('finish')
        },
        onInput(e) {
            // console.log(e)
            this.$emit('input', e)
            this.$emit('render', `
            <div class="introduction-text-content col-12 col-xl-8 offset-xl-2">
            <p>${this.value}</p>
            </div>`)
        },
    },

    template: html`
   <div class="row banner-wrapper">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 450 73">
<path fill="currentColor" fill-rule="nonzero" d="M450 74V30.273c-14.628-8.017-30.82-14.85-48.668-20.589-70.564-22.683-153.456-1.275-226.228 20.77C114.384 51.408 54.216 70.175-10 73.728L450 74z"></path>
</svg>
<div class="banner-content col-12">
<h2>Uitgebreide productinformatie</h2>
<div class="banner-buttons">
<a class="btn btn-primary btn-lg" href="http://inventum.com/downloadcentrum/" target="_blank">Downloadcentrum</a>
</div>
</div>
</div>
    `
}