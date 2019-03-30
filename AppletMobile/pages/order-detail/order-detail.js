// pages/order-detail/order-detail.js
const WXAPI = require('../../wxapi/main.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    orderDetail:null,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this
    let order_id = options.order_id
    WXAPI.orderDetail({order_id:order_id}).then(function(res){
      _this.setData({
        orderDetail:res.data
      })
    })
  },
  // 取消订单
  delOrder: function (e) {
    let _this = this
    wx.showModal({
      content: '确定要取消该订单吗?',
      success(resq) {
        let order_id = e.currentTarget.dataset.id
        let user_id = wx.getStorageSync('user_id')
        WXAPI.delOrder({ order_id: order_id, user_id: user_id }).then(function (res) {
          wx.navigateTo({
            url: '/pages/order/order',
          })
        })
      }
    })

  }
})