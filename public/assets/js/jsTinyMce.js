/**
 * @var addDomain
 * Use 'data-tinymce-domain' in wysiwyg form to add domain before uri address
 */
var addDomain = '';

var myCustomFileBrowser = function(field_name, url, type, win)
{
    var myHref = '/admin/cms-share-elements';

    if(type == 'image'){
        myHref = '/admin/filemanager/getImage';
    }

    $.fancybox.open(
        {
            maxWidth: '85%',
            fitToView: false,
            width: '80%',
            height: '80%',
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: myHref,
            helpers:  {
                overlay : null
            },
            wrapCSS: 'tinymce-fancybox-wrap'
        }
    );
    insertSharePointItem = function (data) {
        var uri = data.uri;
        uri = addDomain + uri;

        $('input#' + field_name, win.document).val(uri);
    }
}

var tiny_options = {
    script_url:'/assets/lib/tinymce/tinymce.min.js'
    ,convert_urls: false
    ,height : 300
    ,image_advtab: false
    ,menubar: "edit insert view tools format table"
    ,language: 'pl'
    ,document_base_url: window.location.protocol + '//' + window.location.hostname
    ,plugins: [
        "advlist autolink link image lists charmap hr anchor pagebreak",
        "searchreplace visualblocks visualchars code insertdatetime nonbreaking",
        "save table contextmenu directionality paste textcolor"
    ]
    ,toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image forecolor"
};

var tiny_options_small = {
    script_url:'/assets/lib/tinymce/tinymce.min.js'
    ,convert_urls: false
    ,height : 200
    ,image_advtab: false
    ,statusbar: false
    ,menubar: ""
    ,language: 'pl'
    ,plugins: [
        "advlist autolink link image lists charmap hr anchor pagebreak",
        "searchreplace visualblocks visualchars code insertdatetime nonbreaking",
        "save table contextmenu directionality paste textcolor"
    ]
    ,toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table"
};



var tiny = function () {

    if($('textarea').data('small') == true)
    {
        var opt = tiny_options_small;
    }
    else if($('textarea').data('mail') == true)
    {
        var opt = tiny_options_mail;
    }
    else {
        var opt = tiny_options;
    }

    if($('textarea').data('tiny-options')){
        var opt = $.extend(opt, $('textarea').data('tiny-options'));
    }

    if($("textarea").data('tinymce-domain') && $('textarea').data('tinymce-domain').length > 0) {
        addDomain = $('textarea').data('tinymce-domain');
    }

    if ($(this).hasClass('tinyON') == false) {
        $(this).addClass('tinyON');

        //if ( typeof tinyMCE != 'undefined') {
        //    $(this).tinymce().remove();
        //}

        $(this).tinymce(opt);
    }
}

var removeTiny = function (textarea) {
    if (textarea == undefined) return false;
    if($(textarea).hasClass('tinyON')){
        $(textarea).removeClass('tinyON');
        textarea.tinymce().remove();
    }
}

var triggerLoadedTiny = function(t){
    setTimeout(function () {
        $(t).find('textarea[aria-role="tinymce"]').trigger('loaded');
    }, 1);
}

$(document).ready(function () {
    $('body').on('loaded', 'textarea[aria-role="tinymce"]', tiny);
    $('textarea[aria-role="tinymce"]').trigger('loaded');
    //initLoaded.addFn(triggerLoadedTiny);
});