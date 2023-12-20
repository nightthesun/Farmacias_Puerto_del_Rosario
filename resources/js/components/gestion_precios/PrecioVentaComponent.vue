<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <!-- <div class="card-header">
                    <i class="fa fa-align-justify"></i> Almacen
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div> -->
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align:center">
                            <label for="">Tiendas y/o Almacenes Disponibles:</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" @change="listarProductosTiendaAlmacen(1)"
                                    v-model="tiendaalmacenselected">
                                    <option value="0" disabled>Seleccionar...</option>
                                    <option v-for="almacenTienda in arrayAlmacenesTiendas" :key="almacenTienda.id" :value="{'id':almacenTienda.id,'tipo':almacenTienda.tipo}"
                                        v-text="(almacenTienda.codsuc === null ? '' : almacenTienda.codsuc + ' -> ') + almacenTienda.codigo + ' ' + almacenTienda.nombre_almacen">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" v-if="tiendaalmacenselected!=0">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar"
                                    v-model="buscar" @keyup.enter="bucarProducto(1)">
                                <button type="submit" class="btn btn-primary" @click="bucarProducto(1)"><i
                                        class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo</th>
                                <th>Linea o Marca</th>
                                <th>Entrada</th>
                                <th>Cantidad</th>
                                <th>Stock</th>
                                <th>Precio Lista</th>
                                <th>Costo Compra</th>
                                <th>Precio Venta</th>
                                <th>% Utilidad Bruta</th>
                                <th>Tipo Entrada</th>
                                <th>Fecha y Hora</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="producto in arrayProductosAlterado" :key="producto.id" :style="[ producto.listo_venta == 1 ? '':'background-color: #FAD537' ]">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm"
                                        @click="abrirModal('editarPrecioUtilidadProducto', producto)" :disabled="producto.stock_ingreso == 0">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <!-- <button v-if="almacen.activo == 1" type="button" class="btn btn-danger btn-sm"
                                        @click="eliminarAlmacen(almacen.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm"
                                        @click="activarAlamcen(almacen.id)">
                                        <i class="icon-check"></i>
                                    </button> -->
                                </td>
                                <!-- <td v-text="(almacen.codsuc === null ? '': almacen.codsuc+' - ') + almacen.codigo"></td> -->
                                <td>{{ producto.codproducto }}</td>
                                <td>{{ producto.lineaProductoNombre }}</td>
                                <td>{{ producto.nomproducto }} - {{ producto.envaseEmbalajeProductoNombre }} X {{ producto.envaseregistrado.toLowerCase()=='primario'?producto.cantidadprimario:'' }} {{ producto.envaseregistrado.toLowerCase()=='secundario'?producto.cantidadsecundario:'' }} {{ producto.envaseregistrado.toLowerCase()=='terceario'?producto.cantidadterciario:'' }} {{ producto.formaUnidadMedidaProducto }} - LOTE: {{ producto.lote }} {{  producto.perecedero == 0 ? '': ("- FV: "+producto.fecha_vencimiento) }} - FI: {{ producto.fecingreso }}   </td>
                                <td>{{ producto.cantidad }}</td> <!-- Cantidad Envase o Enbalaje -->
                                <td>{{ producto.stock_ingreso }}</td><!-- Cantidad stock-->
                                <td><!-- Precio lista -->
                                    <div v-if="producto.listo_venta == 1">
                                        <span class="">
                                          {{ producto.precio_lista_gespreventa === null ? "0.00": producto.precio_lista_gespreventa }}
                                        </span>
                                    </div>
                                    <div v-else>
                                        <span class="">
                                            {{ producto.precio_lista_gespreventa === null ? "0.00": producto.precio_lista_gespreventa }}
                                        </span>
                                    </div>
                                </td>
                                <td><!-- Costo Compra -->
                                    <div v-if="producto.listo_venta == 1">
                                        <span>{{ producto.costo_compra_gespreventa === null ? "0.00":producto.costo_compra_gespreventa }}</span>
                                    </div>
                                    <div v-else>
                                        <span>{{ producto.costo_compra_gespreventa === null ? "0.00":producto.costo_compra_gespreventa }}</span>
                                    </div>
                                </td>
                                <td><!-- Precio de Venta -->
                                    <div v-if="producto.listo_venta == 1">
                                        <span  >
                                            {{ producto.precio_venta_gespreventa === null ? "0.00": producto.precio_venta_gespreventa }} 
                                        </span>
                                    </div>
                                    <div v-else>
                                        <span class="">
                                            {{ producto.precio_venta_gespreventa === null ? "0.00": producto.precio_venta_gespreventa }} 
                                        </span>
                                    </div>
                                </td>
                                <td><!-- % Utilidad Bruta -->
                                    <div v-if="producto.listo_venta == 1">
                                        <span  >{{ producto.utilidad_neto_gespreventa === null ? "0.00":producto.utilidad_neto_gespreventa }}</span>
                                    </div>
                                    <div v-else>
                                        <span class="">{{ producto.utilidad_neto_gespreventa === null ? "0.00":producto.utilidad_neto_gespreventa }}  </span>
                                    </div>
                                </td>
                                <td>{{ producto.tipoentrada }}</td><!-- Tipo Entrada -->
                                <td> <!-- Fecha de Utilidad -->
                                    <div v-if="producto.listo_venta == 1">
                                        <span  >{{ producto.fecha_utilidad}}</span>
                                    </div>
                                    <div v-else>
                                        <span class="">DD/MM/AAAA</span>
                                    </div>
                                </td> 
                                <td>{{ producto.usuarioRegistroIngresoProducto }}</td> <!-- Usuario -->
                            </tr> 
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="calculadoraModal" tabindex="-1" aria-labelledby="calculadoraModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="calculadoraModalLabel">{{ tituloModal }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" class="form-horizontal">
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="alert alert-primary" role="alert">
                                           <b>Ingreso:</b> {{ caracteristicasProductoModificar }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-primary" role="alert">
                                            <b>Cant. Ingreso: </b>{{ cantidadIngresoAlmacen }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label>Precio de Lista</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" v-model="p_lista"
                                                aria-describedby="basic-addon3" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="basic-url">Cantidad Envase o Embalaje</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" v-model="c_disp"
                                                aria-describedby="basic-addon3" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Costo de Compra</label>
                                        <div class="input-group mb-3">
                                            <input type="number" min="0" id="p_compra" class="form-control" v-model.number="p_compra"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Precio de Venta</label>
                                        <div class="input-group mb-3">
                                            <input type="text" id="p_venta" class="form-control" v-model="p_venta"
                                                aria-describedby="basic-addon3" readonly>
                                            <button class="btn btn-warning" type="button" @click="utilidad">
                                                <i class="fa fa-calculator" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label for="basic-url">Margen 20%</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" v-model="margen_20"
                                                aria-describedby="basic-addon3" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Margen 30%</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" v-model="margen_30"
                                                aria-describedby="basic-addon3" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Utilidad Bruta</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" v-model="utilidad_bruta"
                                                aria-describedby="basic-addon3" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- cambiar la utilidad bruta variable y en la base de datos ojo muy importante -->
                                    <div class="col-md-3">
                                        <label>Utilidad Neta (%)</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" v-model="utilidad_neta" 
                                                aria-describedby="basic-addon3" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4" id="area-botones-guarcancelar">
                                        <button type="button" class="btn btn-success" :disabled="!sicompleto" @click="actualizarRegistrarPrecioVenta">Guardar</button>
                                        <button type="button" class="btn btn-danger" style="margin-left: 10px;" data-dismiss="modal" @click="cerrarModal('calculadoraModal')">Cancelar</button>
                                    </div>                                    
                                </div>
                                
                                <hr>
                                <div>
                                    <h5>Gestor Costo Compra</h5><br>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label for="basic-url">Precio de Compra</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="pcc" v-model="pcc"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Desc. 1 %</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="dpc1" v-model="dpc1"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Desc. 2 %</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="dpc2" v-model="dpc2"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Desc. 3 %</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="dpc3" v-model="dpc3"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Descuento Bs.</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="dbsc" v-model="dbsc"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="basic-url">Costo Compra C/Desc.</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="pcdc" v-model="pcdc"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="basic-url">Precio Unitario</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="puc" v-model="puc"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                            <button class="btn btn-warning" type="button" @click="calculadoraCostoCompra">
                                                <i class="fa fa-calculator" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div>
                                    <h5>Gestor Precio Venta</h5><br>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label for="basic-url">P/U de Compra</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="pucc" v-model="pucc"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Liq. 20 %</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="l20pc" v-model="l20pc"
                                                aria-describedby="basic-addon3">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Liq. 30%</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="l30pc" v-model="l30pc"
                                                aria-describedby="basic-addon3">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="basic-url">Utilidad Bruta%</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="upc" v-model="upc"
                                                aria-describedby="basic-addon3">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="basic-url">Precio de Venta</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="pvc" v-model="pvc"
                                                aria-describedby="basic-addon3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)">
                                            <button class="btn btn-warning" type="button" @click="calculadoraPrecioVenta">
                                                <i class="fa fa-calculator" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <!-- <div class="col-md-2">
                                        <label for="basic-url">Utilidad Bruta</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="ubc" v-model="ubc"
                                                aria-describedby="basic-addon3">
                                        </div>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" :disabled="!sicompleto" @click="actualizarRegistrarPrecioVenta">Guardar Cambios</button> -->
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>


<script>
import Swal from 'sweetalert2';
import { error401 } from '../../errores';
// import router from "@/router";

export default {
    data() {
        return {
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            offset: 3,
            tituloModal:'',
            tipo: 0,
            tipoAccion: 1,
            id_table_ingreso_tienda_almacen:0,
            buscar: '',
            matriz: 0,
            arrayRubros: [],
            idrubro: 0,
            arrayAlmacenesTiendas: [],
            arrayProductos: [],
            arrayProductosAlterado:[],
            arrayProductosAlteradoCopy:[],
            arrayLineas:[],
            arrayEmvasesEmbalajes:[],
            arrayFormaUnidadMedidas:[],
            arrayTipoEntradaProductos:[],
            arrayUsuarios:[],
            tiendaalmacenselected: 0,
            existe_registro_gespreventa:0,
            id_gespreventa:0,
            tienda_gespreventa:0,
            almacen_gespreventa:0,
            p_lista: 0,
            c_disp: 0,
            p_compra: 0,
            margen_20: 0,
            margen_30: 0,
            utilidad_bruta:0,
            p_venta: 0,
            utilidad_neta: 0,
            pcc: 0,
            dpc1: 0,
            dpc2: 0,
            dpc3: 0,
            dbsc: 0,
            pcdc: 0,
            puc: 0,
            l20pc: 0,
            l30pc: 0,
            pucc: 0,
            ubc: 0,
            upc: 0,
            pvc: 0,
            idProdProducto:0,
            envaseregistradoAlmIngresoProducto:'',
            caracteristicasProductoModificar:'',
            cantidadIngresoAlmacen:0,
        }

    },
    computed: {

        sicompleto(){
            let me = this;
            if(me.p_venta > 0 && me.margen_20>0 && me.margen_30>0 && me.utilidad_neta>=0)
            {
                return true;
            }
            else{
                return false;
            }
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
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++
            }
            return pagesArray;
        },


    },
    methods: {
        
        pruebaListarProductosIngreso(){
            let me = this;
            var url='/almacen/ingreso-producto/retornarProductosIngreoAlmacen?idalmacen='+me.tiendaalmacenselected.id;
            axios.get(url).then(function(response){
                console.log("@@@@@@@@@@");
                console.log(response.data.productosAlmacen.data);
                console.log(me.tiendaalmacenselected.id);
            })
            .catch(function(error){
                error401(error);
                console.log(error);
            });
        },
        
        listarLineas(){
                let me = this;
                var url='/linea/selectlinea';
                axios.get(url).then(function(response){
                    me.arrayLineas=response.data.lineas;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
                
        },
        
        listarEmvasesEmbalajes(){
            let me = this;
            let url = '/dispenser/selectdispenser';
            axios.get(url)
            .then(function(response){
                me.arrayEmvasesEmbalajes=response.data.dispensers;
            })
            .catch(function(error){
                error401(error);
                console.log(error);
            });            
        },

        listarTipoEntradaProducto(){
              let me = this;
                var url = '/tipoentrada';
              axios.get(url)
              .then(function(response){
                me.arrayTipoEntradaProductos = response.data.tipoentrada_data;
              }).catch(function(error){
                    error401(error);
              });  
            },

        listarUsuarios(){
            let me = this;
            var url='/usuario/listar-usuarios';
                axios.get(url).then(function(response){
                    me.arrayUsuarios = response.data;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
        },

        listarFormaUnidadDeMedida(){
            let me = this;
            let url = '/formafarm/selectformafarm';
            axios.get(url)
            .then(function(response){
                me.arrayFormaUnidadMedidas=response.data.formafarm;
            })
            .catch(function(error){
                error401(error);
                console.log(error);
            });
        },
        
        listarProductosTiendaAlmacen(page) {
            let me = this;
            //me.pruebaListarProductosIngreso();
            me.arrayProductosAlterado = [];
            if (me.tiendaalmacenselected.id != 0) {
                if (me.tiendaalmacenselected.tipo.toLowerCase() == 'tienda') {
                    axios.get('/tienda/ingreso-producto?idtienda='+me.tiendaalmacenselected.id)
                    .then(function(response){
                        var respuesta = response.data;
                        me.pagination = respuesta.pagination;
                        me.arrayProductos = respuesta.productosTienda.data;
                        let nombreEnvaseEmbalajeDelProducto = '';
                        let nombreFormaUnidadMedidaProducto = '';
                        me.arrayProductos.forEach(producto => {
                            if(producto.activo_tda_ingreso_producto == 1)
                            {
                                producto.lineaProductoNombre = me.arrayLineas.find((linea) => linea.id == producto.idlinea).nombre;
                                producto.perecedero = me.arrayRubros.find((rubro) => rubro.id == producto.id_rubro_producto).areamedica;
                                producto.usuarioRegistroIngresoProducto = me.arrayUsuarios.find((usuario) => usuario.id == producto.id_usuario_registra).name;
                                producto.tipoentrada = me.arrayTipoEntradaProductos.find((tipoentrada) => tipoentrada.id == producto.id_tipoentrada).nombre;
                                switch (producto.envaseregistrado) {
                                    case 'primario':
                                        nombreEnvaseEmbalajeDelProducto = me.arrayEmvasesEmbalajes.find((envase) => envase.id == producto.iddispenserprimario).nombre;
                                        if (producto.idformafarmaceuticaprimario == 0) {
                                            nombreFormaUnidadMedidaProducto = '';
                                        } else {
                                            nombreFormaUnidadMedidaProducto = me.arrayFormaUnidadMedidas.find((unidadmedida) => unidadmedida.id == producto.idformafarmaceuticaprimario).nombre;
                                        }
                                        break;
                                    case 'secundario':
                                        nombreEnvaseEmbalajeDelProducto = me.arrayEmvasesEmbalajes.find((envase) => envase.id == producto.iddispensersecundario).nombre;
                                        if (producto.idformafarmaceuticasecundario == 0) {
                                            nombreFormaUnidadMedidaProducto = '';
                                        } else {
                                            nombreFormaUnidadMedidaProducto = me.arrayFormaUnidadMedidas.find((unidadmedida) => unidadmedida.id == producto.idformafarmaceuticasecundario).nombre;
                                        }
                                        break;
                                    case 'terciario':
                                        nombreEnvaseEmbalajeDelProducto = me.arrayEmvasesEmbalajes.find((envase) => envase.id == producto.iddispenserterciario).nombre;
                                        if (producto.idformafarmaceuticaterciario == 0) {
                                            nombreFormaUnidadMedidaProducto = '';
                                        } else {
                                            nombreFormaUnidadMedidaProducto = me.arrayFormaUnidadMedidas.find((unidadmedida) => unidadmedida.id == producto.idformafarmaceuticaterciario).nombre;
                                        }
                                        break;
                                    default:
                                        break;
                                }

                                producto.envaseEmbalajeProductoNombre = nombreEnvaseEmbalajeDelProducto;
                                producto.formaUnidadMedidaProducto = nombreFormaUnidadMedidaProducto;
                                producto.codproducto = producto.codigo_producto;
                                producto.nomproducto = producto.nombre_producto;
                                producto.fecingreso = producto.fecha_ingreso;
                                me.arrayProductosAlterado.push(producto);
                            }
                            console.log(me.arrayProductosAlterado);
                        });
                        me.arrayProductosAlteradoCopy = me.arrayProductosAlterado; // Esto se hace para facilitar la busqueda de productos en la funcion de bucarProducto()
                    })
                    .catch(function(error){
                        error401(error);
                        console.log(error);
                    }); 

                } else {
                    let url = '/almacen/ingreso-producto?page=' + page + '&idalmacen=' + me.tiendaalmacenselected.id;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.pagination = respuesta.pagination;
                        me.arrayProductos = respuesta.productosAlmacen.data;
                        let nombreEnvaseEmbalajeDelProducto = '';
                        let nombreFormaUnidadMedidaProducto = '';
                        me.arrayProductos.forEach(element => {
                            if(element.activo == 1){

                            element.lineaProductoNombre = me.arrayLineas.find((element2) => element2.id == element.idlinea).nombre;
                            element.perecedero = me.arrayRubros.find((rubro) => rubro.id == element.idrubroproducto).areamedica;
                            element.usuarioRegistroIngresoProducto = me.arrayUsuarios.find((usuario) => usuario.id == element.id_usuario_registra).name;
                            element.tipoentrada = me.arrayTipoEntradaProductos.find((tipoentrada) => tipoentrada.id == element.id_tipoentrada).nombre;
                            switch (element.envaseregistrado) {
                                case 'primario':
                                    nombreEnvaseEmbalajeDelProducto = me.arrayEmvasesEmbalajes.find((element3) => element3.id == element.iddispenserprimario).nombre;                                   
                                    if (element.idformafarmaceuticaprimario == 0) {
                                        nombreFormaUnidadMedidaProducto = '';
                                    } else {
                                        nombreFormaUnidadMedidaProducto = me.arrayFormaUnidadMedidas.find((element4) => element4.id == element.idformafarmaceuticaprimario).nombre;
                                    }
                                    break;
                                case 'secundario':
                                    nombreEnvaseEmbalajeDelProducto = me.arrayEmvasesEmbalajes.find((element3) => element3.id == element.iddispensersecundario).nombre;                                    
                                    if (element.idformafarmaceuticasecundario == 0) {
                                        nombreFormaUnidadMedidaProducto = '';
                                    } else {
                                        nombreFormaUnidadMedidaProducto = me.arrayFormaUnidadMedidas.find((element4) => element4.id == element.idformafarmaceuticasecundario).nombre;
                                    }
                                    break;
                                case 'terciario':
                                    nombreEnvaseEmbalajeDelProducto = me.arrayEmvasesEmbalajes.find((element3) => element3.id == element.iddispenserterciario).nombre;
                                    if (element.idformafarmaceuticaterciario == 0) {
                                        nombreFormaUnidadMedidaProducto = '';
                                    } else {
                                        nombreFormaUnidadMedidaProducto = me.arrayFormaUnidadMedidas.find((element4) => element4.id == element.idformafarmaceuticaterciario).nombre;
                                    }
                                    break;
                                default:
                                    break;
                            }

                            element.envaseEmbalajeProductoNombre = nombreEnvaseEmbalajeDelProducto;
                            element.formaUnidadMedidaProducto = nombreFormaUnidadMedidaProducto;
                            me.arrayProductosAlterado.push(element);
                          }
                        });
                        me.arrayProductosAlteradoCopy = me.arrayProductosAlterado; // Esto se hace para facilitar la busqueda de productos en la funcion de bucarProducto()
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                }
            }
        },

        bucarProducto(){
            let me = this;
            let arrayProductosAlterado2 = [];
            if (me.buscar.length == 0) {
                me.arrayProductosAlterado = me.arrayProductosAlteradoCopy;
            }else{
                let evaluacion = false;
                me.arrayProductosAlterado.forEach(element => {
                        evaluacion = (element.codproducto.toLowerCase().includes(me.buscar.toLowerCase()) || 
                            element.lineaProductoNombre.toLowerCase().includes(me.buscar.toLowerCase()) || 
                            element.envaseEmbalajeProductoNombre.toLowerCase().includes(me.buscar.toLowerCase()) || 
                            element.formaUnidadMedidaProducto.toLowerCase().includes(me.buscar.toLowerCase()) || 
                            element.tipoentrada.toLowerCase().includes(me.buscar.toLowerCase()) ||
                            element.usuarioRegistroIngresoProducto.toLowerCase().includes(me.buscar.toLowerCase()) ||
                            element.lote.toLowerCase().includes(me.buscar.toLowerCase()) ||
                            element.nomproducto.toLowerCase().includes(me.buscar.toLowerCase()));

                        if(typeof element.fecha_vencimiento !== 'undefined' && element.fecha_vencimiento !== null){
                          evaluacion = evaluacion || element.fecha_vencimiento.toLowerCase().includes(me.buscar.toLocaleLowerCase())
                        }

                        if(typeof element.fecingreso !== 'undefined' && element.fecingreso !== null){
                          evaluacion = evaluacion || element.fecingreso.toLowerCase().includes(me.buscar.toLocaleLowerCase())
                        }

                        if(typeof element.fecha_utilidad !== 'undefined' && element.fecha_utilidad !== null){
                          evaluacion = evaluacion || element.fecha_utilidad.toLowerCase().includes(me.buscar.toLocaleLowerCase())
                        }

                        if(evaluacion){
                            arrayProductosAlterado2.push(element);
                        }
                });
                me.arrayProductosAlterado = arrayProductosAlterado2;
            }
        },

        selectDepartamentos() {
            let me = this;
            var url = '/depto/selectdepto';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayDepto = respuesta;
            })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        selectCiudades() {
            let me = this;
            var url = '/ciudad/selectciudad';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayCiudad = respuesta;
            })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarSucursales(page) {
            let me = this;
            var url = '/sucursal?page=' + page + '&buscar=' + me.buscar;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arraySucursales = respuesta.sucursales.data;
                    let resp = me.arraySucursales.find(element => element.tipo == 'Casa_Matriz');
                    if (resp != undefined) {
                        if (resp.tipo == 'Casa_Matriz')
                            me.matriz = 1;
                        else
                            me.matriz = 0;
                    }
                    else
                        me.matriz = 0;
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarAlmacenesTiendas(page) {
            let me = this;
            let copiaArrayAlmacenesTiendas = [];
            axios.get('/almacen?page=' + page)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayAlmacenesTiendas = respuesta.almacenes.data;
                    me.arrayAlmacenesTiendas.forEach(element => {
                        if (element.activo == 1) {
                            copiaArrayAlmacenesTiendas.push(element);
                        }
                    });
                    me.arrayAlmacenesTiendas = copiaArrayAlmacenesTiendas;
                })
                .catch(function (error) {
                    error401(error);
                });

            let me2 = this;
            let arrayTiendas = [];
            let copiaArrayTiendas = [];

            axios.get('/tienda?page=' + page)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    arrayTiendas = respuesta.tiendas.data;
                    arrayTiendas.forEach(tienda => {
                        if (tienda.activo_tienda == 1) {
                            tienda.activo = tienda.activo_tienda;
                            tienda.codigo = tienda.codigo_tienda;
                            tienda.codsuc = tienda.codigo_sucursal
                            tienda.correlativo = 1
                            tienda.id = tienda.id_tienda;
                            tienda.idrubro = tienda.id_rubro;
                            tienda.idsucursal = tienda.id_sucursal
                            tienda.nombre_almacen = tienda.razon_social;
                            tienda.razon_social = tienda.razon_social;
                            tienda.telefono = tienda.telefonos;
                            tienda.tipo = "Tienda";
                            copiaArrayTiendas.push(tienda);
                        }
                    });

                    me2.arrayAlmacenesTiendas = me2.arrayAlmacenesTiendas.concat(copiaArrayTiendas);
                    
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarProductosTiendaAlmacen(page);
        },

        actualizarRegistrarPrecioVenta() {
            let me = this;
            axios.post('/gestionprecioventa/actualizar-registrar', {
                'existe_registro_gespreventa':me.existe_registro_gespreventa,
                'id':me.id_gespreventa,
                'id_table_ingreso_tienda_almacen':me.id_table_ingreso_tienda_almacen,
                'tienda':me.tienda_gespreventa,
                'almacen':me.almacen_gespreventa,
                'precio_lista_gespreventa':me.p_lista,
                'precio_venta_gespreventa':me.p_venta,
                'cantidad_envase_gespreventa':me.c_disp,
                'costo_compra_gespreventa':me.p_compra,
                'margen_20p_gespreventa':me.margen_20,
                'margen_30p_gespreventa':me.margen_30,
                'utilidad_bruta_gespreventa':me.utilidad_bruta,
                'utilidad_neto_gespreventa':me.utilidad_neta,
            }).then(function (response) {
                me.cerrarModal('calculadoraModal');
                Swal.fire(
                    'Almacen Registrado exitosamente',
                    'Haga click en Ok',
                    'success'
                );
                me.listarProductosTiendaAlmacen();
                
            }).catch(function (error) {
                error401(error);
                console.log(error);
            });
        },

        eliminarAlmacen(idalmacen) {
            let me = this;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.put('/almacen/desactivar', {
                        'id': idalmacen
                    }).then(function (response) {

                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarSucursales();

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
        activarAlamcen(idalmacen) {
            let me = this;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Activar?',
                text: "Es una Activacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Activar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.put('/almacen/activar', {
                        'id': idalmacen
                    }).then(function (response) {
                        me.listarAlmacenesTiendas();
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
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
        actualizarAlmacen() {
            let me = this;
            axios.put('/almacen/actualizar', {
                'id': me.idalmacen,
                'idsucursal': me.sucursalSeleccionado,
                'nombre_almacen': me.nombrealmacen,
                'telefono': me.telefono,
                'direccion': me.direccion,
                'departamento': me.departamento,
                'ciudad': me.ciudad,
            }).then(function (response) {
                me.listarAlmacenesTiendas();
                Swal.fire(
                    'Actualizado Correctamente!',
                    'El registro a sido actualizado Correctamente',
                    'success'
                )
            }).catch(function (error) {
                error401(error);
            });
            me.cerrarModal('registrar');
        },

        abrirModal(accion, data = []) {
            let me = this;
            switch (accion) {
                case 'registrar':
                    {
                        me.tituloModal = 'Registar Nuevo Almacen'
                        me.tipoAccion = 1;
                        me.tipo = 0;
                        me.nombrealmacen = '';
                        me.nombrealmacen = '',
                        me.telefono = '';
                        me.direccion = '';
                        me.sucursalSeleccionado = 0;
                        me.departamento = 0;
                        me.ciudad = '';
                        me.idrubro = 0;
                        me.classModal.openModal('registrar');
                        break;
                    }

                case 'actualizar':
                    {
                        me.sucursalSeleccionado = data.idsucursal === null ? 0 : data.idsucursal;
                        me.tipoAccion = 2;
                        me.tituloModal = 'Actualizar Datos del Almacen';
                        me.tipo = data.tipo;
                        me.nombrealmacen = data.nombre_almacen;
                        me.telefono = data.telefono;
                        me.direccion = data.direccion;
                        me.ciudad = data.ciudad;
                        me.departamento = data.departamento;
                        me.idalmacen = data.id;
                        me.classModal.openModal('registrar');
                        break;
                    }
                case 'editarPrecioUtilidadProducto':
                    {
                        let me = this;
                        me.tituloModal = 'Modificar Utilidad del Producto';
                        me.caracteristicasProductoModificar = data.nomproducto + '-' + data.envaseEmbalajeProductoNombre +' X '+ (data.envaseregistrado.toLowerCase()=='primario'?data.cantidadprimario:'') + ' ' + (data.envaseregistrado.toLowerCase()=='secundario'?data.cantidadsecundario:'') + ' ' + (data.envaseregistrado.toLowerCase()=='terceario'?data.cantidadterciario:'') + ' ' + data.formaUnidadMedidaProducto;
                        me.idProdProducto=data.id_prod_producto;
                        me.envaseregistradoAlmIngresoProducto=data.envaseregistrado;
                        me.cantidadIngresoAlmacen = data.cantidad;
                        me.id_table_ingreso_tienda_almacen = data.id;
                        axios.get('/gestionprecioventa/verificarProductoConPrecio?id_table_ingreso_tienda_almacen='+me.id_table_ingreso_tienda_almacen+'&tienda_almacen='+me.tiendaalmacenselected.tipo)   
                        .then(function (response) { 
                            console.log("%%%%%%%%%%%")     
                            console.log(response.data)               
                            console.log(data);
                            
                            if (response.data.length == 1) 
                            {
                                me.existe_registro_gespreventa = 1;
                                me.id_gespreventa = response.data[0].id;
                                me.id_table_ingreso_tienda_almacen = response.data[0].id_table_ingreso_tienda_almacen;
                                me.tienda_gespreventa = response.data[0].tienda;
                                me.almacen_gespreventa = response.data[0].almacen;
                                me.p_lista = response.data[0].precio_lista_gespreventa;
                                me.c_disp = response.data[0].cantidad_envase_gespreventa;
                                me.p_compra = response.data[0].costo_compra_gespreventa;
                                me.p_venta = response.data[0].precio_venta_gespreventa;
                                me.margen_20 = response.data[0].margen_20p_gespreventa;
                                me.margen_30 = response.data[0].margen_30p_gespreventa;
                                me.utilidad_bruta = response.data[0].utilidad_bruta_gespreventa;
                                me.utilidad_neta = response.data[0].utilidad_neto_gespreventa;
                            }
                            else{
                                me.existe_registro_gespreventa = 0;
                                if(me.tiendaalmacenselected.tipo.toLowerCase() == 'tienda')
                                {
                                    me.tienda_gespreventa = 1;
                                
                                }else{
                                    me.almacen_gespreventa = 1;
                                }
                                me.p_venta = data.precioventaprimario;
                                me.utilidad_bruta = 0;
                                me.utilidad_neta = 0;
                                me.margen_20 = 0;
                                me.margen_30 = 0;
                                me.dpc1 = 0;
                                me.dpc2 = 0;
                                me.dpc3 = 0;
                                me.dbsc = 0;
                                me.l20pc = 0;
                                me.l30pc = 0;
                                me.pucc = 0;
                                me.ubc = 0;
                                me.upc =  0;
                                me.pvc = 0;
                            }
                            
                        }).catch(function (error) {
                            error401(error);
                            console.log(error);
                        });

                        switch (data.envaseregistrado) 
                                {
                                    case 'primario':
                                        me.p_compra = data.preciolistaprimario/(/[a-z]/.test(data.cantidadprimario.toLowerCase())?1:data.cantidadprimario);
                                        me.c_disp=data.cantidadprimario;
                                        me.p_lista = data.preciolistaprimario;
                                        me.pcc = data.preciolistaprimario;
                                        me.p_venta = data.precioventaprimario;   
                                        break;
                                    case 'secundario':
                                        me.p_compra = data.preciolistasecundario/(/[a-z]/.test(data.cantidadsecundario.toLowerCase())?1:data.cantidadsecundario);
                                        me.c_disp=data.cantidadsecundario;
                                        me.p_lista = data.preciolistasecundario;
                                        me.pcc = data.preciolistasecundario;
                                        me.p_venta = data.precioventasecundario;    
                                        break;

                                    case 'terciario':
                                        me.p_lista = data.preciolistaterciario;
                                        me.p_compra = data.preciolistaterciario/(/[a-z]/.test(data.cantidadterciario.toLowerCase())?1:data.cantidadterciario);
                                        me.c_disp=data.cantidadterciario;
                                        me.pcc = data.preciolistaterciario;
                                        me.p_venta = data.precioventaterciario;
                                        break;
                                        
                                    default:
                                        break;
                                }
                                me.p_compra = me.p_compra.toFixed(2);
                                me.pcdc = me.pcc;
                                me.puc = (me.pcdc/(/[a-z]/.test(me.c_disp.toLowerCase())?1:me.c_disp)).toFixed(2);

                        me.classModal.openModal('calculadoraModal');
                        break;
                    }

            }

        },

        cerrarModal(accion) {
            let me = this;
            me.existe_registro_gespreventa = 0;
            me.id_gespreventa = 0;
            me.tienda_gespreventa = 0;
            me.almacen_gespreventa = 0;
            me.tipoAccion = 1;
            me.margen_20 = 0;
            me.margen_30 = 0;
            me.p_venta = 0;
            me.utilidad_bruta = 0;
            me.utilidad_neta = 0;
            me.dpc1 = 0;
            me.dpc2 = 0;
            me.dpc3 = 0;
            me.dbsc = 0;
            me.l20pc = 0;
            me.l30pc = 0;
            me.pucc = 0;
            me.ubc = 0;
            me.upc =  0;
            me.pvc = 0;
            me.pcc = 0;
            me.pcdc = 0;
            me.classModal.closeModal(accion);        
        },

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select()
            }, 0)
        },

        selectRubros() {
            let me = this;
            var url = '/rubro/selectrubro';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayRubros = respuesta.rubros;
            })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        utilidad() {

            let me = this;
            // var p_compra = parseFloat(me.p_compra);
            me.margen_20 = ((parseFloat(me.p_compra) * 100) / 80).toFixed(2);
            me.margen_30 = ((parseFloat(me.p_compra) * 100) / 70).toFixed(2);
            me.utilidad_bruta = (parseFloat(me.p_venta) - parseFloat(me.p_compra)).toFixed(2);
            me.utilidad_neta = ((parseFloat(me.p_venta) - parseFloat(me.p_compra)) / me.p_venta) * 100;
            me.utilidad_neta = Math.round(me.utilidad_neta);
        },

        calculadoraCostoCompra(){

            let me = this;
            
            me.pcc = parseFloat(me.pcc); // pcc = Precio de Compra
            me.dpc1 = parseFloat(me.dpc1); // dpc1 = Descuento % (primer input)
            me.dpc2 = parseFloat(me.dpc2); // dpc2 = Descuento % (segundo input)
            me.dpc3 = parseFloat(me.dpc3); // dpc3 = Descuento % (tercer input)
            me.dbsc = parseFloat(me.dbsc); // dbsc = Descuento Bs.

            var cd = parseInt((/[a-z]/.test(me.c_disp.toLowerCase())?1:me.c_disp)); // cd = Cantidad Dispenser
            me.pcdc = (me.pcc - (me.pcc * me.dpc1 / 100)); //  pcdc = Costo Compa C/Desc.
            me.pcdc = (me.pcdc - (me.pcdc * me.dpc2 / 100)); 
            me.pcdc = (me.pcdc - (me.pcdc * me.dpc3 / 100));
            me.pcdc = me.pcdc - me.dbsc;
            me.puc = (me.pcdc / cd); // puc = Precio Unitario
    

            me.pcc = me.pcc.toFixed(2);
            me.dpc1 = me.dpc1.toFixed(2);
            me.dpc2 = me.dpc2.toFixed(2);
            me.dpc3 = me.dpc3.toFixed(2);
            me.dbsc = me.dbsc.toFixed(2);
            me.puc = me.puc.toFixed(2);
            me.pcdc = me.pcdc.toFixed(2);
        },

        calculadoraPrecioVenta() {
            let me = this;
            // me.pcc = parseFloat(me.pcc);
            // me.dpc1 = parseFloat(me.dpc1);
            // me.dpc2 = parseFloat(me.dpc2);
            // me.dpc3 = parseFloat(me.dpc3);
            // me.dbsc = parseFloat(me.dbsc);
            // var cd = parseInt((/[a-z]/.test(me.c_disp.toLowerCase())?1:me.c_disp));
            // me.pcdc = (me.pcc - me.dbsc - (me.pcc * me.dpc1 / 100)).toFixed(2);
            // me.pcdc = (me.pcdc - (me.pcdc * me.dpc2 / 100)).toFixed(2);
            // me.pcdc = (me.pcdc - (me.pcdc * me.dpc3 / 100)).toFixed(2);
            // me.puc = (me.pcdc / cd).toFixed(2);
            // me.l20pc = ((me.puc * 100) / 70).toFixed(2);
            // me.l30pc = ((me.puc * 100) / 60).toFixed(2);
            // me.pucc = parseFloat(me.pucc).toFixed(2);
            // me.pvc = parseFloat(me.pvc).toFixed(2);
            // me.ubc = (me.pvc - me.pucc).toFixed(2);
            // me.upc = ((me.ubc * 100) / me.pvc).toFixed(2);
            // me.pcc = me.pcc.toFixed(2);
            // me.dpc1 = me.dpc1.toFixed(2);
            // me.dpc2 = me.dpc2.toFixed(2);
            // me.dpc3 = me.dpc3.toFixed(2);
            // me.dbsc = me.dbsc.toFixed(2);
            

            me.pcdc = parseFloat(me.pcdc);
            me.pvc = parseFloat(me.pvc == 0 ? me.p_venta:me.pvc); // pvc = Precio de Venta
            me.pucc = parseFloat(me.pucc == 0 ? me.puc:me.pucc); // pucc = P/U de Compra
            me.l20pc = ((me.pucc * 100) / 80); // l20pc = Liq. 20 %
            me.l30pc = ((me.pucc * 100) / 70); // l30pc = Liq. 30 %
            me.ubc = (me.pvc - me.pucc); // ubc = Utilidad Bruta
            me.upc = ((me.ubc * 100) / me.pvc); //  upc = Utilidad Bruta%

            // console.log("**********Utilidad********");
            // console.log(        
            // 'pdcd: '+me.pcdc+'\n'+
            // 'puc: '+me.puc+'\n'+
            // 'l20pc: '+me.l20pc+'\n'+
            // 'l30pc: '+me.l30pc+'\n'+
            // 'ubc : '+me.ubc +'\n'+
            // 'upc: '+me.upc+'\n'+
            // 'pucc: '+me.pucc+'\n'+
            // 'pvc: '+me.pvc+'\n'
            // );


            me.pcdc = me.pcdc.toFixed(2);            
            me.l20pc = me.l20pc.toFixed(2);
            me.l30pc = me.l30pc.toFixed(2);
            me.pucc = me.pucc.toFixed(2);
            me.ubc = me.ubc.toFixed(2);
            me.upc = me.upc.toFixed(2); // Math.round(me.upc);
            me.pvc = me.pvc.toFixed(2);
        }

    },

    mounted() {
        this.listarUsuarios();
        this.selectRubros();
        this.listarLineas();
        this.listarEmvasesEmbalajes();
        this.listarFormaUnidadDeMedida();
        this.listarTipoEntradaProducto();
        this.listarAlmacenesTiendas(1);
        this.listarSucursales(1);
        this.selectDepartamentos();
        this.selectCiudades();
        this.classModal = new _pl.Modals();
        this.classModal.addModal('calculadoraModal');
        //console.log('Component mounted.')
    }
}
</script>
<style scoped>

#area-botones-guarcancelar{
    margin-top: 28px;
}

h1 {
    color: red;
}

label {
    font-size: 11px;
}

.modal-xl {
    width: 900px;
}

table > thead > tr > th {
    text-align: center;
    display: table-cell;
    vertical-align: middle;    
}
table > tbody > tr > td > div {
    font-size: 15px;
}
</style>