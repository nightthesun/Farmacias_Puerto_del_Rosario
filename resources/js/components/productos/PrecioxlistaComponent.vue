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
                    <button type="button" class="btn btn-secondary"
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
                                <select class="form-control"  v-model="selectAlmTienda">
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
                    </div>
          
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                             
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                           <tr>
                            <h4 style="text-align: center;"> Sin datos123...</h4>
                          
                            <td>1</td>
                            <td>2</td>
                           
                           </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item">
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
                        <!-- insertar datos -->
                        <div class="container">
                         
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Envase:
                                    <span   class="error">(*)</span>
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
                                    <span   class="error">(*)</span>
                                </label>
                                <div class="col-md-9 input-group mb-3 ">
                                    <select name="" id="" v-model="selectProducto" @change="listarLista()"  class="form-control">
                                        <option v-bind:value="0" disabled>-Seleccionar... </option>
                                        <option
                                          v-for="prod in arrayProducto"
                                          :key="prod.id"
                                          v-bind:value="prod.id"
                                          v-text="prod.prod_cod+' '+prod.leyenda"
                                      ></option>
                                       
                                    </select>
                                    <button class="btn btn-primary" 
                                  v-if="tipoAccion == 1 || tipoAccion == 0"
                                  type="button" id="button-addon1"
                                  @click="abrirModal('bucarProducto');listarProductoRetorno();">                                           
                                                                                
                                      <i class="fa fa-search"></i>                                            
                                  </button> 
                                </div>
                                
                            </div>
                
                            <div class="form-group row" v-if="selectEnvase != 0 && selectProducto !=0">
                                      <label class="col-md-3 form-control-label" for="text-input">Lista
                                        <span   class="error">(*)</span>
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
                       
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="cerrarModal('registrar');cerrarModal('registrar_retorno');">Cerrar</button>
    
                    <button type="button" v-if="tipoAccion==1"  class="btn btn-primary" @click="registrarLisxPre()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" >Actualizar</button>
                   
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
                                            <th scope="col">id.</th>
                                            <th scope="col">codigo.</th>
                                            <th scope="col">linea.</th>
                                            <th scope="col">producto.</th>
                                         </tr>
                                    </thead>
                                    <tbody>  
                                       <tr v-for=" ret in arrayRetorno " :key="ret.id" @click="abrirModal('registrar_retorno',ret);" >
                                        <td v-text="ret.id"></td>
                                        <td v-text="ret.prod_cod"></td>
                                        <td v-text="ret.linea_name"></td>
                                        <td v-text="ret.leyenda"></td>
                                    
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
        data(){
            return{
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
            }
        },
        
       watch:{
        selectProducto: function(id){
            let prodcutoAbuscar = this.arrayProducto.find(
                    (element) => element.id === id);
                    if (prodcutoAbuscar) {  
                                          
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
       methods :{
       
        listarProductoRetorno(){
            let me = this;
            let parteCodigo = me.selectAlmTienda.substring(0, 3);
            var pase="";
            me.tiempopedidoselectedprimario= 12;
            if(parteCodigo==='ALM' || parteCodigo==='TDA'){
                

            var url ="/producto/listarProductoRetorno?codigo=" + parteCodigo + "&envase=" + me.selectEnvase+"&input="+me.inputTextBuscar;
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
                

            var url ="/producto/listarProducto?codigo=" + parteCodigo +
                    "&envase=" + me.selectEnvase;
                    axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProducto = respuesta;
                    me.selectProducto=0;
                    me.id_prod="";
                        me.linea="";
                        me.linea_cod="";
                        me.rubro="";
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
                //me.listarAlmacenes(page);
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

                       me.listarRecepcion();
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
                        
                        me.metodoabcEnvase="";
                        me.selectLista=0;
                        me.tiempopedidoselectedprimario=0;
                        me.metodoselectedprimario=0;
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'actualizar':
                        {
                            me.tipoAccion=2;
                            me.classModal.openModal('registrar');
                        }
                    case 'bucarProducto':{
                        me.inputTextBuscar="";
                        me.tipoAccion=1;
                        me.classModal.openModal('staticBackdrop');
                    break;
                    } 
                    case 'registrar_retorno':
                    {   
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
            cerrarModal(accion){
                let me = this;
                me.inputTextBuscar="";
                me.selectEnvase=0;
                        me.selectProducto=0;
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
                me.classModal.closeModal(accion);
                           
            },

       },
       
       mounted() {
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