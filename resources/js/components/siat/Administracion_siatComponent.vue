<template>
    <main class="main">
   <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <!-- inicio de index -->
        <div class="container-fluid">
    <div class="card">
        <div class="card-header">
           
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" @click="resert_0(1);listarIndex()" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-general" aria-selected="true">CUIS / CUFD</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" @click="resert_0(2);listar_inicio_v2();" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Sincronización SIAT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-comunicacion-tab" data-toggle="pill" href="#pills-comunicacion" role="tab" aria-controls="pills-comunicacion" aria-selected="false">Comunicación</a>
                </li>                         
            </ul>
        </div>
        <div class="card-body">           
             <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-general-tab">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                
    <span>Configuración administrativa de siat</span>
                                  
</div>


             
                            <div class="card-body">    
                                <div class="row">
                               
                                <div class="form-group col-sm-4">
                                    <button  type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-cube" aria-hidden="true"></i> Solicitar CUIS Para todos</button>
                                 </div> 
                                 <div class="form-group col-sm-4">
                                    <button  type="button" class="btn btn-info btn-sm btn-block" style="color: white;"><i class="fa fa-cubes" aria-hidden="true"></i> Solicitar CUFD Para todos</button>
                                </div>
                                <div class="form-group col-sm-4">
                                    <button  type="button" class="btn btn-warning btn-sm btn-block" style="color: white;"><i class="fa fa-clock-o" aria-hidden="true"></i> Programar CUFD</button>
                                </div>    
                                </div>    
                               
                              
                          
                            </div>
                            <!---inserte tabla-->
                            <div>
                                <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th >Opciones</th>
                        <th class="col-md-1">Sucursal siat</th>                       
                        <th class="col-md-1">Codigo siat</th>
                        <th class="col-md-1">Emisor</th>
                        <th class="col-md-1">Cuis</th>
                        <th class="col-md-4">Cufd</th>
                        <th class="col-md-1">Cuis vigencia</th>
                        <th class="col-md-1">Cufd vigencia</th>
                        <th class="col-md-1">Cuis Estado</th> 
                        <th class="col-md-1">Cufd Estado</th>     
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td >   
                        <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>     
  <div class="dropdown-menu">
      <a class="dropdown-item" href="#" @click="solicitarCuis(i.codigo_siat,i.id);"><i style="color: black;" class="fa fa-cube" aria-hidden="true"></i> Solicitar CUIS</a>
    <a  class="dropdown-item" href="#" @click="insertar_cufd(i.codigo_siat,i.cuis);"><i style="color: black;" class="fa fa-cubes" aria-hidden="true"></i> Solicitar CUFD</a>     
    <a  class="dropdown-item" href="#" @click="cerrarOperaciones(i.codigo_siat,i.id,i.cuis,i.id_cufd, i.id_cuis, i.id_emisor);"><i style="color: black;" class="fa fa-refresh" aria-hidden="true"></i> Cierre de operaciones (inhabilita el CUIS y el CUFD)</a>   
     </div>                   
                        </td>
                        <td class="col-md-1">{{ i.nombre_suc_siat }}</td>
                        <td class="col-md-1">{{ i.codigo_siat }}</td>
                        <td class="col-md-1">{{ emisor }}</td>
                        <td class="col-md-1">{{ i.cuis }}</td>
                        <td class="col-md-4">{{ i.cufd }}</td>
                        <td class="col-md-1">{{ i.fecha_v_cuis }}</td>
                        <td class="col-md-1">{{ i.fecha_v_cufd }}</td>
                        <td class="col-md-1">
                            <span v-if="i.estado_cuis===null || i.estado_cuis===''" class="badge badge-pill badge-secondary">Vacio</span>
                            <span v-else-if="i.estado_cuis===1" class="badge badge-pill badge-success">Activado</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span>
                        </td>                      
                        <td class="col-md-1">
                            <span v-if="i.estado_cufd===null || i.estado_cufd===''" class="badge badge-pill badge-secondary">Vacio</span>
                            <span v-else-if="i.estado_cufd===1" class="badge badge-pill badge-success">Activado</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span>                        
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
            <!-----fin de tabla------->
           
                            </div>
           

                          
                        </div>
                        
                    </div>
