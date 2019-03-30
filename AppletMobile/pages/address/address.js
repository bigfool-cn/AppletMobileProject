const WXAPI = require('../../wxapi/main.js');
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
  // 默认地址更改
  selectedAddress:function(e){
    let _this = this
    let is_mr = e.currentTarget.dataset.mr;
    if(is_mr) return;
    let address_id = e.currentTarget.dataset.id
    let user_id = wx.getStorageSync('user_id')
    WXAPI.addressEditMr({address_id:address_id,user_id:user_id}).then(function (res) {
      wx.showToast({
        title: res.msg,
        icon:'success',
        duration:2000
      })
      _this.setData({
        addressList:res.data
      })
    })
  },
  // 添加新地址
  addAddress:function(){
    wx.navigateTo({
      url: '/pages/address-add/address-add',
    })
  },
  // 修改地址
  editAddress: function (e) {
    let address_id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/address-edit/address-edit?address_id='+address_id,
    })
  },
  // 删除地址
  delAddress:function(e){
    let _this = this
    let user_id= wx.getStorageSync('user_id')
    let address_id = e.currentTarget.dataset.id
    wx.showModal({
      title: '提示',
      content: '确定要删除改地址吗?',
      success(res) {
        if (res.confirm) {
          WXAPI.addressDel({ address_id: address_id, user_id: user_id }).then(function (res) {
            wx.showToast({
              title: res.msg,
              icon: 'success',
              duration: 2000
            })
            _this.setData({
              addressList: res.data
            })
          })
        } else if (res.cancel) {
          return false
        }
      }
    })
  }
})