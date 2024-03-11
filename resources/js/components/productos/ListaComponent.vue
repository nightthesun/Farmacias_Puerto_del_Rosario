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
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar',listarAlmTienda())">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarAlmacenes(1)">
                                <button type="submit" class="btn btn-primary" @click="listarAlmacenes(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo</th>
                                <th>Nombre</th>                                
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <!--
<tbody>
                            <tr v-for="almacen in arrayAlmacenes" :key="almacen.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',almacen)">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <button v-if="almacen.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarAlmacen(almacen.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarAlamcen(almacen.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                </td>
                                <td v-text="almacen.codigo"></td>
                                <td>{{ almacen.razon_social }} <br> {{ almacen.tipo }} {{ almacen.tipo == 'Sucursal'? almacen.correlativo:'' }}</td>
                                <td v-text="almacen.nombre_almacen"></td>
                                <td v-text="almacen.telefono"></td>
                                <td v-text="almacen.direccion"></td>
                                <td v-text="almacen.departamento"></td>
                                <td>{{ almacen.ciudad }}</td>
                                <td>
                                    <div v-if="almacen.activo==1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning">Desactivado</span>
                                    </div>
                                    
                                </td>
                            </tr>
                           
                        </tbody>

                        -->
                        
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
                                    <option
                                        v-for="sucursal in arrayAlmTienda"
                                        :key="sucursal.codigo"
                                        :value="sucursal.codigo"
                                        v-text="sucursal.codigoS +
                                            ' -> ' + sucursal.codigo+' ' +
                                            sucursal.razon_social"></option>
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
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAlmacen()">Actualizar</button>
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

                tituloModal:'',    
                tipoAccion:1,          
                selectAlmTienda:0,
                arrayAlmTienda:[],
                nombreLista:'',
                codigo:'',
                lista_id_almacen_id_tienda:'',




                nombre:'',
                tipo:0,
                nit:'',
                direccion:'',
                arraySucursales:[],
                tituloModal:'',
                tipoAccion:1,
                idsucursal:'',
                buscar:'',
                razonsocial:'',
                nombrealmacen:'',
                telefono:'',
                
                arrayciudad:[
                                {'id':1,'valor':'La Paz'},
                                {'id':2,'valor':'Santa Cruz'},
                                {'id':3,'valor':'Cochabamba'},
                                {'id':4,'valor':'Oruro'},
                                {'id':5,'valor':'Potosi'},
                                {'id':6,'valor':'Sucre'},
                                {'id':7,'valor':'Tarija'},
                                {'id':8,'valor':'Pando'},
                                {'id':9,'valor':'Beni'},
                            ],
                matriz:0,
                arrayRubros:[],
                idrubro:0,
                sucursalSeleccionado:0,
                departamento:0,
                ciudad:'',
                idalmacen:0,
                arrayCiudad:[],
                arrayDepto:[],
                arrayAlmacenes:[]
                
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
                    }
        }
       }, 
        methods :{
            listarAlmTienda() {
            let me = this;
            var url = "/lista/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmTienda = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        registrarLista(){
                let me = this;
                console.log( me.codigo+' '+ me.lista_id_almacen_id_tienda+' '+me.nombreLista);
                axios.post('/lista/registrar',{
                    'codigo':me.codigo,
                    'lista_id_almacen_id_tienda':me.lista_id_almacen_id_tienda,
                    'nombreLista':me.nombreLista                    
                }).then(function(response){
                    me.cerrarModal('registrar');
                    Swal.fire(
                        'Almacen Registrado exitosamente',
                        'Haga click en Ok',
                        'success'
                    )

                    ///añadir lista
                   // me.listarAlmacenes();
                   // me.listarSucursales();
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













            caracteresPermitidosTelefono(ex){
                let me=this;
                if(ex.keyCode==32 || ex.keyCode==43 || ex.keyCode==8 || ex.keyCode == 45 || (ex.keyCode >= 48 && ex.keyCode <= 57) )
                {
                    me.telefono = me.telefono+ex.key;
                } 
            },  

            selectDepartamentos(){
                let me=this;
                var url='/depto/selectdepto';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayDepto=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            listarSucursales(page){
                let me=this;
                var url='/sucursal?page='+page+'&buscar='+me.buscar;
                axios.get(url)
                .then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arraySucursales=respuesta.sucursales.data;
                    let resp=me.arraySucursales.find(element=>element.tipo=='Casa_Matriz');
                    if(resp!= undefined)
                    {
                        if(resp.tipo=='Casa_Matriz')
                            me.matriz=1;
                        else
                            me.matriz=0;
                    }
                    else
                        me.matriz=0;
                })
                .catch(function(error){
                    error401(error);
                });
            },

            listarAlmacenes(page)
            {
                let me=this;
                var url='/almacen?page='+page+'&buscar='+me.buscar;
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayAlmacenes = respuesta.almacenes.data;
                })
                .catch(function(error){
                    error401(error);
                });
            },

            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarAlmacenes(page);
            },
            
            registrarAlmacen(){
                let me = this;
                axios.post('/almacen/registrar',{
                    'idsucursal':me.sucursalSeleccionado,
                    'nombre_almacen':me.nombrealmacen,
                    'telefono':me.telefono,
                    'direccion':me.direccion,
                    'departamento':me.departamento,
                    'ciudad':me.ciudad,
                    'activo':1,
                    'estado':1,
                }).then(function(response){
                    me.cerrarModal('registrar');
                    Swal.fire(
                        'Almacen Registrado exitosamente',
                        'Haga click en Ok',
                        'success'
                    )
                    me.listarAlmacenes();
                    me.listarSucursales();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            eliminarAlmacen(idalmacen){
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
                     axios.put('/almacen/desactivar',{
                        'id': idalmacen
                    }).then(function (response) {
                        me.listarAlmacenes();
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
            activarAlamcen(idalmacen){
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
                     axios.put('/almacen/activar',{
                        'id': idalmacen
                    }).then(function (response) {
                        me.listarAlmacenes();
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
            actualizarAlmacen(){
                let me =this;
                axios.put('/almacen/actualizar',{
                    'id':me.idalmacen,
                    'idsucursal':me.sucursalSeleccionado,
                    'nombre_almacen':me.nombrealmacen,
                    'telefono':me.telefono,
                    'direccion':me.direccion,
                    'departamento':me.departamento,
                    'ciudad':me.ciudad,
                }).then(function (response) {
                    me.listarAlmacenes();
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

            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Registar Nueva Lista'
                        me.tipoAccion=1;
                        me.tipo=0;
                        me.selectAlmTienda=0;
                        me.nombreLista='';
                        me.codigo='',
                        me.lista_id_almacen_id_tienda='',
                        
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.sucursalSeleccionado=data.idsucursal===null?0:data.idsucursal;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Datos del Almacen';
                        me.tipo=data.tipo;
                        me.nombrealmacen=data.nombre_almacen;
                        me.telefono=data.telefono;
                        me.direccion=data.direccion;
                        me.ciudad=data.ciudad;
                        me.departamento=data.departamento;
                        me.idalmacen=data.id;
                        me.classModal.openModal('registrar');
                        break;
                    }

                }
                
            },

            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.tipoAccion=1;
                me.tipo=0;
                me.nombrealmacen='';
                me.telefono='';
                me.nit='';
                me.direccion='';
                me.ciudad=0;
                me.tipoAccion=1;
                me.idrubro=0;                
            },

            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },

            selectRubros(){
                let me=this;
                var url='/rubro/selectrubro';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayRubros=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
        },

        mounted() {
            this.selectRubros();
            this.listarAlmacenes(1);
            this.listarSucursales(1);
            this.selectDepartamentos();
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
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