<!--------------------------------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------------------------------->                    
<!--------------------------------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------------------------------->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        <div class="card">
                            <div class="card-header">
                                <span>Configuración de sincronización</span>
                            </div>
                            
                            <div class="alert alert-success" v-if="evento_manual_automatico===0" role="alert">
                               <h3><strong>Activación manual</strong></h3> 
                            </div>
                            <div class="alert alert-warning " v-else-if="evento_manual_automatico===1" role="alert">
                                <h3><strong>Activación automatico</strong></h3>
                            </div>
                            <div class="alert alert-danger" v-else role="alert">
                               <h3><strong>Sin activación</strong></h3> 
                            </div>
                            <div class="card-body">
                                <div class="row">
                                <div class="form-group col-sm-1">
                                    <span>Tipo activacion: </span> 
                                </div> 
                                <div class="form-group col-sm-4">
                                     <select  class="form-control"  v-model="selectAutoManual">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Manual</option>
                                            <option value="2">Automatico</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <button v-if="selectAutoManual==='0'" type="button" class="btn btn-Secondary btn-sm btn-block"><i class="fa fa-star" aria-hidden="true"></i> Activar</button>
                                    <button v-else type="button" @click="activarModo()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-star" aria-hidden="true"></i> Activar</button>
                                 </div> 
                                 <div class="form-group col-sm-3">
                                    <button  v-if="evento_manual_automatico===0" @click="abrirModal('manual'); listar_emisor_v()" type="button" class="btn btn-success btn-sm btn-block" style="color: white;"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> Sincronización manual</button>
                                    <button  v-else-if="evento_manual_automatico===1" @click="abrirModal('auto_v'); listarAuto_sicro() " type="button" class="btn btn-warning btn-sm btn-block" style="color: white;"><i class="fa fa-simplybuilt" aria-hidden="true"></i> Sincronización automatico</button>
                                </div>   
                                </div>    
                               
                            </div>
                    
                                <table class="table table-bordered table-striped table-sm table-responsive" :hidden="arrayInicio.length<=0">
                                    <thead>
                                        <tr>
                                        <th>Numero</th>
                                        <th>tipo</th>
                                        <th>Descripción</th>                       
                                        <th>Fecha / hora</th>
                                        <th>Estado</th>                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="i in arrayInicio" :key="i.id">
                                            <td>{{i.id}}</td>
                                            <td>{{i.tipo}}</td>
                                            <td>{{i.descripcion}}</td>
                                            <td>{{i.created_at}}</td>
                                            <td>
                                                <span class="badge badge-pill badge-success" v-if="i.estado===1">APROBADA</span>
                                                <span class="badge badge-pill badge-danger" v-else>OBSERVADO!</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                        </div>
                    </div>        
                    <!---------------------------------------------------------------------------------------------------------------------------->
               
                    <div class="tab-pane fade" id="pills-comunicacion" role="tabpanel" aria-labelledby="pills-comunicacion-tab">
                        <div class="card">
                            <div class="card-header">
                                Servicios
                            </div>
                           
    <div class="card-body">
        <div class="row">    
                                    <div class="form-group col-sm-1">
                                        <strong>verificar Comunicacion:</strong>
                                   </div> 
                                <div class="form-group col-sm-4">
                                    <button  type="button" class="btn btn-light"  ><i class="fa fa-code-fork" aria-hidden="true"></i> Verificar</button>
                                </div>     
                            </div>   
        <div class="form-group row">
         
        </div> 
    </div>
  
                        </div>
                    </div>        
<!---------------------------------------------------------------------------------------------------------------------------->
            </div>            
        </div>
    </div>
