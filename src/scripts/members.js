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
            var slideDuree = 500;
            var oldContent = container.clone();
            $(oldContent).removeClass('content_container').addClass('fake_content_container');
            $('body').append(oldContent);
            $(oldContent).addClass('fading_to_left');
            setTimeout(function() {$(oldContent).remove();}, slideDuree);

            var title = $(".header__title").html();
            container.html(result);
            $('.h1').text('Membres');
            setReturnButton("members", {}, title);

            container.addClass('fading_from_right');
            setTimeout(function() {container.removeClass('fading_from_right')}, slideDuree);
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
                    member = '<div data-id="'+data[i].ID+'" class="test friend_widget -hidden"><div class="friend__info"><img class="avatar -friendlist" src="'+data[i].AVATAR+'"><div class="container__info"><span class="friend_name -member">'+data[i].USERNAME+'</span><span class="friend_name -km">A '+data[i].km+' km de vous</span></div></div></div>';
                    $(".member__filtred .container .row .col .member_widget_container").append(member);
                }
            }else{
                $(".member__filtred .container .row .col .member_widget_container").append('<p>Aucun résultat trouvé.</p>');
            }


            $(".test").click(function(e){
                e.preventDefault();
                e.stopPropagation();

                var user = $(this).data("id");
                var containerFilter = $(this);

                $.ajax({
                    method: "GET",
                    url:"src/php/simple_profile.php?ID="+user,
                })
                .done(function(result){

                    if ( containerFilter.hasClass('expanded')){

                    }else{
                        containerFilter.append(result);
                        containerFilter.addClass('expanded');
                        let tap = containerFilter.children().eq(1).children().eq(2);

                        let height = containerFilter.children().eq(1).height();
                        containerFilter.animate({ height: height + 25}, "fast");
                        setTimeout(function(){
                            tap.animate({opacity : 1},"slow");
                        },250);
                        containerFilter.children().eq(0).hide();
                        initSwipe();

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
                                var slideDuree = 500;
                                var oldContent = container.clone();
                                $(oldContent).removeClass('content_container').addClass('fake_content_container');
                                $('body').append(oldContent);
                                $(oldContent).addClass('fading_to_left');
                                setTimeout(function() {$(oldContent).remove();}, slideDuree);

                                var title = $(".header__title").html();
                                container.html(result);
                                $('.h1').text('Membres');
                                setReturnButton("members", {}, title);

                                container.addClass('fading_from_right');
                                setTimeout(function() {container.removeClass('fading_from_right')}, slideDuree);   
                            });
                        });
                    }

                    $('.tapToClose').click(function(e){
                        e.stopPropagation();

                        var containerFilter = $(this).parent().parent();
                        var child = $(this).parent().parent().eq(0).children().eq(1);
                        $(this).parent().parent().removeClass('expanded');
                        $(this).parent().parent().children().eq(0).css("display","block");
                        $(this).parent().parent().css("height", "75px");
                        $([document.documentElement, document.body]).animate({
                            scrollTop: containerFilter.offset().top - 100
                        }, 1000);
                        child.animate({opacity : 0},"slow");

                        setTimeout(function(){
                            child.remove();
                        },250);
                    });

                    $('.add__friend').click(function(){

                        let user = $(this).data("id");
                        $.ajax({
                            method: "GET",
                            data:{ID:user},
                            url:"src/php/add_friend.php",
                        })
                        .done(function(result){
                            $('.avatar__container #add_friend').remove();
                            $('.avatar__container .container--full .row .col').append('<div id="friend__button"><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Envoyé</button></div>');
                        });
                    });
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
});