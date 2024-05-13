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
                    <div class="row">
                        <div class="col-md-6">
                            <i class="fa fa-align-justify"></i> Registro de Productos
                            <button type="button" v-if="puedeCrear==1" class="btn btn-secondary" @click="abrirModal('registrar')" :disabled="idrubrofiltro == 0">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="col-md-6" id="botonos-infoprisecter">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#envase-primario" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Envase Primario</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#envase-secundario" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Envase Secundario</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#envase-terciario" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Envase Terciario</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <div class="input-group">
                                <label class="form-control-label" style="margin-top:auto;">Seleccione Rubro:</label> 
                                <select v-model="idrubrofiltro" @change="listarProducto()" class="form-control" style="margin-left:8px;">
                                    <option value="0">Seleccionar</option>
                                    <option v-for="rubro in rubros" :key="rubro.id" :value="rubro.id" v-text="rubro.nombre"></option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-6" v-if="idrubrofiltro != 0">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarProducto(1)">
                                <button type="submit" class="btn btn-primary" @click="listarProducto(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="envase-primario" role="tabpanel" aria-labelledby="pills-home-tab">
                            <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-1">Opciones</th>
                                    <th class="col-md-1">Linea</th>
                                    <th class="col-md-2">Codigo</th>
                                    <th class="col-md-2">Nombre</th>
                                    <!--th>Presentacion</th-->
                                    <th class="col-md-1">Tiempo Pedido</th>
                                    <th class="col-md-1">Precio Lista</th>
                                    <th class="col-md-1">Precio Venta</th>
                                    <th class="col-md-1">Metodo</th>
                                    <th class="col-md-1">Codigo Internacional</th>
                                    <th class="col-md-1">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in arrayProducto" :key="producto.id">
                                    <td class="col-md-1">
                                        <div  class="d-flex justify-content-start">
                                            <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',producto)" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                            </div>
                                            <div v-else>
                                                <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                                <i class="icon-pencil"></i>
                                            </button> 
                                            </div>
                                            <div v-if="puedeActivar==1">
                                                <button v-if="producto.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarProducto(producto.id)" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-info btn-sm" @click="activarProducto(producto.id)" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                        </button>                                        
                                            </div>
                                            <div v-else>
                                                <button v-if="producto.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                        </button>
                                            </div>
                                            <img v-if="producto.foto" :src="'imgproductos/'+ producto.foto.substring(9)" class="rounded fotosociomini" style="margin-right: 5px;">
                                        <img v-else src="img/avatars/noimagen.png"  class="rounded fotosociomini" style="margin-right: 5px;">
                                        </div>        
                                        
                                        
                                    </td>
                                    <td class="col-md-1">{{ producto.nomlinea }}</td>
                                    <td v-text="producto.codprod" class="col-md-2"></td>
                                    <td id="nombre-prducto" class="col-md-2">
                                        <div>
                                            {{ producto.nomprod }} 
                                            <div v-for="dispenser in dispensers"> <div v-if="dispenser.id == producto.idenvaseprimario"> {{ dispenser.nombre }} X {{ producto.cantidadprimario }} </div></div>
                                            <div v-for="formafar in formafarms"> <div v-if="formafar.id == producto.idformafarmaceuticaprimario">&nbsp;{{ formafar.nombre }} </div></div>
                                        </div> <br><br>
                                    </td>
                                    <!--td>{{ producto.codlinea}} - {{producto.cantidadprimario}} <br /> {{producto.idformafarmaceuticaprimario }}</td-->
                                    <td class="col-md-1">{{ producto.tiempopedidoprimario }} meses</td>
                                    <td class="col-md-1" v-text="producto.preciolistaprimario"></td>
                                    <td class="col-md-1" v-text="producto.precioventaprimario"></td>
                                    
                                    <td class="col-md-1" v-text="producto.metodoabcprimario"></td>
                                    <td class="col-md-1" v-text="producto.codigointernacional"></td>
                                    <td class="col-md-1">
                                        <div v-if="producto.activo==1">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-warning">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="tab-pane fade" id="envase-secundario" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <table class="table table-bordered table-striped table-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">Opciones</th>
                                        <th class="col-md-1">Linea</th>
                                        <th class="col-md-1">Codigo</th>
                                        <th class="col-md-2">Nombre</th>
                                        <!--th>Presentacion</th-->
                                        <th class="col-md-2">Tiempo Pedido</th>
                                        <th class="col-md-1">Precio Lista</th>
                                        <th class="col-md-1">Precio Venta</th>
                                        <th class="col-md-1">Metodo</th>
                                        <th class="col-md-1">Codigo Internacional</th>
                                        <th class="col-md-1">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in arrayProducto" :key="producto.id">
                                        <td class="col-md-1">
                                            <div  class="d-flex justify-content-start">
                                                <div  v-if="puedeEditar==1">
                                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',producto)" style="margin-right: 5px;">
                                                <i class="icon-pencil"></i>
                                                 </button> 
                                                </div>
                                                <div v-else>
                                                    <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                                <i class="icon-pencil"></i>
                                                </button> 
                                                </div>
                                                <div v-if="puedeActivar==1">
                                                    <button v-if="producto.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarProducto(producto.id)" style="margin-right: 5px;">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activarProducto(producto.id)" style="margin-right: 5px;">
                                                <i class="icon-check"></i>
                                            </button>
                                                </div>
                                                 <div v-else="">
                                                    <button v-if="producto.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                                <i class="icon-check"></i>
                                            </button>
                                                 </div>   
                                                 <img v-if="producto.foto" :src="'imgproductos/'+ producto.foto.substring(9)" class="rounded fotosociomini">
                                            <img v-else src="img/avatars/noimagen.png"  class="rounded fotosociomini" >
                                            </div>  
                                        </td>
                                        <td class="col-md-1">{{ producto.nomlinea }}</td>
                                        <td class="col-md-1" v-text="producto.codprod"></td>
                                        <td class="col-md-2" id="nombre-prducto">
                                            <div>
                                                {{ producto.nomprod }} 
                                                <div v-for="dispenser in dispensers"> <div v-if="dispenser.id == producto.idenvasesecundario"> {{ dispenser.nombre }} X {{ producto.cantidadsecundario }} </div></div>
                                                <div v-for="formafar in formafarms"> <div v-if="formafar.id == producto.idformafarmaceuticasecundario">&nbsp;{{ formafar.nombre }} </div></div>
                                            </div> <br><br>
                                        </td>
                                        <!--td>{{ producto.codlinea}} - {{producto.cantidadprimario}} <br /> {{producto.idformafarmaceuticaprimario }}</td-->
                                        <td class="col-md-2">{{ producto.tiempopedidosecundario }} meses</td>
                                        <td class="col-md-1" v-text="producto.preciolistasecundario"></td>
                                        <td class="col-md-1" v-text="producto.precioventasecundario"></td>
                                        
                                        <td class="col-md-1" v-text="producto.metodoabcsecundario"></td>
                                        <td class="col-md-1" v-text="producto.codigointernacional"></td>
                                        <td>
                                            <div v-if="producto.activo==1">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-warning">Desactivado</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="envase-terciario" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <table class="table table-bordered table-striped table-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">Opciones</th>
                                        <th class="col-md-1">Linea</th>
                                        <th class="col-md-1">Codigo</th>
                                        <th class="col-md-2">Nombre</th>
                                        <!--th>Presentacion</th-->
                                        <th class="col-md-2">Tiempo Pedido</th>
                                        <th class="col-md-1">Precio Lista</th>
                                        <th class="col-md-1">Precio Venta</th>
                                        <th class="col-md-1">Metodo</th>
                                        <th class="col-md-1">Codigo Internacional</th>
                                        <th class="col-md-1">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in arrayProducto" :key="producto.id">
                                        <td class="col-md-1">
                                            <div  class="d-flex justify-content-start">
                                                <div  v-if="puedeEditar==1">
                                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',producto)" style="margin-right: 5px;">
                                                    <i class="icon-pencil"></i>
                                                    </button> 
                                                </div>
                                                <div v-else>
                                                    <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                                    <i class="icon-pencil"></i>
                                                    </button>
                                                </div>
                                                <div v-if="puedeActivar==1">
                                                    <button v-if="producto.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarProducto(producto.id)" style="margin-right: 5px;">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activarProducto(producto.id)" style="margin-right: 5px;">
                                                <i class="icon-check"></i>
                                            </button>
                                                </div>
                                                <div v-else>
                                                    <button v-if="producto.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                                <i class="icon-check"></i>
                                            </button>
                                                </div>  
                                                <img v-if="producto.foto" :src="'imgproductos/'+ producto.foto.substring(9)" class="rounded fotosociomini">
                                            <img v-else src="img/avatars/noimagen.png"  class="rounded fotosociomini" > 
                                            </div>       
                                            
                                           
                                            
                                        </td>
                                        <td class="col-md-2">{{ producto.nomlinea }}</td>
                                        <td class="col-md-2" v-text="producto.codprod"></td>
                                        <td class="col-md-1" id="nombre-prducto">
                                            <div>
                                                {{ producto.nomprod }}
                                                <div v-for="dispenser in dispensers"> <div v-if="dispenser.id == producto.idenvaseterciario"> {{ dispenser.nombre }} X {{ producto.cantidadterciario }} </div></div>
                                                <div v-for="formafar in formafarms"> <div v-if="formafar.id == producto.idformafarmaceuticaterciario">&nbsp;{{ formafar.nombre }} </div></div>
                                            </div> <br><br>
                                        </td>
                                        <!--td>{{ producto.codlinea}} - {{producto.cantidadprimario}} <br /> {{producto.idformafarmaceuticaprimario }}</td-->
                                        <td class="col-md-1">{{ producto.tiempopedidoterciario }} meses</td>
                                        <td class="col-md-1" v-text="producto.preciolistaterciario"></td>
                                        <td class="col-md-1" v-text="producto.precioventaterciario"></td>
                                        
                                        <td class="col-md-1" v-text="producto.metodoabcterciario"></td>
                                        <td class="col-md-1" v-text="producto.codigointernacional"></td>
                                        <td class="col-md-1">
                                            <div v-if="producto.activo==1">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-warning">Desactivado</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>

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
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>


        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="registrar" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('registrar')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-6" v-if="tipoAccion == 2">
                                <strong>Rubro:</strong>
                                <select v-model="idrubroselected" @change="listarLinea" class="form-control">
                                    <option value="0">Seleccionar</option>
                                    <option v-for="rubro in rubros" :key="rubro.id" :value="rubro.id" v-text="rubro.nombre"></option>
                                </select>
                                <span class="error" v-if="idrubroselected==0">Debe Seleccionar un rubro</span>
                            </div>
                            <div class="form-group col-sm-6">
                                <strong>Linea:</strong>
                                <select v-model="idlineaselected" @change="getCodigoLinea" class="form-control">
                                    <option value="0">Seleccionar</option>
                                    <option v-for="linea in lineas" :key="linea.id" :value="linea.id" v-text="linea.cod"></option>
                                </select>
                                <span class="error" v-if="idlineaselected==0">Debe Seleccionar la Linea</span>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 30px;">
                            <div class="form-group col-sm-6">
                                <strong>Producto:</strong>
                                <input type="text" class="form-control" v-model="nombre" placeholder="Nombre del Producto">
                                <span class="error" v-if="nombre.length==0">Debe Ingresar Nombre del Producto</span>
                            </div>
                        </div>

                            <!-- tab para los envases del producto -->
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-envase-primario-tab" data-toggle="pill" href="#pills-envase-primario" role="tab" aria-controls="pills-home" aria-selected="true">Envase Primario</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-envase-secundario-tab" data-toggle="pill" href="#pills-envase-secundario" role="tab" aria-controls="pills-profile" aria-selected="false">Envase Secundario</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-envase-terciario-tab" data-toggle="pill" href="#pills-envase-terciario" role="tab" aria-controls="pills-contact" aria-selected="false">Envase Terciario</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-envase-primario" role="tabpanel" aria-labelledby="pills-envase-primario-tab">
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Envase Primario:</strong>
                                                <select v-model="iddispenserselectedprimario" class="form-control">
                                                    <option value="0">Seleccionar</option>
                                                    <option v-for="dispenser in dispensers" :key="dispenser.id" :value="dispenser.id" v-text="dispenser.nombre"></option>
                                                </select>
                                                <span class="error" v-if="iddispenserselectedprimario==0">Debe Seleccionar un Envase</span>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Cantidad:</strong>
                                                <input type="text" class="form-control" id="primario" v-model="cantidadprimario" style="text-align:right" placeholder="0" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 65 && event.charCode <= 90))">
                                                <span class="error" v-if="cantidadprimario==''">Debe ingresar Cantidad</span>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Forma o Unid. de Medida:</strong>
                                                <div class="row">
                                                    <div class="col-sm-2"><input type="checkbox" v-model="checkformafarmaceuticaprimario"></div>
                                                    <div class="col-sm-10" v-if="checkformafarmaceuticaprimario">
                                                        <select v-model="idformafarmselectedprimario" class="form-control">
                                                            <option value="0">Seleccionar</option>
                                                            <option v-for="formafarm in formafarms" :key="formafarm.id" :value="formafarm.id" v-text="formafarm.nombre"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <span class="error" v-if="checkformafarmaceuticaprimario && idformafarmselectedprimario==0">Debe Seleccionar la Forma Farmaceutica</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Lista:</strong>
                                                <input type="text" class="form-control" min="0" id="preciolistaprimario" v-model="preciolistaprimario" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Venta:</strong>
                                                <input type="text" id="precioventaprimario" min="0" class="form-control" v-model="precioventaprimario" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Ciclo de Pedido:</strong>
                                                <select v-model="tiempopedidoselectedprimario" class="form-control">
                                                    <option value="0">Seleccionar</option>
                                                    <option v-for="tiempo in tiempopedido" :key="tiempo.id" :value="tiempo.id" v-text="tiempo.dato"></option>
                                                </select>
                                                <span class="error" v-if="tiempopedidoselectedprimario==0">Debe seleccionar el tiempo de pedido</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Clasificación ABC:</strong>
                                                <select v-model="metodoselectedprimario" class="form-control">
                                                    <option v-for="metodo in arrayMetodo" :key="metodo" :value="metodo" v-text="metodo"></option>
                                                </select>
                                            </div>
                                            <div class="form-check col-sm-5 mt-4 pl-5">
                                                <div>
                                                    <input class="form-check-input" type="checkbox" v-model="tiendaprimario" id="defaultChecktiendaprimario">
                                                    <label class="form-check-label" for="defaultChecktiendaprimario">
                                                        Tienda <span class="error" v-if="!seleccinoTiendaAlmacenPrimario">(Debe seleccionar Tienda o Almacen)</span>
                                                    </label> 
                                                </div>
                                                <div>
                                                    <input class="form-check-input" type="checkbox" v-model="almacenprimario" id="defaultCheck1almacen">
                                                    <label class="form-check-label" for="defaultCheck1almacen">
                                                        Almacen <span class="error" v-if="!seleccinoTiendaAlmacenPrimario">(Debe seleccionar Tienda o Almacen)</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-envase-secundario" role="tabpanel" aria-labelledby="pills-envase-secundario-tab">
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Envase Secundario:</strong>
                                                <select v-model="iddispenserselectedsecundario" class="form-control">
                                                    <option value="0">Seleccionar</option>
                                                    <option v-for="dispenser in dispensers" :key="dispenser.id" :value="dispenser.id" v-text="dispenser.nombre"></option>
                                                </select>
                                                <!-- <span class="error" v-if="iddispenserselectedsecundario==0">Debe Seleccionar un Envase</span> -->
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Cantidad:</strong>
                                                <input type="text" class="form-control" id="secundario" v-model="cantidadsecundario" style="text-align:right" placeholder="0" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 65 && event.charCode <= 90))">
                                                <!-- <span class="error" v-if="cantidadsecundario==''">Debe ingresar Cantidad</span> -->
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Forma o Unid. de Medida</strong>
                                                <div class="row">
                                                    <div class="col-sm-2"><input type="checkbox" v-model="checkformafarmaceuticasecundario"></div>
                                                    <div class="col-sm-10" v-if="checkformafarmaceuticasecundario">
                                                        <select v-model="idformafarmselectedsecundario" class="form-control">
                                                            <option value="0">Seleccionar</option>
                                                            <option v-for="formafarm in formafarms" :key="formafarm.id" :value="formafarm.id" v-text="formafarm.nombre"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <span class="error" v-if="checkformafarmaceuticasecundario && idformafarmselectedsecundario==0">Debe Seleccionar la Forma Farmaceutica</span> -->
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Lista:</strong>
                                                <input type="text" class="form-control" id="preciolistasecundario" min="0" v-model="preciolistasecundario" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Venta:</strong>
                                                <input type="text" id="precioventasecundario" min="0" class="form-control" v-model="precioventasecundario" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Ciclo de Pedido:</strong>
                                                <select v-model="tiempopedidoselectedsecundario" class="form-control">
                                                    <option value="0">Seleccionar</option>
                                                    <option v-for="tiempo in tiempopedido" :key="tiempo.id" :value="tiempo.id" v-text="tiempo.dato"></option>
                                                </select>
                                                <!-- <span class="error" v-if="tiempopedidoselectedsecundario==0">Debe seleccionar un tiempo de pedido</span> -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Clasificación ABC:</strong>
                                                <select v-model="metodoselectedsecundario" class="form-control">
                                                    <option v-for="metodo in arrayMetodo" :key="metodo" :value="metodo" v-text="metodo"></option>
                                                </select>
                                            </div>
                                            <div class="form-check col-sm-5 mt-4 pl-5">
                                                <div>
                                                    <input class="form-check-input" type="checkbox" v-model="tiendasecundario" id="defaultCheck1secundariotiendasecundario">
                                                    <label class="form-check-label" for="defaultCheck1secundariotiendasecundario">
                                                        Tienda <span class="error" v-if="!seleccinoTiendaAlmacenSecundario">(Debe seleccionar Tienda o Almacen)</span>
                                                    </label>
                                                </div>
                                                <div>
                                                    <input class="form-check-input" type="checkbox" v-model="almacensecundario" id="defaultCheck1almacensecundario">
                                                    <label class="form-check-label" for="defaultCheck1almacensecundario">
                                                        Almacen <span class="error" v-if="!seleccinoTiendaAlmacenSecundario">(Debe seleccionar Tienda o Almacen)</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-envase-terciario" role="tabpanel" aria-labelledby="pills-envase-terciario-tab">
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Envase Terceario:</strong>
                                                <select v-model="iddispenserselectedterciario" class="form-control">
                                                    <option value="0">Seleccionar</option>
                                                    <option v-for="dispenser in dispensers" :key="dispenser.id" :value="dispenser.id" v-text="dispenser.nombre"></option>
                                                </select>
                                                <!-- <span class="error" v-if="iddispenserselectedterciario==0">Debe Seleccionar un Envase</span> -->
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Cantidad:</strong>
                                                <input type="text" class="form-control" id="terciario" v-model="cantidadterciario" style="text-align:right" placeholder="0" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 65 && event.charCode <= 90))">
                                                <!-- <span class="error" v-if="cantidadterciario==''">Debe ingresar Cantidad</span> -->
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Forma o Unid. de Medida</strong>
                                                <div class="row">
                                                    <div class="col-sm-2"><input type="checkbox" v-model="checkformafarmaceuticaterciario"></div>
                                                    <div class="col-sm-10" v-if="checkformafarmaceuticaterciario">
                                                        <select v-model="idformafarmselectedterciario" class="form-control">
                                                            <option value="0">Seleccionar</option>
                                                            <option v-for="formafarm in formafarms" :key="formafarm.id" :value="formafarm.id" v-text="formafarm.nombre"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <span class="error" v-if="checkformafarmaceuticaterciario && idformafarmselectedterciario==0">Debe Seleccionar la Forma Farmaceutica</span> -->
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Lista:</strong>
                                                <input type="text" class="form-control" id="preciolistaterciario" min="0" v-model="preciolistaterciario" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Venta:</strong>
                                                <input type="text" id="precioventaterciario" min="0" class="form-control" v-model="precioventaterciario" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Ciclo de Pedido:</strong>
                                                <select v-model="tiempopedidoselectedterciario" class="form-control">
                                                    <option value="0">Seleccionar</option>
                                                    <option v-for="tiempo in tiempopedido" :key="tiempo.id" :value="tiempo.id" v-text="tiempo.dato"></option>
                                                </select>
                                                <!-- <span class="error" v-if="tiempopedidoselectedterciario==0">Debe seleccionar un tiempo de pedido</span> -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Clasificación ABC:</strong>
                                                <select v-model="metodoselectedterciario" class="form-control">
                                                    <option v-for="metodo in arrayMetodo" :key="metodo" :value="metodo" v-text="metodo"></option>
                                                </select>
                                            </div>
                                            <div class="form-check col-sm-5 mt-4 pl-5">
                                                <div>
                                                    <input class="form-check-input" type="checkbox" v-model="tiendaterciario" id="defaultCheck1tiendaterciario">
                                                    <label class="form-check-label" for="defaultCheck1tiendaterciario">
                                                        Tienda <span class="error" v-if="!seleccinoTiendaAlmacenTerciario">(Debe seleccionar Tienda o Almacen)</span>
                                                    </label>
                                                </div>
                                                <div>
                                                    <input class="form-check-input" type="checkbox" v-model="almacenterciario" id="defaultCheck1almacenterciario">
                                                    <label class="form-check-label" for="defaultCheck1almacenterciario">
                                                        Almacen <span class="error" v-if="!seleccinoTiendaAlmacenTerciario">(Debe seleccionar Tienda o Almacen)</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- Fin tab para los envases del producto -->
                        
                        <div class="row" style="margin-top: 50px;">
                            <div class="form-group col-sm-4">
                                <strong>Categoria:</strong>
                                <select v-model="idcategoriaselected" class="form-control">
                                    <option value="0">Seleccionar</option>
                                    <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                </select>
                                <!--<Ajaxselect  v-if="clearSelected3"
                                    ruta="/categoria/selectcategoria?buscar=" @found="categorias" @cleaning="cleancategorias"
                                    resp_ruta="categorias"
                                    labels="nombre"
                                    placeholder="Ingrese Texto..." 
                                    idtabla="id"
                                    :id="idcategoriaselected"
                                    :clearable='true'>
                                </Ajaxselect>-->
                                <span class="error" v-if="idcategoriaselected==0">Debe Seleccionar la Categoria</span>
                            </div>

                            <div class="form-group col-sm-5">
                                <strong>Codigo Internacional:</strong>
                                <input type="text" class="form-control" v-model="codigointernacional" placeholder="Introduzca Codigo Internacional" onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45 || event.charCode == 13 || event.charCode == 32 )">
                                <span class="error" v-if="codigointernacional==''">Debe seleccionar el tiempo de pedido</span>
                            </div>
                            
                            <div class="form-check col-sm-3 mt-4 pl-5">
                                <input class="form-check-input" type="checkbox" v-model="mostrardetalles" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Vademecum
                                </label>
                            </div>
                        </div>
                        
                        <div  v-if="mostrardetalles==1">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <strong>Indicaciones:</strong>
                                    <textarea class="form-control" maxlength="255" style="resize: none;" v-model="indicaciones" placeholder="Ninguno"></textarea>
                                </div>
                            
                                <div class="form-group col-sm-6">
                                    <strong>Dosificacion:</strong>
                                    <textarea class="form-control" maxlength="255" style="resize: none;" v-model="dosificacion" placeholder="Ninguno"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <strong>Principio Activo:</strong>
                                    <textarea class="form-control" maxlength="255" style="resize: none;" v-model="principio" placeholder="Ninguno"></textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <strong>Accion Terapeutica:</strong>
                                    <textarea class="form-control" maxlength="255" style="resize: none;" v-model="accion" placeholder="Ninguno"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" v-if="clearInputFile">
                            <label for="">Imagen:&nbsp; &nbsp;</label>
                            <input class="form-control rounded" type="file" @change="subirfoto" :v-model="foto" accept="image/*" id="imgproducto">    
                        </div>
                        <figure>
                            <img width="100" height="100" :src="imagen" alt="">
                        </figure>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProducto()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProducto()" :disabled="!sicompleto">Actualizar</button>
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
import Swal from 'sweetalert2';
import { error401 } from '../../errores';
//import Vue from 'vue'
import VueNumeric from 'vue-numeric';
import QrcodeVue from 'qrcode.vue';