</div>
   
 <!--Inicio del modal manual-->
 <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="manual" aria-hidden="true"
            data-backdrop="static" data-key="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('manual')">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">                       
                        <form action="" class="form-horizontal">                        
                            <!-- insertar datos -->
                            <div class="container">  
                                <div class="card">
  <div class="card-header">
    Opción 1
  </div>
  <div class="card-body">
    <div class="form-group row" >                                   
                                    <div class="col-md-2">
                                        <label>Emisor: </label>                                     
                                    </div>
                                    <div class="col-md-7">
                                        <select  class="form-control"  v-model="selectEmisor_v">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option v-for="a in arrayListarEmisor_v" :key="a.id"  :value="a.id">
                                                {{ "Emisor: " +a.nombre_emisor+" - Sucursal siat: "+a.nombre_suc_siat+" - Razon social: "+a.razon_social }}
                                            </option>                                     
                                    </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" v-if="isSubmitting===false" class="btn btn-primary rounded" :disabled="selectEmisor_v==='0'" @click="sinCronizacion()">Sincronizar</button>                        
                                        <button type="button" v-else class="btn btn-secondary rounded">Sincronizar</button>   
                                    </div>                                        
                                </div> 
  </div>
</div>  
                               

                                <div class="card">
  <div class="card-header">
    Opción 2
  </div>
  <div class="card-body">
    <button type="button" v-if="isSubmitting===false" class="btn btn-warning btn-lg btn-block" style="color: white;" @click="iniciarAutomatizacion()">Sincronizar todo tipo de punto de venta y sucursal</button>                        
                                        <button type="button" v-else class="btn btn-secondary btn-lg btn-block">Sincronizar todo tipo de punto de venta y sucursal</button>   
                                  
  </div>
</div>  
                           
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('manual')">Cerrar</button>                                            </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
        <!--Inicio del modal AUTOMATICO-->
 <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="auto_v" aria-hidden="true"
            data-backdrop="static" data-key="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('auto_v')">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">                       
                     
                        <form action="" class="form-horizontal">  
                            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-3">Hora de sincronización</th>
                        <th class="col-md-3">Frecuencia para la sincronización</th>
                        <th class="col-md-3">Cantidad de intentos</th>
                        <th class="col-md-3">Intervalo de tiempo para los intentos en minutos:</th>                             
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="col-md-3"><input  type="time" class="form-control" v-model="hora_a"></td>
                            <td class="col-md-3">
                                <select  class="form-control"  v-model="frecuencia_a">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Ejecutar todos los días</option>
                                            <option value="2">Ejecutar el ultimo dia de la semana laboral</option>
                                            <option value="3">Ejecutar el último día del mes</option>
                                            <option value="4">Ejecutar cada trimestre el día 1</option>                                                                                                                          
                                </select>
                            </td>
                            <td class="col-md-3"><input  type="text" class="form-control" v-model="intentos" @keypress="onlyNumbers($event)"></td>
                            <td class="col-md-3"><input  type="text" class="form-control" v-model="intervalo_min" @keypress="onlyNumbers($event)"></td>
                        </tr>
                </tbody>
                </table>                      
                          
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('auto_v')">Cerrar</button>       
                        <button type="button" class="btn btn-primary rounded"  @click="cambiarSicronizacion()">Actualizar</button>                
                                          
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
        
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import { data } from "jquery";
//Vue.use(VeeValidate);

