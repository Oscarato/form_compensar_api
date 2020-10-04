<html ng-app="todoApp">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Bienvenido</title>
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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

            table {
                width: 100%;
                border: 1px solid #000;
            }
            th, td {
                width: 25%;
                text-align: left;
                vertical-align: top;
                border: 1px solid #000;
                border-collapse: collapse;
                padding: 0.3em;
                caption-side: bottom;
            }
            caption {
                padding: 0.3em;
                color: #fff;
                background: #000;
            }
            th {
                background: #5a99d4 !important;
                color: white;
            }

            td {
                background: #deeaf6 !important;
                color: black;
            }
            
        </style>        

    </head>

    <body >

    <div class="row" ng-controller="BeerCounter">

        <!-- Modal de acciones -->
        <div id="actions" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4>{{action}} Empleado</h4>
                <div class="row">
                    <form  class="col s12" >
                        <p>Tipo_ID <input type="text" ng-model="form.id_type"></p>
                        <p>ID: <input type="text" ng-model="form.identification"> </p>
                        <p>Nombre: <input type="text"  ng-model="form.name" > </p>
                        <p>Apellido: <input type="text"  ng-model="form.lastname"> </p>
                        <p>Cat: <input type="text"  ng-model="form.cat" > </p>
                        <p>Edad: <input type="text"  ng-model="form.age"> </p>
                        <p>Cargo: <input type="text"  ng-model="form.job" > </p>
                        <p>Estado:
                            <input type="text" ng-model="form.status" >
                        </p>
                        <a ng-click="sendUpdate()" class="waves-effect  btn-flat ">Guardar</a>
                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cancelar</a>
                    </form> 
                </div>
            </div>
        </div>

        <!-- Modal de eliminación -->
        <div id="deleteM" class="modal">
            <div class="modal-content">
                <h4>¿Realmente desea eliminar este registro?</h4>
            </div>
            <div class="modal-footer">
                <a ng-click="delete()" class="modal-action modal-close waves-effect waves-green btn-flat">Eliminar</a>
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
            </div>
        </div>

        <div class="col s12 m12" >

            <h3>Bienvenido {{profile.name}}</h3>
            <p>Usa este token para peticiones en POSTMAN</p>
            <p><b>Token:</b> {{token}}</p>
            
            <div style="position: absolute;top: 84%;left: 93%;">
                <a ng-click="openEdit('Crear', {})" class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">add</i></a>
            </div>

            <!-- <a class="btn" onclick="Materialize.toast('I am a toast', 4000)">Toast!</a> -->
            
            <div class="col s12 m12">

                <div class="progress" ng-show="loading">
                    <div class="indeterminate"></div>
                </div>
                <table>
                    <thead>
                        <tr class="odd">
                            <th>Tipo_ID</th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cat</th>
                            <th>Edad</th>
                            <th>Cargo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr ng-repeat="emplo in employees" class="even">
                            <td>{{emplo.id_type}}</td>
                            <td>{{emplo.identification}}</td>
                            <td>{{emplo.name}}</td>
                            <td>{{emplo.lastname}}</td>
                            <td>{{emplo.cat}}</td>
                            <td>{{emplo.age}}</td>
                            <td>{{emplo.job}}</td>
                            <td>{{emplo.status == 0 ? 'Inactivo' : 'Activo'}}</td>
                            <td>
                                <button><i class="tiny material-icons" ng-click="openEdit('Editar', emplo)">edit</i></button>
                                <button><i class="tiny material-icons" ng-click="openDelete(emplo)">delete</i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
            
    </body>

    <script > 

        angular.module('todoApp', [])

        .controller('BeerCounter', function($scope, $http, $location) {
            
            // mensaje
            $scope.message = ''
            $scope.action = ''
            $scope.form = {status:1}
            $scope.loading = true
            $scope.deleteData = {}

            // datos de perfil
            $scope.profile = JSON.parse(localStorage.getItem("profile"))

            // token
            $scope.token = localStorage.getItem("token")

            if(!$scope.token){
                window.location.href =  url + "employees/login" 
            }
            
            let url = 'http://34.229.90.157/compensar_api/public/'

            // traer lista de empleados 
            $scope.fetch = function() {
                $http({
                    method: 'GET',
                    url: url + '/employees',
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-Token-Compensar': $scope.token
                    }
                }).then(function successCallback(r) {
                    
                    let data = r.data.data
                    $scope.employees = data
                    $scope.loading = false

                }, function errorCallback(r) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }

            // llamamos la funcion
            $scope.fetch()

            //abre el modal de edición
            $scope.openEdit = function(action, data){
                $('#actions').modal('open');
                $scope.action = action
                $scope.form = data
            }

            // envia la peticion para actualizar el empleado
            $scope.sendUpdate = function(data){

                $scope.loading = true

                if($scope.action == 'Crear'){

                    $http({
                        method:  'POST',
                        url: url + 'employees',
                        data: $.param( $scope.form ),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Token-Compensar': $scope.token
                        }
                    }).then(function successCallback(r) {
                        
                        let data = r.data
                        
                        if(!r.data.response){
                            Materialize.toast(data.message, 4000)
                        }else{
                            
                            $scope.loading = false
                            $scope.fetch()
                            $scope.form = {status:1}
                            $scope.action = ''
                            $('#actions').modal('close');
                            Materialize.toast(data.message, 4000)
                        }
                        // this callback will be called asynchronously
                        // when the response is available
                    }, function errorCallback(r) {
                        
                        $scope.loading = false
                        let data = r.data
                        if(!data.response){
                            Materialize.toast(data.message, 8000)
                        }
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });

                }else{

                    const data_f = new FormData();
                    for (let index in $scope.form) {
                        const element = $scope.form[index];
                        data_f.append(index, element);
                    }
                    
                    $http({
                        method:  'PUT',
                        url: url + 'employees/' + $scope.form.id,
                        data: data_f,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Token-Compensar': $scope.token
                        }
                    }).then(function successCallback(r) {
                        
                        let data = r.data

                        console.log(data)

                        if(!data.response){
                            $('.modal').modal();
                        }else{
                            
                            $scope.loading = false
                            $scope.fetch()
                            $scope.form = {status:1}
                            $scope.action = ''
                            $('#actions').modal('close');
                            Materialize.toast(data.message, 4000)
                        }
                        // this callback will be called asynchronously
                        // when the response is available
                    }, function errorCallback(r) {

                        let data = r.data
                        $scope.loading = false
                        
                        if(!data.response){
                            Materialize.toast(data.message, 8000)
                        }
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
                }

            }

            // abre el modal de eliminar
            $scope.openDelete= function(data){
                $('#deleteM').modal('open');
                $scope.deleteData = data
            }

            // envia la peticion de eliminar
            $scope.delete = function(){
                $http({
                        method:  'DELETE',
                        url: url + 'employees/' + $scope.deleteData.id,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Token-Compensar': $scope.token
                        }
                    }).then(function successCallback(r) {
                        
                        let data = r.data

                        console.log(data)

                        if(!data.response){
                            $('.modal').modal();
                        }else{
                            
                            $scope.loading = false
                            $scope.fetch()
                            $scope.deleteData = {}
                            $('#deleteM').modal('close');
                            Materialize.toast(data.message, 4000)
                        }
                        // this callback will be called asynchronously
                        // when the response is available
                    }, function errorCallback(r) {

                        let data = r.data
                        $scope.loading = false
                        
                        if(!data.response){
                            Materialize.toast(data.message, 8000)
                        }
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                    });
            }
            
        });

        $(document).ready(function(){
            // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
            $('select').material_select();
        });
        
    </script> 


</html>