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
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-general" aria-selected="true">CUIS / CUFD</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Sincronización SIAT</a>
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
                                <div class="form-group col-sm-1">
                                    <strong >Tipo:</strong>
                                </div> 
                                <div class="form-group col-sm-5">
                                     <select  class="form-control"  v-model="selectModalidad" @change="listarIndex();listarConfiguracionSiat(1);">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Manual</option>
                                            <option value="2">Automatico</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <button v-show="selectModalidad==='1'" type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-cube" aria-hidden="true"></i> Solicitar CUIS Para todos</button>
                                 </div> 
                                 <div class="form-group col-sm-3">
                                    <button v-show="selectModalidad==='1'" type="button" class="btn btn-info btn-sm btn-block" style="color: white;"><i class="fa fa-cubes" aria-hidden="true"></i> Solicitar CUFD Para todos</button>
                                </div>   
                                </div>    
                               
                              
                          
                            </div>
                            <!---inserte tabla-->
                            <div v-show="selectModalidad==='1'">
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
    <a  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-cubes" aria-hidden="true"></i> Solicitar CUFD</a>     
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
                    <!--------------------------------------------------------------------------------------------------------------------------->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        <div class="card">
                            <div class="card-header">
                           ...........
                            </div>
                            <div class="alert alert-info" role="alert">
  .............n <strong>........</strong>
</div>
                            <div class="card-body">
                                <div class="form-group row">                                                  
                               
                                </div>  
                                <div class="form-group row">
                                 
                                </div> 
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 d-flex justify-content-center">
       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;" >Actualizar datos de empresa</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar datos de empresa</button>
   
    </div>
</div>
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
            selectModalidad:'0',

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
        };
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
                    me.emisor=respuesta.emisor;
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
              
                case "regcuenta": {
                    me.tipoAccion = 1;
              
   
                    me.classModal.openModal("regcuenta");
                    break;
                }
                case "regcuenta_edit":{
                    me.tipoAccion = 2;
                               
                
                    me.classModal.openModal("regcuenta");
                    break;
                }                           
            }
        },
        cerrarModal(accion) {
            let me = this;
            
            if (accion == "regcuenta") {
                me.tipoAccion=1;
              
                me.tituloModal="";
          
                me.classModal.closeModal(accion);         
            }
        },

        verificarComunicacion(){
            
        },


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
            //-----------------------
        this.listarConfiguracionSiat();
        this.listarCredencial();
        this.classModal.addModal("regcuenta");
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