export default {
    components: { VueMultiselect},
     //---permisos_R_W_S
     props: ['codventana','idmodulo'],
        //-------------------
    data() {
        return {
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
          offset:3,
            tipoAccion:1,
            tituloModal:'',
       

            arrayIndex:[],
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------      
                isSubmitting:false,
                //----cuis---
                emisor:'',              
                codigoSis:'',
                codigodAmb:'',
                nit:'',
                modalidad:'',
                codigoSuc:'',
                codigoPunVen:'',
                token_delegado:'',
                //-----------
                id_estado:'',

//////////////////////sincronizacion////////////////////////////////////////
                evento_manual_automatico:'',
                selectAutoManual:'0',
                arrayListarEmisor_v:[],
                selectEmisor_v:'0',

                codigoPuntoVenta_Modal:'',
                codigo_siat_modal:'',
                cuis_modal:'',

                frecuencia_a:'',
                hora_a:"",
                intentos:0,
                intervalo_min:0,

                arrayInicio:[],

        };
    },
    watch: {     
        selectEmisor_v: function (newValue) {
                this.codigoSucursal='';
                this.cuis='';
                let selector = this.arrayListarEmisor_v.find(
                    (element) => element.id === newValue,                   
                );
          console.log(selector);
               if (selector) {
                   this.codigoPuntoVenta_Modal = selector.codigoPuntoVenta; 
                   this.codigo_siat_modal = selector.codigo_siat;    
                   this.cuis_modal =selector.cuis;                                           
                }          
        },
    },
   

    computed: {
      
        isActived: function () {
            return this.pagination.current_page;
        },

       function () {
            return this.pagination.current_page;
        },

        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },

    methods: {
          //-----------------------------------permisos_R_W_S        
    listarPerimsoxyz() {
                //console.log(this.codventana);
    let me = this;   
        
    var url = '/gestion_permiso_editar_eliminar?win='+me.codventana;
  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;
            console.log(respuesta);
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

    resert_0(data){
        let me=this;
        me.listarConfiguracionSiat();
        
        me.pagination.total= 0;
        me.pagination.current_page= 0;   
        me.pagination.per_page= 0;            
        me.pagination.last_page= 0;    
        me.pagination.from= 0;     
        me.pagination.to= 0;   
        me.offset=3; 
if (data===1) {
        
        me.tipoAccion=1;
        me.tituloModal='';
      
        me.arrayIndex=[];
        //---permisos_R_W_S
        me.puedeEditar=2;
        me.puedeActivar=2;
        me.puedeHacerOpciones_especiales=2;
        me.puedeCrear=2;
        //-----------      
        me.isSubmitting=false;
        //----cuis---
        me.emisor='';              
        me.codigoSis='';
        me.codigodAmb='';
        me.nit='';
        me.modalidad='';
        me.codigoSuc='';
        me.codigoPunVen='';
        me.token_delegado='';
        //-----------
} else {
    if (data===2) {
        me.listarAuto_sicro();
        me.selectAutoManual='0';
        me.arrayListarEmisor_v=[];
        me.selectEmisor_v='0';

    } else {
        console.log("error-------");
    }
}       
    },

        solicitarCuis(data,id)
            {
                let me=this;    
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta seguro de pedir CUIS?',
                text: 'Conforme a normativa vigente el proceso de obtención del CUIS para una sucursal o punto de venta debe realizarse mediante el Sistema Informático de Facturación autorizado.El Código Único de Inicio de Sistemas (CUIS) para el Sistema Informático de Facturación en las modalidades de facturación Electrónica en Línea o Computarizada en Línea tendrá una vigencia de trescientos sesenta y cinco (365) días calendario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Solicitar',
                cancelButtonText: 'No, Solicitar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    var url='/siat_cuis_cufd/cuis?emisor='+me.emisor+'&codigo_siat='+data+'&nit='+me.nit+'&codigo_ambiente='+me.codigodAmb+'&codigo_sistema='+me.codigoSis+'&modalidad='+me.modalidad+'&token_delegado='+me.token_delegado+"&cuis_end="+3;                        
                    axios.get(url)
                    .then(function (response) {
                        var respuesta = response.data; 
                     
                        if (respuesta.error==null || respuesta.error=="") {
                            me.parseXML(respuesta,1,id);   
                        }else{
                            swalWithBootstrapButtons.fire(
                            ''+respuesta.error,
                            ''+respuesta.message,
                            'error'
                        )
                        }
                      //  me.listarIndex(1);
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
            
           
            cerrarOperaciones(data,id,cuis,id_cufd,id_cuis,id_emisor)
            {
                let me=this;    
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta seguro de cerrar operaciones?',
                text: 'Conforme normativa vigente el proceso de cierre de operaciones para el Sistema Informático de Facturación podrá realizarse a través de los Servicios Web disponibles y permite realizar el cierre de operaciones de una sucursal o punto de venta, este proceso inhabilita el CUIS y el CUFD vigente, de manera automática no pudiendo realizar la emisión de Facturas Digitales a partir de ese momento.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Solicitar',
                cancelButtonText: 'No, Solicitar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    var url='/siat_cuis_cufd/cerrarOperaciones?emisor='+me.emisor+'&codigo_siat='+data+'&nit='+me.nit+'&codigo_ambiente='+me.codigodAmb+'&codigo_sistema='+me.codigoSis+'&modalidad='+me.modalidad+'&token_delegado='+me.token_delegado+"&cuis_end="+2+"&id="+id+"&cuis="+cuis;                        
                    axios.get(url)
                    .then(function (response) {
                        var respuesta = response.data; 
                        if (respuesta==="error_1") {
                            Swal.fire("Error!","Debe cerrar todos los puntos de venta en esta sucursal antes de usar esta opcion. Debe ir a Siat/emisor y cerrar el punto de venta que quiere.","error",);    
                        } else {                                             
                          
                           me.parseXML(respuesta,2,id,id_cufd,id_cuis,id_emisor);
                        }
                        console.log("----------------------------------");
                        console.log(respuesta);
                        console.log("----------------------------------");
                      //  me.listarIndex(1);
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

              // Método para parsear el XML y extraer los datos
  parseXML(xmlString,data,id,id_cufd,id_cuis,id_emisor) {
    let me= this;
    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(xmlString, "text/xml");
    console.log(xmlDoc);
   // const respuestaCuis = xmlDoc.getElementsByTagName("RespuestaCuis")[0].childNodes[0];
    const respuestaCuis_2 = xmlDoc.getElementsByTagName("faultstring")[0];
    if (respuestaCuis_2!=undefined) {
        Swal.fire("Error",""+respuestaCuis_2.textContent,"error",);
    }else{
      
        // Recorrer todos los hijos de 'mensajesList' mensajesList
        const trasaccion= xmlDoc.querySelector('transaccion');
        if (trasaccion.textContent==='true') {
            console.log("por verdad");
               
         //   console.log(codigoCuis.textContent+" - "+fechaCuis.textContent);
         if (data==1) {
            const codigoCuis= xmlDoc.querySelector('codigo');       
            const fechaCuis= xmlDoc.querySelector('fechaVigencia');    
            me.insertarCuis(id, codigoCuis.textContent, fechaCuis.textContent);
         } else {
            if (data===2) {
                console.log("resultado....");
                console.log(xmlDoc);                
                me.EliminarCuis(id,id_cuis,id_cufd,id_emisor);
            } else {
                Swal.fire("Error de entrada!","revise la entrada  si es eliminacion o adicion, tipo transacción","warning",);  
            }
         }
           

        } else {
            if (trasaccion.textContent==='false') {
                if (data===1) {
                    const codigoCuis= xmlDoc.querySelector('codigo');       
            const fechaCuis= xmlDoc.querySelector('fechaVigencia');   
        const mensajesList = xmlDoc.querySelector('mensajesList'); 
        let cadena_nombre="";
       Array.from(mensajesList.children).forEach(child => {
        cadena_nombre += `${child.tagName}: ${child.textContent.trim()}\n`;   
         // console.log(`${child.tagName}: ${child.textContent}`);         
        });
        Swal.fire("Cuis!",""+cadena_nombre,"warning",);  
                } else {
                    if (data===2) {
                        console.log("-*---*-");
                        console.log(xmlDoc); 
                    } else {
                        Swal.fire("Error de entrada!","revise la entrada  si es eliminacion o adicion","warning",);   
                    }
                }
           
            } else {
                Swal.fire("Error!","transacción nula","error",);  
            }            
        }
        
    }   
  // console.log(xmlDoc);
 
  },

  onlyNumbers(event) {
        if (!/^\d$/.test(event.key)) {
            event.preventDefault();
        }
    },

  EliminarCuis(id,id_cuis,id_cufd,id_emisor){
        let me = this;    
                axios.post("/siat_cuis_cufd/eliminar_operaciones_V", {
                id:id,
                id_cuis:id_cuis,
                id_emisor:id_emisor,              
                id_cufd:id_cufd,

                id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"eliminacion de estados ",             
                
                })
                .then(function (response) {
                 //   me.listarIndexEndPoint(); 
                    let respuesta=response.data; 
                    me.listarIndex();                  
                    if (respuesta.length>0) {
                        Swal.fire("Error!",""+respuesta,"error",);    
                    } else {
                        Swal.fire("Eliminacion","Correctamente","success",);  
                    }
                                                    
                })               
                .catch(function (error) {                
                  console.log(error);                
            });      
        },

  insertarCuis(id,cuis,fecha){
        let me = this;    
                axios.post("/siat_cuis_cufd/insertar_cuis", {
                id:id,
                cuis:cuis,
                fecha:fecha,
                
                id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"insercion de cuis",                
                
                })
                .then(function (response) {
                 //   me.listarIndexEndPoint(); 
                    let respuesta=response.data; 
                    me.listarIndex();                  
                    if (respuesta.length>0) {
                        Swal.fire("Error!",""+respuesta,"error",);    
                    } else {
                        Swal.fire("Insercion!","Correctamente","success",);  
                    }
                                                    
                })               
                .catch(function (error) {                
                  console.log(error);                
            });      
        },
//---------------------

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;
            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },      
       

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
           me.listarIndex(page);
        },
       
        listarConfiguracionSiat()
            {
                let me=this;  
                var url='/siat_cuis_cufd/siat_config';                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data; 
                   // me.evento_manual_automatico=respuesta.id_sincro_auto;
                    me.emisor=0;
                me.codigoSis=respuesta.cod_sis;
                me.codigodAmb=respuesta.tipo_ambiente;           
                me.modalidad=respuesta.tipo_modalidad;
                me.token_delegado=respuesta.token_delegado;    
                    console.log("-------*--------");
                    console.log(respuesta);         
                    console.log("-------*--------");                                     
                })
                .catch(function(error){
                    error401(error);
                });   
            },

            listarCredencial() {
            let me = this;
            var url = "/credenciales_correo";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                  
                 //   me.id_credencial=response.data[0].id;
                 //   me.host=response.data[0].host;                   
                //   me.correo=response.data[0].correo;
                //    me.puerto=response.data[0].puerto;
                //    me.usuario=response.data[0].usuario;
               //     me.contraseña=response.data[0].contraseña;
               //     me.ssl=response.data[0].ssl;               
                    me.nit=response.data[0].nit;
              //      me.nombre_empresa=response.data[0].nom_empresa;
              //      me.celular=response.data[0].nro_celular;
              //      me.validador_variables=response.data[0].factura_dosificacion === null ? 0:response.data[0].factura_dosificacion;
              //      me.actividad_eco=response.data[0].actividad_economica;
              //      me.estado_cambio_moneda=response.data[0].moneda;
                    
              //      me.limite_monto=response.data[0].monto_limite;
              //      me.limite_horas=response.data[0].tiempo_limite;  
              //      me.selectModalApertura=response.data[0].modal_apertura;
                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarIndex(page)
            {
                let me=this;  
                 var url='/siat_cuis_cufd/inicio?page='+page;     
                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.index.data;                   
                })
                .catch(function(error){
                    error401(error);
                });              
               
            },

        abrirModal(accion, data = []) {
            let me = this;     
         switch (accion) {
              
                case "manual": {
                    me.tipoAccion = 1; 
                    me.tituloModal="Sincronización manual"; 
                    me.classModal.openModal("manual");
                  
                    break;
                }
                case "auto_v": {
                    me.tipoAccion = 1; 
                    me.tituloModal="Sincronización automatica"; 
                    me.classModal.openModal("auto_v");
                  
                    break;
                }
                
                                     
            }
        },

        cerrarModal(accion) {
            let me = this;
            
            if (accion == "manual") {
                me.tipoAccion=1;
              
                me.tituloModal="";
                me.isSubmitting=false;
                me.selectEmisor_v="0";
                me.classModal.closeModal(accion);         
            }
            if (accion == "auto_v") {
                me.tipoAccion=1;
                me.tituloModal="";
                me.classModal.closeModal(accion);
            }
        },
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        /************************************************SINCRONIZACION*******************************************/
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        verificarComunicacion(){
            
        },
        
        cambiarSicronizacion(){
            let me = this;         
                axios.put("/siat_sincronizacion/cambiarConfiguracion", {                      
                    frecuencia_a:me.frecuencia_a,
                    hora_a:me.hora_a,
                    intentos:me.intentos,
                    intervalo_min:me.intervalo_min,             
                })
                .then(function (response) {
                 //   me.listarIndexEndPoint(); 
                    let respuesta=response.data; 
                              
                    if (respuesta==="0") {
                        me.cerrarModal('auto_v');
                        Swal.fire("Error!","no exite la tabla con ese ID","error",);    
                    } else {        
                        me.cerrarModal('auto_v');                
                        me.listarAuto_sicro();
                        Swal.fire("Activacion y actualización","Correctamente","success",); 
                    }
                                                    
                })               
                .catch(function (error) {                
                  console.log(error);                
            }); 
        },    
        
        sinCronizacion(){
            let me=this;
            me.isSubmitting=true;
            var url='/siat_sincronizacion/sincronizar_manual_automatico?codigoAmbiente='+me.codigodAmb+'&codigoPuntoVenta='+me.codigoPuntoVenta_Modal+'&codigoSistema='+me.codigoSis+'&codigoSucursal='+me.codigo_siat_modal+'&cuis='+me.cuis_modal+'&nit='+me.nit+'&token_delegado='+me.token_delegado;                        
                axios.get(url)
                .then(function(response){

                    var respuesta = response.data;  
                    if (respuesta===0) {
                        Swal.fire("Sincronización","Correctamente","success",); 
                        me.crearindex("manual",1,"Punto de venta: "+me.codigoPuntoVenta_Modal);
                    } else {
                    
                        if (respuesta!=null || respuesta!="") {
                            Swal.fire("Error",""+respuesta,"error",);
                        } else {
                            Swal.fire(""+respuesta.error,""+respuesta.message,"error",);                    
                        }
                        me.crearindex("manual",2,"Punto de venta: "+me.codigoPuntoVenta_Modal);
                    }      
                    me.cerrarModal('manual');         
             

                })
                .catch(function(error){
                    error401(error);
                    console.log("--- >"+error);
                }); 
        },
      
        listarAuto_sicro(){
            let me=this;  
                 var url='/siat_sincronizacion/auto_s';                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.frecuencia_a=respuesta.frecuencia;
                    me.hora_a=respuesta.hora;
                    me.intentos=respuesta.intentos;
                    me.intervalo_min=respuesta.intervalo_min;
                    me.id_estado=respuesta.activo;
                    me.evento_manual_automatico=respuesta.activo;
                    console.log(respuesta);             
                })
                .catch(function(error){
                    error401(error);
                }); 
        },

        listar_emisor_v()
            {
                let me=this;  
                 var url='/siat_sincronizacion/listar_emisor';                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.arrayListarEmisor_v=respuesta;              
                })
                .catch(function(error){
                    error401(error);
                });              
            },
      
        activarModo(){
        let me = this;         
                axios.put("/siat_sincronizacion/manual_automatico", {
                    selectAutoManual:me.selectAutoManual,                 
                })
                .then(function (response) {
                 //   me.listarIndexEndPoint(); 
                    let respuesta=response.data;                    
                 me.listarAuto_sicro();
                 me.selectAutoManual='0';  

                    if (respuesta.length>0) {
                        Swal.fire("Error!",""+respuesta,"error",);    
                    } else {
                  
                        me.listarConfiguracionSiat();
                        Swal.fire("Activacion","Correctamente","success",); 
                    }
                                                    
                })               
                .catch(function (error) {                
                  console.log(error);                
            });      
        },

        iniciarAutomatizacion(){
            let me=this;  
            me.isSubmitting=true;
                 var url='/siat_sincronizacion/iniciarAutomatizacion';                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.isSubmitting=false;
              
                    if (respuesta===0) {
                        Swal.fire("Sincronización","Correctamente","success",); 
                        me.crearindex("manual",1,"todas las sucursales");
                    } else {
                            Swal.fire("Error","Para ver el problema escoger la opcion 1 ","error",);
                            me.crearindex("manual",2,"todas las sucursales");
                    }  
                      me.cerrarModal('manual');         
                })
                .catch(function(error){
                    error401(error);
                });
        },
      

        listar_inicio_v2()
            {
                let me=this;  
                me.arrayInicio=[];
                 var url='/siat_sincronizacion/listarInicio';                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    console.log("---*");
                    console.log(respuesta);
                    me.arrayInicio=respuesta;              
                })
                .catch(function(error){
                    error401(error);
                });              
            },

        crearindex(tipo,estado,desc){
            let me = this;         
                axios.post("/siat_sincronizacion/parametros", {             
            tipo:tipo,   
            estado:estado,   
            descripcion:desc,                 
                })
                .then(function (response) {
                    me.listar_inicio_v2(); 
                    let respuesta=response.data;              
                                 
                })               
                .catch(function (error) {                
                  console.log(error);                
            });  
        },
