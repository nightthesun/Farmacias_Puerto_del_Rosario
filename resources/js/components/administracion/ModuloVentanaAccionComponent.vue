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
                    <i class="fa fa-align-justify"></i> Modulos - Ventanas - Acciones
                    
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo Modulo
                    </button>
                
                </div>
                <div class="card-body">
                    <div class="table-responsive"> <!-- Agregamos esta clase para que la tabla sea responsive -->
                    <table class="table">
                      
                        <thead>
                          <tr>
                            
                            <th scope="col"  class="col-md-1">Modulo</th>
                            <th scope="col"  class="col-md-7">Ventan</th>
                            <th scope="col"  class="col-md-1">Opciones</th>
                            <th scope="col"  class="col-md-1">Estado</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="modulo in arrayModulos" :key="modulo.id">
                           
                            <td v-text="modulo.nombre"  class="col-md-1"></td>
                            <td  class="col-md-7">
                                <div v-if="puedeHacerOpciones_especiales==1">
                                    <button v-if="!modulo.mostrarventana" type="button" class="btn btn-success btn-sm" @click="expandirModulo(modulo.id)">
                                    Mostrar Modulo
                                </button> &nbsp;
                                <button v-else type="button" class="btn btn-warning btn-sm" @click="reducirModulo(modulo.id)">
                                    Cerrar Modulo
                                </button> &nbsp;
                                <button v-if="modulo.mostrarventana" type="button" class="btn btn-success btn-sm" @click="abrirModal('registrarventana',[],modulo.id)">
                                    Agregar Ventana
                                </button>&nbsp;
                                </div>
                                <div v-else>
                                    <button v-if="!modulo.mostrarventana" type="button" class="btn btn-light btn-sm" >
                                        Mostrar Modulo
                                    </button> &nbsp;
                                </div>
                                <div  v-show="modulo.mostrarventana" v-for="ventana in modulo.ventana" :key="ventana.id" >    
                            
                                    <label for="" class="col-md-1" style="text-align:right">-</label>
                                    <label for="" v-text="ventana.nombre" class="col-md-6"></label>
                                    <button v-if="!ventana.mostraraccion" type="button" class="btn btn-success btn-sm" @click="expandirVentana(modulo.id,ventana.id)">
                                        Mostrar Ventana
                                    </button> &nbsp;
                                    <button v-else type="button" class="btn btn-warning btn-sm" @click="reducirVentana(modulo.id,ventana.id)">
                                        Cerrar Ventana
                                    </button>&nbsp;
                                    <button v-if="ventana.mostraraccion" type="button" class="btn btn-success btn-sm" @click="abrirModal('registraraccion',[],ventana.id)">
                                        Agregar Accion
                                    </button>&nbsp;
                                    
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizarventana',ventana)">
                                        <i class="icon-pencil"></i>
                                    </button>&nbsp;
                                    <button v-if="ventana.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarModulo('ventana',ventana.id)" >
                                        <i class="icon-trash"></i>
                                    </button>&nbsp;
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarModulo('ventana',ventana.id)" >
                                        <i class="icon-check"></i>
                                    </button>&nbsp;
                                    
                                    <span v-if="ventana.activo" class="badge badge-success">Activo</span>
                                    <span v-else class="badge badge-warning">Desactivado</span>
                                        
                                   
                                    <div  v-show="ventana.mostraraccion" v-for="accion in ventana.accion" :key="accion.id" >    
                                        <label for="" class="col-md-2" style="text-align:right">-</label>
                                        <label for="" v-text="accion.nombre" class="col-md-6"></label>
                                        
                                        &nbsp;<button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizaraccion',accion)">
                                            <i class="icon-pencil"></i>
                                        </button>&nbsp;
                                        <button v-if="accion.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarModulo('accion',accion.id)" >
                                            <i class="icon-trash"></i>
                                        </button>&nbsp;
                                        <button v-else type="button" class="btn btn-info btn-sm" @click="activarModulo('accion',accion.id)" >
                                            <i class="icon-check"></i>
                                        </button>&nbsp;
                                        <span v-if="accion.activo" class="badge badge-success">Activo</span>
                                        <span v-else class="badge badge-warning">Desactivado</span>
                                   
                                    </div>
                                </div>
                            </td>
                            <td class="col-md-1">
                                <div class="d-flex justify-content-start">
                                    <div  v-if="puedeEditar==1">
                                        <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px;" @click="abrirModal('actualizar',modulo)">
                                            <i class="icon-pencil"></i>
                                        </button>
                                    </div>
                                    <div  v-else>
                                        <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;" >
                                            <i class="icon-pencil"></i>
                                        </button>
                                    </div>
                                   
                                    
                                    <div v-if="puedeActivar==1">
                                        <button v-if="modulo.activo==1" type="button" class="btn btn-danger btn-sm" style="margin-right: 5px;" @click="eliminarModulo('modulo',modulo.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-info btn-sm" style="margin-right: 5px;" @click="activarModulo('modulo',modulo.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </div>
                                    <div  v-else>
                                        <button v-if="modulo.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;" >
                                            <i class="icon-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            
                            
                            <td  class="col-md-1">
                                <span v-if="modulo.activo" class="badge badge-success">Activo</span>
                                <span v-else class="badge badge-warning">Desactivado</span>
                            </td>
                          </tr>
                         
                        </tbody>
                      </table>
                    </div>

                 
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
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">{{ etiqueta }} <span  v-if="!simodulo" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="modulo" name="modulo" class="form-control" placeholder="Nombre del Modulo" v-model="modulo" v-on:focus="selectAll" >
                                    <span  v-if="!simodulo" class="error">Debe Ingresar el Nombre del Modulo</span>
                                </div>
                            </div>
                            <div class="form-group row" v-if="tipomodal=='modulo'">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre de Icono para el modulo</label>
                                <div class="col-md-9">
                                    <input type="text" id="nomicono" name="nomicono" class="form-control" placeholder="Nombre del icono para el modulo ej: icon-bag" v-model="nombreicono" v-on:focus="selectAll" >
                                </div>
                            </div>
                            <div class="form-group row" v-if="tipomodal=='ventana'">
                                <label class="col-md-3 form-control-label" for="text-input">Template vue <span  v-if="templatevue==''" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="templatevue" name="templatevue" class="form-control" placeholder="Nombre del Modulo" v-model="templatevue" v-on:focus="selectAll" >
                                    <span  v-if="templatevue==''" class="error">Debe Ingresar el Nombre del Template</span>
                                </div>
                            </div>
                            <div class="form-group row" v-if="tipomodal=='accion'">
                                <label class="col-md-3 form-control-label" for="text-input">Metodo Vue <span  v-if="metodovue==''" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="metodovue" name="metodovue" class="form-control" placeholder="Nombre del Metodo Vue" v-model="metodovue" v-on:focus="selectAll" >
                                    <span  v-if="metodovue==''" class="error">Debe Ingresar el Nombre del Metodo Vue</span>
                                </div>
                            </div>
                            <div class="form-group row" v-if="tipomodal=='accion'">
                                <label class="col-md-3 form-control-label" for="text-input">Descripcion </label>
                                <div class="col-md-9">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Nombre del Metodo Vue" v-model="descripcion" v-on:focus="selectAll" >
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarModulo()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarModulo()">Actualizar</button>
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
import {error401} from '../../errores.js';

