const WXAPI = require('../../wxapi/main.js')
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    address: null,
    goodsNumArr:{},
    goods:[],
    goodsIds:'',
    type:'',
    totalExpress:0,
    totalSprice:0,
    userMsg:'',
    array: ['不限时送货时间', '工作日送货', '双休日、假日送货'],
    index:0,
    hasAddress: false,
  },
  onLoad:function(options){
    console.log(options)
    let _this = this
    let ids = options.id
    let type = options.type
    let idsNumArr = ids.split(',')
    var goodsNumArr = {};
    let idsArr = new Array();
    if(type=='pt'){
      idsNumArr.forEach(function (value, index) {
        let idNum = value.split('_')
        idsArr[index] = idNum[0]
        goodsNumArr[idNum[0]] = idNum[1]
      })
    }else{
      idsArr[0] = ids
      goodsNumArr[ids] = 1
    }
    _this.setData({
      goodsNumArr:goodsNumArr
    })
    let idstr = idsArr.join(',')
    WXAPI.orderGoods({ids:idsArr,type:type}).then(function(res){
      // 总运费
      let totalExpress = 0
      // 总订单金额
      let totalSprice = 0
      res.data.forEach(function (item, index) {
        totalExpress += parseFloat(item.goods_express)
        totalSprice = totalSprice + item.goods_sprice * _this.data.goodsNumArr[item.goods_id]
      })
      _this.setData({
        goods:res.data,
        goodsIds: idstr,
        type: type,
        totalSprice:totalSprice,
        totalExpress: totalExpress
      })
    })
    
  },
  onShow:function(){
    let _this = this
    let user_id = wx.getStorageSync('user_id')
    let selectAddress = app.globalData.selectAddress
    console.log(selectAddress)
    if (selectAddress == null) {
      WXAPI.getMrAddress({ user_id: user_id }).then(function (res) {
        console.log(res.data)
        _this.setData({
          address: res.data,
          hasAddress: res.data ? true : false 
        })
        app.globalData.selectAddress = res.data.length ? res.data : null
      })
    } else {
      _this.setData({
        address: app.globalData.selectAddress
      })
    }
  },
  // 选择地址
  selectAddress:function(){
    wx.navigateTo({
      url: '/pages/address-select/address-select',
    })
  },
  // 用户留言
  userMsg:function(e){
    this.setData({
      userMsg:e.detail.value
    })
  },

  pay:function(e){
    let _this = this
    if(!_this.data.hasAddress){
      wx.showToast({
        title: '请选择或添加收货地址',
        icon: 'none'
      })
    }
    let data = {
      user_id:wx.getStorageSync('user_id'),
      goods_ids:_this.data.goodsIds,
      type:_this.data.type,
      address_id:_this.data.address.address_id,
      user_msg:_this.data.userMsg,
      goods_num:JSON.stringify(_this.data.goodsNumArr),
      totalExpress:_this.data.totalExpress,
      totalSprice:_this.data.totalSprice
    }
    WXAPI.submitOrder(data).then(function(res){
      if(_this.data.type == 'pt'){
        let selectCartIndex = wx.getStorageSync('selectCartIndex') || []
        let cartItems = wx.getStorageSync('cartItems')
        let last_value = 0;
        selectCartIndex.forEach(function (value, index) {
          last_value = value==0 ? 0 : value+1
          cartItems.splice(value-last_value, 1)
        })
        wx.setStorageSync('cartItems', cartItems)
      }
      wx.showModal({
        content: '下单成功!',
        showCancel: false,
        success(resq) {
          if (resq.confirm) {
            wx.redirectTo({
              url: '/pages/order-detail/order-detail?order_id='+res.data.order_id,
            })
          }
        }
      })
      
    })
  },
})