<template>
  <div>
    <span v-if="checkPermission(permission)">
      <el-input-number
        v-model="num"
        v-loading="loading"
        :min="min"
        :max="max"
        :controls="false"
        @change="handleChange"
      />
    </span>
    <span v-else>{{ num }}</span>
  </div>
</template>
<script>
export default {
  name: 'SortInput',
  props: {
    id: {
      type: Number,
      default: 0
    },
    value: {
      type: Number,
      default: 0
    },
    min: {
      type: Number,
      default: 0
    },
    max: {
      type: Number,
      default: 100000
    },
    callbackFun: {
      type: Function,
      default: () => {}
    },
    permission: {
      type: Array,
      default: () => {
        return []
      }
    }
  },
  data() {
    return {
      num: 0,
      loading: false
    }
  },

  watch: {
    value(newVal) {
      this.num = newVal
    }
  },
  created() {
    this.num = this.value
  },
  methods: {
    // 删除操作
    rmImage() {
      this.emitInput('')
    },
    // 设置值
    emitInput(val) {
      this.$emit('input', val)
    },
    handleChange(value) {
      if (value === undefined) {
        this.num = 0
        value = 0
      }
      this.loading = true
      this.callbackFun({ id: this.id, sort: value }).then(res => {
        if (res.status === 0) {
          this.$notify({
            title: 'Success',
            message: '操作成功',
            type: 'success',
            duration: 2000
          })
        } else {
          this.$notify({
            title: 'Error',
            message: res.msg,
            type: 'error',
            duration: 2000
          })
        }
        this.loading = false
      })
    }
  }
}
</script>

<style lang="scss">
.el-input-number--medium {
  width: 100%;
}
</style>
