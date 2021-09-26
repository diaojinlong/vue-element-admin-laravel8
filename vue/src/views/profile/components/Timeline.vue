<template>
  <div v-loading="listLoading" class="block">
    <el-timeline>
      <el-timeline-item
        v-for="(item, index) of list"
        :key="index"
        :timestamp="item.request_time"
        placement="top"
      >
        <el-card>
          <h4>{{ item.info }}</h4>
          <p>{{ item.admin_name }} {{ item.ip }}</p>
        </el-card>
      </el-timeline-item>
    </el-timeline>
    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />
  </div>
</template>

<script>
import { list } from '@/api/logs'
import Pagination from '@/components/Pagination'
export default {
  components: { Pagination },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: false,
      listQuery: {
        page: 1,
        limit: 20
      }
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      list(this.listQuery).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.listLoading = false
      })
    }
  }
}
</script>
