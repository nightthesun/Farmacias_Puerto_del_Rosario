<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Usuarios</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarUsuarios(1)">
                                <button type="submit" class="btn btn-primary" @click="listarUsuarios(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>                          
                                <th>Nombre</th>
                                <th>email</th>
                                <th>rol - sucursal</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="usuario in arrayUsuarios" :key="usuario.id">
                                <td>
                                    <div v-if="usuario.name!='admin'">
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',usuario)">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button v-if="usuario.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarUsuario(usuario.id)" >
                                            <i class="icon-trash"></i>
                                        </button>&nbsp;
                                        <button v-else type="button" class="btn btn-info btn-sm" @click="activarUsuario(usuario.id)" >
                                            <i class="icon-check"></i>
                                        </button>&nbsp;
                                        <button  type="button" class="btn btn-success btn-sm" @click="abrirModal('addrolsuc',usuario)">
                                            <!-- <i class="icon-pencil"></i> -->Editar Rol-Sucursal
                                        </button>&nbsp;
                                        <button type="button" style="color: aliceblue;" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Dar permisos de editar y eliminar"
                                        @click="abrirModal('registrar_E_A',usuario);listarPermiso_activar(usuario.id);">
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </button>&nbsp;
                                        <button type="button" style="color: aliceblue;" class="btn btn-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Usuario x tiendas y almacenes"
                                        @click="abrirModal('registrar_mas_sucursales',usuario);sucursalAlmTda();listarMasSucursales(usuario.id);" >
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </button>&nbsp;
                                        <button type="button" style="color: aliceblue;" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver usuario x roles"
                                        @click="abrirModal('GetUsersWithRolesAndSucursals',usuario);listarGetUsersWithRolesAndSucursals(usuario.id);" >
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </button>

                                    </div>
                                      
                                </td>
                               
                                    <td v-text="usuario.nombre"></td>
                                    <td v-text="usuario.email"></td>
                                    <td><div  v-for="rolsuc in usuario.rolsucursal" :key="rolsuc.id" >
                                            <span v-if="rolsuc.activo" class="badge badge-success" v-text="rolsuc.rolsucursal"></span>
                                            <span v-else class="badge badge-danger" v-text="rolsuc.rolsucursal"></span>

                                        </div></td>
                                    
                                <td>
                                    <div v-if="usuario.activo==1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning">Desactivado</span>
                                    </div>
                                    
                                </td>
                            </tr>
                           
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active':'']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page< pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="registrar" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('registrar')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                   
                    <div class="modal-body" v-if="arrayEmpleado.length > 0 || tipoAccion == 2">
                        <form action=""  class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Empleado: <span  v-if="idempleado==0" class="error">(*)</span></label>
                                <div class="col-md-9" v-if="!siactualizar">
                                    <select name="" id="" v-model="idempleado" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="empleados in arrayEmpleado" :key="empleados.id" v-text="empleados.nomempleado" :value="empleados.id" ></option>
                                    </select>
                                    <span  v-if="empleado==0" class="error">(*)</span>
                                </div>
                                <div class="col-md-9" v-else>
                                    <strong>{{ nameempleado }}</strong>                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Email: <span  v-if="email==''" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Ingrese un Email" v-model="email" v-on:focus="selectAll">
                                    <span  v-if="email==''" class="error">Debe Ingresar el email</span>
                                </div>
                            </div>
                            <div>

                            </div>

                            <div v-if="siactualizar" >

                                <input type="checkbox" v-model="cambiarpass" unchecked id="cambiarpass"> <label for="cambiarpass">Actualizar Password?</label>
                            </div>

                            
                            <div class="form-group row" v-if="cambiarpass ||!siactualizar">
                                <label class="col-md-3 form-control-label" for="password"> Password: <span  v-if="password==''" class="error">(*)</span> </label>
                                <div class="col-md-9">
                                    <input type="password"  class="form-control"  v-model="password"   >
                                    <span  v-if="password==''" class="error">Debe Ingresar el password</span>
                                </div>
                            </div>

                            <div class="form-group row" v-if="!siactualizar">
                                <label class="col-md-2 form-control-label" for="text-input">Seleccionar Rol: <span  v-if="rol==0" class="error">(*)</span></label>
                                <div class="col-md-4">
                                    <select name="" id="" v-model="rol" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="roles in arrayRoles" :key="roles.id" :value="roles.id" v-text="roles.nombre" ></option>
                                    </select>
                                </div>
                                <label class="col-md-2 form-control-label" for="text-input">Seleccionar Sucursal <span  v-if="sucursal==0" class="error">(*)</span></label>
                                <div class="col-md-4">
                                    <select name="" id="" v-model="sucursal" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="sucur in arraySucursal" :key="sucur.id" :value="sucur.id" v-text="sucur.nombre" ></option>
                                    </select>
                                </div>
                            </div>
                            
                            
                        </form>
                    </div>
                    <div class="modal-body" v-else>
                        <div class="alert alert-primary d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                            <div><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                              Debe registrar al empleado primero en el modulo de <i class="fa fa-handshake-o" aria-hidden="true"></i> RRHH en empleados.
                            </div>
                          </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1 && arrayEmpleado.length > 0 " class="btn btn-primary" @click="registrarUsuario()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarUsuario()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="addrolsuc" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('addrolsuc')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th colspan="3">Rol Sucursal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="rolsuc in arrayRolSucursal" :key="rolsuc.id">
                                <td>
                                    <!-- <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizarrolsuc',rolsuc)">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp; -->
                                    <button v-if="rolsuc.activo" type="button" class="btn btn-danger btn-sm" @click="eliminarRolSuc(rolsuc.id)" >
                                        <i class="icon-trash"></i>
                                    </button>&nbsp;
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarRolSuc(rolsuc.id)" >
                                        <i class="icon-check"></i>
                                    </button>&nbsp;
                                    
                                    
                                </td>
                                    <td v-text="rolsuc.rolsucursal" colspan="3"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <label class="form-control-label" for="text-input">Seleccionar Rol: <span  v-if="rol==0" class="error">(*)</span></label>
                                
                                    <select name="" id="" v-model="rol" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="roles in arrayRoles" :key="roles.id" :value="roles.id" v-text="roles.nombre" ></option>
                                    </select>
                                </td>
                                <td>
                                    <label class="form-control-label" for="text-input">Seleccionar Sucursal <span  v-if="sucursal==0" class="error">(*)</span></label>
                                
                                    <select name="" id="" v-model="sucursal" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="sucur in arraySucursal" :key="sucur.id" :value="sucur.id" v-text="sucur.nombre" ></option>
                                    </select>
                                </td>
                                <td>
                                    <button  type="button" class="btn btn-warning btn-sm" style="color: white;" v-if="rol==0 || sucursal==0"  disabled >
                                        Debe seleccionar Rol-Sucursal
                                    </button>&nbsp;
                                    <button  type="button" class="btn btn-success btn-sm" v-else @click="AgregarRolSuc()" >
                                        Agregar Rol-Sucursal
                                    </button>&nbsp;
                                </td>
                               
                                
                                
                                
                            
                            </tr>
                           
                        </tbody>
                    </table>
                        
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('addrolsuc')">Cerrar</button>
                        <!-- <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarRolSuc()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRolSuc()">Actualizar</button> -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!-----------------------------MODAL AÑADIR PERMISOS EDITAR /ACTIVAR------------------------------------------------>
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="registrar_E_A" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('registrar_E_A')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" v-for="a in arrayPermisoEditar_Activar" :key="a.id_modelo">
                                <a class="nav-link" :class="{ 'active': a.id_modelo === arrayPermisoEditar_Activar[0].id_modelo }" :id="'pills-'+a.id_modelo+'-tab'" data-toggle="pill" :href="'#pills-'+a.id_modelo" role="tab" :aria-controls="'pills-'+a.id_modelo" :aria-selected="a.id_modelo === arrayPermisoEditar_Activar[0].id_modelo">{{a.nom_modelo}}</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div v-for="a in arrayPermisoEditar_Activar" :key="a.id_modelo" :class="['tab-pane', { 'show': a.id_modelo === arrayPermisoEditar_Activar[0].id_modelo, 'fade': a.id_modelo === arrayPermisoEditar_Activar[0].id_modelo }]" :id="'pills-'+a.id_modelo" role="tabpanel" :aria-labelledby="'pills-'+a.id_modelo+'-tab'">
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Editar</th>
                                        <th scope="col">Eliminar</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr v-for="aa in a.ventanas" :key="aa.id_ventana">
                                        
                                        <td v-text="aa.id_ventana"></td>
                                        <td v-text="aa.nombre_ventana"></td>
                                        <td> 
                                          <input type="checkbox" :value="{ id: aa.id_ventana, editar: aa.editar}" v-model="permisosEditar[aa.id_ventana]">
                                        </td>
                                        <td>
                                          <input type="checkbox" :value="{ id: aa.id_ventana, eliminar: aa.eliminar}" v-model="permisosActivar[aa.id_ventana]">
                                        </td>
                                      </tr>
                                      
                                    
                                    </tbody>
                                  </table>
                            </div>
                       
                        </div>
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar_E_A')">Cerrar</button>
                      
                        <button type="button"  class="btn btn-primary" @click="registrarEditar_Activar()" >Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal activar editar-->

   <!--Inicio del modal asignacion de mas sucusales -->
   <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="registrar_mas_sucursales" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('registrar_mas_sucursales')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-12" >
                                <div class="modal-body">
                     


                     <form action=""  class="form-horizontal">

                       
                             <label class="col-md-6 form-control-label" for="text-input"><strong>Lista de sucursales:</strong> </label>
                             <div class="col-md-9">
                                <table class="table table-bordered table-striped table-sm table-responsive">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Cod Sucursal</th>
                                            <th>Codigo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="option in arraySucursalAlmTda" :key="option.id" >
                                            <td>
                                                <input type="checkbox" :value="{ codigo: option.codigo, id_sucursal: option.id_sucursal, id_tienda_almacen: option.id_tienda_almacen }" v-model="selectAlmTda2">
                                            </td>
                                            
                                            <td v-text="option.razon_social"></td>
                                            <td v-text="option.codigoS"></td>
                                            <td v-text="option.codigo"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            
                        
                             </div>
                       

                     </form>
                 </div>
                            </div>
                         
                            </div>
                        </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar_mas_sucursales')">Cerrar</button>
                      
                        <button type="button" class="btn btn-primary" @click="asignarSucursal()">Asignar</button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

