var latUser;
var lonUser;
var container;
var logo;

$(document).ready(function(){

    container = $(".content_container");
    logo = $(".header__nav").html();
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
            if(!checkSession(result))
                return;
            let title;

            //$("body").css('overflow', 'initial');
            var name = $('.nav_button_group .icon__name').each(function(){
                if($(this).hasClass('-active')){
                    title = $(this).text();
                }
            });
            container.html(result);
            $('.h1').text(title);
            $(window).scrollTop(0);
        });
    });

    var pubnub = new PubNub({
	    subscribeKey: 'sub-c-1ef083d0-dc62-11e8-911d-e217929ad048', // always required
	    publishKey: 'pub-c-07c00e21-e522-43ef-965d-f81e60f7a47f' // only required if publishing
	});

    $(document).on('input', '#slider', function() {
        $('#slider_value').html( $(this).val() + " km" );
    });

    $("#discover").click(function(e){
        e.preventDefault();
        return window.location.href = "members.php";
    });

    // $('.label').click(function(){

    //     $('.label').each(function(){
    //         $(this).removeClass('selected');
    //     })

    //     if (!$(this).hasClass('selected'))
    //         $(this).addClass('selected');
    // });

    // event
    // $('.clickme').click(function(){
    //     $.ajax({
    //         method: "GET",
    //         url:"src/php/add_event.php",
    //     })
    //     .done(function(result){

    //     });
    // });

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

function checkSession(result)
{
    if(result == 0) {
        window.location.href = "index.php";
        return false;
    }
    return true;
}

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

function initSwipe(){
    //var x = nombre de swipe
    var x = 0;
    //var maxX = max swap
    var maxX = $(".dog_card_container").children().length;

    //remove bubble if exist as we can call back the function
    if ($('.bubble').length){
        $(".dog_card_bubble_container").remove();
    }

    if ($(".dog_card_container").children().length > 1){
        if($('body').is('.members'))
            $(".my_pet__container .container--full .row .col").append('<div class="dog_card_bubble_container"></div>');
        else
            $(".my_pet__container .container .row .col").append('<div class="dog_card_bubble_container"></div>');

        $(".dog_card_container").children().each(function(){
            $('.dog_card_bubble_container').append('<div class="bubble"></div>');
        });

        $(".bubble").first().addClass('-active');
    }


    $(".dog_card_container").swipe({
        swipe:function(event, direction, distance, duration, allowPageScroll){

            $(".bubble").each(function(){
            if($(this).hasClass('-active')){
                $(this).removeClass('-active');
            }
            });

            if (direction == "left" && x < maxX - 1){
                x += 1;
            }
            if (direction == "right"  && x > 0){
                x -= 1;
            }
            if (direction == "up" || direction == "down"){
                $(this).swipe({allowPageScroll:"auto"});
            }

            $(this).children().each(function(){
                $(this).animate({"right": ($('.dog_card_container').width() * x) + (16*x) +'px'}, "normal");
            });

            $(".bubble:eq("+x+")").addClass('-active');
        },
        threshold:100
    });
}

function attachListenersFriendMessage()
{
    $(".friend__link").off(); // On retire les précédents listeners
    $(".friend__link").click(function(e){
        var user = $(this).data("id");

        var name = $('.username').text();

        $.ajax({
            method: "GET",
            url:"getMessage.php",
            data: {ID:user}
        })
        .done(function(result) {
            container.html(result);
            setReturnButton("profile", {ID:user}, $(".header__title").html());
        });
    });
}

function setReturnButton(target, params = {}, title = "")
{

    var button = $("<button class='button back'><i class='left'></i></button>");
    $(".header__nav").html(button);
    button.click(function() {
        $.ajax({
            method: "GET",
            url:target+".php",
            data: params
        })
        .done(function(result) {
            $(".header__title").html(title)
            container.html(result);
            switch(target){
                case("home"):
                case("profile"):
                case("members"):
                case("friends"):
                case("walk"):
                case("message"):
                    $(".header__nav").html(logo);
                    break;
            }
        });
    })
}