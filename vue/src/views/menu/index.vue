<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button
        v-permission="['menu/add']"
        class="filter-item"
        style="margin-left: 10px"
        type="primary"
        icon="el-icon-edit"
        @click="handleCreate(0, '顶级权限')"
      >
        新增顶级
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
    >
      <el-table-column label="编号" prop="id" align="center" width="80">
        <template slot-scope="{ row }">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="权限名称" width="550px" align="center">
        <template slot-scope="{ row, $index }">
          <div style="text-align: left">
            <span>{{ levelStr(row.level, $index) }}{{ row.name }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="权限规则" width="350px" align="center">
        <template slot-scope="{ row }">
          <span>{{ row.api }}</span>
        </template>
      </el-table-column>
      <el-table-column label="排序" width="150px" align="center">
        <template slot-scope="{ row }">
          <sort-input
            :id="row.id"
            :value="row.sort"
            :callback-fun="sort"
            :permission="['menu/edit']"
          />
        </template>
      </el-table-column>
      <el-table-column
        v-if="checkPermission(['menu/add', 'menu/edit', 'menu/del'])"
        label="操作"
        align="center"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="{ row, $index }">
          <el-button
            v-permission="['menu/add']"
            type="primary"
            @click="handleCreate(row.id, row.name)"
          >
            新增下级
          </el-button>
          <el-button
            v-permission="['menu/edit']"
            type="primary"
            @click="handleUpdate(row)"
          >
            编辑
          </el-button>
          <el-button
            v-permission="['menu/del']"
            type="danger"
            @click="handleDelete(row, $index)"
          >
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :title="textMap[dialogStatus]" :close-on-click-modal="false" :visible.sync="dialogFormVisible">
      <el-form
        ref="dataForm"
        v-loading="formLoading"
        :rules="rules"
        :model="temp"
        label-position="reight"
        label-width="100px"
        style="padding-left: 20px"
      >
        <el-form-item label="上级权限" prop="parent_id">
          <el-input v-model="temp.parent_name" readonly />
        </el-form-item>
        <el-form-item label="权限名称" prop="name">
          <el-input v-model="temp.name" />
        </el-form-item>
        <el-form-item label="权限规则" prop="api">
          <el-input v-model="temp.api" />
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input v-model="temp.sort" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> 取消 </el-button>
        <el-button
          :loading="subBtnLoading"
          type="primary"
          @click="dialogStatus === 'create' ? createData() : updateData()"
        >
          确认
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import SortInput from '@/components/Sort/Input.vue'
import { list, add, details, edit, del, sort } from '@/api/menu'
export default {
  name: 'Menu',
  components: {
    SortInput
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        2: 'danger'
      }
      return statusMap[status]
    }
  },
  data() {
    return {
      imgHost: '',
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      subBtnLoading: false,
      formLoading: false,
      importanceOptions: [1, 2, 3],
      showReviewer: false,
      temp: {
        id: undefined,
        name: '',
        parent_id: 0,
        parent_name: '',
        api: '',
        sort: 50
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '新增'
      },
      dialogPvVisible: false,
      rules: {
        name: [{ required: true, message: '权限名称必填', trigger: 'blur' }]
      },
      downloadLoading: false,
      statusOptions: [
        {
          value: 1,
          label: '正常'
        },
        {
          value: 2,
          label: '禁用'
        }
      ],
      roleOpetions: []
    }
  },
  created() {
    this.getList()
  },
  methods: {
    sort,
    getList() {
      this.listLoading = true
      list({}).then(response => {
        this.list = response.data.items
        this.listLoading = false
      })
    },
    async resetTemp(parentId, parentName) {
      this.temp = {
        id: undefined,
        name: '',
        parent_id: parentId,
        parent_name: parentName,
        api: '',
        sort: 50
      }
    },
    handleCreate(parentId, parentName) {
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.formLoading = true
      this.resetTemp(parentId, parentName).then(() => {
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate()
          this.formLoading = false
        })
      })
    },
    createData() {
      this.$refs['dataForm'].validate(valid => {
        if (valid) {
          this.subBtnLoading = true
          add(this.temp).then(response => {
            this.subBtnLoading = false
            if (response.status === 0) {
              this.getList()
              this.dialogFormVisible = false
              this.$notify({
                title: 'Success',
                message: '操作成功',
                type: 'success',
                duration: 2000
              })
            } else {
              this.$notify({
                title: 'Error',
                message: response.msg,
                type: 'error',
                duration: 2000
              })
            }
          })
        }
      })
    },
    handleUpdate(row) {
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.formLoading = true
      this.resetTemp().then(() => {
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate()
          details({ id: row.id }).then(res => {
            this.temp = {
              id: res.data.item.id,
              name: res.data.item.name,
              parent_id: res.data.item.parent_id,
              parent_name:
                res.data.item.parent_id === 0
                  ? '顶级权限'
                  : res.data.item.parent_name,
              api: res.data.item.api,
              sort: res.data.item.sort
            }
            this.formLoading = false
          })
        })
      })
    },
    updateData() {
      this.$refs['dataForm'].validate(valid => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          this.subBtnLoading = true
          edit(tempData).then(response => {
            this.subBtnLoading = false
            if (response.status === 0) {
              this.getList()
              this.dialogFormVisible = false
              this.$notify({
                title: 'Success',
                message: '操作成功',
                type: 'success',
                duration: 2000
              })
            } else {
              this.$notify({
                title: 'Error',
                message: response.msg,
                type: 'error',
                duration: 2000
              })
            }
          })
        }
      })
    },
    handleDelete(row, index) {
      this.$confirm('确认删除吗?', 'Warning', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(async () => {
          const response = await del({ id: row.id })
          if (response.status === 0) {
            this.getList()
            this.$notify({
              title: 'Success',
              message: '删除成功',
              type: 'success',
              duration: 2000
            })
          } else {
            this.$notify({
              title: 'Error',
              message: response.msg,
              type: 'error',
              duration: 2000
            })
          }
        })
        .catch(err => {
          console.error(err)
        })
    },
    statusText(status) {
      const text = {
        1: '正常',
        2: '禁用'
      }
      return text[status]
    },
    setAvatar(avatar) {
      this.temp.avatar = avatar
    },
    circleUrl(avatar) {
      if (avatar !== '') {
        avatar = this.imgHost + avatar
      }
      return avatar
    },
    errorHandler() {
      return true
    },
    levelStr(level, index) {
      console.log(index)
      if (level !== 1) {
        const strArr = []
        for (let i = 1; i < level; i++) {
          strArr.push('\xa0\xa0\xa0\xa0')
        }
        const nextIndex = index + 1
        if (
          this.list[nextIndex] !== undefined &&
          this.list[nextIndex].level === level
        ) {
          strArr.push('├\xa0')
        } else {
          strArr.push('└\xa0')
        }

        return strArr.join('')
      } else {
        return ''
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.filter-container .filter-item {
  margin-right: 10px;
}
.select-100 {
  width: 100%;
}
</style>
