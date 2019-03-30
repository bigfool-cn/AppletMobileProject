const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    UserImage: '',
    Username: '',
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      UserImage: wx.getStorageSync('userInfo').avatarUrl,
      Username: wx.getStorageSync('userInfo').nickName
    })
  },
  onShow:function(){
    let cartItems = wx.getStorageSync('cartItems')
    let count = cartItems.length
    // 设置购物车数量
    wx.setTabBarBadge({
      index: 1,
      text: count + ''
    })
  },
  about:function(){
    wx.navigateTo({
      url: '../../pages/about/about' ,
    })
  }
})