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
                    <i class="fa fa-align-justify"></i> Auto Gestion Stock               
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar');">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                   
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: right">
                    <label for="">Tipo:</label>
                </div>
                <div class="col-md-4">
                      <select class="form-control" v-model="tipoSelect" @change="cambioOpcion(tipoSelect)">
                             <option value="0" disabled selected>Seleccionar...</option>
                             <option value="1">TODAS LAS SUCURSALES</option>
                             <option value="2">SUCURSAL ESPECIFICA</option>
                      </select>
                </div>                
            </div>
            <div class="form-group row" :hidden="tipoSelect!='2'">
                <div class="col-md-2" style="text-align: right">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="(sucursal, index) in arraySucursal" :key="index" :value="sucursal.id_sucursal" :hidden="sucursal.id_almacen!=null"
                                        v-text="sucursal.codigoS +' -> ' +  sucursal.codigo+
                                            ' ' +  sucursal.razon_social
                                        "
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                               
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
            </div>
             

            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opc.</th>
                        <th>Nro</th>
                        <th>Linea</th>
                        <th>Producto</th>
                        <th>Cic.Stock</th>
                        <th>C.P.Mensual</th>
                        <th>P.Entrega</th>
                        <th>C.P.Venta</th>
                        <th>S.Maximo</th>
                        <th>S.Medio</th>
                        <th>S.Actual</th>
                        <th>S.Pedido</th>
                        <th>I.Rotación</th>
                        <th>I.Cobertura</th>
                        <th>Rentabilidad</th>
                     
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayIndex" :key="index"
                      :style="i.color === 0 || i.color === 1 || i.color === 2 ? {
  backgroundColor: i.color === 0 ? 'red' :
                   i.color === 1 ? '#FFE300' :
                   '#FF7300',
  color: 'white',
  fontWeight: 'bold'
} : {}" >
                        <td>
                            <button type="button" class="btn btn-sm" style="background: transparent; border: none; color: white;" @click="accionModal(i)">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </button>
                        </td>
                        <td>{{ index }}</td>
                        <td>{{ i.linea }}</td>
                        <td>{{ i.producto }}</td>
                        <td>{{ i.ciclo }}</td>
                        <td>{{ i.consumo_mensual }}</td>
                        <td>{{ i.plazo }}</td>
                        <td>{{ i.consumo_dia }}</td>
                        <td>{{ i.stmax }}</td>
                        <td>{{ i.stmedio }}</td>
                        <td>{{ i.stock_total }}</td>
                        <td>{{ i.stpedido }}</td>
                        <td>{{ i.indicerot }}</td>
                        <td>{{ i.indicecober }}</td>
                        <td>{{ i.rentabilidad }}</td>
                                               
                    </tr>
                </tbody>
            </table>    

            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
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
 <div class="form-group row">
                                
                        <div class="col-md-2">
                     <label for="">Distribuidor: <span v-show="selectDistriuidor=='0'" style="color: red;">(*)</span></label>
                </div>
                  <div class="col-sm-8">
                                     <select v-model="selectDistriuidor" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="(i, index) in arrayDistriuidor" :key="index" :value="i.id" v-text="'Alias: '+i.alias+' Lineas relacionadas: '+i.nom_linea_array"></option>
                                    </select>
                    </div>                    
                </div>
                     </div>
                   
                    <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                        <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Collapsible Group Item #1
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
         <table class="table table-bordered table-striped table-sm table-responsive" >                    
                                <thead>
                                    <tr style="background-color: skyblue;">
                                        <th class="col-md-1" style="color: white;">Opcion</th>
                                        <th class="col-md-5" style="color: white;">Producto</th>
                                        <th style="color: white;">Cant. dispenser</th>
                                        <th style="color: white;">Precio lista</th>
                                        <th style="color: white;">Importe</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <tr v-for="(i, index) in arrayTop" :key="index">
                                        <td class="col-md-1">                        
                                        <button type="button" class="btn btn-danger" @click="quitarEleArray(i.id,index)">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                        </td>
                                        <td class="col-md-5">
                                            <span>{{ "Linea: "+i.linea+" Prod: "+i.producto+" Envase: "+i.envase }}</span>                                            
                                        </td>
                                        <td class="col-md-2">
                                            <input style="text-align: right;" class="form-control form-control-sm" type="number" v-model.number="arrayTop[index].dispedido" @keyup="sumar_top(index)"> 
                                        </td>
                                        <td class="col-md-2" style="text-align: right;">                                            
                                            <span>{{ i.precio_lista+" " }}</span><span>{{ simbolo }}</span>                                        
                                        </td>
                                        <td class="col-md-2" style="text-align: right;">
                                             <span>{{ i.subtotal+" " }}</span><span>{{simbolo}}</span>
                                        </td>
                                    </tr>
                                    <tr style="background-color: skyblue;">
                                        <td colspan="4" style="text-align: center;">                          
                                            <strong style="text-align: center;color: white;" >SUB TOTAL</strong>                                                       
                                        </td>
                                        <td class="col-md-2" style="text-align: right;">
                                            <span>{{subTotal_modal_superior+" "}}</span><span>{{simbolo}}</span>
                                         </td>
                                    </tr>
                                </tbody>           
                            </table>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Collapsible Group Item #2
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
         <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr style="background-color: skyblue; color: white;">
                            <th class="col-md-7">Producto</th>                           
                            <th class="col-md-2">Precio de lista</th>
                            <th class="col-md-3">Cantidad</th>                            
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr>
                            <td  class="col-md-7">
                                    <VueMultiselect
                        v-model="select_distribuidor_x_producto"
                        
                        :options="arraySelect_distribuidor_x_producto"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="id" 
                        :custom-label="nameWithLang_2"                     
                        track-by="id"
                        class="w-200"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado">
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect> 
                            </td>
                            <td  class="col-md-2">
                                <span v-if="select_distribuidor_x_producto" style="text-align: right;">
                                    {{select_distribuidor_x_producto.preciolista+" "+simbolo}}
                                </span>
                                <span style="text-align: right;" v-else>
                                    0.00
                                </span>
                            </td>
                            <td  class="col-md-3">
                                <div v-if="select_distribuidor_x_producto" class="d-flex align-items-center gap-2">
                                    <input style="text-align: right;" class="form-control form-control-sm" type="number" v-model="input_bot">
                                    <button type="button" class="btn btn-primary"  @click="añadir_elemento_array_bot()"><i class="fa fa-plus" aria-hidden="true"></i></button>   
                                </div>
                                <div v-else>
                                    <span style="text-align: right;">0</span>
                                </div>  
                            </td>
                        </tr>                      
                    </tbody>    
                </table>
                 <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr style="background-color: skyblue; color: white;">
                            <th>Opciones</th>
                            <th class="col-md-2">Linea</th>
                            <th class="col-md-1">Cod.Prod</th>
                            <th class="col-md-3">Producto</th>
                            <th class="col-md-2">Pre.Lista</th>
                            <th class="col-md-1">Cantidad</th>
                            <th class="col-md-3">Sub Total</th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr v-for="f in arrayInferior_falso" :key="f.id">
                            <td>
                                <button type="button" class="btn btn-danger" @click="quitarArrayFalsoBot(f.contador)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td class="col-md-2">
                                {{f.linea_nombre}}
                            </td>
                            <td class="col-md-1">
                                {{f.codigoProducto}}
                            </td>
                            <td class="col-md-3">
                                {{f.nom_prod}}
                            </td>
                            <td class="col-md-2">
                                {{f.preciolista+" "+simbolo}}
                            </td>    
                            <td class="col-md-1">
                                {{f.cantidadFalsa}}
                            </td>    
                            <td class="col-md-3">
                                {{f.subTotal_falso+" "+simbolo}}
                            </td>                       
                        </tr>
                        <tr style="background-color: skyblue; color: white;">
                            <td colspan="6" style="align-items: center;">
                                <strong style="text-align: center;">SUMATORIA</strong>
                            </td>
                            <th class="col-md-3">
                                <span>{{sumatoriaBot+" "+simbolo}}</span>
                            </th>
                        </tr>                      
                    </tbody>    
                </table>      
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Collapsible Group Item #3
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
            <div class="form-group row">                               
                        <div class="col-md-1">
                            <label for="">Ciclo:</label>
                        </div>
                        <div class="col-sm-3">
                                     <select v-model="selectCiclo" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option value="1">Un mes</option>
                                        <option value="3">Tres meses</option>
                                        <option value="6">Seis meses</option>
                                        <option value="12">Doce meses</option>
                                    </select>
                        </div>
                        <div class="col-md-1">
                            <label for="">Tipo:</label>
                        </div>
                        <div class="col-sm-3">
                                     <select v-model="selectTipoTiendaOAlmacen" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option value="1">Tienda</option>
                                        <option value="2">Almacen</option>
                                    </select>
                        </div> 
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary" @click="listarIndiceVenta()" :disabled="selectCiclo=='0' || selectTipoTiendaOAlmacen=='0'">Consultar</button>
                        </div>  
            </div>                
            <br>
            <div v-if="dataLoderd==1" >                
                <label for="" style="font-family: 'Times New Roman', Times, serif; font-size: medium;">{{cargaDatos}}</label>
            </div>
            <div v-else>
