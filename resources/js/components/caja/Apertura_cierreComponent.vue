<template>
    
    <main class="main">
        <div  v-if="bloqueador>0">
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
                    <i class="fa fa-align-justify"></i> Aperturar caja:             
                    <button type="button" class="btn btn-secondary" @click="cajaAnteriror();verificador_moneda_sistemas();"
                        :disabled="sucursalSeleccionada === 0 ">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada === 0 " class="error"
                        >&nbsp; &nbsp;Debe Seleccionar una sucursal.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-1" style="text-align: right">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada"  @change="listarIndex(0)">
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
                                    placeholder="BUSCAR POR USUARIO, CODIGO O ESTADO DE CAJA."
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)" 
                                    :hidden="sucursalSeleccionada === 0"
                                    :disabled="sucursalSeleccionada === 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"  
                                    :hidden="sucursalSeleccionada === 0"
                                    :disabled="sucursalSeleccionada === 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
            <div class="form-group row"  :hidden="sucursalSeleccionada == 0" :disabled="sucursalSeleccionada == 0">
                <div class="col-md-1">
                     <label for=""></label>
                </div>
                <div class="col-md-5">                    
                        <label for="Apectura / Cierre:"></label>
                                        
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
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>   
                        <th class="col-md-1">Codigo</th>                                                          
                        <th class="col-md-2">Turno</th>                     
                        <th class="col-md-1">Total Caja</th>
                        <th class="col-md-1">Total Arqueo</th>
                        <th class="col-md-1">Diferencia</th> 
                        <th class="col-md-2">Fecha/Hora</th>                         
                        <th class="col-md-1">Usuario</th>
                        <th class="col-md-1">Estado</th>
                        <th class="col-md-1">Apertura</th>                             
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">                    
                        <td class="col-md-1">                           
                      
                                <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu">
    <a @click.prevent="abrirModal('ver', i);"  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i> Ver Apertura De Caja</a>
    <a @click.prevent="abrirModal('actualizar', i);" v-if="i.id_apertura_cierre == 0"   class="dropdown-item" href="#"><i style="color: black;" class="fa fa-pencil" aria-hidden="true"></i> Editar Apertura De Caja</a>
    <a v-else class="dropdown-item" href="#" style="color: whitesmoke;"><i style="color: whitesmoke;" class="fa fa-pencil" aria-hidden="true"></i> Editar Apertura De Caja</a>
    <a @click.prevent="abrirModal('ver', i);" v-if="i.id_apertura_cierre == 0" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-lock" aria-hidden="true"></i> Cerrar Apertura De Caja</a>
    <a v-else class="dropdown-item" href="#" style="color: whitesmoke;"><i style="color: whitesmoke;" class="fa fa-lock" aria-hidden="true"></i> Cerrar Apertura De Caja</a>
    <a v-if="i.id_apertura_cierre == 0" style="color: whitesmoke;" class="dropdown-item" href="#"><i style="color: whitesmoke;" class="fa fa-eye" aria-hidden="true"></i> Ver Cierre De Caja</a>    
    <a v-else @click.prevent="abrirModal('ver', i);"  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i> Ver Cierre De Caja</a>    
    <a v-if="i.id_apertura_cierre == 0" style="color: whitesmoke;" class="dropdown-item" href="#"><i style="color: whitesmoke;" class="fa fa-pencil" aria-hidden="true"></i> Editar Cierre De Caja</a>   
    <a v-else @click.prevent="abrirModal('ver', i);" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-pencil" aria-hidden="true"></i> Editar Cierre De Caja</a>  
