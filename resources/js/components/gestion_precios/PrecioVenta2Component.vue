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
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align:center">
                            <label for="">Tiendas y/o Almacenes Disponibles:</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control"  @change="listarLista(tiendaalmacenselected);cambiarEstado();"
                                v-model="tiendaalmacenselected">
                                    
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option
                                        v-for="sucursal in arrayAlmacenesTiendas"
                                        :key="sucursal.codigo"
                                        :value="sucursal.codigo"
                                        v-text="sucursal.codigoS + ' -> ' +
                                            sucursal.codigo+ ' ' +
                                            sucursal.razon_social        
                                            
                                        "
                                    ></option>
                                </select>
                            </div>
                        </div>
                     
                        
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control"
                                    placeholder="Texto a buscar" v-model="buscar"
                                    @keyup.enter="listarProductosTiendaAlmacen(1)" 
                                    :hidden="selectLista == 0"
                                    :disabled="selectLista == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarProductosTiendaAlmacen(1)"
                                    :hidden="selectLista == 0"
                                    :disabled="selectLista == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" v-if="tiendaalmacenselected!=0">
                        <div class="col-md-2" style="text-align:center">
                            <label for="">Lista Disponibles:</label>
                        </div>
                        <div class="col-md-5" >
                            <div class="input-group">
                                <select class="form-control" @change="listarProductosTiendaAlmacen(0);listarLista(tiendaalmacenselected);"
                                v-model="selectLista" >
                                    
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option value="x"  >Sin Lista</option>
                                    <option
                                        v-for="l in arrayLista"
                                        :key="l.id_lista"
                                        :value="l.id_lista"
                                        v-text="l.codigo_lista + ' -> ' +l.nombre_lista"
                                    ></option>
                                </select>
                            </div>
                        </div>
                    </div>
                   
                    <!--fin de form-group row-->
                
                        <div   v-if="tiendaalmacenselected==0">
 
