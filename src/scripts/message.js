$(document).ready(function() {
    $.ajax({
        method: "GET",
        url:"src/php/showcase_message.php",
    })
    .done(function(result){
        if(result != "noMsg")
            data = JSON.parse(result);
        let user = [];
        let userList = [];
        let banlist = [];
        let found = false;

        if (typeof data !== 'undefined' && data.length > 0){
            for (i = 0; i < data.length; i++){
                if (user.length > 0){
                    let error = false;
                    let userToAdd;
                    for (k = 0; k < user.length; k++){
                        if (data[i].ID_USER1 == userID){
                            if(data[i].ID_USER2 == user[k]){
                                error = true;
                            }else{
                                userToAdd = "USER1";
                            }
                        }else{
                            if(data[i].ID_USER1 == user[k]){
                                error = true;
                            }else{
                                userToAdd = "USER2";
                            }
                        }
                    }
                    if(!error){
                        if(userToAdd == "USER1"){
                            user.push(data[i].ID_USER2);
                        }else{
                            user.push(data[i].ID_USER1);
                        }
                    }
                }else{
                    if (data[i].ID_USER1 != userID)
                        user.push(data[i].ID_USER1);
                    else
                        user.push(data[i].ID_USER2);
                }
            }

            for (i = 0; i < data.length; i++){
                for(k = 0; k < user.length; k++){
                    found = false;
                    if (banlist.length > 0){
                        if (!banlist.includes(user[k])){
                            if (data[i].ID_USER1 == user[k] ){
                                userList.push(data[i]);
                                found = true;
                            }
                            if (data[i].ID_USER2 == user[k] ){
                                userList.push(data[i]);
                                found = true;
                            }
                        }
                    }else{
                        if (data[i].ID_USER1 == user[k])
                            userList.push(data[i]);
                        if (data[i].ID_USER2 == user[k])
                            userList.push(data[i]);
                        banlist.push(user[k]);
                    }
                    if (found)
                        banlist.push(user[k]);
                }
            }

            userList.sort((a, b) => (a.STATUT > b.STATUT) ? -1 : 1);
            userList.forEach(element => {

                let ID;

                if(element.ID_USER1 != userID){
                    ID = element.ID_USER1;
                }
                else{
                    ID = element.ID_USER2;
                }
                if(element.STATUT == "Unread" && element.ID_USER1 != userID)
                    member = '<div data-id="'+ID+'" class="toMessage statut-0 friend_widget -message -hidden -unread"><div class="friend__info"><img class="avatar -friendlist" src="'+element.AVATAR+'"><div class="container__info"><span class="friend_name -member">'+element.USERNAME+'</span><span class="friend_message">'+element.CONTENT+'</span></div></div></div>';
                else
                    member = '<div data-id="'+ID+'" class="toMessage statut-1 friend_widget -message -hidden"><div class="friend__info"><img class="avatar -friendlist" src="'+element.AVATAR+'"><div class="container__info"><span class="friend_name -member">'+element.USERNAME+'</span><span class="friend_message">'+element.CONTENT+'</span></div></div></div>';
                $(".content_container .container .row .col .message__container").append(member);
            });

            var divElement = $('.content_container .container .row .col .message__container').find('.toMessage');
            divElement.sort(sortMe);

            function sortMe(a, b) {
                return a.className.match(/statut-(\d)/)[1] - b.className.match(/statut-(\d)/)[1];
            }

            $('content_container .container .row .col .message__container').html(' ');
            $('.content_container .container .row .col .message__container').append(divElement);

            $('.toMessage').click(function(){

                console.log("test");

                let IDuser = $(this).data('id');
                var title = $(".header__title").html();
                let name = $(this).children().eq(0).children().eq(1).children().eq(0).text();
                console.log(name);

                $.ajax({
                    method: "GET",
                    data: {ID:IDuser},
                    url:"src/php/updateMessageStatus.php",
                })
                .done(function(result){
                })

                $.ajax({
                    method: "GET",
                    data: {ID:IDuser},
                    url:"getMessage.php",
                })
                .done(function(result){
                    container.html(result);
                    $('.h1').text(name);
                    setReturnButton("message", {}, title);
                });

            });
        }else{
            $(".content_container .container .row .col .message__container").append('<img class="message__img" src="src/assets/img/ressources/no_conversation.png">');
            $('.message__container').css('height','100vh');
            $('.message__container').css('width','100%');
            $('body').css('overflow','hidden');
        }
    })
});