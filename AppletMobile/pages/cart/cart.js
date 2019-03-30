const WXAPI = require('../../wxapi/main.js')
Page({
  data: {
    orderIndex:[],
    cartItems:[],
    total:0,
    CheckAll: wx.getStorageSync("checkAll")
  },
  onLoad:function(e){
    
  },
  onShow: function () {
     let cartItems = wx.getStorageSync("cartItems")
     this.setData({
       cartList: false,
       cartItems: cartItems
     })
     this.getsumTotal()
     let isCheckAll = true
    // 单个未选择，取消全选
    for (let i = 0; i < cartItems.length; i++) {
      if (cartItems[i].selected === false) {
        isCheckAll = false
        break
      }
    }
    this.setData({
      CheckAll: isCheckAll
    })
    wx.setStorageSync("checkAll", isCheckAll)
    let count = cartItems.length
    // 设置购物车数量
    wx.setTabBarBadge({
      index: 1,
      text: count + ''
    })
   },

  //全选择
  select:function(e){
    console.log(e)
    let CheckAll = this.data.CheckAll;
    CheckAll = !CheckAll
    let cartItems = this.data.cartItems

    for(let i=0;i<cartItems.length;i++){
      cartItems[i].selected = CheckAll
    }

    this.setData({
      cartItems: cartItems,
      CheckAll: CheckAll
    })
    wx.setStorageSync("checkAll", CheckAll)
    wx.setStorageSync("cartItems", cartItems)  //存缓存
    this.getsumTotal()
   },
   // 加数量
   add:function (e) {
     let _this = this
     let cartItems = _this.data.cartItems   //获取购物车列表
     let index = e.currentTarget.dataset.index //获取当前点击事件的下标索引
     let value = cartItems[index].value  //获取购物车里面的value值
     let goods_id = e.currentTarget.dataset.id
     let num = value + 1
     // 检查商品库存
     WXAPI.checkStock({ id: goods_id, num: num }).then(function (res) {
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
         value++
         cartItems[index].value = value;
         _this.setData({
           cartItems: cartItems
         });
         _this.getsumTotal()
         wx.setStorageSync("cartItems", cartItems)  //存缓存
       }
     })
   },
   
  // 减数量
  reduce: function (e){
     let cartItems = this.data.cartItems  //获取购物车列表
     let index = e.currentTarget.dataset.index  //获取当前点击事件的下标索引
     let value = cartItems[index].value  //获取购物车里面的value值

    if(value==1){
       value --
       cartItems[index].value = 1
     }else{
       value --
       cartItems[index].value = value;
     }
     this.setData({
       cartItems: cartItems
     });
     this.getsumTotal()
     wx.setStorageSync("cartItems", cartItems)
   },
  
  // 单个选择
  selectedCart:function(e){
    let cartItems = this.data.cartItems   //获取购物车列表
    let index = e.currentTarget.dataset.index  //获取当前点击事件的下标索引
    let selected = cartItems[index].selected    //获取购物车里面的value值
    let isCheckAll = true
    //取反
    cartItems[index].selected = !selected
    this.setData({
      cartItems: cartItems
    })
    this.getsumTotal();   
    wx.setStorageSync("cartItems", cartItems)
    // 单个未选择，取消全选
    for (let i = 0; i < cartItems.length; i++) {
      if (cartItems[i].selected === false) {
        isCheckAll = false
        break
      }
    }
    this.setData({
      CheckAll: isCheckAll
    })
    wx.setStorageSync("checkAll", isCheckAll)
   },

  // 删除
  shanchu:function(e){
    let cartItems = this.data.cartItems  //获取购物车列表
    let index = e.currentTarget.dataset.index  //获取当前点击事件的下标索引
    cartItems.splice(index,1)
    this.setData({
      cartItems: cartItems
    });
    if (cartItems.length) {
      this.setData({
        cartList: false
      });
    }
    this.getsumTotal()
    wx.setStorageSync("cartItems", cartItems)
    let count = cartItems.length
    // 设置购物车数量
    wx.setTabBarBadge({
      index: 1,
      text: count + ''
    })
   },

   // 结算
   go:function(e){
     let cartItems = this.data.cartItems
     let selcetGoods = new Array()
     let selectCartIndex = new Array()
     for (let i = 0; i < cartItems.length; i++) {
       if (cartItems[i].selected) {
         selcetGoods.push(cartItems[i].id + '_' + cartItems[i].value)
         selectCartIndex.push(i)
       }
     }
     wx.removeStorageSync('selectCartIndex')
     wx.setStorageSync('selectCartIndex', selectCartIndex)
     let ids = selcetGoods.join(',');
     if(!ids){
       wx.showToast({
         title: '请选择需要结算的商品',
         icon:'none'
       })
       return
     }
     wx.navigateTo({
       url: '/pages/pay/pay?id='+ids+'&type=pt',
     })
   },


   // 合计
   getsumTotal: function () {
     let sum = 0
     for (let i = 0; i < this.data.cartItems.length; i++) {
       if (this.data.cartItems[i].selected) {
         sum += this.data.cartItems[i].value * this.data.cartItems[i].price
       }
     }
     //更新数据
     this.setData({
       total: sum
     })
   },
})