<template>
  <el-form ref="dataForm" :rules="rules" :model="formData">
    <el-form-item label="原密码" prop="password">
      <el-input v-model="formData.password" type="password" />
    </el-form-item>
    <el-form-item label="新密码" prop="new_password">
      <el-input v-model.trim="formData.new_password" type="password" />
    </el-form-item>
    <el-form-item label="确认新密码" prop="new_password2">
      <el-input v-model.trim="formData.new_password2" type="password" />
    </el-form-item>
    <el-form-item>
      <el-button :loading="loading" type="primary" @click="submit"
        >更改</el-button
      >
    </el-form-item>
  </el-form>
</template>

<script>
import { editPassword } from '@/api/admin'
export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: ''
        }
      }
    }
  },
  data() {
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入新密码'))
      } else {
        if (this.formData.new_password2 !== '') {
          this.$refs.dataForm.validateField('new_password2')
        }
        callback()
      }
    }
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入新密码'))
      } else if (value !== this.formData.new_password) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      formData: {
        password: '',
        new_password: '',
        new_password2: ''
      },
      loading: false,
      rules: {
        password: [{ required: true, message: '原密码必填', trigger: 'blur' }],
        new_password: [
          {
            required: true,

            message: '新密码必填',
            trigger: 'blur'
          },
          {
            validator: validatePass,
            trigger: 'blur'
          }
        ],
        new_password2: [
          {
            required: true,
            message: '确认新密码必填',
            trigger: 'blur'
          },
          {
            validator: validatePass2,
            trigger: 'blur'
          }
        ]
      }
    }
  },
  mounted() {
    this.$refs['dataForm'].clearValidate()
  },
  methods: {
    submit() {
      this.$refs['dataForm'].validate(valid => {
        if (valid) {
          this.loading = true
          editPassword(this.formData).then(response => {
            this.loading = false
            this.$notify({
              title: 'Success',
              message: '更改成功，请重新登录！',
              type: 'success',
              duration: 2000
            })
            setTimeout(() => {
              this.$store.dispatch('user/resetToken').then(() => {
                this.$router.push(`/login`)
              })
            }, 2000)
          })
        }
      })
    }
  }
}
</script>
