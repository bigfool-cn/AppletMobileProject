const md5 = require('../utils/md5.js')
const util = require('../utils/util.js')
// 服务端api地址
const API_BASE_URL = 'https://www.baidu.com/api'

const request = (url, method, data) => {
  wx.showLoading({
    title: '加载中...',
  })
  // api_token
  let api_token = wx.getStorageSync('api_token')
  
  // 请求地址

  let _url = API_BASE_URL + url
  
  return new Promise((resolve, reject) => {
    wx.request({
      url: _url,
      method: method,
      data: data,
      header: {
        'Token': api_token,
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      success(req) {
        if(req.data.code == 200){
          resolve(req.data)
        } else if (req.data.code == 4001) {
         wx.navigateTo({
            url: '/pages/user-auth/user-auth',
          })
        }else{
          wx.showToast({
            title: req.data.msg ? req.data.msg :'请求出错',
            icon: 'none',
            // image:'/image/error.png',
            duration: 2000
          })
        }
        return false;
        
      },
      fail(error) {
        reject(error)
      },
      complete(aaa) {
        wx.hideLoading()
      }
    })
  })
}

/**
 * 小程序的promise没有finally方法，自己扩展下
 */
Promise.prototype.finally = function (callback) {
  var Promise = this.constructor;
  return this.then(
    function (value) {
      Promise.resolve(callback()).then(
        function () {
          return value;
        }
      );
    },
    function (reason) {
      Promise.resolve(callback()).then(
        function () {
          throw reason;
        }
      );
    }
  );
}

module.exports = {
  request,
  // 检查token
  checkToken: (token) => {
    return request('/check-token','get', {
      token
    })
  },
  // 登录授权
  login: (data) => {
    return request('/wxlogin', 'post', data)
  },
  // 首页轮播图
  slideShow: (data) => {
    return request('/slide-show/list', 'get', data)
  },
  // 商品分类
  goodsCate: () => {
    return request('/goods-cate/list', 'get')
  },
  // 秒杀商品
  goodsSkill: () => {
    return request('/goods-skill/list', 'get')
  },
  // 秒杀商品详情
  goodsSkillDetail: (id) => {
    return request('/goods-skill/detail', 'get',{id})
  },
  // 热销商品
  goodsHot: () => {
    return request('/goods-hot/list', 'get')
  },
  // 商品列表
  goods: (data) => {
    return request('/goods/list', 'get', data)
  },
  // 商品详情
  goodsDetail: (id) => {
    return request('/goods/detail', 'get', {
      id
    })
  },
  // 搜索商品
  goodsSearch: (data) => {
    return request('/goods/search', 'get', data)
  },
  // 收货地址列表
  addressList: (data) => {
    return request('/user-address/list', 'get',data)
  },
  // 添加收货地址
  addressAdd: (data) => {
    return request('/user-address/add', 'post', data)
  },
  // 修改默认地址
  addressEditMr: (data) => {
    return request('/user-address/editmr', 'post', data)
  },
  // 修改地址
  addressEdit: (method,data) => {
    return request('/user-address/edit', method, data)
  },
  // 删除地址
  addressDel: (data) => {
    return request('/user-address/delete', 'post', data)
  },
  // 获取用户默认地址
  getMrAddress: (data) => {
    return request('/user-address/get-mr-address', 'get', data)
  },
  // 订单商品信息
  checkStock: (data) => {
    return request('/order/check-stock', 'get', data)
  },
  // 订单商品信息
  orderGoods: (data) => {
    return request('/order/order-goods', 'post', data)
  },
  // 秒杀商品
  skill: (data) => {
    return request('/order/skill', 'get', data)
  },
  // 提交订单
  submitOrder: (data) => {
    return request('/order/submit-order', 'post', data)
  },
  // 订单列表
  orderList: (data) => {
    return request('/order/order-list', 'get', data)
  },
  // 订单详情
  orderDetail: (data) => {
    return request('/order/order-detail', 'get', data)
  },
  delOrder:(data) => {
    return request('/order/del-order', 'get', data)
  }
}