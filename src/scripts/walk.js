$(document).ready(function(){
        var map;
        var mymap;
        var layerGroup;

        navigator.geolocation.getCurrentPosition(function(location) {
            var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
            map = L.map('map').setView(latlng, 10);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox.streets',
                accessToken: 'pk.eyJ1IjoiZ2FsbG93IiwiYSI6ImNqeGtqNm5sZjA0b2k0MG5vZjVqbzZuMHgifQ.eUzgUh43YajD2CCcs3Eveg'
            }).addTo(map);

            //layerGroup = L.layerGroup().addTo(map);

            mymap = L.map('mapid').setView(latlng, 10);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox.streets',
                accessToken: 'pk.eyJ1IjoiZ2FsbG93IiwiYSI6ImNqeGtqNm5sZjA0b2k0MG5vZjVqbzZuMHgifQ.eUzgUh43YajD2CCcs3Eveg'
            }).addTo(mymap);

            layerGroup = L.layerGroup().addTo(mymap);
        });


        //if geoloc failed
        setTimeout(function(){
            if (map == undefined || mymap == undefined){
                var longitude;
                var latitude;

                $.ajax({
                    method: "GET",
                    url:"src/php/get_lat_long_city.php",
                })
                .done(function(result){

                    data = JSON.parse(result);

                    latitude  = data.LAT;
                    longitude  = data.LON;

                    var latlng = new L.LatLng(latitude, longitude);
                    map = L.map('map').setView(latlng, 10);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1IjoiZ2FsbG93IiwiYSI6ImNqeGtqNm5sZjA0b2k0MG5vZjVqbzZuMHgifQ.eUzgUh43YajD2CCcs3Eveg'
                    }).addTo(map);

                    //layerGroup = L.layerGroup().addTo(map);
                    //create mymap

                    var latlng = new L.LatLng(latitude, longitude);
                    mymap = L.map('mapid').setView(latlng, 10);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1IjoiZ2FsbG93IiwiYSI6ImNqeGtqNm5sZjA0b2k0MG5vZjVqbzZuMHgifQ.eUzgUh43YajD2CCcs3Eveg'
                    }).addTo(mymap);

                    layerGroup = L.layerGroup().addTo(mymap);
                });
            }
        },500);

        //icon map
        var bluetopia = L.icon({
            iconUrl: 'src/assets/img/ressources/bluetopia.png',
            shadowUrl: 'src/assets/img/ressources/shadow.png',

            iconSize:     [30, 50], // size of the icon
            shadowSize:   [30, 25], // size of the shadow
            iconAnchor:   [15, 50], // point of the icon which will correspond to marker's location
            shadowAnchor: [5, 27],  // the same for the shadow
            popupAnchor:  [0, -48] // point from which the popup should open relative to the iconAnchor
        });

        var redtopia = L.icon({
            iconUrl: 'src/assets/img/ressources/redtopia.png',
            shadowUrl: 'src/assets/img/ressources/shadow.png',

            iconSize:     [30, 50], // size of the icon
            shadowSize:   [30, 25], // size of the shadow
            iconAnchor:   [15, 50], // point of the icon which will correspond to marker's location
            shadowAnchor: [5, 27],  // the same for the shadow
            popupAnchor:  [0, -48] // point from which the popup should open relative to the iconAnchor
        });

        var greentopia = L.icon({
            iconUrl: 'src/assets/img/ressources/greentopia.png',
            shadowUrl: 'src/assets/img/ressources/shadow.png',

            iconSize:     [30, 50], // size of the icon
            shadowSize:   [30, 25], // size of the shadow
            iconAnchor:   [15, 50], // point of the icon which will correspond to marker's location
            shadowAnchor: [5, 27],  // the same for the shadow
            popupAnchor:  [0, -48] // point from which the popup should open relative to the iconAnchor
        });

        //Si la géoloc ne fonctionne pas ou n'est pas active
        if (!latUser || !lonUser){
            $.ajax({
                method: "GET",
                url:"src/php/get_lat_long_city.php",
            })
            .done(function(result){

                data = JSON.parse(result);

                latUser  = data.LAT;
                lonUser  = data.LON;
            });
        }

        $('#filter').click(function() {
            $("html, body").animate({ scrollTop: 0 }, "slow");
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
            $(".walk__handler").find("input:radio").prop("checked", false);
            $(".label").each(function(){
                if($(this).hasClass('selected'))
                    $(this).removeClass('selected')
            });

            $("body").css('overflow','hidden');
            $(".walk__handler__container").css('display','block');

            setTimeout(function(){
                $(".walk__handler__container").css('transform','translateY(0%)');
            }, 500);

            setTimeout(function(){
                $(".walk__handler__container").css('background','#00000024');
            }, 1500);
        });

        setTimeout(function(){

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today =  yyyy + '-' + mm + '-' + dd;

            $.ajax({
                method: "GET",
                data:{
                    date : today,
                    LAT : latUser,
                    LON : lonUser
                },
                url:"src/php/managewalk.php",
            })
            .done(function(result){

                console.log(result);
                data = JSON.parse(result);
                //console.log(result);

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

                if($('.walk__container__result'))
                    $('.walk__container__result').remove();

                if($('.tag_container'))
                    $('.tag_container').remove();

                if($('.walk__noresult'))
                    $('.walk__noresult').remove();

                if (data.length >= 1){

                    for( let i = 0; i < data.length; i++){
                        var icon;

                        if (data[i]['WALK'] == "Récréative")
                            icon = bluetopia;
                        else if (data[i]['WALK'] == "Sportive")
                            icon = redtopia;
                        else if (data[i]['WALK'] == "Découverte")
                            icon = greentopia;

                        var marker = L.marker([data[i]['LAT'], data[i]['LON']],{icon: icon}).addTo(map).on('click', function(){
                            setTimeout(function(){
                                $('.get_to_walk').click(function(){
                                    // window.location = "walk_detail?ID="+data[i]['ID'];

                                    // var title = $(".header__title").html();
                                    // var newtitle = $(this).parent().parent().find('.name__container span').html();
                                    // $.ajax({
                                    //     method: "GET",
                                    //     url:"walk_detail.php",
                                    //     data:{ID:data[i]['ID']}
                                    // })
                                    // .done(function(result){
                                    //     container.html(result);
                                    //     $('.h1').text(newtitle);
                                    //     setReturnButton("walk", {}, title);
                                    // });
                                });
                            },100);
                        });
                        marker.bindPopup("<b>"+data[i]['NAME']+"</b></br><b>"+data[i]['ROAD']+" "+data[i]['CITY']+"</b></br><button class='button -color -blue -round -top -walk get_to_walk' data-id='10'>En savoir plus</button>");
                    }
                }
            });
        },500);

        $('#new_walk').click(function(e){
            $.ajax({
                method: "GET",
                url:"new_walk.php",
            })
            .done(function(result){
                container.html(result);
            });
        });

    $('#submit__walks').click(function(e){

        e.preventDefault();

        //Si la géoloc ne fonctionne pas ou n'est pas activ
        if (latUser == undefined || lonUser == undefined){
            $.ajax({
                method: "GET",
                url:"src/php/get_lat_long_city.php",
            })
            .done(function(result){

                data = JSON.parse(result);

                latUser  = data.LAT;
                lonUser  = data.LON;
            });
        }

        setTimeout(function(){

            $.ajax({
                method: "GET",
                data:{
                    walk:$('form input[type=radio]:checked').val(),
                    //km : $('#slider').val(),
                    date : $('#date').val(),
                    LAT : latUser,
                    LON : lonUser
                },
                url:"src/php/managewalk.php",
            })
            .done(function(result){

                console.log(result);
                data = JSON.parse(result);
                //console.log(result);

                //map.removeLayer();

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

                if($('.walk__container__result'))
                    $('.walk__container__result').remove();

                if($('.tag_container'))
                    $('.tag_container').remove();

                if($('.walk__noresult'))
                    $('.walk__noresult').remove();

                if (data.length >= 1){

                    setTimeout(() => {
                        mymap.invalidateSize();
                    }, 0);
                    layerGroup.clearLayers();

                    $('.content_container .container .row .col').append('<div class="walk__container__result"></div>');

                    $('.walk__container__result').append('<p class="search__result"><b>'+data.length+'</b> résultats correspondant à votre recherche</p>');

                    for( let i = 0; i < data.length; i++){

                        let adress = data[i]['LOCATION'];

                        setTimeout(function(){
                            if (data[i]['WALK'] == "Récréative"){
                                var marker = L.marker([data[i]['LAT'], data[i]['LON']],{icon: bluetopia}).addTo(mymap).on('click', function(){

                                    setTimeout(function(){
                                        $('.get_to_walk').click(function(){
                                            // window.location = "walk_detail?ID="+data[i]['ID'];

                                            // var title = $(".header__title").html();
                                            // var newtitle = $(this).parent().parent().find('.name__container span').html();
                                            // $.ajax({
                                            //     method: "GET",
                                            //     url:"walk_detail.php",
                                            //     data:{ID:data[i]['ID']}
                                            // })
                                            // .done(function(result){
                                            //     container.html(result);
                                            //     $('.h1').text(newtitle);
                                            //     setReturnButton("walk", {}, title);
                                            // });
                                        });
                                    },100);
                                });
                                marker.bindPopup("<b>"+data[i]['NAME']+"</b></br><b>"+data[i]['ROAD']+" "+data[i]['CITY']+"</b></br><button class='button -color -blue -round -top -walk get_to_walk' data-id='10'>En savoir plus</button>");
                                marker.addTo(layerGroup);
                            }
                            if (data[i]['WALK'] == "Sportive"){
                                var marker = L.marker([data[i]['LAT'], data[i]['LON']],{icon: redtopia}).addTo(mymap).on('click', function(){

                                    setTimeout(function(){
                                        $('.get_to_walk').click(function(){
                                            //window.location = "walk_detail?ID="+data[i]['ID'];
                                            var title = $(".header__title").html();

                                            // var newtitle = $(this).parent().parent().find('.name__container span').html();
                                            // $.ajax({
                                            //     method: "GET",
                                            //     url:"walk_detail.php",
                                            //     data:{ID:data[i]['ID']}
                                            // })
                                            // .done(function(result){
                                            //     container.html(result);
                                            //     $('.h1').text(newtitle);
                                            //     setReturnButton("walk", {}, title);
                                            // });
                                        });
                                    },100);
                                });
                                marker.bindPopup("<b>"+data[i]['NAME']+"</b></br><b>"+data[i]['ROAD']+" "+data[i]['CITY']+"</b></br><button class='button -color -blue -round -top -walk get_to_walk' data-id='10'>En savoir plus</button>");
                                marker.addTo(layerGroup);

                            }
                            if (data[i]['WALK'] == "Découverte"){
                                var marker = L.marker([data[i]['LAT'], data[i]['LON']],{icon: greentopia}).addTo(mymap).on('click', function(){

                                    setTimeout(function(){
                                        $('.get_to_walk').click(function(){
                                            //window.location = "walk_detail?ID="+data[i]['ID'];
                                            var title = $(".header__title").html();

                                            // var newtitle = $(this).parent().parent().find('.name__container span').html();
                                            // $.ajax({
                                            //     method: "GET",
                                            //     url:"walk_detail.php",
                                            //     data:{ID:data[i]['ID']}
                                            // })
                                            // .done(function(result){
                                            //     container.html(result);
                                            //     $('.h1').text(newtitle);
                                            //     setReturnButton("walk", {}, title);
                                            // });
                                        });
                                    },100);
                                });
                                marker.bindPopup("<b>"+data[i]['NAME']+"</b></br><b>"+data[i]['ROAD']+" "+data[i]['CITY']+"</b></br><button class='button -color -blue -round -top -walk get_to_walk' data-id='10'>En savoir plus</button>");
                                marker.addTo(layerGroup);
                            }
                        },250);

                        let date = new Date(data[i]['DATE_START']);
                        let hourSplit = data[i]['DATE_START'].split(' ')[1].split(':');
                        let hour = hourSplit[0] + ":" + hourSplit[1];
                        let day = jour[date.getDay()];
                        let month = mois[date.getMonth()];
                        let dayNumber = date.getDate();

                        let walk = '<div class="name__container"><span class="">'+data[i]['NAME']+'</span></div><div class="date__container -flex"><span class="">'+ day +" "+dayNumber +" "+ month+" "+ hour+'</span>'+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['ROAD']+" "+data[i]['CITY']+' <small class="friend_name -km -walk">('+data[i]['km']+' km de vous)</small></span></div><div class="button__container"><button class="button -color -blue -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div>';
                        $('.walk__container__result').append('<div class="walk__card test">'+walk+'</div>');
                    }
                }else{
                    $('.content_container .container .row .col').append('<div class="walk__noresult">Aucun resultat pour la recherche</div>');
                }

                $(".walk__handler__container").css('background','transparent');

                setTimeout(function(){
                    $(".walk__handler__container").css('transform','translateY(100%)');
                }, 750);

                setTimeout(function(){
                    $(".walk__handler__container").css('display','none');
                    $("body").css('overflow','auto');

                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#mapid").offset().top - 150
                    }, 500);
                }, 1250);

                //$(".map__container").show();

                $('.map__container').eq(0).hide();
                $(".map__container").eq(1).show();
                $('.get_to_walk').click(function(){
                    var title = $(".header__title").html();
                    var newtitle = $(this).parent().parent().find('.name__container span').html();
                    $.ajax({
                        method: "GET",
                        url:"walk_detail.php",
                        data:{ID:$(this).data('id')}
                    })
                    .done(function(result){
                        container.html(result);
                        $('.h1').text(newtitle);
                        setReturnButton("walk", {}, title);
                    });
                    //window.location = "walk_detail?ID="+$(this).data('id');
                });
            });

        },500);
    });

    $('.map__container').eq(0).show();
});