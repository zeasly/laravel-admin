<script>
    /**
     * ajax 删除方法
     * @param url
     */
    function del(url, route, msg) {
        route = route || null;
        msg = msg || '确认删除？';
        layer.confirm(msg, {btnAlign: 'c'}, function (cindex) {
            layer.close(cindex);
            var index = layer.load(2);
            $.ajax({
                url: url,
                type: 'delete',
                data: {_token: "{{ csrf_token() }}"},
                dataType: 'json',
                success: function (data) {
                    if (data.status != 1) {
                        layer.close(index);
                        layer.msg(data.message, {icon: 0, time: 1500});
                        return false;
                    } else {
                        layer.msg(data.message, {icon: 1, time: 1500});
                        if (route) {
                            window.location.href = route;
                        } else {
                            location.reload();
                        }

                    }
                }
            });
        });
    }

    /*表单提交*/
    layui.form.on('submit(ajax_sub)', function (data) {
        var loading = layer.load(2);
        $.ajax({
            type: 'post',
            data: $('form').serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    layer.msg(data.message, {icon: 1, time: 1500}, function () {
                        parent.location.reload();
                    });
                } else {
                    layer.close(loading);
                    layer.alert(data.message, {icon: 0});
                }
            },
            error: function (e) {
                layer.close(loading);
                if (e.status == 422) {
                    var msg = '';
                    $.each(e.responseJSON.errors, function (key, value) {
                        msg = msg + value + '<br>';
                        return false;
                    });
                    layer.alert(msg, {icon: 0});
                } else {
                    layer.alert('未知错误', {icon: 0});
                }
            }
        });
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });

    /*表单提交*/
    layui.form.on('submit(ajax_sub_return)', function (data) {
        var loading = layer.load(2);
        $.ajax({
            type: 'post',
            data: $('form').serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    layer.msg(data.message, {icon: 1, time: 1500}, function () {
                        if (data.data.return) {
                            self.location = data.data.return;
                        } else {
                            self.location = document.referrer;
                        }
                    });
                } else {
                    layer.close(loading);
                    layer.alert(data.message, {icon: 0});
                }
            },
            error: function (e) {
                layer.close(loading);
                if (e.status == 422) {
                    var msg = '';
                    $.each(e.responseJSON.errors, function (key, value) {
                        msg = msg + value + '<br>';
                        return false;
                    });
                    layer.alert(msg, {icon: 0});
                } else {
                    layer.alert('未知错误', {icon: 0});
                }
            }
        });
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });


</script>