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
                    <i class="fa fa-align-justify"></i>Clasificacion Estantes
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')" :disabled="almacenSelected==0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span  v-if="almacenSelected==0" class="error"> &nbsp; &nbsp;Debe Seleccionar una Sucursal</span>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <strong>Seleccionar Almacen:</strong>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" @change="listarEstantes(1,buscar)" name="" id="" v-model="almacenSelected">
                                <option disabled value="0">Seleccionar...</option>
                                <option v-for="alamcen in almacenes" :key="alamcen.id" v-text="alamcen.codigo + ' ' + alamcen.nombre_almacen" :value="alamcen.id"></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarEstantes(1)">
                                <button type="submit" class="btn btn-primary" @click="listarEstantes(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo Estante</th>
                                <th>Letra Asignada</th>
                                <th>Num. Posiciones</th>
                                <th>Altura</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="estante in arrayEstantes" :key="estante.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',estante)">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <button v-if="estante.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarEstante(estante.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarEstante(estante.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" @click="imprimirCodificacion(estante.id)" style="margin-left: 8px;">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm"  @click="abrirModal('ubicacionproductos',estante)" style="margin-left: 8px; background: #8e44ad;">
                                        <i class="fa fa-delicious" aria-hidden="true" style="color: white;"></i>
                                    </button>
                                </td>
                                <td v-text="estante.codestante"></td>
                                <td v-text="estante.letraestante"></td>
                                <td v-text="estante.numposicion"></td>
                                <td v-text="estante.numaltura"></td>
                                <td>
                                    <div v-if="estante.activo==1">
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
            <div class="modal-dialog modal-primary modal-sm" role="document">
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
                                <label class="col-md-12 form-control-label" for="text-input">Letra Asignada:  <strong style="font-size:40px">{{ letra }}</strong></label>
                               
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 form-control-label" for="text-input">Numero de Posiciones: <span  v-if="numposicion==0" class="error">(*)</span></label>
                                <div class="col-md-6">
                                    <input type="number" id="numposicion" name="numposicion" class="form-control"  v-model="numposicion" v-on:focus="selectAll" style="text-align:right" >
                                    <span  v-if="numposicion==0" class="error">Debe Ingresar Numero de Posiciones</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 form-control-label" for="text-input">Altura: <span  v-if="numaltura==0" class="error">(*)</span></label>
                                <div class="col-md-6">
                                    <input type="number" id="numaltura" name="numaltura" class="form-control"  v-model="numaltura" v-on:focus="selectAll" style="text-align:right" >
                                    <span  v-if="numaltura==0" class="error">Debe Ingresar la Altura del Estante</span>
                                </div>
                            </div>
                            <div style="text-align:center">
                                <strong style="font-size:30px">Codigo: {{ codestante }}</strong>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEstante()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEstante()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        
        <!-- Inicio Modal para la ubicacion de productos en un estante -->
        <div class="modal fade" role="dialog" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ tituloModal}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cerrarModal('staticBackdrop')">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
                </div>
            </div>
        </div>
        <!-- Fin modal ubicacion de productos en un estante  -->
        
    </main>
</template>

<script>
//import * as plugin from '../../functions.js';
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
                nombre:'',
                descripcion:'',
                codigo:'',
                correlativo:0,
                arrayEstantes:[],
                tituloModal:'',
                tipoAccion:1,
                idestante:'',
                buscar:'',
                demora:7,

                arraySucursal:[],
                sucursalSelected:0,
                letra:'',
                numletra:0,
                numposicion:0,
                numaltura:0,
                codsucursal:'',
                codestante:'',
                almacenes:[],
                almacenSelected:0,
            }
        },

        computed:{
            sicompleto(){
                let me=this;
                if (me.numaltura!=0 && me.numposicion!=0)
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
            imprimirCodificacion(idestante){
                console.log(idestante);
                let me=this;
                /////////////////////birt
                /* var url=url='/imprimir_codigos?idestante='+ idestante;
                console.log(url);
                plugin.viewPDF(url,'Codigos Estante'); */
                ///////////////////////////////////////
                
                var url='/imprimir_codigos?idestante='+ idestante+'&lista='+0;
                
                //console.log(url);
                window.open(url, '_blank');

            },

            listarEstantes(page){
                let me=this;
                var url='/estante?page='+page+'&buscar='+me.buscar+'&idalmacen='+me.almacenSelected;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    console.log(respuesta);
                    me.pagination=respuesta.pagination;
                    me.arrayEstantes=respuesta.estantes.data;
                    me.letra=respuesta.letra;
                    me.numletra=respuesta.numletra;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarEstantes(page);
            },

            registrarEstante(){
                let me = this;
                axios.post('/estante/registrar',{
                    'idalmacen':me.almacenSelected,
                    'codestante':me.codestante,
                    'letraestante':me.letra,
                    'numletra':me.numletra,
                    'numposicion':me.numposicion,
                    'numaltura':me.numaltura
                }).then(function(response){
                    me.cerrarModal('registrar');
                    me.listarEstantes();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },

            eliminarEstante(idestante){
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
                     axios.put('/estante/desactivar',{
                        'id': idestante
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarEstantes();
                        
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
            activarEstante(idestante){
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
                     axios.put('/estante/activar',{
                        'id': idestante
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarEstantes();
                        
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
            actualizarEstante(){
               // const Swal = require('sweetalert2')
                let me =this;
                axios.put('/estante/actualizar',{
                    'id':me.idestante,
                    'numposicion':me.numposicion,
                    'numaltura':me.numaltura
                    
                }).then(function (response) {
                    if(response.data.length){
                    }
                    // console.log(response)
                    else{
                            Swal.fire('Actualizado Correctamente')

                        me.listarEstantes();
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
                        let resp=me.almacenes.find(element=>element.id==me.almacenSelected);
                        me.codsucursal=resp.codigo;
                        me.tituloModal='Registar Estante '
                        me.tipoAccion=1;
                        me.codestante=me.codsucursal+me.letra;
                        me.numposicion=0;
                        me.numaltura=0;
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.idestante=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Estante'
                        me.numposicion=data.numposicion;
                        me.numaltura=data.numaltura;
                        me.letra=data.letraestante;
                        me.codestante=data.codestante;
                        me.classModal.openModal('registrar');
                        break;
                    }

                    case 'ubicacionproductos':
                    {
                        me.idestante=data.id;
                        me.tipoAccion=3;
                        me.tituloModal='Ubicacion de Productos en Estante'
                        me.numposicion=data.numposicion;
                        me.numaltura=data.numaltura;
                        me.letra=data.letraestante;
                        me.codestante=data.codestante;
                        me.classModal.openModal('staticBackdrop');
                        break;
                    }

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.codsucursal='';
                me.codestante='';
                me.tipoAccion=1;
                me.numposicion=0;
                me.numaltura=0;
                me.letra='';
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  
            selectSucursal(){
                let me=this;
                var url='/sucursal/selectsucursal';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arraySucursal=respuesta;
                    
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            listarAlmacenes(){
                let me = this;
                var url = '/almacen'
                axios.get(url)
                .then(function(response){
                    me.almacenes = response.data.almacenes.data;
                })
                .catch(function(error){
                    console.log(error);
                })
            }


        },
        mounted() {
            this.selectSucursal(1);
            this.listarAlmacenes();
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            this.classModal.addModal('staticBackdrop');
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
