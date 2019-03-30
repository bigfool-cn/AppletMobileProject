// pages/user-auth.js
const app = getApp()
const WXAPI = require('../../wxapi/main')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    canIUse: wx.canIUse('button.open-type.getUserInfo')
  },

  onLoad() {
    
  },

  //授权事件
  bindGetUserInfo(e) {
    let _this = this
    let code = null
    wx.login({
      success: function (res) {
        if (res.code) {
          _this.code = res.code
        }
      }
    })
    // 查看是否授权
    wx.getSetting({
      success(res) {
        if (res.authSetting['scope.userInfo']) {
          wx.getUserInfo({
            success: ress => {
              console.log(_this.code)
              // 登录
              WXAPI.login({
                code: _this.code,
                encryptedData: ress.encryptedData,
                iv:ress.iv
              }).then(function(resss){
                console.log(resss)
                // 移除过期的token
                wx.setStorageSync('userInfo', ress.userInfo)
                wx.setStorageSync('user_id', resss.data.user_id)
                //wx.setStorageSync('api_secret_key',resss.data.api_secret_key)
                wx.setStorage({
                  key: 'api_token',
                  data: resss.data.api_token,
                  success(){
                    //授权成功后，跳转进入小程序首页
                    wx.switchTab({
                      url: '/pages/home/home'
                    })
                  }
                })
                
              })
            }
          })
        }else{
          wx.openSetting({
            success(res) {
              console.log(res.authSetting)
            }
          })
        }
      },
      fail:function(){
        wx.showToast({
          title: '打开用户授权登录失败!',
          icon: 'cancel',
          duration: 5000
        })
      }
    })
  }
})