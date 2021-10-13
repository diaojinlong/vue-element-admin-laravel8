<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="listQuery.username"
        placeholder="用户名"
        style="width: 200px"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
      <el-input
        v-model="listQuery.name"
        placeholder="姓名"
        style="width: 200px"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
      <el-select
        v-model="listQuery.status"
        placeholder="状态"
        clearable
        style="width: 90px"
        class="filter-item"
      >
        <el-option
          v-for="item in statusOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="handleFilter"
      >
        搜索
      </el-button>
      <el-button
        v-permission="['admin/add']"
        class="filter-item"
        style="margin-left: 10px"
        type="primary"
        icon="el-icon-edit"
        @click="handleCreate"
      >
        新增
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
      @sort-change="sortChange"
    >
      <el-table-column
        label="编号"
        prop="id"
        sortable="custom"
        align="center"
        width="80"
        :class-name="getSortClass('id')"
      >
        <template slot-scope="{ row }">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="姓名" width="250px" align="center">
        <template slot-scope="{ row }">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="用户名" width="150px" align="center">
        <template slot-scope="{ row }">
          <span>{{ row.username }}</span>
        </template>
      </el-table-column>
      <el-table-column label="手机号" width="150px" align="center">
        <template slot-scope="{ row }">
          <span>{{ row.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column label="角色" width="150px" align="center">
        <template slot-scope="{ row }">
          <div v-for="(item, index) in row.role" :key="index">
            <el-tag class="margin-bottom-5px">{{ item }}</el-tag>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="状态" class-name="status-col" width="100">
        <template slot-scope="{ row }">
          <el-tag :type="row.status | statusFilter">
            {{ statusText(row.status) }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="最后登录时间" width="250px" align="center">
        <template slot-scope="{ row }">
          <span>{{ row.login_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="创建时间" width="250px" align="center">
        <template slot-scope="{ row }">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column
        v-if="checkPermission(['admin/edit', 'admin/del'])"
        label="操作"
        align="center"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="{ row, $index }">
          <el-button
            v-permission="['admin/edit']"
            type="primary"
            :disabled="row.id === 1"
            @click="handleUpdate(row)"
          >
            编辑
          </el-button>
          <el-button
            v-permission="['admin/del']"
            type="danger"
            :disabled="row.id === 1"
            @click="handleDelete(row, $index)"
          >
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />

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
        <el-form-item label="姓名" prop="name">
          <el-input v-model="temp.name" />
        </el-form-item>
        <el-form-item label="用户名" prop="username">
          <el-input v-model="temp.username" />
        </el-form-item>
        <el-form-item label="手机号" prop="phone">
          <el-input v-model="temp.phone" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="temp.password" type="password" />
        </el-form-item>
        <el-form-item label="角色" prop="role">
          <el-select
            v-model="temp.role"
            class="select-100"
            multiple
            filterable
            default-first-option
            placeholder="请选择角色"
          >
            <el-option
              v-for="item in roleOpetions"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-select
            v-model="temp.status"
            class="select-100 filter-item"
            placeholder="Please select"
          >
            <el-option
              v-for="item in statusOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
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
import { list, add, details, edit, del } from '@/api/admin'
import { role } from '@/api/public'
import Pagination from '@/components/Pagination'
export default {
  name: 'Admin',
  components: { Pagination },
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
      listQuery: {
        page: 1,
        limit: 20,
        username: '',
        name: '',
        status: undefined,
        sort: '+id'
      },
      importanceOptions: [1, 2, 3],
      sortOptions: [
        { label: 'ID Ascending', key: '+id' },
        { label: 'ID Descending', key: '-id' }
      ],
      showReviewer: false,
      temp: {
        id: undefined,
        username: '',
        phone: '',
        password: '',
        name: '',
        role: [],
        status: 1
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '新增'
      },
      dialogPvVisible: false,
      rules: {
        name: [{ required: true, message: '姓名必填', trigger: 'blur' }],
        username: [{ required: true, message: '用户名必填', trigger: 'blur' }],
        password: [{ required: true, message: '密码必填', trigger: 'blur' }]
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
    getList() {
      this.listLoading = true
      list(this.listQuery).then(response => {
        this.imgHost = response.data.host
        this.list = response.data.items
        this.total = response.data.total
        this.roleOpetions = response.data.roles
        this.listLoading = false
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    sortChange(data) {
      const { prop, order } = data
      if (prop === 'id') {
        this.sortByID(order)
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id'
      } else {
        this.listQuery.sort = '-id'
      }
      this.handleFilter()
    },
    async resetTemp() {
      this.temp = {
        id: undefined,
        username: '',
        phone: '',
        password: '',
        name: '',
        role: [],
        status: 1
      }
      const res = await role()
      this.roleOpetions = res.data.items
    },
    handleCreate() {
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.formLoading = true
      this.resetTemp().then(() => {
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
              this.dialogFormVisible = false
              this.handleFilter()
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
            this.temp = res.data.item
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
              this.dialogFormVisible = false
              this.handleFilter()
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
            this.handleFilter()
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
    getSortClass(key) {
      const sort = this.listQuery.sort
      return sort === `+${key}` ? 'ascending' : 'descending'
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
.margin-bottom-5px {
  margin-bottom: 5px;
}
</style>
