$(function (){

    'use strict';
    // Switch Between Login & SignUp
    $('.login-page h2 span').click(function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
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

    //confirmation Message Yes No
    $('.confirm').click(function(){
        return confirm ('Are You Sure ?');
    });

    //////////////////////////////////////
    $('.live').keyup(function(){
        $($(this).data('class')).text($(this).val());
    });
});