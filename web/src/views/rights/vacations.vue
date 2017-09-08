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
          <span class="link-type" @click="showDetail(scope.row)">{{scope.row.real_name}}</span>
        </template>
      </el-table-column>

      <el-table-column width="200" align="center" label="时间">
        <template scope="scope">
          <span>{{scope.row.year}}年</span>
        </template>
      </el-table-column>

      <el-table-column min-width="220" align="center" label="剩余时间">
        <template scope="scope">
          <span>{{scope.row.remain_time | dayParse}}</span>
        </template>
      </el-table-column>

      <el-table-column width="100px" align="center" label="享用时间">
        <template scope="scope">
          <span>{{scope.row.stayed_time | dayParse}}</span>
        </template>
      </el-table-column>

      <el-table-column width="100px" align="center" label="总时间">
        <template scope="scope">
          <span>{{scope.row.total_time | dayParse}}</span>
        </template>
      </el-table-column>

      <el-table-column width="100px" align="center" label="状态">
        <template scope="scope">
          <el-tag :type="scope.row.is_disabled | disableFilter">{{scope.row.is_disabled | disableParse}}</el-tag>
        </template>
      </el-table-column>

      <el-table-column min-width="200px" align="center" label="更新人">
        <template scope="scope">
          <span>{{scope.row.updator_name ? scope.row.updator_name : ''}}</span>
        </template>
      </el-table-column>

      <el-table-column  align="center" label="操作" width="100">
        <template scope="scope">
          <el-button size="small" type="primary" @click="handleUpdate(scope.row)">修改</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div v-show="!listLoading" class="pagination-container">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="listQuery.page" :page-sizes="[20]"
        :page-size="listQuery.limit" layout="total, sizes, prev, pager, next, jumper" :total="pagination.total">
      </el-pagination>
    </div>
    <el-dialog :title="detailTitle" :visible.sync="detailVisible" :top="dialogTop">
      <el-table  :key='tableKey' :data="details" v-loading.body="listLoading" border fit highlight-current-row style="width: 100%">
        <el-table-column align="center" label="序号" width="65">
          <template scope="scope">
            <span>{{scope.row.id}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="度假时间" width="150">
          <template scope="scope">
            <span>{{scope.row.stayed_at}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="度假时长" width="150">
          <template scope="scope">
            <span>{{scope.row.stayed_time}}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="备注" min-width="250">
          <template scope="scope">
            <span>{{scope.row.remark}}</span>
          </template>
        </el-table-column>
      </el-table>
      <div slot="footer" class="dialog-footer">
        <el-button @click="detailVisible = false">关闭</el-button>
      </div>
    </el-dialog>
    <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible" :top="dialogTop">
      <el-form class="small-space" :model="temp" label-position="right" label-width="76px" style='width:400px; margin-left:50px;'>
        <el-form-item label="庄园主">
          <span>{{temp.real_name}}</span>
        </el-form-item>
        <el-form-item label="度假时间">
		  <el-date-picker
		    v-model="temp.stayed_at"
		    type="date"
		    placeholder="度假时间"
            >
          </el-date-picker>
        </el-form-item>

        <el-form-item label="度假时长">
		  <el-select v-model="temp.stayed_time" placeholder="请选择度假时长">
			<el-option
			  v-for="item in temp.remains"
			  :key="item.value"
			  :label="item.name"
			  :value="item.value">
			</el-option>
		  </el-select>
        </el-form-item>

        <el-form-item label="备注">
          <el-input class="input250" v-model="temp.remark" placeholder="备注"></el-input>
        </el-form-item>

      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button :loading="updateLoading" type="primary" @click="update">度假</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
  import {
    getVacations,
    updateVacation,
    vacationDetail
  } from '@/api/products'

  import { parseTime } from '@/utils'

  export default {
    data() {
      return {
        list: [],           // 列表数据
        pagination: {       // 分页信息
        },
        listLoading: false, // 列表加载状态
        details: [],
        listQuery: { // 过滤条件
          page: 1,
          limit: 20,
          keyword: '',
          type: 1,
          sort: '+id'
        },
        detailTitle: '度假详情',
        dialogTitle: '住宿登记',
        dialogFormVisible: false,
        detailVisible: false,
        dialogTop: '5%',
        updateLoading: false,
        temp: {
          id: undefined,
          stayed_time: undefined,
          stayed_at: undefined,
          remark: undefined
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
      disableFilter(s) { // 绑定状态过滤器
        const delivery = {
          '0': 'success',
          '1': 'danger'
        }
        return delivery[s]
      },
      disableParse(s) { // 绑定状态解析器
        const delivery = {
          '0': '可用',
          '1': '不可用'
        }
        return delivery[s]
      },
      dayParse(s) {
        if (s === 0) {
          return '-'
        } else {
          const df = Math.floor(s)
          const dr = Math.round(s)

          return `${dr}天${df}夜`
        }
      }
    },
    methods: {
      getList() { // 获取用户列表
        this.loading = true
        getVacations(this.listQuery).then(res => {
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
      showDetail(row) {
        this.detailVisible = true
        vacationDetail({ id: row.id }).then(res => {
          this.details = res.data.data
        })
      },
      handleUpdate(row) {
        const remain_time = row.remain_time

        console.log(remain_time)
        if (remain_time === 0.0) {
          alert('度假已消费完')
        } else {
          const remains = [
            { value: 0.5, name: '1天' },
            { value: 1.0, name: '1天1晚' },
            { value: 1.5, name: '2天1晚' },
            { value: 2.0, name: '2天2晚' },
            { value: 2.5, name: '3天2晚' },
            { value: 3.0, name: '3天3晚' },
            { value: 3.5, name: '4天3晚' },
            { value: 4.0, name: '4天4晚' },
            { value: 4.5, name: '5天5晚' },
            { value: 5.0, name: '6天5晚' }
          ]
          this.temp = {
            ...this.temp,
            ...row,
            remains: remains.filter(item => item.value <= remain_time)
          }
          this.dialogFormVisible = true
        }
      },
      update() {
        this.updateLoading = true
        updateVacation({ ...this.temp }).then(response => {
          const {
            status
          } = response
          if (status === 204) {
            this.dialogFormVisible = false
            this.getList()
          }
          this.updateLoading = false
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
