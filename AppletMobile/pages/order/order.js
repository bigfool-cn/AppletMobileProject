// pages/order/order.js
const WXAPI = require('../../wxapi/main.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loadMore: true,
    loading: false,
    notData: false,
    orderList:[],
    page:null,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
  },


  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    let _this = this
    let user_id = wx.getStorageSync('user_id')
    WXAPI.orderList({ page: 1, user_id: user_id }).then(function (res) {
      _this.setData({
        orderList:res.data.ordersAll,
        page:res.data.page
      })
      if (res.data.page.current_page >= res.data.page.last_page){
        _this.setData({
          loadMore: false,
          loading: false,
          notData: true,
        })
      }else{
        _this.setData({
          loadMore: true,
          loading: false,
          notData: false,
        })
      }
    })
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    let _this = this
    let user_id = wx.getStorageSync('user_id')
    if (_this.data.loadMore){
      _this.setData({
        loadMore: false,
        loading: true,
        notData: false,
      })
      let page = parseInt(_this.data.page.current_page) + 1
      WXAPI.orderList({ page: page, user_id: user_id }).then(function (res) {
        _this.setData({
          orderList: _this.data.orderList.concat(res.data.ordersAll),
          page: res.data.page
        })
        if (res.data.page.current_page >= res.data.page.last_page) {
          _this.setData({
            loadMore: false,
            loading: false,
            notData: true,
          })
        } else {
          _this.setData({
            loadMore: true,
            loading: false,
            notData: false,
          })
        }
      })
    }
  },
  //订单详情
  goOrderDetail:function(e){
    let order_id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/order-detail/order-detail?order_id='+order_id,
    })
  },
  // 取消订单
  delOrder:function(e){
    let _this = this
    wx.showModal({
      content: '确定要取消该订单吗?',
      success(resq) {
        let order_id = e.currentTarget.dataset.id
        let user_id = wx.getStorageSync('user_id')
        WXAPI.delOrder({ order_id: order_id, user_id: user_id }).then(function (res) {
          _this.setData({
            orderList: res.data.ordersAll,
            page: res.data.page
          })
          if (res.data.page.current_page >= res.data.page.last_page) {
            _this.setData({
              loadMore: false,
              loading: false,
              notData: true,
            })
          } else {
            _this.setData({
              loadMore: true,
              loading: false,
              notData: false,
            })
          }
        })
      }
    })
  }
})