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
                    <i class="fa fa-align-justify"></i> Lista
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary" @click="abrirModal('registrar',listarAlmTienda())">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                       <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar" @keyup.enter="listarLista(1)">
                         <button type="button" class="btn btn-primary" @click="listarLista(1)"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                        </div>
                       
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-1">Codigo</th>
                                <th class="col-md-4">Nombre</th>
                                <th class="col-md-2">Tienda/Almacen</th>
                                <th class="col-md-2">Razon social</th>
                                <th class="col-md-1">Usuario</th>                                    
                                <th class="col-md-1">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lista in arrayLista" :key="lista.id">
                                <td class="col-md-1">  
                                    <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1" >
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',lista,listarAlmTienda())" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="lista.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminar(lista.id)" style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm"  @click="activar(lista.id)" style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="lista.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                    </div>                                         
                                   
                                </td>
                                <td v-text="lista.codigo_lista" class="col-md-1"></td>
                                <td v-text="lista.nombre_lista" class="col-md-4"></td>
                                <td v-text="lista.codigo_tda_alm" class="col-md-2"></td>
                                <td v-text="lista.razon_social" class="col-md-2"></td>
                                <td v-text="lista.user_name" class="col-md-1"></td>
                                <td class="col-md-1">
                                    <div v-if="lista.activo==1">
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
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <form action=""  class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Asignar Local/Tienda:
                                </label>
                                <div class="col-md-9">
                                    <select class="form-control"  v-model="selectAlmTienda">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arrayAlmTienda"
                                        :key="sucursal.codigo"
                                        :value="sucursal.codigo"
                                        v-text="sucursal.codigoS +
                                            ' -> ' + sucursal.codigo+' ' +
                                            sucursal.razon_social">
                                    </option>
                                    </select>
                                    
                                </div>
                            </div>
                           
                            <!-- Esto es para Nombre de lista -->
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre de lista: <span  v-if="nombreLista==''" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="tex" id="nombrealmacen" name="nombrealmacen" class="form-control"  v-model="nombreLista" v-on:focus="selectAll"  >
                                    <span  v-if="nombreLista==''" class="error">Debe Ingresar el nombre de lista</span>
                                </div>
                            </div>                           
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarLista()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        
        
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import { error401 } from '../../errores';
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
                buscar:'',
                tituloModal:'',    
                tipoAccion:1,          
                selectAlmTienda:0,
                arrayAlmTienda:[],
                nombreLista:'',
                codigo:'',
                lista_id_almacen_id_tienda:'',
                arrayLista:[],
                id_o:'',
                id_sucursal:'',
                 //---permisos_R_W_S
                 puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
            }

        },
        computed:{
            
            sicompleto(){
                let me=this;
                if (me.nombreLista!=''  && me.selectAlmTienda!=0 )
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
        watch:{
            selectAlmTienda: function(codigo){
            let sucursalAbuscar = this.arrayAlmTienda.find(
                    (element) => element.codigo === codigo);
                    if (sucursalAbuscar) {                        
                       this.codigo=sucursalAbuscar.codigo;
                       this.lista_id_almacen_id_tienda=sucursalAbuscar.lista_id_almacen_id_tienda;
                       this.id=sucursalAbuscar.sucursalAbuscar;
                       this.id_sucursal=sucursalAbuscar.id_sucursal;
                       
                    }
        }
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

            //then entonces
            listarLista(page){
                let me=this;
                var url="/lista?page="+page+"&buscar="+me.buscar;
                
                axios.get(url)
                     .then(function (response){

                        var respuesta = response.data; 
                        me.pagination = respuesta.pagination;
                        me.arrayLista=respuesta.resultadoCombinacion.data;
                       
                     })
                     .catch(function (error){
                        error401(error);
                     });   

            },
            listarAlmTienda() {
            let me = this;
            var url = "/lista/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmTienda = respuesta;
                   console.log(me.arrayAlmTienda); 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        registrarLista(){
                let me = this;
                
                axios.post('/lista/registrar',{
                    'codigo':me.codigo,
                    'lista_id_almacen_id_tienda':me.lista_id_almacen_id_tienda,
                    'nombreLista':me.nombreLista,
                    'id_sucursal':me.id_sucursal                    
                }).then(function(response){
                    me.cerrarModal('registrar');
                    Swal.fire(
                        'Almacen Registrado exitosamente',
                        'Haga click en Ok',
                        'success'
                    )

                    ///añadir lista
                    me.listarLista();
                   
                }).catch(function (error) {                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            });
            },

           cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarLista(page);
            },
            
       

            eliminar(id){
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
                     axios.put('/lista/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarLista();
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarSucursales();
                        
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
            activar(id){
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
                     axios.put('/lista/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarLista();
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
            

            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Registar Nueva Lista'
                        me.tipoAccion=1;
                        me.codigo_tda_alm="";
                        me.selectAlmTienda=0;
                        me.nombreLista='';
                        me.codigo='';
                        me.lista_id_almacen_id_tienda='';
                        
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                   
                        me.tipoAccion=2;
                        me.selectAlmTienda=data.codigo_tda_alm==null?0:data.codigo_tda_alm;
                        me.nombreLista=data.nombre_lista;
                        me.id_o=data.id;          
                        
                        me.lista_id_almacen_id_tienda=data.id_tda_alm;
                        me.classModal.openModal('registrar');
                        
                        break;
                    }

                }
                
            },
            actualizar(){
                let me =this;
                axios.put('/lista/actualizar',{
                    id:me.id_o,
                    codigo:me.selectAlmTienda,
                    lista_id_almacen_id_tienda:me.lista_id_almacen_id_tienda,
                    nombreLista:me.nombreLista,
                    id_sucursal:me.id_sucursal    
                    
                }).then(function (response) {
                    me.listarLista(1);
                    Swal.fire(
                        'Actualizado Correctamente!',
                        'El registro a sido actualizado Correctamente',
                        'success'
                    )
                }).catch(function (error) {                
                if (error.response.status === 500) {
                    me.listarLista(1);
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
                }else{
                    me.listarLista(1);
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            });
                me.cerrarModal('registrar');
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.tipoAccion=1;
                        me.tipo=0;
                        me.selectAlmTienda=0;
                        me.nombreLista='';
                        me.codigo='';
                        me.lista_id_almacen_id_tienda='';   
                        me.id_o='';           
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
            //  this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            this.listarLista(1);
            
        }
    }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>