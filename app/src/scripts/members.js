$(document).ready(function() {

    $('body').css('overflow-y','scroll');

    $('.label').click(function(event){
        event.preventDefault();

        // $('.label').each(function(){
        //     if($(this).hasClass('selected'))
        //         $(this).removeClass('selected');
        // })

        if ($(this).hasClass('selected')){
            $(this).removeClass('selected');
        }else{              
            $(this).addClass('selected');
        }
    });


    $('#filter').click(function(){

        height = $(window).height() - 120;
        console.log(height);
        $('.member__content').css('height',height+'px');

        $("html, body").animate({ scrollTop: 0 }, "slow");
        
        $(".members__handler").find("input:radio").prop("checked", false);

        $("body").css('overflow','hidden');
        $(".members__handler__container").css('display','block');

        $(".label").each(function(){
            if($(this).hasClass('selected'))
                $(this).removeClass('selected')
        });

        setTimeout(function(){
            $(".members__handler__container").css('transform','translateY(0%)');
        }, 500);

        setTimeout(function(){
            $(".members__handler__container").css('background','#00000024');
        }, 1500);

    });

    $('#add_friend').click(function(){
        let user = $(this).data("id");
        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/add_friend.php",
        })
        .done(function(result){
            $('.avatar__container #friend__button').remove();
            let button = '<div id="friend__button"><span class="friend__link" data-id="'+user+'"><i class="icon friend__message -friend icon__friend icon-ic_sms_48px"></i></span><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Envoyé</button></div>';
            $(button).insertBefore($('.avatar__container .container .row .col .avatars'));
            //$('.avatar__container .container .row .col').append('<div id="friend__button"><a class="friend__link" href="getMessage.php?ID='+user+'"><i class="icon friend__message -friend icon__friend icon-ic_sms_48px"></i></a><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Envoyé</button></div>');
            attachListenersFriendMessage();
        });
    });

    $('#submit__members').click(function(e){

        e.preventDefault();

        let walks = [];

        $('.label').each(function(){
            if($(this).hasClass('selected')){
                walks.push($(this).children().eq(0).children().eq(2).text());
            }
        });

        console.log(walks);

        $.ajax({
            method: "GET",
            data:{
                walk:walks,
                km : $('#slider').val()
            },
            url:"src/php/managefriend.php",
        })
        .done(function(result){

            console.log(result);

            let data = JSON.parse(result);
            let member;

                
            if($('.member_widget_container'))
                $('.member_widget_container').remove();

            if($('.tag_container'))
                $('.tag_container').remove();

            console.log(walks);

            if (walks.length == 0 || walks.length == 3 )
                $('.member__filtred .container .row .col').append('<div class="tag_container"><div class="tags"><span>Moins de '+$('#slider').val()+'km de vous</span></div><div class="tags"><span>Tout les types de balades</span></div></div>');
            else{
                $('.member__filtred .container .row .col').append('<div class="tag_container"><div class="tags"><span>Moins de '+$('#slider').val()+'km de vous</span></div></div>');

                for(i = 0; i < walks.length;i++){
                    $('.tag_container').append('<div class="tags"><span>'+walks[i]+'</span></div>');
                }
            }

            //<div class="tags"><span>Tout les types de balades</span></div>

            $('.member__filtred .container .row .col').append('<div class="member_widget_container"></div>');

            if(data.length > 0){

                for( let i = 0; i < data.length; i++){
                    member = '<div data-id="'+data[i].ID+'" class="friend_widget view"><div class="friend__info"><img class="avatar -friendlist" src="'+data[i].AVATAR+'"><div class="container__info"><span class="friend_name -member">'+data[i].USERNAME+'</span><span class="friend_name -km">A '+data[i].km+' km de vous</span></div></div></div>';
                    $(".member__filtred .container .row .col .member_widget_container").append(member);
                }
            }else{
                $(".member__filtred .container .row .col .member_widget_container").append('<p>Aucun résultat trouvé.</p>');
            }

            $(".view").click(function(e){
                e.preventDefault();
                e.stopPropagation();
        
                var user = $(this).data("id");
        
                $.ajax({
                    method: "GET",
                    url:"profile.php",
                    data: {ID:user}
                })
                .done(function(result) {
                    slidePage(result,'right');
                    var title = $(".header__title").html();
                    setReturnButton("members", {}, title);
                    setTitle('Membres');
                });
            });
        });

        $(".members__handler__container").css('background','transparent');

        setTimeout(function(){
            $(".members__handler__container").css('transform','translateY(100%)');
        }, 750);

        setTimeout(function(){
            $(".members__handler__container").css('display','none');
            $("body").css('overflow','auto');
            $(".showcase__member").css("height","0px");
        }, 1250);
    });

    $(".view").click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var user = $(this).data("id");

        $.ajax({
            method: "GET",
            url:"profile.php",
            data: {ID:user}
        })
        .done(function(result) {
            slidePage(result,'right');
            var title = $(".header__title").html();
            setReturnButton("members", {}, title);
            setTitle('Profil');
            $(window).scrollTop(0);
        });
    });
});