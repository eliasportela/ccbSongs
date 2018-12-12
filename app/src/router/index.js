import Vue from 'vue'
import Router from 'vue-router'

import Apresentacao from '@/pages/Apresentacao.vue'
import Home from '@/pages/Home.vue'
import Reproducao from '@/pages/Reproducao.vue'

Vue.use(Router)

export default new Router({
  base: '/',
  routes: [
    {
      path: '/',
      name: 'Apresentacao',
      component: Apresentacao
    },
    {
      path: '/home',
      name: 'Home',
      component: Home
    },
    {
      path: '/reproducao/:id/',
      name: 'Reproducao',
      component: Reproducao
    },
    {
      path: '/reproducao/:id/:hymn',
      name: 'ReproducaoHino',
      component: Reproducao
    }
  ]
})
