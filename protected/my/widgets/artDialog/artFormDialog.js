(function ($) {
    $.fn.formDialog = function (options) {

        return this.each(function(){
            var link = $(this);
            var url = link.attr('href');
            //alert(url);

            var artDialogId = options['id'];

            $.ajax({
                'url':url,
                'dataType': 'json',
                'success': function (data) {

                    var dialogContent = $('<div style="display:none;"><div class="forView"></div></div>');

                    var defaultDialogOptions = {
                        id:artDialogId,
                        width: 460
                    }
                    var dialogOptions = $.extend(defaultDialogOptions,options["dialogOptions"]);
                    dialogOptions['content'] = dialogContent.html();

                    $.dialog(dialogOptions);

                    dialogContent.find('.forView').html(data.view || data.form);

                    dialogContent.delegate('form', 'submit', function (e) {
                        e.preventDefault();
                        $.ajax({
                            'url': link.attr('href'),
                            'type': 'post',
                            'data': $(this).serialize(),
                            'dataType': 'json',
                            'success': function (data) {
                                if (data.status == 'failure')
                                    dialogContent.find('.forView').html(data.view || data.form);
                                else if (data.status == 'success') {
                                    var dialog = art.dialog.get(artDialogId);
                                    dialog.close();
                                    options['onSuccess'](data, e);
                                }
                            }
                        });

                    });

                }
            });
        });
    }
})(jQuery);