//Vue.use(VeeValidate);
    export default {
        //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
        data(){
            return{
                isSubmitting: false, // Controla el estado del botón de envío
                modulo:'',
                arrayModulos:[],
                tituloModal:'',
                tipoAccion:1,
                idmodulo:'',
                buscar:'',
                nombre:'',
                nombreicono:'',
                etiqueta:'Modulo:',
                tipomodal:'modulo',
                templatevue:'',
                metodovue:'',
                descripcion:'',
                idventana:'',
                idaccion:'',

                showModal: false,
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
           
            }

        },
        computed:{
            simodulo(){
                let me=this;
                if(me.modulo!='')
                    return true;
                else
                    return false;
            },
            // simodulo(){
            //     let me=this;
            //     if(me.modulo!='')
            //         return true;
            //     else
            //         return false;
            // },
            
            sicompleto(){
                let me=this;
                if (me.modulo!='' )
                    return true;
                else
                    return false;
            },
            isActived:function(){
                return this.pagination.current_page;
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
        });
},
//--------------------------------------------------------------


            expandirModulo(id){
                let me=this;
                me.arrayModulos.forEach(element => {
                    if(element.id==id)
                    {
                        element.mostrarventana=true;                        
                    }
                    else
                        element.mostrarventana=false;
                    
                });
               
            },


            reducirModulo(id){
                let me=this;
                me.arrayModulos.forEach(element => {
                    if(element.id==id)
                    {
                        element.mostrarventana=false;                        
                    }
                    
                });
              
            },
            expandirVentana(idmodulo,idventana){
                let me=this;
                me.arrayModulos.forEach(element => {
                    if(element.id==idmodulo){
                        element.ventana.forEach(el=>{
                            
                            if(el.id==idventana)
                                el.mostraraccion=true;
                            else
                                el.mostraraccion=false;
                        });
                    }
                });
            },

            reducirVentana(idmodulo,idventana){
                let me=this;
                me.arrayModulos.forEach(element => {
                    if(element.id==idmodulo){
                        element.ventana.forEach(el=>{
                            if(el.id==idventana)
                                el.mostraraccion=false;
                           
                        });
                    }
                });
            },

            
            listarModulos(page){
                let me=this;
                var url='/modulo?page='+page+'&buscar='+me.buscar;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
              
                    me.arrayModulos=respuesta.modulos;
                })
                .catch(function(error){
                    error401(error);
                });
            },
            registrarModulo(){
                let me = this;
           
                if(me.tipomodal=='modulo')
                {
                    axios.post('/modulo/registrar',{
                    'nombre':me.modulo,
                    'nombre_icono':me.nombreicono,
                    }).then(function(response){
                        me.cerrarModal('registrar');
                        me.listarModulos();
                    }).catch(function(error){
                        error401(error);
                    });
                }
                if(me.tipomodal=='ventana')
                {
                     // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                    axios.post('/ventana/registrar',{
                        'idmodulo':me.idmodulo,
                        'nombre':me.modulo,
                        'nomtemplate':me.templatevue,
                    }).then(function(response){
                        me.cerrarModal('registrar');
                        me.listarModulos();
                    }).catch(function(error){
                        error401(error);
                    }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
                }
                if(me.tipomodal=='accion')
                {
                    axios.post('/accion/registrar',{
                        'idventana':me.idventana,
                        'nombre':me.modulo,
                        'metodovue':me.metodovue,
                        'descripcion':me.descripcion
                    }).then(function(response){
                        me.cerrarModal('registrar');
                        me.listarModulos();
                    }).catch(function(error){
                        error401(error);
                    });
                }
                    

            },
            eliminarModulo(accion,idmodulo){
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
                    let url;
                    switch(accion){
                        case 'modulo':
                            {
                                url='/modulo/desactivar';
                                break;
                            }
                        case 'ventana':
                            {
                                url='/ventana/desactivar';
                                break;
                            }
                        case 'accion':
                            {
                                url='/accion/desactivar';
                                break;
                            }
                                
                    }
                    

                if (result.isConfirmed) {
                     axios.put(url,{
                        'id': idmodulo
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarModulos();
                        
                    }).catch(function (error) {
                        error401(error);
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
            activarModulo(accion,idmodulo){
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
                    let url;
                    switch(accion){
                        case 'modulo':
                            {
                                url='/modulo/activar';
                                break;
                            }
                        case 'ventana':
                            {
                                url='/ventana/activar';
                                break;
                            }
                        case 'accion':
                            {
                                url='/accion/activar';
                                break;
                            }
                                
                    }
                if (result.isConfirmed) {
                     axios.put(url,{
                        'id': idmodulo
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarModulos();
                        
                    }).catch(function (error) {
                        error401(error);
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
            actualizarModulo(){
               // const Swal = require('sweetalert2')
                let me =this;

                if(me.tipomodal=='modulo'){
                    axios.put('/modulo/actualizar',{
                        'id':me.idmodulo,
                        'nombre':me.modulo,
                        
                    }).then(function (response) {
                        if(response.data.length){
                        }
                        else{
                                Swal.fire('Actualizado Correctamente');
                            me.listarModulos();
                        } 
                    }).catch(function (error) {
                        error401(error);
                    });
                }
                if(me.tipomodal=='ventana')
                {
                    axios.put('/ventana/actualizar',{
                        'id':me.idventana,
                        'idmodulo':me.idmodulo,
                        'nombre':me.modulo,
                        'nomtemplate':me.templatevue,
                        
                    }).then(function (response) {
                        if(response.data.length){
                        }
                        else{
                                Swal.fire('Actualizado Correctamente');
                            me.listarModulos();
                        } 
                    }).catch(function (error) {
                        error401(error);
                    });
                }
                if(me.tipomodal=='accion'){
                    axios.put('/accion/actualizar',{
                        'id':me.idaccion,
                        'nombre':me.modulo,
                        'metodovue':me.metodovue,
                        'descripcion':me.descripcion
                        
                    }).then(function (response) {
                        if(response.data.length){
                        }
                        else{
                                Swal.fire('Actualizado Correctamente');
                            me.listarModulos();
                        } 
                    }).catch(function (error) {
                        error401(error);
                    });
                }
                
                me.cerrarModal('registrar');


            },
            abrirModal(accion,data= [],id){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Registrar Modulo';
                        me.isSubmitting=false;
                        me.tipomodal='modulo';
                        me.tipoAccion=1;
                        me.modulo='';
                        me.showModal = true;
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.isSubmitting=false;
                        me.showModal = true;
                        me.idmodulo=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Modulo';
                        me.modulo=data.nombre;
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'registrarventana':
                    {
                        me.showModal = true;
                        me.tituloModal='Registar Ventana'
                        me.tipoAccion=1;
                        me.etiqueta='Nombre Ventana: ';
                        me.tipomodal='ventana';
                        me.modulo='';
                        me.templatevue=''
                        me.idmodulo=id;
                        me.classModal.openModal('registrar');
                        break;

                    }
                     case 'actualizarventana':
                    {
                        me.showModal = true;
                        me.tituloModal='Actualizar Ventana'
                        me.tipoAccion=2;
                        me.etiqueta='Nombre Ventana: ';
                        me.tipomodal='ventana';
                        me.modulo=data.nombre;
                        me.templatevue=data.template
                        me.idventana=data.id;
                        me.classModal.openModal('registrar');
                        break;

                    }
                    case 'registraraccion':
                    {
                        me.showModal = true;
                        me.tituloModal='Registar Accion'
                        me.tipoAccion=1;
                        me.etiqueta='Nombre Accion: ';
                        me.tipomodal='accion';
                        me.modulo='';
                        me.metodovue=''
                        me.idventana=id;
                        me.classModal.openModal('registrar');
                        break;

                    }
                     case 'actualizaraccion':
                    {
                        me.showModal = true;
                        me.tituloModal='Actualizar Accion'
                        me.tipoAccion=2;
                        me.etiqueta='Nombre Accion: ';
                        me.tipomodal='accion';
                        me.modulo=data.nombre;
                        me.metodovue=data.metodo_vue
                        me.idaccion=data.id;
                        me.classModal.openModal('registrar');
                        break;

                    }

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.isSubmitting=false;
                me.classModal.closeModal(accion);
                me.modulo='';
                me.descripcion='';
                me.tipoAccion=1;
                me.templatevue=''
                me.showModal = false;
                me.areamedica=true;
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  


        },
        mounted() {
           
            this.listarModulos(1);
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            //-------permiso E_W_S-----
            this.listarPerimsoxyz();
            //-----------------------
        
        }
    }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;
    
}
hr{
    margin-top: 1px;
    margin-bottom: 1px;
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
