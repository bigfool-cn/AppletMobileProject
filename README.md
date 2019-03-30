"#小程序商城" 
### 项目环境
* Ubuntu16.4
* PHP7.0
* MySQL5.6
* Redis 3.0 
* 腾讯云cos/数据万象/CDN 


### AppletMobile目录是小程序源代码
### AppletMobileServer目录是小程序对应的后台源码  

### 小程序模块完成模块有有
* 首页轮博图商品
* 商品分类
* 秒杀商品
* 热销商品
* 商品列表（带搜索功能）
* 购物车
* 订单列表
* 收货地址管理

### 后台管理系统完成模块有
* 登录
* 首页数据图表
* 栏目管理
* 商城管理（商品分类，商品轮播图，热销商品，秒杀商品，商品列表）
* 订单管理
* 管理员管理
* 权限管理

项目使用PHP中文分词扩展SCWS做搜索商品功能,使用已封装成工具类，helps目录下的Swcs,php,另外项目还封装ElasticSearch和迅搜的使用帮助类
需要请自行去官网下载安装
>ubuntu16.4安装scws
```shell
# 下载源码
wget http://www.xunsearch.com/scws/down/scws-1.2.3.tar.bz2

# 解压缩
tar xvjf scws-1.2.3.tar.bz2

# 编译安装
cd scws-1.2.3
./configure --prefix=/usr/local/scws
make && make install

# 检查是否安装成功
ls -al /usr/local/scws/lib/libscws.la
/usr/local/scws/bin/scws -h

# 下载通用词典
cd /usr/local/scws/etc
wget http://www.xunsearch.com/scws/down/scws-dict-chs-gbk.tar.bz2
wget http://www.xunsearch.com/scws/down/scws-dict-chs-utf8.tar.bz2
tar xvjf scws-dict-chs-gbk.tar.bz2
tar xvjf scws-dict-chs-utf8.tar.bz2

#编译扩展
apt-get install autoconf
cd /root/scws-1.2.3/phpext
phpize
./configure --with-scws=/usr/local/scws --with-php-config=/usr/local/bin/php-config
make && make install

# 加入配置文件中php.ini
[scws]
extension=scws.so
scws.default.charset=gbk
scws.default.fpath=/usr/local/scws/etc
```



