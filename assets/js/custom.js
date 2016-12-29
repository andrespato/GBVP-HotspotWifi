$( document ).ready(function() {
  // get ip and server info
    $.ajax({
    type: 'GET',
    url: 'getip.php',
      success: function(data) {
          console.log(data);
          console.log(GetURLParameter('local'));
      },
      error: function (request, status, error) {
          alert("Error: "+request.responseText);
      }
    });

  // fbinit
  window.fbAsyncInit = function() {
      FB.init({
        appId      : '1868493876770798',
        cookie     : true,
        xfbml      : true,
        version    : 'v2.8',
        secret     :'acf9bd0c602d41c59c342fd701398dc2'
      });
      fbcheckState();
  };
  // load sdk asynchronously
  (function(d, s, id){
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {return;}
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/pt_PT/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
});

// login with facebook with extra permissions
function fblogin() {
    FB.login(function(response) {
        if (response.status === 'connected') {
            //alert(JSON.stringify(response,null,4));
            console.log('Connected');


         } else if (response.status === 'not_authorized') {
               console.log('We are not logged in');
    } else {
      console.log('You are not logged into Facebook.');
}
    }, {scope: 'public_profile,email'});//,user_hometown,user_birthday'});
}


//logout
function fblogout(){
        FB.logout(function(response) {
         // user is now logged out
            location.reload();
});
}

function fbcheckState() {
  FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            console.log('STATE: We are not logged in');

        } else if (response.status === 'not_authorized') {
        console.log('STATE: We are not logged in');
        } else {
            console.log('STATE: You are not logged into Facebook.');
        }
    });
}

// getting basic user info
function fbGetInfo(arg) {
      FB.api('/me', 'GET', {fields: 'first_name,last_name,name,id,email,locale,gender,hometown,birthday'}, function(response) {
          console.log('Successful login for: ' + response.name);
          console.log(JSON.stringify(response,null,4));

          if(arg===1){
              console.log(JSON.stringify(response,null,4));
          } else if(arg === 2){
              //alert("getinfo-> "+response.name);
              console.log('GetInfo:chamar info');
              info(response.id);
              setTimeout(function(){
                  document.getElementById('main-title').innerHTML = "Bem-vindo/a "+response.name+" !";
                  document.getElementById('login-options').innerHTML = "Tem agora acesso gratuito ao Wifi.</br> Aproveite a visita ! </br> <button onclick='fblogout();'>Logout</button>";
              }, 2000);
          }
      });
}

function info(usr_id){
    var usr = "/"+usr_id;
    document.getElementById('login-options').innerHTML = "<img src='images/loading.gif' alt='loading' height='60' width='80'>";
    FB.api(
        usr,
        'GET',
        {fields: 'first_name,last_name,name,id,email,locale,gender,hometown,birthday'},
        function (response) {
            if (response && !response.error) {
            /* handle the result */
                $.ajax({
                type: 'POST',
                data: {response},
                url: 'dbWriter.php?type=fb&centroEnot='+GetURLParameter('local'),
                success: function(data) {
                    console.log("Sucesso: "+ data);

                },
                error: function (request, status, error) {
                    alert("Error: "+request.responseText);
                }
            });

            }
        }
    );
}

// get URL parameters
function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam){
            return sParameterName[1];
        }
    }
}

function emailConn(){
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    var email = document.getElementById("email-connect").value;


        if (reg.test(email) == false)
        {
            alert('Email inválido');
            return 0;
        }
        else {

            document.getElementById('main-title').innerHTML = 'Bem Vindo/a ! ';

            $.ajax({
                    type: 'POST',
                    data: { email : email},
                    url: 'dbWriter.php?type=email&centroEnot='+GetURLParameter('local'),
                    success: function(data) {
                        document.getElementById('login-options').innerHTML = '<strong>Passo 2 de 3</strong></br><b>Está a um passo de usar o Hotspot Wifi ! </b>Inserir número de telemóvel para receber o código de confirmação</b></br></br>Telemóvel <input type="tel" id="telemField" style="width: 100%;"/></br><input type="button" value="Enviar Código" onclick="sendConfCode('+data+');"/>';

                        //alert("Sucesso: "+ data);
                    },
                    error: function(data){
                        alert('Falha ajax -> '+JSON.stringify(data));
                    }
                });
        }
}

