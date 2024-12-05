
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
                    <i class="fa fa-align-justify"></i> Tesoreria               
                    <button type="button" class="btn btn-secondary" @click="abrirModal_2();" :disabled="selectApertura_cierre!=0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: right">
                     <label for="">Sucursal: </label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="cambiarEstado()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"  :hidden="sucursal.id_tienda===null"
                                        v-text="sucursal.codigoS + ' -> ' + sucursal.codigo+' ' +sucursal.razon_social"></option>
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
                                    placeholder="Buscar por usuario o numero de docuemnto"
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="selectApertura_cierre == 1"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"  
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="selectApertura_cierre == 1"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
            
            <div class="form-group row"  :hidden="sucursalSeleccionada == 0" :disabled="sucursalSeleccionada == 0">
                <div class="col-md-2">
                     <label for=""></label>
                </div>
                <div class="col-md-4">                    
                        <label for="Apectura / Cierre:">Acción</label>
                        <select class="form-control" v-model="selectApertura_cierre" @change=" get_tiene_tesoreria(); listarIndex();">
                                    <option value=1 disabled selected>Seleccionar...</option>
                                    <option value=0>Abrir</option>
                                    <option value=9>Cerrar</option>
                        </select>                                        
                </div>
        <div class="col-md-3">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control"  v-model="startDate" :disabled="sucursalSeleccionada===0 || selectApertura_cierre===1">
        </div>
        <div class="col-md-3">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" :disabled="sucursalSeleccionada===0 || selectApertura_cierre===1">
        </div>        
            </div> 
  <br>
            <!---inserte tabla--->
            
        <div :hidden="selectApertura_cierre===1"> 
            <table  class="table table-bordered table-striped table-sm table-responsive" >
                
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Nro documento</th>
                        <th class="col-md-1">Nro arqueo</th>
                        <th class="col-md-1">Total arqueo</th>
                        <th class="col-md-1">Monto actual</th>
                        <th class="col-md-2">Observación</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-2">Usuario</th>
                        <th class="col-md-1">Estado</th>
                    </tr>
                </thead>
                <tbody>          
                    <tr v-for="i in arrayIndex" :key="i.id">  
                        <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <button type="button" class="btn btn-warning" style="margin-right: 5px; color: whitesmoke;" @click="abrirModal('ver',i);">
                                <i class="fa fa-eye" aria-hidden="true"></i></button>
                               
                                <button v-if="i.abrir_cerrar===0" @click="abrirModal('actualizar',i); verificador_moneda_sistemas();" type="button" class="btn btn-danger" style="margin-right: 5px; color: whitesmoke;">
                                    <i class="fa fa-unlock" aria-hidden="true"></i></button>  
                                <button v-else type="button" class="btn btn-secondary" style="margin-right: 5px;">
                                    <i class="fa fa-lock" aria-hidden="true"></i></button>  
                            </div>                              
                        </td>
                        <td class="col-md-1">{{ i.id }}</td>
                        <td class="col-md-1">
                            <span v-if="selectApertura_cierre==='0'">{{ i.id_arqueo_abrir }}</span>
                            <span v-else>{{ i.id_arqueo_cerrar}}</span>
                        </td>
                        <td class="col-md-1">
                            <span v-if="selectApertura_cierre==='0'">{{ i.total_arqueo_caja_abrir+" "+i.simbolo }}</span>
                            <span v-else>{{ i.total_arqueo_caja_cerrar+" "+i.simbolo }}</span>
                        </td>
                        <td class="col-md-1">{{ i.monto_actual_abrir +" "+i.simbolo}}</td>
                        <td class="col-md-1">
                            <span v-if="selectApertura_cierre==='0'">{{ i.comentario_abrir }}</span>
                            <span v-else>{{ i.comentario_cerrar }}</span>
                        </td>
                        <td class="col-md-1">
                            <span v-if="selectApertura_cierre==='0'">{{ i.created_at }}</span>
                            <span v-else>{{ i.updated_at }}</span>
                        </td>
                        <td class="col-md-1">{{ i.name }}</td>
                        <td class="col-md-1">
                            <span v-if="i.abrir_cerrar===0" class="badge badge-pill badge-success">Abierto</span>
                            <span v-else class="badge badge-pill badge-danger">Cerrado</span>
                        </td>
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
                        <button
                            type="button"
                            class="close"
                            aria-label="Close"
                            @click="cerrarModal('registrar')"
                        >
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
                                    <th class="col-md-2">Monto anterior</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-2">Monto sumado</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-6">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-2" style="text-align: right;">{{ monto_anteriror_X }}</td>
                                    <td class="col-md-1">{{ SimboloB }}</td>
                                    <td class="col-md-2" style="text-align: right;">{{ monto_sumado_X }}</td>
                                    <td class="col-md-1">{{ SimboloB }}</td>
                                    <td class="col-md-2"><input type="text" class="form-control" placeholder="Escriba la observación"  v-model="observacion_X"/>  </td>
                                </tr>
                            </tbody>
                        </table>        
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" 
                           @click="registro()" :disabled="observacion_X===''">Guardar</button>
                        <button @click="actualizar()" type="button" v-if="tipoAccion == 2" class="btn btn-primary" :disabled="observacion_X===''">
                            Actualizar
                        </button>
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
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Codigo</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Codigo Arqueo</th> 
                                 
                                    <th class="col-md-4" style="font-size: 11px; text-align: center">Observación</th>                       
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Usuario</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Estado</th>                       
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ fecha_v3 }}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{id_index}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{cogigo_arqueo_v3}}</td>
                                                     
                                    <td class="col-md-4" style="font-size: 11px; text-align: center">{{observacion_v3}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{usuario_v3}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{estado_v3}}</td>                                    
                                 
                                </tr>
                            </tbody> 
                        </table> 
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Estado Actual</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Cant. Monedas</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Monto en Monedas</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Cant. Billetes</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Monto en Billetes</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Arqueo Total</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Monto Total</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr> 
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ estado_actual_v3+" "+ver_simbolo  }}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ver_cantidad_moenda}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_monto_moneda+" "+ver_simbolo }}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ver_cantidad_billete}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_monto_billete+" "+ver_simbolo}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ver_monto_total+" "+ver_simbolo}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{arqueo_actual_v3+" "+ver_simbolo}}</td>
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
            id_sucursal:'',
            tituloModal: '',
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:'',
            tipoAccion:1,
            startDate: '',
      endDate: '',

      selectApertura_cierre:1,

      input:{},
      arrayMoneda:[],
      moneda_s1:'',

      cantidadMonedas:0,
      cantidadBilletes:0, 
      totalMonedas:"0.00",
      totalBilletas:"0.00",
      totalMonto:"0.00",
      SimboloM:'S/N',
      SimboloB:'S/N',
      
      monto_anteriror_X:0,
      monto_sumado_X:0,
      observacion_X:'',

      arrayExiste:[],

      arrayIndex:[],

      ocultar_tabla:0,

      id_index:'',

      fecha_v3:'',
      cogigo_arqueo_v3:'',
      usuario_v3:'',
      observacion_v3:'',
      estado_v3:'',
      arqueo_actual_v3:'',
      estado_actual_v3:'',
      ver_cantidad_moenda:'',
      ver_monto_moneda:'',
      ver_simbolo:'',
      ver_cantidad_billete:'',
      ver_monto_billete:'',
      ver_monto_total:'',
      arrayMonedaModal:[],
    
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

        cambiarEstado(){
            let me=this;
            me.selectApertura_cierre=1;
        },


        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarIndex(page);
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
            me.monto_sumado_X=Number(me.monto_anteriror_X)+Number(me.totalMonto);
            me.totalMonto=Number(me.totalMonto).toFixed(2);
            me.monto_sumado_X=Number(me.monto_sumado_X).toFixed(2);
             
            
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

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
                    me.tituloModal = "Registro para abrir caja";
                    me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.input={}; 
                  
                if ((me.arrayExiste).length <= 0) {
                    me.monto_anteriror_X=(0).toFixed(2);       
                   } else {
                    me.monto_anteriror_X=(me.arrayExiste).monto_actual_abrir;
                   } 
                   me.monto_sumado_X=(0).toFixed(2);  
                   me.observacion_X="";
                  
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;                
                    me.id_index=data.id;
                 
                    me.isSubmitting=false;
                    me.tituloModal = "Registro para cerrar caja de codigo "+data.id;

                    me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.input={};              
                  
                    me.monto_anteriror_X=data.monto_actual_abrir;       
                 
                   me.monto_sumado_X=(0).toFixed(2);  
                   me.observacion_X="";

                    me.classModal.openModal("registrar");
                    break;
                }
                case "ver": {
           
                 if (data.abrir_cerrar===0) {
                    me.tituloModal = "Vista de inicio de caja";
                    me.fecha_v3=data.created_at;    
                    me.cogigo_arqueo_v3=data.id_arqueo_abrir;                  
                    me.observacion_v3=data.comentario_abrir;  
                    me.estado_v3="Abierto";  
                    me.arqueo_actual_v3=data.total_arqueo_caja_abrir;    
                    me.modalMoneda(data.id_arqueo_abrir);                    
                 } else {   
                    me.tituloModal = "Vista de cierre de caja";                    
                    me.fecha_v3=data.updated_at;  
                    me.cogigo_arqueo_v3=data.id_arqueo_cerrar;                  
                    me.observacion_v3=data.comentario_cerrar; 
                    me.estado_v3="Cerrado";
                    me.arqueo_actual_v3=data.total_arqueo_caja_cerrar;
                    me.modalMoneda(data.id_arqueo_cerrar);   
                 }  
                 me.estado_actual_v3=data.monto_actual_abrir;
                 me.ver_cantidad_moenda=data.cantidad_moneda;
                    me.ver_monto_moneda=data.total_moneda;
                    me.ver_simbolo=data.simbolo;
                    me.ver_cantidad_billete=data.cantidad_billete;
                    me.ver_monto_billete=data.total_billete;
                    me.ver_monto_total=data.total_arqueo;
                 me.usuario_v3=data.name;
                 me.id_index=data.id;
          
         // me.modalMoneda(data.num_arqueo);      
          me.classModal.openModal("ver");
      break;
      }
            
            }
        },

        actualizar(){
            let me = this;
              // Si ya está enviando, no permitas otra solicitud    
            let bandera = 0;    
            if ((me.SimboloM==="S/N"&&me.SimboloB==="S/N")||me.totalMonto==="0.00") {
                bandera=1;
                Swal.fire(
                    "Sector de monedas sin dados",
                    "Haga click en Ok",
                    "warning",
                    );
                me.cerrarModal('registrar');    
            } 
            if (me.observacion_X==="" || me.observacion_X===null) {
             bandera=2;
             Swal.fire(
                    "Datos nulos en observación",
                    "Haga click en Ok",
                    "warning",
                    );
                me.cerrarModal('registrar');    
            }
            //---------------REGISTRO
            if (bandera===0) {
                if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
                axios.post("/tesoreria/editar", {
                        id:me.id_index,
                       total_arqueo_caja:me.totalMonto,
                        cantidadBilletes:me.cantidadBilletes,
                        totalBilletas:me.totalBilletas,
                        cantidadMonedas:me.cantidadMonedas,
                        totalMonedas:me.totalMonedas,
                        moneda_s1:me.moneda_s1,
                        simbolo:me.SimboloB,                                                
                    	id_sucursal:me.id_sucursal,

                        monto_anteriror_X:me.monto_anteriror_X,
                        monto_sumado_X:me.monto_sumado_X,
                        observacion_X:me.observacion_X,
                        selectApertura_cierre:me.selectApertura_cierre,                         
                        input:me.input,
                        arrayMoneda:me.arrayMoneda,  
                    })
                    .then(function (response) {
                        me.listarIndex();                       
                        let respuesta = response.data;  
                        console.log(respuesta);                                    
                    me.cerrarModal('registrar');                        
                        Swal.fire(
                        "Registrado exitosamente",
                        "Haga click en Ok",
                        "success"
                        );
                  

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

        registro(){
            let me = this;
              // Si ya está enviando, no permitas otra solicitud    
            let bandera = 0;    
            if ((me.SimboloM==="S/N"&&me.SimboloB==="S/N")||me.totalMonto==="0.00") {
                bandera=1;
                Swal.fire(
                    "Sector de monedas sin dados",
                    "Haga click en Ok",
                    "warning",
                    );
                me.cerrarModal('registrar');    
            } 
            if (me.observacion_X==="" || me.observacion_X===null) {
             bandera=2;
             Swal.fire(
                    "Datos nulos en observación",
                    "Haga click en Ok",
                    "warning",
                    );
                me.cerrarModal('registrar');    
            }
            //---------------REGISTRO
            if (bandera===0) {
                if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
                axios.post("/tesoreria/crear", {
                  
                       total_arqueo_caja:me.totalMonto,
                        cantidadBilletes:me.cantidadBilletes,
                        totalBilletas:me.totalBilletas,
                        cantidadMonedas:me.cantidadMonedas,
                        totalMonedas:me.totalMonedas,
                        moneda_s1:me.moneda_s1,
                        simbolo:me.SimboloB,                                                
                    	id_sucursal:me.id_sucursal,

                        monto_anteriror_X:me.monto_anteriror_X,
                        monto_sumado_X:me.monto_sumado_X,
                        observacion_X:me.observacion_X,
                        selectApertura_cierre:me.selectApertura_cierre,                         
                        input:me.input,
                        arrayMoneda:me.arrayMoneda,  
                    })
                    .then(function (response) {
                        me.listarIndex(); 
                        me.get_tiene_tesoreria();                      
                        let respuesta = response.data;  
                        console.log(respuesta);                                    
                    me.cerrarModal('registrar');                        
                        Swal.fire(
                        "Registrado exitosamente",
                        "Haga click en Ok",
                        "success"
                        );
                  

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

        abrirModal_2(){
            let me =this;
         
            if ((me.arrayExiste).abrir_cerrar===0) {
                Swal.fire(
                        "Ya tiene abrierto una caja en esta sucursal",
                        "Haga click en Ok",
                        "error"
                        );            
            } else {
                me.verificador_moneda_sistemas();
                me.abrirModal('registrar'); 
            }
        },

        
        listarIndex(page) {
            let me = this;        
            var url ="/tesoreria/listarInicio?page="+page+"&buscar="+me.buscar+"&id_sucursal="+me.id_sucursal+"&abrir_cerrar="+parseInt(me.selectApertura_cierre)+"&ini="+me.startDate+"&fini="+me.endDate;
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

        get_tiene_tesoreria(){            
            let me = this;
           var url = "/tesoreria/getTesoreria?id_sucursal="+me.id_sucursal;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;                                
                    me.arrayExiste=respuesta; 
                 
                }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        fecha_inicial() {
    // Obtener la fecha actual
    const today = new Date();    
    // Obtener la fecha actual menos 5 días
    const startDate = new Date();
    startDate.setDate(today.getDate() - 10);
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

        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.input={};          
                        me.monto_anteriror_X=0;
                        me.monto_sumado_X=0;
                        me.observacion_X='';                        
                me.tituloModal = "";          
                me.inputTextBuscarProductoIngreso = "";
                me.arrayRetornarProductosIngreso = "";
            
        }
        if (accion == "ver") {
            me.id_index="";
            me.fecha_v3="";
            me.cogigo_arqueo_v3="";
            me.usuario_v3="";
            me.observacion_v3="";
            me.estado_v3="";
            me.arqueo_actual_v3="";
            me.estado_actual_v3="";
            me.ver_cantidad_moenda="";
            me.ver_monto_moneda="";
            me.ver_simbolo="";
            me.ver_cantidad_billete="";
            me.ver_monto_billete="";
            me.ver_monto_total="";
            me.arrayMonedaModal=[];

        me.classModal.closeModal(accion);
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
