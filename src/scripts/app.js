var latUser;
var lonUser;
var container;

$(document).ready(function(){

    container = $(".content_container");
    let city;
    let road;
    let postcode;

    //GET GEOLOC
    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };

    function success(pos) {
        var crd = pos.coords;

        latUser = crd.latitude;
        lonUser = crd.longitude;
    }

    function error(err) {
        console.warn(`ERREUR (${err.code}): ${err.message}`);
    }

    navigator.geolocation.getCurrentPosition(success, error, options);

    let lat;
    let lon;

    $(".nav_button_group").click(function(e) {
        $(".-active").removeClass('-active');
        $(this).children().eq(0).addClass('-active');
        $(this).children().eq(1).addClass('-active');
        $.ajax({
            method: "GET",
            url:$(this).data('url')+".php",
        })
        .done(function(result){
            container.html(result);
        });
    });

    var pubnub = new PubNub({
	    subscribeKey: 'sub-c-1ef083d0-dc62-11e8-911d-e217929ad048', // always required
	    publishKey: 'pub-c-07c00e21-e522-43ef-965d-f81e60f7a47f' // only required if publishing
	});

    // CREATE YES BUTTON
    $("#accept").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var user = $(this).data("user");

        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/accept_friend.php",
        })
        .done(function(result){
            document.location.reload(true);
        });
    });
    // CREAT NO BUTTON
    $("#refuse").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var user = $(this).data("user");

        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/refuse_friend.php",
        })
        .done(function(result){
            document.location.reload(true);
        });
    });

    $(document).on('input', '#slider', function() {
        $('#slider_value').html( $(this).val() + " km" );
    });

    $("#discover").click(function(e){
        e.preventDefault();
        return window.location.href = "members.php";
    });

    $('.label').click(function(){

        $('.label').each(function(){
            $(this).removeClass('selected');
        })

        if (!$(this).hasClass('selected'))
            $(this).addClass('selected');
    });

    // event
    // $('.clickme').click(function(){
    //     $.ajax({
    //         method: "GET",
    //         url:"src/php/add_event.php",
    //     })
    //     .done(function(result){

    //     });
    // });

    if($('body').is('.getMessage')){

        $('#message').keypress(function (e) {
            var key = e.which;
            if(key == 13)  // the enter key code
            {
                let user2 = $('body').data('id');
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
        });

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

                setTimeout(function(){
                    if ($('.messages').children().last().data('id') == $('body').data('me')){

                        $('.messages').children().last().remove();
                        $('.messages').children().last().css('opacity','1');
                    }else{
                         $('.messages').children().last().css('opacity','1');
                    }
                },10);

                //$('.messages').children().last().css('opacity','1');


                let realHeight  = $('.messages').scrollTop() + ($('.message__body').height() * 2);

                setTimeout(function(){
                    $('.messages').animate({
                        scrollTop: realHeight
                    }, 500);
                },100);

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

        $('.sendMessage').click(function(){

            let user2 = $('body').data('id');

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
        });
    }
});

$('body').on('click', '[data-editable]', function(){

    var $el = $(this);
    let value = $(this).html();

    var $input = $('<input />').val( $el.text() );
    $el.replaceWith( $input );

    var save = function(){
        let value = $p
        var $p = $('<p data-editable class="dog_name -nolimit"'+ value +'><i class="icon edit icon-ic_edit_48px"></i></p>').text( $input.val() );
        if ($(this).is(':empty')){
            $input.replaceWith( $p );
        }
    };

    $input.one('blur', save).focus();

});

function previewImage(file) {
    var gallery = $('#gallery');
    var imageType = /image.*/;

    if (!file.type.match(imageType)) {
        throw "File Type must be an image";
    }

    var thumb = document.createElement("div");
    thumb.classList.add('thumbnail');

    var img = document.createElement("img");
    img.classList.add('dog_img');
    img.file = file;
    thumb.appendChild(img);
    gallery.html(thumb);

    var reader = new FileReader();
    reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
    reader.readAsDataURL(file.slice(0,10 * 4096 * 4096));
    //console.log(reader);

    galleryButton = document.getElementById("gallery__button");
    var button = document.createElement("button");
    button.id = "deleteImage";
    button.setAttribute('type', 'button');
    button.classList.add('icon');
    button.classList.add('close-icon');
    if($('.edit__profile').length != 0){
        button.classList.add('-imgAdd');
    }
    else{
        button.classList.add('-dogAdd');
    }

    galleryButton.appendChild(button);

    var deleteImg = document.getElementById("deleteImage");
    deleteImage.addEventListener("click", function(){
        var gallery = document.getElementById("gallery");
        var galleryButton = document.getElementById("gallery__button");
        if (gallery.children.length != 0){
            gallery.removeChild(gallery.childNodes[0]);
            galleryButton.removeChild(galleryButton.childNodes[0]);
        }

        $('.label-file').show();
        let size = $(window).height();
        let position_popup = (size / 2) - (($('.dog_handler_container').height() + 40) / 2);
        $('.dog_handler_container').css('top',position_popup);


    });
}

function uploadFile(file, name, page){

    //console.log(name);

    if (name != "")
        var url = 'src/php/uploadFile.php?name='+name+'&'+'page='+page;
    else
        var url = 'src/php/uploadFile.php?page='+page;
    var name = name;
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //console.log(xhr.responseText);
            if (name != "")
                $("#addDog").prop('disabled', false);
            else
                $("#update").prop('disabled', false);
        }
    };
    fd.append("upload_file", file);
    xhr.send(fd);
}