<html ng-app="todoApp">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login para usuarios</title>
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.js"></script>
        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
                
        <style>

            html, body {
                width: 100%;
                height: 100%;
                color: #444;
                -webkit-font-smoothing: antialiased;
                background: #f0f0f0;
            }
            
            
            form {
                min-width: 400px;
                max-width: 60%;
                margin: 0px auto;
                background: rgba(255, 255, 255, 0.15);
                padding: 2rem 3rem;
                margin: 80%;
                border-radius: 2.5rem;
                background-color: #ecf0f3;
                box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #ffffff;
                color: black;
                margin-top: 10rem;
            }
        </style>        

    </head>

    <body >
        
        <div class="row" ng-controller="BeerCounter">
            
            <div class="col s12 m6" >
                
                <!-- Modal Structure -->
                <div id="modal1" class="modal bottom-sheet">
                    <div class="modal-content">
                        <h4>Error de ingreso</h4>
                        <p>{{message}}</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Aveptar</a>
                    </div>
                </div>

                <form name=form action="TU_PAGINA_WEB.HTML" >

                    <P>Usuario: <input type=text  ng-model="email"> 
                    <P>Contraseña: <input type=password  ng-model="password" > 
                    <a ng-click=sendLogin() class="waves-effect waves-light btn">Ingresar</a>
                </form> 

            </div>

        </div>    

    </body>

    <script > 

        angular.module('todoApp', [])

        .controller('BeerCounter', function($scope, $http, $location) {

            console.log(localStorage.getItem("profile"))
            
            // mensaje
            $scope.message = ''
            $scope.email = 'oscar@yopmail.co'
            $scope.password = '12345678'
            let url = 'http://localhost/compensar_api/public/'

            // envia la peticion login
            $scope.sendLogin = function(){
                $http({
                    method: 'POST',
                    url: url + 'login/signin',
                    data: $.param({ 
                        email: $scope.email,
                        password: $scope.password
                    }),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
                }).then(function successCallback(r) {
                    console.log(r)
                    if(!r.data.response){
                        $('.modal').modal();
                    }else{

                        let data = r.data.data
                        localStorage.setItem("profile", JSON.stringify(data.user));
                        localStorage.setItem("token", data.token);

                        window.location.href =  url + "employees/list" 
                    }
                    // this callback will be called asynchronously
                    // when the response is available
                }, function errorCallback(r) {
                    console.log(r.data.response)
                    if(!r.data.response){
                        $('#modal1').modal('open');
                        $scope.message = r.data.message
                    }
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }
            
        });

        $(document).ready(function(){
            // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
        });
        
    </script> 
    

</html>