<table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr style="background-color: skyblue; color: white;">
                            <th class="col-md-1">Codigo</th>
                            <th class="col-md-4">Producto</th>
                            <th >C.Venta</th>                            
                            <th class="col-md-2">Estado 1</th>
                            <th class="col-md-2">Estado 2</th>
                            <th class="col-md-2">Estado 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(i, index) in arrayModalConsultarVenta" :key="index">
                            <td class="col-md-1">{{i.codigo}}</td>
                            <td class="col-md-4">{{i.nombre}}</td>
                            <td style="text-align: right;">{{i.cantidad}}</td>
                            <td class="col-md-2">{{i.estado}}</td>
                            <td class="col-md-2">{{i.estadoTra}}</td>
                            <td class="col-md-2">{{i.estadoFinal}}</td>
                        </tr>
                    </tbody>
              </table>    
            </div>
                        
        </div>
    </div>
  </div>
    <div class="card">
    <div class="card-header" id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Collapsible Group Item #4
        </button>
      </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
      <div class="card-body">
        <div class="form-group row">                               
            
                       
            <div class="col-sm-2">
                <label for="">Tipo:</label>    
                <select v-model="selectTipoTiendaOAlmacen2" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option value="1">Tienda</option>
                                        <option value="2">Almacen</option>
                </select>
            </div> 
           
                
         
            <div class="col-sm-3">
                <label for="">Intervalo inferior:</label>
                <input type="text" class="form-control" v-model="intervaloIni" @input="intervaloIni = $event.target.value.replace(/\D/g, '')" placeholder="Numero"/>
             </div>
            <div class="col-sm-3">
                <label for="">Intervalo superior:</label>
                <input type="text" class="form-control" v-model="intervaloFin" @input="intervaloFin = $event.target.value.replace(/\D/g, '')" placeholder="Numero"/>
              
            </div>
            <div class="col-sm-2">
                <br> 
                <button type="button" class="btn btn-secondary" v-if="selectTipoTiendaOAlmacen2=='0' || intervaloFin<0 || intervaloIni<0"  >Consultar</button>               
                <button type="button" class="btn btn-primary" v-else @click="listarIndiceVentaX2()">Consultar</button>
            </div>  
        </div>
        <div class="form-group row">
                <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr style="background-color: skyblue; color: white;">
                            <th class="col-md-6">Producto</th>
                            <th class="col-md-2">Fecha V.</th>                           
                            <th class="col-md-2">Precio de lista</th>
                            <th class="col-md-2">Cantidad</th>                            
                        </tr>                        
                    </thead>
                </table>
                <div class="col-md-2">
                     <label for="">Producto:</label>
                </div>
                <div class="col-sm-8">
                                     <select v-model="selectProducto_2" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="(i, index) in arrayProducto_2" :key="index" :value="i.id" v-text="i.nom_linea+' '+i.nombre+' - '+i.nom_farmace+' X '+i.nom_dispen+' Envase: '+i.envase"></option>
                                    </select>
                </div>                    
        </div>                
      </div>
    </div>
  </div>
