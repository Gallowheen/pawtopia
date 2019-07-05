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

            if($('body').is('.new_walk') || $('body').is('.walk_detail')){
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
                })
            }

            if($('body').is('.walk_detail')){
                //console.log('lol');
                $("#validate__sign").prop('disabled', true);
                $('.submit__button').remove();
                $(window).scrollTop($('.walk__add__dog').offset().top);
            }
        }
    });
});