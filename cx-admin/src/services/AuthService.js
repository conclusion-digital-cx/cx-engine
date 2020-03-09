// import {router} from '../index'
import axios from 'axios'

// URL and endpoint constants
// const API_URL = '' // ConfigService.get('api')
const LOGIN_URL = `/login`
const SIGNUP_URL = `/users`
const localStorage = window.localStorage

const post = axios.post

export const AuthService = {
  // User object will let us check authentication status
  user: {
    authenticated: false
  },

  currentTokenData () {
    try {
      const accessToken = localStorage.getItem('access_token')
      return this.parseJwt(accessToken)
    } catch (err) {
      // Remove on fault
      localStorage.removeItem('access_token')
      console.warn(err)
    }
  },

  parseJwt (token) {
    if (!token) return null
    var base64Url = token.split('.')[1]
    var base64 = base64Url.replace('-', '+').replace('_', '/')
    return JSON.parse(window.atob(base64))
  },

  sendResetLink (email) {
    return post(`/sendresetlink`, { email })
  },

  checkAuth () {
    var accessToken = localStorage.getItem('access_token')
    if (accessToken || accessToken !== 'undefined') {
      axios.defaults.headers.common['Authorization'] = `Bearer ${accessToken}`
      // console.log("Axios Authorization set to","Bearer " + access_token)
      this.user.authenticated = true
    } else {
      axios.defaults.headers.common['Authorization'] = ``
      this.user.authenticated = false
    }

    console.log(`Axios Authorization set to`, `Bearer ${accessToken}`)
  },

  // Send a request to the login URL and save the returned JWT
  login (creds) {
    return post(LOGIN_URL, creds, { headers: { Authorization: '' } })
      .then(data => {
        const accessToken = data.access_token || data.token
        localStorage.setItem('access_token', accessToken)
        return data
      })
      // .catch(err => { console.log(err); throw new Error(err.data) })

    // .catch(err => {
    //   console.warn('AI',err)
    //   return err.data
    // })
  },

  // Send a request to the login URL and save the returned JWT
  loginCall (creds) {
    return post(LOGIN_URL, creds, { headers: { Authorization: '' } })
  },

  signup (creds, context = {}) {
    post(SIGNUP_URL, creds, (data) => {
      // localStorage.setItem('id_token', data.id_token)
      // localStorage.setItem('access_token', data.access_token)

      this.user.authenticated = true
    }).catch((err) => {
      context.error = err
    })
  },

  // To log out, we just need to remove the token
  logout () {
    // localStorage.removeItem('id_token')
    localStorage.removeItem('access_token')
    this.user.authenticated = false
  },

  // The object to be passed as a header for authenticated requests
  getAuthHeader () {
    return {
      Authorization: 'Bearer ' + localStorage.getItem('access_token')
    }
  }
}
