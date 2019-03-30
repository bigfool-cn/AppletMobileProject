const WXAPI = require('../../wxapi/main.js')
const WxParse = require('../../wxParse/wxParse.js');
Page({
  data:{
    goodsSwipers:null,
    goods:null,
    selectIndex:0,
  },
  goPay:function(e){
    let _this = this
    let goods_id = e.currentTarget.dataset.id
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
      }else{
        let id = goods_id + "_" + 1
        wx.navigateTo({
          url: '/pages/pay/pay?id=' + id + '&type=pt'
        })
      }
    })
  },
  boxtwo: function (e) {
    let index = parseInt(e.currentTarget.dataset.index) 
    this.setData({
      selectIndex: index
    })
  },
  addcart: function (e) {
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
    WXAPI.checkStock({id:goods_id,num:1}).then(function(res){
      if (res.code == 2002){
        wx.showToast({
          title: '商品库存不足',
          icon:'none'
        })
        let goods = _this.data.goods
        goods.goods_stock = 0
        _this.setData({
          goods:goods
        })
      }else{
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

  onLoad: function (option){
    let _this = this
    let goods_id = option.id
    WXAPI.goodsDetail(goods_id).then(function(res){
      if(res.code == 200){
        _this.setData({
          goods:res.data.goods,
          goodsSwipers:res.data.goods.goods_image_urls
        })
        WxParse.wxParse('goodsDetail', 'html', _this.data.goods.goods_detail, _this, 5)
        WxParse.wxParse('goodsParam', 'html', _this.data.goods.goods_param, _this, 5);
      }else{
        wx.showToast({
          title: '获取商品数据失败',
        })
      }
    })
  }

})