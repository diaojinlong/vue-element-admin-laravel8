import request from '@/utils/request'

export function role(params) {
  return request({
    url: '/public/role',
    method: 'get',
    params
  })
}

export function menuTree(params) {
  return request({
    url: '/public/menu_tree',
    method: 'get',
    params
  })
}
