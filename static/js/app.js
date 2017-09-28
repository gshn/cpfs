'use strict';
$('[data-toggle="tooltip"]').tooltip();
$('[data-toggle="dropdown"]').dropdown()
$('a[href="#"]').click(function(e) {
    e.preventDefault();
    return false;
});

$('.navToggle').click(function() {
    $('#wrap').toggleClass('nav-toggle');
});

$('.check-all').click(function() {
    var checked = this.checked;
    $(this).parents('table').find('.check-list[type="checkbox"]').each(function() {
        this.checked = checked;
    });
});

$('.list-form-submit').click(function() {
    var $this = $(this);
    var checked = false;
    var type = $(this).val();

    $this.parents('form').find('.check-list[type="checkbox"]').each(function() {
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
        confirmButtonClass: 'btn-info btn-raised',
        confirmButtonText: '네, ' + type + '해요!',
        cancelButtonText: '아니요',
        closeOnConfirm: false
    },
    function() {
        if (type === '수정') {
            $this.parents('form').find('#req').val('list-modify');
        } else if (type === '삭제') {
            $this.parents('form').find('#req').val('list-delete');
        }
        $this.parents('form').submit();
    });
});

$('.btn-reply').click(function() {
    var id = $(this).data('id');

    $('.tr-reply').hide();
    $('.tr-reply[data-id=' + id + ']').show();
});

/* 스크롤 없이 볼 수 있는 콘텐츠에서 렌더링 차단 자바스크립트 및 CSS 삭제 */
var loadDeferredStyles = function() {
    var addStylesNode = document.getElementById('deferred-styles');
    var replacement = document.createElement('div');
    replacement.innerHTML = addStylesNode.textContent;
    document.body.appendChild(replacement)
    addStylesNode.parentElement.removeChild(addStylesNode);
};
var raf = requestAnimationFrame || mozRequestAnimationFrame ||
      webkitRequestAnimationFrame || msRequestAnimationFrame;
if (raf) {
    raf(function() { window.setTimeout(loadDeferredStyles, 0); });
} else {
    window.addEventListener('load', loadDeferredStyles);
}
