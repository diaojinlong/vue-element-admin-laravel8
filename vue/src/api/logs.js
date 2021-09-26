import request from '@/utils/request'

export function list(params) {
  return request({
    url: '/logs/lists',
    method: 'get',
    params
  })
}