<!-----------------------------------------------MODAL VER PERMISOS PERMISOS------------------------------------------------>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="GetUsersWithRolesAndSucursals" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ tituloModal }}</h4>
                <button type="button" class="close"  aria-label="Close" @click="cerrarModal('GetUsersWithRolesAndSucursals')">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <!-- modal-body -->
            
            
            <div class="col-md-12 d-flex justify-content-center" style="padding-top: 20px;">
                <div style="height: 250px; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead style="position: sticky; top: 0; background-color: aliceblue;">
                            <tr>
                                <th>Local</th>
                                <th>Modulo</th>
                                <th>Ventana</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="role in arrayRoles_Perimos" :key="role.key">                
                                <td v-text="role.sucursal_rol"></td>
                                <td v-text="role.modulo_nombre"></td>
                                <td v-text="role.ventana_nombre"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            
            
            <!---end modal --->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  @click="cerrarModal('GetUsersWithRolesAndSucursals')">Cerrar</button>
              
             </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!--Fin del modal activar editar-->

    </main>
</template>

<script>
import Swal from 'sweetalert2';
import { error401 } from '../../errores';
//Vue.use(VeeValidate);
    export default {
        data(){
            return{
                pagination:{
                    'total' :0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0
                },
                offset:3,
                idempleado:0,
                email:'',
                arrayUsuarios:[],
                tituloModal:'',
                tipoAccion:1,
                idusuario:'',
                buscar:'',
                password:'',
                arrayEmpleado:[],
                siactualizar:0,
                nameempleado:'',
                cambiarpass:false,
                rol:0,
                sucursal:0,
                arraySucursal:[],
                arrayRoles:[],
                arrayRolSucursal:[],
                idrolsuc:'',

                id_sucursal:0,
                //añadir permisos de editar y activar
                arrayPermisoEditar_Activar:[],
                permisosEditar: {},
        permisosActivar: {},
                arrayE_A_S:[],
                id_user_permiso:'',  
                //añadir mas sucursales                   
                arraySucursalAlmTda:[],
                selectAlmTda2:[],
                arrayFalso:[],
                arrayMasSucursales:[],
                //boton roles y sucursales
                arrayRoles_Perimos:[],
                
            }

        },
        watch:{
            sucursal: function(id){
           
            let sucursalss = this.arraySucursal.find(
                    (element) => element.id === id);
                    if (sucursalss) {                        
                        this.id_sucursal=sucursalss.id;                       
                    }
        },
    },
        computed:{
            sinombre(){
                let me=this;
                if(me.nombre!='')
                    return true;
                else
                    return false;
            },
            
            sicompleto(){
                let me=this;
                if (me.nombre!='' )
                    return true;
                else
                    return false;
            },
            isActived:function(){
                return this.pagination.current_page;
            },
            pagesNumber:function(){
                if(!this.pagination.to){
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from<1){
                    from=1;
                }
                var to = from +(this.offset * 2);
                if(to>= this.pagination.last_page){
                    to=this.pagination.last_page;
                }
                var pagesArray =[];
                while(from<=to){
                    pagesArray.push(from);
                    from++
                }
                return pagesArray;
            },


        },
        methods :{         
            listarGetUsersWithRolesAndSucursals(user_id){
                let me = this;
                var url ="/userrolesuc/getUsersWithRolesAndSucursals?user_id="+user_id;
                axios.get(url)
                .then(function (response){
                    var respuesta=response.data;
                    me.arrayRoles_Perimos = respuesta;
                    console.log(me.arrayRoles_Perimos);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });   
            },

              listar_asig_permiso_e_a_s()
            {
                   let me = this;                              
                    var url = "/userrolesuc/listar_asig_permiso_e_a_s";             
                    axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayE_A_S = respuesta;
             
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });            
            },            
      

            listarMasSucursales(id)
            {
                   let me = this;               
                   if (typeof id!=="undefined") {
                    var url = "/userrolesuc/listarMas_sucursales?id="+id;             
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                   //  me.arrayVehucloX = respuesta.datos.data;
                   me.arrayMasSucursales = respuesta;      
                 
                   me.arraySucursalAlmTda.forEach(function(elemento1) {
                   me.arrayMasSucursales.forEach(function(elementoInterno) {
                    if (elemento1.codigo === elementoInterno.codigo) {
                    me.arrayFalso.push({
                    codigo: elemento1.codigo,
                    id_sucursal: elemento1.id_sucursal,
                    id_tienda_almacen: elemento1.id_tienda_almacen,       
                    });
                } 
            });  
        });
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });  
                   }
            
            },

            asignarSucursal(){
            let me =this;                
            let cadena=[];
            for (const selectedOption of this.selectAlmTda2) {
                let elemento = {
                'codigo': selectedOption.codigo,
                'id_sucursal': selectedOption.id_sucursal,
                'id_tienda_almacen': selectedOption.id_tienda_almacen
            };
            cadena.push(elemento);
            }                
                axios.post('/userrolesuc/asignar',{
                    id:me.id_user_permiso,
                    bloque:cadena,
                    
                }).then(function (response) {
                    
                    Swal.fire(
                        'Asigno Correctamente!',
                        'El Accion realizada Correctamente',
                        'success'
                    )
                }).catch(function (error) {
                    error401(error);
                });
                me.arrayFalso=[];
                me.cerrarModal('registrar_mas_sucursales');
            },   

            sucursalAlmTda() {
            let me = this;
            var url = "/userrolesuc/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursalAlmTda = respuesta;
  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
            },

        listarPermiso_activar(id) {
            let me = this;
            var url = "/userrolesuc/listarVentanas?id="+id;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayPermisoEditar_Activar = respuesta;
          
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        registrarEditar_Activar(){   
              
                           let me = this;    
                            
const a = me.permisosEditar;
const b = me.permisosActivar;

// Obtener todas las claves únicas de a y b
const allKeys = new Set([...Object.keys(a), ...Object.keys(b)]);

// Crear el objeto c con las uniones lógicas
const c = {};
allKeys.forEach(key => {
  const aValue = a.hasOwnProperty(key) ? a[key] : false;
  const bValue = b.hasOwnProperty(key) ? b[key] : false;
  c[key] = { a: aValue, b: bValue };

  // Cambiar los valores true/false por 1/2
  if (c[key].a === true) {
    c[key].a = 1;
  } else {
    c[key].a = 2;
  }

  if (c[key].b === true) {
    c[key].b = 1;
  } else {
    c[key].b = 2;
  }
});


                axios.post('/userrolesuc/registrarEditar_Activar',{
                 
                    'id':me.id_user_permiso,
                    'datos':c,
                  
                   
                }).then(function(response){
                    me.cerrarModal('registrar_E_A');
                    me.listarUsuarios();                  
                    me.listar_asig_permiso_e_a_s();
                })  .catch(function (error) {           
                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
            },

            AgregarRolSuc(){
                let me = this;                
                let user=me.idusuario;
                var bandera=0;
              
                this.arrayRolSucursal.forEach(item => {
                     if (item.idrole === me.rol && item.idsucursal === me.sucursal) {
                      bandera = 1;
                    return; // Termina el ciclo forEach
                      }
                });
             if (bandera==1) {
                Swal.fire(
                    "El rol y la sucursal ya existe para el usuario debe seleccionar otro rol y sucursal que no este en la tabla.",
                    "Haga click en Ok",
                    "warning",
                );
             } else {
                axios.post('/userrolesuc/registrar',{
                    'iduser':user,
                    'idsucursal':me.sucursal,
                    'idrole':me.rol,
                }).then(function(response){
                    me.cerrarModal('registrar');
                    me.listarUserRolSuc();
                    me.listarUsuarios();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });
             }
            },

            listarUserRolSuc(){
                let me=this;
                var url='/userrolesuc?iduser='+me.idusuario;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayRolSucursal=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            listarUsuarios(page){
                let me=this;
                var url='/usuario?page='+page+'&buscar='+me.buscar;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arrayUsuarios=respuesta.users.data;   
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarUsuarios(page);
            },
            registrarUsuario(){
                let me = this;                
                let resp=me.arrayEmpleado.find(element=>element.id==me.idempleado);

                axios.post('/registro',{
                    'name':resp.name,
                    'idempleado':me.idempleado,
                    'email':me.email,
                    'password':me.password,
                    'idrole':me.rol,
                    'idsucursal':me.sucursal
                }).then(function(response){
                    me.cerrarModal('registrar');
                    me.listarUsuarios();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },

            eliminarRolSuc(idrolsuc){
                let me=this;

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/userrolesuc/desactivar',{
                        'id': idrolsuc
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarUserRolSuc();
                        me.listarUsuarios();
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                    
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                }
                })

            },
            activarRolSuc(idrolsuc){
                let me=this;
       
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Activar?',
                text: "Es una Activacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Activar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/userrolesuc/activar',{
                        'id': idrolsuc
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarUserRolSuc();
                        me.listarUsuarios();
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                    
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue Activado',
                    'error'
                    ) */
                }
                })
            },
            eliminarUsuario(idusuario){
                let me=this;
            
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/usuario/desactivar',{
                        'id': idusuario
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarUsuarios();
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                    
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                }
                })
            },
            activarUsuario(idusuario){
                let me=this;
          
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Activar?',
                text: "Es una Activacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Activar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/usuario/activar',{
                        'id': idusuario
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarUsuarios();
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                    
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue Activado',
                    'error'
                    ) */
                }
                })
            },
            actualizarUsuario(){
               // const Swal = require('sweetalert2')
                let me =this;
                                
                axios.put('/usuario/actualizar',{
                    'id':me.idusuario,
                    'cambiarpass':me.cambiarpass,
                    'email':me.email,
                    'password':me.password
                    
                }).then(function (response) {
                    if(response.data.length){
                    }
      
                    else{
                            Swal.fire('Actualizado Correctamente')

                        me.listarUsuarios();
                    } 
                }).catch(function (error) {
                    error401(error);
                });
                me.cerrarModal('registrar');


            },
            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.siactualizar=0;
                        me.tituloModal='Registar Usuario'
                        me.tipoAccion=1;
                        me.nombre='';
                        me.idempleado=0;
                        me.email='';
                        me.password='';
                        me.rol=0;
                        me.sucursal=0;
                        
                        me.classModal.openModal('registrar');
                        this.selectEmpleados();
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.siactualizar=1;
                        me.idusuario=data.id;
                        me.nameempleado=data.name;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Usuario'
                        me.nombre=data.nombre;
                        me.email=data.email;
                        me.password='';
                        me.rol=0;
                        me.sucursal=0;
                       
                        //me.rol=data.rolsucursal[0].idrole;
                        //me.sucursal=data.rolsucursal[0].idsucursal;
                   
                       
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'addrolsuc':
                        {                                
                            me.arrayRolSucursal=data.rolsucursal;
                            me.tituloModal='Editar - Agregar Rol Sucursal';
                            me.idusuario=data.id;
                            me.idsucursal=0;
                            me.idrole=0;
                            me.id_sucursal=0;
                            me.rol=0;
                             me.sucursal=0;
                            me.classModal.openModal('addrolsuc');    
                            break;
                        }
                        case 'registrar_E_A':
                        {
                          
                            me.tituloModal='Permiso de editar y eliminar para '+data.nombre;
                       
                            me.id_user_permiso=data.id;                            
                            // me.listarPermiso_activar(data.id);
                            // Se busca un elemento en el array arrayPermisoEditar_Activar donde id_user_role_sucu coincida con data.id
                           // let user1 = this.arrayE_A_S.find((element) => element.id_user_role_sucu === data.id);
                           let user1 = this.arrayE_A_S.filter(item => item.id_user_role_sucu === data.id);
                           let vectorE = {};
                           let vectorA = {};
                           user1.forEach(user => {
                                // Cambiar el valor de edit a true si es 1, o false si es 2
                            let editar = user.edit === 1 ? true : false;
                                // Cambiar el valor de activar a true si es 1, o false si es 2
                            let activar = user.activar === 1 ? true : false;
                            vectorE[user.id_ventana] = editar;
                            vectorA[user.id_ventana] = activar;
                            });

                           // Si se encuentra un elemento que coincida, se establecen las variables permiso_Editar y permiso_Activar en los valores edit y activar del elemento encontrado, respectivamente
                           if (user1.length > 0) {
                            this.permisosEditar = vectorE;
                            this.permisosActivar = vectorA;
                           
                            } else {
                            this.permisosEditar = {};                   
                            this.permisosActivar = {};
                            }
                           me.classModal.openModal('registrar_E_A');    
                            break;
                        } 
                        case 'registrar_mas_sucursales':
                        {        
                        me.tituloModal='Asignar mas sucursales';
                        me.selectAlmTda2=me.arrayFalso;
                        me.id_user_permiso=data.id;
                    
                        me.classModal.openModal('registrar_mas_sucursales');                   
                        break;
                        } 
                        case 'GetUsersWithRolesAndSucursals':
                        {        
                        me.tituloModal='Usuario:'+data.nombre;                   
                        me.classModal.openModal('GetUsersWithRolesAndSucursals');                   
                        break;
                        }                      
                }
               
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.nombre='';
                me.email='';
                me.tipoAccion=1;
                me.password=true;
                me.siactualizar=0;
                me.tituloModal='';
                me.rol=0;
                me.sucursal=0;
                me.permiso_Editar=0;                
                me.permiso_Activar=0;
               me.id_user_permiso='';
               me.permisosEditar = {};                   
               me.permisosActivar = {};
                me.arrayFalso=[];
                me.selectAlmTda2=[];
                me.arrayMasSucursales=[];
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },
            selectEmpleados(){
                let me=this;
                var url='/empleado/selectnouser';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayEmpleado=respuesta;
                    
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },  
            selectRoles(){
                let me=this;
                var url='/role/selectrole';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayRoles=respuesta;
                    
                })
                .catch(function(error){
                    console.log(error);
                });
            },  
            selectSucursales(){
                let me=this;
                var url='/sucursal/selectsucursal';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arraySucursal=respuesta;
           
                })
                .catch(function(error){
                    console.log(error);
                });
            },  


        },
        mounted() {
            this.listarUsuarios(1);
            this.selectRoles();
         
            this.selectSucursales();
            this.selectEmpleados();
            this.listar_asig_permiso_e_a_s();
            this.listarMasSucursales();
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            this.classModal.addModal('addrolsuc');
            this.classModal.addModal('registrar_E_A');
            this.classModal.addModal('GetUsersWithRolesAndSucursals');
            this.classModal.addModal('registrar_mas_sucursales');
            this.classModal.addModal('registrar_sucursal');
         
            
            //console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;
    
    }
</style>
