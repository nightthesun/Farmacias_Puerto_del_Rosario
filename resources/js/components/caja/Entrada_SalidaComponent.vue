<template>
    <main class="main">
   <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <!-- inicio de index -->
 
        <div v-if="limite_meseje===0">

        </div>  
        <div v-else class="container-fluid" >
            <div class="card">                
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Apertura / Cierre de cajas               
                    <button type="button" class="btn btn-secondary" @click="verificadorAperturaCierre();verificador_moneda_sistemas();"  :disabled="sucursalSeleccionada === 0 || selectEntradaSalida === 0 || bloqueador ===0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada === 0 ||  selectEntradaSalida === 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar una sucursal.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-1" style="text-align: right">
                     <label for="">Almacen/Tienda:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada"  @change="cambiarEstadoSucursal()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.id_tienda===null"
                                        v-text="sucursal.codigoS +' -> ' +sucursal.codigo+' '+sucursal.razon_social"
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Buscar por usuario, codigo,receptor o emisor"
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"  
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>             
            <div class="form-group row" :hidden="sucursalSeleccionada === 0" :disabled="sucursalSeleccionada === 0">
                <div class="col-md-1" style="text-align: center">
                    <label for=""></label>
                </div>
                <div class="col-md-5">
                    <label for="Apectura / Cierre:">Entrada / Salida:</label>
                <select class="form-control" v-model="selectEntradaSalida" @change="listarIndex(0)">
                    <option value="0" disabled selected >Seleccionar...</option>
                    <option value="1" >Entrada</option> 
                    <option value="2" >Salida</option>                 
                </select>
           
                </div>
                <div class="col-md-3">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-3">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
            </div>    
        
  <br>
  <div v-if="selectEntradaSalida ===0" class="alert alert-warning" role="alert">
  Debe seleccionar una entrada o salida
</div>
<div v-else>
  <!---inserte tabla-->
  <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Codigo.</th>
                        <th class="col-md-2">Fecha / Hora</th>
                        <th class="col-md-1">Valor total</th>
                        <th class="col-md-2">Receptor / Emisor</th>
                        <th class="col-md-3">Observación</th>                        
                        <th class="col-md-2">Usuario</th>
                        
                    </tr>
                </thead>  
            <tbody>
                <tr v-for="i in arrayIndex" :key="i.id">                    
                <td class="col-md-1">
                    <button type="button" class="btn btn-info" style="margin-right: 5px; color: whitesmoke;" @click="abrirModal('re_imprecion',i);">
                        <i class="fa fa-print" aria-hidden="true"></i></button>
                    
                    <button type="button" class="btn btn-warning" style="margin-right: 5px; color: whitesmoke;" @click="abrirModal('ver',i);">
                        <i class="fa fa-eye" aria-hidden="true"></i></button>
                </td>
                <td class="col-md-1" style="text-align: right;">{{ i.id }}</td>
                <td class="col-md-2">{{ i.created_at }}</td> 
                <td class="col-md-1" style="text-align: right;">{{ i.valor }}</td>
                <td class="col-md-2">{{ i.mensaje }}</td>
                <td class="col-md-3">{{ i.observacion }}</td>
                <td class="col-md-2">{{ i.name }}</td>
            </tr> 
            </tbody>           
        </table>  
            <!-----fin de tabla------->
            <nav>
                    <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent=" cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