function sendConfCode(visitanteID){
    var numTelem = document.getElementById('telemField').value;
    var postData = visitanteID+"-"+numTelem;

    //alert(postData);

    if( !document.getElementById('telemField').value ) {
        $('#telemField').css('border', '2px solid red');
    }
    else{
        document.getElementById('login-options').innerHTML = "<img src='images/loading.gif' alt='loading' height='60' width='80'>";
        // GERAR CODIGO E ENVIAR AO UTILIZADOR
         $.ajax({
                type: 'POST',
                data: {postdata : postData},
                url: 'phone-conf.php',
                success: function(data) {
                    setTimeout(function(){
                        if(data != 'E-Mail foi enviado'){
                            document.getElementById('login-options').innerHTML = data;
                        }
                        else {
                            document.getElementById('login-options').innerHTML = '<strong>Passo 3 de 3</strong></br>Inserir código de confirmação enviado para '+numTelem+'</b></br></br>Código <input type="text" id="confCode" style="width: 100%;" /></br><a href="#" class="button small icon fa-sign-in" onclick="checkConfCode('+visitanteID+');">Entrar</a><div id="confStat"></div>';
                        }
                    }, 2000);
                },
                error: function(data){
                    alert('Falha ajax -> '+JSON.stringify(data));
                }
            });
    }

}

function checkConfCode(visitanteID){
    document.getElementById('confStat').innerHTML = "<img src='images/loading.gif' alt='loading' height='20' width='40'>";

    if( !document.getElementById('confCode').value ) {
        $('#confCode').css('border', '2px solid red');
        $('#confCode').css('color', 'red');
        document.getElementById('confStat').innerHTML = "Código Inválido";
    }
    else{
        var postData = visitanteID+"-"+document.getElementById('confCode').value;
        $.ajax({
                    type: 'POST',
                    data: {postdata : postData},
                    url: 'code-conf.php',
                    success: function(data) {
                        setTimeout(function(){
                            // TRY < 3 - PROXIMA TENTATIVA
                            if(data < 3){
                                $('#confCode').css('color', 'red');
                                document.getElementById('confStat').innerHTML = "Código Inválido - Tem mais "+(3-data)+" tentativa/s";
                            }
                            // 500 - SUCESSO
                            else if(data == 500){
                                 $('#login-options').css('color', 'green');
                                document.getElementById('login-options').innerHTML = "Código Validado - Vai ser redirecionado dentro de momentos";
                            }
                            // 400 - INATIVO
                            else{
                                $('#confCode').css('color', 'red');
                                document.getElementById('login-options').innerHTML = "Esgotou o número de tentativas ! Volte a aceder para tentar de novo";
                            }
                        }, 2000);
                    },
                    error: function(data){
                        alert('Falha ajax -> '+JSON.stringify(data));
                        document.getElementById('login-options').innerHTML = data;
                    }
                });
    }
}

// facebook-opt click function
$( "#facebook-opt" ).click(function() {
      FB.login(function(response) {
          if (response.status === 'connected') {
              //alert(JSON.stringify(response,null,4));
              console.log('LOGIN:Connected');
              console.log('LOGIN:chamar getInfo');
              fbGetInfo(2);
           } else if (response.status === 'not_authorized') {
                 console.log('LOGIN:We are not logged in');
           } else {
                 console.log('LOGIN:You are not logged into Facebook.');
           }
       }, {scope: 'public_profile,email'});//,user_hometown,user_birthday'});
});

// facebook-opt click function
$( "#email-opt" ).click(function() {
  document.getElementById('login-options').innerHTML ="<strong>Passo 1 de 3</strong></br>Email </br><input type='email' id='email-connect' name='email' style='width: 100%;'/><a href='#' class='button small icon fa-sign-in' id='emailSubmit' onclick='emailConn();'>Entrar</a>";
});
