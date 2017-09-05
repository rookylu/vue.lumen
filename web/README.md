# Vue.js + vuex

## vuex基础
vuex是一个专为vue.js应用程序开发的状态管理模式。它采用集中式存储管理应用的所有组件的状态，并以相应的规则保证状态以一种可预测的方式发生变化。

### 状态管理模式

#### 单项数据流

```
 +--------------------> View --------------------+
 |                                               |
 |                                               |
 |                                               |
 |                                               |
 +--------- State <------------- Actions <-------+
```

#### state
驱动应用的数据源。


#### view
以声明方式将state映射到视图。

#### actions
响应在view上的用户输入导致的状态变化。

但是，当我们应用遇到多个组件共享状态时，单向数据流的简洁性很容易被破坏:

* 多个试图依赖同一状态。
* 来自不同视图的行为需要变更同一状态。

可采取共享状态抽取出来，以一个全局单例模式管理。这种模式下，我们的组件构成了一个巨大的"视图", 不管在树的哪个位置，任何组件都能获取状态或者触发行为。


### State介绍
vuex使用单一状态树。唯一数据源SSOT(Single source of truth)。

#### 在vue组件中获取vuex的状态
```
const Counter = {
  template: `<div>{{ count }}</div>`,
  computed: {
    count() {
      return store.state.count
    }
  }
}
```

#### 通过store选项，将状态从根组件注入到每个自组件中
```
const app = new Vue({
  el: '#app',
  // 把 store 对象提供给 “store” 选项，这可以把 store 的实例注入所有的子组件
  store,
  components: { Counter },
  template: `
    <div class="app">
      <counter></counter>
    </div>
  `
})


// 然后在Counter组件可以如下方式实现
const Counter = {
  template: `<div>{{ count }}</div>`,
  computed: {
    count () {
      return this.$store.state.count
    }
  }
}
```

#### mapState辅助函数

当一个组件需要获取多个状态的时候，将这些状态都声明为计算属性有些重复和冗余。为了解决这个问题，我们可以使用mapState辅助函数帮助我们生成计算属性:
```
// 在单独构建的版本中辅助函数为 Vuex.mapState
import { mapState } from 'vuex'

export default {
  // ...
  computed: mapState({
    // 箭头函数可使代码更简练
    count: state => state.count,

    // 传字符串参数 'count' 等同于 `state => state.count`
    countAlias: 'count',

    // 为了能够使用 `this` 获取局部状态，必须使用常规函数
    countPlusLocalState (state) {
      return state.count + this.localCount
    }
  })
}

// 当映射的计算属性的名称与 state 的子节点名称相同时，我们也可以给 mapState 传一个字符串数组。

computed: mapState([
  // 映射 this.count 为 store.state.count
  'count'
])
```

#### 对象展开运算符
```
computed: {
  localComputed () { /* ... */ },
  // 使用对象展开运算符将此对象混入到外部对象中
  ...mapState({
    // ...
  })
}
```

#### 组件仍然保有局部状态
使用 Vuex 并不意味着你需要将所有的状态放入 Vuex。虽然将所有的状态放到 Vuex 会使状态变化更显式和易调试，但也会使代码变得冗长和不直观。如果有些状态严格属于单个组件，最好还是作为组件的局部状态。你应该根据你的应用开发需要进行权衡和确定。


### Getters
场景: 需要从store中派生出来一些状态，比如对列表进行过滤并计数。

假设在多个组件中都需要使用到这个派生状态，那么普通的实现方式是在每个组件中都拷贝一份这样的函数，或者抽离成共享函数，需要的地方导入这个函数。

上面两种方式都不是很理想的方式。Getters就提供了一种解决这种需求的方式。

vuex允许我们在store中定义getters(可以认为是store的计算属性). 就像计算属性一样，getters的返回值会根据它的依赖被缓存起来，且只有当它的依赖发生了改变才会被重新计算。
```
// Getters 接受 state 作为其第一个参数：
const store = new Vuex.Store({
  state: {
    todos: [
      { id: 1, text: '...', done: true },
      { id: 2, text: '...', done: false }
    ]
  },
  getters: {
    doneTodos: state => {
      return state.todos.filter(todo => todo.done)
    }
  }
})
// Getters 会暴露为 store.getters 对象：
store.getters.doneTodos // -> [{ id: 1, text: '...', done: true }]

// Getters 也可以接受其他 getters 作为第二个参数：
getters: {
  // ...
  doneTodosCount: (state, getters) => {
    return getters.doneTodos.length
  }
}
store.getters.doneTodosCount // -> 1

//我们可以很容易地在任何组件中使用它：
computed: {
  doneTodosCount () {
    return this.$store.getters.doneTodosCount
  }
}
```

#### mapGetters 辅助函数
```
// mapGetters 辅助函数仅仅是将 store 中的 getters 映射到局部计算属性：
import { mapGetters } from 'vuex'
export default {
  // ...
  computed: {
  // 使用对象展开运算符将 getters 混入 computed 对象中
    ...mapGetters([
      'doneTodosCount',
      'anotherGetter',
      // ...
    ])
  }
}

// 如果你想将一个 getter 属性另取一个名字，使用对象形式：
mapGetters({
  // 映射 this.doneCount 为 store.getters.doneTodosCount
  doneCount: 'doneTodosCount'
})
```

## 参考链接

* [vuex官方文档](https://vuex.vuejs.org/zh-cn/state.html)
