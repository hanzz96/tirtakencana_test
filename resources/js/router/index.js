import { createRouter, createWebHistory } from 'vue-router'
import MenuA from '../../views/MenuA.vue'
import MenuB from '../../views/MenuB.vue'
import MenuC from '../../views/MenuC.vue'
import MenuD from '../../views/MenuD.vue'

const routes = [
  { path: '/', component: MenuA },
  { path: '/menu-a', name: 'MenuA', component: MenuA },
  { path: '/menu-b', name: 'MenuB', component: MenuB },
  { path: '/menu-c', name: 'MenuC', component: MenuC },
  { path: '/menu-d', name: 'MenuD', component: MenuD },
]

export default createRouter({
  history: createWebHistory(),
  routes,
})