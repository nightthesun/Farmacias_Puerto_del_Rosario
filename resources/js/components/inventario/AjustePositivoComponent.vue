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
                    <i class="fa fa-align-justify"></i> Ajustes Positivos
                    <button v-if="puedeCrear==1"
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar'); ProductoLineaIngreso(); "
                        :disabled="sucursalSeleccionada == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span
                    >
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align: center">
                            <label for="">Almacen o Tienda:</label>
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

                    <!---codigo antiguo-->

                    <!---------------------------------------------------------------->
                    <table
                        class="table table-bordered table-striped table-sm table-responsive"
                    >
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-1">Codigó</th>
                                <th class="col-md-1">Linea</th>
                                <th class="col-md-4">Producto</th>
                                <th >Can. Ingreso</th>
                                <th>Stock</th>
                                <th class="col-md-2">Fecha de Ingreso / Hora</th>
                                <th>Fecha de vencimiento</th>
                                <th class="col-md-2">Tipo</th>
                                <th class="col-md-1">Usuario</th>
                                <th>Estado</th>

                            </tr>
                        </thead>

                        <tbody v-if="sucursalSeleccionada == 0"></tbody>
                        <tbody v-if="sucursalSeleccionada != 0">
                            <!--botones de opciones-->
                            <tr v-for="AjusteNegativos in arrayAjusteNegativos" :key="AjusteNegativos.id">
                                <td class="col-md-1">
                                    <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',AjusteNegativos);
                                            ProductoLineaIngreso();" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="AjusteNegativos.activo == 1" type="button" class="btn btn-danger btn-sm"
                                            @click="eliminarAjusteNegativos(AjusteNegativos.id)" tyle="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activarAjusteNegativos(AjusteNegativos.id)" tyle="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="AjusteNegativos.activo == 1" type="button" class="btn btn-light btn-sm"
                                             tyle="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-light btn-sm"  tyle="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                    </div>       
                                </td>

                                <td class="col-md-1" v-text="AjusteNegativos.codigo"></td>
                                <td class="col-md-1" v-text="AjusteNegativos.linea"></td>
                                <td class="col-md-4" v-text="AjusteNegativos.leyenda"></td>
                                <td  v-text="AjusteNegativos.cantidad"></td>
                                <td v-text="AjusteNegativos.stock"></td>
                                <td class="col-md-2" v-text="AjusteNegativos.fecha"></td>
                                <td v-text="AjusteNegativos.fecha_vencimiento"></td>

                                <td  class="col-md-2" v-text="AjusteNegativos.nombreTipo + ' ' + AjusteNegativos.tras" v-if="AjusteNegativos.nombreTipo === 'Traspaso'"></td>
