$(function (){

    'use strict';
    //Dashboard
    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-minus fa-lg"></i>')
        }else{
            $(this).html('<i class="fa fa-plus fa-lg"></i>')
        }
    });
    //execute SelectBox Plugin
    $("select").selectBoxIt({
        autoWidth:false 
       ,   aggressiveChange: true
        
    });

    //hide placeholder
    $('[placeholder]').focus(function(){
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){
        $(this).attr('placeholder',  $(this).attr('data-text'));
    })

    // convert password field from hidden to show
    var pasField = $('.password');
    $('.show-pass').hover(function(){
        pasField.attr('type','text');
    },function(){
        pasField.attr('type','password');
    });

    //confirmation Message Yes No
    $('.confirm').click(function(){
        return confirm ('Are You Sure ?');
    });

    //category option view
    $('.cat h3').click(function(){
        $(this).next('.full-view').fadeToggle(100);
    });

    $('.option span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');

        if($(this).data('view')==='full'){
            $('.cat .full-view').fadeIn(200);
        }else{
            $('.cat .full-view').fadeOut(200);
        }
    });

    //show delete button on child categories
    $('.child-link').hover(function(){
        $(this).find('.show-delete').fadeIn(400);
    }, function(){
        $(this).find('.show-delete').fadeOut(400);
    });
});