//Vue.use(VueNumeric)
//Vue.use(VeeValidate);
    export default {
        //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
        data(){
            return{
                pagination:{
                    'total' :0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0
                },
                offset:3,
                
                arrayProducto:[],
                tituloModal:'',
                tipoAccion:1,
                //iddispenser:'',
                buscar:'',
                idlineas:[],
                //idlineaselected:0,
                clearSelected:1,
                
                //cantidad:0,
                nombre:'',
                clearSelected2:1,
                clearSelected1:1,
                //iddispenserselected:0,
                iddispenser:[],
                //idformafarmselected:0,
                idformafarm:[],
                //preciolista:0,
                //precioventa:0,
                tiempopedido:[{'id':1,'dato':'1 mes'},
                                {'id':3,'dato':'3 meses'},
                                {'id':6,'dato':'6 meses'},
                                {'id':12,'dato':'12 meses'}],
                //tiempopedidoselected:0,
                //indicaciones:'',
                //dosificacion:'',
                //principio:'',
                accion:'',
                idproducto:'',
                //image:'',
                //imagen:'',
                metodoselected:'A',
                arrayMetodo:['A','B','C'],
                idcategoria:[],
                //idcategoriaselected:0,
                clearSelected3:1,
                //mostrardetalles:0,
                clearInputFile:1,
                foto:'',
                imagenminiatura:'',

                ////// a qui comiienza las nuevas variables
                idlineaselected:0,
                nombre:'',
                idrubroselected:0,
                idrubrofiltro:0,
                iddispenserselectedprimario:0,
                cantidadprimario:0,
                idformafarmselectedprimario:0,
                checkformafarmaceuticaprimario:false,
                preciolistaprimario:0,
                precioventaprimario:0,
                tiempopedidoselectedprimario:0,
                metodoselectedprimario:0,
                tiendaprimario:0,
                almacenprimario:0,
                iddispenserselectedsecundario:0,
                cantidadsecundario:0,
                idformafarmselectedsecundario:0,
                checkformafarmaceuticasecundario:false,
                preciolistasecundario:0,
                precioventasecundario:0,
                tiempopedidoselectedsecundario:0,
                metodoselectedsecundario:0,
                tiendasecundario:0,
                almacensecundario:0,
                iddispenserselectedterciario:0,
                cantidadterciario:0,
                idformafarmselectedterciario:0,
                checkformafarmaceuticaterciario:false,
                preciolistaterciario:0,
                precioventaterciario:0,
                tiempopedidoselectedterciario:0,
                metodoselectedterciario:0,
                tiendaterciario:0,
                almacenterciario:0,
                idcategoriaselected:0,
                codigointernacional:'',
                mostrardetalles:0,
                indicaciones:'',
                dosificacion:'',
                principio:'',
                accion:'',
                foto:'',
                codigolinea:'L',

                //////qrcode
                value: 'https://example.com',
                size: 300,
                lineas:[],
                rubros:[],
                dispensers:[],
                formafarms:[],
                categorias:[],
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
            }

        },
        components: {
            QrcodeVue,
        },
        computed:{

            seleccinoTiendaAlmacenPrimario()
            {
                if( (this.tiendaprimario == false && this.almacenprimario == true) ||
                    (this.tiendaprimario == true && this.almacenprimario == false))
                {
                    return true;
                }else {
                    return false;
                }   
            },

            seleccinoTiendaAlmacenSecundario()
            {
                if( (this.tiendasecundario == false && this.almacensecundario == true) ||
                    (this.tiendasecundario == true && this.almacensecundario == false))
                {
                    return true;
                }else {
                    return false;
                }   
            },

            seleccinoTiendaAlmacenTerciario()
            {
                if( (this.tiendaterciario == false && this.almacenterciario == true) ||
                    (this.tiendaterciario == true && this.almacenterciario == false))
                {
                    return true;
                }else {
                    return false;
                }   
            },
            
            imagen(){
                return this.imagenminiatura;
            },

            sicompleto(){
                let me=this;
                if (me.tipoAccion == 2) {
                    if (
                            me.idrubroselected == 0 ||
                            me.idlineaselected == 0 || 
                            me.nombre == '' || 

                            me.iddispenserselectedprimario == 0 || 
                            me.cantidadprimario == 0 || 
                            (me.checkformafarmaceuticaprimario == true && me.idformafarmselectedprimario == 0) ||
                            me.tiempopedidoselectedprimario==0 ||
                            
                            // me.iddispenserselectedsecundario == 0 || 
                            // me.cantidadsecundario == 0 || 
                            // (me.checkformafarmaceuticasecundario == true && me.idformafarmselectedsecundario == 0) ||
                            // me.tiempopedidoselectedsecundario==0 ||
                            
                            me.idcategoriaselected == 0 ||
                            me.codigointernacional == '' ||
                            !me.seleccinoTiendaAlmacenPrimario // || !me.seleccinoTiendaAlmacenSecundario
                        )
                        return false;
                    else
                        return true;   
                } else {
                    if (
                            me.idlineaselected == 0 || 
                            me.nombre == '' || 

                            me.iddispenserselectedprimario == 0 || 
                            me.cantidadprimario == 0 || 
                            (me.checkformafarmaceuticaprimario == true && me.idformafarmselectedprimario == 0) ||
                            me.tiempopedidoselectedprimario==0 ||
                            
                            // me.iddispenserselectedsecundario == 0 || 
                            // me.cantidadsecundario == 0 || 
                            // (me.checkformafarmaceuticasecundario == true && me.idformafarmselectedsecundario == 0) ||
                            // me.tiempopedidoselectedsecundario==0 ||
                            
                            me.idcategoriaselected == 0 ||
                            me.codigointernacional == '' ||
                            !me.seleccinoTiendaAlmacenPrimario //|| !me.seleccinoTiendaAlmacenSecundario
                        )
                        return false;
                    else
                        return true;
                }
            },

            isActived:function(){
                return this.pagination.current_page;
            },

            pagesNumber:function(){
                if(!this.pagination.to){
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from<1){
                    from=1;
                }
                var to = from +(this.offset * 2);
                if(to>= this.pagination.last_page){
                    to=this.pagination.last_page;
                }
                var pagesArray =[];
                while(from<=to){
                    pagesArray.push(from);
                    from++
                }
                return pagesArray;
            },


        },
        methods :{

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
              teresPermitidosCantidad(ex){
                let me=this;
                console.log(ex.keyCode +'--->'+ex.key);
                if( ex.keyCode==32  || ex.keyCode==8  || (ex.keyCode >= 48 && ex.keyCode <= 57 ) || (ex.keyCode >= 97 && ex.keyCode <= 122 ) || (ex.keyCode >= 65 && ex.keyCode <= 90 ) )
                {
                    if(ex.currentTarget.id == 'primario'){
                        me.cantidadprimario = me.cantidadprimario+ex.key;
                    }else if(ex.currentTarget.id == 'secundario'){
                        me.cantidadsecundario = me.cantidadsecundario+ex.key;
                    }else if(ex.currentTarget.id == 'terciario'){
                        me.cantidadterciario = me.cantidadterciario+ex.key;
                    }
                    
                } 
            },
            
            subirfoto(event){
                let me=this;
                me.foto=event.target.files[0];
                me.cargarImagen();
            },

            cargarImagen(){
                let reader = new FileReader();
                reader.onload=(e)=>{
                    this.imagenminiatura=e.target.result;
                }
                reader.readAsDataURL(this.foto);
            },

            listarrubro(){
                let me=this;
                var url='/rubro/selectrubro';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.rubros=respuesta.rubros;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            listarLinea(){
                let me=this;
                let aux = me.tipoAccion==2?me.idrubroselected:me.idrubrofiltro;
                var url='/linea/selectlinea2?idrubro='+aux;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.lineas=respuesta;
                    me.idlineaselected=0;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
                me.listarCategorias();
            },

            getCodigoLinea(){
                let me = this;
                axios.get('/linea/codigolinea?id='+me.idlineaselected)
                .then(function(response){
                    var respuesta=response.data;
                    me.codigolinea = respuesta[0].codigo;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            listarDispenser(){
                let me=this;
                var url='/dispenser/selectdispenser2';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.dispensers=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },

            listarFormafarm(){
                let me=this;
                var url='/formafarm/selectformafarm2';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.formafarms=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },

            listarCategorias(){
                let me=this;
                let aux = me.tipoAccion==2?me.idrubroselected:me.idrubrofiltro;
                var url='/categoria/selectcategoria2?idrubro='+aux;
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.categorias = respuesta;
                    me.idcategoriaselected = 0;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                return;
                this.createImage(files[0]);
            },
            createImage(file) {
                var image = new Image();
                var reader = new FileReader();
                var vm = this;

                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                    reader.readAsDataURL(file);
            },
            removeImage: function (e) {
                this.image = '';
            },
            tiempo(){
            this.clearSelected=1;
            },
            tiempo1(){
            this.clearSelected1=1;
            },
            tiempo2(){
            this.clearSelected2=1;
            },
            tiempo3(){
            this.clearSelected3=1;
            },
            /*categorias(categorias){
                this.idcategoria=[];
                for (const key in categorias) {
                    if (categorias.hasOwnProperty(key)) {
                        const element = categorias[key];
                        //console.log(element);
                        this.idcategoria.push(element);
                    }
                }
                //console.log(this.idprestaciones);
            },
            cleancategorias(){
                this.idcategoria=[];
                this.idcategoriaelected='';
            
            },*/
            /*
            lineas(lineas){
                this.idlineas=[];
                for (const key in lineas) {
                    if (lineas.hasOwnProperty(key)) {
                        const element = lineas[key];
                        //console.log(element);
                        this.idlineas.push(element);
                    }
                }
                //console.log(this.idprestaciones);
            },
            cleanlineas(){
                this.idlineas=[];
                this.idlineaselected='';
            
            },*/
            /*dispensers(dispensers){
                this.iddispenser=[];
                for (const key in dispensers) {
                    if (dispensers.hasOwnProperty(key)) {
                        const element = dispensers[key];
                        //console.log(element);
                        this.iddispenser.push(element);
                    }
                }
                //console.log(this.idprestaciones);
            },
            cleandispensers(){
                this.iddispenser=[];
                this.iddispenserselected='';
            
            },*/
            /*
            formafarm(formafarm){
                this.idformafarm=[];
                for (const key in formafarm) {
                    if (formafarm.hasOwnProperty(key)) {
                        const element = formafarm[key];
                        //console.log(element);
                        this.idformafarm.push(element);
                    }
                }
                //console.log(this.idprestaciones);
            },
            cleanformafarm(){
                this.idformafarm=[];
                this.idformafarmselected='';
            
            },*/

            listarProducto(page){
                let me=this;
                var url='/producto?page='+page+'&buscar='+me.buscar+'&idrubro='+me.idrubrofiltro;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arrayProducto=respuesta.producto.data;
                    me.listarCategorias();
                    me.listarLinea();
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },


            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarProducto(page);
            },

            registrarProducto(){
                let me = this;
                let formData = new FormData();
                formData.append('foto', me.foto);
                formData.append('idlineaselected', me.idlineaselected);
                formData.append('codigolinea',me.codigolinea);
                formData.append('nombre',me.nombre);
                formData.append('idrubro',me.idrubrofiltro);
                formData.append('iddispenserselectedprimario',me.iddispenserselectedprimario);
                formData.append('cantidadPrimario',me.cantidadprimario);
                formData.append('checkformafarmaceuticaprimario',me.checkformafarmaceuticaprimario==true?1:0);        
                formData.append('idformafarmselectedprimario',me.checkformafarmaceuticaprimario==true?me.idformafarmselectedprimario:0);
                formData.append('idformafarmselectedprimario',me.idformafarmselectedprimario);
                formData.append('preciolistaprimario',me.preciolistaprimario);
                formData.append('precioventaprimario',me.precioventaprimario);
                formData.append('tiempopedidoselectedprimario',me.tiempopedidoselectedprimario);
                formData.append('metodoselectedprimario',me.metodoselectedprimario);
                formData.append('tiendaprimario',me.tiendaprimario==true?1:0);
                formData.append('almacenprimario',me.almacenprimario==true?1:0);

                formData.append('iddispenserselectedsecundario',me.iddispenserselectedsecundario);
                formData.append('cantidadsecundario',me.cantidadsecundario);
                formData.append('checkformafarmaceuticasecundario',me.checkformafarmaceuticasecundario==true?1:0);
                formData.append('idformafarmselectedsecundario',me.checkformafarmaceuticasecundario==true?me.idformafarmselectedsecundario:0);
                formData.append('preciolistasecundario',me.preciolistasecundario);
                formData.append('precioventasecundario',me.precioventasecundario);
                formData.append('tiempopedidoselectedsecundario',me.tiempopedidoselectedsecundario);
                formData.append('metodoselectedsecundario',me.metodoselectedsecundario);
                formData.append('tiendasecundario',me.tiendasecundario==true?1:0);
                formData.append('almacensecundario',me.almacensecundario==true?1:0);

                formData.append('iddispenserselectedterciario',me.iddispenserselectedterciario);
                formData.append('cantidadterciario',me.cantidadterciario);
                formData.append('checkformafarmaceuticaterciario',me.checkformafarmaceuticaterciario==true?1:0);
                formData.append('idformafarmselectedterciario',me.checkformafarmaceuticaterciario==true?me.idformafarmselectedterciario:0);
                formData.append('preciolistaterciario',me.preciolistaterciario);
                formData.append('precioventaterciario',me.precioventaterciario);
                formData.append('tiempopedidoselectedterciario',me.tiempopedidoselectedterciario);
                formData.append('metodoselectedterciario',me.metodoselectedterciario);
                formData.append('tiendaterciario',me.tiendaterciario==true?1:0);
                formData.append('almacenterciario',me.almacenterciario==true?1:0);
                
                formData.append('idcategoriaselected',me.idcategoriaselected);
                formData.append('codigointernacional',me.codigointernacional);
                formData.append('mostrardetalles',me.mostrardetalles==true?1:0);
                formData.append('indicaciones',me.mostrardetalles==true?me.indicaciones:'');
                formData.append('dosificacion',me.mostrardetalles==true?me.dosificacion:'');
                formData.append('principio',me.mostrardetalles==true?me.principio:'');
                formData.append('accion',me.mostrardetalles==true?me.accion:'');

                axios.post('/producto/registrar', formData, {headers : {'content-type': 'multipart/form-data'}})
                .then(function(response){
                    if(response.data=='error')
                    {
                        Swal.fire('El registro ya existe','Debe introducir uno diferente');
                    }
                    else
                    {
                        Swal.fire('Registrado Correctamente');
                        me.cerrarModal('registrar');
                        me.listarProducto(1);
                    }
                    
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },
            eliminarProducto(idproducto){
                let me=this;
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
                     axios.put('/producto/desactivar',{
                        'id': idproducto
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarProducto(me.pagination.current_page);
                        
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
            activarProducto(idproducto){
                let me=this;
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
                     axios.put('/producto/activar',{
                        'id': idproducto
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarProducto(me.pagination.current_page);
                        
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
            actualizarProducto(){
                let me = this;
                let formData = new FormData();
                formData.append('id', me.id);
                formData.append('foto', me.foto);
                formData.append('idlineaselected', me.idlineaselected);
                formData.append('nombre',me.nombre);
                formData.append('idrubro',me.idrubroselected);
                formData.append('iddispenserselectedprimario',me.iddispenserselectedprimario);
                formData.append('cantidadPrimario',me.cantidadprimario);
                formData.append('checkformafarmaceuticaprimario',me.checkformafarmaceuticaprimario==true?1:0);
                formData.append('idformafarmselectedprimario',me.checkformafarmaceuticaprimario==true?me.idformafarmselectedprimario:0);
                formData.append('preciolistaprimario',me.preciolistaprimario);
                formData.append('precioventaprimario',me.precioventaprimario);
                formData.append('tiempopedidoselectedprimario',me.tiempopedidoselectedprimario);
                formData.append('metodoselectedprimario',me.metodoselectedprimario);
                formData.append('tiendaprimario',me.tiendaprimario==true?1:0);
                formData.append('almacenprimario',me.almacenprimario==true?1:0);

                formData.append('iddispenserselectedsecundario',me.iddispenserselectedsecundario);
                formData.append('cantidadsecundario',me.cantidadsecundario);
                formData.append('checkformafarmaceuticasecundario',me.checkformafarmaceuticasecundario==true?1:0);
                formData.append('idformafarmselectedsecundario',me.checkformafarmaceuticasecundario==true?me.idformafarmselectedsecundario:0);
                formData.append('preciolistasecundario',me.preciolistasecundario);
                formData.append('precioventasecundario',me.precioventasecundario);
                formData.append('tiempopedidoselectedsecundario',me.tiempopedidoselectedsecundario);
                formData.append('metodoselectedsecundario',me.metodoselectedsecundario);
                formData.append('tiendasecundario',me.tiendasecundario==true?1:0);
                formData.append('almacensecundario',me.almacensecundario==true?1:0);

                formData.append('iddispenserselectedterciario',me.iddispenserselectedterciario);
                formData.append('cantidadterciario',me.cantidadterciario);
                formData.append('checkformafarmaceuticaterciario',me.checkformafarmaceuticaterciario==true?1:0);
                formData.append('idformafarmselectedterciario',me.checkformafarmaceuticaterciario==true?me.idformafarmselectedterciario:0);
                formData.append('preciolistaterciario',me.preciolistaterciario);
                formData.append('precioventaterciario',me.precioventaterciario);
                formData.append('tiempopedidoselectedterciario',me.tiempopedidoselectedterciario);
                formData.append('metodoselectedterciario',me.metodoselectedterciario);
                formData.append('tiendaterciario',me.tiendaterciario==true?1:0);
                formData.append('almacenterciario',me.almacenterciario==true?1:0);

                formData.append('idcategoriaselected',me.idcategoriaselected);
                formData.append('codigointernacional',me.codigointernacional);
                formData.append('mostrardetalles',me.mostrardetalles==true?1:0);
                formData.append('indicaciones',me.mostrardetalles==true?me.indicaciones:'');
                formData.append('dosificacion',me.mostrardetalles==true?me.dosificacion:'');
                formData.append('principio',me.mostrardetalles==true?me.principio:'');
                formData.append('accion',me.mostrardetalles==true?me.accion:'');

                axios.post('/producto/actualizar', formData, {headers : {'content-type': 'multipart/form-data'}})
                .then(function (response) {
                    if(response.data.length)
                    {}
                    else
                    {
                        Swal.fire('Actualizado Correctamente')
                    } 
                    me.listarProducto(me.pagination.current_page);
                }).catch(function (error) {
                    error401(error);
                });
                me.cerrarModal('registrar');
            },

            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.tipoAccion = 1;
                        me.removeImage;
                        me.tituloModal='Registar Producto'
                        me.nombre = '';
                        me.idrubroselected = 0;
                        me.iddispenserselectedprimario = 0;
                        me.cantidadprimario = 0;
                        me.checkformafarmaceuticaprimario = false;
                        me.idformafarmselectedprimario = 0;
                        me.preciolistaprimario = 0;
                        me.precioventaprimario = 0;
                        me.tiempopedidoselectedprimario = 0;
                        me.metodoselectedprimario = 'A';
                        me.tiendaprimario = false;
                        me.almacenprimario = false;
                        me.iddispenserselectedsecundario = 0;
                        me.cantidadsecundario = 0;
                        me.checkformafarmaceuticasecundario = false;
                        me.idformafarmselectedsecundario = 0;
                        me.preciolistasecundario = 0;
                        me.precioventasecundario = 0;
                        me.tiempopedidoselectedsecundario = 0;
                        me.metodoselectedsecundario = 'A';
                        me.tiendasecundario = false;
                        me.almacensecundario = false;
                        me.iddispenserselectedterciario = 0;
                        me.cantidadterciario = 0;
                        me.checkformafarmaceuticaterciario = false;
                        me.idformafarmselectedterciario = 0;
                        me.preciolistaterciario = 0;
                        me.precioventaterciario = 0;
                        me.tiempopedidoselectedterciario = 0;
                        me.metodoselectedterciario = 'A';
                        me.tiendaterciario = false;
                        me.almacenterciario = false;
                        me.mostrardetalles = false;
                        me.idcategoriaselected = 0;
                        me.indicaciones = '';
                        me.dosificacion = '';
                        me.principio = '';
                        me.accion = '';
                        me.foto = '';
                        //me.imagen = '';
                        me.imagenminiatura = '';
                        document.getElementById('imgproducto').value = '';
                        me.codigointernacional = '';

                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Producto: ' + data.codprod;
                        me.id = data.id;
                        //me.codigo = data.codprod;
                        me.nombre = data.nomprod;
                        me.idrubroselected = data.idrubro;
                        me.idlineaselected = data.idlinea;
                        me.iddispenserselectedprimario = data.idenvaseprimario;
                        me.cantidadprimario = data.cantidadprimario;
                        me.checkformafarmaceuticaprimario = data.checkformafarmaceuticaprimario==1?true:false;
                        me.idformafarmselectedprimario = data.idformafarmaceuticaprimario;
                        me.preciolistaprimario = data.preciolistaprimario;
                        me.precioventaprimario = data.precioventaprimario;
                        me.tiempopedidoselectedprimario = data.tiempopedidoprimario;
                        me.metodoselectedprimario = data.metodoabcprimario;
                        me.tiendaprimario = data.tiendaprimario==1?true:false;
                        me.almacenprimario = data.almacenprimario==1?true:false;
                        me.iddispenserselectedsecundario = data.idenvasesecundario;
                        me.cantidadsecundario = data.cantidadsecundario;
                        me.checkformafarmaceuticasecundario = data.checkformafarmaceuticasecundario==1?true:false;
                        me.idformafarmselectedsecundario = data.idformafarmaceuticasecundario;
                        me.preciolistasecundario = data.preciolistasecundario;
                        me.precioventasecundario = data.precioventasecundario;
                        me.tiempopedidoselectedsecundario = data.tiempopedidosecundario;
                        me.metodoselectedsecundario = data.metodoabcsecundario;
                        me.tiendasecundario = data.tiendasecundario==1?true:false;
                        me.almacensecundario = data.almacensecundario==1?true:false;
                        me.iddispenserselectedterciario = data.idenvaseterciario;
                        me.cantidadterciario = data.cantidadterciario;
                        me.checkformafarmaceuticaterciario = data.checkformafarmaceuticaterciario==1?true:false;
                        me.idformafarmselectedterciario = data.idformafarmaceuticaterciario;
                        me.preciolistaterciario = data.preciolistaterciario;
                        me.precioventaterciario = data.precioventaterciario;
                        me.tiempopedidoselectedterciario = data.tiempopedidoterciario;
                        me.metodoselectedterciario = data.metodoabcterciario;
                        me.tiendaterciario = data.tiendaterciario==1?true:false;
                        me.almacenterciario = data.almacenterciario==1?true:false;
                        me.mostrardetalles = data.mostrardetalles==1?true:false;
                        me.idcategoriaselected = data.idcateg;
                        me.indicaciones = data.indicaciones;
                        me.dosificacion = data.dosificacion;
                        me.principio = data.principio;
                        me.accion = data.accion;
                        data.foto = data.foto === null ? 'persona.png': data.foto.substring(8);
                        me.imagenminiatura = 'imgproductos/'+ data.foto;
                        me.codigointernacional = data.codigointernacional;

                        me.classModal.openModal('registrar');
                        
                        break;
                    }

                }
                
            },

            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.nombre='';
                me.cantidad='';
                me.idlineas=[];
                me.idlineaselected=0;
                me.clearSelected=0;
                // setTimeout(me.tiempo, 200); 
                me.iddispenser=[];
                me.iddispenserselected=0;
                me.clearSelected1=0;
                // setTimeout(me.tiempo1, 200); 
                me.idformafarm=[];
                me.idformafarmselected=0;
                me.clearSelected2=0;
                // setTimeout(me.tiempo2, 200); 
                me.clearSelected3=0;
                // setTimeout(me.tiempo3, 200); 
                 me.idcategoria=[];
                me.idcategoriaselected=0;
                me.preciolista=0;
                me.precioventa=0;
                me.tiempopedidoselected=0;
                me.indicaciones='';
                // me.dosificacione='';
                me.principio='';
                me.accion='';
                me.tipoAccion=1;
                // me.image='';
                me.imagen='';
                me.metodoselected='A';
                me.listarProducto();  
            },

            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  


        },
        mounted() {
             //-------permiso E_W_S-----
             this.listarPerimsoxyz();
              //this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
            //this.listarProducto(1);
            //this.listarLinea();
            this.listarrubro();
            this.listarDispenser();
            this.listarFormafarm();
            //this.listarCategorias();
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            //console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;
    
}
img {
  width: 30%;
  margin: auto;
  display: block;
  margin-bottom: 0px;
  margin-left: 7px;
}
.fotosociomini{
	    display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:32px;
}

#nombre-prducto > div > div
{
    display: inline-block;
}

#botonos-infoprisecter > ul > li
{
    margin-left: 10px;   
}

#botonos-infoprisecter > ul > li >button
{
    border: 0;
}

</style>