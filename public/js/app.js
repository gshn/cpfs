'use strict';
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="dropdown"]').dropdown()

    $('a[href="#"]').click(function(e) {
        e.preventDefault();
        return false;
    });

    $(window).scroll(function(e) {
        var st = $(this).scrollTop();
        var h = $(this).height();

        if (st > h / 4) {
            $('body').addClass('scroll');
        } else if (st < h / 4) {
            $('body').removeClass('scroll');
        }
    });

    $('.list-check-all').click(function() {
        var checked = this.checked;
        $(this).parents('table').find('.list-check[type="checkbox"]').each(function() {
            this.checked = checked;
        });
    });

    $('.list-form-submit').click(function() {
        var $this = $(this);
        var checked = false;
        var type = $(this).val();

        $this.parents('form').find('.list-check[type="checkbox"]').each(function() {
            if (this.checked) {
                checked = true;
                return false;
            }
        });
        if (!checked) {
            swal(type, type + '하실 항목을 선택해주세요.', 'warning');
            return false;
        }

        swal({
            title: type,
            text: '선택하신 항목을 ' + type + '하시겠습니까?',
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'btn-info',
            confirmButtonText: '네, ' + type + '해요!',
            cancelButtonText: '아니요',
            closeOnConfirm: false
        },
        function() {
            if (type === '수정') {
                $this.parents('form').append('<input type="hidden" name="req" value="list-modify">');
            } else if (type === '삭제') {
                $this.parents('form').append('<input type="hidden" name="req" value="list-delete">');
            }
            $this.parents('form').submit();
        });
    });

});

if (typeof CKEDITOR === 'object') {
    CKEDITOR.replace('content');
}

var loadDeferredStyles = function() {
    var addStylesNode = document.getElementById('deferred-styles');
    var replacement = document.createElement('div');
    replacement.innerHTML = addStylesNode.textContent;
    document.body.appendChild(replacement)
    addStylesNode.parentElement.removeChild(addStylesNode);
};

var raf = requestAnimationFrame || mozRequestAnimationFrame || webkitRequestAnimationFrame || msRequestAnimationFrame;
if (raf) {
    raf(function() {
        window.setTimeout(loadDeferredStyles, 0);
    });
} else {
    window.addEventListener('load', loadDeferredStyles);
}

var setCookie = function(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    var secure = '';
    if (location.protocol === 'https:') {
        secure = 'secure';
    }

    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;" + secure;
}

var getCookie = function(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

var move_scroll = function (id) {
    $(window).on("mousewheel.disableScroll DOMMouseScroll.disableScroll touchmove.disableScroll", function (e) {
        e.preventDefault();
        return;
    });

    var top = $(id).offset().top + 'px';
    $('html, body').stop(true).animate({ scrollTop: top }, {
        duration: 1500, easing: 'easeOutExpo', complete: function () {
            $(window).off(".disableScroll");
        }
    });
}
