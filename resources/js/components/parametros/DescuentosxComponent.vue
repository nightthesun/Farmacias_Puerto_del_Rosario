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
                    <div class="d-flex align-items-center">
                        <div v-if="puedeCrear==1">
                            <i class="fa fa-align-justify"></i> Descuentos    
                            <button v-if="selectTabla==4" :disabled="selectTabla==0"
                                    type="button"
                                    class="btn btn-secondary"
                                    style="margin-right: 10px;" 
                                    @click="abrirModal('registrar');listarProductoX();">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                            <button v-else :disabled="selectTabla==0"
                                    type="button"
                                    class="btn btn-secondary"
                                    style="margin-right: 10px;" 
                                    @click="abrirModal('registrar');">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>                      
                        </div>
                        <button type="button"
                        class="btn btn-outline-secondary"
                        @click="listarSucursalesX_descuentos();abrirModal('vista_descuento');"
                        style="margin-left: 10px;"> 
                            <i class="fa fa-eye" aria-hidden="true"></i> Ver descuento x sucursal
                        </button>

                        <button type="button" v-if="selectTabla===3 && puedeHacerOpciones_especiales==1"
                        class="btn btn-outline-info"
                        @click="abrirModal('asignar');listarDescuentoXtdaAlm(0);"
                        style="margin-left: 10px;"> 
                        <i class="fa fa-plus" aria-hidden="true"></i> Añadir sucursal
                        </button>
                    </div>                                    
                </div>
                
        <div class="card-body">
            <div class="form-group row">
             
                <div class="col-md-1">
                     <label for="">Tipo de tabla:</label>
                </div>
                        <div class="col-md-5">
                            
                                        <select v-model="selectTabla" class="form-control" @change="listarIndex(0)">
                                            <option v-bind:value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option v-for="t in arrayTabla" :key="t.id" :value="t.id" v-text="t.nombre"></option>
                                        </select>
                               
                             
                        </div>
                        <div class="col-md-4" :hidden="selectTabla==0">
                            
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)"
                                    />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>

            </div>
            <!---table ini-->
            <table class="table table-bordered table-striped table-sm table-responsive">
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-2">Nombre del descuento</th>
                        <th class="col-md-2">Descripcion</th>
                        <th class="col-md-1">Tipo</th>
                        <th class="col-md-1">Descuento</th>
                        <th v-if="selectTabla == 2 " class="col-md-1">Tipo C/V</th>
                        <th v-if="selectTabla == 2" class="col-md-1">Regla</th>
            <th v-if="selectTabla == 2" class="col-md-1">Valor C/V</th>
           
            <th v-if="selectTabla == 3" class="col-md-1">Número de documento</th>
            <th v-if="selectTabla == 3" class="col-md-2">Nombre a facturar</th>

            <th v-if="selectTabla == 4" class="col-md-1">Lugar</th>
            <th v-if="selectTabla == 4" class="col-md-2">Producto</th>
            <th v-if="selectTabla == 5" class="col-md-2">Maximo</th>
            <th class="col-md-1">Usuario</th>
            <th class="col-md-1">Estado</th>

                    </tr>
                </thead>
                <tbody v-if="selectTabla == 0"></tbody>
                        <tbody v-else>
                          
                        <tr v-for="i in arrayIndex" :key="i.id">
                            <td class="col-md-1">
                                <div  class="d-flex justify-content-start">
                                    <div  v-if="puedeEditar==1">
                                        <button type="button" class="btn btn-warning btn-sm"  style="margin-right: 5px;"  @click="abrirModal('actualizar', i)">
                                            <i class="icon-pencil"></i></button>    
                                    </div>
                                    <div v-else>
                                        <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-pencil"></i></button>
                                    </div>
                                    <div v-if="puedeActivar==1">
                                        <button v-if="i.activo == 1" type="button" class="btn btn-danger btn-sm" @click="eliminar(i.id)"  
                                        style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-info btn-sm" @click="activar(i.id)" style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                        </button>  
                                    </div>
                                    <div v-else>
                                        <button v-if="i.activo == 1" type="button" class="btn btn-light btn-sm" 
                                        style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                        </button> 
                                    </div>    
                                        
                                    <div v-if="puedeHacerOpciones_especiales==1 && selectTabla!=3">
                                        <button type="button" @click="listarDescuentoXtdaAlm(i.id); abrirModal('asignar',i);" 
                                        class="btn btn-secondary btn-sm" style="margin-right: 5px; color: white;" data-toggle="tooltip" data-placement="right" >
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                        </button> 
                                    </div>
                                   <div v-else>
                                    <button type="button" 
                                    class="btn btn-light btn-sm" style="margin-right: 5px; color: white;" data-toggle="tooltip" data-placement="right" >
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button> 
                                   </div>
                              
                                    
                                    
                                    
                                </div> 
                                   
                            </td>
                            <td class="col-md-2" v-text="i.nombre_descuento"></td>
                            <td class="col-md-2" v-text="i.descripcion"></td>                      
                            <td class="col-md-1">
                                <div v-if="i.desc_num == 1">
                                    <span>Monto monetario</span>
                                </div>
                                <div v-if="i.desc_num == 2">
                                    <span>Porcentual</span>
                                </div>
                            </td> 

                             <td class="col-md-1" >
                                <div v-if="i.desc_num == 1">
                                    <span>{{ i.monto_descuento + ' Bs.' }}</span>
                                </div>
                                <div v-if="i.desc_num == 2">
                                    <span>{{ i.monto_descuento + ' %.' }}</span>
                                </div>
                            </td>
                            <td class="col-md-1" v-if="selectTabla == 2">
                                <div v-if="i.es_cantidad_es_monto == 1">
                                    <span>Cantidad</span>
                                </div>
                                <div v-if="i.es_cantidad_es_monto == 2">
                                    <span>Valor monetario</span>
                                </div>                              
                               </td>
                            <td class="col-md-1" v-if="selectTabla == 2">
                                <div v-if="i.regla == 1">
                                    <span>Menor que</span>
                                </div>
                                <div v-if="i.regla == 2">
                                    <span>Mayor que</span>
                                </div>
                                <div v-if="i.regla == 3">
                                    <span>Igual que</span>
                                </div>
                            </td>
                          
                            <td class="col-md-1" v-if="selectTabla == 2">
                                <div v-if="i.es_cantidad_es_monto == 1">
                                    <span>{{ i.cantidad_valor + ' Uni.' }}</span>
                                </div>
                                <div v-if="i.es_cantidad_es_monto == 2">
                                    <span>{{ i.cantidad_valor + ' Bs.' }}</span>
                                </div>
                            </td>

                            <td class="col-md-1" v-text="i.num_documento" v-if="selectTabla == 3"></td>
                            <td class="col-md-1" v-text="i.nom_facturar" v-if="selectTabla == 3"></td>

                            <td class="col-md-1" v-text="i.lugar" v-if="selectTabla == 4"></td>
                            <td class="col-md-1" v-if="selectTabla == 4">{{i.cod_prod+' '+i.leyenda+' envase: '+i.envase}}</td>
                            <td class="col-md-1" v-if="selectTabla == 5">{{i.max}}</td>
                            <td class="col-md-1" v-text="i.user_name"></td>
                            <td class="col-md-1">
                                <div v-if="i.activo == 1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning">Desactivado</span>
                                    </div>
                            </td>
                        </tr>

                    </tbody>
            </table>    
            <nav> <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">                              <a
                                    class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,)">Sig</a
                                >
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
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <form action="" class="form-horizontal">                        
                            <!-- insertar datos -->
                            <div class="container">
                               
                                <div v-if="selectTabla!=0">
                                    <div class="from-group row" >
                                        <div class="col-md-6" v-if="selectTabla!==3">
                                            <strong>Nombre descuento:</strong>
                                            <input type="text" class="form-control" placeholder="Nombre Descuento" v-model="nombre_Des" v-on:focus="selectAll" >
                                        <span  v-if="nombre_Des==''" class="error">Debe Ingresar el nombre de descuento</span>
                                        </div>
                                        <div class="col-md-6" v-if="selectTabla!==3">
                                            <strong>Descripcion:</strong>                                    
                                                <input type="text" class="form-control" placeholder="Describa como se realizara el descuento" v-model="descripcion_Des" v-on:focus="selectAll" >
                                            <span  v-if="descripcion_Des==''" class="error">Debe Ingresar la descripcion de descuento</span>
                                            
                                        </div>                                                             
                                    </div>
    <br>
                                    <div class="from-group row">
                                        <div class="col-md-4">
                                            <strong>Nombre tipo:</strong>
                                            <div class="col-auto my-1">
                                           
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" v-model="selectTipoNumPor">
                                                  <option selected :value="0">Seleccionar...</option>
                                                  <option value="1">Numeral #</option>
                                                  <option value="2">Procential %</option>
                                                  
                                                </select>
                                              </div> <span  v-if="selectTipoNumPor==0" class="error">Debe Ingresar el nombre de descuento</span>
                                        </div>
                                        <div class="col-md-8" v-if="selectTipoNumPor!=0">
                                            <strong>Monto: <span  v-if="selectTipoNumPor==1">en bolivianos</span><span  v-if="selectTipoNumPor==2">en porcentaje</span></strong>                                    
                                                <input type="number"  style="text-align: right;" class="form-control" placeholder="Ingresa valores numericos" v-model="monto" v-on:focus="selectAll" >
                                            <span  v-if="monto==''" class="error">Debe Ingresar un monto </span> 
                                            
                                        </div>                                                             
                                    </div> 
                                </div> 
                                <br>
                                <hr> 
                                <div v-if="selectTabla==2">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Tipo de cantidad o valor: <span  v-if="selectCantidad_precio==0" class="error">(*)</span></label>
                                         <div class="col-md-8">
                                            <select v-model="selectCantidad_precio" class="form-control">
                                                <option selected :value="0">Seleccionar...</option>
                                                <option value="1">Cantidad</option>
                                                <option value="2">Valor de compra</option>
                                            </select>
                                        <span  v-if="selectCantidad_precio==0" class="error">Debe eligir un tipo de descuento</span>
                                    </div>
                                    </div> 
                                    <br>
                                    <div class="from-group row" v-if="selectCantidad_precio!=0&&nombre_Des!=''&&monto!=''">
                                        <div class="col-md-4">
                                            <strong>Regla:</strong>
                                            <div class="col-auto my-1">                       
                                               
                                            <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" v-model="selectRegla">
                                                <option selected :value="0">Seleccionar...</option>
                                                <option value="1">Menor a</option>
                                                <option value="2">Mayor a</option>
                                                <option value="3">Igual a</option>
                                              </select>
                                            </div> <span  v-if="selectRegla==0" class="error">Debe Ingresar la regla.</span>
                                        </div>  
                                       
                                        <div class="col-md-8" v-if="selectRegla!=0">
                                            <strong> <span  v-if="selectCantidad_precio==1">Cantida en unidades:</span><span  v-if="selectCantidad_precio==2">Monto en bolivianos:</span></strong>                                    
                                                <input type="number"  style="text-align: right;" class="form-control" placeholder="Debe ingresar un monto o cantidad positiva" v-model="cantidad_monto_x" v-on:focus="selectAll" >
                                            <span  v-if="cantidad_monto_x==''" class="error">Debe Ingresar una cantidad o monto</span> 
                                            
                                        </div>                                                             
                                    </div> 

                                </div> 
                                <div v-if="selectTabla==3">
                                    <span>Cliente</span>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="text" id="texto"  name="texto" class="form-control" placeholder="Numero de documento"
                                                    v-model="buscarCliente"/>
                                                <button type="button" class="btn btn-primary" @click=" listarClienteX()">                                               
                                                    <i class="fa fa-search"></i> 
                                                </button>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="texto" disabled name="texto" class="form-control" placeholder="Debe buscar un cliente"
                                                 :value="datos_cliente"/>
                                        </div>
                                     
                                    </div>
                                 
                                </div>                             
                                <div v-if="selectTabla==4">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                       
                                            <VueMultiselect
                                            v-model="selected"
                                            :options="arrayProductoX"
                                            :max-height="190"                   
                                            :block-keys="['Tab', 'Enter']"                       
                                            placeholder="Seleccione una opción"
                                            label="leyenda" 
                                            :custom-label="nameWithLang"               
                                            track-by="id"
                                            class="w-250"
                                            selectLabel="Añadir a seleccion"
                                            deselectLabel="Quitar seleccion"
                                            selectedLabel="Seleccionado"
                                           >
                                           <template #noResult>
                                            No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                                          </template>
                                        </VueMultiselect>  
                                            </div>
                                        </div>
                                    
                             </div>
                                <div v-if="selectTabla==5 && selectTipoNumPor!=0 && monto!=''">
                                    <div class="alert alert-danger" role="alert">
                                      Una vez que asigne este descuento alguna sucursal, tienda o almacen.
                                      Solo funcionara este descuento ya que es un descuento personalizado y si tiene algun descuento asignado en esa sucursal dejara de funcionar. 
                                      </div>
                                      <div class="alert alert-danger" role="alert">
                                     Solo puede existir uno seleccionado por sucursal, el valor maximo de <strong> Valor maximo de {{ monto}} </strong>  </div>
                                 
                                                  
                                    
                                   
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
                           @click="registrarDescuento()"
                            :disabled="!sicompleto"
                        >
                            Guardar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 2"
                            class="btn btn-primary"
                          @click="actualizar()"
                        >
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->

         <!--Inicio del modal asignacion -->
         <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="registrar1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal1('registrar1')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-12" >
                                <div class="modal-body">
                     


                     <form action=""  class="form-horizontal">

                       
                             <label class="col-md-6 form-control-label" for="text-input"><strong>Lista de sucursales:</strong> </label>
                             <div class="col-md-9">
                                <table class="table table-bordered table-striped table-sm table-responsive">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Cod Sucursal</th>
                                            <th>Codigo</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="option in arrayAlmTienda" :key="option.id">
                                            <td>
                                                <input type="checkbox" :value="{ codigo: option.codigo, id_sucursal: option.id_sucursal, id_tienda_almacen: option.id_tienda_almacen }" v-model="selectAlmTda2">
                                            </td>
                                            
                                            <td v-text="option.razon_social"></td>
                                            <td v-text="option.codigoS"></td>
                                            <td v-text="option.codigo"></td>
                                        
                                        </tr>
                                    </tbody>
                                </table>

                            
                        
                             </div>
                       

                     </form>
                 </div>
                            </div>
                         
                            </div>
                        </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal1('registrar1')">Cerrar</button>
 
                        <button type="button" class="btn btn-primary" @click="asignarSucursal()">Asignar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->


         <!--Inicio del modal vista de descuentos -->
         <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="vista_descuento" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal1('vista_descuento')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-12" >
                                <div class="modal-body">
                     


                     <form action=""  class="form-horizontal">
  <div class="col-md-12" style="overflow-x: auto; overflow-y: auto; max-height: 400px;">
    <table class="table table-bordered table-striped table-sm table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="col-md-2">Nombre sucursal</th>
                                            <th class="col-md-2">Nombre Tienda o Almacen</th>
                                            <th class="col-md-5">Codigo</th>
                                            <th class="col-md-2">Tipo</th>
                                            <th>Nombre del descuento</th>
                                            <th class="col-md-1">Tipo de descuento</th>
                                            <th>Monto</th>
                                            <th>Tipo de tabla</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="s in arraySucursalY" :key="s.id"  >
                                            <td class="col-md-2" v-text="s.nom_suc"></td>
                                            <td class="col-md-2" v-text="s.nom_alm_td"></td>                                           
                                            <td class="col-md-5" v-if="s.codigo_alm_tda!=''">{{s.cod_suc+" -> "+s.codigo_alm_tda}}</td>
                                            <td class="col-md-5" v-else></td>
                                            <td class="col-md-2" v-text="s.tipo_tienda_almacen"></td>
                                            <td v-text="s.nombre_descuento"></td>
                                            <td class="col-md-1" v-text="s.tipo_descuento"></td>
                                            <td v-text="s.monto_descuento"></td>
                                            <td v-text="s.nombre_de_tabla"></td>   
                                        </tr>
                                    </tbody>
                                </table>

                            
                        
                             </div>
                       

                     </form>
                 </div>
                            </div>
                         
                            </div>
                        </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal1('vista_descuento')">Cerrar</button>
 
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
import VueMultiselect from 'vue-multiselect';
//Vue.use(VeeValidate);
export default {
    components: { VueMultiselect},
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
          
            offset:3,
            tituloModal: "",
            selectAlmTienda:0,
            arrayAlmTienda:[],
            buscar:"",
            tipoAccion:1,

            //---------
            selectTabla:0,
            arrayTabla:[],
            nombre_Des:'',
            descripcion_Des:'',
            selectTipoNumPor:0,
            monto:0,
            //-----
            selectCantidad_precio:0,
            selectRegla:0,
            cantidad_monto_x:0,
            buscarCliente:'',
            buscarCliente_Nom:'',
            buscarResultado:'',
          
            id_cliente:'',
            nombre_cliente:'',
            num_cliente:'',
            arrayProductoX:[],
            selected:null,
            arrayIndex:[],
            datos_cliente:'',
            id_index:'',
            id_descuento_x:'',

            selectAlmTda2:[],
            selectAlmTda3:[],
            arrayFalso:[],
            arrayDescuentoxSucursal:[],
             //---permisos_R_W_S
             puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------   
              
                arrayDescuentoY:[],
                arraySucursalY:[],

                personalizado:0,
                id_personalizado:'',
            
        };
    },

   

    computed: {
        sicompleto() {
           let me = this;
            if (
          
                me.nombre_Des != "" &&
                me.descripcion_Des != "" &&
                me.selectTipoNumPor !=0 && me.monto !=""
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

        nameWithLang ({codigo_prod,leyenda, envase,tipo_lugar}) {
            
            return `${codigo_prod} ${leyenda} ${envase} ${tipo_lugar}`
          },

          registrarDescuento(){
                let me = this;
                let codificador=0;
                let valor = me.selectTabla;
            
    switch (valor) {
    case 1:
    if (me.nombre_Des==""|| me.descripcion_Des==""||me.selectTipoNumPor==0||me.monto=="") {
                        codificador=1;
                    }
        break;
    case 2:
        if (me.selectCantidad_precio==0|| me.selectRegla==0||me.cantidad_monto_x=="") {
                        codificador=1;
                    }
        break;
    case 3:
        if (me.id_cliente==0||me.id_cliente=="") {
            codificador=1;
        }
        break;
    case 4:
        if (me.selected==null||me.selected==""||me.selected==0) {
            codificador=1;
        }
    case 5:{
        if (me.nombre_Des==""|| me.descripcion_Des==""||me.selectTipoNumPor==0||me.monto=="") {
                        codificador=1;
        }   
        break;    
    }    
    case 6:
    if (me.nombre_Des==""|| me.descripcion_Des==""||me.selectTipoNumPor==0||me.monto=="") {
                        codificador=1;
                    }
    
        break;    
    default:
        console.log('no existe datos');
        break;
}
                if(codificador==1){
                    Swal.fire(
                    "Error",
                    "Existen campos nulos debe revisar que campos estan vacios", 
                    "error"
                );
                }
                else{

                    let data = {};

if (valor == 1||valor == 6) {
    data = {
        'id_tipo_tabla': valor,
       
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto
    };
}
if (valor == 2) {
    data = {
        'id_tipo_tabla': valor,
       
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'es_cantidad_es_monto': me.selectCantidad_precio,
        'regla': me.selectRegla,
        'cantidad_valor': me.cantidad_monto_x,
    };
}
if (valor == 3) {
    data = {
        'id_tipo_tabla': valor,
      
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'id_cliente_p': me.id_cliente,
        'num_documento': me.num_cliente,
        'nom_facturar': me.nombre_cliente,
        
      
    };
   
}
if (valor == 4) {
  
    data = {
        'id_tipo_tabla': valor,
      
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'id_prod': me.selected.id,
        'cod_prod': me.selected.codigo_prod,
        'envase': me.selected.envase,
        'tipo_tda_alm': me.selected.tipo_lugar,
        'id_linea': me.selected.id_liena,
        'leyenda': me.selected.leyenda,

    };
}
if (valor == 5) {
  
  data = {
      'id_tipo_tabla': valor,
    
      'nombre_descuento': me.nombre_Des,
      'descripcion': me.descripcion_Des,
      'desc_num': me.selectTipoNumPor,
      'monto_descuento': me.monto,
      'personalizado':me.monto
     

  };
}

axios.post('/descuento2/registrarDescuento', data)
    .then(function(response) {
        if (response.data.message=="La persona ya existe") {
                    Swal.fire(
                            "Error de ingreso de cliente",
                            "El cliente ya tiene descuento",
                            "error",
                        );
                  }else{
                    Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
        me.cerrarModal('registrar');
        me.listarIndex(1);  
                  }
     
    })       .catch(function (error) {    
                   

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

            }
        },


        listarProductoX() {
            let me = this;
            var url = "/descuento2/listarProductoX";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data;
                    me.arrayProductoX =respuesta1;                  
            
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarClienteX() {
            let me = this;
            var url = "/descuento2/listarClienteX?num="+me.buscarCliente;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data;
                    if(respuesta1.nom_a_facturar==undefined&&respuesta1.num_cliente==undefined){
                        me.datos_cliente="No se encontro cliente."
                        me.nombre_cliente="Sin datos";
                        me.num_cliente="No se encontro datos";
                        me.id_cliente=0;
                    }else{
                        me.id_cliente=respuesta1.id;
                        me.datos_cliente="Nombre a facturar:"+respuesta1.nom_a_facturar+" Numero de docuemnto:"+respuesta1.num_documento;
                    me.nombre_cliente=respuesta1.nom_a_facturar;
                    me.num_cliente=respuesta1.num_documento;
                    }
               
            
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },


        listarLista_cliente() {
            let me = this;
            var url = "/descuento2/listarClienteX?num="+me.buscarCliente;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data;
                    if(respuesta1.nom_a_facturar==undefined&&respuesta1.num_cliente==undefined){
                        me.datos_cliente="No se encontro cliente."
                        me.nombre_cliente="Sin datos";
                        me.num_cliente="No se encontro datos";
                        me.id_cliente=0;
                    }else{
                        me.id_cliente=respuesta1.id;
                        me.datos_cliente="Nombre a facturar:"+respuesta1.nom_a_facturar+" Numero de docuemnto:"+respuesta1.num_documento;
                    me.nombre_cliente=respuesta1.nom_a_facturar;
                    me.num_cliente=respuesta1.num_documento;
                    }
               
            
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        
        listarIndex(page)
            {
                let me=this;
                if (me.selectTabla!=0) {
                    var url='/descuento2/index?page='+page+'&buscar='+me.buscar+'&tabla='+me.selectTabla;               
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.descuento.data;
                 
                })
                .catch(function(error){
                    error401(error);
                }); 
                } 
               
            },

        listarTipoTabla() {
            let me = this;
            var url = "/descuento2/listarTipoTabla";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data;                                
                    me.arrayTabla = respuesta1;               
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarSucursalesX_descuentos(){
            let me = this;
            var url = "/descuento2/listarSucursalesX_descuentos";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                
                    me.arraySucursalY=respuesta;

                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarAlmTienda() {
            let me = this;
            var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmTienda = respuesta;

                 
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

        eliminar(id) {
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
                            .put("/descuento2/desactivar", {
                                id: id,
                                
                            })
                            .then(function (response) {
                                me.listarIndex();
                                swalWithBootstrapButtons.fire(
                                    "Desactivado!",
                                    "El registro a sido desactivado Correctamente",
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
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
        },

        activar(id) {
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
                            .put("/descuento2/activar", {
                                id: id,
                            })
                            .then(function (response) {
                                me.listarIndex();
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

        actualizar() {            
            let me = this;
            let codificador=0;
            let valor = me.selectTabla;
                  
    switch (valor) {
    case 1:
    if (me.nombre_Des==""|| me.descripcion_Des==""||me.selectTipoNumPor==0||me.monto=="") {
                        codificador=1;
                    }
        break;
    case 2:
        if (me.selectCantidad_precio==0|| me.selectRegla==0||me.cantidad_monto_x=="") {
                        codificador=1;
                    }
        break;
    case 3:
        if (me.id_cliente==0||me.id_cliente=="") {
            codificador=1;
        }
        break;
    case 4:
        if (me.selected==null||me.selected==""||me.selected==0) {
            codificador=1;
        }
        break;  
    case 5:
    if (me.nombre_Des==""|| me.descripcion_Des==""||me.selectTipoNumPor==0||me.monto=="") {
                        codificador=1;
                    }
        break;  
          
        case 6:
    if (me.nombre_Des==""|| me.descripcion_Des==""||me.selectTipoNumPor==0||me.monto=="") {
                        codificador=1;
                    }
        break;  
    default:
        console.log('noexiste datos');
        break;
}
                if(codificador==1){
                    Swal.fire(
                    "Error",
                    "Existen campos nulos debe revisar que campos estan vacios", 
                    "error"
                );
                }
                else{

                    let data = {};

if (valor == 1 || valor == 6) {
    data = {
        'id':me.id_index,
        'id_tipo_tabla': valor,
       
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto
    };
}
if (valor == 2) {
    data = {
        'id':me.id_index,
        'id_tipo_tabla': valor,
       
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'es_cantidad_es_monto': me.selectCantidad_precio,
        'regla': me.selectRegla,
        'cantidad_valor': me.cantidad_monto_x,
        'id_descuento_x':me.id_descuento_x,
    };
}
if (valor == 3) {
    data = {
        'id':me.id_index,
        'id_tipo_tabla': valor,
      
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'id_cliente_p': me.id_cliente,
        'num_documento': me.num_cliente,
        'nom_facturar': me.nombre_cliente,
        'id_descuento_x':me.id_descuento_x,
      
    };
}
if (valor == 4) {
   
    data = {
        'id':me.id_index,
        'id_tipo_tabla': valor,
      
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'id_prod': me.selected.id,
        'cod_prod': me.selected.codigo_prod,
        'envase': me.selected.envase,
        'tipo_tda_alm': me.selected.tipo_lugar,
        'id_linea': me.selected.id_liena,
        'leyenda': me.selected.leyenda,
        'id_descuento_x':me.id_descuento_x,
    };
}
if (valor == 5) {
   
   data = {
       'id':me.id_index,
       'id_tipo_tabla': valor,
     
       'nombre_descuento': me.nombre_Des,
       'descripcion': me.descripcion_Des,
       'desc_num': me.selectTipoNumPor,
       'monto_descuento': me.monto,
       'id_personalizado':me.id_personalizado,
       'personalizado': me.monto,
       'id_descuento_x':me.id_descuento_x,
   };
}

axios.put('/descuento2/actualizar', data)
    .then(function(response) {
        
        Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
        me.cerrarModal('registrar');
        me.listarIndex(1);  
        
    })       .catch(function (error) {                
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

            }
        },

        asignarSucursal(){
            
                let me =this;               
               let cadena=[];

   
           
                for (const selectedOption of this.selectAlmTda2) {
                let elemento = {
      'codigo': selectedOption.codigo,
      'id_sucursal': selectedOption.id_sucursal,
      'id_tienda_almacen': selectedOption.id_tienda_almacen
    };
    cadena.push(elemento);

      }
  
                axios.post('/descuento2/asignar',{
                    id:me.id_index,
                    bloque:cadena,
                    p:me.selectTabla,
                    
                }).then(function (response) {
                  
                    if (response.data.status === 200) {
                        Swal.fire(
                    "Error",
                    "200 (No puede existir dos datos en la misma sucursal) "+response.data.message, // Muestra el mensaje de error en el alert
                    "error");  
                    }else{
                        me.listarIndex();
                    Swal.fire(
                        'Asigno Correctamente!',
                        'El Accion realizada Correctamente',
                        'success'
                    )
                    }
                   
                }).catch(function (error) {                
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
                me.arrayFalso=[];
                me.cerrarModal('registrar1');
               
            },


        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    if(me.selectTabla==1){
                        me.tituloModal = "Registro de descuento normal";
                        me.nombre_Des="";
                    me.descripcion_Des="";
                    }
                    if(me.selectTabla==2){
                        me.tituloModal = "Registro de descuento cantidad o valor";
                        me.nombre_Des="";
                    me.descripcion_Des="";
                    }
                    if(me.selectTabla==3){
                        me.tituloModal = "Registro de descuento por cliente";
                        me.nombre_Des="Lista de clientes";
                        me.descripcion_Des="Lista generica de clientes con descuento";
                    }
                    if(me.selectTabla==4){
                        me.tituloModal = "Registro de descuento por producto";
                        me.nombre_Des="";
                    me.descripcion_Des="";
                    }
                    if(me.selectTabla==5){
                        me.tituloModal = "Registro de descuento personalizado";
                        me.nombre_Des="";
                    me.descripcion_Des="";
                    }
                    if(me.selectTabla==6){
                        me.tituloModal = "Registro de descuento final";
                        me.nombre_Des="";
                    me.descripcion_Des="";
                    }
                          
                   
                    me.selectTipoNumPor=0;
                    me.monto="",
            
                    me.selectCantidad_precio=0;
                    me.selectRegla=0;
                    me.cantidad_monto_x=0;
                    me.buscarCliente="";
                    me.buscarCliente_Nom="";
                    me.buscarResultado="";        
                    me.id_cliente="";
                    me.nombre_cliente="";
                    me.num_cliente="";
                    me.selected=null;
                    me.datos_cliente="";
                    me.personalizado=0;
                    me.id_personalizado="";
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    
                    me.tipoAccion = 2;
                    me.id_index=data.id;
                    if(data.id_tipo_tabla==1||data.id_tipo_tabla==6){
                        me.nombre_Des=data.nombre_descuento;
                        me.descripcion_Des=data.descripcion;
                        me.selectTipoNumPor=data.desc_num === null ? 0 : data.desc_num;
                        me.monto=data.monto_descuento;
                        me.tituloModal = "Registro de descuento Normal o Final";
                   }
                   if(data.id_tipo_tabla==2){
                        me.nombre_Des=data.nombre_descuento;
                        me.descripcion_Des=data.descripcion;
                        me.selectTipoNumPor=data.desc_num === null ? 0 : data.desc_num;
                        me.monto=data.monto_descuento;
                        
                        me.selectCantidad_precio=data.es_cantidad_es_monto == null ? 0: data.es_cantidad_es_monto; 
                        me.selectRegla=data.regla === null ? 0: data.regla;                        
                        me.cantidad_monto_x=data.cantidad_valor;
                        me.id_descuento_x=data.id_descuento_pcp;
                        me.tituloModal = "Registro de descuentos por cantidad o precio";
                   }
                   if(data.id_tipo_tabla==3){
                    me.tituloModal = "Registro por cliente";
                        me.nombre_Des=data.nombre_descuento;
                        me.descripcion_Des=data.descripcion;
                        me.datos_cliente="Nombre a facturar:"+data.nom_facturar+" Numero de docuemnto:"+data.num_documento;
                        me.selectTipoNumPor=data.desc_num === null ? 0 : data.desc_num;
                        me.monto=data.monto_descuento;
                        
                        me.id_cliente=data.id_cliente_p;
                        me.nombre_cliente=data.nom_facturar;
                        me.num_cliente=data.num_documento;
                        me.id_descuento_x=data.id_descuento_cli;
                   }
                    
                   if(data.id_tipo_tabla==4){
                    me.tituloModal = "Registro por cliente";
                        me.nombre_Des=data.nombre_descuento;
                        me.descripcion_Des=data.descripcion;
                        me.selectTipoNumPor=data.desc_num === null ? 0 : data.desc_num;
                        me.monto=data.monto_descuento;
                           // Find the product with the matching id_prod_producto in arrayProductos
            let producto = me.arrayProductoX.find(producto => producto.id === data.id_prod);
            if (producto) {
                me.selected = producto;
            } else {
                me.selected = null;
            }
                   me.id_descuento_x=data.id_descuento_prod;
                   }

                   if(data.id_tipo_tabla==5){
                    me.tituloModal = "Registro personalizado";
                    me.nombre_Des=data.nombre_descuento;
                        me.descripcion_Des=data.descripcion;
                        me.selectTipoNumPor=data.desc_num === null ? 0 : data.desc_num;
                        me.monto=data.monto_descuento;
                        me.personalizado=data.max;
                        me.id_personalizado=data.id_personalizado;
                        me.id_descuento_x=data.id_descuento_personal;
                   }
                    me.classModal.openModal("registrar");

                    break;
                }

            case 'asignar':
                    {                                            
                         me.tituloModal='Asignar sucursal';
                         me.selectAlmTda2=me.arrayFalso;
                         if (me.selectTabla===3) {
                            me.id_index=0;
                         } else {
                            me.id_index=data.id;
                         }
                       
                        me.classModal.openModal('registrar1');
                   
                        break;

                    }
                    
            case 'vista_descuento':{
                me.tituloModal='Asignar sucursal';
                me.classModal.openModal('vista_descuento');
            }   

            }
        },
        cerrarModal(accion) {
            let me = this;
          
                me.classModal.closeModal(accion);               
                    me.tituloModal = " ";                        
                    me.nombre_Des="";
                    me.descripcion_Des="";
                    me.selectTipoNumPor=0;
                    me.monto="",            
                    me.selectCantidad_precio=0;
                    me.selectRegla=0;
                    me.cantidad_monto_x=0;
                    me.buscarCliente="";
                    me.buscarCliente_Nom="";
                    me.buscarResultado="";        
                    me.id_cliente="";
                    me.nombre_cliente="";
                    me.datos_cliente="";
                    me.num_cliente="";
                    me.selected=null;
                    me.personalizado=0;
                    me.id_personalizado="";
                    setTimeout(me.tiempo, 200); 
                    //me.ProductoLineaIngresoSeleccionado = 0;
                    me.inputTextBuscarProductoIngreso = "";
                        me.arrayRetornarProductosIngreso = "";
                        me.id_descuento_x="";
                        me.arrayDescuentoxSucursal=[];
            
        },

        
        cerrarModal1(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.tipoAccion=1;
               me.id_index="";
                me.arrayFalso=[];
                me.selectAlmTda2=[];
              me.arrayDescuentoxSucursal=[];              
            },
     
            listarDescuentoXtdaAlm(id)
            {
                   let me = this;               
                   if (typeof id!=="undefined") {
                    var url = "/descuento2/listarAsignar?id="+id;
             
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                   //  me.arrayDescuentoxSucursal = respuesta.datos.data;
                   me.arrayDescuentoxSucursal = respuesta;
                 
             
                   me.arrayAlmTienda.forEach(function(elemento1) {
  me.arrayDescuentoxSucursal.forEach(function(elementoInterno) {
    if (elemento1.codigo === elementoInterno.codigo) {
      me.arrayFalso.push({
        codigo: elemento1.codigo,
        id_sucursal: elemento1.id_sucursal,
        id_tienda_almacen: elemento1.id_tienda_almacen,
       
      });
    } 
  });
  
});

                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });  
                   }
            
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
        //-----------------------
        this.classModal = new _pl.Modals();
        this.listarAlmTienda();
        this.listarTipoTabla();
       this.listarIndex(1);
        this.listarProductoX();
        this.classModal.addModal("registrar");
        this.classModal.addModal('registrar1');
        this.listarDescuentoXtdaAlm();
        
        this.classModal.addModal("vista_descuento");
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
