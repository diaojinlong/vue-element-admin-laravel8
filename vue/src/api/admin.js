import request from '@/utils/request'

export function list(params) {
  return request({
    url: '/admin/lists',
    method: 'get',
    params
  })
}

export function details(params) {
  return request({
    url: '/admin/details',
    method: 'get',
    params
  })
}

export function add(data) {
  return request({
    url: '/admin/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/admin/edit',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/admin/del',
    method: 'post',
    data
  })
}

export function editPassword(data) {
  return request({
    url: '/admin/edit_password',
    method: 'post',
    data
  })
}
