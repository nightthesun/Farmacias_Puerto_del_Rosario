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
                    <i class="fa fa-align-justify"></i> Listas
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary"
                            @click="abrirModal('registrar')
                                   "
                                    :disabled="selectAlmTienda == 0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="selectAlmTienda == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span >
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align: center">
                           <label for="">Almacen o Tienda:</label>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control"  @change="listarPrecioxlista(1)" v-model="selectAlmTienda">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option
                                        v-for="sucursal in arrayAlmTienda"
                                        :key="sucursal.codigo"
                                        :value="sucursal.codigo"
                                        v-text="sucursal.codigoS +
                                            ' -> ' + sucursal.codigo+' ' +
                                            sucursal.razon_social"></option>
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
                                    @keyup.enter="listarPrecioxlista(1)"
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarPrecioxlista(1)"
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
          
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-1">Codigó</th>
                                <th class="col-md-1">Linea</th>
                                <th class="col-md-2">Producto</th>
                                <th class="col-md-1">Envase</th>
                                <th class="col-md-1">Lista</th>
                                <th class="col-md-1">Tiempo Pedido</th>
                                <th class="col-md-1">Precio Lista</th>
                                <th class="col-md-1">Precio Venta</th>
                                <th class="col-md-1">Metodo</th>                              
                                <th class="col-md-1">Estado</th>
                            </tr>
                        </thead>
                        <tbody v-if="selectAlmTienda == 0"></tbody>
                        <tbody v-if="selectAlmTienda != 0">
                            <tr v-for="p_xlist in arrayPrecioxlista" :key="p_xlist.id">
                              <td class="col-md-1">  
                                <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm"
                                            @click="cambioAccion();abrirModal('actualizar',p_xlist);listarLista();" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>    
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm"
                                                     style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                        </div>
                                        <div v-if="puedeActivar==1">
                                    <button  v-if="p_xlist.activo == 1" type="button"
                                   class="btn btn-danger btn-sm"
                                   @click="eliminar(p_xlist.id)" style="margin-right: 5px;">
                                   <i class="icon-trash"></i>
                               </button>
                               <button v-else
                                   type="button" class="btn btn-info btn-sm"
                                   @click="activar(p_xlist.id)" style="margin-right: 5px;">
                                   <i class="icon-check"></i>
                               </button> 
                                </div>                 
                                <div v-else>
                                    <button  v-if="p_xlist.activo == 1" type="button"
                                   class="btn btn-light btn-sm"
                                   style="margin-right: 5px;">
                                   <i class="icon-trash"></i>
                               </button>
                               <button v-else
                                   type="button" class="btn btn-light btn-sm"
                                  style="margin-right: 5px;">
                                   <i class="icon-check"></i>
                               </button> 
                                </div>
                                </div>                                      
                                
                                                             
                              </td>
                              <td class="col-md-1" v-text="p_xlist.codigo_prod"></td>
                              <td class="col-md-1" v-text="p_xlist.linea_name"></td>  
                              <td class="col-md-2" v-text="p_xlist.leyenda+' Cantidad:'+p_xlist.cantidad+''+p_xlist.cantidadEnvase+' Lote:'+p_xlist.lote"></td>
                              <td class="col-md-1" v-text="p_xlist.envase"></td> 
                              <td class="col-md-1" v-text="p_xlist.nombre_lista"></td>    
                              <td class="col-md-1">
                                <div v-for="t in tiempopedido">
                                    <span v-if="t.dato==p_xlist.tiempopedido1" v-text="t.tiempo"></span>
                                  </div>
                              </td>
                              <td class="col-md-1" v-text="p_xlist.preciolista"></td>
                              <td class="col-md-1" v-text="p_xlist.precioventa"></td> 
                              <td class="col-md-1">
                                <div v-for="m in arrayMetodo">
                                    <span v-if="m.letra==p_xlist.metodoabc" v-text="m.letra"></span>
                                  </div>
                              </td> 
                              <td class="col-md-1">
                                    <div v-if="p_xlist.activo==1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning">Desactivado</span>
                                    </div>
                                </td>                       
                            </tr>
                        </tbody>
                    </table>
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
        <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="registrar" aria-hidden="true" data-backdrop="static" data-key="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">{{ tituloModal }}</h4>
                    <button  type="button" class="close" aria-label="Close" @click="cerrarModal('registrar')">
                        <span aria-hidden="true">x</span>
                    </button>
                    
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        Todos los campos con (*) son requeridos
                    </div>
                    <form action="" class="form-horizontal">
                        <!-- insertar datos accion 1-->
                        <div class="container" v-if="tipoAccion==1">
                         
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Envase:
                                    <span v-if="selectEnvase == 0"  class="error">(*)</span>
                                </label>
                                <div class="col-md-9">
                                    <select name="" id="" v-model="selectEnvase" @change="listarProducto();" class="form-control">
                                        <option v-bind:value="0" disabled>Seleccior... </option>
                                        <option
                                          v-for="env in arrayEnvase"
                                          :key="env.envase"
                                          v-bind:value="env.envase"
                                          v-text="'tipo -> '+env.envase"
                                      ></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" v-if="selectEnvase != 0">
                                
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Producto:
                                    <span  v-if="selectProducto == 0" class="error">(*)</span>
                                </label>
                                <div class="col-md-9 input-group mb-3 ">
                                    <select name="" id="" v-model="selectProducto" @change="listarLista()"  class="form-control">
                                        <option v-bind:value="0" disabled>-Seleccionar... </option>
                                        <option
                                          v-for="prod in arrayProducto"
                                          :key="prod.id"
                                          v-bind:value="prod.id"
                                          v-text="prod.prod_cod+' '+prod.leyenda+' C:'+prod.cantidad+' L:'+prod.lote"
                                      ></option>
                                       
                                    </select>
                                    <button class="btn btn-primary" 
                                  v-if="tipoAccion== 1 || tipoAccion== 0"
                                  type="button" id="button-addon1"
                                  @click="abrirModal('bucarProducto');listarProductoRetorno();">                                           
                                                                                
                                      <i class="fa fa-search"></i>                                            
                                  </button> 
                                </div>
                                
                            </div>
                
                            <div class="form-group row" v-if="selectEnvase != 0 && selectProducto !=0">
                                      <label class="col-md-3 form-control-label" for="text-input">Lista
                                        <span v-if="selectLista == 0"  class="error">(*)</span>
                                      </label>
                                        <div class="col-md-9">
                                        <select name="" id="" v-model="selectLista"   class="form-control">
                                        <option v-bind:value="0" disabled>-Seleccionar... </option>
                                        <option
                                          v-for="list in arrayLista"
                                          :key="list.id"
                                          v-bind:value="list.id"
                                          v-text="list.codigo_lista+' '+list.nombre_lista">
                                        </option>
                                       
                                    </select>
                                      </div>
                                  </div>  
                            <div v-if="selectEnvase != 0 && selectProducto !=0">
                                <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Lista:</strong>     
                                                <input type="text" class="form-control" min="0" id="precioventaEnvase" v-model="preciolistaEnvase" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Precio de Venta:</strong>
                                                <input type="text" id="precioventaEnvase" min="0" class="form-control" v-model="precioventaEnvase" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                            </div>
                                         
                                            <div class="form-group col-sm-4">
                                                <strong>Ciclo de Pedido:</strong>
                                                <select v-model="tiempopedidoselectedprimario" class="form-control">
                                                    <option v-bind:value="0" disabled>-Seleccionar... </option>
                                                    <option v-for="tiempo in tiempopedido" :key="tiempo.dato" :value="tiempo.dato" v-text="tiempo.tiempo"></option>
                                                </select>
                                                <span class="error" v-if="tiempopedidoselectedprimario==0">Debe seleccionar el tiempo de pedido</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Clasificación ABC:</strong>
                                                <select v-model="metodoselectedprimario" class="form-control">
                                                    <option v-bind:value="0" disabled>-Seleccionar... </option>
                                                    <option v-for="metodo in arrayMetodo" :key="metodo.letra" :value="metodo.letra" v-text="metodo.letra"></option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Rubro:</strong>
                                                <input type="text"  class="form-control" v-model="rubro" disabled>
                                             
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Linea:</strong>
                                                <input type="text"  class="form-control" v-model="lineaS" disabled>
                                             
                                             </div>

                                        </div>
                            </div>     

       
                        </div>
                       <!--end de accion 1-->
                       
                         <!-- insertar datos accion 2-->
                         <div class="container" v-else>
                         
                         <div class="form-group row">
                             <label class="col-md-3 form-control-label" for="text-input">
                                 Envase:
                               
                             </label>
                             <div class="col-md-9">
                                <span class="form-control" style="background: #C2CFD6;">{{selectEnvase}}</span>
                                                     
                             </div>
                         </div>
                         <div class="form-group row">                             
                             <label class="col-md-3 form-control-label" for="text-input">
                                 Producto:
                               
                             </label>
                             <div class="col-md-9 input-group mb-3 ">
                                <span class="form-control" style="background: #C2CFD6;">{{codigo_prod}} {{selectProducto}}</span>
                           
                             
                             </div>
                             
                         </div>
             
                         <div class="form-group row" >
                                   <label class="col-md-3 form-control-label" for="text-input">Lista
                                     <span v-if="selectLista == 0"  class="error">(*)</span>
                                   </label>
                                     <div class="col-md-9">
                                     <select name="" id="" v-model="selectLista"   class="form-control">
                                     <option v-bind:value="0" disabled>-Seleccionar... </option>
                                     <option
                                       v-for="list in arrayLista"
                                       :key="list.id"
                                       v-bind:value="list.id"
                                       v-text="list.codigo_lista+' '+list.nombre_lista">
                                     </option>
                                    
                                 </select>
                                   </div>
                               </div>  
                         <div v-if="selectEnvase != 0 && selectProducto !=0">
                             <div class="row">
                                         <div class="form-group col-sm-4">
                                             <strong>Precio de Lista:</strong>     
                                             <input type="text" class="form-control" min="0" id="precioventaEnvase" v-model="preciolistaEnvase" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                         </div>
                                         <div class="form-group col-sm-4">
                                             <strong>Precio de Venta:</strong>
                                             <input type="text" id="precioventaEnvase" min="0" class="form-control" v-model="precioventaEnvase" step="any" v-on:focus="selectAll" style="text-align:right" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 )">
                                         </div>
                                      
                                         <div class="form-group col-sm-4">
                                             <strong>Ciclo de Pedido:</strong>
                                             <select v-model="tiempopedidoselectedprimario" class="form-control">
                                                 <option v-bind:value="0" disabled>-Seleccionar... </option>
                                                 <option v-for="tiempo in tiempopedido" :key="tiempo.dato" :value="tiempo.dato" v-text="tiempo.tiempo"></option>
                                             </select>
                                             <span class="error" v-if="tiempopedidoselectedprimario==0">Debe seleccionar el tiempo de pedido</span>
                                         </div>
                                     </div>
                                     <div class="row">
                                         <div class="form-group col-sm-4">
                                             <strong>Clasificación ABC:</strong>
                                             <select v-model="metodoselectedprimario" class="form-control">
                                                 <option v-bind:value="0" disabled>-Seleccionar... </option>
                                                 <option v-for="metodo in arrayMetodo" :key="metodo.letra" :value="metodo.letra" v-text="metodo.letra"></option>
                                             </select>
                                         </div>
                                         <div class="form-group col-sm-4">
                                             <strong>Rubro:</strong>
                                             <input type="text"  class="form-control" v-model="rubro" disabled>
                                          
                                         </div>
                                         <div class="form-group col-sm-4">
                                             <strong>Linea:</strong>
                                             <input type="text"  class="form-control" v-model="lineaS" disabled>
                                          
                                          </div>

                                     </div>
                         </div>     

    
                     </div>
                       <!--- end de accion 2-->
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar');cerrarModal('registrar_retorno');">Cerrar</button>
    
                    <button type="button" v-if="tipoAccion==1"  class="btn btn-primary" @click="registrarLisxPre()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
                   
                </div>
                </div>
                
            </div>
        </div>
        <!--fin del modal-->

               <!-- Modal para la busqueda de producto por lote -->
 <div class="modal fade" id="staticBackdrop" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-primary">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Busqueda de producto</h5>
                    <button type="button" @click="cerrarModal('staticBackdrop')" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Introdusca descripcion a buscar: </label>
                            <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" 
                            v-model="inputTextBuscar"
                               @input="listarProductoRetorno()">
                            <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                        </div>
                        <div>
                            <table class="table table-hover" id="tablaProductosIngreso"  style="width: 100%; display: block; overflow-y: auto;">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID.</th>
                                            <th scope="col">Codigo.</th>
                                            <th scope="col">Linea.</th>
                                            <th scope="col">Producto.</th>
                                            <th scope="col">Lote.</th>
                                            <th scope="col">Cantidad.</th>
                                         </tr>
                                    </thead>
                                    <tbody>  
                                       <tr v-for=" ret in arrayRetorno " :key="ret.id" @click="abrirModal('registrar_retorno',ret);" >
                                        <td v-text="ret.id"></td>
                                        <td v-text="ret.prod_cod"></td>
                                        <td v-text="ret.linea_name"></td>
                                        <td v-text="ret.leyenda"></td>
                                        <td v-text="ret.lote"></td>
                                        <td v-text="ret.cantidad"></td>
                                       </tr>
                                      
                                    </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    
                 
                    <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" data-bs-dismiss="modal" @click="cerrarModal('staticBackdrop');abrirModal('registrar');" >Cerrar</button>
     
                    
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
                </div>
            </div>
     </div>
     <!---fin de modal 2-->
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import { error401 } from '../../errores';
     //Vue.use(VeeValidate);
     export default{
        //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
        data(){
            return{
                pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            offset:3,
                tituloModal:'',    
                tipoAccion:1,   
                
                selectAlmTienda:0,
                arrayAlmTienda:[],
                selectEnvase:0,
                arrayEnvase:[{id:1,envase:'primario'},
                            {id:2,envase:'secundario'},
                            {id:3,envase:'terciario'}],
                arrayProducto:[],
                selectProducto:0,
                id_prod:'',
                rubro:'',
                linea:'',
                linea_cod:'',
                lineaS:'',
                selectTiempo:0,
                tiempopedido:[{'id':1,'dato':'1','tiempo':'un mes'},
                                {'id':2,'dato':'3','tiempo':'tres meses'},
                                {'id':3,'dato':'6','tiempo':'seis meses'},
                                {'id':4,'dato':'12','tiempo':'doce meses'}],
                selectMetodo:'A',
                arrayMetodo:[{'id':1,'letra':'A'},{'id':2,'letra':'B'},{'id':3,'letra':'C'}],
                precioventaEnvase:'',
                preciolistaEnvase:'',
                tiempopedidoEnvase:'',
                metodoabcEnvase:'',
                selectLista:0,
                arrayLista:[],

                tiempopedidoselectedprimario:0,
                metodoselectedprimario:0,
                inputTextBuscar:'',
                arrayRetorno:[],

                lista_id_almacen_id_tienda:'',
                codigo_tda_alm:'',
                tipoCodigo:'',
                arrayPrecioxlista:[],
                buscar:'',
                codigo_prod:'',
                id_pre_x:'',
                tipo_tienda_almacen:'',
                id_ingreso:'',
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
            }
        },
        
       watch:{
        selectProducto: function(id){
            let prodcutoAbuscar = this.arrayProducto.find(
                    (element) => element.id === id);
                    if (prodcutoAbuscar) {  
                        this.id_ingreso=prodcutoAbuscar.id_ingreso;               
                        this.id_prod=prodcutoAbuscar.id;
                       this.rubro=prodcutoAbuscar.rubro_name;
                       this.linea_cod=prodcutoAbuscar.linea_cod;
                       this.linea=prodcutoAbuscar.linea_name;
                       this.lineaS=prodcutoAbuscar.lineaS;
                       this.preciolistaEnvase=prodcutoAbuscar.preciolistaEnvase;
                       this.precioventaEnvase=prodcutoAbuscar.precioventaEnvase;
                       this.tiempopedidoEnvase=prodcutoAbuscar.tiempopedidoEnvase;
                       this.metodoabcEnvase=prodcutoAbuscar.metodoabcEnvase;
                       this.tiempopedidoselectedprimario=this.tiempopedidoEnvase;
                       this.metodoselectedprimario=this.metodoabcEnvase;
                    }
        },
        selectAlmTienda: function(codigo){
            let alm_tda_buscar = this.arrayAlmTienda.find(
                    (element) => element.codigo  === codigo);
                    if (alm_tda_buscar) {  
                                          
                       this.lista_id_almacen_id_tienda=alm_tda_buscar.lista_id_almacen_id_tienda;
                       this.codigo_tda_alm=alm_tda_buscar.codigo;
                       this.tipoCodigo=alm_tda_buscar.tipoCodigo;
                  
                    }
        }
       }, 
    computed: {
      sicompleto() {
      let me = this;
           if (
          
               me.selectEnvase != 0 &&
               me.selectProducto != 0 &&
               me.selectLista !=0
            )
             return true;
           else return false;
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

        cambioAccion(){
        let me = this;
        
        me.tipoAccion=2;
        
       },
        listarPrecioxlista(page){
            let me=this;
                var url='/producto/index?page='+page+'&buscar='+me.buscar+'&buscarAlmTdn='+me.selectAlmTienda;
             
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayPrecioxlista = respuesta.resultadocombinado.data;
                })
                .catch(function(error){
                    error401(error);
                });
        },

        listarProductoRetorno(){
            let me = this;
            let parteCodigo = me.selectAlmTienda.substring(0, 3);
            var pase="";
            me.tiempopedidoselectedprimario= 12;
            if(parteCodigo==='ALM' || parteCodigo==='TDA'){
                

            var url ="/producto/listarProductoRetorno?codigo=" + parteCodigo + "&envase=" + me.selectEnvase+"&input="+me.inputTextBuscar+"&alm_tda="+me.selectAlmTienda;
                    axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayRetorno = respuesta;                    
                 
                })                    
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
            }
            else{
                error401(error);
                console.log(error); 
            } 
        },
        listarLista(){
            let me = this;
            var url = "/producto/listarLista?codigo="+me.selectAlmTienda+"&envase=" + me.selectEnvase;
       
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
        listarProducto() {
            let me = this;
            let parteCodigo = me.selectAlmTienda.substring(0, 3);
            var pase="";
          
            me.tiempopedidoselectedprimario= 12;
            if(parteCodigo==='ALM' || parteCodigo==='TDA'){                

            var url ="/producto/listarProducto?codigo="+parteCodigo+
                    "&envase=" + me.selectEnvase+"&alm_tda="+me.selectAlmTienda;
                    console.log(url);
                    axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProducto = respuesta;
                    if(me.tipoAccion==1){
                        me.selectProducto=0;
                    me.id_prod="";
                        me.linea="";
                        me.linea_cod="";
                        me.rubro="";
                    }
                    
                })
                    
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
            }
            else{
                error401(error);
                console.log(error); 
            } 
            
         
        },
        listarAlmTienda() {
            let me = this;
            var url = "/producto/listarSucursal";
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
        cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarPrecioxlista(page);
            },
    registrarLisxPre() {
            let me = this;
            var numero_lista = 0;
            var numero_venta = 0; 

    if (!isNaN(parseFloat(me.preciolistaEnvase))) {
        // Redondear a dos decimales
        numero_lista = parseFloat(me.preciolistaEnvase).toFixed(2);
        } else {
        numero_lista = 'string';
        }
    if (!isNaN(parseFloat(me.precioventaEnvase))) {
        // Redondear a dos decimales
        numero_venta = parseFloat(me.precioventaEnvase).toFixed(2);
        } else {
            numero_venta = 'string';
        }


      if (me.selectLista===0 || me.selectLista === "" ||  me.precioventaEnvase==="" || me.preciolistaEnvase==="" || me.tiempopedidoEnvase===""
                || me.metodoselectedprimario==="" || numero_lista ==="string" || numero_venta === "string") {
                Swal.fire(
                    "No puede ingresar valor vacios.",
                    "Haga click en Ok",
                    "warning",
                );
            } else { 
                   axios                   
                     .post("/producto/registrarLista", {
                        'id_ingreso':me.id_ingreso,
                        'envase': me.selectEnvase,
                        'id_producto':me.id_prod,  
                        'id_lista':me.selectLista,  
                        'id_tda_alm':me.lista_id_almacen_id_tienda,  
                        'cod_tda_alm':me.codigo_tda_alm, 
                        'tipo_tda_alm':me.tipoCodigo,   
                        'preciolista':numero_lista,
                        'precioventa':numero_venta, 
                        'tiempopedido':me.tiempopedidoselectedprimario, 
                        'metodoabc':me.metodoselectedprimario,        
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Se registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );

                       me.listarPrecioxlista();
                        me.listarAlmTienda();
                    })
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
             }
        },    
        actualizar() {
            let me = this;
            var numero_lista = 0;
            var numero_venta = 0; 

    if (!isNaN(parseFloat(me.preciolistaEnvase))) {
        // Redondear a dos decimales
        numero_lista = parseFloat(me.preciolistaEnvase).toFixed(2);
        } else {
        numero_lista = 'string';
        }
    if (!isNaN(parseFloat(me.precioventaEnvase))) {
        // Redondear a dos decimales
        numero_venta = parseFloat(me.precioventaEnvase).toFixed(2);
        } else {
            numero_venta = 'string';
        }
          if (me.selectLista===0 || me.selectLista === ""  || me.tiempopedidoselectedprimario===""
                || me.metodoselectedprimario==="" || numero_lista ==="string" || numero_venta === "string") {
                Swal.fire(
                    "No puede ingresar valor vacios.",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                axios
                .put("/producto/actualizarListaX", {
            
                    id: me.id_pre_x,
                    tipo_tienda_almacen:me.tipo_tienda_almacen,
                    id_lista:me.selectLista,  
                    preciolista:numero_lista,
                    precioventa:numero_venta, 
                    tiempopedido:me.tiempopedidoselectedprimario, 
                    metodoabc:me.metodoselectedprimario 
                })
                .then(function (response) {
                    me.listarPrecioxlista();
                        me.listarAlmTienda();
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
            me.cerrarModal("registrar");
            }
          

        },
        abrirModal(accion,data= []){
            let me=this;
           
                switch(accion){
                    case 'registrar':
                    {   
                       me.tipoAccion=1;
                        me.tituloModal='Lista de precios en '+me.selectAlmTienda;
                        me.selectEnvase=0;
                        me.selectProducto=0;
                        me.id_prod="";
                        me.linea="";
                        me.linea_cod="";
                        me.rubro="";
                        me.lineaS="";
                        me.preciolistaEnvase="";
                        me.precioventaEnvase="";
                        me.codigo_prod="";
                        me.metodoabcEnvase="";
                        me.selectLista=0;
                        me.tiempopedidoselectedprimario=0;
                        me.metodoselectedprimario=0;
                        me.id_ingreso="";
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'actualizar':
                        {
                            me.tipoAccion=2;
                            me.id_ingreso="";                       
                            me.tituloModal='Lista de precios en '+me.selectAlmTienda;                            
                           
                            me.selectEnvase=data.envase;                         
                            me.selectProducto=data.leyenda;                           
                            me.selectLista=data.id_lista==null?0:data.id_lista;
                            me.id_prod=data.id_producto;
                            me.linea="";
                            me.linea_cod="";
                            me.rubro=data.rubro_name;
                            me.lineaS=data.linea_name;
                            me.preciolistaEnvase=data.preciolista;
                            me.precioventaEnvase=data.precioventa;
                            me.codigo_prod=data.codigo_prod;
                            me.metodoabcEnvase=data.metodoabc;
                            me.id_pre_x=data.id;
                            me.tipo_tienda_almacen=data.tipo_tienda_almacen;
                            me.tiempopedidoselectedprimario=data.tiempopedido1==null?0:data.tiempopedido1;
                            
                            me.metodoselectedprimario=data.metodoabc==null?0:data.metodoabc;
                            me.classModal.openModal('registrar');
                          
                            break;
                        }
                    case 'bucarProducto':{
                        me.inputTextBuscar="";
                        me.tipoAccion=1;
                        me.classModal.openModal('staticBackdrop');
                    break;
                    } 
                    case 'registrar_retorno':
                    {   
                        me.id_ingreso="";
                        me.tipoAccion=1;
                        this.listarLista();
                        me.tituloModal='Lista de precios en '+me.selectAlmTienda;
                        me.selectEnvase=data.tipoE;
                        me.selectProducto=data.id;
                        me.id_prod="";
                        me.linea="";
                        me.linea_cod="";
                        me.rubro="";
                        me.lineaS="";
                        me.preciolistaEnvase="";
                        me.precioventaEnvase="";
                        
                        me.metodoabcEnvase="";
                        me.selectLista=0;
                        me.tiempopedidoselectedprimario=0;
                        me.metodoselectedprimario=0;
                        
                        me.classModal.openModal('registrar');
                        break;
                    }  
                }                
            },
            eliminar(id_pre_x){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/producto/desactivarListaX',{
                        'id': id_pre_x
                    }).then(function (response) {
                        me.listarPrecioxlista();
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
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
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                }
                })
            },
            activar(id_pre_x){
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
                     axios.put('/producto/activarListaX',{
                        'id': id_pre_x
                    }).then(function (response) {
                        me.listarPrecioxlista();
                    
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
            cerrarModal(accion){
                let me = this;
                me.inputTextBuscar="";
                me.selectEnvase=0;
                me.tipoAccion=1;
                me.id_pre_x="";
                me.id_ingreso="";
                        me.tipo_tienda_almacen="";
                        me.selectProducto=0;
                        me.id_prod="";
                        me.linea="";
                        me.linea_cod="";
                        me.rubro="";
                        me.lineaS="";
                        me.preciolistaEnvase="";
                        me.precioventaEnvase="";
                        me.codigo_prod="";
                        me.metodoabcEnvase="";
                        me.selectLista=0;
                        me.tiempopedidoselectedprimario=0;
                        me.metodoselectedprimario=0;
                me.classModal.closeModal(accion);
                           
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
        //      this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
        this.classModal = new _pl.Modals();
        this.classModal.addModal("staticBackdrop");
        this.listarAlmTienda();
        this.classModal.addModal('registrar');
 
        
       }
     }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>