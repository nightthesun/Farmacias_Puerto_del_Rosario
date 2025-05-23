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
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary" @click="listarEmepleado_2(); abrirModal('registrar')">
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
                                    <div v-if="usuario.name!='admin'" class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',usuario)" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                        </div>
                                        <div  v-else>
                                            <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                        </div>

                                        <div v-if="puedeActivar==1">
                                        <button v-if="usuario.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarUsuario(usuario.id)" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-info btn-sm" @click="activarUsuario(usuario.id)" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                        </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="usuario.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                        </button>
                                        </div>
                                        
                                        <div v-if="puedeHacerOpciones_especiales==1">
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
                                        <div v-else>
                                            <button  type="button" class="btn btn-light btn-sm">
                                            <!-- <i class="icon-pencil"></i> -->Editar Rol-Sucursal
                                        </button>&nbsp;
                                        <button type="button" style="color: aliceblue;" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Dar permisos de editar y eliminar"
                                       >
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </button>&nbsp;
                                        <button type="button" style="color: aliceblue;" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Usuario x tiendas y almacenes"
                                       >
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </button>&nbsp;
                                        <button type="button" style="color: aliceblue;" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver usuario x roles"
                                        >
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </button>
                                        </div>
                                       

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
        <transition name="fade">
            <div v-if="showModal_1" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <form action=""  class="form-horizontal">
                              <!-- insertar datos -->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Empleado: <span  v-if="selected==null" class="error">(*)</span></label>
                                <div class="col-md-9" v-if="!siactualizar">
                                    <VueMultiselect
                        v-model="selected"
                        :options="arrayEmpleado_2 "
                        :max-height="300"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="name" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-350"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect> 
                                    <span  v-if="selected==null " class="error">Debe seleccionar una opcion</span>
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
                            <div v-if="tipoAccion == 2">
                              
                                <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-5" style="font-size: 11px; text-align: center">Empelado</th>
                                <th class="col-md-7" style="font-size: 11px; text-align: center">Rol/sucursal</th>                          
                            </tr>
                        </thead>
                        <tbody>
                
                            <tr>
                                <td class="col-md-5" style="font-size: 11px; text-align: center">{{nombre}}</td>
                    
                                    <td class="col-md-7" style="font-size: 11px; text-align: center">
                                        <div v-for="(item, index) in arrayModal_2" :key="item.id">
                                            <span>{{ item.rolsucursal }}</span>
                                        </div>
                                        
                                    </td>
                                              
                            </tr>
                        </tbody>
                     </table> 
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
                                    <select  v-model="rol" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="roles in arrayRoles" :key="roles.id" :value="roles.id" v-text="roles.nombre" ></option>
                                    </select>
                                    <span  v-if="rol==0 " class="error">Debe seleccionar una opcion</span>
                                </div>
                                <label class="col-md-2 form-control-label" for="text-input">Seleccionar Sucursal <span  v-if="sucursal==0" class="error">(*)</span></label>
                                <div class="col-md-4">
                                    <select v-model="sucursal" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="sucur in arraySucursal" :key="sucur.id" :value="sucur.id" v-text="sucur.nombre" ></option>
                                    </select>
                                    <span  v-if="sucursal==0" class="error">Debe seleccionar una opcion</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarUsuario()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarUsuario()">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1 && arrayEmpleado.length > 0 " class="btn btn-light">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-light">Actualizar</button>
                            </div>
                        </div>
                    </div>
                        
                    </div>
                </div>
            </div>
        </transition>              
       
        <!--Fin del modal-->

        
        <!--Inicio del modal roles y sucursales-->
        <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('addrolsuc')">
                            <span>&times;</span>
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
                </div>
            </div> 
        </transition>                   
      
        <!--Fin del modal-->

        <!-----------------------------MODAL AÑADIR PERMISOS EDITAR /ACTIVAR------------------------------------------------>
        <transition name="fade">
            <div v-if="showModal_3" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_E_A')">
                            <span>&times;</span>
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
                                        <th scope="col">Especial</th>
                                        <th scope="col">Crear</th>
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
                                        <td>
                                          <input type="checkbox" :value="{ id: aa.id_ventana, especial: aa.especial}" v-model="permisoEspecial[aa.id_ventana]">
                                        </td>
                                        <td>
                                          <input type="checkbox" :value="{ id: aa.id_ventana, crear: aa.crear}" v-model="permisoCrear[aa.id_ventana]">
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
                </div>        
            </div>
        </transition>            
       
        <!--Fin del modal activar editar-->

   <!--Inicio del modal asignacion de mas sucusales -->
   <transition name="fade">
            <div v-if="showModal_4" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar_mas_sucursales')">
                            <span>&times;</span>
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
                </div>
            </div>
    </transition>                    
   
        <!--Fin del modal-->

