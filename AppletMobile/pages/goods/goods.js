const WXAPI = require('../../wxapi/main');
const app = getApp();
Page({
  data: {
    goods_cate_id:0,
    inputShowed: false,
    inputVal: "",
    loadMore:true,
    loading:false,
    notData:false,
    goodsList: null,
    pages:null
  },
  onLoad:function(){
    let _this = this
    // 选择的分类
    let goods_cate_name = app.globalData.goods_cate_name
    _this.setData({
      goods_cate_id:app.globalData.goods_cate_id
    })
    wx.setNavigationBarTitle({
      title: goods_cate_name,
    })
    WXAPI.goods({page:1,id:_this.data.goods_cate_id}).then(function (res){
      _this.setData({
        goodsList:res.data.goods,
        pages:res.data.pages
      })
      let next_page = parseInt(res.data.pages.current_page) + 1
      if (next_page > res.data.pages.last_page) {
        _this.setData({
          loadMore: false,
          loading: false,
          notData: true
        })
      }
    })
  },

  // 搜索框可输入
  showInput: function () {
    this.setData({
      inputShowed: true
    });
  },
  // 搜索框重置
  hideInput: function () {
    this.setData({
      inputVal: "",
      inputShowed: false
    });
  },
  // 清除搜索框
  clearInput: function () {
    this.setData({
      inputVal: ""
    });
  },
  // 显示输入值到搜索框
  inputTyping: function (e) {
    this.setData({
      inputVal: e.detail.value
    });
  },
  // 点击搜索
  clickSearch:function(){
    let _this = this
    WXAPI.goodsSearch({page: 1, id: _this.data.goods_cate_id,kw:_this.data.inputVal}).then(function (res) {
      _this.setData({
        goodsList: res.data.goods,
        pages: res.data.pages
      })
      let next_page = parseInt(res.data.pages.current_page) + 1
      if (next_page > res.data.pages.last_page) {
        _this.setData({
          loadMore: false,
          loading: false,
          notData: true
        })
      }
    })
  },
  // 下拉刷新
  onPullDownRefresh: function () {
    let _this = this
    WXAPI.goods({ page: 1, id: _this.data.goods_cate_id, kw: _this.data.inputVal}).then(function (res) {
      _this.setData({
        goodsList:res.data.goods,
        pages:res.data.pages
      })
      wx.stopPullDownRefresh()
    })
  },

  //上拉加载
  onReachBottom: function () {
    let _this = this
    // 下一页
    let page = parseInt(_this.data.pages.current_page) + 1
    // 最后一页
    let lastPage = parseInt(_this.data.pages.last_page)
    if (page > lastPage){
      _this.setData({
        loadMore:false,
        loading:false,
        notData:true
      })
    }else{
      _this.setData({
        loadMore: false,
        loading: true,
        notData: false
      })
      WXAPI.goods({ page: page, id: _this.data.goods_cate_id, kw: _this.data.inputVal}).then(function (res){
        let next_page = parseInt(res.data.pages.current_page) + 1
        let lastPage = parseInt(res.data.pages.last_page)
        if (page > lastPage) {
          _this.setData({
            loadMore: false,
            loading: false,
            notData: true
          })
        }else{
          _this.setData({
            loadMore: true,
            loading: false,
            notData: false
          })
        }
        // 追加数据
        _this.setData({
          goodsList: _this.data.goodsList.concat(res.data.goods),
          pages: res.data.pages,
        })
      })
    }
  },
  // 跳转到商品详情页
  goGoodsDetail(e){
    let goods_id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/goods-details/goods-details?id='+goods_id,
    })
  },
  // 加入购物车
  addCart: function (e) {
    let _this = this
    let goods_id = e.target.dataset.id
    let cartItems = wx.getStorageSync("cartItems") || []
    let exist = cartItems.find(function (el) {
      return el.id == goods_id
    })
    let num = 1
    if (exist) {
      num = parseInt(exist.value) + 1;
    }
    //如果购物车里面有该商品那么他的数量每次加一
    // 检查商品库存
    WXAPI.checkStock({ id: goods_id, num: 1 }).then(function (res) {
      if (res.code == 2002) {
        wx.showToast({
          title: '商品库存不足',
          icon: 'none'
        })
        let goods = _this.data.goods
        goods.goods_stock = 0
        _this.setData({
          goods: goods
        })
      } else {
        let cartItems = wx.getStorageSync("cartItems") || []
        let exist = cartItems.find(function (el) {
          return el.id == goods_id
        })
        //如果购物车里面有该商品那么他的数量每次加一
        if (exist) {
          exist.value = parseInt(exist.value) + 1
        } else {
          cartItems.push({
            id: e.target.dataset.id,
            title: e.target.dataset.title,
            image: e.target.dataset.image,
            price: e.target.dataset.price,
            value: 1,
            selected: true
          })
        }
        wx.showToast({
          title: "加入购物车成功！",
          duration: 1000
        })
        //更新缓存数据
        wx.setStorageSync("cartItems", cartItems)
      }
    })
  },
});