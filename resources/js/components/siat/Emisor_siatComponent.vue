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
            <div class="card" v-if="arraySucursal.length>0">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Emisor               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');listar_caja();"
                        :disabled="sucursalSeleccionada == 0"   
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar una sucursal.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursal siat:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex();">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.id"
                                        v-text="'codigo siat: '+sucursal.codigo_siat +' - '+sucursal.nombre_suc_siat">
                                    </option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4" v-show="sucursalSeleccionada != 0"  >
                            <button type="button" @click="consultarPuntoV()" class="btn btn-primary"><i class="fa fa-volume-control-phone" aria-hidden="true"></i> Consultar punto de venta a siat</button>
                        </div>      
                
                       

            </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Nombre caja</th>
                        <th>Tipo</th>
                        <th>Emisor</th>
                        <th>Cuis</th>
                        <th>Cufd</th>
                        <th>Cuis vigencia</th>
                        <th>Cufd vigecia</th>
                        <th>Estado Cuis</th>
                        <th>Estado Cufd</th>
                        <th>Estado</th>
                               
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td >
                            <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>     
<div class="dropdown-menu">
    <a v-show="i.nombre_caja===null && i.tipo===1000" @click="abrirModal('caja',i);listar_caja();" class="dropdown-item" href="#"><i style="color: black;" class="icon-pencil"></i> Añadir tipo</a>
    <a v-show="i.nombre_caja!=null" @click="quitarCaja(i.id)" class="dropdown-item" href="#"><i style="color:black;" class="fa fa-window-close-o" aria-hidden="true"></i> Quitar nomre de caja</a>
    <a v-show="i.tipo!=1000" class="dropdown-item" href="#"><i style="color: black;" class="icon-trash"></i> Eliminar</a>
    
    <a v-show="i.tipo!=1000" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-cubes" aria-hidden="true"></i> Solicitar CUFD</a>   
    <a v-show="i.tipo!=1000" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-cube" aria-hidden="true"></i> Solicitar CUIS</a> 
</div>  
                        </td>
                        <td>{{ i.nombre }}</td>
                        <td>{{ i.descripcion }}</td>
                        <td>{{ i.nombre_caja }}</td>
                        <td><span v-if="i.tipo===1000">Principal</span><span v-else>{{ i.descripcion_tipo }}</span></td>
                        <td>{{ i.id_punto_venta }}</td>
                        <td>{{ i.cuis }}</td>
                        <td>{{ i.cufd }}</td>
                        <td>{{ i.fecha_cuis }}</td>
                        <td>{{ i.fecha_vigencia }}</td>
                        <td>
                            <span v-if="i.cuis_estado===1" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span> 
                        </td>
                        <td>
                            <span v-if="i.cufd_estado===1" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span> 
                        </td>
                        <td>
                            <span v-if="i.nombre_caja===null" class="badge badge-pill badge-warning">Nesecita caja</span>
                            <span v-else-if="i.estado===1" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span> 
                        </td>

                        
                    </tr>
                </tbody>

            </table>    

            <!-----fin de tabla------->
        </div>


            </div>   
            <div v-else class="alert alert-warning" role="alert">
