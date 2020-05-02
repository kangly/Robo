# 介绍:

* 有时需要在终端命令中执行php代码(例如采集一些数据)，使用 [Robo](https://github.com/consolidation/Robo) ，它是一个简单的PHP任务运行器旨在自动化常见的任务。
* 包含一些示例，采集主要使用 [QueryList](https://querylist.cc/) 实现，它使用jQuery选择器来做采集。


## 准备工作

下载 Robo.phar
```
wget http://robo.li/robo.phar
```
安装 `robo.phar` 到 `/usr/bin` 目录.
```
chmod +x robo.phar && sudo mv robo.phar /usr/bin/robo
```

### 注意

Mac环境下操作这步时，提示：`Operation not permitted`。
解决方法：需要关闭Rootless，重启按住 Command+R，进入恢复模式，打开Terminal，输入：
```
csrutil disable
```
重启之后重新执行命令就可以了。

如果要恢复默认Rootless设置，输入：
```
csrutil enable
```
OK，到这里准备工作就完成了。记得执行`composer install`安装依赖。

# Getting Started

执行操作
```
cd yourproject/app #你的项目文件夹下的app目录
robo hello worlds --load-from TestCommand.php #执行
```
正确的显示如下：
```
hello world
```

# 新增文件

在app目录下创建 *Command.php 文件，参考 testCommand.php 文件。
例如，要写一个处理采集新闻的操作，在app目录下新建newsCommand.php文件，文件内容如下：

```php
<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/3
 * Time: 08:42
 */
require_once '../base.php'; #依赖和一些便捷函数
use Base\Base;
use QL\QueryList; #采集

class newsCommand
{
    //抓取新浪新闻
    public function run()
    {
        #采集地址
        $url = 'http://roll.news.sina.com.cn/news/shxw/qwys/index.shtml';

        #采集规则
        $rules = array(
            'title' => array('.list_009 li a','text'),
            'link' => array('.list_009 li a','href')
        );

        #执行，需要注意，新浪新闻页面编码为gb2312，需要进行转码
        $ql = QueryList::get($url)->rules($rules)
            ->encoding('UTF-8')->removeHead() #转码并移除html头部
            ->query()->getData();

        de($ql->all());
    }
    
    /**
     * 其他操作...
     */
    public function others()
    {
    
    }
}
```