</div>
              
                    <div v-else>
                        <div class="alert alert-warning" role="alert" v-if="selectLista==0">
                            <h4 class="alert-heading">Intrucciones!</h4>
  <p>Para poder ver el listado de productos primero debe escoger una tienda o almacén después escoger una lista, para ver listado por default solo debe escoger <strong>Sin Lista</strong>.</p>
  <hr>
  <p class="mb-0">El precio se verá dependiendo de la lista que escogió por la cantidad de productos que existe.</p>
                        </div>
                        <div v-else>
                            <table class="table table-bordered table-striped table-sm table-responsive" >
                   
                   <thead>
                       <tr>
                           <th>Opciones</th>
                           <th class="col-md-1">Codigo</th>
                           <th class="col-md-1">Linea o Marca</th>
                           <th  class="col-md-3">Entrada</th>
                           <th  >Cantidad</th>
                           <th  >Stock</th>
                           <th  class="col-md-1">Precio Lista</th>
                           <th  class="col-md-1">Costo Compra</th>
                           <th  class="col-md-1">Precio Venta</th>
                           <th  class="col-md-1">% Utilidad Bruta</th>
                           <th  class="col-md-1">Tipo Entrada</th>
                           <th  class="col-md-1">Fecha y Hora</th>
                           <th>Usuario</th>
                       </tr>
                   </thead>
                  
                   <tr v-for="producto in arrayProductosAlterado" :key="producto.id" :style="[ producto.listo_venta == 1 ? '':'background-color: #FAD537' ]">
                       <td>
                        <div  v-if="puedeEditar==1">
                            <button type="button" class="btn btn-warning btn-sm"
                                   @click="abrirModal('editarPrecioUtilidadProducto', producto)" :disabled="producto.stock_ingreso == 0">
                                   <i class="icon-pencil"></i>
                           </button> &nbsp; 
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-light btn-sm"
                                   :disabled="producto.stock_ingreso == 0">
                                   <i class="icon-pencil"></i>
                           </button> 
                        </div>    
                           
                       </td>
                       <td  class="col-md-1" v-text="producto.codigo_producto"></td>
                       <td  class="col-md-1" v-text="producto.nombre_linea"></td>
                       <td   class="col-md-3" v-text="producto.leyenda+' FI:'+producto.fecha_ingreso+' Lote:'+producto.lote+' FV:'+producto.fecha_vencimiento"></td>
                       <td  class="col-md-1" v-text="producto.cantidad_ingreso"></td>
                       <td  class="col-md-1" v-text="producto.stock_ingreso"></td>
                       <td  class="col-md-1"><!-- Precio lista -->
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
                       <td  class="col-md-1"><!-- Costo Compra -->
                               <div v-if="producto.listo_venta == 1">
                                   <span>{{ producto.costo_compra_gespreventa === null ? "0.00":producto.costo_compra_gespreventa }}</span>
                               </div>
                               <div v-else>
                                   <span>{{ producto.costo_compra_gespreventa === null ? "0.00":producto.costo_compra_gespreventa }}</span>
                               </div>
                       </td>
                       <td  class="col-md-1"><!-- Precio de Venta -->
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
                       <td  class="col-md-1"><!-- % Utilidad Bruta -->
                               <div v-if="producto.listo_venta == 1">
                                   <span  >{{ producto.utilidad_neto_gespreventa === null ? "0.00":producto.utilidad_neto_gespreventa }}</span>
                               </div>
                               <div v-else>
                                   <span class="">{{ producto.utilidad_neto_gespreventa === null ? "0.00":producto.utilidad_neto_gespreventa }}  </span>
                               </div>
                       </td>
                       <td  class="col-md-1" v-text="producto.tipoentrada"></td><!-- Tipo Entrada -->
                       <td  class="col-md-1"> <!-- Fecha de Utilidad -->
                               <div v-if="producto.listo_venta == 1">
                                   <span  >{{ producto.fecha}}</span>
                               </div>
                               <div v-else>
                                   <span class="">DD/MM/AAAA</span>
                               </div>
                       </td> 
                       <td  class="col-md-1">
                           <div v-if="producto.listo_venta == 1">
                                   <span  >{{ producto.user_name}}</span>
                               </div>
                               <div v-else>
                                   <span class="">S/M</span>
                               </div>
                       </td> 
                       
                   </tr>     
               </table>   
               <nav>
                   <ul class="pagination">
                       <li
                           class="page-item"
                           v-if="pagination.current_page > 1"
                       >
                           <a
                               class="page-link"
                               href="#"
                               @click.prevent="
                                   cambiarPagina(
                                       pagination.current_page - 1,
                                   )
                               "
                               >Ant</a
                           >
                       </li>
                       <li
                           class="page-item"
                           v-for="page in pagesNumber"
                           :key="page"
                           :class="[page == isActived ? 'active' : '']"
                       >
                           <a
                               class="page-link"
                               href="#"
                               @click.prevent="cambiarPagina(page)"
                               v-text="page"
                           ></a>
                       </li>
                       <li
                           class="page-item"
                           v-if="
                               pagination.current_page <
                               pagination.last_page
                           "
                       >
                           <a
                               class="page-link"
                               href="#"
                               @click.prevent="
                                   cambiarPagina(
                                       pagination.current_page + 1,
                                   )
                               "
                               >Sig</a
                           >
                       </li>
                   </ul>
               </nav>
                        </div>
                       
                    </div>
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
                                        <div v-if="activarEvento==2" class="alert alert-danger" role="alert">
  No puede ingresar valores nulos, negativo o iguales a cero
