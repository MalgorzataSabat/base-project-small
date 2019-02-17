var actualStartTime = parseInt(new Date().getTime().toString().substr(0, 10));
var timerInt;

function timer(){
    var timeNow = parseInt(new Date().getTime().toString().substr(0, 10));
    var timeDiff = parseInt(timeNow - actualStartTime);
    var timers = $('[data-timer-start]');

    timers.each(function( index ) {
        var timerText = '';
        var timerType = 'add';
        var timerTextAttr =  $(this).attr('data-timer-text');
        var timerTypeAttr =  $(this).attr('data-timer-type');

        if (typeof timerTextAttr !== typeof undefined && timerTextAttr !== false){
            timerText = ' '+timerTextAttr;
        }

        if (typeof timerTypeAttr !== typeof undefined && timerTypeAttr !== false){
            timerType = timerTypeAttr;
        }

        var dateTmp = new Date(null);

        if(timerType == 'sub'){
            var timeDiffSub  = parseInt($(this).attr('data-timer-start')) - timeDiff;

            if(timeDiffSub < 0) { timeDiffSub = 0; }
            dateTmp.setSeconds(timeDiffSub);
        }else{
            dateTmp.setSeconds(parseInt($(this).attr('data-timer-start')) + timeDiff);
        }

        var hours = (parseInt(dateTmp.toISOString().substr(8, 2))-1)*24 + parseInt(dateTmp.toISOString().substr(11, 2));
        if(hours.toString().length == 1){ hours = '0'+hours; }

        $(this).html(hours+':'+dateTmp.toISOString().substr(14, 5)+timerText);
    });
}

timerInt = setInterval("timer()",1000);



$(document).ready(function(){

    var reportTimeTaskInput = $('input[type="text"].report-task');

    /*--- TIMER ---*/
    // formularz input w widgecie
    $('.task-report-widget').on('keyup click', '.report-task', function(){
        var reportTime = $(this);
        var value = reportTime.val();

        delay(function(){
            $.ajax({
                url: reportTime.data('url-search'),
                method: 'post',
                data: {'search': value},
                beforeSend: function(){
                    $('body').addClass('loading');
                },
                success : function(response) {
                    $('.task-timer-search .task-timer-search-body').html(response);
                    $('.task-timer-search').show();
                    $('body').removeClass('loading');
                }
            });
        }, 500);
    });

    // formularz input w layoucie
    $('.report-time-layout').on('keyup', 'input#search.form-control', function(){
        var $this = $(this);
        var value = $this.val();

        delay(function(){
            $.ajax({
                url: $this.data('url-search'),
                method: 'post',
                data: {'search': value},
                beforeSend: function(){
                    $this.addClass('loader-wrap');
                    $('.report-time-content').addClass('loader-wrap');
                },
                success : function(response) {
                    $('.report-time-content').html(response);
                    $('.report-time-content').removeClass('loader-wrap');
                    $('body').removeClass('loading');
                }
            });
        }, 500);
    });

    $('body').on('click', '[action-task-start-time]', function(e){
        $(this).find('i').attr('class', 'fa fa-spinner fa-spin fa-fw');
    });

    $('body').on('click', '[action-task-stop-time]', function(e){
        $(this).find('i').attr('class', 'fa fa-spinner fa-spin fa-fw');
    });


    $('body').on('bootstrap-ajax:success', '[action-task-start-time]', function(){
        $('[data-ref-group="task-timer"]').trigger('reload');
    });

    $('body').on('bootstrap-ajax:success', '[action-task-stop-time]', function(){
        $('[data-ref-group="task-timer"]').trigger('reload');
    });

    $('body').on('reload', '.task-list-widget', function(e){
        var $this = $(this);
        $.ajax({
            url: $this.attr('action-reload'),
            method: 'post',
            beforeSend: function(){
                $('body').addClass('loading');
            },
            success : function(response) {
                $this.find('.task-list-widget-body').replaceWith(response);
                $('body').removeClass('loading');
                actualStartTime = parseInt(new Date().getTime().toString().substr(0, 10));
            }
        });
    });

    $('body').on('reload', '.task-report-widget', function(e){
        var $this = $(this);
        $.ajax({
            url: $this.attr('action-reload'),
            method: 'post',
            beforeSend: function(){
                $('body').addClass('loading');
            },
            success : function(response) {
                $this.find('.task-report-widget-body').replaceWith(response);
                $('body').removeClass('loading');
                reportTimeTaskInput = $('.report-task');
                actualStartTime = parseInt(new Date().getTime().toString().substr(0, 10));
            }
        });
    });

    $('body').on('reload', '.report-time-layout', function(e){
        var $this = $(this);
        $.ajax({
            url: $this.attr('action-reload'),
            method: 'post',
            beforeSend: function(){
                $('body').addClass('loading');
            },
            success : function(response) {
                $this.replaceWith(response);
                $('body').removeClass('loading');
                actualStartTime = parseInt(new Date().getTime().toString().substr(0, 10));
            }
        });
    });


    $('.task-report-widget, .task-list-widget').on('click', '.show-task-enity', function(e){
        var timeEnityList = $(this).closest('.task-item').find('.task-enity-list');
        if(timeEnityList){
            timeEnityList.slideToggle('fast');
        }
        e.preventDefault();
    });
});




$('html').click(function(e) {
    $('.search-results').removeClass('loading').removeClass('loaded');

    if ($(e.target).closest(".task-timer-search").length === 0) {
        $(".task-timer-search").hide();
    }
});
