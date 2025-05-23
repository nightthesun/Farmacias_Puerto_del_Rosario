<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Vehiculo
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                       <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar" @keyup.enter="listarVehiculo(1)">
                         <button type="button" class="btn btn-primary" @click="listarVehiculo(1)"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                        </div>
                       
                    </div>
                   
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-1">Matricula</th>
                                <th class="col-md-1">Numero de chasis</th>
                                <th class="col-md-1">Color</th>
                                <th class="col-md-1">Telefonos</th>
                                <th class="col-md-1">Tipo</th>
                                <th class="col-md-2">Chofer</th>  
                                <th class="col-md-1">Usuario</th>
                                <th class="col-md-1">Estado</th>
                                  
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="vehiculo in arrayVehiculos" :key="vehiculo.id">
                                <td class="col-md-1">
                                    <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',vehiculo)" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="vehiculo.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminar(vehiculo.id)" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activar(vehiculo.id)" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="vehiculo.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                        <div v-if="puedeHacerOpciones_especiales==1">
                                            <button type="button" style="color: aliceblue; margin-right: 5px;" class="btn btn-secondary btn-sm" @click="listarVehiculoXtdaAlm(vehiculo.id); abrirModal('asignar',vehiculo);" >
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </div> 
                                         <div v-else>
                                            <button type="button" style="margin-right: 5px;" class="btn btn-secondary btn-sm" >
                                            <i class="fa fa-plus"></i>
                                            </button>
                                         </div>  
                                    </div>       
                                    
                                    
                                    
                                </td>
                                <!-- <td v-text="(almacen.codsuc === null ? '': almacen.codsuc+' - ') + almacen.codigo"></td> -->
                                <td class="col-md-1" v-text="vehiculo.matricula"></td>
                                <td class="col-md-1" v-text="vehiculo.nro_chasis"></td>
                                <td class="col-md-1" v-text="vehiculo.color"></td>
                                <td class="col-md-1" v-text="vehiculo.telefono"></td>
                                <td class="col-md-1" v-text="vehiculo.tipo"></td>                            
                                <td class="col-md-2" v-text="vehiculo.nom_completo"></td>
                                <td class="col-md-1" v-text="vehiculo.user_name"></td>
                                <td class="col-md-1">
                                    <div v-if="vehiculo.activo==1">
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
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>


                        <form action=""  class="form-horizontal">


                            <div class="form-group row" >
                                
                                <label class="col-md-3 form-control-label" for="text-input">Asignar Sucursal por defecto:<span  v-if="selectAlmTda==0" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <select name="" id="" v-model="selectAlmTda" class="form-control">
                                       <option v-bind:value="0" disabled>Seleccionar...</option>
                                        <option  v-for="sucursal in arraySucursalAlmTda"
                                        :key="sucursal.id"
                                        :value="sucursal.codigo"
                                        v-text="
                                            sucursal.codigoS +
                                            ' -> ' +
                                            sucursal.codigo+
                                            ' ' +
                                            sucursal.razon_social
                                        "
                                    ></option>
                                    </select>
                                    <span  v-if="selectAlmTda==0" class="error">Debe seleccionar una sucursal</span>
                                </div>
                            </div>
                          
                            <div class="form-group row">
                                
                               
                                <label class="col-md-3 form-control-label" for="text-input">Asignar Chofer: <span  v-if="selectUsuario==0" class="error">(*)</span></label>
                                <div class="col-md-9" v-if="arrayUsuario==''">
                                    <span style="color: red;">
                                        -   Debe crear un empleado con el cargo chofer.
                                        <br>
                                        -   Debe estar como usuario.
                                    </span>
                                </div>
                                <div class="col-md-9" v-if="arrayUsuario!=''">
                                    <select name="" id="" v-model="selectUsuario" class="form-control">
                                       <option v-bind:value="0" disabled>Seleccionar...</option>
                                        <option  v-for="usu in arrayUsuario"
                                        :key="usu.user_id"
                                        :value="usu.user_id"
                                        v-text="usu.nom_completo"
                                    ></option>
                                    </select>
                                    <span  v-if="selectUsuario==0" class="error">Debe seleccionar una sucursal:</span>
                                </div>
                            </div>
                         
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Matricula: <span  v-if="matricula==''" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="tex" id="matricula" name="matricula" placeholder="XXX000" class="form-control"  v-model="matricula" v-on:focus="selectAll"  >
                                    <span  v-if="matricula==''" class="error">Debe Ingresar la matricula</span>
                                </div>
                            </div>
                            <!-- Fin nombre comercial -->
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Telefonos:</label>
                                <div class="col-md-9">
                                    <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Ingrese Los numeros de Telefono" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 13 || event.charCode == 40 || event.charCode == 41 || event.charCode == 45 )" v-model="telefono" v-on:focus="selectAll">
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Numero Chasis:</label>
                                <div class="col-md-9">
                                    <input type="text" id="" name="" class="form-control" placeholder="Ingrese Los numero de chasis" v-model="nro_chasis">
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Color:</label>
                                <div class="col-md-9">
                                    <input type="text" id="" name="" class="form-control" placeholder="Ingrese El color del auto vehiculo" v-model="color">
                                 </div>
                            </div>
                         
                    
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo :<span  v-if="selectTipo==0" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <select name="" id="" v-model="selectTipo" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="tt in arrayTipo" :key="tt.id" :value="tt.valor" v-text="tt.valor"></option>
                                    </select>
                                    <span  v-if="selectTipo==0" class="error">Debe seleccionar un Tipo</span>
                                </div>
                            </div>
                      
                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrar()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizar()" :disabled="!sicompleto">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-light">Actualizar</button>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
            </div>  
        </transition>              
      
        <!--Fin del modal-->
        
         <!--Inicio del modal asignacion -->

         <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal1('registrar1')">
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
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal1('registrar1')">Cerrar</button>
                        <button type="button" v-if="selectAlmTda2==false" class="btn btn-primary" disabled>Asignar</button>
                        <button type="button" v-if="selectAlmTda2!=false" class="btn btn-primary" @click="asignarSucursal()">Asignar</button>
                    </div>
                    
                    </div>
                </div>
            </div>
         </transition>               
    
        <!--Fin del modal-->
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import { error401 } from '../../errores';
import { datapicker } from '../../func_10251';
import { resolveTransitionHooks } from 'vue';
//Vue.use(VeeValidate);
    export default {
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
                nombre:'',
                tipo:0,
                
                tituloModal:'',
                tipoAccion:1,
                
                buscar:'',
               
                showModal: false,
                telefono:'',
                showModal: false,
                showModal_2: false,
                //------
                arraySucursalAlmTda:[],
                selectAlmTda:0,  
                arrayTipo:[
                                {'id':1,'valor':'Automovil'},
                                {'id':2,'valor':'Motosicleta'},
                                {'id':3,'valor':'Otros'},
                               
                            ], 
                selectTipo:0, 
                matricula:'',    
                tipoCodigo:'',
                codigoDestino:'',
                razon_social_des:'',
                id_tienda_almacen:'',
                codigo:'',
                arrayVehiculos:[],
                id_vehiculo:0,
                arrayUsuario:[],
                selectUsuario:'',
                selectArray:[],
                selectAlmTda2:[],
                arrayVehucloX:[],
                selectVehucloX:[],
                arrayFalso:[],
                color:'',
                nro_chasis:'',
                  //---permisos_R_W_S
                  puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------             
            }

        },
        watch: {
            selectAlmTda: function (newValue) {
                let sucursalSeleccionadoD = this.arraySucursalAlmTda.find( 
        (element) => element.codigo === newValue );
    
    if (sucursalSeleccionadoD) {
        this.tipoCodigo = sucursalSeleccionadoD.tipoCodigo;
        this.codigoDestino = sucursalSeleccionadoD.codigo;
        this.razon_social_des=sucursalSeleccionadoD.razon_social;
        this.id_tienda_almacen=sucursalSeleccionadoD.id_tienda_almacen
        this.codigo=sucursalSeleccionadoD.codigo

    }
            },
        },
        computed:{
            
            sicompleto(){
                let me=this;
                if (me.matricula!='' && me.selectTipo!=0 && me.selectAlmTda!=0 && me.selectUsuario!=0 )
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

            sucursalAlmTda() {
            let me = this;
            var url = "/vehiculo/listarSucursal";
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
            caracteresPermitidosTelefono(ex){
                let me=this;
                if(ex.keyCode==32 || ex.keyCode==43 || ex.keyCode==8 || ex.keyCode == 45 || (ex.keyCode >= 48 && ex.keyCode <= 57) )
                {
                    me.telefono = me.telefono+ex.key;
                } 
            },  
          
            listarUsuario()
            {
            let me = this;
            var url = "/vehiculo/listarUsuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayUsuario = respuesta;
  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
            },
            listarVehiculoXtdaAlm(id)
            {
                   let me = this;
               
                   if (typeof id!=="undefined") {
                    var url = "/vehiculo/listarAsignar?id="+id;
             
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                   //  me.arrayVehucloX = respuesta.datos.data;
                   me.arrayVehucloX = respuesta;
                 
             
                   me.arraySucursalAlmTda.forEach(function(elemento1) {
  me.arrayVehucloX.forEach(function(elementoInterno) {
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
            listarVehiculo(page)
            {
                let me=this;
                var url='/vehiculo?page='+page+'&buscar='+me.buscar;
               
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayVehiculos = respuesta.resultadocombinado.data;
                })
                .catch(function(error){
                    error401(error);
                });
            },
      

            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarVehiculo(page);
            },
            
            registrar(){
                let me = this;
                  // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
               axios.post('/vehiculo/registrar',{
                    'id_tienda_almacen':me.id_tienda_almacen,
                    'matricula':me.matricula,
                    'razon_social_des':me.razon_social_des,
                    'codigo':me.codigo,
                    'telefono':me.telefono,
                    'selectTipo':me.selectTipo,
                    'activo':1,
                    'estado':1,
                    'id_emple':me.selectUsuario,
                    'nro_chasis':me.nro_chasis,
                    'color':me.color, 
                }).then(function(response){
                    me.cerrarModal('registrar');
                    Swal.fire(
                        'Registro exitosamente',
                        'Haga click en Ok',
                        'success'
                    )
                    me.listarVehiculo();
                    me.sucursalAlmTda();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
            },

            eliminar(id_vehiculo){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/vehiculo/desactivar',{
                        'id': id_vehiculo
                    }).then(function (response) {
                        me.listarVehiculo();
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarVehiculo();
                        
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
            activar(id_vehiculo){
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
                     axios.put('/vehiculo/activar',{
                        'id': id_vehiculo
                    }).then(function (response) {
                        me.listarVehiculo();
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
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
            actualizar(){
                let me =this;
              
                axios.put('/vehiculo/actualizar',{
                    'id':me.id_vehiculo,
                    'id_tienda_almacen':me.id_tienda_almacen,
                    'matricula':me.matricula,
                    'razon_social_des':me.razon_social_des,
                    'codigo':me.codigo,
                    'telefono':me.telefono,
                    'selectTipo':me.selectTipo,
                    'id_emple':me.selectUsuario,
                    'nro_chasis':me.nro_chasis,
                    'color':me.color,                   
                }).then(function (response) {
                    me.listarVehiculo();
                    Swal.fire(
                        'Actualizado Correctamente!',
                        'El registro a sido actualizado Correctamente',
                        'success'
                    )
                }).catch(function (error) {
                    error401(error);
                });
                me.cerrarModal('registrar');
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
  
      
                axios.post('/vehiculo/asignar',{
                    id:me.id_vehiculo,
                    bloque:cadena,
                    
                }).then(function (response) {
                    me.listarVehiculo();
                    me.arrayFalso=[];
                    me.cerrarModal1('registrar1')
                    Swal.fire(
                        'Asigno Correctamente!',
                        'El Accion realizada Correctamente',
                        'success'
                    )
                }).catch(function (error) {
                    error401(error);
                    me.arrayFalso=[];
                    me.cerrarModal1('registrar1')
                });
               
            },

             


            abrirModal(accion,data= []){
                let me=this;        
            
               
                switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Registar Nuevo Vehiculo'
                        me.isSubmitting=false;
                        me.tipoAccion=1;
                        me.tipo=0;
                        me.showModal = true;
                        
                        me.telefono='';
                        me.selectAlmTda=0;
                        me.selectTipo=0;                      
                        me.matricula='';
                        me.tipoCodigo='';
                        me.codigoDestino='';
                        me.razon_social_des='';
                        me.id_tienda_almacen='';
                        me.codigo='';
                        me.color='';
                        me.nro_chasis='';
                        me.selectUsuario=0;
                        me.classModal.openModal('registrar');
 
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.tituloModal='Registar Nuevo Vehiculo';
                        me.isSubmitting=false;
                        me.tipoAccion=2;                        
                        me.tipo=0; 
                        me.showModal = true;
                        me.telefono=data.telefono;
                        me.id_vehiculo=data.id;
                        me.selectAlmTda=data.codigo==null?0:data.codigo;
                        me.selectTipo=data.tipo==null?0:data.tipo;                      
                        me.matricula=data.matricula;
                        me.tipoCodigo='';
                        me.codigoDestino='';
                        me.razon_social_des=data.razon_social;
                        me.id_tienda_almacen=data.idsucursal;
                        me.codigo=data.codigo;
                        me.color=data.color;
                        me.nro_chasis=data.nro_chasis;
                        me.selectUsuario=data.id_emple==null?0:data.id_emple;                        
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'asignar':
                    {  
                                          
                         me.tituloModal='Asignar sucursal';
  
                         me.showModal_2 = true;
                       me.selectAlmTda2=me.arrayFalso;
                        me.id_vehiculo=data.id;
                        me.classModal.openModal('registrar1');
                   
                        break;

                    }

                }
                
            },

            cerrarModal(accion){
                let me = this;
                me.isSubmitting=false;
                me.showModal = false;
                me.classModal.closeModal(accion);
                me.tipoAccion=1;
                me.tipo=0;  
                me.telefono='';
                me.selectAlmTda=0;
                        me.selectTipo=0;                      
                        me.matricula='';
                        me.tipoCodigo='';
                        me.codigoDestino='';
                        me.razon_social_des='';
                        me.id_tienda_almacen='';
                        me.codigo='';
                        me.color='';
                        me.nro_chasis='';
                        me.selectAlmTda2=[];
                me.arrayVehucloX=[];
                        me.selectUsuario=0;
            },
            cerrarModal1(accion){
                let me = this;
                me.showModal_2 = false;
                me.tipoAccion=1;
                me.id_vehiculo="";
                me.arrayFalso=[];
                me.selectAlmTda2=[];
                me.arrayVehucloX=[];
                me.classModal.closeModal(accion);
              
             
               
            },

            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },

        },

        mounted() {
          //-------permiso E_W_S-----
            this.listarPerimsoxyz();        
            //-----------------------
       
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            this.classModal.addModal('registrar1');
            this.sucursalAlmTda();
            this.listarVehiculo(1);
            this.listarUsuario();
            this.listarVehiculoXtdaAlm();
     
        }
    }
</script>
<style scoped>
.error{
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