import axios from "axios";

export default({
    namespaced: true,
  
    state: {
      authenticated: false,
      user: null,
      permissions: null,
      roles:null,
      cart: [],
      wishlist: [],
      account_type: null
    },
  
    getters: {
      authenticated: state => {
        return state.authenticated
      },
  
      user: state => {
        return state.user
      },

      permissions: state => {
        return state.permissions
      },
      roles: state => {
        return state.roles
      },
      cart: state => {
        return state.cart
      }
      ,
      account_type: state => {
        return state.account_type
      }
      ,
      wishlist: state => {
        return state.wishlist
      }
    },
  
    mutations: {
      SET_AUTHENTICATED (state, value) {
        state.authenticated = value
      },
  
      SET_USER (state, value) {
        state.user = value
      },

      SET_PERMISSIONS (state, value){
        state.permissions = value
      }
      ,

      SET_ROLES (state, value){
        state.roles = value
      },
      SET_ACCOUNT_TYPE (state, value){
        state.account_type = value
      }
    },
  
    actions: {
        async signIn ({ dispatch }, credentials) {
          await axios.get('/sanctum/csrf-cookie')
          await axios.post('/login', credentials).then( response =>{
            console.log(response)
          })

          return dispatch('me')
        },

        async signOut ({ dispatch }) {
          await axios.post('/logout')

          return dispatch('me')
        },
        permissions ({ commit }){
        return axios.get('/getStaffPermissions')
          .then( (response) => {
            commit('SET_PERMISSIONS', JSON.parse(response.data.permissions))  
          })
        },
        me ({ commit }) {
        return axios.get('/user').then((response) => {
            commit('SET_AUTHENTICATED', true)
            commit('SET_USER', response.data)
            commit('SET_ROLES', response.data.user_role)
            commit('SET_ACCOUNT_TYPE', response.data.account_type)

        }).catch(() => {
            commit('SET_AUTHENTICATED', false)
            commit('SET_USER', null)
            commit('SET_ROLES', null)
            commit('SET_ACCOUNT_TYPE', null)
        })
        }
    }
  })