</div>      
  
                        </td>
                        <td class="col-md-1" style="text-align: left;">{{i.id}}</td>                                               
                        <td class="col-md-2">
                            <span v-if="i.turno_caja===1">Turno uno</span>
                            <span v-if="i.turno_caja===2">Turno dos</span>
                            <span v-if="i.turno_caja===3">Turno completo</span>
                        </td>
                    
                        <td class="col-md-1" style="text-align: right;">{{i.total_caja}}</td>
                        <td class="col-md-1" style="text-align: right;">{{i.total_arqueo_caja}}</td>
                        <td class="col-md-1" style="text-align: right;">{{i.diferencia_caja}}</td>
                        <td class="col-md-2">{{i.created_at}}</td> 
                        <td class="col-md-1">{{i.name}}</td>
                        <td class="col-md-1">{{i.estado_caja}}</td>
                        <td class="col-md-1">
                            <span v-if="i.id_apertura_cierre===0" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-light">Cerrado</span>
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

            
                    </div>
                    <div class="modal-body">                      
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2">Tipo caja</th>
                                    <th class="col-md-2">Monto</th>
                                    <th class="col-md-2">Estado anterior</th>
                                    <th class="col-md-2">Estado</th>   
                                    <th class="col-md-4">Turno</th>                                
                                 </tr>
                            </thead>  
                            <tbody>  
                                <tr>                         
                                    <td class="col-md-2">{{turno_caja}}</td>
                                    <td class="col-md-2">{{total_caja}}</td>
                                    <td class="col-md-2">Anterior</td>
                                    <td class="col-md-2">{{estado_caja}}</td>                                    
                                    <td class="col-md-4">
                                        <select v-model="selectTurno" class="form-control">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option value="1">TURNO UNO</option> 
                                            <option value="2">TURNO DOS</option>
                                            <option value="3">TURNO COMPLETO</option>
                                        </select>
                                    </td>                                    
                                </tr>    
                            </tbody>          
                        </table>
                    </div> 
                    <div class="modal-body" >                      
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
                    </div> 
                             
                    <div class="modal-footer">
                        <button  type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarArqueo()" >Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">Actualizar</button>
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
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Codigo caja</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Codigo arqueo</th>                                    
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Turno</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Tipo</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Usuario</th>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Estado</th>                                  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                    
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{fecha_modal}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{id_modal}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{id_arqueo_modal}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{turno_modal}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{tipo_modal}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{usuario_modal}}</td>
                                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{estado_modal}}</td>
                                </tr>
                            </tbody> 
                        </table> 
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Total ventas</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Total entradas</th>
                                 
                                    
                                   
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Total salidas</th>
                                   
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Total caja</th>                                    
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Total arqueo</th>
                                    <th class="col-md-1" style="font-size: 11px; text-align: center">Total diferencia</th>                                   
                                 </tr>
                            </thead> 
                            <tbody>
                                <tr>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">0.00</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">0.00</td>
                                   
                            
                                 
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">0.00</td>
                                
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{total_caja_modal}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{total_arqueo_modal}}</td>
                                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{total_diferencia_modal}}</td>
                                </tr>
                            </tbody>
                        </table>  
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Cantidad monedas</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Monto en monedas</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Cantidad Billetes</th>
                                    <th class="col-md-3" style="font-size: 11px; text-align: center">Monto en Billetes</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{cantidad_moneda_modal}}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{monto_moneda_modal}}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{cantidad_billete_modal}}</td>
                                    <td class="col-md-3" style="font-size: 11px; text-align: center">{{monto_billete_modal}}</td>
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
        </div>      
     
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
            valor_entero:0,
          
            bloqueador:0,
            arrayMoneda:[],
            input:{},
            arrayInput:[],
            tituloModal: "",
            sucursalSeleccionada:0,
         
         
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
     
            totalMonedas:"0.00",
            SimboloM:'S/N',
            SimboloB:'S/N',            
            totalBilletas:"0.00",
            totalMonto:"0.00",
            cantidadMonedas:0,
            cantidadBilletes:0, 
            id_sucursal:0,
            selectTurno:0,

            moneda_s1:'',

            turno_caja:'',
            tipo_caja_c_a:'',
            total_caja:'',
            estado_caja:'',

            arrayIndex:[],

            //modal---
            id_modal:'',
            id_arqueo_modal:'',
            turno_modal:'',
            tipo_modal:'',
            usuario_modal:'',
            fecha_modal:'',
            estado_modal:'',

            total_venta_modal:'',
            total_entrada_modal:'',
            
            
          
            total_salida_modal:'',       
        
            total_caja_modal:'',
            total_arqueo_modal:'',
            total_diferencia_modal:'',

            cantidad_moneda_modal:'',
            cantidad_billete_modal:'',
            monto_moneda_modal:'',
            monto_billete_modal:'',

            arrayMonedaModal:[],

            //limitado                    
            startDate: '',
            endDate: '',
        
        };
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

    methods: {
               
        modalMoneda(id) {
            let me = this;           
            var url ="/apertura_cierre/monedaModal?id_arqueo="+id;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayMonedaModal=respuesta;
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarIndex(page) {
            let me = this;  
            let entrada=0;      
            var url ="/apertura_cierre/index?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_sucursal+"&a_e="+parseInt(entrada)+"&ini="+me.startDate+"&fini="+me.endDate;
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

        cajaAnteriror(){
            let me=this;
            var url = "/apertura_cierre/cajaAnteriror?id_sucursal="+me.id_sucursal ;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;          
                    if (respuesta===0 ) {           
                            me.turno_caja="Inicio";
                            me.tipo_caja_c_a="Inicio";
                            me.total_caja=Number(0).toFixed(2);
                            me.estado_caja="Inicio";
                            me.abrirModal('registrar'); 
                    } else {
                        if (respuesta.tipo_caja_c_a===0) {
                            Swal.fire("Ya se hizo una apertura",
                                        "Haga click en Ok",
                                        "warning");
                        } else {
                                if (respuesta.turno_caja===1) {
                                me.turno_caja= "TURNO UNO";
                                }
                                if (respuesta.turno_caja===2) {
                                me.turno_caja= "TURNO DOS";
                                } 
                                if (respuesta.turno_caja===3) {
                                me.turno_caja= "TURNO COMPLETO ";
                                } 
                                  
                                if(respuesta.tipo_caja_c_a===0){
                                me.tipo_caja_c_a="Apertura";
                                }    

                                if(respuesta.tipo_caja_c_a===9){
                                me.tipo_caja_c_a="Cierre";
                                }                                  
                            
                            me.total_caja=respuesta.total_caja;
                            me.estado_caja=respuesta.estado_caja;
                            me.abrirModal('registrar');  
                        }
                    }
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
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
        
        registrarArqueo(){
            let me = this;
      
            if (me.selectTurno === "0") {
                Swal.fire(
                    "Debe seleccionar su tuno",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
                //------------------algoritmo-------------------------
                let a=me.totalMonto;
                let b=me.total_caja;
                let estado="";
                //c ---- diferencia
                  //  b=40.60;
                let cero=Number(0).toFixed(2);
            
                let c=a-b;   
                         
                if (c===0) {
                    if (a===cero && b===cero) {
                        estado="Acción nula";
                    } else {
                        if (a>0 && b>0) {
                            estado="OK";  
                        } else {
                            estado="Error de entrada"; 
                        }
                    }
                } else {
                    if (c>cero) {
                        if (a>cero && b>cero) {
                            estado="Sobrante";
                        } else {
                            if (a>cero && b===cero) {
                                estado="Saldo inicial";
                            } else {
                                if (a>cero && b<cero) {
                                    estado="Caja negativa";
                                } else {
                                    if (a===cero && b>cero) {
                                        estado="Perdida";
                                    } else {
                                        estado="Error";
                                    }
                                }
                            }
                        }
                    } else {
                        if (c<cero) {
                            if (a>cero && b>cero) {
                                estado="Faltante";
                            } else {
                                if (a===cero && b>cero) {
                                    estado="Perdida";
                                } else {
                                    estado==="Error";
                                }
                            }
                        } else {
                            estado="Error"; 
                        }
                    }
                }
               
                axios.post("/apertura_cierre/store", {
                    	id_sucursal:me.id_sucursal,
                        selectTurno:me.selectTurno,
                        tipo_caja_c_a:0,
                        total_caja:me.total_caja,
                        total_arqueo_caja:me.totalMonto,
                        cantidadMonedas:me.cantidadMonedas,
                        totalMonedas:me.totalMonedas,
                        cantidadBilletes:me.cantidadBilletes,
                        totalBilletas:me.totalBilletas,
                        input:me.input,
                        arrayMoneda:me.arrayMoneda,
                        diferencia:c,
                        estado:estado,
                        moneda_s1:me.moneda_s1
                    })
                    .then(function (response) {
                        me.listarIndex();
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
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
            }        
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
         switch (accion) {
            
                case "registrar": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
                  
                        me.tituloModal = "Registro de apertura de caja";
                        me.selectTurno="0";
                        me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.password="";
                        me.input={};
                  
                    
            
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
                    me.isSubmitting=false;
            
                    me.classModal.openModal("registrar");

                    break;
                }

                case "ver": {
               
                    if (data.tipo_caja_c_a===0) {
                        me.tituloModal = "Apertura de caja vista";  
                    } else {
                        if (data.tipo_caja_c_a===9) {
                            me.tituloModal = "Cierre de caja vista";
                        } else {
                            me.tituloModal = "Error";
                        }                       
                    }
                    me.id_modal="000"+data.id;
                    me.id_arqueo_modal="000"+data.id_arqueo;
                    if (data.turno_caja===1) {
                        me.turno_modal="Uno";
                    } else {
                        if (data.turno_caja===2) {
                         me.turno_modal="Dos"; 
                        } else {
                            me.turno_modal="Completo";   
                        }
                    }
                    if (data.tipo_caja_c_a===0) {
                        me.tipo_modal="Apertura";
                    } else {
                        me.tipo_modal="Cierre";
                    }        
                    me.usuario_modal=data.name;
                    me.fecha_modal=data.created_at;
                    me.estado_modal=data.estado_caja;

                    me.total_venta_modal=data.total_venta_caja;
                    me.total_entrada_modal=data.total_ingreso_caja;
                   
                    
              
                    me.total_salida_modal=data.total_salida_caja;               
       
                    me.total_caja_modal=data.total_caja;
                    me.total_arqueo_modal=data.total_arqueo_caja;
                    me.total_diferencia_modal=data.diferencia_caja;
                    
                    me.cantidad_moneda_modal=data.cantidad_moneda;
                    me.cantidad_billete_modal=data.cantidad_billete;
                    me.monto_moneda_modal=data.total_moneda;
                    me.monto_billete_modal=data.total_billete
                    me.modalMoneda(data.id_arqueo);
                    me.classModal.openModal("ver");
                    break;
                }
            
            }
        },

       
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.isSubmitting=false;
                me.selectTurno="0";
                        me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.password="";
                        me.input={};
                        me.classModal.closeModal(accion);
            }
            if(accion=== "ver"){
            me.id_modal="";
            me.id_arqueo_modal="";
            me.turno_modal="";
            me.tipo_modal="";
            me.usuario_modal="";
            me.fecha_modal="";
            me.estado_modal="";
            me.total_venta_modal="";
            me.total_entrada_modal="";
            
            
         
            me.total_salida_modal="";   
    
            me.total_caja_modal="";
            me.total_arqueo_modal="";
            me.total_diferencia_modal="";
            me.cantidad_moneda_modal="";
            me.cantidad_billete_modal="";
            me.monto_moneda_modal="";
            me.monto_billete_modal="";
            me.arrayMonedaModal=[];
                me.classModal.closeModal(accion);
            }
        },

       fecha_inicial() {
    // Obtener la fecha actual
    const today = new Date();    
    // Obtener la fecha actual menos 5 días
    const startDate = new Date();
    startDate.setDate(today.getDate() - 7);
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
        this.verificador_moneda_sistemas();
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
