$(document).ready(function() {
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

    $("#next").click(function(e){

        error = [];
        e.preventDefault();
        if($("#walk_name").val() == null){
            error.push("true");
            $("#walk_name").addClass('-error');
        }
        // if( $("#town").val() == null){
        //     error.push("true");
        //     $("#town").addClass('-error');
        // }
        if( $("#info").val() == ""){
            error.push("true");
            $("#info").addClass('-error');
        }
        if( $("#walk_type").val() == null){
            error.push("true");
            $("#walk_type").addClass('-error');
        }
        if( $("#date").val() == null){
            error.push("true");
            $("#date").addClass('-error');
        }
        if($("#time").val() ==""){
            error.push("true");
            $("#time").addClass('-error');
        }
        if( $("#length").val() == null){
            error.push("true");
            $("#length").addClass('-error');
        }

        if (error.length == 0){

            $('#event_information').css('display','none');
            $('.walk__dog').css('display','block');

            if($('.walk__dog__container').length <= 0){
                $('.walk__dog').append('<div class="information error">Vous devez avoir au moins un compagnon pour créer une balade</div>');
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
                //console.log(selected);
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

        var date = $("#date").val() + " " + $("#time").val();

        let dogSelected = [];
        let x = 0;

        $('.dog_card').each(function(){
            if($(this).hasClass('-active')){
                dogSelected.push($(this).data('id'));
            }
        });

        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q='+$('#info').val()+'&addressdetails=1', function(data){

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
                    ID_OWNER : $("form").data('id'),
                    NAME : $("#walk_name").val(),
                    LOCATION : $("#info").val(),
                    TYPE : $("#walk_type").val(),
                    DATE : date,
                    LENGTH : $("#length").val(),
                    DOG : dogSelected,
                    LAT : lat,
                    LON : lon,
                    CITY : city,
                    ROAD : road,
                    POSTCODE : postcode
                },

                function(data){
                    console.log(data);
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
        console.log('//nominatim.openstreetmap.org/search?format=json&q='+$(this).val());
        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q='+$(this).val(),      function(data){

            for (i = 2; i < $("#info").parent().children().length; i++){
                $("#info").parent().children().eq(i).remove();
            }

            if(data.length) {
                console.log(data);

                if($("#info").hasClass('-error'))
                    $("#info").removeClass('-error');

                for ( i = 0; i < data.length; i++){
                    $("#info").parent().append("<div class='pickadress__container'><div class='pickaddress' style='width: 90%;margin-left: 24px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;padding: 8px;border: 1px solid #8080804a;background:white;'>"+data[i].display_name+"</div></div>")
                }

                $(".pickaddress").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    //console.log(e);

                    console.log($(this).text());
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