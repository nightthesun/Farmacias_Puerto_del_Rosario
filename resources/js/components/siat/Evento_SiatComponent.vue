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
                    <i class="fa fa-search"></i> Filtro de busqueda 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-3">
                     <label for="">Sucursal SIAT:</label>
                     <div class="input-group">
                        <select class="form-control" v-model="selectEmisor">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option value="10000" >Todos</option>
                                    <option v-for="(i, index) in emisor_query_1" :key="index" :value="i.id_sucursal">{{'Emisor: '+i.nombre_emisor+' - Nombre suc.Siat: '+i.nombre_suc_siat+' - Sucursal: '+i.razon_social}}</option>                                   
                                </select>
                          <button type="submit" class="btn btn-primary" @click="listarInicio(1,1)" :disabled="selectEmisor==='0'"><i class="fa fa-search"></i></button>       
                     </div>
                                
                </div>
                <div class="col-md-3">
                    <label for="">Nro.Factura:</label>
                    <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar por numero de factura" v-model="buscar_factura"/>
                                <button type="submit" class="btn btn-primary" @click="listarInicio(1,2)" :disabled="buscar_factura===''"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="">CUF:</label>
                    <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar codigo de autorización" v-model="buscar_cuf"/>
                                <button type="submit" class="btn btn-primary" @click="listarInicio(1,3)" :disabled="buscar_cuf===''">
                                    <i class="fa fa-search"></i> 
                                </button>
                    </div>
                </div>   
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                 <label for="">Sector SIAT:</label>
                 <div class="input-group">
                    <select class="form-control" v-model="selectSector">
                                    <option value="0" disabled selected>Seleccionar...</option>                               
                                    <option v-for="(i, index) in sector_query_2" :key="index" :value="i.codigo">{{'Descripción: '+i.descripcion+' - Codigo: '+i.codigo}}</option>                                   
                                </select>
                                 <button type="submit" class="btn btn-primary" @click="listarInicio(1,4)" :disabled="selectSector==='0'">
                                    <i class="fa fa-search"></i> 
                                </button>
                 </div>
                                
                </div>
                <div class="col-md-3">
                     <label for="">Estado SIAT:</label>
                     <div class="input-group"> 
                        <select class="form-control" v-model="selectEstado">
                        <option value="0" disabled selected>Seleccionar...</option>    
                        <option value="901">Recepción Pendiente</option> 
                        <option value="902">Recepción Rechazada</option> 
                        <option value="903">Recepción Procesada</option> 
                        <option value="904">Recepción Observada</option>  
                        <option value="905">Anulación Confirmada</option> 
                        <option value="906">Anulación Rechazada</option> 
                        <option value="907">Reversión De Anulación Confirmada</option> 
                        <option value="908">Recepción Validada</option> 
                        <option value="909">Reversión De Anulación Rechazada</option> 
                    </select>
                       <button type="submit" class="btn btn-primary" @click="listarInicio(1,5)" :disabled="selectEstado==='0'">
                                    <i class="fa fa-search"></i> 
                                </button>
                                
                     </div>                    
                </div>
                 <div class="col-md-3">
                    <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" @change="listarInicio(1,6)">
                 </div>
                  <div class="col-md-3">
 <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" @change="listarInicio(1,6)">
                  </div>    
            </div> 
         </div>


            </div>   
  
        <!-- fin de index -->
        </div>  
         <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    RESULTADO DE TABLA     
                </div>
        <div class="card-body"> 
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Nro. Documento</th>
                        <th class="col-md-1">Sucursal</th>
                        <th class="col-md-1">Emisor</th>
                        <th class="col-md-2">F.Emision</th>
                        <th class="col-md-3">Sector</th>
                        <th class="col-md-3">CUF</th> 
                        <th>Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in inicioArray" :key="index">
                        <td>
                            <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>     
  <div class="dropdown-menu">    
      <a class="dropdown-item" href="#" @click="abrirModal('registrar',i);"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i>Ver datos</a>
    <a  class="dropdown-item" href="#" ><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i>Ver estado factura</a>     
    <a  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i>Ver en SIAT</a>
    <a v-show="i.codEstado != '908'" class="dropdown-item" href="#" ><i style="color: black;"  class="fa fa-exclamation-triangle" aria-hidden="true"></i>Contingencia</a>
    <a  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-refresh" aria-hidden="true"></i>Anular</a>   
     </div>  
                            
                        </td>
                        <td class="col-md-1">{{i.nro_doc}}</td>
                        <td class="col-md-1">{{i.nomre_sucursal}}</td>
                        <td class="col-md-1">{{i.punto_venta}}</td>
                        <td class="col-md-2">{{i.fechaEmision}}</td>
                        <td class="col-md-3">{{i.sector_descripcion}}</td>
                        <td class="col-md-3">{{i.cuf}}</td>
                        <td>
                            <div v-if="i.codEstado=='908'">
                                <span class="badge badge-pill badge-success">VALIDA</span>
                               
                            </div>
                            <div  v-else>
                                 <span class="badge badge-pill badge-warning">OBSERVADA</span>
                               
                            </div>
                        </td>                        
                    </tr>
                </tbody>
            </table>    
<nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,)">Ant</a>
                    </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                    </li>
                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,)">Sig</a>
                    </li>
                </ul>
            </nav>
            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>    
           <!--Inicio del modal agregar/actualizar-->
           <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;"> 
                     
                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center">
                                                    <strong>Datos de cliente</strong>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Documento:</th>
                                                <td>{{ documento_modal }}</td>
                                                <th>Razón social:</th>
                                                <td>{{ rasonSocial_modal }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                     <table  class="table table-bordered table-striped table-sm table-responsive">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center">
                                                    <strong>Datos de factura</strong>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="col-3">Numero Fac.:</td>
                                                <td class="col-3">{{ numeroFactura_modal }}</td>
                                                <td class="col-3">Cod. Resepción:</td>
                                                <td class="col-3">{{ codRecepcion_modal }}</td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">Cod. Autorización:</td>
                                                <td class="col-md-3">{{ codAutorizacion_modal }}</td>
                                                <td class="col-md-3">fecha Emision:</td>
                                                <td class="col-md-3">{{ fechaEmision_modal }}</td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">Sucursal S.:</td>
                                                <td class="col-md-3">{{nombreSucursal_modal}}</td>
                                                <td class="col-md-3">Punto venta:</td>
                                                <td class="col-md-3">{{ razonSocialSucursal_modal}}</td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">Sector:</td>
                                                <td class="col-md-3">{{sectorDescripcion_modal}}</td>
                                                <td class="col-md-3">Estado:</td>
                                                <td class="col-md-3">{{estado_modal}}</td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">Contigencia:</td>
                                                <td class="col-md-3">{{contingencia_modal}}</td>
                                                <td class="col-md-3">Estado contigencia:</td>
                                                <td class="col-md-3">{{ contigenciaDes_modal }}</td>
     
                                            </tr>                                            
                                        </tbody>
                                    </table>       
                                </div>
                         
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                     
                    </div>
                    </div>    
                </div>
            </div>  
    </transition>
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
          

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',
showModal: false,
offset:3,
        emisor_query_1:[],
        sector_query_2:[],
        selectEmisor:'0',
        selectSector:'0',

        buscar_cuf:'',
        buscar_factura:'',
        selectEstado:'0',  
        inicioArray:[],

       documento_modal:'',
        rasonSocial_modal:'',
        numeroFactura_modal:'',
        codRecepcion_modal:'',
        codAutorizacion_modal:'',
        fechaEmision_modal:'',
        estado_modal:'',
        puntoVenta_modal:'',
      
       sectorDescripcion_modal:'', 
        nombreSucursal_modal:'',
        razonSocialSucursal_modal:'',
        contigenciaDes_modal:'',
        contingencia_modal:'',

        };
    },

    

    computed: {
      //  sicompleto() {
      //      let me = this;
       //     if (
          
     //           me.glosa != "" &&
     //           me.cantidadS != "" &&
     //           me.ProductoLineaIngresoSeleccionado
     //       )
       //         return true;
      //      else return false;
      //  },
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

        
         listarQueryModal_1(id_sucursal,punto,id_contigencia) {
            let me = this;
            
           var url = "/siat_eventos/listarQueryModal_1?id_sucursal="+id_sucursal+"&suc="+punto+"&id_contigencia="+id_contigencia;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;   
                                   
                    if(id_contigencia===0){
                        me.contingencia_modal="Sin contingencia";
                        me.contigenciaDes_modal="Catalogo normal";
                    }else{
                        let respues_conti=respuesta.contigencia;
                       me.contingencia_modal=respues_conti.descripcion; 
                        me.contigenciaDes_modal="Caralogo "+respues_conti.codigo; 
                    }
                    let r=respuesta.sucursal;
                    if (length.r>0) {
                           me.nombreSucursal_modal=r.nombre_suc_siat;
                            me.razonSocialSucursal_modal=r.razon_social;
                    }else{
                           me.nombreSucursal_modal="error nombre";
                            me.razonSocialSucursal_modal="error razon social";
                        
                    }
                    console.log(respuesta);
                     me.showModal = true;
                    me.classModal.openModal("registrar");
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

 listarEmisorAndSector() {
            let me = this;
           var url = "/siat_eventos/listarEmisorAndSector";
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    let respuesta_1=respuesta.emisor_query_1;
                    let respuesta_2=respuesta.sector_query_2;
                    let error_1=respuesta.error_1;
                    let error_2=respuesta.error_2;
                    if (error_1==0 && error_2==0) {
                           Swal.fire("Error!", "No existe datos en la lista de sector ni en emisor",  "error",);                     
                    }
                    else{
                        if (error_1==0||error_2==0) {
                            Swal.fire("Error!", "No existe datos en la lista de emisor o sel sector",  "error",); 
                        }                        
                    }
                    me.emisor_query_1=respuesta_1;
                    me.sector_query_2=respuesta_2;

                    console.log(respuesta);
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

listarInicio(page,data)
            {
                let me=this;
                let valor=0;
                let url="";
                switch (data) {
                    case 1:{
                     url='/siat_eventos/listarInicio?page='+page+'&id_sucursal='+me.selectEmisor+'&inicial='+data;
                    break;
                    }
                    case 2:{
                     url='/siat_eventos/listarInicio?page='+page+'&num_factura='+me.buscar_factura+'&inicial='+data;
                    break;                    
                    }
                    case 3:{
                     url='/siat_eventos/listarInicio?page='+page+'&cuf='+me.buscar_cuf+'&inicial='+data;
                    break;                    
                    }
                    case 4:{
                     url='/siat_eventos/listarInicio?page='+page+'&sector='+me.selectSector+'&inicial='+data    ;
                    break;                    
                    }
                    case 5:{
                    url='/siat_eventos/listarInicio?page='+page+'&estado='+me.selectEstado+'&inicial='+data;
                    break;                    
                    }
                    case 6:{
                    url='/siat_eventos/listarInicio?page='+page+'&fecha_inicio='+me.startDate+'&fecha_fin='+me.endDate+'&inicial='+data;
                    break;                    
                    }
                    default:{
                        valor=1;
                        break;
                    }
                }
                if (valor==1) {
                 return Swal.fire('Error!','error de lista','error');
                }
               
                me.inicioArray=[];
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                      me.inicioArray =respuesta.index.data;


                    console.log(me.inicioArray);
                  
                })
                .catch(function(error){
                    error401(error);
                });
            },

        sucursalFiltro() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
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
            me.listarInicio(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
            
                case "registrar": {
                    me.tipoAccion = 1;
                    console.log(data);
                    me.documento_modal=data.nro_doc;
                    me.rasonSocial_modal=data.nom_cliente;

                    me.numeroFactura_modal=data.numFactura;
                    me.codRecepcion_modal=data.codRecepcion;
                    me.codAutorizacion_modal=data.cuf;
                    me.fechaEmision_modal=data.fechaEmision;

                    me.sectorDescripcion_modal=data.sector_descripcion;
                    if (data.codEstado==='908') {
                    me.estado_modal="VALIDO";    
                    }else{
                    me.estado_modal="OBSERVADO";                        
                    }
                    
                    
                    me.puntoVenta_modal=data.punto_suc;
                    
                   



                    me.tituloModal = "Datos de venta facturada.";
                    me.listarQueryModal_1(data.id_sucursal,data.punto_suc,data.tipo_contigencia);
                
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
          
            
                    me.classModal.openModal("registrar");

                    break;
                }
            
            }
        },

        fecha_inicial(){
            // Obtener la fecha actual
    const today = new Date();
    // Obtener el año, mes y día actual
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const day = String(today.getDate()).padStart(2, '0');

    // Asignar la fecha del primer día del mes al input de fecha de inicio
    this.startDate = `${year}-${month}-01`;
    // Asignar la fecha actual al input de fecha final
    this.endDate = `${year}-${month}-${day}`;
        },

        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = " ";
             
             
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
        this.fecha_inicial();
        this.listarEmisorAndSector();
        this.classModal.addModal("registrar");
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