<td v-else v-text="AjusteNegativos.nombreTipo"></td>
                               
                                        <td class="col-md-1" v-text="AjusteNegativos.nombre_usuario"
                                ></td>
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
        <div
            class="modal fade"
            tabindex="-1"
            role="dialog"
            arial-labelledby="myModalLabel"
            id="registrar"
            aria-hidden="true"
            data-backdrop="static"
            data-key="false"
        >
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
                            <!-- insertar datos -->
                            <div class="container">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Producto:<span
                                            v-if="ProductoLineaIngreso == '0'" class="error">(*)</span>
                                    </label>
                                    <div class="col-md-7 input-group mb-3" v-if="tipoAccion == 1">
                                        <select v-model="ProductoLineaIngresoSeleccionado" v-if="tipoAccion == 1"
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
                                                v-text="'Cod: '+ProductoLineaIngreso.codigo_producto+
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
                                        <button class="btn btn-primary" v-if="tipoAccion == 1" type="button" id="button-addon1"
                                            @click="abrirModal('bucarProductoIngreso'); ListarretornarProductosIngreso();"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Muestra los productos mayores a cero">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <br>
                                        <!--boton de busqueda de productos en stock 0-->
                                        <button
                                            class="btn btn-danger" v-if="tipoAccion == 1" type="button"
                                            id="button-addon1" @click="abrirModal('stockCero');ListarretornarProductosIngresoCero();"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Muestra los productos en cero" >
                                            <i class="fa fa-search"></i>
                                        </button>
                                       
                                    </div>
                                    <div class="col-md-12 input-group mb-3" v-if="tipoAccion == 2">
                                        <select v-model="ProductoLineaIngresoSeleccionado" v-if="tipoAccion == 2"
                                            class="form-control"  :disabled="tipoAccion === 2" @change="cambioDeEstado">
                                            <option v-bind:value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option 
                                                v-for="ProductoLineaIngreso in arrayProductoLineaIngreso"
                                                :key=" ProductoLineaIngreso.id_ingreso"
                                                v-bind:value="ProductoLineaIngreso.id_ingreso" 
                                                
                                                v-text="
                                                    ProductoLineaIngreso.leyenda 
                                                    +
                                                    ' Stock: ' +
                                                    ProductoLineaIngreso.stock_ingreso+
                                                    ' Lote: ' +
                                                    ProductoLineaIngreso.lote +
                                                    ' FI: ' +
                                                    ProductoLineaIngreso.fecha_ingreso +
                                                    ' FV: ' +
                                                    (ProductoLineaIngreso.fecha_vencimiento ===
                                                    null ? '| sin registro' : ProductoLineaIngreso.fecha_vencimiento) 
                                                "
                                                
                                            ></option>
                                        </select>
                                    </div>
                                 
                                    <input type="text" v-if="tipoAccion == 1" v-model="leyenda" hidden/>
                                    <input type="text" v-if="tipoAccion == 1" v-model="id_codigo"  hidden/>
                                    <input type="number" v-if="tipoAccion == 1" v-model="cantidadProductoLineaIngreso" hidden/>
                                    <input type="text" v-if="tipoAccion == 1" v-model="codigo" hidden/>
                                    <input type="text" v-if="tipoAccion == 1" v-model="linea"  hidden/>
                                    <input type="text" v-if="tipoAccion == 1" v-model="producto" hidden/>  
                                    <input type="text" v-if="tipoAccion == 1" v-model="id_producto" hidden/>
                                    
                                    <input type="text" v-if="tipoAccion == 1" v-model="id_sucursal" hidden/>
                                    <input type="text" v-if="tipoAccion == 1" v-model="id_ingreso" hidden/>
                                    <input type="text" v-if="tipoAccion == 1" v-model="fecha_ingreso" hidden/>
                                    <input type="text" v-if="tipoAccion == 1" v-model="fecha_vencimiento" hidden/>
                                   
                                    <input type="text" v-if="tipoAccion == 1" v-model="lote" hidden>
                                    <input type="text" v-if="tipoAccion == 1"  v-model="cantidad" hidden>

                                    <!---datos  nulos-->
                                    <input type="text" v-if="tipoAccion == 2" v-model="leyenda" disabled hidden/>
                                    <input type="text" v-if="tipoAccion == 2" v-model="id_codigo" disabled hidden/>
                                    <input type="number" v-if="tipoAccion == 2" v-model="cantidadProductoLineaIngreso" disabled hidden/>
                                    <input type="text" v-if="tipoAccion == 2" v-model="codigo" disabled hidden/>
                                    <input type="text" v-if="tipoAccion == 2" v-model="linea" disabled hidden/>
                                    <input type="text" v-if="tipoAccion == 2" v-model="producto" disabled hidden/>  
                                    <input type="text" v-if="tipoAccion == 2" v-model="id_producto" disabled hidden/>
                                    
                                    <input type="text" v-if="tipoAccion == 2" v-model="id_sucursal" disabled hidden/>
                                    <input type="text" v-if="tipoAccion == 2" v-model="id_ingreso" disabled hidden/>
                                    <input type="text" v-if="tipoAccion == 2" v-model="fecha_ingreso" disabled hidden/>
                                    <input type="text" v-if="tipoAccion == 2" v-model="fecha_vencimiento" disabled hidden/>
                                   
                                    <input type="text" v-if="tipoAccion == 2" v-model="lote" disabled hidden>
                                    <input type="text" v-if="tipoAccion == 2"  v-model="cantidad" disabled hidden>  
                                 </div>

                                <div class="form-group row">
                                    <label
                                        class="col-md-3 form-control-label"
                                        for="text-input"
                                        >Cantidad:
                                        <span
                                            v-if="cantidadS == ''"
                                            class="error"
                                            >(*)</span
                                        >
                                    </label>
                                    <div class="col-md-9">
                                        <input v-if="tipoAccion == 1"
                                            type="number"
                                            id="cantidad"
                                            name="cantidad"
                                            v-model="cantidadS"
                                            class="form-control"
                                            placeholder="Datos de stock"
                                          
                                        />
                                       <input v-if="tipoAccion == 2"
                                            type="number"
                                            
                                            v-model="cantidadS"
                                            class="form-control"
                                            placeholder="Datos de stock"
                                            v-on:focus="selectAll" 
                                            disabled
                                        />
                                        <span
                                            v-if="cantidadS == ''"
                                            class="error"
                                            >Debe Ingresar una cantidad</span
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Tipo: <span class="error">(*)</span>
                                    </label>
                                    <div class="col-md-9">
                                        <select
                                            name=""
                                            id=""
                                            v-model="TiposSeleccionado"
                                            class="form-control"
                                        >
                                            <option value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option
                                                v-for="Tipos in arrayTipos"
                                                :key="arrayTipos.id"
                                                :value="Tipos.id"
                                                v-show="Tipos.id !== 13"
                                                v-text="Tipos.nombre"
                                            ></option>
                                        </select>
                                        <span
                                            v-if="TiposSeleccionado == 0"
                                            class="error"
                                            >Debe seleccionar una opcion</span>
                                    </div>
                                </div>
                            
                               
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrorAjusteNegativo()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarAjusteNegativo()" :disabled="!sicompleto">Actualizar</button>
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
        <!-- Modal para la busqueda de producto por lote -->
        <div
            class="modal fade"
            id="staticBackdrop"
            tabindex="-2"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
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
                            <div>
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
                                            <th scope="col">Nro Lote.</th>
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
                                                    RetornarProductosIngreso.lote
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
                    <div class="modal-footer">
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
                        <!--
                      <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" data-bs-dismiss="modal" @click="cerrarModal('staticBackdrop');abrirModal('actualizar',AjusteNegativos);ProductoLineaIngreso();">Cerraaaa</button>
                    
                    --->

                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Fun Modal para la busqueda de producto por lote -->
          <!-- Modal para la busqueda de producto por stock cero -->
          <div
            class="modal fade"
            id="modalCero"
            tabindex="-2"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-scrollable modal-danger">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Busqueda de Productos en stock cero
                        </h5>
                        <button
                            type="button"
                            @click="cerrarModal('modalCero')"
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
                                    >Introduzca descripcion:
                                </label>
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="inputTextBuscarProductoIngresoCero"
                                    @input="ListarretornarProductosIngresoCero()"
                                />
                                <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                            </div>
                            <div>
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
                                            <th scope="col">Nro Lote.</th>
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
                                        <tr  v-for="RetornarProductosIngreso in arrayRetornarProductosIngresoCero" :key="
                                             RetornarProductosIngreso.id_ingreso"  @click="abrirModal('registrarCeroX',RetornarProductosIngreso);
                                             ">
                                            <td
                                                v-text="
                                                    RetornarProductosIngreso.lote
                                                "
                                            ></td>
                                            <td
                                                v-text="RetornarProductosIngreso.codigo_producto +' '+
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
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            @click="
                                cerrarModal('modalCero');
                                abrirModal('registrar');
                                ProductoLineaIngreso();
                            "
                        >
                            Cerrar
                        </button>
                        <!--
                      <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" data-bs-dismiss="modal" @click="cerrarModal('staticBackdrop');abrirModal('actualizar',AjusteNegativos);ProductoLineaIngreso();">Cerraaaa</button>
                    
                    --->

                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Fun Modal stock cero para la busqueda de producto por lote -->
         <!--Inicio del modal actualizar stock cero-->
         <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="registrarCero" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-danger modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('registrarCero')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form  enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <strong class="col-md-9 form-control-label" for="text-input">Producto: {{productoX_name}}</strong>
                            
                               
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <strong>Cantidad: <span  v-if="cantidadX==0" class="error">(*)</span></strong>
                                    <input type="number" min="0" class="form-control" v-model="cantidadX" style="text-align:right" placeholder="0" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                    <span  v-if="cantidadX==0" class="error">Debe Ingresar la Cantidad </span>
                                </div>
                                <div class="form-group col-sm-4">
                                    <strong>Tipo Entrada:</strong>
                                    <select
                                            name=""
                                            id=""
                                            v-model="TiposSeleccionado"
                                            class="form-control"
                                        >
                                            <option value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option
                                                v-for="Tipos in arrayTipos"
                                                :key="arrayTipos.id"
                                                :value="Tipos.id"
                                               
                                                v-text="Tipos.nombre"
                                            ></option>
                                        </select>
                                        <span
                                            v-if="TiposSeleccionado == 0"
                                            class="error"
                                            >Debe seleccionar una opcion</span>
                                </div>
                                <div class="form-group col-sm-4">
                                    <strong>Lote: <span  v-if="loteX==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Lote"  v-model="loteX" v-on:focus="selectAll">
                                    <span  v-if="loteX==''" class="error">Debe Ingresar el lote</span>
                                </div>
                            </div>
                            <div class="row">
                               
                                <div class="form-group col-sm-4" >
                                    <strong>Fecha de Vencimiento: <span  v-if="fecha_vencimientoX==''" class="error">(*)</span></strong>
                                    <input type="date"  class="form-control" v-model="fecha_vencimientoX">
                                    <span  v-if="fecha_vencimientoX==''" class="error">Debe Ingresar la fecha de Vencimiento</span>
                                </div>
                                
                                <div class="form-group col-sm-4">
                                    <strong>Registro Sanitario:<span  v-if="registrosanitarioX==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Registro Sanitario" v-model="registrosanitarioX" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45 || event.charCode == 13 || event.charCode == 32 )">
                                    <span  v-if="registrosanitarioX==''" class="error">Debe Ingresar el Registro Sanitario</span>
                                </div>
                            </div>
                          
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrarCero')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary"  @click="actualizarProductoCeroTdaAlm()" >Guardar</button>
                    
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
//Vue.use(VeeValidate);
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
                to: 0,
            },
            pestañaActiva: "pills-envase-primario-tab",

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
          //  descripcion: "",
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
            isSubmitting: false, // Controla el estado del botón de envío
            tipoAccion: 1,
            id_sucursal: "",
            arraySucursal: [],
            sucursalSeleccionada: 0,
            sucuralName: "",
            sucursalId: 0,
            //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------


            inputTextBuscarProductoIngreso: "",
            arrayRetornarProductosIngreso: [],
            leyenda: "",
            stock_ingreso:"",

            //retorncero
            arrayRetornarProductosIngresoCero:[],    
            inputTextBuscarProductoIngresoCero:"",
            //modal actualizacion de datos
            cantidadX:"",
            fecha_vencimientoX:"",
            registrosanitarioX:"",
            loteX:"",
            productoX_name:"",
            id_ingresoX:"",
            codX:"",
            id_productoX:"",
            envaseX:"",
            id_alm_tda:"",
            arrayPemisoSuscursal:[],
                defaulSucural:'',
                codigoDefault:'',
                codigoDeRoles:'',//0=root,//1=defaul,2=tiene_permisos_Especificios
                codigoArray_p:[],

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
                    this.stock_ingreso=productoSeleccionado.stock_ingreso;
                    this.cantidadProductoLineaIngreso =
                        productoSeleccionado.stock_ingreso;
                    this.codigo = productoSeleccionado.codigo_producto;
                    this.fecha_ingreso=productoSeleccionado.fecha_ingreso;
        this.fecha_vencimiento=productoSeleccionado.fecha_ingreso;
        this.linea=productoSeleccionado.nombre_linea;
        this.producto=productoSeleccionado.nombre;
         this.id_sucursal=productoSeleccionado.id_sucursal;
         this.id_producto=productoSeleccionado.id_producto; 
         this.id_ingreso=productoSeleccionado.id_ingreso;
         this.lote=productoSeleccionado.lote;   
         this.cantidad=productoSeleccionado.cantidad_ingreso;
                    this.leyenda =
                        productoSeleccionado.leyenda +                       
                        "  FI:" +
                        productoSeleccionado.fecha_ingreso +
                        "  Lote: " +
                        productoSeleccionado.lote +
                        "  FV:" +
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
        },
       // buscar: function (valor) {
       //     if (this.buscar !== "") {
       //         this.sucursalSeleccionada = "";
       //     }
       // },
    },

    computed: {
        sicompleto() {
            let me = this;
            if (
              //  me.descripcion != "" &&
                me.TiposSeleccionado != "" &&
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

        //-----------------------------------permisos_R_W_S        
 listarPerimsoxyz() {
                //console.log(this.codventana);
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

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

        sucursalFiltro() {
            let me = this;
           // var url = "/ajustes-positivo/listarSucursal";
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

        ajustesNegativos() {
            let me = this;
            var url = "/ajustes-positivo/listarTipo";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayTipos = respuesta;
               
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
                    "/ajustes-positivo/listarProductoLineaIngreso?respuesta0=" +
                    this.sucursalSeleccionada+"&tipo="+me.tipoAccion;
            }
            if (me.tipoAccion == 2) {
                var url =
                    "/ajustes-positivo/listarProductoLineaIngreso?respuesta0=" +
                    this.id_codigo+"&tipo="+me.tipoAccion;
            
                }
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;

                    me.arrayProductoLineaIngreso = respuesta;

           
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        ListarretornarProductosIngreso() {
            let me = this;
            
            if (me.tipoAccion == 1) {
                var url ="/ajustes-positivo/retornarProductosIngreso?respuesta0=" + this.sucursalSeleccionada +
                    "&respuesta1=" + me.inputTextBuscarProductoIngreso+"&tipo="+me.tipoAccion;
            }

            if (me.tipoAccion == 2) {
               var url =
                   "/ajustes-positivo/retornarProductosIngreso?respuesta0=" + this.id_codigo +"&respuesta1=" +
                   me.inputTextBuscarProductoIngres+"&tipo="+me.tipoAccion;
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
        ListarretornarProductosIngresoCero() {
            let me = this;
            
            if (me.tipoAccion == 1) {
                var url ="/ajustes-positivo/retornarProductosIngresoCero?respuesta0=" + this.sucursalSeleccionada +
                    "&respuesta1=" + me.inputTextBuscarProductoIngresoCero+"&tipo="+me.tipoAccion;
            }

            if (me.tipoAccion == 2) {
               var url ="/ajustes-positivo/retornarProductosIngresoCero?respuesta0=" + this.id_codigo +"&respuesta1=" +
                   me.inputTextBuscarProductoIngresoCero+"&tipo="+me.tipoAccion;
           }
        
            axios 
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayRetornarProductosIngresoCero = respuesta;
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
                    me.isSubmitting=false;
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro para ajuste de positivo ";
                    me.ProductoLineaIngresoSeleccionado = 0;

                    me.id_codigo = me.sucursalSeleccionada;
                    me.cantidadProductoLineaIngreso = "";
                    me.TiposSeleccionado = 0;
                    me.cambiodeEstado = "";

                    me.codigo = "";
                    me.linea = "";
                    me.producto = "";
                    me.cantidadS = "";
                  //  me.descripcion = "";
                    me.fecha_ingreso='';
                    me.fecha_vencimiento='';
                    me.lote='';
                    me.cantidad='';
                    
                    me.id_sucursal = "";
                    me.id_producto = "";
                    me.id_ingreso = "";
                    me.classModal.openModal("registrar");
                    me.leyenda = "";
                    me.stock_ingreso="";
                    break;
                }
                case "actualizar": {
                    me.isSubmitting=false;
                    me.leyenda = data.leyenda;
                    me.id_codigo = data.cod;
                    me.tipoAccion = 2;
                    me.tituloModal = "Actualizacion para ajuste positivo  ";
                    me.codigo = data.codigo;
                    me.cantidadProductoLineaIngreso = data.cantidad;
                    me.linea = data.linea;
                    me.producto = data.nombreProd;
                    me.cantidadS = data.cantidad;
                //    me.descripcion = data.descripcion;
                    me.fecha_ingreso=data.fecha_ingreso;
                    me.fecha_vencimiento=data.fecha_vencimiento;
                    me.lote=data.lote;
                    me.cantidad=data.cantidad;
                    me.idAjusteNegativos = data.id;
                    me.id_sucursal = data.id_sucursal;
                    me.id_producto = data.cod;
                    me.id_ingreso = data.id_ingreso;
                    me.TiposSeleccionado =
                        data.id_tipo === null ? 0 : data.id_tipo;
                    me.ProductoLineaIngresoSeleccionado =
                        data.id_ingreso === null ? 0 : data.id_ingreso;

                    me.classModal.openModal("registrar");

                    break;
                }
                case "bucarProductoIngreso": {
                    //  me.tipoAccion=1;
                    (me.inputTextBuscarProductoIngreso = ""),
                        (me.arrayRetornarProductosIngreso = ""),
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
                    //  me.id_codigo=me.sucursalSeleccionada;
                    // me.id_codigo=data.cod;
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro para Positivo ";
                    me.ProductoLineaIngresoSeleccionado =
                        data.id_ingreso === null ? 0 : data.id_ingreso;

                    me.cantidadProductoLineaIngreso = "";
                    me.TiposSeleccionado = 0;

                    me.codigo = "";
                    me.linea = "";
                    me.producto = "";
                    me.cantidadS = "";
                   // me.descripcion = "";
                   me.fecha_ingreso='';
                    me.fecha_vencimiento='';
                    me.lote='';
                    me.cantidad='';
                    me.id_sucursal = "";
                    me.id_producto = "";
                    me.id_ingreso = "";
                    me.leyenda = "";
                    me.classModal.openModal("registrar");
                    break;
                }
                case "inputModal2": {
                    //  me.id_codigo=me.sucursalSeleccionada;
                    // me.id_codigo=data.cod;
                    // me.tipoAccion=2;
                    me.tituloModal = "Registro para ajuste de positivo ";
                    me.ProductoLineaIngresoSeleccionado =
                        data.id_ingreso === null ? 0 : data.id_ingreso;

                    me.cantidadProductoLineaIngreso = "";
                    me.TiposSeleccionado = 0;

                    me.codigo = "";
                    me.linea = "";
                    me.producto = "";
                    me.cantidadS = "";
                //    me.descripcion = "";
                    me.fecha = "";
                    me.id_sucursal = "";
                    me.id_producto = "";
                    me.id_ingreso = "";
                    me.leyenda = "";
                    me.classModal.openModal("registrar");
                    break;
                }
                case "inputModalCerrar": {
                    me.id_codigo = data.cod;
                    //   me.tipoAccion=2;
                    me.tituloModal =
                        "Actualizacion de ajuste positivo: ";
                    me.codigo = data.codigo;
                    me.cantidadProductoLineaIngreso = data.cantidad;
                    me.linea = data.linea;
                    me.producto = data.nombreProd;
                    me.cantidadS = data.catidad;
                  //  me.descripcion = data.descripcion;
                    me.fecha = data.fecha;
                    me.idAjusteNegativos = data.id;
                    me.id_sucursal = data.id_sucursal;
                    me.id_producto = data.cod;
                    me.id_ingreso = data.id_ingreso;
                    me.TiposSeleccionado =
                    data.id_tipo === null ? 0 : data.id_tipo;
                    me.ProductoLineaIngresoSeleccionado =
                        data.id_ingreso === null ? 0 : data.id_ingreso;
                    me.leyenda = data.nombre;
                }
                case "stockCero":{
                    (me.inputTextBuscarProductoIngresoCero = ""),
                        (me.arrayRetornarProductosIngresoCero = ""),
                        me.classModal.openModal("modalCero");
                    break;
                }
                case "registrarCeroX":{
                
                    me.productoX_name=data.leyenda;
                    me.tipoAccion = 1;
                    me.cantidadX=data.stock_ingreso;
                    me.fecha_vencimientoX=data.fecha_vencimiento;
                    me.registrosanitarioX=data.registro_sanitario;
                    me.loteX=data.lote;
                    me.TiposSeleccionado =data.id_tipoentrada === null ? 0 : data.id_tipoentrada;
                    me.id_ingresoX=data.id_ingreso;
                    me.codX=data.cod;
                    me.id_productoX=data.id_producto;                    
                    me.envaseX=data.envase;

                    me.leyenda = data.leyenda;
                    me.id_codigo = data.cod;                    
                    me.codigo = data.codigo_producto;                 
                    me.linea = data.nombre_linea;
                    me.producto = data.nombre;                   
                    me.fecha_vencimiento=data.fecha_vencimiento;          
                    me.id_sucursal = data.id_sucursal;
                    me.id_producto = data.cod;         
                    
                    me.tituloModal = "Actualizacion de producto ";
                    me.classModal.openModal("registrarCero");
                    break;
                }
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.isSubmitting=false;
                me.classModal.closeModal(accion);
                me.ProductoLineaIngresoSeleccionado = 0;
                me.TiposSeleccionado = 0;
                me.cantidadProductoLineaIngreso = "";
                me.tipoAccion = 1;
                me.stock_ingreso ="";
                me.codigo = "";
                me.linea = "";
                (me.producto = ""), (me.cantidadS = "");
               // me.descripcion = "";
               me.fecha_ingreso='';
                    me.fecha_vencimiento='';
                    me.lote='';
                    me.cantidad='';
                me.id_sucursal = "";
                me.id_ingreso = "";
                me.id_producto = "";
                me.leyenda = "";
            } else {
                if (accion == "registrarCeroX") {
                    me.productoX_name="";
                    me.tipoAccion = 1;
                    me.cantidadX="";
                    me.fecha_vencimientoX="";
                    me.registrosanitarioX="";
                    me.loteX="";
                    me.TiposSeleccionado =0;
                    me.id_ingresoX="";
                    me.codX="";
                    me.id_productoX="";                    
                    me.envaseX="";

                    me.leyenda = "";
                    me.id_codigo = "";                    
                    me.codigo = "";                 
                    me.linea = "";
                    me.producto = "";                   
                    me.fecha_vencimiento="";          
                    me.id_sucursal = "";
                    me.id_producto = ""; 
                    me.classModal.closeModal(accion);
                } else {
                    me.classModal.closeModal(accion);
                //me.idproductoselected = me.idproductoselected;
            //    me.classModal.openModal("registrar"); 
                }
                
            }
        },

        registrorAjusteNegativo() {
            let me = this;
          
            let suma = me.cantidadProductoLineaIngreso + me.cantidadS;

            if (
                me.codigo === "" ||
                me.linea === "" ||
                me.producto === "" ||
                me.cantidadS === "" ||
                me.TiposSeleccionado === ""
             //   me.descripcion === "" ||
     
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
                axios.post("/ajustes-positivo/registrar", {
                        'id_tipo': me.TiposSeleccionado,
                        'id_producto_linea': me.ProductoLineaIngresoSeleccionado,
                        'codigo': me.codigo,
                        'linea': me.linea,
                        'producto': me.producto,
                        'cantidad': me.cantidadS,
                        'stock':suma,                        
                        'fecha_ingreso':me.fecha_ingreso, 
                        'fecha_vencimiento':me.fecha_ingreso,
                     //   descripcion: me.descripcion,
                     'lote':me.lote, 
                        'activo': 1,
                        'id_sucursal': me.id_sucursal,
                        'id_producto': me.id_producto,
                        'cod': me.sucursalSeleccionada,
                        'id_ingreso': me.id_ingreso,
                        'leyenda': me.leyenda,
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        );

                        me.listarAjusteNegativos();
                        me.sucursalFiltro();
                    })
                   // .catch(function (error) {
                   //     error401(error);
                   //     console.log(error);
                   // });
                   .catch(function (error) {                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
            }
        },
        actualizarAjusteNegativo() {
            let me = this;
            let suma = me.cantidadProductoLineaIngreso - me.cantidadS;
            axios
                .put("/ajustes-positivo/actualizar", {
                    id: me.idAjusteNegativos,
                    id_tipo: me.TiposSeleccionado,                 
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
                //.catch(function (error) {
                //    error401(error);
                //});
                .catch(function (error) {                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            });
            me.cerrarModal("registrar");
        },
        //para listar db alm__ajuste_negativos
        listarAjusteNegativos(page) {
            let me = this;
 
           // if (me.sucursalSeleccionada == 0) {
           //     var url =     "/ajustes-positivo?page=" + page + "&buscar=" + me.buscar;
               // me.sucursalSeleccionada = 0;
           // } else {
           //     var url =
           //         "/ajustes-positivo?page="+page+"&buscar=" + me.sucursalSeleccionada;
               // me.buscar = "";
           // }
            var url ="/ajustes-positivo?page="+page+"&buscar=" +me.buscar+"&buscarAlmTdn=" +me.sucursalSeleccionada;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayAjusteNegativos =
                        respuesta.query_ajuste_negativos.data;
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
       
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        eliminarAjusteNegativos(idAjusteNegativos) {
            let me = this;          
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
                            .put("/ajustes-positivo/desactivar", {
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
                       //     .catch(function (error) {
                       //         error401(error);
                       //         console.log(error);
                       //     });
                       .catch(function (error) {                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
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
        actualizarProductoCeroTdaAlm(){
                let me =this;
                
                axios.put('/ajustes-positivo/actualizarTdaAlm',{
                    'cod':me.codX,
                    'id':me.id_ingresoX,
                    'id_prod_producto':me.id_productoX,
                    'envase':me.envaseX,
                    'idtienda':me.id_alm_tda,
                    'cantidad':me.cantidadX,
                    'id_tipo_entrada':me.TiposSeleccionado,
                    'fecha_vencimiento':me.fecha_vencimientoX,
                    'lote':me.loteX,
                    'registro_sanitario':me.registrosanitarioX,

                    'leyenda': me.leyenda,
                    'id_codigo': me.id_codigo,                    
                    'codigo' : me.codigo,                 
                    'linea': me.linea,
                    'producto' : me.producto,                   
                    'fecha_vencimiento':me.fecha_vencimiento,          
                    'id_sucursal' : me.id_sucursal,
                    'id_producto' : me.id_producto


                }).then(function (response) {
                    me.cerrarModal("registrarCero");
                    me.cerrarModal("modalCero");
                     me.cerrarModal("registrar");
                    Swal.fire('Actualizado Correctamente')
                  
                    me.listarAjusteNegativos();
                        me.sucursalFiltro();
                }) .catch(function (error) {                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            });
               
            },
        activarAjusteNegativos(idAjusteNegativos) {
            let me = this;
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
                            .put("/ajustes-positivo/activar", {
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
                           // .catch(function (error) {
                           //     error401(error);
                           //     console.log(error);
                           // });
            .catch(function (error) {                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"       );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
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
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();
         //     this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
        this.classModal = new _pl.Modals();
        this.classModal.addModal("registrar");
        this.listarAjusteNegativos(1);
        this.ajustesNegativos();
        this.cambiodeEstado();
        this.sucursalFiltro();
        this.classModal.addModal("staticBackdrop");
        this.classModal.addModal("modalCero");
        this.classModal.addModal("registrarCero");
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
