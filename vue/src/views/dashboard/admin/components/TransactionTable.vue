<template>
  <el-table :data="list" style="width: 100%; padding-top: 15px">
    <el-table-column label="Order_No" min-width="200">
      <template slot-scope="scope">
        {{ scope.row.order_no | orderNoFilter }}
      </template>
    </el-table-column>
    <el-table-column label="Price" width="195" align="center">
      <template slot-scope="scope">
        ¥{{ scope.row.price | toThousandFilter }}
      </template>
    </el-table-column>
    <el-table-column label="Status" width="100" align="center">
      <template slot-scope="{ row }">
        <el-tag :type="row.status | statusFilter">
          {{ row.status }}
        </el-tag>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
// import { transactionList } from '@/api/remote-search'

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        success: 'success',
        pending: 'danger'
      }
      return statusMap[status]
    },
    orderNoFilter(str) {
      return str.substring(0, 30)
    }
  },
  data() {
    return {
      list: null
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.list = [
        { order_no: '1', price: '100.00', status: 'success' },
        { order_no: '2', price: '360.00', status: 'pending' },
        { order_no: '3', price: '170.00', status: 'success' },
        { order_no: '4', price: '520.00', status: 'pending' },
        { order_no: '5', price: '150.00', status: 'pending' },
        { order_no: '6', price: '150.00', status: 'success' },
        { order_no: '7', price: '216.00', status: 'pending' },
        { order_no: '8', price: '750.00', status: 'success' }
      ]
      // transactionList().then(response => {
      //   this.list = response.data.items.slice(0, 8)
      // })
    }
  }
}
</script>
