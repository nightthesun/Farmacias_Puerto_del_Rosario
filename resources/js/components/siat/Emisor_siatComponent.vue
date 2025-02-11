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
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar');listar_caja();" :disabled="sucursalSeleccionada == 0">
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
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex();">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.id"
                                        v-text="'codigo siat: '+sucursal.codigo_siat +' - '+sucursal.nombre_suc_siat">
                                    </option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-3" v-show="sucursalSeleccionada != 0"  >
                            <button type="button" @click="consultarPuntoV(1)" class="btn btn-primary"><i class="fa fa-volume-control-phone" aria-hidden="true"></i> Consultar punto de venta a siat</button>
                        </div>      
                
                        <div class="col-md-3" v-show="sucursalSeleccionada != 0 " :disabled="puedeActivar===1">
                            <button type="button" @click="consultarPuntoV(2);" class="btn btn-danger"><i class="fa fa-lock" aria-hidden="true"></i> Cerrar punto de venta </button>
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
    <a v-show="i.nombre_caja===null && i.tipo===1000" @click="abrirModal('caja',i);listar_caja();" class="dropdown-item" href="#"><i style="color: black;" class="icon-pencil"></i> Añadir caja</a>
    <a v-show="i.nombre_caja!=null" @click="quitarCaja(i.id,i.id_cuis)" class="dropdown-item" href="#"><i style="color:black;" class="fa fa-window-close-o" aria-hidden="true"></i> Quitar nombre de caja</a>
    <a v-show="i.tipo!=1000 && i.estado===1" @click="abrirModal('cerrar_PV',i);" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-trash" aria-hidden="true"></i> Eliminar punto de venta</a> 
    
    <a v-show="i.tipo!=1000  && i.estado===1" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-cubes" aria-hidden="true"></i> Solicitar CUFD</a>   
 
        <div v-if="i.id_cuis==null"> 
           <a v-show="i.tipo!=1000  && i.estado===1" @click="solicitarCuis(i.codigo_siat,i.id,i.id_punto_venta)" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-cube" aria-hidden="true"></i> Solicitar CUIS</a>              
        </div>
        <div v-else>
            <a v-show="i.tipo!=1000  && i.estado===1" @click="cerrarOperaciones(i.codigo_siat,i.id,i.cuis,i.id_cufd,i.id_cuis,i.id_punto_venta)" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-refresh" aria-hidden="true"></i> Cierre de operaciones en punto de venta (inhabilita el CUIS y el CUFD) </a> 
        </div>                                                                                        
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
                            <span v-if="i.nombre_caja===null&&i.estado===1" class="badge badge-pill badge-warning">Nesecita caja</span>
                            <span v-else-if="i.estado===1 && i.nombre_caja!=null" class="badge badge-pill badge-success">Activo</span>
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
           <!---------------------modal caja---------------------->
           <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="caja" aria-hidden="true" data-backdrop="static" data-key="false" >
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
                                                                 
                              
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" class="btn btn-primary" @click="subir_caja()" :disabled="selectCaja==='0'">Actualizar</button> 
                            
                            </div>
                            <div v-else>
                            
                                <button type="button" class="btn btn-light">Actualizar</button>
                            </div>
                        </div>             
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->   
        <!---------------------modal cierre punto venta---------------------->
        <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="cerrar_PV" aria-hidden="true" data-backdrop="static" data-key="false" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('cerrar_PV')">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <div class="alert alert-danger" role="alert">
                            Este servicio permite al Sujeto Pasivo realizar el cierre definitivo de un punto de venta, solo podrá realizar esta operación <strong>si para el punto de venta no existe CUIS o CUFD activo. </strong>Una vez que el punto de venta se haya cerrado no podrá generarse nuevamente con el mismo correlativo.
                      
                        </div>
                        <form action="" class="form-horizontal">                        
                            <div class="container">                                
                                <div class="form-group row">   
                                 
                                    <div v-show="tipoAccion===3" class="col-md-8">
             
                                        <label >Tipo: <span v-show="selectPuntoVneta=='0'" style="color: red;">(*)</span></label>
                                    <select class="form-control" v-model="selectPuntoVneta">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="a in arrayPuntosVentas" :key="a.consulta"  :value="a.codigo">{{ "Emisor: " +a.codigo+" Nombre: "+a.nombre+" Tipo: "+a.tipo }}</option>
                                    </select>
                                        <span  v-if="selectPuntoVneta=='0'" class="error">Debe llenar el dato</span> 
                                    </div>
                                    <div class="col-md-4">
                                    <label for="end-date">Contraseña de cambio: <span v-show="selectPuntoVneta!='0'" style="color: red;">(*)</span></label>
                                    <input type="password" class="form-control" v-model="passsCmbio" id="inputPassword" placeholder="Contraseña">                                   
                                        <span  v-if="passsCmbio==''" class="error">Debe llenar el dato</span> 
                                    </div>   
                                    
                                </div> 
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('cerrar_PV')">Cerrar</button>                    
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button v-if=" tipoAccion===3" type="button" class="btn btn-primary" @click="cerrarPuntoVenta()" :disabled="selectPuntoVneta==='0' || passsCmbio===''" >Aceptar</button>      
                                <button v-else-if="tipoAccion===2" type="button" class="btn btn-primary" @click="cerrarPuntoVenta()" :disabled="passsCmbio===''" >Aceptar</button> 
                            </div>
                            <div v-else>
                                <button v-if=" tipoAccion===3" type="button" class="btn btn-primary">Aceptar</button>      
                                <button v-else-if="tipoAccion===2" type="button" class="btn btn-primary">Aceptar</button> 
                            </div>
                        </div>                
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
          
            isSubmitting: false,
            offset:3,

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

            arrayPuntosVentas:[],
            selectPuntoVneta:"0",
            passsCmbio:"",

            ventana_0:0,

              //---permisos_R_W_S
              puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
            id_cufd:'',
            id_cuis:'', 

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
  parseXML(xmlString,data) {
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
   
                const codigoPuntoVenta_1= xmlDoc.querySelector('codigoPuntoVenta');    
            if (data===1) {                            
                me.crear(codigoPuntoVenta_1.textContent);
              //  me.cerrarModal('registrar');
            } else {
                if (data===2) {
                    console.log("***"+codigoPuntoVenta_1.textContent);
                console.log("+++"+me.sucursalSeleccionada);
                me.eliminar_puntoVenta();   
              //  me.cerrarModal('cerrar_PV');  
                } else {                    
                        me.cerrarModal('registrar');  
                        me.cerrarModal('cerrar_PV'); 
                        Swal.fire("Error de data!","","warning",);                   
                }           
            }
                    
           
        } else {
            if (trasaccion.textContent==='false') {
                let cadena_nombre="";
                Array.from(mensajesList.children).forEach(child => {
        cadena_nombre += `${child.tagName}: ${child.textContent.trim()}\n`;   
         console.log(`${child.tagName}: ${child.textContent}`);         
        });
                if (data===1) {
                
                    me.cerrarModal('registrar');                
                } else {
                     if (data===2) {
                        me.cerrarModal('cerrar_PV'); 
                     } else {                       
                            me.cerrarModal('registrar');  
                        me.cerrarModal('cerrar_PV'); 
                        Swal.fire("Error de data!","","warning",);  
                     
                     }            
                                 
                }
                Swal.fire("Punto de venta!",""+cadena_nombre,"warning",); 
          //  const codigoCuis= xmlDoc.querySelector('codigo');       
          //  const fechaCuis= xmlDoc.querySelector('fechaVigencia');   
        //const mensajesList = xmlDoc.querySelector('mensajesList'); 
        
      
       
            } else {
               
                me.cerrarModal('registrar');
                Swal.fire("Error!","transacción nula","error",);  
            }            
        }        
    }   
  
  },

  parseXML_eliminar(xmlString,data,id,id_cuis,id_cufd,id_emisor) {
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
          
               
            if (data===1) {                            
                const codigoCuis= xmlDoc.querySelector('codigo');       
            const fechaCuis= xmlDoc.querySelector('fechaVigencia');    
            me.insertarCuis(id, codigoCuis.textContent, fechaCuis.textContent);
            } else {
                if (data===2) {
                    console.log("resultado....");
                console.log(xmlDoc);                
                me.EliminarCuis(id,id_cuis,id_cufd,id_emisor);  
              //  me.cerrarModal('cerrar_PV');  
                } else {                  
                Swal.fire("Error de entrada!","revise la entrada  si es eliminacion o adicion, tipo transacción","warning",);          
                }
           
            }
                     
           
        } else {
            if (trasaccion.textContent==='false') {
                let cadena_nombre="";
                Array.from(mensajesList.children).forEach(child => {
        cadena_nombre += `${child.tagName}: ${child.textContent.trim()}\n`;   
         console.log(`${child.tagName}: ${child.textContent}`);         
        });
              
                Swal.fire("Punto de venta!",""+cadena_nombre,"warning",); 
          //  const codigoCuis= xmlDoc.querySelector('codigo');       
          //  const fechaCuis= xmlDoc.querySelector('fechaVigencia');   
        //const mensajesList = xmlDoc.querySelector('mensajesList');      
       
            } else {
        
                Swal.fire("Error!","transacción nula","error",);  
            }            
        }        
    }   
  
  },

           //---------------------------------permiso de ver lista de sucursales tiendas almacenes
    
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

       solicitarCuis(codigo_siat,id,id_punto_venta)
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
                                    
                    var url='/siat_cuis_cufd/cuis?emisor='+id_punto_venta+'&codigo_siat='+codigo_siat+'&nit='+me.nit+'&codigo_ambiente='+me.codigoAmbiente+'&codigo_sistema='+me.codigoSistema+'&modalidad='+me.codigoModalidad+'&token_delegado='+me.token_delegado+"&cuis_end="+3;                        
                   console.log(url);
                    axios.get(url)
                    .then(function (response) {
                        var respuesta = response.data;   
                        console.log("*************solicitud**************");
                        console.log(respuesta);                   
                        if (respuesta.error==null || respuesta.error=="") {
                            me.parseXML_eliminar(respuesta,1,id,0,0,0);   
                         
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
//----------------------
insertarCuis(id,cuis,fecha){
        let me = this;    
                axios.post("/siat_emisor/insertar_cuis", {
                id:id,
                cuis:cuis,
                fecha:fecha,
                
                id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"insercion de cuis desde emisor",                  
                })
                .then(function (response) {
               
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
cerrarOperaciones(codigo_siat,id,cuis,id_cufd,id_cuis,id_emisor)
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
                    var url='/siat_cuis_cufd/cerrarOperaciones?emisor='+id_emisor+'&codigo_siat='+codigo_siat+'&nit='+me.nit+'&codigo_ambiente='+me.codigoAmbiente+'&codigo_sistema='+me.codigoSistema+'&modalidad='+me.codigoModalidad+'&token_delegado='+me.token_delegado+"&cuis_end="+2+"&id="+id+"&cuis="+cuis;                        
                    axios.get(url)
                    .then(function (response) {
                        var respuesta = response.data; 
                        if (respuesta==="error_1") {
                            Swal.fire("Error!","Debe cerrar todos los puntos de venta en esta sucursal antes de usar esta opcion. Debe ir a Siat/emisor y cerrar el punto de venta que quiere.","error",);    
                        } else {                                             
                            me.id_cufd=id_cufd;
                            me.id_cuis=id_cuis; 
                           me.parseXML_eliminar(respuesta,2,id,id_cuis,id_cufd,id_emisor);
                     
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

            EliminarCuis(id,id_cuis,id_cufd){
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
 //----------------------
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



            cerrarPuntoVenta(){
                let me=this;                
            me.isSubmitting = true; // Deshabilita el botón
                axios.get("/siat_emisor/cerrar_puntoVenta?codigoAmbiente="+me.codigoAmbiente+"&codigoPuntoVenta="+me.selectPuntoVneta+"&codigoSistema="+me.codigoSistema+"&codigoSucursal="+me.codigoSucursal+"&cuis="+me.cuis+"&nit="+me.nit+"&token_delegado="+me.token_delegado+"&passsCmbio="+me.passsCmbio, {                
                   
                  
                })
                .then(function (response) {
                 
            me.listarIndex(); 
                    let respuesta=response.data;             
                    console.log(respuesta);
                    if (respuesta=="error") {
                        me.isSubmitting=false;                 
                        Swal.fire("Error!","contraseña incorrecta","error",);    
                    } else {
                        me.parseXML(respuesta,2);    
                    }                                                 
                })               
                .catch(function (error) {                
                  console.log(error);                
            });  
            },

            consultarPuntoV(data){
                
                let me=this;  
                me.isSubmitting=true;
                 var url='/siat_emisor/consultar_PuntoV_siat?codigoAmbiente='+me.codigoAmbiente+"&codigoSistema="+me.codigoSistema+"&codigoSucursal="+me.codigoSucursal+"&cuis="+me.cuis+"&nit="+me.nit+"&token_delegado="+me.token_delegado;                     
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;     
                    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(respuesta, "text/xml");    
    console.log(xmlDoc);    
    // const respuestaCuis = xmlDoc.getElementsByTagName("RespuestaCuis")[0].childNodes[0];
    const respuestaCuis_2 = xmlDoc.getElementsByTagName("faultstring")[0];
    if (respuestaCuis_2!=undefined) {
        Swal.fire("Error",""+respuestaCuis_2.textContent,"error",);
    }else{      
        // Recorrer todos los hijos de 'mensajesList' mensajesList
        const trasaccion= xmlDoc.querySelector('transaccion');
        if (trasaccion.textContent === "true") {
       
    const trasaccion = xmlDoc.querySelector("transaccion");
    const RespuestaConsultaPuntoVenta = xmlDoc.querySelector("RespuestaConsultaPuntoVenta");

    if (RespuestaConsultaPuntoVenta) {
        // Obtener TODOS los elementos <listaPuntosVentas>
        const listaPuntosVentas = RespuestaConsultaPuntoVenta.querySelectorAll("listaPuntosVentas");

        console.log("Transacción:", trasaccion.textContent);
        console.log("RespuestaConsultaPuntoVenta:", RespuestaConsultaPuntoVenta);
        console.log("Cantidad de listaPuntosVentas:", listaPuntosVentas.length);
        
        let cadenaPuntosVentas = ""; // Inicializamos la cadena

        me.arrayPuntosVentas=[];

listaPuntosVentas.forEach((puntoVenta, index) => {
    const codigo = puntoVenta.querySelector("codigoPuntoVenta")?.textContent || "N/A";
    const nombre = puntoVenta.querySelector("nombrePuntoVenta")?.textContent || "N/A";
    const tipo = puntoVenta.querySelector("tipoPuntoVenta")?.textContent || "N/A";

    cadenaPuntosVentas += `Consulta ${index + 1}:\n`;
    cadenaPuntosVentas += `  Código: ${codigo}\n`;
    cadenaPuntosVentas += `  Nombre: ${nombre}\n`;
    cadenaPuntosVentas += `  Tipo: ${tipo}\n`;
    cadenaPuntosVentas += "-------------------\n";
    
          // Guardamos el objeto en el array
          me.arrayPuntosVentas.push({
                consulta: index + 1,
                codigo: codigo,
                nombre: nombre,
                tipo: tipo
            });

});
if (data===1) {
    Swal.fire({
            title: "",
            html: `<pre>${cadenaPuntosVentas}</pre>`, // Usamos <pre> para respetar saltos de línea
            icon: "success"
        }); 
} else {
    if (data===2) {
           me.tipoAccion=2;
        me.abrirModal('cerrar_PV',999);
    } else {
        me.isSubmitting=false;
        Swal.fire("Error!","error de entrada interno","error",);   
    }
}
    } else {    
        me.isSubmitting=false;  
        Swal.fire("Error!","No se encontró RespuestaConsultaPuntoVenta","warning",);   
    }
}
 else {
            if (trasaccion.textContent==='false') {
                me.isSubmitting=false;
          //  const codigoCuis= xmlDoc.querySelector('codigo');       
          //  const fechaCuis= xmlDoc.querySelector('fechaVigencia');   
        const mensajesList = xmlDoc.querySelector('mensajesList'); 
        let cadena_nombre="";
       Array.from(mensajesList.children).forEach(child => {
        cadena_nombre += `${child.tagName}: ${child.textContent.trim()}\n`;   
          console.log(`${child.tagName}: ${child.textContent}`);         
        });
        Swal.fire("Problemas con  envio de datos!",""+cadena_nombre,"warning",); 
            } else {
                me.cerrarModal('registrar');
                Swal.fire("Error!","transacción nula","error",);  
            }            
        }        
    }          
                                 
                })
                .catch(function(error){
                    error401(error);
                }); 

            },

            quitarCaja(id,id_cuis){
                let me=this;
                if (id_cuis===null) {
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
                   
                } else {
                    Swal.fire("Error!","debe dar de baja primero el cuis","error",);  
                }               
            },



        subir_caja(){
            let me=this;
            // me.isSubmitting = true; // Deshabilita el botón
            me.isSubmitting=true;
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

        eliminar_puntoVenta(data){
            let me=this;
        
                axios.post("/siat_emisor/eliminar_puntoVenta", {
                    id:me.id,
                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"Eliminacion de punto de venta",              
                })
                .then(function (response) {
                    me.cerrarModal('cerrar_PV');
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
                    me.parseXML(respuesta,1);                
                }).catch(function (error) {
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
          me.listarIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           console.log(accion+"---");
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
                    me.tipoAccion =1;
                    me.ventana_0=1;
                    me.isSubmitting=false;
                    break;
                }   
                case "cerrar_PV":{
                    me.tituloModal="Cerrar punto de venta";                
                    me.classModal.openModal("cerrar_PV");
                    console.log("--------");
                    console.log(data);
                    me.isSubmitting=false;
                    if (data==999) {                       
                        me.selectPuntoVneta='0';
                        me.tipoAccion =3;
                    }else{
                        console.log(data);
                        me.selectPuntoVneta=data.id_punto_venta;
                        me.id=data.id;
                        me.tipoAccion =2;
                    }
                  
                    //  me.ventana_0=2;
                  //  if (me.tipoAccion===2) {
                  //      me.selectPuntoVneta=data.id;
                  //      me.tipoAccion =3;
                  //  } else {                       
                  //      me.selectPuntoVneta='0';
                  //      me.tipoAccion =2;
                  //  }
                   
                    me.passsCmbio="";
                    break;
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
                    me.tipoAccion=1;
                    me.ventana_0=0;
            }
            if (accion == "caja") {
                me.classModal.closeModal(accion);
                me.tituloModal = "";
                me.selectCaja="0";
                me.id="";
                me.tipoAccion=1;
                me.isSubmitting=false;
            }  
            if (accion == "cerrar_PV") {
                me.classModal.closeModal(accion);
                me.tituloModal="";
                me.arrayPuntosVentas=[];
                me.selectPuntoVneta="0";
                me.passsCmbio="";
                me.tipoAccion=1;
                me.ventana_0=0;
                me.id="";
                me.isSubmitting=false;
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
          //-------permiso E_W_S-----
          this.listarPerimsoxyz();        
            //-----------------------
        this.listar_config_siat();
        this.classModal.addModal("registrar");
        this.classModal.addModal("caja");       
        this.classModal.addModal("cerrar_PV");
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
