import fetch from '@/utils/fetch'

export function getManorOwners(params) {
  return fetch({
    url: '/manorOwners',
    method: 'get',
    params
  })
}

export function createManorOwner(params) {
  return fetch({
    url: '/manorOwners',
    method: 'post',
    data: params
  })
}

export function updateManorOwner(params) {
  const {
    id
  } = params

  delete params['id']

  return fetch({
    url: `/manorOwners/${id}`,
    method: 'put',
    data: params
  })
}

export function changeManorOwnerStatus(params) {
  const {
    id
  } = params

  delete params['id']

  return fetch({
    url: `/manorOwners/${id}`,
    method: 'patch',
    params
  })
}

export function deleteManorOwner(params) {
  const {
    id
  } = params

  delete params['id']

  return fetch({
    url: `/manorOwners/${id}`,
    method: 'delete',
    params
  })
}
