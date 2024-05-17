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
                    <i class="fa fa-align-justify"></i> Recepcion               
                    <button v-if="puedeCrear==1"
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');listarTraspaso();"
                        :disabled="selectAlmTienda == 0"
                    >
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
                                <select
                                    class="form-control"
                                    @change="listarRecepcion(1)"
                                  v-model="selectAlmTienda"
                                >
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option
                                        v-for="sucursal in arrayAlmTienda"
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
                                    @keyup.enter="listarRecepcion(1)"
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarRecepcion(1)"
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>

            </div>
            <!--inicio de tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Nro. Traspaso</th>
                        <th class="col-md-5">Producto</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th >Origen</th>
                        <th class="col-md-2">Glosa</th>
                        <th>Vehiculo</th>
                        <th class="col-md-2">Observación</th>
                        <th class="col-md-2">Per. Enviada</th>
                        <th>Usuario</th>
                        <th>Estado</th> 
                    </tr>
                </thead>
                <tbody v-if="selectAlmTienda == 0"></tbody>
                    <tbody v-if="selectAlmTienda != 0">
                        <tr v-for="rec in arrayRecepcion" :key="rec.id"> 
                            <td>
                                <div  class="d-flex justify-content-start">
                                        <div v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',rec); listarTraspaso();" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="rec.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminar(rec.id)" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activar(rec.id)" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="rec.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button>
                                        </div>
                                </div>            
                               
                                    
                                   

                             
                            </td>
                            <td class="col-md-1" v-text="rec.numero_traspaso"></td>
                            <td class="col-md-5" v-text="rec.leyenda"></td>
                            <td v-text="rec.cantidad"></td>
                            <td v-text="rec.fecha"></td>
                            <td v-text="rec.name_ori"></td>
                            <td class="col-md-2" v-text="rec.glosa"></td>
                            <td v-text="rec.name_vehiculo"></td>
                            <td class="col-md-2" v-text="rec.rec_observacion"></td>
                            <td class="col-md-2" v-text="rec.nom_completo"></td>
                            <td v-text="rec.user_name"></td>
                            <td>
                                 <div v-if="rec.activo==1">
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
                                
                                <div class="form-group row">
                              
                              <label class="col-md-3 form-control-label" for="text-input">
                              <strong>Traspaso:</strong> 
                                  <span v-if="selectTraspaso == '0'" class="error">(*)</span>
                              </label>
                              <div class="col-md-7 input-group mb-3">
                                  <select v-model="selectTraspaso" v-if="tipoAccion==1"
                                      class="form-control">
                                      <option v-bind:value="0" disabled>
                                          Seleccionar...
                                      </option>
                                      <option
                                          v-for="traspasoI in arrayTraspaso"
                                          :key="traspasoI.id"
                                          v-bind:value="traspasoI.id"
                                          v-text="'Nro:'+traspasoI.numero_traspaso +
                                              ' | Des: ' +traspasoI.name_des +
                                              ' | ' +traspasoI.leyenda +
                                              ' | C: ' + traspasoI.cantidad  "
                                      ></option>
                                  </select>
                                  
                                  <button class="btn btn-primary" 
                                  v-if="tipoAccion == 1"
                                  type="button" id="button-addon1"
                                  @click="abrirModal('bucarProductoIngreso');listarRetornoTraspaso();">                                           
                                                                                
                                      <i class="fa fa-search"></i>                                            
                                  </button>  
                                  <div v-if="tipoAccion == 2">
                                            <strong>Nro:{{numero_traspaso}}|{{leyenda}}| Destino:{{destino}}|Cantidad:{{cantidad}}</strong>
                                        </div>
                                
                               </div>
                                  <!-- debe ingresar los datos de para asignar datos del array-->
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Origen: <span>{{ origen }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Numero de traspaso: <span>{{ numero_traspaso }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Producto: <span>{{ leyenda }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Cantidad: <span>{{ cantidad }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Operario de logística: <span>{{ nom_completo }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Vehiculo: <span>{{ name_vehiculo }}</span></strong>   
                                  </div>

                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Tiempo estimado de entrega: 
                                        <span  >{{tiempo}}</span>
                                     
                                    </strong>   
                                  </div>
          
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!='' &&estado==1">
                                    <strong>Estado: 
                                        <span  style="color: green;">Listo para concluir</span>
                                     
                                    </strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!='' &&estado==0">
                                    <strong>Estado: 
                                        <span  style="color: red;">Pendiente</span>
                                    </strong>   
                                  </div>
                                </div>
                                <div class="form-group row" v-if="selectTraspaso!='' &&estado==1 &&tipoAccion==1 ">
                                      <label class="col-md-3 form-control-label" for="text-input"><strong>Observación: </strong> 
                                        <span v-if="observacion == ''" class="error">(*)</span>
                                      </label>
                                      <div class="col-md-7">
                                        <textarea id="" name="" v-model="observacion" class="form-control" placeholder="Debe ingresar una observación"></textarea>
                                        </div>
                                  </div>  
                                  <div class="form-group row" v-if="selectTraspaso!='' &&tipoAccion==2 ">
                                      <label class="col-md-3 form-control-label" for="text-input"><strong>Observación: </strong> 
                                        <span v-if="observacion == ''" class="error">(*)</span>
                                      </label>
                                      <div class="col-md-7">
                                        <textarea id="" name="" v-model="observacion" class="form-control" placeholder="Debe ingresar una observación"></textarea>
                                        </div>
                                  </div> 
                                    <!---
                                  <div class="row justify-content-center" v-if="selectTraspaso!='' &&estado==1  &&tipoAccion==1">
                                    <input type="checkbox" id="checkbox" v-model="checked" hidden/>
                                    <label for="checkbox" v-if="checked === false" style="background-color: rgb(51, 118, 145); color: white; border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                                     <strong>Aceptar</strong>
                                     </label>
                                     <label for="checkbox" v-else style="background-color: rgb(122, 30, 45); color: white; border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                                     <strong>Deshacer</strong>
                                     </label>
                                  </div>  
                                
                                    <div class="row justify-content-center" v-if="selectTraspaso!='' &&tipoAccion==2">
                                    <input type="checkbox" id="checkbox" v-model="checked" hidden/>
                                    <label for="checkbox" v-if="checked === false" style="background-color: rgb(51, 118, 145); color: white; border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                                     <strong>Aceptar</strong>
                                     </label>
                                     <label for="checkbox" v-else style="background-color: rgb(122, 30, 45); color: white; border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                                     <strong>Deshacer</strong>
                                     </label>
                                  </div>  
                                    --->
                                  
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
                            v-if="tipoAccion == 1 && estado==1"
                            class="btn btn-primary"
                            @click="registrar()"
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
         <!-- Modal para la busqueda de producto por lote -->
 <div class="modal fade" id="staticBackdrop" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-primary">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Busqueda de Traspasos</h5>
                    <button type="button" @click="cerrarModal('staticBackdrop')" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Introdusca descripcion a buscar: </label>
                            <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" 
                            v-model="inputTextBuscar"
                               @input="listarRetornoTraspaso()">
                            <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                        </div>
                        <div>
                            <table class="table table-hover" id="tablaProductosIngreso"  style='height:350px;display:block;overflow:scroll'>
                                    <thead>
                                        <tr>
                                            <th scope="col">Nro Traspaso.</th>
                                            <th scope="col">Descripcion Prod.</th>
                                            <th scope="col">Cantidad.</th>
                                       </tr>
                                    </thead>
                                    <tbody>  
                                        <tr v-for="RetornarProductosIngreso  in arrayRetornarTraspaso" :key="RetornarProductosIngreso.id" @click="abrirModal('registrar',RetornarProductosIngreso);listarRetornoTraspaso();" >
                                        <td v-text="RetornarProductosIngreso.numero_traspaso"></td>
                                        <td v-text="RetornarProductosIngreso.leyenda"></td>
                                        <td v-text="RetornarProductosIngreso.cantidad"></td>
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
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import { resolveTransitionHooks } from "vue";
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
            offset:3,

            tituloModal: "",
            selectAlmTienda:0,
            arrayAlmTienda:[],
            buscar:"",
            tipoAccion:1,
            cod_alm_tienda:"",
            razon_socialAlmTienda:"",
            selectTraspaso:0,
            arrayTraspaso:[],
            inputTextBuscar:"",
            arrayRetornarTraspaso:[],
            tipoEvento:1,
            ////////////
            id_traspaso:"",
            numero_traspaso:"",
            leyenda:"",
            origen:"",
            cantidad:"",
            id_traslado:"",
            nom_completo:"",
            name_vehiculo:"",
            estado:"",
            observacion:"",
            checked:"",
            id_ingreso:"",
            cod_1:"",
            cod_2:"",
            tiempo:"",
            id_prod_producto:"",
            envase:"",
            id_almacen_tienda:"",
            id_tipoentrada:"",
            fecha_vencimiento:"",
            lote:"",
            registro_sanitario:"",
            cod_prod:"",
            linea_name:"",
            name_prod:"",
            fecha_vencimiento:"",
            lote:"",
            id_sucursal:"",
            leyenda:"",
            //------
            arrayRecepcion:[],
            id_recepcion:"",
            destino:"",
            id_destino:"",
             //---permisos_R_W_S
             puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
          
        };
    },

    watch:{
        selectAlmTienda: function(cod){
           
            let tiendaOalmacenSeleccionado = this.arrayAlmTienda.find(
                    (element) => element.codigo === cod);
                    if (tiendaOalmacenSeleccionado) {                        
                        this.cod_alm_tienda=tiendaOalmacenSeleccionado.codigo;
                        this.razon_socialAlmTienda=tiendaOalmacenSeleccionado.razon_social;
                       
                    }
        },
        selectTraspaso: function(newValue){
            let traspasO = this.arrayTraspaso.find(
                    (element) => element.id === newValue,
                );
              if(traspasO){              
                this.id_traspaso=traspasO.id;
                this.numero_traspaso=traspasO.numero_traspaso;            
                this.leyenda=traspasO.leyenda;
                this.origen=traspasO.name_ori; 
                this.cantidad=traspasO.cantidad;  
                this.id_traslado=traspasO.id_traslado;  
                this.nom_completo=traspasO.nom_completo;    
                this.name_vehiculo=traspasO.name_vehiculo;   
                this.estado=traspasO.estado;   
                this.observacion="";
                this.checked=false;                   
                this.id_ingreso=traspasO.id_ingreso;            
                this.cod_1=traspasO.cod_1;
                this.cod_2=traspasO.cod_2;
                this.tiempo=traspasO.tiempo;
                this.id_prod_producto=traspasO.id_prod_producto;
                this.envase=traspasO.envase;
                this.id_almacen_tienda=traspasO.id_almacen_tienda;
                this.id_tipoentrada=traspasO.id_tipoentrada;  
                this.fecha_vencimiento=traspasO.fecha_vencimiento;
                this.lote=traspasO.lote; 
                this.registro_sanitario=traspasO.registro_sanitario;  
                this.cod_prod=traspasO.cod_prod;
                this.linea_name=traspasO.linea_name;
                this.name_prod=traspasO.name_prod;
                this.fecha_vencimiento=traspasO.fecha_vencimiento;
                this.lote=traspasO.lote;
                this.id_sucursal=traspasO.id_sucursal;
                this.destino=traspasO.name_des;
                this.id_destino=traspasO.id_destino;
                
        
              }  
        }   
    },

    computed: {
      sicompleto() {
      let me = this;
           if (
          
               me.selectTraspaso != "" &&
               me.observacion != ""
           )
             return true;
           else return false;
       },
        isActived: function () {
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

        listarRecepcion(page){
            let me=this;
                var url='/recepcion?page='+page+'&buscar='+me.buscar+'&buscarAlmTdn='+me.selectAlmTienda;
             
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayRecepcion = respuesta.resultadoCombinacion.data;
                })
                .catch(function(error){
                    error401(error);
                });
        },
        listarRetornoTraspaso() {
            let me = this;
                var url ="/recepcion/listarRetornoTraspaso?codigo=" + me.cod_alm_tienda +
                    "&input=" + me.inputTextBuscar;
                         
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayRetornarTraspaso = respuesta;})
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        listarTraspaso() {
            if (this.cod_alm_tienda!="") {
                let me = this;            
     
            var url = "/recepcion/listarTraspaso?codigo="+me.cod_alm_tienda;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayTraspaso = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });  
            } 
        },
        listarAlmTienda() {
            let me = this;
            //var url = "/recepcion/listarSucursal";
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
           me.listarRecepcion(page);
        },
        registrar() {
            let me = this;
      if (me.selectTraspaso===0 || me.observacion === "" ) {
                Swal.fire(
                    "No puede ingresar valor vacios.",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                
               
                   axios
                    //axios.get(url)
                     .post("/recepcion/registrar", {
                       'id_traslado': me.id_traslado,
                        'id_ingreso': me.id_ingreso,
                        'cod_1': me.cod_1,
                        'cod_2': me.cod_2,
                        'cantidad': me.cantidad,
                        'lote':me.lote,
                        'registro_sanitario':me.registro_sanitario,
                        'id_tipoentrada':me.id_tipoentrada,
                          'id_prod_producto':me.id_prod_producto,
                          'envase':me.envase,
                          'name_prod':me.name_prod,
                     'id_almacen_tienda':me.id_almacen_tienda,
                      'cod_prod':me.cod_prod,
                     'linea_name':me.linea_name, 
                    'fecha_vencimiento':me.fecha_vencimiento,     
                     'id_sucursal':me.id_sucursal,      
                    'leyenda':me.leyenda,    
                    'observacion':me.observacion,
                    'numero_traspaso':me.numero_traspaso,
                    'id_traspaso':me.id_traspaso,
                    'destino':me.destino,
                    'id_destino':me.id_destino,
                
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
                        error401(error);
                        console.log(error);
                    });
                
               
            }
        },
        actualizar() {
            let me = this;
          
            axios
                .put("/recepcion/actualizar", {
                    id: me.id_recepcion,                   
                    observacion:me.observacion,                     
                })
                .then(function (response) {                  
                    me.listarRecepcion();
                        me.listarAlmTienda();
               
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
        },    

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de traspaso: "+me.razon_socialAlmTienda;
                    if(this.tipoEvento==1){
                    me.selectTraspaso=0;
                   }
                   if(this.tipoEvento==2){
                    me.selectTraspaso=data.id=== null ?0:data.id;
                   }
                   //
                   me.id_traspaso="";
                   me.numero_traspaso="";
                   me.leyenda="";
                   me.origen="";
                   me.cantidad="";
                   me.id_traslado="";
                   me.nom_completo="";
                   me.name_vehiculo="";
                   me.estado="";
                   me.observacion="";
                   me.checked=false;

                   me.id_ingreso="";            
                   me.cod_1="";
                   me.cod_2="";
                   me.tiempo="";
                   me.id_prod_producto="";
                   me.envase="";
                   me.id_almacen_tienda="";
                   me.id_tipoentrada="";
                   me.fecha_vencimiento="";
                   me.lote="";
                   me.registro_sanitario="";
                   me.cod_prod="";
                   me.linea_name="";
                   me.name_prod="";
                   me.fecha_vencimiento="";
                   me.id_destino="";
                   me.id_sucursal="";
                   me.id_recepcion="";
                   me.destino="";
                   me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
            
                    me.tipoAccion = 2;
                    me.id_recepcion= data.id;
                    me.selectTraspaso=data.id_traslado === null ? 0:data.id_traslado ;
                    me.tituloModal = "Registro de traspaso: "+me.razon_socialAlmTienda;          
                    me.id_traspaso=data.id_traspaso;
                   me.numero_traspaso=data.numero_traspaso;
                   me.leyenda=data.leyenda;
                   me.origen=data.name_ori;
                   me.cantidad=data.cantidad;
                   me.id_traslado=data.id_traslado;
                   me.nom_completo=data.nom_completo;
                   me.name_vehiculo=data.name_vehiculo;
                   me.estado=data.estado;
                   me.observacion=data.rec_observacion;
                   me.checked=false;
                   me.id_ingreso=data.id_ingreso;            
                   me.cod_1=data.cod_1;
                   me.cod_2=data.cod_2;
                   me.tiempo=data.tiempo;
                   me.id_prod_producto=data.id_prod_producto;
                   me.envase=data.envase;
                   me.id_almacen_tienda=data.id_almacen_tienda;
                   me.id_tipoentrada=data.id_tipoentrada;
                   me.fecha_vencimiento=data.fecha_vencimiento;
                   me.lote=data.lote;
                   me.registro_sanitario=data.registro_sanitario;
                   me.cod_prod=data.cod_prod;
                   me.linea_name=data.linea_name;
                   me.name_prod=data.name_prod;
                   me.fecha_vencimiento=data.fecha_vencimiento;
                   me.id_sucursal=data.id_sucursal;
                   me.destino=data.name_des;
                   me.classModal.openModal("registrar");
                   
                    

                    break;
                }
                case 'bucarProductoIngreso':{
                    me.tipoEvento=2;  
                    me.inputTextBuscar= "";
                    //me.arrayRetornarTraspaso = [];
                    me.classModal.openModal('staticBackdrop');
                    break;
                }
            
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.tipoEvento=1;
                me.tipoAccion = 1;
                me.selectTraspaso=0;
                    me.tituloModal = " ";
                    me.id_traspaso="";
                   me.numero_traspaso="",
                   me.leyenda="",
                   me.origen="",
                   me.cantidad="",
                   me.id_traslado="",
                   me.nom_completo="",
                   me.name_vehiculo="",
                   me.estado="",
                  
                   me.checked="",
                   me.id_ingreso="";            
                   me.cod_1="";
                   me.cod_2="";
                   me.tiempo="";
                   me.id_prod_producto="";
                   me.envase="";
                   me.id_almacen_tienda="";
                   me.id_tipoentrada="";
                   me.fecha_vencimiento="";
                   me.lote="";
                   me.registro_sanitario="";
                   me.cod_prod="";
                   me.linea_name="";
                   me.name_prod="";
                   me.fecha_vencimiento="";
                   me.lote="";
                   me.id_sucursal="";
                   me.observacion="";
                   me.id_recepcion="";
                   me.id_destino="";
                    setTimeout(me.tiempo, 200); 
                    //me.ProductoLineaIngresoSeleccionado = 0;
                    me.inputTextBuscarProductoIngreso = "";
                        me.arrayRetornarProductosIngreso = "";
              
            } else {
                me.classModal.closeModal(accion);
                me.tipoEvento=1;
                me.selectTraspaso=0;
                me.classModal.openModal("registrar");
            }
        },
        eliminar(id_recepcion){
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
                     axios.put('/recepcion/desactivar',{
                        id: id_recepcion
                    }).then(function (response) {
                        me.listarRecepcion();
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
        activar(id_recepcion){
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
                     axios.put('/recepcion/activar',{
                        id: id_recepcion
                    }).then(function (response) {
                        me.listarRecepcion();
                    
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
        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
         //-------permiso E_W_S-----
         this.listarPerimsoxyz();
        // this.listarAlmacenes_tiendas_con_permisos();
        //-----------------------
        this.classModal = new _pl.Modals();
        this.listarAlmTienda();
        this.classModal.addModal("registrar");
        this. listarTraspaso();
        this.classModal.addModal("staticBackdrop");
        this.listarRecepcion();
        
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
