const WXAPI = require('../../wxapi/main.js')
const WxParse = require('../../wxParse/wxParse.js');
const util = require('../../utils/util.js')
Page({
  data:{
    goodsSkillSwipers:null,
    goodsSkill:null,
    selectIndex:0
  },
  
  boxtwo: function (e) {
    let index = parseInt(e.currentTarget.dataset.index) 
    this.setData({
      selectIndex: index
    })
  },
  // 抢购
  skillAction: function (e) {
    let _this = this
    let is_start = this.data.goodsSkill.is_start;
    if (!is_start){
      wx.showToast({
        title: '秒杀未开始',
        icon: 'none'
      })
      return false
    }
    let user_id = wx.getStorageSync('user_id')
    let id = this.data.goodsSkill.goods_skill_id
    WXAPI.skill({ id: id, user_id: user_id }).then(function (res) {
      wx.showModal({
        content: '抢购成功!请提交订单完成抢购',
        showCancel: false,
        success(res) {
          if (res.confirm) {
            wx.navigateTo({
              url: '/pages/pay/pay?id=' + id + '&type=skill'
            })
          }
        }
      })
    })
  },

  onLoad: function (option){
    let _this = this
    let goods_skill_id = option.id
    wx.showLoading({
      title: '加载中',
    })
    WXAPI.goodsSkillDetail(goods_skill_id).then(function(res){
      wx.hideLoading()
      let skill_time = util.getDiffTime(res.data.goods_skill_time);
      res.data.skill_time = skill_time != "00:00:00" ? skill_time : '抢购中...'
      res.data.is_start == skill_time != "00:00:00" ? false : true
      res.data.is_end == res.data.count ? false: true
      _this.setData({
        goodsSkill:res.data,
        goodsSkillSwipers:res.data.goods_skill_image_urls
      })
      WxParse.wxParse('goodsSkillDetail', 'html', _this.data.goodsSkill.goods_skill_detail, _this, 5)
      WxParse.wxParse('goodsSkillParam', 'html', _this.data.goodsSkill.goods_skill_param, _this, 5);
    })
    let time = setInterval(function () {
      let skill_time = util.getDiffTime(_this.data.goodsSkill.goods_skill_time);
      skill_time = skill_time != "00:00:00" ? skill_time : '抢购中...'
      if (skill_time == '抢购中...') {
        clearInterval(time)
        _this.data.goodsSkill.is_start = true
      }
      _this.data.goodsSkill.skill_time = skill_time
      _this.setData({
        goodsSkill: _this.data.goodsSkill
      })
    }, 1000)
  }

})