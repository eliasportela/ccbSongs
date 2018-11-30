import Vue from 'vue'
import Router from 'vue-router'

import Home from '@/pages/Home.vue'
import Reproducao from '@/pages/Reproducao.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/reproducao/:id/',
      name: 'Reproducao',
      component: Reproducao
    }
    // {
    //   path: '/reproducao/:id/:hymn',
    //   name: 'ReproducaoHino',
    //   component: Reproducao
    // }
  ]
})
