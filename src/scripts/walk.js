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
                                    window.location = "walk_detail?ID="+data[i]['ID'];
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

        $('.map__container').eq(0).show();
});