<!-----------------------------------------------MODAL VER PERMISOS PERMISOS------------------------------------------------>
        <transition name="fade">
            <div v-if="showModal_5" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
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
                </div>        
            </div>
        </transition>                    

<!--Fin del modal activar editar-->

    </main>
</template>

<script>
import Swal from 'sweetalert2';
import { error401 } from '../../errores';
import VueMultiselect from 'vue-multiselect';
//Vue.use(VeeValidate);
    export default {
        components: { VueMultiselect },
        //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
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
                isSubmitting: false, // Controla el estado del botón de envío
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
                nombre:"",
                id_sucursal:0,
                //añadir permisos de editar y activar
                arrayPermisoEditar_Activar:[],
                permisosEditar: {},
        permisosActivar: {},
        permisoEspecial:{},
        permisoCrear:{},
                arrayE_A_S:[],
                id_user_permiso:'',  
                //añadir mas sucursales                   
                arraySucursalAlmTda:[],
                selectAlmTda2:[],
                arrayFalso:[],
                arrayMasSucursales:[],
                //boton roles y sucursales
                arrayRoles_Perimos:[],
                showModal_1: false,
                showModal_2: false,
                showModal_3: false,
                showModal_4: false,
                showModal_5: false,
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
                
                arrayModal_2:[],
                //---------------
                arrayEmpleado_2:[],
                selected: null,
                randomString:'',
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
                if (me.selected!=null && me.email !="" && me.rol !=0 && me.sucursal!=0)
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
               //-----------------------------------permisos_R_W_S        
    listarPerimsoxyz() {
                //console.log(this.codventana);
    let me = this;
   
        
    var url = '/gestion_permiso_editar_eliminar?win='+me.codventana;
  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;

            if(respuesta=="root"){
            me.puedeEditar=1;
            me.puedeActivar=1;
            me.puedeHacerOpciones_especiales=1;
            me.puedeCrear=1; 
            }else{
            me.puedeEditar=respuesta.edit;
            me.puedeActivar=respuesta.activar;
            me.puedeHacerOpciones_especiales=respuesta.especial;
            me.puedeCrear=respuesta.crear;        
            }
           
        })
        .catch(function(error) {
            error401(error);
            console.log(error);
        });
},
//--------------------------------------------------------------   
            listarGetUsersWithRolesAndSucursals(user_id){
                let me = this;
                var url ="/userrolesuc/getUsersWithRolesAndSucursals?user_id="+user_id;
                axios.get(url)
                .then(function (response){
                    var respuesta=response.data;
                    me.arrayRoles_Perimos = respuesta;       
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
const cc = me.permisoEspecial;
const d = me.permisoCrear;
// Obtener todas las claves únicas de a y b
const allKeys = new Set([...Object.keys(a), ...Object.keys(b), ...Object.keys(cc), ...Object.keys(d)]);

// Crear el objeto c con las uniones lógicas
const c = {};
allKeys.forEach(key => {
  const aValue = a.hasOwnProperty(key) ? a[key] : false;
  const bValue = b.hasOwnProperty(key) ? b[key] : false;
  const cValue = cc.hasOwnProperty(key) ? cc[key] : false;
  const dValue = d.hasOwnProperty(key) ? d[key] : false;
  c[key] = { a: aValue, b: bValue, cc: cValue, d: dValue};

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
  if (c[key].cc === true) {
    c[key].cc = 1;
  } else {
    c[key].cc = 2;
  }
  if (c[key].d === true) {
    c[key].d = 1;
  } else {
    c[key].d = 2;
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

            listarEmepleado_2(){
                let me=this;
                me.arrayEmpleado_2=[]; 
                var url='/getEmpelado';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayEmpleado_2=respuesta;
                    
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
                if (me.password===null || me.password==="") {
                    Swal.fire(
                    "Error",
                    "La contraseña esta vacia",
                    "error");
                } else {
                    //let resp=me.arrayEmpleado.find(element=>element.id==me.idempleado);

// Si ya está enviando, no permitas otra solicitud
if (me.isSubmitting) return;
me.isSubmitting = true; // Deshabilita el botón
me.generateRandomString()
                let cadena=(me.selected).name+"-"+me.randomString;
axios.post('/registro',{
   //'name':resp.name,
   'name':cadena,
   //'idempleado':me.idempleado,
   'idempleado':(me.selected).id,
   'email':me.email,
   'password':me.password,
   'idrole':me.rol,
   'idsucursal':me.sucursal
}).then(function(response){
   me.cerrarModal('registrar');
   me.listarUsuarios();
   if(response.data.length){
                      
                        Swal.fire(
                    "Error",
                    "Correo duplicado",
                    "error");
                     
                    }else{
                        Swal.fire("Se creo","Correctamente","success");
                    }

}).catch(function(error){
   error401(error);
   console.log(error);
}).finally(() => {
me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
});
                }
            },

            generateRandomString() {
                let me=this;
      const length = 3; // Longitud deseada de la cadena
      const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letras y números disponibles
      let result = '';
      for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      me.randomString = result; // Asignamos la cadena generada a la variable data
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
           
                if ((me.password===null || me.password==="")&& me.cambiarpass===true  ) {
                    Swal.fire(
                    "Error",
                    "La contraseña esta vacia",
                    "error");
                } else {
                    axios.put('/usuario/actualizar',{
                    'id':me.idusuario,
                    'cambiarpass':me.cambiarpass,
                    'email':me.email,
                    'password':me.password
                    
                }).then(function (response) {         
            
                    if(response.data.length){
                        me.listarUsuarios();
                        Swal.fire(
                    "Error",
                    "Correo duplicado",
                    "error");
                     
                    }else{
                            Swal.fire('Actualizado Correctamente')                            
                            me.listarUsuarios();
                    } 
                }).catch(function (error) {
                    error401(error);
                });
                me.cerrarModal('registrar');
                }  
            },

            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.siactualizar=0;
                        me.selected=null;
                        me.isSubmitting=false;
                        me.showModal_1 = true;
                        me.tituloModal='Registar Usuario'
                        me.tipoAccion=1;
                        me.nombre='';
                        me.idempleado=0;
                        me.email='';
                        me.password='';
                        me.rol=0;
                        me.sucursal=0;
                        me.cambiarpass=false;
                        
                me.selected= null;
                me.randomString='';
                        me.classModal.openModal('registrar');
                        this.selectEmpleados();
                        break;
                    }
                    
                    case 'actualizar':
                    {
          
                        me.cambiarpass=false;
                        me.showModal_1 = true;
                        me.siactualizar=1;
                        me.isSubmitting=false;
                        me.idusuario=data.id;
                        me.nameempleado=data.name;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Usuario'
                        me.nombre=data.nombre;
                        me.email=data.email;
                        me.password='';
                        me.rol=999;
                        me.sucursal=999;
                        me.idempleado=999;
                        me.arrayModal_2=data.rolsucursal;
                        //me.rol=data.rolsucursal[0].idrole;
                        //me.sucursal=data.rolsucursal[0].idsucursal;
                   
                       
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'addrolsuc':
                        {                                
                            me.arrayRolSucursal=data.rolsucursal;
                            me.showModal_2 = true;
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
                            me.showModal_3 = true;
                            me.tituloModal='Permiso de editar y eliminar para '+data.nombre;
                       
                            me.id_user_permiso=data.id;                            
                            // me.listarPermiso_activar(data.id);
                            // Se busca un elemento en el array arrayPermisoEditar_Activar donde id_user_role_sucu coincida con data.id
                           // let user1 = this.arrayE_A_S.find((element) => element.id_user_role_sucu === data.id);
                           let user1 = this.arrayE_A_S.filter(item => item.id_user_role_sucu === data.id);
                           let vectorE = {};
                           let vectorA = {};
                           let vectorP = {};
                           let vectorC = {};
                           user1.forEach(user => {
                                // Cambiar el valor de edit a true si es 1, o false si es 2
                            let editar = user.edit === 1 ? true : false;
                                // Cambiar el valor de activar a true si es 1, o false si es 2
                            let activar = user.activar === 1 ? true : false;

                            let especial = user.especial === 1 ? true : false;
                            let crear = user.crear === 1 ? true : false;
                            vectorE[user.id_ventana] = editar;
                            vectorA[user.id_ventana] = activar;
                            vectorP[user.id_ventana] = especial;
                            vectorC[user.id_ventana] = crear;
                            });

                           // Si se encuentra un elemento que coincida, se establecen las variables permiso_Editar y permiso_Activar en los valores edit y activar del elemento encontrado, respectivamente
                           if (user1.length > 0) {
                            this.permisosEditar = vectorE;
                            this.permisosActivar = vectorA;
                            this.permisoEspecial = vectorP;
                            this.permisoCrear = vectorC;
                           
                            } else {
                            this.permisosEditar = {};                   
                            this.permisosActivar = {};
                            this.permisoEspecial = {};
                            this.permisoCrear = {};
                            }
                           me.classModal.openModal('registrar_E_A');    
                            break;
                        } 
                        case 'registrar_mas_sucursales':
                        {      
                            me.showModal_4 = true;  
                        me.tituloModal='Asignar mas sucursales';
                        me.selectAlmTda2=me.arrayFalso;
                        me.id_user_permiso=data.id;
                    
                        me.classModal.openModal('registrar_mas_sucursales');                   
                        break;
                        } 
                        case 'GetUsersWithRolesAndSucursals':
                        {       
                        me.showModal_5 = true; 
                        me.tituloModal='Usuario:'+data.nombre;                   
                        me.classModal.openModal('GetUsersWithRolesAndSucursals');                   
                        break;
                        }                      
                }
               
            },
            cerrarModal(accion){
                let me = this;
                me.isSubmitting=false;
                me.showModal_1 = false;
                me.showModal_2 = false;
                me.showModal_3 = false;
                me.showModal_4 = false;
                me.showModal_5 = false;
                me.classModal.closeModal(accion);
                me.nombre='';
                me.email='';
                me.tipoAccion=1;
                me.password=true;
                me.siactualizar=0;
                me.tituloModal='';
                me.cambiarpass=false;
                        me.idempleado=0;
                me.rol=0;
                me.sucursal=0;
                me.permiso_Editar=0;                
                me.permiso_Activar=0;
               me.id_user_permiso='';
               me.permisosEditar = {};                   
               me.permisosActivar = {};
               me.permisoEspecial ={};
                me.arrayFalso=[];
                me.selectAlmTda2=[];
                me.arrayMasSucursales=[];
                me.arrayModal_2=[];
                me.arrayEmpleado_2=[];
                me.selected= null;
                me.randomString='';
                
            },

            nameWithLang ({name,nomempleado, ci,}) {
            
            return `${name} ${nomempleado} (${ci}) `
          },

        toggle () {
      this.$refs.multiselect.$el.focus()

      setTimeout(() => {
        this.$refs.multiselect.$refs.search.blur()
      }, 1000)
    },
    open () {
      this.$refs.multiselect.activate()
    },
    close () {
      this.$refs.multiselect.deactivate()
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
               //-------permiso E_W_S-----
               this.listarPerimsoxyz();
            //-----------------------
            this.listarUsuarios(1);
            this.selectRoles();
            //this.listarEmepleado_2();
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
<style scoped>
.modal {
  transition: opacity 0.5s ease;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active en versiones de Vue < 2.1.8 */ {
  opacity: 0;
}
</style>