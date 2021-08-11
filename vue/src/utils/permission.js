import store from '@/store'

/**
 * @param {Array} value
 * @returns {Boolean}
 * @example see @/views/permission/directive.vue
 */
export default function checkPermission(value) {
  if (value && value instanceof Array && value.length > 0) {
    const permissions = store.getters && store.getters.permissions
    if (!permissions.includes('*')) {
      const hasPermission = permissions.some((permission) => {
        return value.includes(permission)
      })
      return hasPermission
    } else {
      return true
    }
  } else {
    console.error(`need roles! Like v-permission="['admin/lists','admin/add']"`)
    return false
  }
}