////////////////////////////////////-----------CUFD-------
insertar_cufd(codigo_siat,cuis){
    let me=this;    
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta seguro de pedir CUFD?',
                text: 'Conforme a normativa vigente el proceso de obtención del Código Único de Facturación Diaria (CUFD) para el Sistema Informático de Facturación autorizado debe realizarse diariamente. Este código habilita el sistema del Sujeto Pasivo para la emisión de Facturas Digitales durante un periodo de vigencia de 24 horas.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Solicitar',
                cancelButtonText: 'No, Solicitar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
            
               axios.post("/siat_cuis_cufd/insertar_cufd", {             
                    emisor:me.emisor,   
                    codigo_siat:codigo_siat,   
                    codigo_ambiente:me.codigodAmb,
                    codigo_sistema:me.codigoSis, 
                    modalidad:me.modalidad, 
                    token_delegado:me.token_delegado, 
                    endpoint:3, 
                    cuis:cuis,                  
                })
                    .then(function (response) {
                        var respuesta = response.data;  
                        console.log("*----------*");
                        console.log(respuesta);                    
                        if (respuesta.error==null || respuesta.error=="") {
                          console.log("---- exito o error"); 
                        }else{
                            swalWithBootstrapButtons.fire(
                            ''+respuesta.error,
                            ''+respuesta.message,
                            'error'
                        )}
                      // xxxx me.listarIndex(1);
                    }).catch(function (error) {
                       error401(error);                        
                        console.log(error);
                    });
                    
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    
                }
                }); 
        },
////////////////////--------------------------------------

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();
        this.listarIndex();
        //-------------------------
        this.listarConfiguracionSiat();
        this.listarCredencial();
        this.classModal.addModal("manual");
        this.classModal.addModal("auto_v");
        this.listarAuto_sicro();
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
