import request from '@/utils/request'

export function list(params) {
  return request({
    url: '/menu/lists',
    method: 'get',
    params
  })
}

export function details(params) {
  return request({
    url: '/menu/details',
    method: 'get',
    params
  })
}

export function add(data) {
  return request({
    url: '/menu/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/menu/edit',
    method: 'post',
    data
  })
}

export function sort(params) {
  return request({
    url: '/menu/sort',
    method: 'post',
    params
  })
}

export function del(data) {
  return request({
    url: '/menu/del',
    method: 'post',
    data
  })
}
