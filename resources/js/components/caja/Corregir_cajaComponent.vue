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
                    <i class="fa fa-align-justify"></i> Corregir caja               
                   
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-1" style="text-align: right">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex(0)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.id_almacen!=null"
                                        v-text="sucursal.codigoS + ' -> ' + sucursal.codigo+' '+sucursal.razon_social">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Buscar por usuario, codigo de caja, codigo "
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"/>
                                <button type="submit" class="btn btn-primary"
                                @click="listarIndex(1)"  
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                        
            </div>             
    <div class="form-group row"  :hidden="sucursalSeleccionada == 0" :disabled="sucursalSeleccionada == 0">
                <div class="col-md-1">
                     <label for=""></label>
                </div>
                <div class="col-md-5">                      
                </div>
        <div class="col-md-3">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" :disabled="sucursalSeleccionada===0" @change="listarIndex(0)">
        </div>
        <div class="col-md-3">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" :disabled="sucursalSeleccionada===0" @change="listarIndex(0)">
        </div>        
    </div> 
      
  <br>
    <div class="alert alert-warning" role="alert" v-if="sucursalSeleccionada===0">
        <span>Debe elegir una opción</span>
    </div>
    <div v-else>
  <!---inserte tabla-->
  <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Codigo Nombre</th>
                        <th class="col-md-2">Nombre</th>
                        <th class="col-md-1">Nro apertura</th>
                        <th class="col-md-1">Nro cierre</th>
                        <th class="col-md-1">Nro arqueo</th>                  
                        <th class="col-md-1">Total apertura</th>
                        <th class="col-md-1">Total cierre</th>
                        <th class="col-md-1">Diferencia</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Usuario</th>
                        <th>Estado</th>       
                    </tr>
                </thead>                
                <tr v-for="i in arrayIndex" :key="i.id">  
                    <td>
                        <div  class="d-flex justify-content-start">    
                            <button type="button" v-if="i.numero_edicion!=null || i.numero_edicion===0" class="btn btn-light btn-sm" style="margin-right: 5px;"><i class="icon-pencil"></i></button>                      
                            <button type="button" v-else  class="btn btn-warning btn-sm" @click="verificador_moneda_sistemas();abrirModal('registrar',i );" style="margin-right: 5px;"><i class="icon-pencil"></i></button>           
                          
                            <button type="button" v-if="i.numero_edicion==null" class="btn btn-light btn-sm" style="margin-right: 5px; color: white;"><i class="fa fa-eye" aria-hidden="true"></i></button>
                            <button type="button" v-else class="btn btn-info btn-sm" @click="abrirModal('show',i )" style="margin-right: 5px; color: white;"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </div>                       
                    </td>
                    <td class="col-md-1">{{i.codigo}}</td>
                    <td class="col-md-2">{{i.nombre_caja}}</td>
                    <td class="col-md-1">{{i.id_apertura}}</td>
                    <td class="col-md-1">{{i.id}}</td>
                    <td class="col-md-1">{{i.id_arqueo}}</td>
                    <td class="col-md-1">{{i.total_apertura+" "+i.moneda}}</td>
                    <td class="col-md-1">{{i.total_cierre+" "+i.moneda}}</td>
                    <td class="col-md-1">{{i.diferencia_caja+" "+i.moneda}}</td>
                    <td class="col-md-1">{{i.created_at}}</td>
                    <td class="col-md-2">{{i.name}}</td>
                    <td>
                        <span :class="{ 'badge badge-pill bg-success': i.estado_caja === 'OK',
                        'badge badge-pill bg-danger': i.estado_caja === 'Sobrante' || i.estado_caja === 'Faltante',
                        'badge badge-pill bg-warning': i.estado_caja === 'Corregido'}">
                        {{ i.estado_caja }}</span>
                    </td>
                </tr>
            </table>    
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

       <div class="modal-body" style="max-height: 90vh; overflow-y: auto;">  
                   
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

                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">
                                    <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2" style=" text-align: center">Nro cierre</th>                                                               
                                    <th class="col-md-2" style=" text-align: center">Diferencia</th>                                    
                                    <th class="col-md-2" style=" text-align: center">Estado</th>
                                    <th class="col-md-6" style=" text-align: center">Motivo</th>                                   
                                 </tr>
                            </thead>
                            <tbody>
                                <td class="col-md-2" style=" text-align: center">{{id_cierre_modal}}</td>
                                <td class="col-md-2" style=" text-align: center">{{diferencia_modal}}</td>
                                <td class="col-md-2" style=" text-align: center">{{estado_modal}}</td>                        
                                <td class="col-md-6" >
                                <textarea v-model="textArea_modal" class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
                                </td>
                            </tbody> 
                      
                        </table>
                                   
        
                                </div>
                              
                            
                               
                            </div>
                        </form>
                    </div>                  
                    
                    <div class="modal-footer">
                        <button  type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="crear()">Corregir</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Corregir</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-light">Actualizar</button>
                            </div>
                        </div>                        
                    </div>

                    </div>
                </div>
            </div>
        </transition>                

       
        <!--fin del modal-->
        <!--Inicio del modal ver-->
        <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
               <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('show')">
                            <span>&times;</span>
                        </button>
                        </div>

       <div class="modal-body" style="max-height: 90vh; overflow-y: auto;">  
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                  
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Nro</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Monto anterior</th>                                   
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Motivo</th> 
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Fecha/Hora</th>                       
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Estado anterior</th> 
                                             
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ id_mod_v2 }}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{monto_v2+" "+simbolo_v2 }}</td>                                  
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{motivo_v2}}</td>               
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{fecha_mas_reciente}}</td>                           
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{estado_v2}}</td>   
                                 
                                </tr>
                            </tbody> 
                        </table> 
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                  
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Nro arqueo</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Cant moneda</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Monto moneda</th>                                   
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Cant billete</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Monto billete</th> 
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Total de arqueo</th>                       
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Usuario</th> 
                                             
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="a in array_arqueo" :key="a.id">
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.id }}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.cantidad_moneda }}</td>                                  
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{a.total_moneda+" "+a.simbolo }}</td>               
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{a.cantidad_billete}}</td>                           
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{a.total_billete +" "+a.simbolo}}</td>   
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{a.total_arqueo +" "+a.simbolo}}</td>  
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{a.name}}</td>  
                                </tr>
                            </tbody> 
                        </table> 
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>                                  
                                    <th class="col-md-4" style="font-size: 11px; text-align: center">Tipo corte</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Cantidad</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Valor</th>                                   
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Suma de valor</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="a in array_array_arqueo" :key="a.id">
                                    <td class="col-md-4" style="font-size: 11px; text-align: center">{{a.tipo_corte}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ a.cantidad }}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{ a.unidad_entera+" "+a.unidad }}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{ (a.valor * a.cantidad).toFixed(2) +" "+a.unidad }}</td>
                                </tr>
                            </tbody>
                          
                        </table> 
                     
                 
                    </div>                  
                    
                    <div class="modal-footer">
                        <button  type="button" class="btn btn-secondary" @click="cerrarModal('show')">Cerrar</button>
                                               
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
            isSubmitting: false, // Controla el estado del botón de envío
            offset:3,
            tituloModal: '',
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:'',
            tipoAccion:1,
         
            arrayIndex:[],
            id_sucursal:'',

            startDate: '',
            endDate: '',  
            id_cierre_modal:'',
            diferencia_modal:'',
            estado_modal:'',
            textArea_modal:'',

            arrayMoneda:[],
            input:{},
           
           cantidadMonedas:0,
           cantidadBilletes:0, 
           totalMonedas:"0.00",
            SimboloM:'S/N',
            SimboloB:'S/N',            
            totalBilletas:"0.00",
            totalMonto:"0.00",

            fecha_creacion:'',
            array_array_arqueo:[],
            array_arqueo:[],
            id_mod_v2:'',
            monto_v2:'',
            estado_v2:'',
            motivo_v2:'',
            fecha_mas_reciente:'',
            simbolo_v2:'',

            showModal: false,
            showModal_2: false,
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
        },

    },
    methods: {        

        listaArqueo(data) {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/caja_modificar/ver_arqueo?id_arqueo="+data;
            axios.get(url)
                .then(function (response) {
                    var respuesta_1 = (response.data).array_arqueo;
                    var respuesta_2 = (response.data).arqueo;
      
                   me.array_array_arqueo=respuesta_1;
                   me.array_arqueo=respuesta_2;
           
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        crear(){
            let me = this;     
            if (me.textArea_modal==="" || me.textArea_modal===null) {
                Swal.fire("debe llenar el motivo","Haga click en Ok","error",); 
            } else {

                  if (me.diferencia_modal===me.totalMonto) {                  
                     // Si ya está enviando, no permitas otra solicitud
            if (me.isSubmitting) return;
            me.isSubmitting = true; // Deshabilita el botón    
                 
                axios.post("/caja_modificar/registrar", {         
               
                    total_arqueo_caja:me.totalMonto,
                    cantidadBilletes:me.cantidadBilletes,
                    totalBilletas:me.totalBilletas,
                    cantidadMonedas:me.cantidadMonedas,
                    totalMonedas:me.totalMonedas,
                    moneda_s1:me.moneda_s1,
                    input:me.input,
                 


                id_cierre_modal:me.id_cierre_modal,
                diferencia_modal:me.diferencia_modal,
                estado_modal:me.estado_modal,
                textArea_modal:me.textArea_modal, 
                arrayMoneda:me.arrayMoneda,

                    }).then(function (response) {                                            
                        let a=response.data;
                     
                        me.cerrarModal("registrar");                  
                           me.listarIndex();  
                            if (a===null || a==="" ) {
                                Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);                             
                            } else {
                                Swal.fire(""+a,"Haga click en Ok","error",); 
                            } 
                    })                
                  .catch(function (error) {   
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });

                  } else {
                    Swal.fire("La diferencia no es la misma","Haga click en Ok","error",); 
                  }            
            }           
        },

        listarIndex(page) {
            let me = this;  
            
            var url ="/caja_modificar/listarInicio?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_sucursal+"&ini="+me.startDate+"&fini="+me.endDate;
          
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;
            
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
            me.totalMonto=Number(me.totalMonto).toFixed(2);
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
                });
        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;
            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

        verificador_moneda_sistemas(){
            let me=this;
            var url = "/apertura_cierre/verificador_moneda_sistemas";
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
                    "error",
                );
                  } else {
                    me.bloqueador=1;
                    me.arrayMoneda=respuesta_lista;
                    me.moneda_s1=respuesta_moneda;                 
                  }                                  
                })
                .catch(function (error) {
                    error401(error);
                });
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
                    me.tituloModal = "Registro de "+data.nombre_caja;
                                        
                    me.id_cierre_modal=data.id;
                    me.diferencia_modal=data.diferencia_caja;
                    me.estado_modal=data.estado_caja;
                    me.textArea_modal="";

                    me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                me.showModal = true;
                        me.input={};
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.showModal = true;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "show":{
                    me.tituloModal="Ver "+data.nombre_caja+" codigo "+data.codigo ;                
                    me.fecha_creacion=data.fecha_mas_reciente;
                    me.id_mod_v2=data.id_mod_v2;
                    me.monto_v2=data.monto_v2;
                    me.estado_v2=data.estado_v2;
                    me.motivo_v2=data.motivo_v2;
                    me.fecha_mas_reciente=data.fecha_mas_reciente;
                    me.simbolo_v2=data.moneda;
                    me.listaArqueo(data.id_arqueo_mod);
                    me.showModal_2 = true;
                    me.classModal.openModal("show");
                    break;
                }
            
            }
        },

   
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.isSubmitting=false;
                me.id_cierre_modal="";
                    me.diferencia_modal="";
                    me.estado_modal="";
                    me.textArea_modal="";
     me.showModal = false;
                    me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0;                
                        me.input={};                
            }
            if (accion == "show") {
                    me.classModal.closeModal(accion);
                    me.fecha_creacion="";
                    me.id_mod_v2="";
                    me.monto_v2="";
                    me.estado_v2="";
                    me.motivo_v2="";
                    me.fecha_mas_reciente="";
                    me.array_array_arqueo="";
                    me.array_arqueo="";
                    me.simbolo_v2="";
                         me.showModal_2 = false;
            }
        },

        fecha_inicial() {
    // Obtener la fecha actual
    const today = new Date();    
    // Obtener la fecha actual menos 5 días
    const startDate = new Date();
    startDate.setDate(today.getDate() - 20);
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
        this.fecha_inicial();
        this.classModal.addModal("registrar");
        this.classModal.addModal("show");
    
    },
};
</script>
<style scoped>
.error {
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