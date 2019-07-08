$(document).ready(function() {

    var pubnub = new PubNub({
	    subscribeKey: 'sub-c-1ef083d0-dc62-11e8-911d-e217929ad048', // always required
	    publishKey: 'pub-c-07c00e21-e522-43ef-965d-f81e60f7a47f' // only required if publishing
	});

	pubnub.subscribe({
		channels : ['message'],
	});

	pubnub.addListener({
	    message: function(message) {

            $('.messages').append(message.message);
            $('.messages').children().last().css('opacity','0');
            $('.messages').children().last().css('height','0');

            setTimeout(function(){
                if ($('.messages').children().last().data('id') == userID){
               
                    $('.messages').children().last().remove();
                  
                }else{
                    
                    $('.messages').children().last().css('opacity','1');
                    $('.messages').children().last().css('height','initial');
                }
            },10);

            let realHeight  = $('.messages').scrollTop() + ($('.message__body').height() * 2);

            // setTimeout(function(){
                $('.messages').animate({
                    scrollTop: realHeight
                }, 500);
            // },100);
		}
	});

    $('body').css('overflow','hidden');

    $('.message__viewer__container').css('height',$(window).height()  - 220+'px');
    $('.messages').css('height',$(window).height()  - 220+'px');
    $('.message__viewer__container').css('position','relative');

    let realHeight  = $('.messages')[0].scrollHeight;

    setTimeout(function(){
        $('.messages').animate({
            scrollTop: realHeight
        }, 500);
    },500);

    $('#message').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
        	sendMessage();
        }
    });

    $('.sendMessage').click(function(){
    	sendMessage();
    });
});

function sendMessage()
{
    if($('#message').val() != ""){
        $.ajax({
            method: "GET",
            data: {ID:user2,MESSAGE:$('#message').val()},
            url:"src/php/insertMessage.php",
        })
        .done(function(result){

            $.ajax({
                method: "GET",
                data: {ID:user2},
                url:"src/php/getLastMessage.php",
            })
            .done(function(result){

                setTimeout(function(){
                    $('.messages').append(result);
                },500);

                let realHeight  = $('.messages').scrollTop() + ($('.message__body').height() * 2);

                $('.messages').animate({
                    scrollTop: realHeight
                }, 500);

                if($('#message').hasClass('-error')){
                    $('#message').removeClass('-error');
                }

                $('#message').val('');
            })
        })
    }else{
        $('#message').addClass('-error');
    }
}