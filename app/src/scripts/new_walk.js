$(document).ready(function() {
    $('body').css('overflow','hidden');

    $('#time').clockTimePicker();
    $('.input__container button').eq(0).css('display','none');
    $('.input__container').eq(6).css('justify-content', 'flex-end');


    var walk_id_owner;
    var walk_name;
    var walk_location;
    var walk_type;
    var walk_date;
    var walk_length;

    var step = 1;

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    var hour = today.getHours();
    if(dd<10){
            dd='0'+dd
        }
        if(mm<10){
            mm='0'+mm
        }

    today = yyyy+'-'+mm+'-'+dd;
    document.getElementById("date").setAttribute("min", today);
    document.getElementById("date").setAttribute("value", today);
    document.getElementById("time").setAttribute("min", hour);

    let steps = $('.multi-steps').children();
    
    $('.label').click(function(event){
        event.preventDefault();

        $('.label').each(function(){
            if($(this).hasClass('selected'))
                $(this).removeClass('selected');
        })

        if ($(this).hasClass('selected')){
            $(this).removeClass('selected');
        }else{              
            $(this).addClass('selected');
        }
    });

    $('#previous').click(function(e){
        e.preventDefault();

            switch(step)
            {
                case(2):
                    $("[data-step="+step+"]").addClass('hidden_form');
                    step--;
                    $("[data-step="+step+"]").removeClass('hidden_form');

                    $('.multi-steps').children().each(function(){
                        if($(this).hasClass('is-active')){
                            $(this).removeClass('is-active');
                        }
                    });

                    $(steps[step - 1]).addClass('is-active');
                    $('.input__container button').eq(0).css('display','none');
                    $('.input__container').eq(6).css('justify-content', 'flex-end');
                    break;
                case(3):
                    $("[data-step="+step+"]").addClass('hidden_form');
                    step--;
                    $("[data-step="+step+"]").removeClass('hidden_form');
                    $('.multi-steps').children().each(function(){
                        if($(this).hasClass('is-active')){
                            $(this).removeClass('is-active');
                        }
                    });

                    $(steps[step - 1]).addClass('is-active');
                    $('.input__container button').eq(0).css('display','block');
                    $('.input__container').eq(6).css('justify-content', 'space-between');
                    break;
            }
    });

    $("#next").click(function(e){

        e.preventDefault();
        switch(step)
        {
            case(1):
                if($("#walk_name").val() == null){
                    $("#walk_name").addClass('-error');
                    return;
                }

                $('.label').each(function(){
                    if($(this).hasClass('selected')){
                        walk_type = $(this).children().eq(0).children().eq(2).text();
                    }
                });

                $("[data-step="+step+"]").addClass('hidden_form');
                step++;
                $("[data-step="+step+"]").removeClass('hidden_form');

                $('.multi-steps').children().each(function(){
                    if($(this).hasClass('is-active')){
                        $(this).removeClass('is-active');
                    }
                });

                $(steps[step - 1]).addClass('is-active');
                $('.input__container button').eq(0).css('display','block');
                $('.input__container').eq(6).css('justify-content', 'space-between');
                break;
            case(2):
                if( $("#info").val() == ""){
                    $("#info").addClass('-error');
                    return;
                }
                if( $("#date").val() == null){
                    $("#date").addClass('-error');
                    retun;
                }
                $("[data-step="+step+"]").addClass('hidden_form');
                step++;
                $("[data-step="+step+"]").removeClass('hidden_form');

                $('.multi-steps').children().each(function(){
                    if($(this).hasClass('is-active')){
                        $(this).removeClass('is-active');
                    }
                });

                $(steps[step - 1]).addClass('is-active');
                $('.input__container button').eq(0).css('display','block');
                $('.input__container').eq(6).css('justify-content', 'space-between');
                break;
            case(3):
                if($("#time").val() ==""){
                    $("#time").addClass('-error');
                    return;
                }
                if( $("#length").val() == null){
                    $("#length").addClass('-error');
                    return;
                }
                $("[data-step="+step+"]").addClass('hidden_form');
                step++;
                $("[data-step="+step+"]").removeClass('hidden_form');
                $('.multi-steps').children().each(function(){
                    if($(this).hasClass('is-active')){
                        $(this).removeClass('is-active');
                    }
                });

                $(steps[step - 1]).addClass('is-active');
                $('.input__container button').eq(0).css('display','block');
                $('.input__container').eq(6).css('justify-content', 'space-between');
                break;
        }

        if (step >= 4){
            walk_id_owner = $("form").data('id');
            walk_name = $("#walk_name").val();
            walk_location = $("#info").val();
            walk_length = $("#length").val();
            walk_date = $("#date").val() + " " + $("#time").val();

            $('.multi-steps').children().each(function(){
                if($(this).hasClass('is-active')){
                    $(this).removeClass('is-active');
                }
            });

            $(steps[step - 1]).addClass('is-active');

            $('#event_information').css('display','none');
            $('.walk__dog').css('display','block');

            if($('.walk__dog__container').children().length <= 0){
           
                $('.walk__dog').append('<div class="information error -center">Vous devez avoir au moins un compagnon pour créer une balade</div>');
                $("#validate").prop('disabled', true);
            }

            $('.dog_card').click(function(){
                if(!$(this).hasClass('-active')){
                    $(this).addClass('-active');
                }else{
                    $(this).removeClass('-active');
                }

                var selected = false;

                for(i = 0; i < $('.dog_card').length && !selected; i++){
                    if ($('.dog_card').eq(i).hasClass('-active')){
                        selected = true;
                    }
                }
              
                if(!selected)
                    $("#validate__sign").prop('disabled', true);
                else
                    $("#validate__sign").prop('disabled', false);
            });
        }
    });

    $('#validate__sign').click(function(e){

        let dogSelected = [];
        let id_event = $('#validate__sign').data('id');
        let x = 0;

        $('.dog_card').each(function(){
            if($(this).hasClass('-active')){
                dogSelected.push($(this).data('id'));
            }
        });

        if(dogSelected.length > 0){
            $.ajax({
                method: "GET",
                data:{
                   DOG : dogSelected,
                   ID : id_event
                },
                url:"src/php/joinwalk.php",
            })
            .done(function(result){
                if (result == "success")
                    document.location.reload(true);
            });
        }
    });

    $("#validate").click(function(e){
        e.preventDefault();

        let dogSelected = [];
        let x = 0;

        $('.dog_card').each(function(){
            if($(this).hasClass('-active')){
                dogSelected.push($(this).data('id'));
            }
        });

        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q='+walk_location+'&addressdetails=1', function(data){

            lat = data[0].lat;
            lon = data[0].lon;

            if (data[0].address.city != undefined){
                city = data[0].address.city;
            }
            if (data[0].address.village != undefined){
                city = data[0].address.village;
            }
            if (data[0].address.town != undefined){
                city = data[0].address.town;
            }

            road = data[0].address.road;
            postcode = data[0].address.postcode;

            $.get(
                'src/php/add_event.php',
                {
                    ID_OWNER : walk_id_owner,
                    NAME : walk_name,
                    LOCATION : walk_location,
                    TYPE : walk_type,
                    DATE : walk_date,
                    LENGTH : walk_length,
                    DOG : dogSelected,
                    LAT : lat,
                    LON : lon,
                    CITY : city,
                    ROAD : road,
                    POSTCODE : postcode
                },

                function(data){
                   
                    if(data != 'failed'){
                    	container.html(data);
                    	// Afficher ici message "Votre balade a bien été créée"
                    }
                },
                'text'
            );
        });
    });

    $("#info").on('keyup', function(){
       
        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q='+$(this).val(),      function(data){

            for (i = 2; i < $("#info").parent().children().length; i++){
                $("#info").parent().children().eq(i).remove();
            }

            if(data.length) {

                if($("#info").hasClass('-error'))
                    $("#info").removeClass('-error');

                for ( i = 0; i < data.length; i++){
                    $("#info").parent().append("<div class='pickadress__container'><div class='pickaddress' style='width: 90%;margin-left: 24px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;padding: 8px;border: 1px solid #8080804a;background:white;'>"+data[i].display_name+"</div></div>")
                }

                $(".pickaddress").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();

                    var ville = $(this).text();


                    $('.pickadress__container').remove();
                    $("#info").val(ville);
                });
            }
            else {
                $("#info").addClass('-error');
            }
        });
    });

});