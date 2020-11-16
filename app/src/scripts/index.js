var http = require('http');
http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('Hello World\n');
}).listen(1337, "127.0.0.1");
console.log('Server running at http://127.0.0.1:1337/');

var myVar
var tweetnumber =  1;

function createTimer(){
    myVar = setInterval(myTimer, 56000);
};

createTimer();

function myTimer() {
    var d = new Date();
    var n = d.getHours();
    var m = d.getMinutes();
    //console.log(n);
    //console.log(m);

    var hour = [8,12,16,20];

    //console.log(n + m);

    for ( i = 0; i < hour.length; i++){
        if ( n == hour[i] && m == 0){
  
            setTimeout(function(){
                
                myStopFunction();
                createLink();
            },1000);  // this will make it click again every 1000 miliseconds
        }
    }
}

function createLink(){
    //lien.setAttribute('href', 'https://twitter.com/intent/tweet?text=Mon%20score%20est%20de '+x+'&hashtags=CasseOs,sncb,toolate');
    // $('#share').attr('href', "https://twitter.com/intent/tweet?text=Je viens de finir une partie sur CasseOs ! J'ai vaincu cassos et réalisé un score de !"+'&hashtags=CasseOs,sncb,jpp,punchThemAll');
    // $('#share').trigger('click');

    //window.open("https://twitter.com/intent/tweet?text=Je viens de finir une partie sur CasseOs ! J'ai vaincu cassos et réalisé un score de !"+'&hashtags=CasseOs,sncb,jpp,punchThemAll',"_self"); 
    var OAuth = require('oauth');

    var twitter_application_consumer_key = 'RDFHOdjthcXqGijX4fb9IhnKO';  // API Key
    var twitter_application_secret = 'ikA9tHc5CEKb2rOPjafp78K58bAYMbE8EMHXgxeOW05YpaVxPy';  // API Secret
    var twitter_user_access_token = '1151172005691056129-HZgy5Wc99YiSRFqivVQZUEqmWShVcY';  // Access Token
    var twitter_user_secret = 'bb6wak9nkeeOebmTqapE4Ucs3twR9fXzxt5IyilhUGm2s';  // Access Token Secret

    var oauth = new OAuth.OAuth(
        'https://api.twitter.com/oauth/request_token',
        'https://api.twitter.com/oauth/access_token',
        twitter_application_consumer_key,
        twitter_application_secret,
        '1.0A',
        null,
        'HMAC-SHA1'
    );

    var status = "Hey @Dorlesley_ ! N'oublies pas de regarder ta glycémie ! <3 #HypoBot #"+tweetnumber+"";  // This is the tweet (ie status)

    var postBody = {
        'status': status
    };

    // console.log('Ready to Tweet article:\n\t', postBody.status);
    oauth.post('https://api.twitter.com/1.1/statuses/update.json',
        twitter_user_access_token,  // oauth_token (user access token)
        twitter_user_secret,  // oauth_secret (user secret)
        postBody,  // post body
        '',  // post content type ?
        function(err, data, res) {
            if (err) {
                console.log(err);
            } else {
                console.log(data);
            }
        });

    setTimeout(function(){
        tweetnumber += 1;
        console.log('on relance');
        createTimer();
    },66000);
}


function myStopFunction() {
    clearInterval(myVar);
}