Debe crear antes una sucursal en el modulo de SIAT
</div>
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade"
            tabindex="-1"
            role="dialog"
            arial-labelledby="myModalLabel"
            id="registrar"
            aria-hidden="true"
            data-backdrop="static"
            data-key="false" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('registrar')">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <form action="" class="form-horizontal">                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                <div class="form-group row" >                                   
                                    <div class="col-md-4">
                                        <label>Nombre del punto de venta: <span v-show="nombrePuntoVenta==''" style="color: red;">(*)</span></label>
                                        <input type="text" class="form-control" v-model="nombrePuntoVenta">                                   
                                        <span  v-if="nombrePuntoVenta==''" class="error">Debe llenar el dato</span> 
                                    </div>
                                    <div class="col-md-4">
                                    <label for="end-date">Descripción: <span v-show="descripcion==''" style="color: red;">(*)</span></label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" v-model="descripcion" rows="3"></textarea>                                    
                                        <span  v-if="descripcion==''" class="error">Debe llenar el dato</span> 
                                    </div>   
                                    <div class="col-md-4">
                                    <label >Tipo: <span v-show="codigoTipoPuntoVenta=='0'" style="color: red;">(*)</span></label>
                                    <select class="form-control" v-model="codigoTipoPuntoVenta">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="a in arrayConcepto" :key="a.codigo"  :value="a.codigo">{{ a.descripcion }}</option>
                                    </select>
                                        <span  v-if="codigoTipoPuntoVenta=='0'" class="error">Debe llenar el dato</span> 
                                    </div>     
                                </div> 
                                <div class="form-group row">                                    
                                    <div class="col-md-12">
                                    <label >Caja: <span v-show="selectCaja=='0'" style="color: red;">(*)</span></label>
                                    <select class="form-control" v-model="selectCaja">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="a in arrayCaja" :key="a.id"  :value="a.id">{{ "NOMBRE CAJA: "+a.nombre_caja+" NOMBRE SUCURSAL: "+a.razon_social+" NOMBRE SIAT: "+a.nombre_suc_siat }}</option>
                                    </select>
                                        <span  v-if="selectCaja=='0'" class="error">Debe llenar el dato</span> 
                                    </div>     
                                </div>
                               
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="requerirCreacionPV()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                                <button type="button" v-else class="btn btn-light">Actualizar</button>
                            </div>
                        </div>
                     
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
           <!---------------------caja---------------------->
           <div class="modal fade"
            tabindex="-1"
            role="dialog"
            arial-labelledby="myModalLabel"
            id="caja"
            aria-hidden="true"
            data-backdrop="static"
            data-key="false" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('caja')">
                            <span aria-hidden="true">x</span>
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
                                    <div class="col-md-12">
                                    <label >Caja: <span v-show="selectCaja=='0'" style="color: red;">(*)</span></label>
                                    <select class="form-control" v-model="selectCaja">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="a in arrayCaja" :key="a.id"  :value="a.id">{{ "NOMBRE CAJA: "+a.nombre_caja+" NOMBRE SUCURSAL: "+a.razon_social+" NOMBRE SIAT: "+a.nombre_suc_siat }}</option>
                                    </select>
                                        <span  v-if="selectCaja=='0'" class="error">Debe llenar el dato</span> 
                                    </div>     
                                </div>                                
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('caja')">Cerrar</button>                                             
                        <button type="button" class="btn btn-primary" @click="subir_caja()" :disabled="selectCaja==='0'">Actualizar</button>                     
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
//Vue.use(VeeValidate);
export default {
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
          
            isSubmitting: false,
            

            tituloModal: "",
            sucursalSeleccionada:'0',
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,

            arrayConcepto:[],
            codigoTipoPuntoVenta:'0',
            descripcion:'',
            nombrePuntoVenta:'',
            codigoAmbiente:'',
            codigoModalidad:'',
            codigoSistema:'',
            codigoSucursal:'',           
            cuis:'',
            nit:'',
            token_delegado:'',

            arrayCaja:[],
            selectCaja:'0',
            arrayIndex:[],
            id:'',
        };
    },

    watch: {
        sucursalSeleccionada: function (newValue) {
                this.codigoSucursal='';
                this.cuis='';
                let selector = this.arraySucursal.find(
                    (element) => element.id === newValue,
                   
                );
          console.log(selector);
               if (selector) {
                   this.codigoSucursal = selector.codigo_siat;  
                   this.cuis = selector.dato;                                 
                }          
        },
    },

    computed: {
        sicompleto() {
             let me = this;
           if (   me.codigoTipoPuntoVenta != "0" &&  me.descripcion != "" && me.nombrePuntoVenta!=""&& me.selectCaja!="0")
               return true;
          else return false;
       },
        isActived: function () {
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
       
                    // Método para parsear el XML y extraer los datos
  parseXML(xmlString) {
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
            const codigoPuntoVenta_1= xmlDoc.querySelector('codigoPuntoVenta');                
            me.crear(codigoPuntoVenta_1.textContent);
        } else {
            if (trasaccion.textContent==='false') {
                me.cerrarModal('registrar');
          //  const codigoCuis= xmlDoc.querySelector('codigo');       
          //  const fechaCuis= xmlDoc.querySelector('fechaVigencia');   
        //const mensajesList = xmlDoc.querySelector('mensajesList'); 
        let cadena_nombre="";
       //Array.from(mensajesList.children).forEach(child => {
       // cadena_nombre += `${child.tagName}: ${child.textContent.trim()}\n`;   
         // console.log(`${child.tagName}: ${child.textContent}`);         
       // });
        Swal.fire("Punto de venta!",""+cadena_nombre,"warning",); 
            } else {
                me.cerrarModal('registrar');
                Swal.fire("Error!","transacción nula","error",);  
            }            
        }        
    }   
  
  },

 
  listarIndex(page)
          {
                let me=this;  
                 var url='/siat_emisor/listar_inicio?page='+page+'&id='+me.sucursalSeleccionada;                     
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

            consultarPuntoV(){
                let me=this;  
                 var url='/siat_emisor/consultar_PuntoV_siat?codigoAmbiente='+me.codigoAmbiente+"&codigoSistema="+me.codigoSistema+"&codigoSucursal="+me.codigoSucursal+"&cuis="+me.cuis+"&nit="+me.nit+"&token_delegado="+me.token_delegado;                     
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;     
                    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(respuesta, "text/xml");    
    console.log(xmlDoc);           
                                 
                })
                .catch(function(error){
                    error401(error);
                }); 

            },

            quitarCaja(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta seguro de volver?',
                text: "Nulo el valor",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Quitar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                
                if (result.isConfirmed) {
                     axios.put('/siat_emisor/quitar_caja',{
                        'id': id
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            'A nulacion!',
                            'El registro volvio a su estado de nulo Correctamente',
                            'success'
                        )
                        me.listarIndex(1);
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



        subir_caja(){
            let me=this;
            // me.isSubmitting = true; // Deshabilita el botón
                axios.put("/siat_emisor/subir_caja", {
                    id:me.id,
                    id_caja:me.selectCaja,                                       
                })
                .then(function (response) {
                    me.cerrarModal('caja');
                    me.listarIndex(); 
                    let respuesta=response.data;    
                  
                    if (respuesta.length>0) {
                        Swal.fire("Error!",""+respuesta,"error",);    
                    } else {
                        Swal.fire("Registro creado!","Correctamente","success",);  
                    }                               
                })               
                .catch(function (error) {                
                  console.log(error);                
            });  
        },

        crear(data){
            let me=this;
           // me.isSubmitting = true; // Deshabilita el botón
                axios.post("/siat_emisor/crear", {
                    nombre:me.nombrePuntoVenta,
                    descripcion:me.descripcion,
                    id_siat_sucursal:me.sucursalSeleccionada,
                    id_punto_venta:data,
                    id_caja:me.selectCaja,  
                    tipo:me.codigoTipoPuntoVenta,                      
                })
                .then(function (response) {
                    me.cerrarModal('registrar');
                     me.listarIndex(); 
                    let respuesta=response.data;             
                    
                    if (respuesta.length>0) {
                        Swal.fire("Error!",""+respuesta,"error",);    
                    } else {
                        Swal.fire("Registro creado!","Correctamente","success",);  
                    }                               
                })               
                .catch(function (error) {                
                  console.log(error);                
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });  
        },

        requerirCreacionPV(){
            let me=this;   
            me.isSubmitting= true;       
            var url ="/siat_emisor/solicitar_regiPunV?codigoAmbiente="+me.codigoAmbiente+"&codigoModalidad="+me.codigoModalidad
            +"&codigoSistema="+me.codigoSistema+"&prod_piloto="+me.selectModalidad+"&codigoSucursal="+me.codigoSucursal
            +"&codigoTipoPuntoVenta="+me.codigoTipoPuntoVenta+"&cuis="+me.cuis+"&descripcion="+me.descripcion
            +"&nit="+me.nit+"&nombrePuntoVenta="+me.nombrePuntoVenta+"&token_delegado="+me.token_delegado;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.parseXML(respuesta);                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });             
        },
        
        listar_config_siat() {
            let me = this;
             var url = "/listar_config_siat_sis_v3";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    var siat=respuesta.config_siat;  
                    var sis=respuesta.config_sistema;              
                    me.codigoAmbiente=siat[0].tipo_ambiente;
                    me.codigoModalidad=siat[0].tipo_modalidad;
                    me.codigoSistema=siat[0].cod_sis;
                    me.token_delegado=siat[0].token_delegado;
                    me.nit=sis[0].nit;                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listar_caja() {
            let me = this;
            me.arrayCaja=[];
             var url = "/siat_emisor/listar_caja?id="+me.codigoSucursal;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.arrayCaja=respuesta;    
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },


        listarConceptos() {
            let me = this;
             var url = "/listar_conceptos_v3?id="+13;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayConcepto = respuesta;                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        sucursalFiltro() {
            let me = this;
            var url = "/siat_emisor/listar_siat_sucursal";
          // var url = "/listar_conceptos_v3?id="+13;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta; 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
        //    me.listarAjusteNegativos(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registrar punto de venta";
                    me.codigoTipoPuntoVenta="0";
                    me.descripcion="";
                    me.nombrePuntoVenta="";
                    me.isSubmitting=false; 
                    me.selectCaja="0";
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "caja":{
                    me.tituloModal = "Agregar el tipo de caja";
                    me.classModal.openModal("caja");
                    me.id=data.id;
                }
            
            }
        },

      
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);               
                    me.tituloModal = " ";
                    me.codigoTipoPuntoVenta="0";
                    me.descripcion="";
                    me.nombrePuntoVenta="";
                    me.isSubmitting=false;  
                    me.selectCaja="0";
            }
            if (accion == "caja") {
                me.classModal.closeModal(accion);
                me.tituloModal = " ";
                me.selectCaja="0";
                me.id="";

            }   
        },

     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        this.sucursalFiltro();
        this.listarConceptos();
        this.listar_config_siat();
        this.classModal.addModal("registrar");
        this.classModal.addModal("caja");
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
