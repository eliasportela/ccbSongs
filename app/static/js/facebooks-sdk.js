window.fbAsyncInit = function() {
  FB.init({
    appId      : '255504468465604',
    cookie     : true,
    xfbml      : true,
    version    : 'v2.7'
  });

  FB.AppEvents.logPageView();

};

(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/pt_BR/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
