
const queryParams = params => Object.keys(params)
  .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
  .join('&')

const fetchWithTimeout = (url, options, timeout = 2000) => {
  return Promise.race([
    fetch(url, options),
    new Promise((resolve, reject) =>
      setTimeout(() => reject(new Error('timeout')), timeout)
    )
  ])
}

// Throw error on non 200 responses
const fetchStatusHandler = (response) => {
  if (response.status === 200) {
    return response
  } else {
    throw new Error(response.statusText)
  }
}

export default (config = {}) =>
  (name) => {
    return {
      fetch (path, options = { method: 'GET' }) {
        const {
          server = window.server || 'http://localhost:8666/api',
          headers = ''
        } = config
        // console.log(config)

        return fetchWithTimeout(`${server}${path}`, {
          ...options,
          headers: {
            'Content-Type': 'application/json',
            ...headers
          }
          // headers: {
          //   Authorization: `Bearer ${window.localStorage.getItem('token')}`
          // }
        })
          .then(fetchStatusHandler)
          .then(resp => resp.json())
      },

      // GET /:entity
      getAll (options = {}) {
        return this.fetch(`/${name}?${queryParams(options)}`, {
          method: 'GET'
        })
      },

      // GET /:entity
      async getOne (options = {}) {
        // TODO add _limit = 1 ?
        const resp = await this.fetch(`/${name}?${queryParams(options)}`, {
          method: 'GET'
        })

        return resp[0]
      },

      // TODO real server side
      // GET /:entity
      async getWithTotalItems (options = {}) {
        const resp = await this.fetch(`/${name}?${queryParams(options)}`, {
          method: 'GET'
        })

        // FAKE
        return {
          items: resp,
          totalItems: resp.length
        }
      },

      // GET /:entity/:id
      getById (id) {
        return this.fetch(`/${name}/${id}`, {
          method: 'GET'
        })
      },

      // Mixed
      createOrUpdateById (id = null, form = {}) {
        delete form._id

        return id
          ? this.updateById(id, form)
          : this.insert(form)
      },

      // POST /:entity
      insert (form = {}) {
        delete form._id

        return this.fetch(`/${name}`, {
          method: 'POST',
          body: JSON.stringify(form)
        })
      },

      // PATCH /:entity/:id
      updateById (id, form = {}) {
        delete form._id

        return this.fetch(`/${name}/${id}`, {
          method: 'PATCH',
          body: JSON.stringify(form)
        })
      },

      // PUT /:entity/:id
      putById (id, form = {}) {
        delete form._id

        return this.fetch(`/${name}/${id}`, {
          method: 'PATCH',
          body: JSON.stringify(form)
        })
      },

      // DELETE /:entity/:id
      deleteById (id = null) {
        return this.fetch(`/${name}/${id}`, {
          method: 'DELETE'
        })
      }
    }
  }
