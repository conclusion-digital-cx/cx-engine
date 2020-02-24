
const queryParams = params => Object.keys(params)
  .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
  .join('&')

export default (config = {}) => ({
  // setBearerToken (token) {
  //   authHeader = `Bearer ${token}`
  // },

  // Raw
  fetchRaw (path, options = { method: 'GET' }) {
    const {
      server = window.server || 'http://localhost:8666/api',
      headers = ''
    } = config
    // console.log(config)

    return fetch(`${server}${path}`, {
      ...options,
      headers: {
        'Content-Type': 'application/json',
        ...headers
      }
      // headers
      // headers: {
      //   Authorization: `Bearer ${window.localStorage.getItem('token')}`
      // }
    })
  },

  fetch (path, options = { method: 'GET' }) {
    return this.fetchRaw(path, options).then(resp => resp.json())
  },

  // Collection
  getCollectionById (id) {
    return this.fetch(`/collections/${id}`, {
      method: 'GET'
    })
  },

  getCollectionByName (name) {
    return this.fetch(`/collections?name=${name}`, {
      method: 'GET'
    })
      .then(resp => resp[0])
  },

  async getCollectionSchemaByName (name) {
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
  getAll (name, options = {}) {
    return this.fetch(`/${name}?${queryParams(options)}`, {
      method: 'GET'
    })
  },

  // GET /:entity
  async getOne (name, options = {}) {
    const resp = await this.fetch(`/${name}?${queryParams(options)}`, {
      method: 'GET'
    })

    return resp[0]
  },

  // TODO real server side
  // GET /:entity
  async getWithTotalItems (name, options = {}) {
    const respRaw = await this.fetchRaw(`/${name}?${queryParams(options)}`, {
      method: 'GET'
    })
    console.log(respRaw)

    const resp = await respRaw.json()
    const totalItems = respRaw.headers.get('X-Total-Count') || 999

    respRaw.headers.forEach(function (val, key) { console.log(key + ' -> ' + val) })

    console.log(resp, totalItems)

    // FAKE
    return {
      items: resp,
      totalItems
    }
  },

  // GET /:entity/:id
  getById (name, id) {
    return this.fetch(`/${name}/${id}`, {
      method: 'GET'
    })
  },

  // Mixed
  createOrUpdateById (name, id = null, form = {}) {
    console.log(id)
    return id
      ? this.updateById(name, id, form)
      : this.create(name, form)
  },

  // POST /:entity
  create (name, form = {}) {
    return this.fetch(`/${name}`, {
      method: 'POST',
      body: JSON.stringify(form)
    })
  },

  // PATCH /:entity/:id
  updateById (name, id, form = {}) {
    return this.fetch(`/${name}/${id}`, {
      method: 'PATCH',
      body: JSON.stringify(form)
    })
  },

  // PUT /:entity/:id
  putById (name, id, form = {}) {
    return this.fetch(`/${name}/${id}`, {
      method: 'PATCH',
      body: JSON.stringify(form)
    })
  },

  // DELETE /:entity/:id
  deleteById (name, id = null) {
    return this.fetch(`/${name}/${id}`, {
      method: 'DELETE'
    })
  }
})
