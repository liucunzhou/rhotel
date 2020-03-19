function dateDiff(firstDate,secondDate){
    var firstDate = new Date(firstDate);
    var secondDate = new Date(secondDate);
    var diff = Math.abs(firstDate.getTime() - secondDate.getTime())
    var result = parseInt(diff / (1000 * 60 * 60 * 24));
    return result
}

$(function(){
    $("ul.nav-top li.tongxing").bind({
        mouseleave: function(){
            $("ul.nav-top li.tongxing .icon-fx").attr("src", "/assets/img/common/down.png");
        },
        mouseover: function(){
            $("ul.nav-top li.tongxing .icon-fx").attr("src", "/assets/img/common/up.png");
        }
    });

    $("ul.nav-top li.language").bind({
        mouseleave: function(){
            $("ul.nav-top li.language .icon-pre-language").attr("src", "/assets/img/common/language.png");
            $("ul.nav-top li.language .icon-fx").attr("src", "/assets/img/common/down.png");
        },
        mouseover: function(){
            $("ul.nav-top li.language .icon-pre-language").attr("src", "/assets/img/common/language_up.png");
            $("ul.nav-top li.language .icon-fx").attr("src", "/assets/img/common/up.png");
        }
    });

    $(".show-pop-user").click(function(){
        $(".mask").toggle();
        $(".pop-user").toggle();
        $('body').addClass("overflow");
    });

    $(".pop-user .close").click(function(){
        $(".mask").toggle();
        $(".pop-user").toggle();
        $('body').removeClass("overflow");
    });

    $("#login-mobile").bind("input propertychange",function(event){
        var mobile = event.target.value; 
        if(isNaN(mobile)) {
            alert("请输入正确的手机号");
            return false;
        }

        if(!isNaN(mobile) && mobile.length == 11) {
            $(".login-count").removeClass("unactive");
        } else {
            $(".login-count").addClass("unactive");
        }
    });

    $(".login-count").click(function() {
        var _ = $(this);
        if(_.hasClass("unactive")) {
            alert("请先输入正确的手机号");
            return false;
        }
        var txt = $(this).text().replace('s', '');
        if(isNaN(txt)) {
            var mobile = $("#login-mobile").val();
            var url = '/index/user/getLoginCode.html';
            console.log(url);
            $.post(url, {
                mobile:mobile
            }, function(res){
                if (res.data.resultCode == 0) {
                    var applyId = res.data.result;
                    $("#verify_id").val(applyId);
                } else {
                    alert(res.data.resultMsg);
                }
            })
        } else {
            return false;
        }

        var seconds = 60;
        var loop = setInterval(function() {
            if(seconds > 0) {
                seconds = seconds - 1;
                _.html(seconds + 's');
            } else {
                clearTimeout(loop);
                _.html('获取验证码');
            }
        }, 1000);
    });

    $(".login-submit").click(function(){
        // 发送版本统计信息，请移除
        try {
            var mobile = $("#login-mobile").val();
            if(mobile == '') {
                alert('手机号不能为空');
                return false;
            }

            var code = $("#login-code").val();
            var verify_id = $("#verify_id").val();
            if(code == '' || verify_id == '') {
                alert('请输入手机验证码');
                return false;
            }

            var url = '/index/user/login.html';
            $.ajax({
                url: url,
                data: {
                    mobile: mobile,
                    code: code,
                    verify_id: verify_id
                },
                method: 'post',
                dataType: 'json',
                success: function(res) {
                    if(res.code == 1) {
                        window.location.href = res.url;
                    } else {
                        alert(res.msg);
                    }
                }
            });

        } catch (e) {

        }
    });

    $("#reg-mobile").bind("input propertychange",function(event){
        var mobile = event.target.value; 
        if(isNaN(mobile)) {
            alert("请输入正确的手机号");
            return false;
        }

        if(!isNaN(mobile) && mobile.length == 11) {
            $(".reg-count").removeClass("unactive");
        } else {
            $(".reg-count").addClass("unactive");
        }
    });

    $(".reg-count").click(function() {
        var _ = $(this);
        if(_.hasClass("unactive")) {
            alert("请先输入正确的手机号");
            return false;
        }
        var txt = $(this).text().replace('s', '');
        if(isNaN(txt)) {
            var name = $("#reg-username").val();
            if(name == '') {
                alert('用户名不能为空');
                return false;
            }
            var mobile = $("#reg-mobile").val();
            var url = '/index/user/getRegisterApplyId.html';
            $.post(url, {
                name: name,
                mobile:mobile
            }, function(res){
                if (res.code == 1) {
                    var applyId = res.data.applyId;
                    $("#applyId").val(applyId);
                } else {
                    alert(res.data.msg);
                }
            })
        } else {
            return false;
        }

        var seconds = 60;
        var loop = setInterval(function() {
            if(seconds > 0) {
                seconds = seconds - 1;
                _.html(seconds + 's');
            } else {
                clearTimeout(loop);
                _.html('获取验证码');
            }
        }, 1000);
    });

    $(".reg-submit").click(function(){
        // 发送版本统计信息，请移除
        try {
            var code = $("#reg-code").val();
            var applyId = $("#applyId").val();
            if(code == '' || applyId == '') {
                alert('用户名不能为空');
                return false;
            }

            var mobile = $('#reg-mobile').val();
            if(mobile == '') {
                alert('手机号不能为空');
                return false;
            }

            var url = '/index/user/register.html';
            $.ajax({
                url: url,
                data: {
                    applyId: applyId,
                    code: code,
                    mobile: mobile
                },
                method: 'post',
                dataType: 'json',
                success: function(res) {
                    if(res.code == 1) {
                        window.location.href = res.url;
                    } else {
                        alert(res.msg);
                    }
                }
            });

        } catch (e) {

        }
    });

    $(".showFullScreen").click(function(){
        var src = $(this).attr("src");
        $(".fullScreen .full-screen-img img").attr('src', src);
        $(".fullScreen").show();
    });

    $(".fullScreen .close").click(function(){
        $(".fullScreen").hide();
    });
});