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

function addSmiley(code, textareaId) {
    if ($('#' + textareaId).length == 0)
        return;
    var currentContent = $('#' + textareaId).val();
    currentContent += ' ' + code + ' ';
    $('#' + textareaId).val(currentContent).focus().setCursorPosition($('#' + textareaId).val().length);
}

