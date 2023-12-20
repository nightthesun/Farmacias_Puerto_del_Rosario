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
                    <i class="fa fa-align-justify"></i> Roles
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarRoles(1)">
                                <button type="submit" class="btn btn-primary" @click="listarRoles(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="role in arrayRoles" :key="role.id">
                                <td>
                                    <div v-if="role.nombre!='AdmSys'">
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',role)">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button v-if="role.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarRole(role.id)" >
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-info btn-sm" @click="activarRole(role.id)" >
                                            <i class="icon-check"></i>
                                        </button>
                                    </div>
                                    
                                </td>
                                    <td v-text="role.nombre"></td>
                                    <td v-text="role.descripcion"></td>
                                <td>
                                    <div v-if="role.activo==1">
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
                        <form action=""  class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre del Rol <span  v-if="nombre==''" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del Rol" v-model="nombre" v-on:focus="selectAll" >
                                    <span  v-if="nombre==''" class="error">Debe Ingresar el Nombre del Rol</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripcion </label>
                                <div class="col-md-9">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Nombre del Rol" v-model="descripcion" v-on:focus="selectAll" >
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12 form-control-label" for="text-input">Seleccionar Modulos y Ventanas de Acceso</label>
                            </div>
                            <div class=" col " v-for="modulo in arrayModulos" :key="modulo.id" >
                                <input type="checkbox" :value="modulo.id" v-model="seleccionados" :id="modulo.nombre" @change="seleccionarTodo(modulo.id)">&nbsp;
                                <strong :for="modulo.nombre" v-text="modulo.nombre"></strong><br>
                                <div class=" col" v-for="ventana in modulo.ventana" :key="ventana.id">
                                    <input type="checkbox" :value="ventana.id" v-model="ventanaseleccionados" :id="ventana.nombre">&nbsp;
                                    <label  :for="ventana.nombre" v-text="ventana.nombre"></label>
                                </div>  <br>
                            </div> <br>
                            
                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarRole()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRole()">Actualizar</button>
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
import {error401} from '../../errores';

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
                nombre:'',
                descripcion:'',
                arrayRoles:[],
                tituloModal:'',
                tipoAccion:1,
                idrole:'',
                buscar:'',
                areamedica:true,
                arrayModulos:[],
                seleccionados:[],
                ventanaseleccionados:[],
                accionesseleccionados:[],
            }

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
                if (me.nombre!='' && me.seleccionados.length!=0  && me.ventanaseleccionados.length!=0 )
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
            seleccionarTodo(idmod){
                let me=this;
                let res=me.seleccionados.find(el=>el==idmod)
                me.arrayModulos.forEach(element => {
                    if(element.id==idmod)
                        element.ventana.forEach(el => {
                            if(res)
                                me.ventanaseleccionados.push(el.id);
                            else
                            {
                                me.ventanaseleccionados = me.ventanaseleccionados.filter(function(elem) {
                                    return elem !== el.id; 
                                });
                            }
                        });
                    
                });
                

            },
            listarModulos(){
                let me=this;
                var url='/modulo';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    console.log(respuesta);
                    me.arrayModulos=respuesta.modulos;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

           
            listarRoles(page){
                let me=this;
                var url='/role?page='+page+'&buscar='+me.buscar;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arrayRoles=respuesta.roles.data;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarRoles(page);
            },
            registrarRole(){
                let me = this;
                let ventanas=me.ventanaseleccionados.toString();
                let modul=me.seleccionados.toString();                
                axios.post('/role/registrar',{
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'idventanas':ventanas,
                    'idmodulos':modul,
                }).then(function(response){
                    me.cerrarModal('registrar');
                    me.listarRoles();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },
            eliminarRole(idrole){
                let me=this;
                //console.log("prueba");
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
                     axios.put('/role/desactivar',{
                        'id': idrole
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarRoles();
                        
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
            activarRole(idrole){
                let me=this;
                //console.log("prueba");
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
                     axios.put('/role/activar',{
                        'id': idrole
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarRoles();
                        
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
            actualizarRole(){
               // const Swal = require('sweetalert2')
                let me =this;
                axios.put('/role/actualizar',{
                    'id':me.idrole,
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'idmodulos':me.seleccionados.toString(),
                    'idventanas':me.ventanaseleccionados.toString(),
                }).then(function (response) {
                    console.log("//////////////////////////");
                    if(response.status == 200)
                    {  
                       me.listarRoles();
                       Swal.fire('Actualizado Correctamente')
                    }
                    else{
                        //   Swal.fire('Actualizado Correctamente')
                        //   me.listarRoles();
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
                        me.tituloModal='Registar Role'
                        me.tipoAccion=1;
                        me.nombre='';
                        me.descripcion='';
                        me.areamedica=true;
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        console.log(data);
                        me.idrole=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Role'
                        me.nombre=data.nombre;
                        me.descripcion=data.descripcion;
                        me.seleccionados=data.idmodulos.split(',');
                        me.ventanaseleccionados=data.idventanas.split(',');
                        //me.accionesseleccionados=data.idacciones.split(',');
                        me.classModal.openModal('registrar');
                        break;
                    }

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.nombre='';
                me.descripcion='';
                me.tipoAccion=1;
                me.seleccionados=[];
                me.ventanaseleccionados=[];
                me.accionesseleccionados=[];
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  


        },
        mounted() {
            this.listarModulos();
            this.listarRoles(1);
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
