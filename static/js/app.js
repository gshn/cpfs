'use strict';

$('[data-toggle="tooltip"]').tooltip();
$('[data-toggle="dropdown"]').dropdown()

$('a[href="#"]').click(function(e) {
    e.preventDefault();
    return false;
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