</div>
                                        <button v-if="activarEvento==1" type="button" class="btn btn-success" :disabled="!sicompleto" @click="actualizarRegistrarPrecioVenta">Guardar</button>
                                        <button v-else type="button" class="btn btn-light">Guardar</button>
                                        
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
    //---permisos_R_W_S
    props: ['codventana'],
        //-------------------
    data() {
        return {
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            activarEvento:0,
            offset: 3,           
            tipo: 0,
            tipoAccion: 1, 
            buscar:'',
            tiendaalmacenselected:0,  
            arrayAlmacenesTiendas:[],
            arrayProductosAlterado:[],    
            id_gespreventa:'',
            //modal
            listo_venta:0,
            codigo:'',
            tituloModal:'',
            caracteristicasProductoModificar:'',
            cantidadIngresoAlmacen:'',
            p_lista:'',//precio de lista
            c_disp:'',//Cantidad Envase o Embalaje
            p_compra:'',//Costo de Compra
            p_venta:'',//Precio de Venta
            id_ingreso:'',
            alm_tda:'',
            tienda_gespreventa:'',
            almacen_gespreventa:'',
            //poracion de utilidad
            margen_20:0,
            margen_30:0,
            utilidad_bruta:0,
            utilidad_neta:0,
            //gestor costo compra
            pcc:0,//Precio de Compra
            dpc1:0,//descuento uno
            dpc2:0,//descuento dos
            dpc3:0,//descuento tres
            dbsc:0,//Descuento Bs.
            pcdc:0,//Costo Compra C/Desc.
            puc:0,//Precio Unitario
            //Gestor Precio Venta
            pucc:0,//P/U de Compra
            l20pc:0,//Liq. 20 %
            l30pc:0,//Liq. 30 %
            upc:0,//Utilidad Bruta%
            pvc:0,//Precio de venta  gestion 
            //lista...
            listaX:'',
            selectLista:0,
            arrayLista:[],
            clear:0,
//---permisos_R_W_S
puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------


            

        }

    },
   
       
    computed: {

sicompleto(){
    let me = this;
    if(me.p_venta > 0 && me.margen_20>=0 && me.margen_30>=0 && me.utilidad_neta>=0)
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

    //-----------------------------------permisos_R_W_S        
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

    cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },
    cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarProductosTiendaAlmacen(page);
        },
        cambiarEstado() {
    this.selectLista = 0;
    this.arrayProductosAlterado = [];
    this.pagination = {
      total: 0,
      current_page: 0,
      per_page: 0,
      last_page: 0,
      from: 0,
      to: 0
    };
  },
    listarProductosTiendaAlmacen(page) {       
        let me=this;
        if (page!=undefined&&+me.tiendaalmacenselected!=0&&me.selectLista!=0) {
            var url='/gestionprecioventa2?page='+page+'&buscar='+me.buscar+'&buscarAlmTdn='+me.tiendaalmacenselected+'&lista='+me.selectLista;
   
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayProductosAlterado = respuesta.queryCombinacion.data;
                   
                })
                .catch(function(error){
                    error401(error);
                });
        } 
     
          me.clear=0;      
        
    },  
    listarLista(code){
            let me = this;
            var url = "/gestionprecioventa2/listarLista?codigo="+code;
       
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayLista = respuesta;
                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
    listarAlmTienda() {
            let me = this;
            //var url = "/gestionprecioventa2/listarSucursal";
            var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario"; 
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmacenesTiendas = respuesta;                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
     
    abrirModal(accion, data = []) {
            let me = this;
       
            switch (accion) {
               
                case 'editarPrecioUtilidadProducto':
                    {
                        me.codigo=data.cod;                       
                        me.id_gespreventa=data.gpv_id;              
                        me.tituloModal = 'Modificar Utilidad del Producto2';
                        me.caracteristicasProductoModificar=data.leyenda;
                        me.cantidadIngresoAlmacen=data.cantidad_ingreso;
                        me.p_lista=data.preciolistaEnvase;
                        me.c_disp=data.cantidadEnvase;
                        me.activarEvento=0;
                        //me.p_compra=data.costocompraEnvase;
                       // me.p_compra=999999;
                          if (data.costo_compra_gespreventa==null) {
                          me.p_compra=data.costocompraEnvase;
                       } else {
                           me.p_compra=data.costo_compra_gespreventa;
                       }
                        me.p_venta=data.precioventaEnvase;
                        if(data.listo_venta===null){
                            me.margen_20=0;
                        me.margen_30=0;
                        me.utilidad_bruta=0;
                        me.utilidad_neta=0;
                        }else{
                            me.margen_20=data.margen_20p_gespreventa;
                        me.margen_30=data.margen_30p_gespreventa;
                        me.utilidad_bruta=data.utilidad_bruta_gespreventa;
                        me.utilidad_neta=data.utilidad_neto_gespreventa;
                       
                        }
                        me.pcc=data.preciolistaEnvase;
                        me.dpc1=0;
                        me.dpc2=0;
                        me.dpc3=0;
                        me.dbsc=0;
                        me.pcdc=data.preciolistaEnvase;
                        me.puc=data.costocompraEnvase;
                        me.pucc=0;                 
                        me.l20pc=0;
                        me.l30pc=0;
                        me.upc=0;
                        me.pvc=0;   
                        me.id_ingreso=data.id;    
                        me.alm_tda=data.cod.substring(0, (data.cod).length - 3); 
                        if(me.selectLista=='x'){
                        me.listaX="x";    
                        }
                        else{
                        me.listaX=data.id_lista;    
                        }
                        if(me.alm_tda==='TDA'){
                            me.tienda_gespreventa=1;
                            me.almacen_gespreventa=0;
                        } else {
                            if (me.alm_tda==='ALM') {
                            me.almacen_gespreventa=1; 
                            me.tienda_gespreventa=0; 
                            }else{
                                console.log("error");
                            }
                        }   
                             
                        me.classModal.openModal('calculadoraModal');
                        
                        break;
                    }

            }

        },
        cerrarModal(accion) {
            let me = this;
            me.caracteristicasProductoModificar='';
            me.cantidadIngresoAlmacen='';
            me.p_lista='';
            me.c_disp='';
            me.p_compra='';
            me.p_venta='';
            me.id_gespreventa='';
            me.margen_20=0;
            me.listaX='';
                        me.margen_30=0;
                        me.utilidad_bruta=0;
                        me.utilidad_neta=0;
                        me.pcc=0;
                        me.dpc1=0;
                        me.dpc2=0;
                        me.dpc3=0;
                        me.dbsc=0;
                        me.pcdc=0;
                        me.puc=0;
                        me.pucc=0;                 
                        me.l20pc=0;
                        me.l30pc=0;
                        me.upc=0;
                        me.pvc=0;  
                        me.activarEvento=0;
            me.classModal.closeModal(accion);        
        },
        selectAll: function (event) {
            setTimeout(function () {
                event.target.select()
            }, 0)
        },
        actualizarRegistrarPrecioVenta() {
            let me = this;
            if (me.id_gespreventa==undefined || me.id_gespreventa==null  ) {
                axios.post('/gestionprecioventa2/registrar', {
               // 'id':me.id_gespreventa,
                'codigo':me.codigo,                
                'id_table_ingreso_tienda_almacen':me.id_ingreso,   
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
                'id_lista':me.listaX,
            }).then(function (response) {
                me.cerrarModal('calculadoraModal');
                me.listarProductosTiendaAlmacen(1);
                Swal.fire(
                    'Almacen Registrado exitosamente',
                    'Haga click en Ok',
                    'success'
                );
                
                
            })
            
            //.catch(function (error) {
            //    error401(error);
            //    console.log(error);
           // });
           .catch(function (error) {                  
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            });
            }else{
                axios.post('/gestionprecioventa2/actualizar', {
                'id':me.id_gespreventa,
                'codigo':me.codigo,                
                'id_table_ingreso_tienda_almacen':me.id_ingreso,   
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
                'id_lista':me.listaX,
            }).then(function (response) {
                me.cerrarModal('calculadoraModal');
                me.listarProductosTiendaAlmacen(1);
                Swal.fire(
                    'Almacen Registrado exitosamente',
                    'Haga click en Ok',
                    'success'
                );          
                
            })
            
            //.catch(function (error) {
            //    error401(error);
            //    console.log(error);
           // });
           .catch(function (error) {           
                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
            }
            
            
        },
        utilidad() {

let me = this;
// var p_compra = parseFloat(me.p_compra);
me.margen_20 = ((parseFloat(me.p_compra) * 100) / 80).toFixed(2);
me.margen_30 = ((parseFloat(me.p_compra) * 100) / 70).toFixed(2);
me.utilidad_bruta = (parseFloat(me.p_venta) - parseFloat(me.p_compra)).toFixed(2);
me.utilidad_neta = ((parseFloat(me.p_venta) - parseFloat(me.p_compra)) / me.p_venta) * 100;
me.utilidad_neta = Math.round(me.utilidad_neta);
if (me.p_compra>0) {
    me.activarEvento=1;
} else {
    me.activarEvento=2;
}
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
             me.pcdc = parseFloat(me.pcdc);
            me.pvc = parseFloat(me.pvc == 0 ? me.p_venta:me.pvc); // pvc = Precio de Venta
            me.pucc = parseFloat(me.pucc == 0 ? me.puc:me.pucc); // pucc = P/U de Compra
            me.l20pc = ((me.pucc * 100) / 80); // l20pc = Liq. 20 %
            me.l30pc = ((me.pucc * 100) / 70); // l30pc = Liq. 30 %
            me.ubc = (me.pvc - me.pucc); // ubc = Utilidad Bruta
            me.upc = ((me.ubc * 100) / me.pvc); //  upc = Utilidad Bruta%


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
    //-------permiso E_W_S-----
    this.listarPerimsoxyz();
             // this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
        this.classModal = new _pl.Modals();
        this.listarProductosTiendaAlmacen();
        this.listarAlmTienda();
        this.classModal.addModal('calculadoraModal');
       
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