</div>
          
        </div>


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
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-1">Monto</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-2">Tipo</th>
                                    <th class="col-md-1">Valor</th>
                                    <th class="col-md-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                <tr v-for="a in arrayMoneda" :key="a.id">
                                    <td  class="col-md-1" style="text-align: right;">{{a.unidad_entera}}</td>
                                    <td class="col-md-1">{{a.unidad}}</td>
                                    <td  class="col-md-2">{{a.tipo_corte}}</td>
                                    <td  class="col-md-1" style="text-align: right;">{{a.valor_default}}</td>
                                    <td  class="col-md-3"> <input type="text" style="text-align: right;" class="form-control" placeholder="Solo valores enteros" v-model="input[a.id]"  @input="validateIntegerInput(a.id,a)" />
                                    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2">Cant. Moneda</th>
                                    <th class="col-md-2">Monto Moneda</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-2">Cant. Billete</th>
                                    <th class="col-md-2">Monto Billete</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-2">Monto Total</th>
                                    <th class="col-md-1">Simbolo</th>
                                 </tr>
                            </thead>  
                            <tbody>
                                <tr>
                                    <td class="col-md-2" style="text-align: right;">{{cantidadMonedas}}</td>
                                    <td class="col-md-2" style="text-align: right;">{{totalMonedas}}</td>
                                    <td class="col-md-1">{{SimboloM}}</td>
                                    <td class="col-md-2" style="text-align: right;">{{cantidadBilletes}}</td>
                                    <td class="col-md-2" style="text-align: right;">{{ totalBilletas }}</td>
                                    <td class="col-md-1">{{SimboloB}}</td>
                                    <td class="col-md-2" style="text-align: right; background-color:darkred; color: azure;">{{totalMonto}}</td>
                                    <td class="col-md-1">{{SimboloB}}</td>
                                    
                                </tr>    
                            </tbody>          
                        </table>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-4">{{ titulo_ }}</th>
                                    <th class="col-md-4">Observación</th> 
                                    <th class="col-md-3">Código</th> 
                                    <th class="col-md-1">Estado</th>                              
                                 </tr>
                            </thead>  
                            <tbody>
                                <tr>
                                    <td class="col-md-4" style="text-align: right;">
                                        <input type="text" class="form-control" v-model="emisor"/>    
                                    </td>
                                    <td class="col-md-4" style="text-align: right;">
                                        <input type="text" class="form-control" v-model="Obs"/>   
                                    </td> 
                                    <td class="col-md-3">
                                        <div class="d-flex align-items-center" v-if="selectEntradaSalida==='1'">
                                            <button type="button" class="btn btn-light me-2" disabled>
                                                <i class="fa fa-repeat" aria-hidden="true"></i>
                                            </button>
                                            <input type="text" class="form-control"  disabled/>
                                        </div>
                                      
                                        <div v-else class="d-flex align-items-center">
                                            <button type="button" class="btn btn-primary me-2" :disabled="password===''" @click="validatePassword()">
                                                <i class="fa fa-repeat" aria-hidden="true"></i>
                                            </button>
                                            <input type="password" class="form-control" v-model="password"/>
                                       
                                        </div>                                        
                                    </td>
                                    <td class="col-md-1" style="text-align: center; vertical-align: middle;">
                                        <i v-if="foco===0" class="fa fa-bell-slash" aria-hidden="true" style="color: dimgray; font-size: 20px;"></i>
                                        <i v-else-if="foco===1" class="fa fa-bell" aria-hidden="true" style="color: green; font-size: 20px;"></i>
                                        <i v-else class="fa fa-bell" aria-hidden="true" style="color: red; font-size: 20px;"></i>
                                                                     
                                    </td> 
                                </tr>    
                            </tbody>          
                        </table>
            
                    </div>
                
                             
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registro()">Guardar</button>                         
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>                         
                            </div>
                        </div>
                   
                  
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
         <!--Inicio del modal VER-->
         <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="ver" aria-hidden="true" data-backdrop="static" data-key="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('ver')">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">                      
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Fecha/Hora</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Codigo Entrada/Salida</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Codigo Arqueo</th>                                    
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Responsable</th>  
                                    <th class="col-md-4" style="font-size: 11px; text-align: center">Observación</th>                       
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Usuario</th>
                                                            
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_created_at}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ver_codigo_E_S}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ver_codigo_arqueo}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_responsable}}</td>
                                    <td class="col-md-4" style="font-size: 11px; text-align: center">{{ver_observacion}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_usuario}}</td>
                                 
                                </tr>
                            </tbody> 
                        </table> 
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Cantidad monedas</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Monto en monedas</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Cantidad Billetes</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Monto en Billetes</th>
                                    <th class="col-md-4" style="font-size: 11px; text-align: center">Monto Total</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr> 
                                 
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_cantidad_moenda}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_monto_moneda+" "+ver_simbolo }}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_cantidad_billete}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_monto_billete+" "+ver_simbolo}}</td>
                                    <td class="col-md-4" style="font-size: 11px; text-align: center">{{ver_monto_total+" "+ver_simbolo}}</td>
                                </tr>
                            </tbody>   
                        </table> 
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Unidad</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Simbolo</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Tipo</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Valor</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Cantidad</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                              
                                <tr v-for="m in arrayMonedaModal" :key="m.id">   
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{m.unidad_entera}}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{m.unidad}}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{m.tipo_corte}}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{(m.valor * m.cantidad).toFixed(2)}}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{m.cantidad}}</td>
                                </tr>
                            </tbody>   
                        </table>                       
                    </div>                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('ver')">
                            Cerrar
                        </button>
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
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
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
            offset:3,
            isSubmitting: false, // Controla el estado del botón de envío
            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            arrayMoneda:[],
            buscar:"",
            tipoAccion:1,
            id_sucursal:0,
            bloqueador:0,
            moneda_s1:'',


      selectEntradaSalida:0,

      totalMonedas:"0.00",
            SimboloM:'S/N',
            SimboloB:'S/N',            
            totalBilletas:"0.00",
            totalMonto:"0.00",
            cantidadMonedas:0,
            cantidadBilletes:0,           
            selectTurno:0,
            input:{},

            emisor:'',
            Obs:'',
            password:'',

            titulo_:'',

            id_apertura_cierre:'',
            arrayIndex:[],
            foco:0,

            arrayMonedaVentana:[],

            ver_created_at:'',
            ver_codigo_E_S:'',
            ver_codigo_arqueo:'',
            ver_responsable:'',
            ver_observacion:'',   
            ver_usuario:'',

            ver_cantidad_moenda:'',
            ver_monto_moneda:'',
            ver_cantidad_billete:'',
            ver_monto_billete:'',
            ver_monto_total:'',
            ver_simbolo:'',
            arrayMonedaModal:[],

            limite_meseje:0,

            //limitado 
            startDate: '',
            endDate: '',
            //apertura 
            arrayExisteApertura:[],
        };
    },

    

    computed: {
        sicompleto() {
           let me = this;
          
            if (
                (me.input).length ==0
             
            )
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
    watch: {
        sucursalSeleccionada: function (newValue) {
           
        let s = this.arraySucursal.find(
                    (element) => element.codigo === newValue);
            if (s) {               
                this.id_sucursal = s.id_sucursal;  
            }        
        }
    },
    methods: {

////////--------------------- STAR PDF--------------------///////////////
general_pdf(razon_social,direccion,lugar,cadena_A,id,soloFecha,soloHora,mensaje,observacion,valor,simbolo,user){
    const documentDefinition = {
        pageMargins: [6, 8, 6, 4], // Configura los márgenes en cero
        pageSize: {
    width: 80 * 2.83465, // Ancho en puntos (conversión a puntos desde mm)
    height: 'auto',
    columnGap: 2,
  },
  content: [
    { text: razon_social.toUpperCase(), style: 'header'},
    { text: direccion.toUpperCase(), style: 'header_2'},
    { text: lugar.toUpperCase(), style: 'header_2'},
    {
       text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
       style:'linea_2' 
    },
    { text: 'CAJA '+cadena_A, style: 'header_2'},
    {
       text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
       style:'linea_2' 
    },
    {style: 'datos_f',
		table: {
				body: [
					['NUMERO DE '+cadena_A+':',id],
					['FECHA: ', soloFecha+' HORA: '+soloHora],
                    ['RESPONSABLE: ', mensaje.toUpperCase()],
				]
			},
            	layout: 'noBorders'
		},
        
        {
       text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
       style:'linea_2' 
    },
    {style: 'datos_f',
		table: {
				body: [
					['DESCRIPCIÓN: ',observacion.toUpperCase()],
					['MONTO: ', valor+' '+simbolo],  
                    ['USUARIO: ', user.toUpperCase()]               
				]
			},
            	layout: 'noBorders'
		},
      
  ],
  styles: {
          header: {
            fontSize: 7,
            bold: true,
            alignment: 'center',         
          },
          header_2: {
            fontSize: 7,            
            alignment: 'center',         
          },
          linea_2: {
            fontSize: 9,
            margin: [1, 1, 1, 1],
            alignment: 'center',  
            },
        datos_f: {
            fontSize: 7,         
            alignment: 'left',         
          },  
    }    
   }; 
   // Genera el PDF y abre una nueva ventana con el documento
      pdfMake.createPdf(documentDefinition).open(); 
},
/////////////////////////////END PDF/////////////////////////////////////

        listarLimite(){
            let me = this;            
            var url ="/entrada_salida/getlimite";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                  
                    let respues_1=respuesta.monto_limite;
                    let respues_2=respuesta.tiempo_limite;
                    
                    if (respues_1==="0.00" || respues_1===null || respues_1===""||respues_2<=0||respues_2==="") {
                        me.limite_meseje=0
                        Swal.fire("No se tiene limite de salida",
                    "Para configurar esta parte, entrea Administracion/Configuración en la pestaña de limite de transacción.",
                    "warning",
                    );
                    }else{
                        me.limite_meseje=1;
                    }        
                
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        modalMoneda(id) {
            let me = this;           
            var url ="/entrada_salida/monedaModal_vista?id_arqueo="+id;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayMonedaModal=respuesta;           
                
                })
                .catch(function (error) {
                    error401(error);
                });
        },

    validatePassword() {
            let me = this;           
            var url ="/entrada_salida/validate-password?password="+me.password;
            axios.post(url)
                .then(function (response) {
                    var respuesta = response.data;
                    if (respuesta===1) {
                       me.foco=1; 
                    } else {
                       me.foco=2; 
                    }
              
                })
                .catch(function (error) {
                    error401(error);
                });
        },


        listarIndex(page) {
            let me = this;    
       
            var url ="/entrada_salida/index?page="+page+"&buscar="+me.buscar+"&id_sucursal="+me.id_sucursal+"&entrada_salida="+parseInt(me.selectEntradaSalida)+"&ini="+me.startDate+"&fini="+me.endDate;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.respuesta.data;
                 
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        registro(){
            let me = this;
              // Si ya está enviando, no permitas otra solicitud
    
            let bandera=0;
            let cadena_A="";
            if ((me.SimboloM==="S/N"&&me.SimboloB==="S/N")||me.totalMonto==="0.00") {
                Swal.fire(
                    "Sector de monedas sin dados",
                    "Haga click en Ok",
                    "warning",
                    );
                me.cerrarModal('registrar');    
            } else {
                if (me.selectEntradaSalida==="1") {
                    if (me.emisor===""||me.Obs==="") {
                        Swal.fire(
                    "Emisor o la observacion esta nula",
                    "Haga click en Ok",
                    "warning",
                    );
                    } else {
                        //// datos de registro
                        cadena_A="ENTRADA";
                        bandera=1;
                    }   
                } else {
                    if (me.selectEntradaSalida==="2") {
                        if (me.emisor===""||me.Obs===""||me.password==="") {
                        Swal.fire(
                    "Emisor o la observacion esta nula, puede revisar el codigo tambien si es el correcto",
                    "Haga click en Ok",
                    "warning",
                    );
                    } else {
                    //// datos de registro
                        if (me.foco===1) {
                        
                            cadena_A="SALIDA";
                            bandera=1; 
                        } else {
                            Swal.fire(
                    "Codigo de salida erroneo.",
                    "Haga click en Ok",
                    "warning",
                    );
                        }
                    
                    }
                            } else {
                              Swal.fire(
                                 "error de entrada o salida",
                                      "Haga click en Ok",
                                    "warning",
                                         );
                                    me.cerrarModal('registrar');  
                                     }
                }
            }
            //---------------REGISTRO
            if (bandera===1) {
                if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
                axios.post("/entrada_salida/store", {
                  
                       total_arqueo_caja:me.totalMonto,
                        cantidadBilletes:me.cantidadBilletes,
                        totalBilletas:me.totalBilletas,
                        cantidadMonedas:me.cantidadMonedas,
                        totalMonedas:me.totalMonedas,
                        moneda_s1:me.moneda_s1,
                        simbolo:me.SimboloB,
                                                
                    	id_sucursal:me.id_sucursal,
                        obs:me.Obs,
                        mensaje:me.emisor,
                        entrada_salida:me.selectEntradaSalida,      
                        
                        input:me.input,
                        arrayMoneda:me.arrayMoneda,  
                        
                        id_apertura_cierre:me.id_apertura_cierre
                       
                    })
                    .then(function (response) {
                        me.listarIndex();                       
                        let respuesta = response.data;                                      
                        let razon_social=respuesta.titulo.razon_social;                        
                        let direccion=respuesta.titulo.direccion;
                        let lugar=respuesta.titulo.nombre+" - "+respuesta.titulo.ciudad;
                        let id=respuesta.id;
                        let soloFecha=respuesta.soloFecha;
                        let soloHora=respuesta.soloHora;
                        let mensaje=respuesta.mensaje;
                        let observacion=respuesta.observacion;
                        let valor=respuesta.valor;
                        let simbolo=respuesta.simbolo;
                        let user=respuesta.user;                           
                       //console.log("--->"+razon_social+" "+direccion+" "+lugar+" "+id+" "+soloFecha+" "+soloHora+" "+mensaje+" "+observacion+" "+valor+" "+simbolo+" "+user); 
                       me.cerrarModal('registrar');                        
                        Swal.fire(
                        "Registrado exitosamente",
                        "Haga click en Ok",
                        "success"
                        );
                        me.general_pdf(razon_social,direccion,lugar,cadena_A,id,soloFecha,soloHora,mensaje,observacion,valor,simbolo,user);

                    }).catch(function (error) {                 
                if (error.response) {               
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            })
        .finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
    
            me.cerrarModal('registrar');
            } 
        },

        validateIntegerInput(id,index) {
            let me = this;
            me.cantidadMonedas=0;
           me.cantidadBilletes=0;
           me.totalMonedas=0;
           me.totalBilletas=0;
           me.totalMonto=0;
    me.input[id] = this.input[id].replace(/[^0-9]/g, '');
    if ( me.input[id]===""|| me.input[id]===null) {
        me.input[id]=0; 
        let aa=me.arrayMoneda[id-1];
        aa.valor_default="0.00";       
        aa.input=  me.input[id];
         
       
    }else{     
    
        let aa=me.arrayMoneda[id-1]; 
        aa.valor_default=Number(me.input[id] * aa.valor).toFixed(2);
        aa.input=me.input[id];
      
    }
    me.arrayMoneda.forEach(element => {
            if (element.tipo_corte==="Moneda") {
                me.cantidadMonedas=me.cantidadMonedas+  parseInt(element.input, 10);                
                me.totalMonedas = Number(me.totalMonedas) + Number(element.valor_default);
                // Si deseas que el resultado final esté formateado a dos decimales
                me.totalMonedas = Number(me.totalMonedas).toFixed(2);
                me.SimboloM=element.unidad;
      
            }
            if (element.tipo_corte==="Billete") {
                me.cantidadBilletes=me.cantidadBilletes+  parseInt(element.input, 10);             
                me.totalBilletas = Number(me.totalBilletas) + Number(element.valor_default);
                // Si deseas que el resultado final esté formateado a dos decimales
                me.totalBilletas = Number(me.totalBilletas).toFixed(2);
                me.SimboloB=element.unidad;
    
            }
            me.totalMonto=Number(me.totalMonto)+ Number(element.valor_default);   
            me.totalMonto=Number(me.totalMonto).toFixed(2);
        });
  },

        verificador_moneda_sistemas(){
            let me=this;
            var url = "/verificador_moneda_sistemas";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta_lista = response.data.listaMoneda;
                    var respuesta_moneda = response.data.moneda;
                  if (respuesta_moneda===0) {
                    me.bloqueador=0;
                    Swal.fire(
                    "No se activo el tipo de moneda necesita activar algun tipo de moneda.",
                    "Para activar necesita ir a configuracion y ver la pestaña de tipo de moneda.",
                    "warning",
                );
                  } else {
                    me.bloqueador=1;
                    me.arrayMoneda=respuesta_lista; 
                    me.moneda_s1=respuesta_moneda;
             
                  }                                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        verificadorAperturaCierre(){
            let me=this;
            var url = "/verificacionAperturaCierre?id_sucursal="+me.id_sucursal;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;       
                    if (respuesta===0||respuesta.tipo_caja_c_a===9||respuesta.id_apertura_cierre!=0) {
                        Swal.fire(
                    "Debe aperturar una caja",
                    "Haga click en Ok",
                    "warning",
                    );    
                    } else {
                        me.abrirModal('registrar'); 
                        me.id_apertura_cierre=respuesta.id;
                    } 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarVentanaEntradaSalida(id_arqueo){

           var url = "/entrada_salida/getmoneda?id_arqueo"+id_arqueo;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayMonedaVentana = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
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
            me.listarIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;  
                    me.isSubmitting=false;
                    if (me.selectEntradaSalida==="1") {
                            me.titulo_ ="Emisor";
                            me.tituloModal = "Registro de entradas";
                    } else {
                        if (me.selectEntradaSalida==="2") {
                            me.titulo_ ="Receptor";
                            me.tituloModal = "Registro de salidas";
                        } else { Swal.fire("ERROR...","Haga click en Ok","warning",);  
                        }
                    }
                    me.password="";
                        me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.input={};  
                        me.emisor=""; 
                        me.Obs="Sin Observación";
                        me.codigo="";
                        me.foco=0;               
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;       
            
                    me.classModal.openModal("registrar");

                    break;
                }
                case "re_imprecion":{
                    me.tipoAccion = 2;
                    let cadena_A="";
                    if (data.cadena_A===1) {
                        cadena_A="ENTRADA";
                    } else {
                        if (data.cadena_A===2) {
                            cadena_A="SALIDA";
                        } else {
                            cadena_A="E";
                        }                        
                    }
                    if (cadena_A==="E") {                  
                    } else {      
                        let createdAt = data.created_at;
                        let [date, time] = createdAt.split(' ');               
                    me.general_pdf(data.razon_social,data.direccion,data.dir,cadena_A,data.id,date,time,data.mensaje,data.observacion,data.valor,data.simbolo,data.name)
        
                    }                
                   break;
                }
                case "ver": {
          
                    let cadena_A="";
                    if (data.cadena_A===1) {
                        cadena_A="ENTRADA";
                    } else {
                        if (data.cadena_A===2) {
                            cadena_A="SALIDA";
                        } else {
                            cadena_A="Error";
                        }                        
                    }                    
                    me.tituloModal="TIPO DE VISTA "+cadena_A+" EN "+data.razon_social.toUpperCase();
                    me.ver_created_at=data.created_at;
                    me.ver_codigo_E_S="000"+data.id;
                    me.ver_codigo_arqueo="000"+data.num_arqueo;
                    me.ver_responsable=(data.mensaje).toUpperCase();
                    me.ver_observacion=(data.observacion).toUpperCase();                                    
                    me.ver_usuario=(data.name).toUpperCase();  
                    me.ver_cantidad_moenda=data.cantidad_moneda;
                    me.ver_monto_moneda=data.total_moneda;
                    me.ver_cantidad_billete=data.cantidad_billete;
                    me.ver_monto_billete=data.total_billete;
                    me.ver_monto_total=data.valor; 
                    me.ver_simbolo=data.simbolo;  
                    me.modalMoneda(data.num_arqueo);      
                    me.classModal.openModal("ver");
                break;
                }
            
            }
        },
        cambiarEstadoSucursal(){
            let me=this;
            me.selectEntradaSalida=0;
        },
    
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.isSubmitting=false;
                me.totalMonedas="0.00";
            me.SimboloM='S/N';
            me.SimboloB='S/N';            
            me.totalBilletas="0.00";
            me.totalMonto="0.00";
            me.cantidadMonedas=0;
            me.cantidadBilletes=0;           
            me.selectTurno=0;
            me.input={};
            me.password="";
            me.emisor="";
            me.Obs="";
            me.codigo="";
            me.classModal.closeModal(accion);
            me.foco=0;

            }
            if (accion == "ver") {

                me.ver_created_at="";
            me.ver_codigo_E_S="";
            me.ver_codigo_arqueo="";
            me.ver_responsable="";
            me.ver_observacion="";
            me.ver_tipo="";
            me.ver_usuario="";
            me.ver_cantidad_moenda="";
            me.ver_monto_moneda="";
            me.ver_cantidad_billete="";
            me.ver_monto_billete="";
            me.ver_monto_total="";
            me.ver_simbolo="";
            me.arrayMonedaModal=[];
            me.classModal.closeModal(accion);
            }
        },

        fecha_inicial() {
    // Obtener la fecha actual
    const today = new Date();    
    // Obtener la fecha actual menos 5 días
    const startDate = new Date();
    startDate.setDate(today.getDate() - 5);
    // Formatear el año, mes y día para la fecha de inicio
    const startYear = startDate.getFullYear();
    const startMonth = String(startDate.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const startDay = String(startDate.getDate()).padStart(2, '0');
    // Formatear el año, mes y día para la fecha final (hoy)
    const endYear = today.getFullYear();
    const endMonth = String(today.getMonth() + 1).padStart(2, '0');
    const endDay = String(today.getDate()).padStart(2, '0');
    // Asignar las fechas a los campos correspondientes
    this.startDate = `${startYear}-${startMonth}-${startDay}`;  // Fecha de inicio (5 días antes)
    this.endDate = `${endYear}-${endMonth}-${endDay}`;  // Fecha final (hoy)
},

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        this.verificador_moneda_sistemas();
        this.sucursalFiltro();
        this.listarLimite();
        this.fecha_inicial(); 
        this.classModal.addModal("registrar");
        this.classModal.addModal("ver");
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
