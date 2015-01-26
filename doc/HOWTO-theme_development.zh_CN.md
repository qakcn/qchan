Qchan主题开发指引
====================

## 1 概述

本指引适用于Qchan、Qchan lite、Qchan plus的主题开发，三者主题在一定条件下可通用。

本指引使用Markdown语法书写，若使用文本编辑器查看，请注意正文中的格式标记，这些标记并不是正文的一部分。

以下示例只适用于使用文本编辑器查看：

    *表示说明* （两侧各有一个星号）
    **表示强调**（两侧各有两个星号）
    `表示代码`（两侧各有一个反引号，中间的内容才是代码内容，输入代码时请不要包括反引号）
    <表示链接>（两侧各有一个尖括号）

更多关于Markdown语法的说明，请参见<http://wowubuntu.com/markdown/>。

如果您使用Windows，可以使用[MarkdownEditor](https://github.com/jijinggang/MarkdownEditor)来查看本文档。下载地址：<https://github.com/jijinggang/MarkdownEditor/blob/master/download/MarkdownEditor.zip?raw=true>

如果您使用Mozilla Firefox，亦可安装扩展[Markdown Viewer](https://addons.mozilla.org/zh-CN/firefox/addon/markdown-viewer/)，然后直接用Firefox打开本文件。


## 2 文件结构

Qchan的文件结构很简单，主要是以下几个文件和目录：

* `header.php`  *页面头部*
* `footer.php`  *页面脚部*
* `main.php`  *主页*
* `functions.php`  *主题相关函数*
* `page-xxx.php`  *页面xxx*
* `lang/`  *语言文件目录*

其中必须要有的只有`main.php`和`functions.php`，`header.php`或`footer.php`通过在`main.php`或`page-xxx.php`中调用`load_header()`或`load_footer()`函数来加载。

`page-xxx.php`中的xxx可以自己定义。访问页面xxx的方法是在图床的主页地址后加上`page=xxx`的query，如：`http://tuchuang.org/index.php?page=xxx`。

`functions.php`是用于主题的自定义函数，只要不与Qchan内部已定义的函数重名即可，可以自由地编写任何代码。比如默认主题中对非AJAX上传的结果格式化的函数就是定义在此文件中的。

主题中所有文本文件都是**UTF-8编码无BOM格式**，其他格式无法保证不出现乱码。


## 3 模板引擎

Qchan没有另外实现模板引擎，使用的是PHP本身的模板引擎功能，任何没有包含在`<?php`与`?>`之中内容都会直接输出。

Qchan内置的函数没有直接输出内容的，结果均为返回值。需要使用输出语句来输出。

例如，`get_url()`是返回当前Qchan站点的URL地址的，结果是字符串。如果要输出，需要使用如下代码：

    <?php echo get_url() ?>

如果使用*PHP 5.3*以上版本，或者使用*PHP 5.2*以下版本但是在`php,ini`中设置了`short_open_tag=1`的话，可以使用短格式来输出。如下代码与上面的代码是等价的：

    <?=get_url ?>

推荐使用第二种方式，因为Qchan本身的运行环境要求就是*PHP > 5.3*。

## 4 引用js和css文件

如果需要在网页上引用js或者css文件，可以使用`theme_path()`函数来获得主题相对于Qchan根目录的路径。

比如主题名称是abc，则`theme_path()`的返回值就是`themes/abc/`（注意前面没有`/`结尾有`/`）。

和上面提到的`get_url()`配合，就可以引用在主题目录下的文件了。比如在主题js目录下有`main,js`，可以这样引用：

    <script src="<?=get_url().theme_path() ?>js/main.js"></script>

## 5 加载其他php文件

如果想包含在主题目录下的其他php文件，与上面类似，也可以使用`theme_path()`函数。不过不能使用`get_url()`了，而要使用`ABSPATH`常量来获得绝对路径（注意：`ABSPATH`的结尾并不包含`/`，使用时需要加上）。

如要包含主题下的`functions-format.php`，可以使用：

    <?php
        require ABSPATH . '/' . theme_path() . 'function-format.php';
    ?>

## 6 国际化

Qchan内置简单的国际化支持，当然并非强制要求。如果要进行国际化支持，请进行如下工作：

### 6.1 确定所使用的语言

Qchan可以支持的语言在Qchan根目录下的`lang/lang.list`文件里找到。此文件是JSON格式，内容是`"语言代码": "语言名称"`，因语言名称是JavaScript的Unicode转义字符，无法直接识别，所以语言名称请自行根据语言代码上网查找。

下面列出一些常用的语言的代码：

* `en`  *英语*
* `zh`  *中文*
* `zh-Hans`  *中文（简体）*
* `zh-Hant`  *中文（繁体）*
* `zh-CN`  *中文（中国）*
* `zh-TW`  *中文（台湾）*
* `zh-HK`  *中文（香港）*
* `ja`  *日语*
* `de`  *德语*
* `fr`  *法语*

### 6.2 建立语言文件

语言文件是**JSON格式**，以`语言代码.json`为文件名。

*默认语言*（编写主题所使用的语言）的语言文件里不需要任何内容，但是也要建立语言文件，否则在语言列表里不会出现默认语言。

其他语言文件内是一个**JSON对象**，对象中是`"默认语言字符串": "翻译语言字符串"`的属性。

例如：默认语言是*zh-CN*（中文），有“你好”、“再见”两个词，则*en*（英语）的语言文件`en.json`内容是这样的：

    {
        "你好":
        "Hello",
        
        "再见":
        "Goodbye"
    }

*ja*（日语）的语言文件`ja.json`内容是这样的：

    {
        "你好":
        "お早う",
        
        "再见":
        "さよなら"
    }

### 6.3 使用翻译的字符串

Qchan采用与gettext类似的语法，使用`__()`函数来格式化语言字符串。在编写时只需要把*默认语言字符串*放入函数参数中即可。`__()`亦如前面所提到的是返回字符串而不会输出，需要使用输出语句来输出。

如引用上面建立的语言文件的词语：

    <?=__('你好') ?>
    <?=__('再见') ?>

*默认语言字符串*必须和*语言文件*中的**完全一致**，否则因为找不到匹配的*翻译语言字符串*，会把*默认语言字符串*直接返回。

`__()`亦可接受多个参数，第一个参数是*默认语言字符串*，包含有`printf`格式化字符，其它参数将用来输出到*默认语言字符串*中，这样就可以在字符串中使用非固定的内容。

比如，输出Qchan的版本号：

    <?=__('Qchan版本： %s。', QCHAN_VER) ?>

## 7 上传处理

Qchan同时支持*AJAX上传*与*非AJAX上传*，以便获得最嘉的兼容性。下面分别说明。

### 7.1 非AJAX上传

非AJAX上传只需要提交表单到Qchan的主页即可，表单的设置如下：

* `method`属性设置为`post`
* `enctype`属性设置为`multipart/form-data`
* `action`属性如果是主页可以不设置，如果是其他页面需要设置为主页的URL

表单中包含的控件如下：

* 文件控件  
  即`input`元素，`type`属性为`file`，`name`属性为`file[]`（注意不要省略`[]`）。  
  多文件上传在HTML5中可以设置`multiple`属性，若要兼容旧浏览器可以添加多个`input`元素，一致设置上述属性。  
  可将`accept`属性设置为`image/*`来只接受图片文件。 亦可将`capture`属性设置为`filesystem camera`来兼容移动设备。
* 隐藏控件  
  即`input`元素，`type`属性为`hidden`。必须设置`name`属性为`normal`，`value`属性为`upload`。

表单可以包含一个提交按钮来提交，也可以用JavaScript的表单对象的`submit()`方法来提交。如下是一个表单示例：

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="files[]" accept="image/*" capture="filesystem camera" multiple>
        <input type="hidden" name="normal" value="upload">
        <input type="submit" value="Upload">
    </form>

### 7.2 AJAX上传

Qchan的AJAX上传处理通过引用内置的js文件来处理，可以方便快捷地处理相关操作，将主题开发大大简化，让开发者只需要专注于表现，而不用实现业务逻辑。

#### 7.2.1 引用内置js文件

使用函数`embed_script()`即可返回包含内置js文件引用的HTML代码，在主题的`body`元素的最后使用如下代码即可：

    <?=embed_script() ?>

Qchan会自动根据配置来返回合适的js文件（比如Qchan lite设置了`DIRECT_AJAX`的话，就会引用直接上传到贴图库的js文件）。

#### 7.2.2 设置回调函数

Qchan将js代码函数化后包装到了`qchan()`函数里，使用前无需初始化，但需要设置三个回调函数来处理上传过程中的相关操作（如果不设置也能上传，但无法得到任何结果）。

* `before`函数  
  此函数通过接受一个work对象参数，来进行上传前的工作，比如将上传的工作显示到页面上。
* `after`函数  
  此函数通过接受一个res对象参数，来进行上传后的工作，比如将上传结果显示到页面上。
* `progress`函数
  此函数通过接受一个work对象参数和一个range参数，来处理上传进度的表现。range是0到1之间的浮点数，0表示未上传，1表示上传完成。

上面三个函数都只用于根据返回的参数来处理页面的表现，不需要处理任何上传的逻辑。关于work与res对象见后面。三个回调函数通过调用如下方法来设置：

    qchan().setBefore(before);
    qchan().setAfter(after);
    qchan().setProgress(progress);

`before`、`after`、`progress`可以是事先声明的函数（函数变量），也可以直接使用匿名函数。

#### 7.2.3 上传

##### 7.2.3.1 URL上传

URL上传通过调用方法`qchan().url_upload(urls)`来进行，传入的`urls`参数是一个数组对象，数组成员是各个待上传的URL字符串。

例如：

    var urls = [
        "http://example.com/a.jpg",
        "http://example.com/b.png"
    ];
    qchan().url_upload(urls);

##### 7.2.3.2 文件上传

文件上传通过调用方法`qchan().file_upload(filelist)`来进行，传入的`filelist`参数是一个FileList对象，FileList对象中包含各个待上传的File对象。

FileList对象不用自己生成，可以通过两种方式来获得：

1. 文件控件（`type`属性为`file`的`input`元素）的`files`属性。
2. 拖放事件的事件对象的`dataTransfer.files`属性。

注意低版本的IE的兼容性问题。

例如：

HTML代码：

    <input type="file" id="filesel">

JavaScript代码：

    var files = document.getElementById('filesel').files;
    qchan().file_upload(files);

#### 7.2.4 work对象
