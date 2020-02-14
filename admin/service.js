
const queryParams = params => Object.keys(params)
        .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
        .join('&')

export default {
    getCollectionById(id) {
        return fetch(`/api/collections/${id}`, {
            method: 'GET',
        }).then(resp => resp.json())
    },

    getCollectionByName(name) {
        return fetch(`/api/collections?name=${name}`, {
            method: 'GET',
        })
            .then(resp => resp.json())
            .then(resp => resp[0])
    },
    async getCollectionSchemaByName(name) {
        const schema = await this.getCollectionByName(name)
            .then(resp => resp.schemaJson)
        const arr = Object.entries(schema)
        console.log(arr)
        const entries = arr.map(([key, value]) => ({
            name: key,
            ...value
        }))
        return entries
    },

    // CRUD actions

    // GET /:entity
    get(name, options = {}) {
        return fetch(`/api/${name}`, {
            method: 'GET',
        }).then(resp => resp.json())
    },

    // TODO real server side
    // GET /:entity
    async getWithTotalItems(name, options = {}) {
        const resp = await fetch(`/api/${name}?${queryParams(options)}`, {
            method: 'GET',
        }).then(resp => resp.json())

        // FAKE
        return {
            items: resp,
            totalItems: resp.length
        }
    },

    // GET /:entity/:id
    getById(name, id) {
        return fetch(`/api/${name}/${id}`, {
            method: 'GET',
        }).then(resp => resp.json())
    },

    // Mixed
    createOrUpdateById(name, id = null, form = {}) {
        console.log(id)
        return id ?
            this.updateById(name, id, form) :
            this.create(name, form)
    },

    // POST /:entity
    create(name, form = {}) {
        return fetch(`/api/${name}`, {
            method: 'POST',
            body: JSON.stringify(form)
        }).then(resp => resp.json())
    },

    // PATCH /:entity/:id
    updateById(name, id, form = {}) {
        return fetch(`/api/${name}/${id}`, {
            method: 'PATCH',
            body: JSON.stringify(form)
        }).then(resp => resp.json())
    },

    // PUT /:entity/:id
    putById(name, id, form = {}) {
        return fetch(`/api/${name}/${id}`, {
            method: 'PATCH',
            body: JSON.stringify(form)
        }).then(resp => resp.json())
    },

    // DELETE /:entity/:id
    deleteById(name, id = null) {
        return fetch(`/api/${name}/${id}`, {
            method: 'DELETE',
        }).then(resp => resp.json())
    },
}