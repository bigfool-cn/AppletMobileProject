const WXAPI = require('../../wxapi/main.js')
const app = getApp()
Page({
  data:{
    addressList:[],
  },
  onShow:function(){
    let _this = this
    let user_id = wx.getStorageSync('user_id')
    WXAPI.addressList({user_id:user_id}).then(function (res) {
      _this.setData({
        addressList:res.data
      })
    })
  },
  // 用户选择地址
  userSelectAddress:function(e){
    let index= e.currentTarget.dataset.index
    app.globalData.selectAddress = this.data.addressList[index];
    wx.navigateBack({
      delta:1
    })
  },
  // 添加新地址
  addAddress:function(){
    wx.navigateTo({
      url: '/pages/address-add/address-add',
    })
  },
})