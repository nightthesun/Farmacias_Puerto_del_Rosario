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
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Traspaso entre almacenes y tiendas               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar'); ProductoLineaIngreso(); "
                        :disabled="sucursalSeleccionada == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span >
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align: center">
                            <label for="">Almacen o Tienda Origen:</label>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select
                                    class="form-control"
                                    @change="listarAjusteNegativos(0)"
                                    v-model="sucursalSeleccionada"
                                >
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option
                                        v-for="sucursal in arraySucursal"
                                        :key="sucursal.id"
                                        :value="sucursal.codigo"
                                        v-text="
                                            sucursal.codigoS +
                                            ' -> ' +
                                            sucursal.codigo+
                                            ' ' +
                                            sucursal.razon_social
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
                                    @keyup.enter="listarAjusteNegativos(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarAjusteNegativos(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>

                  <table class="table table-bordered table-striped table-sm table-responsive" >
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nro.Traspaso</th>                         
                            <th>Producto</th>
                            <th>Envase</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Glosa</th>
                            <th>Origen</th>                            
                            <th>Destino</th>
                            <th>Tipo</th>                
                            <th>Responsable</th>
                            <th>Estado</th>
                            <th>Estado de traspaso</th>
                            

                        </tr>
                    </thead>
                        <tbody v-if="sucursalSeleccionada == 0"></tbody>
                        <tbody v-if="sucursalSeleccionada != 0">
                            <tr v-for="AjusteNegativos in arrayAjusteNegativos" :key="AjusteNegativos.id" >
                                <td>
                                    <div v-if="AjusteNegativos.estado_procesado == 3">
                                        <button
                                        type="button"
                                        class="btn btn-light  btn-sm"
                                      
                                    >
                                        <i class="icon-pencil"></i>
                                    </button>
                                    &nbsp;
                                    <button
                                        v-if="AjusteNegativos.activo == 1"
                                        type="button"
                                        class="btn btn-light  btn-sm"
                                      
                                    >
                                        <i class="fa fa-hand-paper-o"></i>
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="btn btn-light  btn-sm"
                                     
                                    >
                                        <i class="icon-check"></i>
                                    </button>

                                    </div>
                                    <div v-else>
                                        <button
                                        type="button"
                                        class="btn btn-warning btn-sm"
                                        @click="
                                            abrirModal(
                                                'actualizar',
                                                AjusteNegativos,
                                            );
                                            ProductoLineaIngreso();
                                        "
                                    >
                                        <i class="icon-pencil"></i>
                                    </button>
                                    &nbsp;
                                    <button
                                        v-if="AjusteNegativos.activo == 1"
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        @click="
                                            eliminarAjusteNegativos(
                                                AjusteNegativos.id,
                                            )
                                        "
                                    >
                                        <i class="fa fa-hand-paper-o"></i>
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="btn btn-info btn-sm"
                                        @click="
                                            activarAjusteNegativos(
                                                AjusteNegativos.id,
                                            )
                                        "
                                    >
                                        <i class="icon-check"></i>
                                    </button>
                                    </div>     
                                    
                                </td>

                                <td v-text="AjusteNegativos.numero_traspaso"></td>
                                <td v-text="AjusteNegativos.leyenda"></td>
                                <td v-text="AjusteNegativos.envase"></td>
                                <td v-text="AjusteNegativos.cantidad"></td>
                                <td v-text="AjusteNegativos.fecha"></td>
                                <td v-text="AjusteNegativos.glosa"></td>
                                <td v-text="AjusteNegativos.origen"></td>
                                <td v-text="AjusteNegativos.destino"></td>
                                <td v-text="AjusteNegativos.tipo_traspaso"></td>
                                <td v-text="AjusteNegativos.user_name"></td>
                                <td>
                                    <div v-if="AjusteNegativos.activo == 1">
                                        <span class="badge badge-success"
                                            >Activo</span
                                        >
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning"
                                            >Desactivado</span
                                        >
                                    </div>
                                </td>
                                <td>
                                    <div v-if="AjusteNegativos.estado_procesado == 0">
                                        <span class="badge badge-warning">Pendiente</span>
                                    </div>
                                    <div v-if="AjusteNegativos.estado_procesado == 1">
                                        <span class="badge badge-primary">En Proceso</span>
                                    </div>
                                    <div v-if="AjusteNegativos.estado_procesado == 2">
                                        <span class="badge badge-success">Procesado</span>
                                    </div>
                                    <div v-if="AjusteNegativos.estado_procesado == 3">
                                        <span class="badge badge-danger">Anulado</span>
                                    </div>
                                    
                                </td>
                            </tr>
                        </tbody>    
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
            <!-- Fin ejemplo de tabla Listado -->
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
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <form action="" class="form-horizontal">
                            <div class="form-group col-sm-8" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Origen: <span>{{tipoCodigo}} -> </span> <span> {{ razon_social }}</span></strong>
                                     
                                </div>
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">
                                   
                                    <label class="col-md-3 form-control-label" for="text-input">
                                    <strong>   Producto:</strong>
                                        <span
                                            v-if="ProductoLineaIngreso == '0'"
                                            class="error"
                                            >(*)</span
                                        >
                                    </label>
                                    <div class="col-md-7 input-group mb-3">
                                        <select v-model="ProductoLineaIngresoSeleccionado"
                                            class="form-control"  @change="cambioDeEstado">
                                            <option v-bind:value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option
                                                v-for="ProductoLineaIngreso in arrayProductoLineaIngreso"
                                                :key="
                                                    ProductoLineaIngreso.id_ingreso
                                                "
                                                v-bind:value="
                                                    ProductoLineaIngreso.id_ingreso
                                                "
                                                v-text="
                                                    ProductoLineaIngreso.leyenda +
                                                    ' | Lote: ' +
                                                    ProductoLineaIngreso.lote +
                                                    ' | FI: ' +
                                                    ProductoLineaIngreso.fecha_ingreso +
                                                    ' | FV: ' +
                                                    (ProductoLineaIngreso.fecha_vencimiento ===
                                                    null
                                                        ? '| sin registro'
                                                        : ProductoLineaIngreso.fecha_vencimiento) +
                                                    ' | Stock: ' +
                                                    ProductoLineaIngreso.stock_ingreso
                                                "
                                            ></option>
                                        </select>
                                        <button
                                            class="btn btn-primary"
                                       
                                            type="button"
                                            id="button-addon1"
                                            @click="
                                                abrirModal(
                                                    'bucarProductoIngreso',
                                                );
                                                ListarretornarProductosIngreso();
                                            "
                                        >
                                            <i class="fa fa-search"></i>
                                        </button>
                                       <!--
                                        <input v-if="tipoAccion == 2" type="text" v-model="leyenda" class="form-control"
                                            placeholder="letenda" disabled />
                                       --> 
                                        
                                    </div>
                               
                                <div class="form-group col-sm-4" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Cantidad existente: <span>{{ cantidadProductoLineaIngreso }}</span></strong>
   
                                </div>

                                <div class="form-group col-sm-4" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Tipo Entrada: <span> Traspaso</span></strong>
                                   
                                </div>
                                <div class="form-group col-sm-4" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Lote: <span> {{ lote }}</span></strong>
                                     
                                </div>
                                <div class="form-group col-sm-4" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Fecha de vencimiento: <span> {{ fecha_vencimiento }}</span></strong>
                                  
                                </div>
                                <div class="form-group col-sm-4" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Registro sanitario: <span> {{registro_sanitario}}</span></strong>
                                  
                                </div>
                                <div class="form-group col-sm-4" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Id ingreso: <span> 000{{ id_ingreso }}</span></strong>
                                </div>
                                <div class="form-group col-sm-4" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <strong>Id productor: <span> {{ id_producto}}</span></strong>
                                </div>
        
                                      </div>
                                 <div class="form-group row" v-if="ProductoLineaIngresoSeleccionado!=''"> 
                                    <label
                                        class="col-md-3 form-control-label"
                                        for="text-input"
                                    >
                                    <strong>Destino</strong>    
                                        <span class="error">(*)</span>
                                    </label>
                                    <div class="col-md-7">
                                        <select
                                            name=""
                                            id=""
                                            v-model="sucursalSeleccionadaDestino"
                                            class="form-control"
                                        >
                                            <option value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option
                                            v-for="sucursal in arraySucursalDestino"
                                        :key="sucursal.id"
                                        :value="sucursal.codigo"
                                        v-text="
                                            sucursal.codigoS +
                                            ' -> ' +
                                            sucursal.codigo+
                                            ' ' +
                                            sucursal.razon_social
                                        "
                                            ></option>
                                        </select>
                                        <span
                                            v-if="sucursalSeleccionadaDestino == 0"
                                            class="error"
                                            >Debe seleccionar una opcion</span
                                        >
                              
                                    </div>
                                </div>
                                <div class="form-group row" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <label
                                        class="col-md-3 form-control-label"
                                        for="text-input"
                                        ><strong>Cantidad a enviar</strong> 
                                        <span
                                            v-if="cantidadS == ''"
                                            class="error"
                                            >(*)</span
                                        >
                                    </label>
                                    <div class="col-md-5">
                                        <input v-if="tipoAccion == 1"
                                            type="number"
                                            min="0"
                                            id="cantidad"
                                            name="cantidad"
                                            v-model="cantidadS"
                                            class="form-control"
                                            placeholder="Datos de stock"
                                            v-on:focus="selectAll"
                                        />
                                       <input v-if="tipoAccion == 2"
                                            type="number"
                                            min="0"
                                            id="cantidad"
                                            name="cantidad"
                                            v-model="cantidadS"
                                            class="form-control"
                                            placeholder="Datos de stock"
                                            v-on:focus="selectAll" 
                                         
                                        />
                                        <span
                                            v-if="cantidadS == ''"
                                            class="error"
                                            >Debe Ingresar una cantidad</span
                                        >
                                    </div>


                                </div>
                                <div class="form-group row" v-if="ProductoLineaIngresoSeleccionado!=''">
                                    <label
                                        class="col-md-3 form-control-label"
                                        for="text-input"
                                        ><strong>Glosa</strong> 
                                        <span v-if="glosa" class="error"
                                            >(*)</span
                                        >
                                    </label>
                                    <div class="col-md-9">
                                        <textarea
                                            v-model="glosa"
                                            class="form-control"
                                            id="glosa"
                                            name="glosa"
                                            rows="3"
                                            v-on:focus="selectAll"
                                        ></textarea>
                                        <span
                                            v-if="glosa === ''"
                                            class="error"
                                            >Debe Ingresar la glosa</span
                                        >
                                    </div>
                                </div>
                               
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="cerrarModal('registrar')"
                        >
                            Cerrar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 1"
                            class="btn btn-primary"
                            @click="registrorAjusteNegativo()"
                            :disabled="!sicompleto"
                        >
                            Guardar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 2"
                            class="btn btn-primary"
                            @click="actualizarAjusteNegativo()"
                        >
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
        <!-- Modal para la busqueda de producto por lote -->
        <div
            class="modal fade"
            id="staticBackdrop"
            tabindex="-2"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Busqueda de Productos
                        </h5>
                        <button
                            type="button"
                            @click="cerrarModal('staticBackdrop')"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            X
                        </button>
                    </div>

                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label
                                    for="exampleInputEmail1"
                                    class="form-label"
                                    >Introduzca el codigo Internacional:
                                </label>
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="inputTextBuscarProductoIngreso"
                                    @input="ListarretornarProductosIngreso()"
                                />
                                <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                            </div>
                            <div v-if="tipoAccion==1">
                                <table
                                    class="table table-hover"
                                    id="tablaProductosIngreso"
                                    style="
                                        height: 350px;
                                        display: block;
                                        overflow: scroll;
                                    "
                                >
                                    <thead>
                                        <tr>
                                            <th scope="col">Id Ingreso.</th>
                                            <th scope="col">
                                                Descripcion Prod.
                                            </th>
                                            <th scope="col">Envase</th>
                                            <th scope="col">
                                                Codigo Internacional
                                            </th>
                                        </tr>
                                    </thead>
                                        
                                    <tbody>
                                        <tr
                                            v-for="RetornarProductosIngreso in arrayRetornarProductosIngreso" :key=" RetornarProductosIngreso.id_ingreso"  @click="abrirModal('inputModal',RetornarProductosIngreso,
                                                );
                                                ListarretornarProductosIngreso();
                                            "
                                        >
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.id_ingreso
                                                "
                                            ></td>
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.leyenda
                                                "
                                            ></td>
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.envase
                                                "
                                            ></td>
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.codigointernacional
                                                "
                                            ></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-if="tipoAccion==2">
                                <table
                                    class="table table-hover"
                                    id="tablaProductosIngreso"
                                    style="
                                        height: 350px;
                                        display: block;
                                        overflow: scroll;
                                    "
                                >
                                    <thead>
                                        <tr>
                                            <th scope="col">Id Ingreso.</th>
                                            <th scope="col">
                                                Descripcion Prod.
                                            </th>
                                            <th scope="col">Envase</th>
                                            <th scope="col">
                                                Codigo Internacional
                                            </th>
                                        </tr>
                                    </thead>
                                        
                                    <tbody>
                                        <tr
                                            v-for="RetornarProductosIngreso in arrayRetornarProductosIngreso" :key=" RetornarProductosIngreso.id_ingreso"  @click="abrirModal('inputModal22',RetornarProductosIngreso,
                                                );
                                                ListarretornarProductosIngreso();
                                            "
                                        >
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.id_ingreso
                                                "
                                            ></td>
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.leyenda
                                                "
                                            ></td>
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.envase
                                                "
                                            ></td>
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.codigointernacional
                                                "
                                            ></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                   
                    <div class="modal-footer" v-if="tipoAccion == 1">
                        
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            @click="
                                cerrarModal('staticBackdrop');
                                abrirModal('registrar');
                                ProductoLineaIngreso();
                            "
                        >
                            Cerrar
                        </button>
                   
                    </div>
                    
                    <div class="modal-footer" v-if="tipoAccion == 2">
                      
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            @click="abrirModal('inputModal22',RetornarProductosIngreso);  ListarretornarProductosIngreso();
                                            "
                        >
                            Cerrar
                        </button>
                   
                    </div>
                </div>
            </div>
        </div>
        <!-- Fun Modal para la busqueda de producto por lote -->
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
            arrayTipos: [],
            TiposSeleccionado: 0,
            id_tipo: "",
            arrayProductoLineaIngreso: [],
            ProductoLineaIngresoSeleccionado: 0,

            id_producto_linea: "",
            cantidadS: "",
            listarTipo: 0,
            cantidadProductoLineaIngreso: "",
     
            codigo: "",
            linea: "",
            producto: "",
          
            id_ingreso: "",
            id_producto: "",
            id_codigo: "",
            arrayAjusteNegativos: [],

            fecha_ingreso:'',
                fecha_vencimiento:'',
                lote:'',
                cantidad:'',

            buscar: "",
            idAjusteNegativos: 0,
            offset: 3,
            tipoAccion: 1,
            id_sucursal: "",
            arraySucursal: [],
            sucursalSeleccionada: 0,
            sucuralName: "",
            sucursalId: 0,

            inputTextBuscarProductoIngreso: "",
            arrayRetornarProductosIngreso: [],
            leyenda: "",
            /////////////////
            razon_social: "", 
            tipoCodigo:"",  
            registro_sanitario:"",
            arraySucursalDestino: [],
            sucursalSeleccionadaDestino: 0,
            glosa:"",
            id_almacen_tienda:"",
            envase:"",
            ////////////
            lista_id_almacen_id_tienda:"",
            codigoDestino:"",
            razon_social_des:"",
        };
    },

    watch: {
        ProductoLineaIngresoSeleccionado: function (newValue) {
            if (newValue > 0) {
                if (this.tipoAccion === 1) {
                    this.cantidadS = 0;
                }

                let productoSeleccionado = this.arrayProductoLineaIngreso.find(
                    (element) => element.id_ingreso === newValue,
                );
              
                if (productoSeleccionado) {
                    this.cantidadProductoLineaIngreso =
                        productoSeleccionado.stock_ingreso;
                    this.codigo = productoSeleccionado.codigo_producto;
                    this.fecha_ingreso=productoSeleccionado.fecha_ingreso;
        this.fecha_vencimiento=productoSeleccionado.fecha_vencimiento;
        this.linea=productoSeleccionado.nombre_linea;
        this.producto=productoSeleccionado.nombre;
         this.id_sucursal=productoSeleccionado.id_sucursal;
         this.id_producto=productoSeleccionado.id_producto; 
         this.id_ingreso=productoSeleccionado.id_ingreso;
         this.lote=productoSeleccionado.lote;   
         this.cantidad=productoSeleccionado.cantidad_ingreso;
         this.razon_social=productoSeleccionado.razon_social;
         this.tipoCodigo=productoSeleccionado.tipoCodigo;
         this.registro_sanitario=productoSeleccionado.registro_sanitario;
         this.id_almacen_tienda=productoSeleccionado.id_almacen_tienda;
         this.envase=productoSeleccionado.envase;
                    this.leyenda =
                        productoSeleccionado.leyenda +
                        " | lote: " +
                        productoSeleccionado.lote +
                        " | FI:" +
                        productoSeleccionado.fecha_ingreso +
                        " | FV:" +
                        productoSeleccionado.fecha_vencimiento;
                } else {
                    console.log(
                        "No matching element found in arrayProductoLineaIngreso.",
                    );
                }
            } else {
                this.cantidadS = "";
            }
        },
      
        cantidadS: function (valor) {
            if (valor < 0) {
                if (this.tipoAccion === 1) {
                    this.cantidadS = 0;
                }

                Swal.fire(
                    'No puede ingresar un número negativos',
                        'Haga click en Ok',
                        'error'
                );
             
            } else if (valor !== this.cantidadProductoLineaIngreso) {
                this.cantidadS = valor;
            }
            if (valor > this.cantidadProductoLineaIngreso) {
                if (this.tipoAccion === 1) {
                    this.cantidadS = 0;
                }

                Swal.fire(
                    "No puede ingresar un número mayor al stock actual",
                    "Haga click en Ok",
                    "error",
                );
                console.log(
                    "No se puede ingresar datos mayor que el stock actual",
                );
            } else if (valor !== this.cantidadProductoLineaIngreso) {
                this.cantidadS = valor;
            }

        },
sucursalSeleccionadaDestino: function (newValue) {
    
    let sucursalSeleccionadoD = this.arraySucursalDestino.find(
        (element) => element.codigo === newValue,
    );
    
    if (sucursalSeleccionadoD) {
        this.lista_id_almacen_id_tienda = sucursalSeleccionadoD.lista_id_almacen_id_tienda;
        this.codigoDestino = sucursalSeleccionadoD.codigo;
        this.razon_social_des=sucursalSeleccionadoD.razon_social;


    }
},
    },

    computed: {
        sicompleto() {
            let me = this;
            if (
          
                me.glosa != "" &&
                me.cantidadS != "" &&
                me.ProductoLineaIngresoSeleccionado
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
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },
        sucursalFiltro() {
            let me = this;
            var url = "/traspaso/listarSucursal";
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
        sucursalFiltroDestino() {
            let me = this;
            var url = "/traspaso/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursalDestino = respuesta;
                    console.log(me.arraySucursalDestino);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        ajustesNegativos() {
            let me = this;
            var url = "/ajustes-positivo/listarTipo";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayTipos = respuesta;
                    console.log(me.arrayTipos);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        cambiodeEstado(valor) {
            let me = this;
            const productoSeleccionado = me.arrayProductoLineaIngreso.find(
                (element) =>
                    element.id == this.ProductoLineaIngresoSeleccionado,
            );
        },

        ProductoLineaIngreso() {
            let me = this;

            if (me.tipoAccion == 1) {
                var url =
                    "/traspaso/listarProductoLineaIngreso?respuesta0=" +
                    this.sucursalSeleccionada;
            }
            if (me.tipoAccion == 2) {
                var url =
                    "/traspaso/listarProductoLineaIngreso?respuesta0=" +
                    this.sucursalSeleccionada;
            }
         
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;

                    me.arrayProductoLineaIngreso = respuesta;

                    console.log(me.arrayProductoLineaIngreso);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        ListarretornarProductosIngreso() {
            let me = this;
            console.log("--- "+me.sucursalSeleccionada+"--- "+me.inputTextBuscarProductoIngreso);
            if (me.tipoAccion == 1) {
                var url ="/traspaso/retornarProductosIngreso?respuesta0=" + this.sucursalSeleccionada +
                    "&respuesta1=" + me.inputTextBuscarProductoIngreso;
            }
            if (me.tipoAccion == 2) {
                var url ="/traspaso/retornarProductosIngreso?respuesta0=" + this.sucursalSeleccionada +
                    "&respuesta1=" + me.inputTextBuscarProductoIngreso;
            }
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;

                    me.arrayRetornarProductosIngreso = respuesta;
   })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarAjusteNegativos(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
            let respuesta = me.arraySucursal.find(
                (element) => element.codigo == me.sucursalSeleccionada,
            );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de traspaso origen "+respuesta.razon_social;
                    me.ProductoLineaIngresoSeleccionado = 0;
                    me.id_codigo = me.sucursalSeleccionada;
                    me.cantidadProductoLineaIngreso = '';                 
                    me.codigo = '';
                    me.linea = '';
                    me.producto = '';
                    me.cantidadS = '';              
                    me.fecha_ingreso='';
                    me.fecha_vencimiento='';
                    me.lote='';
                    me.cantidad='';                    
                    me.id_sucursal ='';
                    me.id_producto = '';
                    me.id_ingreso = '';                    
                    me.leyenda = '';
                    //
                    me.razon_social='';
                    me.razon_social_des='';
                    me.tipoCodigo='';
                    me.registro_sanitario='';
                    me.sucursalSeleccionadaDestino=0;
                    me.glosa='';
                    me.id_almacen_tienda='';
                    me.envase='';
                    me.lista_id_almacen_id_tienda='';
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
                    me.id_producto = data.id_prod_producto;
                    me.ProductoLineaIngresoSeleccionado = data.id_ingreso === null ? 0 : data.id_ingreso;
                    me.sucursalSeleccionadaDestino=data.cod_2 === null ? 0 : data.cod_2;
                    me.registro_sanitario=data.registro_sanitario;
                    me.envase=data.envase;
                    me.leyenda = data.leyenda;
                    me.id_codigo = data.cod;
                    me.tituloModal = "Traspaso origen "+respuesta.razon_social;
                    me.codigo = data.codigo;
                    me.cantidadProductoLineaIngreso = data.cantidad;
                    me.linea = data.linea;
                    me.producto = data.nombreProd;
                    me.cantidadS = data.cantidad;
                    me.id_almacen_tienda=data.id_almacen_tienda;
                    me.fecha_ingreso=data.fecha_ingreso;
                    me.fecha_vencimiento=data.fecha_vencimiento;
                    me.lote=data.lote;
                    me.cantidad=data.cantidad;
                    me.idAjusteNegativos = data.id;
                    me.id_sucursal = data.id_sucursal;
                  
                    me.id_ingreso = data.id_ingreso;
                    me.glosa=data.glosa;
                   
                    me.tipoCodigo =respuesta.tipoCodigo;
                    me.razon_social=respuesta.razon_social;
            
                    me.classModal.openModal("registrar");

                    break;
                }
                case "bucarProductoIngreso": {
                    //  me.tipoAccion=1;
                    me.ProductoLineaIngresoSeleccionado = 0;
                    me.inputTextBuscarProductoIngreso = "";
                        me.arrayRetornarProductosIngreso = "";
                        me.classModal.openModal("staticBackdrop");
                    break;
                }
                case "bucarProductoIngreso2": {
                    //   me.tipoAccion=2;
                    (me.inputTextBuscarProductoIngreso = ""),
                        (me.arrayRetornarProductosIngreso = ""),
                        me.classModal.openModal("staticBackdrop");
                    break;
                }

                case "inputModal": {
                 
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro traspaso origen "+respuesta.razon_social;
                    me.ProductoLineaIngresoSeleccionado =
                        data.id_ingreso === null ? 0 : data.id_ingreso;

                    me.cantidadProductoLineaIngreso = "";
                    me.TiposSeleccionado = 0;

                    me.codigo = "";
                    me.linea = "";
                    me.producto = "";
                    
          
                   me.fecha_ingreso='';
                    me.fecha_vencimiento='';
                    me.lote='';
                    me.cantidad='';
                    me.id_sucursal = "";
                    me.id_producto = "";
                    me.id_ingreso = "";
                    me.leyenda = "";
                   me.glosa="";
                  me.cantidadS = "";
                   me.sucursalSeleccionadaDestino=0;
                me.sucursalSeleccionadaDestino=0;
                    
                    me.classModal.openModal("registrar");
                    break;
                }
                case "inputModal22": {
                 
                 me.tipoAccion = 2;
                me.cantidadS=data.cantidad;
                 me.ProductoLineaIngresoSeleccionado = me.id_ingreso;
                 me.tituloModal = "Registro traspaso origen "+respuesta.razon_social;
              
                 
                 me.classModal.openModal("registrar");
                 break;
             }
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
               
                    me.tituloModal = " ";
                    me.ProductoLineaIngresoSeleccionado = 0;
                    me.id_codigo = me.sucursalSeleccionada;
                    me.cantidadProductoLineaIngreso = '';                 
                    me.codigo = '';
                    me.linea = '';
                    me.producto = '';
                    me.cantidadS = '';              
                    me.fecha_ingreso='';
                    me.fecha_vencimiento='';
                    me.lote='';
                    me.cantidad='';                    
                    me.id_sucursal = '';
                    me.id_producto = '';
                    me.id_ingreso = '';                    
                    me.leyenda = '';
                    //
                    me.razon_social='';
                    me.razon_social_des='';
                    me.tipoCodigo='';
                    me.registro_sanitario='';
                    me.sucursalSeleccionadaDestino=0;
                    me.glosa='';
                    me.id_almacen_tienda='';
                    me.envase='';
                    me.lista_id_almacen_id_tienda;
                    setTimeout(me.tiempo, 200); 
                    //me.ProductoLineaIngresoSeleccionado = 0;
                    me.inputTextBuscarProductoIngreso = "";
                        me.arrayRetornarProductosIngreso = "";
              
            } else {
                me.classModal.closeModal(accion);
              
                me.classModal.openModal("registrar");
            }
        },

        registrorAjusteNegativo() {
            let me = this;
         //   console.log("-----id_almace:"+me.id_almacen_tienda +" id_pro: " +me.id_producto +" id_ingr: " + me.id_ingreso+" enva: "+me.envase+" can: "+me.cantidadS+" fech: "+me.fecha_vencimiento+" lote: "+me.lote+" reg_sani: "
         //  +me.registro_sanitario+" id_alm_tinda: "+me.id_almacen_tienda+" lista_id_a_t: "+me.lista_id_almacen_id_tienda+" id_ingre: "+me.id_ingreso+" sucuSe: "+me.sucursalSeleccionada+" codigoDes "+me.codigoDestino
         //   +" > "+me.leyenda+" > "+me.glosa );
         console.log(me.producto+"-"+me.fecha_ingreso+"-"+me.id_sucursal+"-"+me.codigo+"-"+me.linea);
            if (
               me.ProductoLineaIngresoSeleccionado === 0 ||
                me.sucursalSeleccionadaDestino === 0 ||
                me.cantidadS === "" ||
                me.glosa === ""
       
     
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                if (me.sucursalSeleccionada==me.codigoDestino) {
                    Swal.fire(
                    "No puede hacer traspasos con la misma tienda o almacen.",
                    "Haga click en Ok",
                    "warning",
                );
                } else {
                    let suma = me.cantidadProductoLineaIngreso - me.cantidadS;
        
                    axios
                    .post("/traspaso/registrar", {
                        'id_almacen_tienda': me.id_almacen_tienda,
                        'id_prod_producto': me.id_producto,
                        'envase': me.envase,
                        'cantidad__stock_ingreso': suma,
                        'fecha_vencimiento': me.fecha_vencimiento,
                        'lote':me.lote, 
                        'registro_sanitario':me.registro_sanitario,
                        'activo': 1,
                        'id_origen':me.id_almacen_tienda,
                        'id_destino':me.lista_id_almacen_id_tienda,
                        'id_ingreso': me.id_ingreso,
                        'cod_1':me.sucursalSeleccionada,
                        'cod_2':me.codigoDestino,
                        'leyenda': me.leyenda,
                        'glosa':me.glosa,
                        'name_des':me.razon_social_des,
                        'name_ori':me.razon_social,

                        'linea':me.linea,
                        'prod_name':me.producto,
                        'fecha_ingreso':me.fecha_ingreso, 
                        'id_sucursal':me.id_sucursal,   
                        'codigo':me.codigo,   
                        'cantidad':me.cantidadS,                
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Se registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );

                        me.listarAjusteNegativos();
                        me.sucursalFiltro();
                    })
                    .catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                }
               
            }
        },
        actualizarAjusteNegativo() {
            let me = this;
            var mensajeX="";
     
       //       console.log("-----id_almace:"+me.id_almacen_tienda +" -- id_pro: " +me.id_producto +" -- id_ingr: " + me.id_ingreso+" --enva: "+me.envase+" --can: "+me.cantidadS+" fech: "+me.fecha_vencimiento+" lote: "+me.lote+" reg_sani: "
       //   +me.registro_sanitario+" id_alm_tinda: "+me.id_almacen_tienda+" lista_id_a_t: "+me.lista_id_almacen_id_tienda+" id_ingre: "+me.id_ingreso+" sucuSe: "+me.sucursalSeleccionada+" codigoDes "+me.codigoDestino
       //    +" > "+me.leyenda+" > "+me.glosa );
    
    //   console.log(me.producto+"-"+me.fecha_ingreso+"-"+me.id_sucursal+"-"+me.codigo+"-"+me.linea);
        if (
               me.ProductoLineaIngresoSeleccionado === 0 ||
                me.sucursalSeleccionadaDestino === 0 ||
                me.cantidadS === "" ||
                me.glosa === ""||
                me.ProductoLineaIngresoSeleccionado === undefined ||
                me.sucursalSeleccionadaDestino === undefined ||
                me.cantidadS === undefined ||
                me.glosa ===  undefined||
                me.cantidadS===undefined 
     
            )
            {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",

                    "Haga click en Ok",
                    "warning",
                );
            } else {
                axios
                .put("/traspaso/actualizar", {
                    id: me.idAjusteNegativos,
                    id_almacen_tienda: me.id_almacen_tienda,
                    id_prod_producto: me.id_producto,
                    envase: me.envase,
                    cantidad__stock_ingreso: me.cantidadS,
                    fecha_vencimiento: me.fecha_vencimiento,
                    lote:me.lote, 
                    registro_sanitario:me.registro_sanitario,                  
                    id_origen:me.id_almacen_tienda,
                    id_destino:me.lista_id_almacen_id_tienda,
                    id_ingreso: me.id_ingreso,
                        cod_1:me.sucursalSeleccionada,
                        cod_2:me.codigoDestino,
                       leyenda: me.leyenda,
                        glosa:me.glosa,
                       name_des:me.razon_social_des,
                       name_ori:me.razon_social,
                       
                       linea:me.linea,   
                       prod_name:me.producto,     
                       fecha_ingreso:me.fecha_ingreso,  
                       id_sucursal:me.id_sucursal,      
                       codigo:me.codigo,             
                })
                .then(function (response) {
                    me.listarAjusteNegativos();
                    me.sucursalFiltro();
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                .catch(function (error) {
                    error401(error);
                });
            me.cerrarModal("registrar");
            }
           
        },
        
        listarAjusteNegativos(page) {
            let me = this;
         
            var url ="/traspaso?page="+page+"&buscar=" +me.buscar+"&buscarAlmTdn=" +me.sucursalSeleccionada;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayAjusteNegativos =
                        respuesta.reusltadocombinado.data;
                        
                })
                .catch(function (error) {
                    error401(error);
                });
        },
        //para listar db  la consulta ingreso almacen y ingreso producto
        listarConsulta() {
            let me = this;
            var url = "/ajustes-positivo/retornarProductosIngreso";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayIngresoAlmacen_tienda = respuesta;
                    console.log(me.arrayTipos);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        eliminarAjusteNegativos(idAjusteNegativos) {
            let me = this;
           console.log(     idAjusteNegativos +     " - " + me.sucursalSeleccionada +  " - " +  me.id_ingreso    );
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons
                .fire({
                    title: "Esta Seguro de Desactivar?",
                    text: "Es una eliminacion logica",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .put("/traspaso/desactivar", {
                                id: idAjusteNegativos,
                                
                            })
                            .then(function (response) {
                                me.listarAjusteNegativos();
                                swalWithBootstrapButtons.fire(
                                    "Desactivado!",
                                    "El registro a sido desactivado Correctamente",
                                    "success",
                                );
                                me.listarAjusteNegativos();
                            })
                            .catch(function (error) {
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
                });
        },

        activarAjusteNegativos(idAjusteNegativos) {
            let me = this;
            console.log("----"+idAjusteNegativos);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            swalWithBootstrapButtons
                .fire({
                    title: "Esta Seguro de Activar?",
                    text: "Es una Activacion logica",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Activar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .put("/traspaso/activar", {
                                id: idAjusteNegativos,
                                cod: me.sucursalSeleccionada,
                                id_ingreso: me.id_ingreso,
                            })
                            .then(function (response) {
                                me.listarAjusteNegativos();
                                swalWithBootstrapButtons.fire(
                                    "Activado!",
                                    "El registro a sido Activado Correctamente",
                                    "success",
                                );
                            })
                            .catch(function (error) {
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
                });
        },
        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        this.classModal.addModal("registrar");
        this.listarAjusteNegativos(1);
        this.ajustesNegativos();
        this.cambiodeEstado();
        this.sucursalFiltro();
        this.sucursalFiltroDestino();
        this.classModal.addModal("staticBackdrop");
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
