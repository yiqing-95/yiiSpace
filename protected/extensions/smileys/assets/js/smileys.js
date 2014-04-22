(function($) {
    $.fn.setCursorPosition = function(pos) {
        if ($(this).get(0).setSelectionRange) {
            $(this).get(0).setSelectionRange(pos, pos);
        } else if ($(this).get(0).createTextRange) {
            var range = $(this).get(0).createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    }
})(jQuery);

//修改了addSmiley，增加了容器，在点击增加后，容器关闭
function addSmiley(code, textareaId, container) {
    if ($('#' + textareaId).length == 0)
        return;
    var currentContent = $('#' + textareaId).val();
    if (currentContent.length == 0)
        currentContent = code;
    else
        currentContent += ' ' + code;
    $('#' + textareaId).val(currentContent).focus().setCursorPosition($('#' + textareaId).val().length);
    $('#' + container).hide();
}

