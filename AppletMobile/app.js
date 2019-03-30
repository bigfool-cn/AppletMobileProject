//app.js
const WXAPI = require('wxapi/main')
App({
  navigateToLogin: false,
  onLaunch: function () {
    const cartItems = wx.getStorageSync('cartItems')
    const count = cartItems.length
    // 设置购物商品种类数量
    wx.setTabBarBadge({
      index: 1,
      text: count+''
    })
    const that = this;
    // 检测新版本
    const updateManager = wx.getUpdateManager()
    updateManager.onUpdateReady(function () {
      wx.showModal({
        title: '更新提示',
        content: '新版本已经准备好，是否重启应用？',
        success(res) {
          if (res.confirm) {
            // 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
            updateManager.applyUpdate()
          }
        }
      })
    })
    /**
     * 初次加载判断网络情况
     * 无网络状态下根据实际情况进行调整
     */
    wx.getNetworkType({
      success(res) {
        const networkType = res.networkType
        if (networkType === 'none') {
          that.globalData.isConnected = false
          wx.showToast({
            title: '当前无网络',
            icon: 'loading',
            duration: 2000
          })
        }
      }
    });
    /**
     * 监听网络状态变化
     * 可根据业务需求进行调整
     */
    wx.onNetworkStatusChange(function (res) {
      if (!res.isConnected) {
        that.globalData.isConnected = false
        wx.showToast({
          title: '网络已断开',
          icon: 'loading',
          duration: 2000,
          complete: function () {
            that.goStartIndexPage()
          }
        })
      } else {
        that.globalData.isConnected = true
        wx.hideToast()
      }
    });
 
    // 判断是否登录
    let token = wx.getStorageSync('api_token');
    if (!token) {
      that.goLoginPageTimeOut()
      return
    }else{
      // 记录登录日志并更新用户信息
      let code = null
      wx.login({
        success: function (res) {
          if (res.code) {
            that.code = res.code
          }
        }
      })
      // 查看是否授权
      wx.getSetting({
        success(res) {
          if (res.authSetting['scope.userInfo']) {
            wx.getUserInfo({
              success: ress => {
                console.log(that.code)
                // 登录
                WXAPI.login({
                  code: that.code,
                  encryptedData: ress.encryptedData,
                  iv: ress.iv
                }).then(function (resss) {
                  console.log(resss)
                  // 移除过期的token
                  wx.setStorageSync('userInfo', ress.userInfo)
                  wx.setStorageSync('user_id', resss.data.user_id)
                  wx.setStorageSync('api_token', resss.data.api_token)
                })
              }
            })
          }
        },
      })
    }
    // WXAPI.checkToken(token).then(function (res) {
    //   if (res.code == 201) {
    //     wx.removeStorageSync('token')
    //     wx.setStorageSync('token', res.data.token)
    //   }
    // })
    // setInterval(function(){
    //   WXAPI.checkToken(token).then(function (res) {
    //     if (res.code == 201) {
    //       wx.removeStorageSync('token')
    //       wx.setStorageSync('token', res.data.token)
    //     }
    //   })
    // },180000);
  },
  goLoginPageTimeOut: function () {
    if (this.navigateToLogin) {
      return
    }
    this.navigateToLogin = true
    setTimeout(function () {
      wx.navigateTo({
        url: "/pages/user-auth/user-auth"
      })
    }, 500)
  },
  
  globalData: {
    isConnected: true,
    goods_cate_id:0,
    goods_cate_name:'全部',
    selectAddress:null,
  }
})





// App({
//   globalData: {
//     host: 'https://appletmobile.bigfool.cn/api/',
//     token: null,
//     userInfo: null
//   },
//   onLaunch: function () {
//     // 展示本地存储能力
//     // var logs = wx.getStorageSync('logs') || []
//     // logs.unshift(Date.now())
//     // wx.setStorageSync('logs', logs)
//     if (!this.globalData.token){
//       console.log(this.globalData.token)
//       wx.navigateTo({
//         url: '/pages/user-auth/user-auth',
//       })
//     } 
//     console.log(this.globalData.token)
//   },
//   // 登录获取token
//   // 保存用户信息
//   userLogin: function () {
//     let that = this
//     let code = null
//     wx.login({
//       success: function (res) {
//         if (res.code) {
//           that.code = res.code
//         }
//       }
//     })
//     wx.getSetting({
//       success: res => {
//         if (res.authSetting['scope.userInfo']) {
//           // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
//           wx.getUserInfo({
//             success: ress => {
//               // 可以将 res 发送给后台解码出 unionId
//               wx.request({
//                 url: that.globalData.host + 'wxlogin',
//                 method: 'POST',
//                 header: {
//                   'content-type': 'application/x-www-form-urlencoded',
//                 },
//                 data: {
//                   code: that.code,
//                   encryptedData: ress.encryptedData,
//                   iv:ress.iv
//                 },
//                 success: function (resss) {
//                   // 移除过期的token
//                   wx.removeStorageSync('token')
//                   wx.setStorageSync('token', resss.data.data.token)
//                   that.globalData.token = resss.data.data.token
//                   console.log(resss)
//                 },
//                 fail: function () {
//                   console.log('请求失败')
//                 }
//               })
//               that.globalData.userInfo = ress.userInfo
//               // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
//               // 所以此处加入 callback 以防止这种情况
//               if (that.userInfoReadyCallback) {
//                 that.userInfoReadyCallback(ress)
//               }
//             }
//           })
//         }
//       },
//       fail: res => {
//         console.log('失败')
//       }
//     })
//   }
// })