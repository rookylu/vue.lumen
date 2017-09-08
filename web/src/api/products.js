import fetch from '@/utils/fetch'

export function getProducts(params) {
  return fetch({
    url: '/products',
    method: 'get',
    params
  })
}

export function createProduct(params) {
  return fetch({
    url: '/products',
    method: 'post',
    data: params
  })
}

export function updateProduct(params) {
  const {
    id
  } = params

  delete params['id']

  return fetch({
    url: `/products/${id}`,
    method: 'put',
    data: params
  })
}

export function changeProductStatus(params) {
  const {
    id
  } = params

  delete params['id']

  return fetch({
    url: `/products/${id}`,
    method: 'patch',
    params
  })
}

export function deleteProduct(params) {
  const {
    id
  } = params

  delete params['id']

  return fetch({
    url: `/products/${id}`,
    method: 'delete',
    params
  })
}

export function getTeas(params) {
  return fetch({
    url: '/teas',
    method: 'get',
    params
  })
}

export function deliveryTea(params) {
  const id = params.id
  return fetch({
    url: `/teas/${id}`,
    method: 'patch'
  })
}

export function getVacations(params) {
  return fetch({
    url: '/vacations',
    method: 'get',
    params
  })
}

export function updateVacation(params) {
  const id = params.id
  delete params.id

  return fetch({
    url: `/vacations/${id}`,
    method: 'put',
    data: params
  })
}

export function vacationDetail(params) {
  const id = params.id
  delete params.id

  return fetch({
    url: `/vacations/${id}`,
    method: 'get'
  })
}
