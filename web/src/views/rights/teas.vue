<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container">
      <el-input @keyup.enter.native="handleFilter" style="width: 200px;" class="filter-item" placeholder="姓名" v-model="listQuery.keyword">
      </el-input>

      <el-button class="filter-item" type="primary" icon="search" @click="handleFilter">搜索</el-button>
    </div>

    <el-table  :key='tableKey' :data="list" v-loading.body="listLoading" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="序号" width="65">
        <template scope="scope">
          <span>{{scope.row.id}}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="220" align="center" label="姓名">
        <template scope="scope">
          <span>{{scope.row.real_name}}</span>
        </template>
      </el-table-column>

      <el-table-column width="200" align="center" label="批次">
        <template scope="scope">
          <span>{{scope.row.year + ' - 第' + scope.row.period_index + '批'}}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="220" align="center" label="最后交付日期">
        <template scope="scope">
          <span>{{scope.row.delivery_time_deadline}}</span>
        </template>
      </el-table-column>

      <el-table-column width="100px" align="center" label="交付状态">
        <template scope="scope">
          <el-tag :type="scope.row.is_deliveried | deliveryFilter">{{scope.row.is_deliveried | deliveryParse}}</el-tag>
        </template>
      </el-table-column>

      <el-table-column min-width="200px" align="center" label="更新人">
        <template scope="scope">
          <span>{{scope.row.updator_name ? scope.row.updator_name : ''}}</span>
        </template>
      </el-table-column>

      <el-table-column  align="center" label="操作" width="100">
        <template scope="scope">
          <el-button v-if="scope.row.is_deliveried != '1'" size="small" type="primary" @click="deliveryIt(scope.row)">交付</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div v-show="!listLoading" class="pagination-container">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="listQuery.page" :page-sizes="[20]"
        :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="pagination.total">
      </el-pagination>
    </div>
  </div>
</template>
<script>
  import {
    getTeas,
    deliveryTea
  } from '@/api/products'

  import { parseTime } from '@/utils'
  import { MessageBox } from 'element-ui'

  export default {
    data() {
      return {
        list: [],           // 列表数据
        pagination: {       // 分页信息
        },
        listLoading: false, // 列表加载状态
        listQuery: { // 过滤条件
          page: 1,
          limit: 20,
          keyword: '',
          type: 1,
          sort: '+id'
        },
        tableKey: 0,
        sortOptions: [{ label: '按ID升序列', key: '+id' }, { label: '按ID降序', key: '-id' }]
      }
    },
    created() {
      this.getList()
    },
    mounted() {
    },
    components: {
    },
    filters: {
      deliveryFilter(s) { // 绑定状态过滤器
        const delivery = {
          '1': 'success',
          '0': 'danger'
        }
        return delivery[s]
      },
      deliveryParse(s) { // 绑定状态解析器
        const delivery = {
          '0': '未交付',
          '1': '已交付'
        }
        return delivery[s]
      }
    },
    methods: {
      getList() { // 获取用户列表
        this.loading = true
        getTeas(this.listQuery).then(res => {
          this.list = res.data.data
          this.pagination = res.data.meta.pagination
          this.listLoading = false
        })
      },
      handleFilter() { // 筛选查询处理
        this.getList()
      },
      handleChange(v) {
        console.log(v)
      },
      handleSizeChange(val) {
        this.listQuery.limit = val
        this.getList()
      },
      handleCurrentChange(val) {
        this.listQuery.page = val
        this.getList()
      },
      deliveryIt(row) {
        MessageBox.confirm('确认交付, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          deliveryTea({ id: row.id }).then(response => {
            const {
              status
            } = response
            if (status === 204) {
              this.getList()
            }
          })
        }).catch(() => {
        })
      },
      formatJson(filterVal, jsonData) {
        return jsonData.map(v => filterVal.map(j => {
          if (j === 'timestamp') {
            return parseTime(v[j])
          } else {
            return v[j]
          }
        }))
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .input100 { width:100px; }
  .input150 { width:150px; }
  .input200 { width:200px; }
  .input250 { width:250px; }
  .input500 { width:500px; }

  .demo-table-expand {
    font-size: 0;
  }
  .demo-table-expand label {
    width: 90px;
    color: #99a9bf;
  }
  .demo-table-expand .el-form-item {
    margin-right: 0;
    margin-bottom: 0;
    width: 33%;
  }
</style>
