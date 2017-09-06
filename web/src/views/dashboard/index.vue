<template>
  <div class="dashboard-container">
    <component :is="currentRole"></component>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import adminDashboard from './admin/index'
import editorDashboard from './editor/index'
import kfDashboard from './kf/index'

export default {
  name: 'dashboard',
  components: { adminDashboard, editorDashboard, kfDashboard },
  data() {
    return {
      // currentRole: 'adminDashboard'
      currentRole: 'kfDashboard'
    }
  },
  computed: {
    ...mapGetters([
      'roles'
    ])
  },
  created() {
    if (this.roles.indexOf('admin') >= 0) {
      return
    }
    this.currentRole = 'editorDashboard'
  }
}
</script>
