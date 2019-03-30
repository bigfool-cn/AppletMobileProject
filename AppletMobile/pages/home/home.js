const app = getApp();
const WXAPI = require('../../wxapi/main')
const util = require('../../utils/util.js')
Page({

  /* 页面的初始数据*/
  data: {
    indicatorDots: true,
    autoplay: false,
    slideShows:[],
    goodsCates:[],
    goodsSkills:[],
    goodsHots:[],
  },
  // 获取数据
  initData:function(){
    let _this = this
    let cartItems = wx.getStorageSync("cartItems")
    let count = cartItems.length
    // 设置购物车数量
    wx.setTabBarBadge({
      index: 1,
      text: count + ''
    })
    // 获取轮播图
    WXAPI.slideShow().then(function (res) {
      _this.setData({
        slideShow: res.data
      })
    })
    // 获取商品分类
    WXAPI.goodsCate().then(function (res) {
      _this.setData({
        goodsCates: res.data
      })
    })
    // 获取秒杀商品
    WXAPI.goodsSkill().then(function (res) {
      res.data.forEach(function (item, index) {
        var skill_time = util.getDiffTime(item.goods_skill_time);
        item.skill_time = skill_time != "00:00:00" ? skill_time : '抢购中...'
        item.is_start = skill_time == "00:00:00" ? true : false
        res.data[index] = item
      })
      _this.setData({
        goodsSkills: res.data
      })
      // 秒杀商品倒计时
      _this.data.goodsSkills.forEach(function (item, index) {
        var time = setInterval(function () {
          var skill_time = util.getDiffTime(item.goods_skill_time);
          skill_time = skill_time != "00:00:00" ? skill_time : '抢购中...'
          if (skill_time == "抢购中...") {
            var timer = wx.getStorageSync('timer_' + item.goods_skill_id)
            clearInterval(timer)
            wx.removeStorageSync('timer_' + item.goods_skill_id)
          }
          _this.data.goodsSkills[index].skill_time = skill_time
          _this.setData({
            goodsSkills: _this.data.goodsSkills
          })
        }, 1000)
        wx.setStorageSync('timer_' + item.goods_skill_id, time)
      })
    })
    // 获取热销商品
    WXAPI.goodsHot().then(function (res) {
      _this.setData({
        goodsHots: res.data
      })
    })
  },
  /* 生命周期函数--监听页面加载 */
  onLoad: function () {
    
  },
  onShow:function(){
    if(wx.getStorageSync('api_token') || ''){
      this.initData()
    }
    let cartItems = wx.getStorageSync('cartItems')
    let count = cartItems.length
    // 设置购物商品种类数量
    wx.setTabBarBadge({
      index: 1,
      text: count + ''
    })
  },
  // 下拉刷新
  onPullDownRefresh: function () {
    this.initData()
    wx.stopPullDownRefresh()
  },
  // 点击轮播图
  clickSlide:function(event){
    let goods_id = event.currentTarget.dataset.goods_id
    wx.navigateTo({
      url: '../../pages/goods-details/goods-details?id=' + goods_id,
    })
  },
  // 点击商品分类
  clickGoodsCate:function(event){
    let goods_cate_id = event.currentTarget.dataset.goods_cate_id
    let goods_cate_name = event.currentTarget.dataset.goods_cate_name
    app.globalData.goods_cate_id = goods_cate_id
    app.globalData.goods_cate_name = goods_cate_name
    wx.navigateTo({
      url: '/pages/goods/goods'
    })
    console.log(goods_cate_name)
  },

  // 秒杀商品详情页
  clickGoodsSkill:function(e){
    var id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/goods-skill-details/goods-skill-details?id=' + id,
    })
  },

  // 跳转子页面 详情页面
  btn:function(e){
    var goods_id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/goods-details/goods-details?id=' + goods_id,
    })
  }
  
})