</div>
                       
                           
                        
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">
                            Actualizar
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
import VueMultiselect from 'vue-multiselect';
//Vue.use(VeeValidate);
export default {
    components: { VueMultiselect},
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
            showModal: false,
            //offset:3,

            arrayIndex:[],

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            

            tipoSelect:'0',

            //-----modal
            arrayModalEnvio:[],
            nomEmpresa:'',
            numDocumen:'',
            plazo:'',
            fechaPago:'',
            fechaPedido:'',
            Entrega:'',
            Distribuidor:'',
            FacturarA:'',
            formaPago:'',
            total:0,

            simbolo:'',
            importe_total:0,

            arrayDistriuidor:[],
            selectDistriuidor:'0',
            id_linea:0,
            arrayTop:[],
            bandela:0,
            inputTop:[],
            subTotal_modal_superior:0,

            nomDist:'',
            idsLineas:'',

            select_distribuidor_x_producto:null,
            arraySelect_distribuidor_x_producto:[],
            input_bot:1,
            arrayInferior_falso:[],
            sumatoriaBot:0,
            count:0,

            arrayModalConsultarVenta:[],
            selectCiclo:'0',
            selectTipoTiendaOAlmacen:'0',
            cargaDatos:'',
            dataLoderd:0,

            selectTipoTiendaOAlmacen2:'0',
            intervaloIni:0,
            intervaloFin:0,
            selectProducto_2:'0',
            arrayProducto_2:[],

        };
    },

    watch: {
        selectDistriuidor: function (newValue) {
        
            if (this.arrayDistriuidor.length>0) {               
                let distribuidorSeleccionado = this.arrayDistriuidor.find(
                    (element) => element.id === newValue,
                );                
                if (distribuidorSeleccionado) {
                    this.idsLineas=distribuidorSeleccionado.id_linea_array;
                    this.nomDist=distribuidorSeleccionado.alias;
                    this.modalMedio();
                    this.arrayInferior_falso=[];
                    this.sumatoriaBot=0;
                    this.count=0;
                    this.select_distribuidor_x_producto=null;   
                    this.input_bot=1;
                }              
            }
        },   
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

handleInput(e) {
      // toma solo dígitos
      const onlyDigits = e.target.value.replace(/\D+/g, '');
      // evita valores vacíos => dejar cadena vacía o 0 según prefieras
      this.intervaloIni = onlyDigits === '' ? '' : String(parseInt(onlyDigits, 10));
    },

        cambioOpcion(data){
            let me = this;
            me.arrayIndex=[];
            if (data=="1") {
                 me.sucursalSeleccionada=0;
                 me.listarIndex();
            } else {
              me.sucursalSeleccionada=0;
            }          
        },

        accionModal(data){
            let me=this;
            me.arrayDistriuidor=[];
            me.selectDistriuidor="0";  
            me.id_linea=data.id_linea;
            me.arrayTop=[];
            me.simbolo="";
            me.importe_total=0;
            me.subTotal_modal_superior=0;
            me.nomDist="";
            me.idsLineas="";
            me.select_distribuidor_x_producto=null;
            me.arraySelect_distribuidor_x_producto=[];
            me.input_bot=1;
             
            me.arrayInferior_falso=[];
            me.sumatoriaBot=0;
            me.count=0;
            me.arrayModalConsultarVenta=[];
            me.selectCiclo="1";
            me.selectTipoTiendaOAlmacen="1";
            me.listarDistribuidores(me.id_linea,data.linea);  
            me.selectTipoTiendaOAlmacen2="1"; 

            me.intervaloIni=0;
            me.intervaloFin=0;
            me.selectProducto_2="0";
            me.arrayProducto_2=[];
            if (me.bandela==1) {
                Swal.fire("Error nivel 1 encontrado: ","No existe el distribuidor relacionado con la linea. "+data.linea,"error",);  
            } else{
                me.modalSuperior();
                
               if (me.bandela==2) {
               Swal.fire("Error nivel 2 encontrado: ","No existe datos para la tabla superior con la linea. "+data.linea,"error",);       
               }else{                
                me.abrirModal('registrar',data); 
                           
               }
            }
           // me.modalSuperior();
           // me.listarModalAlerta_inferior(linea_es);
           // me.abrirModal('registrar',data);        
        },

       nameWithLang_2 ({linea_nombre,nom_prod,dis_nom,cantidad,nom_for_farmaceutica}) {            
            return `${linea_nombre}: ${nom_prod} - ${dis_nom} X ${cantidad} ${nom_for_farmaceutica}`
          },  

    sumar_top(id){
        let me=this;
        const element=0;
        console.log(id);
        if( me.inputTop[id]!="" || me.inputTop[id]!=null || me.inputTop[id]>=0){
             parseFloat(me.arrayTop[id].subtotal=(me.arrayTop[id].dispedido)*me.arrayTop[id].precio_lista).toFixed(2);
            let suma = 0;
    for (let i = 0; i < me.arrayTop.length; i++) {
      suma += Number(me.arrayTop[i].subtotal || 0);
    }
         me.subTotal_modal_superior=parseFloat(suma).toFixed(2);
        }
      },

       quitarArrayFalsoBot(id){
            let me=this;         
            me.arrayInferior_falso = me.arrayInferior_falso.filter(item => item.contador !== id);
             let suma = 0;
            for (let ii = 0; ii < me.arrayInferior_falso.length; ii++) {
                suma += Number(me.arrayInferior_falso[ii].subTotal_falso || 0);
            }
         me.sumatoriaBot=parseFloat(suma).toFixed(2);
        },

          añadir_elemento_array_bot() {
    let me = this;    
    if (me.count<0 || me.input_bot<=0 || me.input_bot==""|| me.input_bot==null ) {
        me.cerrarModal('alerta');
        alert("error de entrada valor negativo o nulos");
    }
    me.count=me.count+1;
    let subTotal_falso=(me.select_distribuidor_x_producto).preciolista * me.input_bot;
    me.arrayInferior_falso.push({ 
        contador:me.count,
        id_prod: (me.select_distribuidor_x_producto).id_prod,
        id_linea: (me.select_distribuidor_x_producto).id_linea,
        linea_nombre: (me.select_distribuidor_x_producto).linea_nombre,
        codigoProducto: (me.select_distribuidor_x_producto).codigoProducto,
        nom_prod: (me.select_distribuidor_x_producto).nom_prod,
        distribuidor_nom: (me.select_distribuidor_x_producto).distribuidor_nom,
        id_dispenser: (me.select_distribuidor_x_producto).id_dispenser,
        cantidad: (me.select_distribuidor_x_producto).cantidad,
        tiempo_pedido: (me.select_distribuidor_x_producto).tiempo_pedido,
        dis_nom: (me.select_distribuidor_x_producto).dis_nom,
        nom_for_farmaceutica: (me.select_distribuidor_x_producto).nom_for_farmaceutica,
        preciolista: (me.select_distribuidor_x_producto).preciolista,
        precioventa: (me.select_distribuidor_x_producto).precioventa,
        
        cantidadFalsa:me.input_bot,
        subTotal_falso: parseFloat(subTotal_falso).toFixed(2),
        moneda:me.simbolo
    });
    me.select_distribuidor_x_producto=null;
   
        me.input_bot=1;
         let suma = 0;
            for (let ii = 0; ii < me.arrayInferior_falso.length; ii++) {
                suma += Number(me.arrayInferior_falso[ii].subTotal_falso || 0);
            }
 me.sumatoriaBot=parseFloat(suma).toFixed(2);
},

      quitarEleArray(id,i){
            let me=this;         
            me.arrayTop = me.arrayTop.filter(item => item.id !== id);
             let suma = 0;
            for (let ii = 0; ii < me.arrayTop.length; ii++) {
                suma += Number(me.arrayTop[ii].subtotal || 0);
            }
         me.subTotal_modal_superior=parseFloat(suma).toFixed(2);
        },
        
        modalSuperior() {
        let me = this;             
           var url = "/auto-gestion-stock/listarAlertaModalSuperior?id_sucursal="+me.sucursalSeleccionada+"&tipo="+me.tipoSelect+"&id_linea="+me.id_linea;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;  
                    me.arrayTop =respuesta.arrayMostrar;  
                    console.log(respuesta);                 
                   
                    me.importe_total=respuesta.importe_total;
                    
                    if (me.arrayTop.length>=1) {
                        let count_2=0;
                        if ((respuesta.simbolos).simbolo==null||(respuesta.simbolos).simbolo=="") {
                         me.simbolo="Bs";
                    }else{
                    me.simbolo=(respuesta.simbolos).simbolo;
                    }
                    me.subTotal_modal_superior=respuesta.importe_total;
                    me.arrayTop.forEach(e => {
                        if (count_2==0) {
                            me.subTotal_modal_superior=e.subtotal;
                        } else {
                            me.subTotal_modal_superior=me.subTotal_modal_superior+e.subtotal;
                        }
                           count_2=1;
                    });
                    return me.bandela=0;
                    } else{
                        return me.bandela=2;
                    }                         
                })
                .catch(function (error) {
                    error401(error);
                });
        },
                
        listarDistribuidores(id_linea,linea) {
        let me = this;    
           var url = "/auto-gestion-stock/listarDistribuidor?id="+id_linea;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;  
                    me.arrayDistriuidor=respuesta;   
                    if (me.arrayDistriuidor.length>=1) {
                        if (me.arrayDistriuidor.length==1) {
                            me.selectDistriuidor=me.arrayDistriuidor[0].id;
                           
                            return me.bandela=0;
                        }               
                    } else {
                        return me.bandela=1;                        
                    }                                
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarIndiceVenta() {
        let me = this;    
        me.arrayModalConsultarVenta=[];
            console.log("--------------**-");
       me.cargaDatos="Consulta enviada...";                    
                    me.dataLoderd=1;
           var url = "/auto-gestion-stock/listarIndiceVenta?data="+me.idsLineas+"&id_sucursal="+me.sucursalSeleccionada+"&tipo="+me.tipoSelect+"&tipoAlmTienda="+me.selectTipoTiendaOAlmacen+"&intervalo="+me.selectCiclo;
            axios
                .get(url)
                .then(function (response) {
                   
                    var respuesta = response.data;
                    console.log(respuesta);
                    if (respuesta.length>0) {     
                        let c1=0.1;
                                    let c2=0.25;
                                    let c3=0.5;
                                    let c4=0.59;
                                    let c5=0.75;
                                    let c6=0.9;                    
                        respuesta.forEach(e => {
                            if (e.cantidad_ingreso!=null) {
                               let cantidad_ingreso_tienda= parseInt(e.cantidad_ingreso);
                               let stock_ingreso_tienda=parseInt(e.stock_ingreso);                            
                               let cs=cantidad_ingreso_tienda-stock_ingreso_tienda;
                               let cantidad_venta=parseInt(e.cantidad_venta);
                               let estado="";
                               let estadoTra="";
                               let estadoFinal="";
                                    if (cs>=0) {
                                        estado="Normal";
                                    }else{
                                        estado="Exceso de productos";
                                    }
                                    console.log(estado);
                                                                       
                                    let U=cantidad_venta+stock_ingreso_tienda;
                                    let Traspaso=cantidad_ingreso_tienda-U;
                                    if (Traspaso==0) {
                                        estadoTra="Sin traspaso";
                                    }else{
                                        if (Traspaso<0) {
                                            Traspaso=Traspaso*(-1);
                                            estadoTra="Con traspaso: "+Traspaso;
                                        } else {
                                            estadoTra="Ingreso con decremento: "+Traspaso;
                                        }
                                    }
                                    console.log(estadoTra);
                                    let trasCantiIngre=cantidad_ingreso_tienda+Traspaso;   
                                                                   
                                    c1=trasCantiIngre*0.1;
                                   if(cantidad_venta<=c1){
                                        estadoFinal="Consumo muy bajo";
                                   }else{
                                    c2=trasCantiIngre*0.25;
                                        if (cantidad_venta>c1 && cantidad_venta<=c2) {
                                            estadoFinal="Consumo bajo";
                                        }else{
                                        c3=trasCantiIngre*0.5;    
                                            if (cantidad_venta>c2 && cantidad_venta<=c3) {
                                                estadoFinal="Consumo moderado";
                                            }else{
                                                c4=trasCantiIngre*0.59;  
                                                if (cantidad_venta>c3 && cantidad_venta<=c4) {
                                                    estadoFinal="Consumo normal";
                                                }else{
                                                    c5=trasCantiIngre*0.75; 
                                                    if (cantidad_venta>c4 && cantidad_venta<=c5) {
                                                        estadoFinal="Consumo alto";
                                                    }else{
                                                        c6=trasCantiIngre*0.90; 
                                                        if (cantidad_venta>c5 && cantidad_venta<=c6) {
                                                            estadoFinal="Consumo muy alto";
                                                        }else{
                                                            if (cantidad_venta>c6) {
                                                                estadoFinal="Consumo óptimo";
                                                            }else{
                                                                estadoFinal="Error..";
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                   }
                                    me.arrayModalConsultarVenta.push({
                            codigo: e.codigo_prod,
                            nombre: e.nom_prod+" "+e.nombreD+" X "+e.cantidadF+" "+e.nombreF+" Envase: "+e.envase,
                            cantidad: e.cantidad_venta,
                            estado:estado,
                            estadoTra:estadoTra,
                            estadoFinal:estadoFinal,
                            });  
                            estadoFinal="";
                            estadoTra="";
                            estado="";
                            }
                                
                        });    
                    }
                    
                 me.dataLoderd=0;
                    console.log(me.arrayModalConsultarVenta);                                               
                })
                .catch(function (error) {
                    error401(error);
                });
        },  
        


        listarIndiceVentaX2() {
        let me = this;       
     
           var url = "/auto-gestion-stock/listarIndicePrecio?data="+me.idsLineas+"&id_sucursal="+me.sucursalSeleccionada+"&tipo="+me.tipoSelect+"&tipoAlmTienda="+me.selectTipoTiendaOAlmacen+"&intervaloIni="+me.intervaloIni+"&intervaloFin="+me.intervaloFin;
            axios.get(url)
                .then(function (response) {
                   
                    var respuesta = response.data;
                    me.arrayProducto_2=respuesta;
                    console.log(respuesta);
                                                                 
                })
                .catch(function (error) {
                    error401(error);
                });
        },


        modalMedio() {
            let me = this;
           var url = "/auto-gestion-stock/listar_Producto_x_distribuidor?data="+me.idsLineas;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;  
                    me.arraySelect_distribuidor_x_producto=respuesta;   
                    console.log("-----------------------------------");
                    console.log(me.arraySelect_distribuidor_x_producto);             
                })
                .catch(function (error) {
                    error401(error);
                });
        },

       listarIndex() {
            let me = this;
           var url = "/auto-gestion-stock/listarGestorStock?id_sucursal="+me.sucursalSeleccionada+"&tipo="+me.tipoSelect;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;  
                    me.arrayIndex=respuesta.arrayMostrar;                  
                    console.log(respuesta);                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        sucursalFiltro() {
            let me = this;
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;
                    console.log(me.arraySucursal);                 
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
                    me.tituloModal = "Crear pedido";
                    me.showModal = true;
                    me.cargaDatos="";                    
                    me.dataLoderd=0;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.classModal.openModal("registrar");
                    break;
                }
            
            }
        },

   
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = "";
                me.arrayModalEnvio=[];
                me.nomEmpresa="";
                me.numDocumen="";
                me.plazo="";
                me.fechaPago="";
                me.fechaPedido="";
                me.Entrega="";
                me.Distribuidor="";
                me.FacturarA="";
                me.formaPago="";
                me.total=0;
                me.simbolo="";             
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
