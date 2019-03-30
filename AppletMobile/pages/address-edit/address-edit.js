const address = require('../../data/address-data.js')
const arrays = address.addressList;
const WXAPI = require('../../wxapi/main.js');
Page({
  /**
   * 页面的初始数据
   */
  data: {
    address_id:0,// 所修改的地址id
    address:null,// 当前address_id数据
    cityArray: null,// 地址数组
    global_sheng: null,//省
    ssq: '', // 省市区
    citysIndex: [0, 0, 0]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this;
    _this.setData({
      address_id:options.address_id
    })
    let user_id = wx.getStorageSync('user_id')
    WXAPI.addressEdit('get',{address_id:_this.data.address_id,user_id:user_id}).then(function (res) {
      console.log(res.data.address_index.split(','));
      _this.setData({
        address:res.data,
        ssq:res.data.address,
        isCheck:res.data.is_mr ? true : false,
        citysIndex:res.data.address_index.split(',')
      })
      //定义三列空数组
      var cityArray = [[], [], [],];
      if (!wx.getStorageSync('global_cityIndexData')) {
        //定义三列空数组
        var cityArray = [[], [], [],];
        //定义三列空数组
        var cityIndexArray = [[], [], [],];
        for (let i = 0, len = arrays.length; i < len; i++) {
          switch (arrays[i]['level']) {
            case 1:
              //第一列 省
              cityArray[0].push(arrays[i]["name"]);
              cityIndexArray[0].push(arrays[i]["sheng"])
              break;
            case 2:
              //第二列(默认由第一列第一个关联) 市
              if (arrays[i]['sheng'] == arrays[0]['sheng']) {
                cityArray[1].push(arrays[i]["name"]);
                cityIndexArray[1].push(arrays[i]["sheng"])
              }
              break;
            case 3:
              //第三列(默认第一列第一个、第二列第一个关联) 区/县
              if (arrays[i]['sheng'] == arrays[0]['sheng'] && arrays[i]['di'] == arrays[1]['di']) {
                cityArray[2].push(arrays[i]["name"]);
                cityIndexArray[2].push(arrays[i]["di"])
              }
              break;
          }
        }
        wx.setStorageSync('global_cityData', cityArray);
        wx.setStorageSync('global_cityIndexData', cityIndexArray);
      }
      var cityIndexArray = wx.getStorageSync('global_cityIndexData');
      console.log(cityIndexArray[0][_this.data.citysIndex[0]])
      for (let i = 0, len = arrays.length; i < len; i++) {
        switch (arrays[i]['level']) {
          case 1:
            //第一列 省
            cityArray[0].push(arrays[i]["name"]);
            break;
          case 2:
            //第二列(默认由第一列第一个关联) 市
            if (arrays[i]['sheng'] == cityIndexArray[0][_this.data.citysIndex[0]]) {
              cityArray[1].push(arrays[i]["name"]);
            }
            break;
          case 3:
            //第三列(默认第一列第一个、第二列第一个关联) 区/县
            if (arrays[i]['sheng'] == cityIndexArray[0][_this.data.citysIndex[0]] && arrays[i]['di'] == arrays[1]['di']) {
              cityArray[2].push(arrays[i]["name"]);
            }
            break;
        }
      }
      _this.setData({
        cityArray: cityArray
      });
    })
  },
  changeCitysChange: function (e) {
    let _this = this;
    let cityArray = _this.data.cityArray;

    let address = '';
    if (_this.data.ssq == undefined) {
      //下面方法中没有设置ssq，应该给它默认值 ，此时citysIndex相当于[0,0,0]
      let citysIndex = _this.data.citysIndex;
      for (let i in citysIndex) {
        address += cityArray[i][citysIndex[i]]
      }
    } else {
      address = _this.data.ssq;
    }
  },
  changeCitysChangeColumn: function (e) {
    console.log(e)
    let _this = this;
    var cityArray = _this.data.cityArray;

    var list1 = []; //存放第二列数据，即市的列
    var list2 = []; //存放第三列数据，即区的列

    var citysIndex = [];
    //主要是注意地址文件中的字段关系，省、市、区关联的字段有 sheng、di、level
    switch (e.detail.column) {
      case 0:
        //滑动左列
        for (let i = 0, len = arrays.length; i < len; i++) {
          if (arrays[i]['name'] == cityArray[0][e.detail.value]) {
            var sheng = arrays[i]['sheng'];
          }
          if (arrays[i]['sheng'] == sheng && arrays[i]['level'] == 2) {
            list1.push(arrays[i]['name']);
          }
          if (arrays[i]['sheng'] == sheng && arrays[i]['level'] == 3 && arrays[i]['di'] == arrays[1]['di']) {
            list2.push(arrays[i]['name']);
          }
        }
        citysIndex = [e.detail.value, 0, 0];
        var ssq = cityArray[0][e.detail.value] + list1[0] + list2[0] + '';

        _this.setData({
          global_sheng: sheng
        });
        break;
      case 1:
        //滑动中列
        var di;
        // 默认湖北省
        var sheng = _this.data.global_sheng ? _this.data.global_sheng : 42;
        console.log('sheng:' + sheng)
        list1 = cityArray[1];
        for (let i = 0, len = arrays.length; i < len; i++) {
          if (arrays[i]['name'] == cityArray[1][e.detail.value]) {
            di = arrays[i]['di'];
          }
        }
        for (let i = 0, len = arrays.length; i < len; i++) {
          if (arrays[i]['sheng'] == sheng && arrays[i]['level'] == 3 && arrays[i]['di'] == di) {
            list2.push(arrays[i]['name']);
          }
        }
        citysIndex = [_this.data.citysIndex[0], e.detail.value, 0];

        var ssq = cityArray[0][_this.data.citysIndex[0]] + list1[e.detail.value] + list2[0] + '';

        break;
      case 2:
        //滑动右列
        list1 = cityArray[1];
        list2 = cityArray[2];
        citysIndex = [_this.data.citysIndex[0], _this.data.citysIndex[1], e.detail.value];

        var ssq = cityArray[0][_this.data.citysIndex[0]] + list1[_this.data.citysIndex[1]] + list2[e.detail.value] + '';
        break;
    }

    _this.setData({
      "cityArray[1]": list1,//重新赋值中列数组，即联动了市
      "cityArray[2]": list2,//重新赋值右列数组，即联动了区
      citysIndex: citysIndex,//更新索引
      ssq: ssq,//获取选中的省市区
    });
  },
  // 点击默认地址选项
  checkMr: function () {
    this.setData({
      isCheck: !this.data.isCheck
    })
  },
  bindFormSubmit(e) {
    let data = {
      addressee: e.detail.value.user,
      mobile: e.detail.value.mobile,
      address: e.detail.value.address,
      xx_address: e.detail.value.xiangxi,
      mr: e.detail.value.moren,
      address_index:this.data.citysIndex.toString()
    }
    let qs = '?address_id=' + this.data.address_id + '&user_id=' + wx.getStorageSync('user_id')
    WXAPI.request('/user-address/edit'+qs,'POST',data).then(function (res) {
      wx.showToast({
        title: res.msg,
        icon: 'success',
        duration: 1000
      })
      setTimeout(function(){
        wx.navigateTo({ url: '/pages/address/address' })
      },1000)
    })
  }
})