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
                    <i class="fa fa-align-justify"></i> Lineas y Marcas
                    <button type="button" v-if="puedeCrear==1"  class="btn btn-secondary" @click="abrirModal('registrar')" :disabled="idrubrofiltro==0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <div class="input-group">
                                <label class="form-control-label" style="margin-top:auto;">Seleccione Rubro:</label> 
                                <select v-model="idrubrofiltro" @change="listarLineas()" class="form-control" style="margin-left:8px;">
                                    <option value="0">Seleccionar</option>
                                    <option v-for="rubro in rubros" :key="rubro.id" :value="rubro.id" v-text="rubro.nombre"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group" v-if="idrubrofiltro != 0">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarLineas(1)">
                                <button type="submit" class="btn btn-primary" @click="listarLineas(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-1">Codigo</th>
                                <th class="col-md-2">Nombre</th>
                                <th class="col-md-2">Descripción</th>
                                <th class="col-md-1">Plazo de Entrega</th>
                                <th class="col-md-1">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="linea in arrayLineas" :key="linea.id">
                                <td class="col-md-1">
                                    <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',linea)" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="linea.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarLinea(linea.id)"  style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarLinea(linea.id)"  style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="linea.activo==1" type="button" class="btn btn-light btn-sm"   style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                    </div>        
                                   
                                   
                                </td>
                                <td v-text="linea.codigo" class="col-md-1"></td>
                                <td v-text="linea.nombre" class="col-md-2"></td>
                                <td v-text="linea.descripcion" class="col-md-2"></td>
                                <td v-text="linea.tiempo_demora + ' dias'" class="col-md-1"></td>
                                <td class="col-md-1">
                                    <div v-if="linea.activo==1">
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
                        <form action=""  class="form-horizontal">
                            <div class="form-group row" v-if="tipoAccion == 2">
                                <label class="col-md-3 form-control-label">Rubro:</label>
                                <div class="col-md-9">
                                    <select v-model="idrubroselected" @change="listarrubro()" class="form-control">
                                        <option value="0">Seleccionar</option>
                                        <option v-for="rubro in rubros" :key="rubro.id" :value="rubro.id" v-text="rubro.nombre"></option>
                                    </select>
                                    <span class="error" v-if="idrubroselected==0">Debe Seleccionar un rubro</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre <span  v-if="!sinombre" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre de la Linea o Marca" v-model="nombre" v-on:focus="selectAll" >
                                    <span  v-if="!sinombre" class="error">Debe Ingresar el Nombre de la Linea</span>
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Plazo de Entrega <span  v-if="!sidemora" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="number" id="demora" name="demora" class="form-control"  v-model="demora" v-on:focus="selectAll" style="padding-left: 15px;" >
                                    <span  v-if="!sidemora" class="error">Debe Ingresar el Plazo de Entrega</span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese una Descripción" v-model="descripcion" v-on:focus="selectAll">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarLinea()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarLinea()" :disabled="!sicompleto">Actualizar</button>
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
                nombre:'',
                descripcion:'',
                codigo:'',
                correlativo:0,
                arrayLineas:[],
                tituloModal:'',
                tipoAccion:1,
                idlinea:'',
                buscar:'',
                demora:7,
                idrubroselected:0,
                rubros:[],
                idrubrofiltro:0,
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
            sidemora(){
                let me=this;
                if(me.demora!='')
                    return true;
                else
                    return false;
            },

            sicompleto(){
                let me=this;
                if (me.tipoAccion == 2) {
                    if (me.nombre !='' && me.demora !='' && me.idrubroselected != 0)
                        return true;
                    else
                        return false;   
                } else {
                    if (me.nombre!='' && me.demora!='')
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
            listarLineas(page){
                let me=this;
                var url='/linea?page='+page+'&buscar='+me.buscar+'&idrubro='+me.idrubrofiltro;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arrayLineas=respuesta.lineas.data;
                    me.correlativo=respuesta.maxcorrelativo[0].maximo;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarLineas(page);
            },

            registrarLinea(){
                let me = this;
                    // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                axios.post('/linea/registrar',{
                    'idrubro':me.idrubrofiltro,
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'tiempo_demora':me.demora,
                }).then(function(response){
                    me.cerrarModal('registrar');
                    me.listarLineas();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });

            },

            listarrubro(){
                let me=this;
                var url='/rubro/selectrubro';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.rubros=respuesta.rubros;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            eliminarLinea(idlinea){
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
                     axios.put('/linea/desactivar',{
                        'id': idlinea
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarLineas();
                        
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

            activarLinea(idlinea){
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
                     axios.put('/linea/activar',{
                        'id': idlinea
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarLineas();
                        
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

            actualizarLinea(){
                let me =this;
                axios.put('/linea/actualizar',{
                    'id':me.idlinea,
                    'idrubro':me.idrubroselected,
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'tiempo_demora':me.demora
                    
                }).then(function (response) {
                    if(response.data.length){
                    }
                    // console.log(response)
                    else{
                            Swal.fire('Actualizado Correctamente')

                        me.listarLineas();
                    } 
                }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                me.cerrarModal('registrar');
            },

            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.tipoAccion=1;
                        me.isSubmitting=false;
                        me.tituloModal='Registar Lineas y Marcas'
                        me.idrubroselected=0;
                        me.nombre='';
                        me.descripcion='';
                        me.demora=7;
                        me.showModal = true;
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.tipoAccion=2;
                        me.showModal = true;
                        me.isSubmitting=false;
                        me.idlinea=data.id;
                        me.tituloModal='Actualizar Linea';
                        me.idrubroselected=data.idrubro;
                        me.nombre=data.nombre;
                        me.descripcion=data.descripcion;
                        me.demora=data.tiempo_demora;
                        me.classModal.openModal('registrar');
                        break;
                    }
                } 
            },

            cerrarModal(accion){
                let me = this;
                me.isSubmitting=false;
                me.classModal.closeModal(accion);
                me.nombre='';
                me.descripcion='';
                me.tipoAccion=1;
                me.demora=7;
                me.showModal = false;
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
            this.listarLineas(1);
            this.listarrubro();
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