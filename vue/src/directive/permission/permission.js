import store from '@/store'

function checkPermission(el, binding) {
  const { value } = binding
  const permissions = store.getters && store.getters.permissions
  if (value && value instanceof Array) {
    if (value.length > 0 && !permissions.includes('*')) {
      const hasPermission = permissions.some((role) => {
        return value.includes(role)
      })
      if (!hasPermission) {
        el.parentNode && el.parentNode.removeChild(el)
      }
    }
  } else {
    throw new Error(
      `need roles! Like v-permission="['admin/lists','admin/add']"`
    )
  }
}

export default {
  inserted(el, binding) {
    checkPermission(el, binding)
  },
  update(el, binding) {
    checkPermission(el, binding)
  }
}
