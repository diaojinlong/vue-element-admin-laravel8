import request from '@/utils/request'

export function list(params) {
  return request({
    url: '/role/lists',
    method: 'get',
    params
  })
}

export function details(params) {
  return request({
    url: '/role/details',
    method: 'get',
    params
  })
}

export function add(data) {
  return request({
    url: '/role/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/role/edit',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/role/del',
    method: 'post',
    data
  })
}
