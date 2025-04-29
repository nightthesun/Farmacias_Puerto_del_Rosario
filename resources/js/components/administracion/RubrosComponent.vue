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
                    <i class="fa fa-align-justify"></i> Rubros
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarRubros(1)">
                                <button type="submit" class="btn btn-primary" @click="listarRubros(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Productos Perecederos</th>
                                <th>Tiene actividad economica</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="rubro in arrayRubros" :key="rubro.id">
                                <td>
                                    <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',rubro)" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div  v-else>
                                            <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="rubro.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarRubro(rubro.id)" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activarRubro(rubro.id)" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                        <div  v-else>
                                            <button v-if="rubro.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    
                                </td>
                                    <td v-text="rubro.nombre"></td>
                                    <td v-text="rubro.descripcion"></td>
                                    <td v-if="rubro.areamedica">Si</td>
                                    <td v-else>No</td>
                                    <td>
                                        <span v-if="rubro.codigo_activdad_siat===0 || rubro.codigo_activdad_siat===null">
                                            No tiene actividad
                                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: yellow; margin-left: 5px;"></span>
                                        </span>
                                        <span v-else>
                                            Si tiene actividad
                                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: green; margin-left: 5px;"></span>
                                        </span>
                                    </td>
                                <td>
                                    <div v-if="rubro.activo==1">
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
                        <form action="" class="form-horizontal">                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre <span  v-if="!sinombre" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre de la Rubro" v-model="nombre" v-on:focus="selectAll" >
                                    <span  v-if="!sinombre" class="error">Debe Ingresar el Nombre del Rubro</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese una Descripción" v-model="descripcion" v-on:focus="selectAll">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="form-control-label" for="areamedica">La Actividad o Rubro tiene productos perecederos? </label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox"   v-model="areamedica" name="areamedica" checked  >
                                </div>
                            </div>                         
                               
                            </div>
                        <!----------------------------------------datos añadidos para impuestos------------------------------------------->
                            <div class="card">
                                <div class="alert alert-warning" role="alert">
                            Esta parte solo es necesaria para homologar productos para siat de impuestos nacionales
                        </div>
                                <div class="card-body" v-if="arrayActEco.length>0">
                                    <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Asociar Actividad ecomica:</label>
                                <div class="col-md-9">
                                    <select name="" id="" v-model="selectActEco" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="a in arrayActEco" :key="a.codigo" :value="a.codigo" v-text="a.descripcion"></option>
                                    </select>                                 
                                </div>
                            </div>
                                </div>
                                <div v-else class="alert alert-danger" role="alert">
                                    No existe datos para esta seleccion, debe configurar en modulo de siat en conceptos 
                                </div>
                            </div>
                        </form>
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarRubro()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarRubro()">Actualizar</button>
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
      
        
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import obj from '../plugin_vue/Body_header.vue';
import {error401} from '../../errores.js';
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
                arrayRubros:[],
                tituloModal:'',
                tipoAccion:1,
                idrubro:'',
                buscar:'',
                areamedica:true,
                 //---permisos_R_W_S
                 puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------

                showModal: false,

                arrayActEco:[],
                selectActEco:'0',
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

            listarTipoActividad(){              
                let me=this;
                var url='/listarTipoActividad';
                axios.get(url).then(function(response){
                    var respuesta=response.data;                   
                    me.arrayActEco=respuesta;
                   
                })
                .catch(function(error){
                    error401(error);                    
                });
            },

            listarRubros(page){
                // obj.methods.actualizarTiempoSessionUsuario();    
                let me=this;
                var url='/rubro?page='+page+'&buscar='+me.buscar;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;                  
                    me.arrayRubros=respuesta.rubros.data;
                    console.log( me.arrayRubros);
                })
                .catch(function(error){
                    error401(error);                    
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarRubros(page);
            },
            registrarRubro(){
                let me = this;
                  // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                axios.post('/rubro/registrar',{
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'areamedica':me.areamedica,
                    'selectActEco':me.selectActEco,
                }).then(function(response){
                    let respuesta =response.data;
                    if (respuesta.length>0) {
                        Swal.fire("Error",""+respuesta,"error");
                    } else {
                        Swal.fire("Exitoso","Se registro corectamente.","success"); 
                    }
                    me.cerrarModal('registrar');
                    me.listarRubros();
                }).catch(function(error){
                    error401(error);
                 
                }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });

            },
            eliminarRubro(idrubro){
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
                     axios.put('/rubro/desactivar',{
                        'id': idrubro
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarRubros();
                        
                    }).catch(function (error) {
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
            activarRubro(idrubro){
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
                     axios.put('/rubro/activar',{
                        'id': idrubro
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarRubros();
                        
                    }).catch(function (error) {
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
            actualizarRubro(){
               // const Swal = require('sweetalert2')
                let me =this;
                axios.put('/rubro/actualizar',{
                    'id':me.idrubro,
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'areamedica':me.areamedica,
                    'selectActEco':me.selectActEco,
                    
                }).then(function (response) {
                    if(response.data.length){
                    }
                    // console.log(response)
                    else{
                            Swal.fire('Actualizado Correctamente')

                        me.listarRubros();
                    } 
                }).catch(function (error) {
                   
                });
                me.cerrarModal('registrar');


            },
            abrirModal(accion,data= []){
                let me=this;             
                switch(accion){
                    case 'registrar':
                    {
                        me.isSubmitting=false;
                        me.tituloModal='Registar Actividad o Rubro'
                        me.tipoAccion=1;
                        me.nombre='';
                        me.descripcion='';
                        me.areamedica=true;
                        me.showModal = true;
                        me.selectActEco="0";
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.isSubmitting=false;
                        me.idrubro=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Rubro'
                        me.nombre=data.nombre;
                        me.descripcion=data.descripcion;
                        me.areamedica=(data.areamedica === 1);
                        me.showModal = true;
                        me.selectActEco = data.codigo_activdad_siat == null ? "0" : String(data.codigo_activdad_siat);   
                               
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
                me.areamedica=true;
                me.showModal = false;
                me.selectActEco=="0";
                
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
            this.listarRubros(1);
            this.listarTipoActividad();
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
