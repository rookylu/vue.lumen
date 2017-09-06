import fetch from '@/utils/fetch'

export function loginByEmail(email, password) {
  const data = {
    email,
    password
  }
  return fetch({
    url: '/kf/login',
    method: 'post',
    data
  })
}

export function logout() {
  return fetch({
    url: '/kf/logout',
    method: 'delete'
  })
}

export function getUserInfo(token) {
  return fetch({
    url: '/kf/info',
    method: 'get'
  })
}
