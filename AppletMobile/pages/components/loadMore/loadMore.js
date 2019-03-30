// components/loadMore/loadMore.js
Component({
  properties: {
    // 这里定义了innerText属性，属性值可以在组件使用时指定
    loadMore: {
      type: Boolean,
      value: true,
    },
    loading: {
      type: Boolean,
      value: false,
    },
    notData: {
      type: Boolean,
      value: false,
    },
  },
})