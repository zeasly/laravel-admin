/**
 * 本页打开新页面
 * @param url
 * @param title
 * @author 穆风杰<hcy.php@qq.com>
 */
function openView(url, title, width, height) {
    height = height || (document.documentElement.clientHeight - 50) + 'px';
    width = width || '350px';
    title = title || '新窗口';
    layer.open({
        title: title,
        area: [width, height],//高宽
        offset: '30px',//坐标
        type: 2,//ifr 打开类型
        //shade: 0,//遮罩
        content: url //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
    });
}
