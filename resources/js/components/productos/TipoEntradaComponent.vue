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
                    <i class="fa fa-align-justify"></i> Tipo de Entrada
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="valorBuscar"  @keyup.enter="buscar">
                                <button type="submit" class="btn btn-primary" @click="buscar"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-10">Nombre</th>
                                <th class="col-md-1">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="tipoentrada in arrayTipoEntrada" :key="tipoentrada.id">
                                <td class="col-md-1">
                                    <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',tipoentrada)" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                         </div>
                                         <div v-else>
                                            <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                         </div>
                                         <div v-if="puedeActivar==1">
                                            <button v-if="tipoentrada.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarTipoEntrada(tipoentrada.id)" style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarTipoEntrada(tipoentrada.id)" style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="tipoentrada.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                    </div>        
                                    
                                   
                                </td>
                                <td v-text="tipoentrada.nombre" class="col-md-10"></td>
                                <td class="col-md-1">
                                    <div v-if="tipoentrada.activo==1">
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
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre: <span  v-if="!sinombre" class="error">(*)</span></label>
                            <div class="col-md-9">
                               <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del Tipo de Entrada" v-model="nombre" v-on:focus="selectAll" @keyup.enter="tipoAccion==1?registrarTipoEntrada():actualizarTipoEntrada()">
                               <span  v-if="!sinombre" class="error">Debe Ingresar el Nombre del Tipo de Entrada</span>
                            </div>
                        </div>                            
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarTipoEntrada()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarTipoEntrada()" :disabled="!sicompleto">Actualizar</button>
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
                isSubmitting: false, // Controla el estado del botón de envío
                idtipoentrada:0,
                nombre:'',
                arrayTipoEntrada:[],
                arrayTipoEntradaData:[],
                arrayTipoEntradaDataCopia:[],
                tituloModal:'',
                tipoAccion:1,
                valorBuscar:'', 
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------   
                showModal: false,         
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
                if (me.tipoAccion == 2) {
                    if (me.nombre !='')
                        return true;
                    else
                        return false;   
                } else {
                    if (me.nombre!='')
                        return true;
                    else
                        return false;   
                }
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

            listarTipoEntrada(page){
                let me=this;
                var url='/tipoentrada?page='+page;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arrayTipoEntrada=respuesta.tipoentrada.data;
                    me.arrayTipoEntradaData = respuesta.tipoentrada_data;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            buscar(){
                let me = this;
                me.arrayTipoEntradaDataCopia = me.arrayTipoEntradaData;
                let arrayAux = [];
                if(me.valorBuscar == ''){
                    me.arrayTipoEntrada = me.arrayTipoEntradaDataCopia;
                }else{
                    me.arrayTipoEntrada.forEach(tipoentrada => {
                        if(tipoentrada.nombre.toLowerCase().includes(me.valorBuscar.toLowerCase())){
                            arrayAux.push(tipoentrada);
                        }
                    });
                    me.arrayTipoEntrada = arrayAux;
                }
            },

            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarTipoEntrada(page);
            },

            registrarTipoEntrada(){
                let me = this;
                // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                axios.post('/tipoentrada/registrar',{'nombre':me.nombre})
                .then(function(response){
                    Swal.fire('Tipo de Entrada almacenado Exitosamente!')
                    me.cerrarModal('registrar');
                    me.listarTipoEntrada(1);
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                }) .finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });

            },

            actualizarTipoEntrada(){
                let me =this;
                axios.put('/tipoentrada/actualizar',{
                    'id':me.idtipoentrada,
                    'nombre':me.nombre,
                }).then(function (response) {
                    Swal.fire('Tipo de Entrada Actualizado Exitosamente!');
                    me.listarTipoEntrada(1);
                }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                me.cerrarModal('registrar');
            },


            eliminarTipoEntrada(idtipoentrada){
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
                     axios.put('/tipoentrada/desactivar',{
                        'id': idtipoentrada,
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarTipoEntrada(1);
                        
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

            activarTipoEntrada(idtipoentrada){
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
                     axios.put('/tipoentrada/activar',{
                        'id': idtipoentrada,
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarTipoEntrada(1);
                        
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
                        me.tipoAccion=1;
                        me.isSubmitting=false;
                        me.tituloModal='Actualizar Nombre de Tipo de Entrada';
                        me.nombre='';
                        me.showModal = true;
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.tipoAccion=2;
                        me.isSubmitting=false;
                        me.tituloModal='Actualizar Nombre de Tipo de Entrada';
                        me.idtipoentrada=data.id;
                        me.nombre=data.nombre;
                        me.showModal = true;
                        me.classModal.openModal('registrar');
                        break;
                    }
                } 
            },

            cerrarModal(accion){
                let me = this;
                me.isSubmitting=false;
                me.showModal = false;
                me.nombre='';
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
          //    this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
            this.listarTipoEntrada(1);
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            //console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.error
{
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
