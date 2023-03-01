import Home from '@/layout/Home.vue'
import Login from '@/layout/Login.vue'
import NotFound from '@/pages/error/NotFound'

/**
 * Public Routes
 * @type {Array}
 */

const publicRoutes = [
  {
    path: '*',
    name: 'Not found',
    component: NotFound
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/',
    name: 'Inicio',
    component: Home,
    meta: {
      action: 'mdi-home'
    },
    children: [
      {
        path: '/index',
        name: 'Dashboard',
        component: () => import('@/pages/Index')
      }
    ]
  },
  {
    path: '/',
    name: 'Usuarios',
    component: Home,
    meta: {
      action: 'people_alt'
    },
    children: [
      {
        path: '/usuarios/lista',
        name: 'Lista de ususarios',
        component: () => import('@/pages/users/Pguser')
      }
    ]
  },
  {
    path: '/',
    name: 'Empresas',
    component: Home,
    meta: {
      action: 'business'
    },
    children: [
      {
        path: '/empresa/lista',
        name: 'Lista de empresas',
        component: () => import('@/pages/business/Pgbusiness')
      }
    ]
  },
  {
    path: '/',
    name: 'Catalogo',
    component: Home,
    meta: {
      action: 'perm_media'
    },
    children: [
      {
        path: '/catalogo/productos',
        name: 'Catálogo de productos',
        component: () => import('@/pages/catalogo/producto')
      }
    ]
  },
  {
    path: '/',
    name: 'CFDI\'s',
    component: Home,
    meta: {
      action: 'library_books'
    },
    children: [
      {
        path: '/cfdi/nuevo',
        name: 'Nuevo CFDI',
        component: () => import('@/pages/cfdi/crearCFDI')
      }
    ]
  },
  {
    path: '/',
    name: 'Configuración',
    component: Home,
    meta: {
      action: 'settings_applications'
    },
    children: [
      {
        path: '/pages/configuracion',
        name: 'configuracion',
        component: () => import('@/pages/configuracion')
      }
    ]
  }
]

const routes = [...publicRoutes]

export default routes
