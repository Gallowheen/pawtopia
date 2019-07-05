$(document).ready(function() {
    $.ajax({
        method: "GET",
        url:"src/php/get_user_walk.php",
    })
    .done(function(result){

        console.log(result);

        if(result){
            data = JSON.parse(result);

            //savoir le jour
            var jour = new Array(7);
            jour[0] = "Lundi";
            jour[1] = "Mardi";
            jour[2] = "Mercredi";
            jour[3] = "Jeudi";
            jour[4] = "Vendredi";
            jour[5] = "Samedi";
            jour[6] = "Dimanche";

            //savoir le mois
            var mois = new Array(12);
            mois[0] = "Janvier";
            mois[1] = "Février";
            mois[2] = "Mars";
            mois[3] = "Avril";
            mois[4] = "Mai";
            mois[5] = "Juin";
            mois[6] = "Juillet";
            mois[7] = "Août";
            mois[8] = "Septembre";
            mois[9] = "Octobre";
            mois[10] = "Novembre";
            mois[11] = "Décembre";

            if (data.length > 0){

                $('.content_container .container .row .col .user_walk').append('<div class="walk__container"></div>');

                for( let i = 0; i < data.length; i++){
                    let date = new Date(data[i]['DATE_START']);
                    let hourSplit = data[i]['DATE_START'].split(' ')[1].split(':');
                    let hour = hourSplit[0] + ":" + hourSplit[1];
                    let day = jour[date.getDay()];
                    let monthNumber = date.getMonth()+1;
                    let month = mois[date.getMonth()];
                    let dayNumber = date.getDate();
                    let year = date.getFullYear();

                    let walk = '<div class="name__container -home"><span>'+data[i]['NAME']+'</span></div><div class="address__container -home">'+data[i]['ROAD']+' '+data[i]['CITY']+'</div><div class="date__container -home"><span class="">'+ day +" "+dayNumber +" "+ month+" "+ hour+'</span></div><div class="button__container -preview"><i class="icon icon__up -right get_to_walk" data-id='+data[i]['ID']+'></i><span class="learn__more">En savoir plus</span></div></div>';
                    // A rajouter à la ligne du dessus pour repasser à l'ancienne version
                    //+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['LOCATION']+'</span></div><div class="align-right"><div class="button__container"><button class="button -color -blue -round -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div></div>'
                    $('.walk__container').append('<div class="walk__card -home test">'+walk+'</div>');
                }

                $('.get_to_walk').click(function(){
                    window.location = "walk_detail?ID="+$(this).data('id');
                });

                if (data.length > 1 && $('body').is('.walk') || $('body').is('.home')){
                    $('.content_container .container .row .col .user_walk').css({
                        "max-height": "120px",
                        "overflow" : "scroll"
                    })
                }


            }
        }else{
            if($('body').is('.home'))
                $('.content_container .container .row .col .user_walk').html("<p class='information'>Vous n'êtes inscrit à aucune balades pour le moment !</p><div class='center'><a href='walk.php'><button class='button -color'>Découvrez les balades</button></a></div>");
            else
                $('.content_container .container .row .col .user_walk').html("<img class='map__img' src='src/assets/img/ressources/no_walk.png'/>");
        }
    });
})