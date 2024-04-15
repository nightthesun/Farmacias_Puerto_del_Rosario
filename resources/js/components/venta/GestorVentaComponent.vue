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
                <div class="card-header d-flex">
                    <span>Venta de productos </span>  
                 
                    &nbsp;
      <select class="form-control form-control-sm" v-model="selectSucursalGet" style="width: 200px;">
        <option value="0" selected disabled>Seleccionar sucursal...</option>
        <option  v-for="get in arraySucursalGet"  :key="get.id" 
        v-text="get.razon_social"></option>      
     
      </select>


     
    <select class="form-control form-control-sm" v-model="selectAlmTienda" style="width: 200px;">
        <option value="0" selected disabled>Seleccionar tienda o almacen...</option>
        <option v-for="sucursal in arrayAlmTienda" :key="sucursal.codigo" :value="sucursal.codigo"
            v-text="sucursal.codigoS + ' -> ' + sucursal.codigo+' '+sucursal.razon_social"></option>
    </select>                          
                    
                    
                </div>
        <div class="card-body" style="padding-top: 1px;">
            <div class="form-group row">

                <div class="card w-100">
                  
                    <div class="card-body">
                      <strong >Detalle de cliente</strong> 


                      <hr> <!-- Línea horizontal -->
                      
                      <div class="row">
                    
                        <div class="form-group col-sm-4" style="display: flex; align-items: center;">
                            <input type="text" class="form-control"  placeholder="Número de Documento" v-model="buscarCliente">
                            <button class="btn btn-primary" type="button" 
                                    @click="listarUsuario()" :disabled="buscarCliente==''">
                                Buscar
                            </button>
                          
                        </div>
                        <div class="form-group col-sm-5" style="display: flex; align-items: center;">
                            <input type="text" class="form-control"  v-model="datos_cliete" disabled placeholder="Número Documento/Número de Cliente">
                            <button class="btn btn-primary" type="button" @click="abrirModal('lote_cliete');listarUsuarioRetorno();">
                                <i class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>                            
                            <button class="btn btn-info" type="button" style="color: white;"  @click="abrirModal('registrar_cliente');listarEX();listarTipoDoc();">
                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                   
                       
                      
                        
                    </div> 
                    </div>
                  </div>













                <table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">Producto</th>
      <th scope="col">P/U</th>
      <th scope="col">Stock</th>
      <th scope="col">F. Ven</th>
      <th scope="col">Descuento</th>
      <th scope="col">Cantidad</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>3</td>
    </tr>
   
    
  </tbody>
</table>
                       

            </div>
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
                           
                            :disabled="!sicompleto"
                        >
                            Guardar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 2"
                            class="btn btn-primary"
                          
                        >
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
          <!-- MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE  -->
          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="cliente_modal"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title">Datos de cliente</h4>
                        <button class="close" @click="cerrarModal('cliente_modal')">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>NO ESCRITO EN EL PADRÓN</strong> <br>
                            OD-OTRO DOCUMENTO DE IDENTIDAD 99001.
                           
                          </div>
                          <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <strong>Razon social:</strong>
                                <input type="text" class="form-control" v-model="razon_social_99001">
                            </div>
                        </div>
                        
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('cliente_modal')">Cerrar</button>
                        <button :disabled="razon_social_99001==''" class="btn btn-primary" @click="caso_99001();">Guardar</button>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal add cliente -->
          <!-- Modal para la busqueda de clientes por lote -->
          <div class="modal fade" id="lote_cliete" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-primary">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">
                          Busqueda de clientes
                      </h5>
                      <button type="button" @click="cerrarModal('lote_cliete')" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                          X
                      </button>
                  </div>

                  <div class="modal-body">
                      <form>
                          <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">Introduzca numero de documento: </label>
                              <input
                                  type="text"
                                  id="texto"
                                  name="texto"
                                  class="form-control"
                                  placeholder="Numero de cliente/nombre/nombre a facturar"
                                  v-model="buscar"
                                  @input="listarUsuarioRetorno()" />                             
                          </div>
                          <div> <table class="table table-hover" id="tablaProductosIngreso" 
                                  style="height: 350px; display: block; overflow: scroll;"
                              >
                                  <thead>
                                      <tr>
                                          <th scope="col">Nro cliente.</th>
                                          <th scope="col">Nombre.</th>
                                          <th scope="col">Nombre a facturar</th>
                                          <th scope="col">Datos de cliente</th>
                                      </tr>
                                  </thead>

                                  <tbody>
                                      <tr v-for="cliente in arrayClienteLote" :key=" cliente.id"  @click="caso_loteCliente(cliente);
                                          listarUsuarioRetorno();">
                                          <td v-text="cliente.id"></td>
                                          <td v-text="cliente.nombre_completo"></td>
                                          <td v-text="cliente.nom_a_facturar"></td>
                                          <td v-text="cliente.num_documento+'/'+cliente.tipo_doc_datos+'-'+cliente.tipo_doc_nombre"></td>
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
                          @click="cerrarModal('lote_cliete');">
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
      <!-- Fun Modal para la busqueda de clientes por lote -->
      <!--Inicio del modal de registro de-->
      <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="registrar_cliente" aria-hidden="true" data-backdrop="static" data-key="false">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">{{ tituloModal }}</h4>
                <button  type="button" class="close" aria-label="Close" @click="cerrarModal('registrar_cliente')">
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
                                Tipo de cliente:
                                <span  v-if="selectTipo==0" class="error">(*)</span>
                            </label>
                            <div class="col-md-6">   
        
                                <select name="" id=""  class="form-control" v-model="selectTipo" @change="cambiarEstadoSElectrorCliente()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="t in arrayTipo"
                                        :key="t.id"
                                        :value="t.id"
                                        v-text="t.tipo">
                                    </option>                                  
                                </select>
                            </div>
                          
                           
                        </div>
                        <div class="form-group row"  v-if="selectTipo!=0">
                            <label class="col-md-3 form-control-label" for="text-input">
                                Tipo de docuemento:
                                <span  v-if="selectTipoDoc==0" class="error">(*)</span>
                            </label>
                            <div class="col-md-6" >        
                                <select name="" id=""  class="form-control" v-model="selectTipoDoc" @change="estadoEX();" >
                                    <option value="0" selected disabled>-Seleccione un dato </option>
                                    <option v-for="t in arrayTipoDocumento" :key="t.id"
                                        :value="t.id"
                                        v-text="t.datos+'-'+t.nombre_doc">
                                    </option>                                  
                                </select>
                            </div>
                        </div>  
                        <!---- acoordion ----> 
                        <div class="accordion" id="accordionExample" v-if="selectTipoDoc!=0&&selectTipo!=0">
                            <div class="card">
                              <div class="card-header" id="headingOne">
                                <h2 class="mb-0" v-if="selectTipo==1">
                                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Datos de persona
                                  </button>
                                </h2>
                                <h2 class="mb-0" v-else>
                                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Empresa
                                  </button>
                                </h2>
                                
                              </div>
                          
                              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" v-if="selectTipo==1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <strong>Nombre: </strong>
                                            <input type="text" class="form-control" v-model="nombres"  placeholder="Nombres."  >
                                          
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <strong>Apellidos:</strong>
                                            <input type="text" class="form-control" placeholder="Apellido Paterno / Apellido Materno."  v-model="apellidos" >
                                          
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <strong>Número Documento: <span  v-if="num_documento2==''" class="error">(*)</span></strong>
                                            <input type="text" @input="validateInput()" class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" v-on:focus="selectAll" >
                                            <span  v-if="num_documento2==''" class="error">Debe ingresar el documento de identidad</span>
                                        </div>
                                    </div> 
                                        <div class="row">
                                            <div class="form-group col-sm-4" >
                                                <strong>EX: </strong>
                                             
                                <select name="" id=""  class="form-control" v-model="selectEX" :disabled="selectTipoDoc != 1">
                                    <option value="0" selected disabled>-Seleccione un EX.</option>
                                    <option v-for="e in arrayEX" :key="e.id"
                                        :value="e.id"
                                        v-text="e.abrev">
                                    </option>
                                  
                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Correo: <span  v-if="correo==''" class="error">(*)</span></strong>
                                                <input type="email" class="form-control" placeholder="Correo@correo.es"  v-model="correo" v-on:focus="selectAll" required>
                                                <span  v-if="correo==''" class="error">Debe Ingresar un correo</span>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Nombre a Facturar: <span  v-if="nombre_a_facturar==''" class="error">(*)</span></strong>
                                                <input type="text" @input="validateInput" class="form-control" placeholder="Razon social"  v-model="nombre_a_facturar" v-on:focus="selectAll">
                                                <span  v-if="nombre_a_facturar==''" class="error">Debe ingresar un nombre a quien va la factura</span>
                                            </div>                                             
                                        </div>

                                    
                                </div>
                              </div>
                            <!------else del if-------->
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" v-else>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <strong>Razon_social: <span  v-if="nombre_a_facturar==''" class="error">(*)</span></strong>
                                            <input type="text" class="form-control" placeholder="Nombre a Facturar"  v-model="nombre_a_facturar" v-on:focus="selectAll">
                                            <span  v-if="nombre_a_facturar==''" class="error">Debe ingresar un nombre del establecimiento</span>
                                        </div>  
                                        <div class="form-group col-sm-4">
                                            <strong>Correo: <span  v-if="correo==''" class="error">(*)</span></strong>
                                            <input type="email" class="form-control" placeholder="Correo@correo.es"  v-model="correo" v-on:focus="selectAll" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" required>
                                            <span  v-if="correo==''" class="error">Debe Ingresar un correo</span>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <strong>Número Documento: <span  v-if="num_documento2==''" class="error">(*)</span></strong>
                                            <input type="text" @input="validateInput()" class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" v-on:focus="selectAll">
                                            <span  v-if="num_documento2==''" class="error">Debe ingresar el documento de identidad</span>
                                        </div>
                                    </div> 
                                       

                                    
                                </div>
                              </div>

                            </div>
                            <div class="card">
                              <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Datos Adicionales
                                  </button>
                                </h2>
                              </div>
                              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <strong>Dispositivo de comunicación fijo o móvil:</strong>
                                            <input type="text" class="form-control" v-model="telefono"  placeholder="Celular/telefono."  >
                                          
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <strong>Dirección:</strong>
                                            <input type="text" class="form-control" placeholder="Zona/Calle/Barrio/Numero de puerta."  v-model="direccion" >
                                        
                                        </div>
                                    
                                    </div> 
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <strong>País:</strong>
                                            <input type="text" class="form-control" v-model="pais"  placeholder="Lugar donde radica."  >
                                          
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <strong>Ciudad:</strong>
                                            <input type="text" class="form-control" placeholder="ciudad donde vive."  v-model="ciudad">
                                        
                                        </div>
                                    
                                    </div> 
                                </div>
                              </div>
                            </div>
                      
                          </div>
                      </div>
                   
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar_cliente')">Cerrar</button>
                <button type="button"  class="btn btn-primary"  @click="registrar_cliente()">Guardar</button>              
               
            </div>
            </div>
            
        </div>
    </div>
    <!--fin del modal-->
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
            //selecto get sucursal
            selectSucursalGet:0,
            arraySucursalGet:[],    
            id_sucu_get:'',
            razon_social_sucu_get:'',
            cod_sucu_get:'',            
            //sucusales_tienda_almacen   
            selectAlmTienda:0,
            arrayAlmTienda:[],
            //clientes
            buscarCliente:'',
            cliente_id:'',
            datos_cliete:'',
            nom_a_facturar:'',
            num_documento:'',
            //casos especiales
        
            id_tipo_doc:'',
            razon_social_99001:'',    
            //cliente_lote
            buscar:'',
            clientelote:'',
            arrayClienteLote:[],
            tipoAccion:1,
            //add_cliente 
            arrayTipoDocumento:[],
         
            selectTipoDoc:0,
            arrayTipo:[{id:1,tipo:'Persona'},
                            {id:2,tipo:'Empresa'}],
            selectTipo:0, 
            arrayEX:[],
            selectEX:0,  
            //datos de cliente
            pais:'',
            ciudad:'',
            direccion:'',
            telefono:'',
            num_documento2:'',
            correo:'',
            nombre_a_facturar:'',
            nombres:'',
        };
    },

   

    computed: {
        sicompleto() {
           let me = this;
     //       if (
          
       //         me.glosa != "" &&
       //         me.cantidadS != "" &&
     //           me.ProductoLineaIngresoSeleccionado
     //       )
       //         return true;
      //      else return false;
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
        cargardatosCliente(){

        },
        validateInput() {
        this.num_documento2 = this.num_documento2.replace(/[^a-zA-Z0-9]/g, '');
        },
        cambiarEstadoSElectrorCliente(){
            let me=this;
            me.pais='';
            me.ciudad='';
            me.direccion='';
            me.telefono='';
            me.num_documento2='';
            me.correo='';
            me.nombre_a_facturar='';
            me.nombres='';
            me.selectEX=0; 
            me.apellidos='';
            me.selectTipoDoc=0;
        
        },
        listarEX(){
            let me=this;
            var url='/listarEx';           
            axios.get(url).then(function(response){
                var respuesta=response.data;
                    me.arrayEX=respuesta;
            }).catch(function(error){
                    error401(error);
                    console.log(error);
                })
        },
        estadoEX(){
            if (this.selectTipoDoc!=1) {
                this.selectEX=0;  
            }
        },
        
        listarTipoDoc(){
            let me=this;
            var url='/listarTipoDoc';            
            axios.get(url).then(function(response){
                var respuesta=response.data;
                me.arrayTipoDocumento=respuesta;
            }).catch(function(error){
                    error401(error);
                    console.log(error);
                })
        },  

        caso_loteCliente(cliente){
           console.log(cliente);
           this.id_tipo_doc=cliente.id_tipo_doc;
        this.cliente_id=cliente.id;
        this.nom_a_facturar=cliente.nom_a_facturar;
        this.num_documento=cliente.num_documento;
        this.datos_cliete=cliente.nom_a_facturar+"/"+cliente.num_documento+"/"+cliente.tipo_doc_datos+"-"+cliente.tipo_doc_nombre;
        this.cerrarModal('lote_cliete')
        },
        listarUsuarioRetorno() {
            let me = this;       
            var url ="/gestor_ventas/listarUsuarioRetorno?buscar="+this.buscar;            
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayClienteLote = respuesta;
            }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
      caso_99001(){
        this.id_tipo_doc=4;
        this.cliente_id=0;
        this.nom_a_facturar=this.razon_social_99001;
        this.datos_cliete=this.razon_social_99001+"/99001/OD-OTRO DOCUMENTO DE IDENTIDAD";
        this.cerrarModal('cliente_modal')       
      },

      
        listarUsuario() {
            let me = this;
            var url = "/gestor_ventas/listarUsuario?num_doc="+me.buscarCliente;
         
            var opcion=me.buscarCliente;
            switch(opcion) {
    case '99001':
        me.abrirModal('cliente_modal');
        break;
    case '99002':
            me.id_tipo_doc=4;
            me.cliente_id=0;
            me.nom_a_facturar="CONTROL TRIBUTARIO";
            me.datos_cliete=me.nom_a_facturar+"/99002/OD-OTRO DOCUMENTO DE IDENTIDAD";
        break;
    case '99003':
        me.id_tipo_doc=4;
        me.cliente_id=0;
        me.nom_a_facturar="VENTAS MENORES DEL DIA";
        me.datos_cliete=me.nom_a_facturar+"/99003/OD-OTRO DOCUMENTO DE IDENTIDAD";
    break;
    default:
    axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    console.log(response.data);
                    me.cliente_id=response.data.id;
                    if (me.cliente_id==undefined) {
                        me.datos_cliete="No se encontro cliente..."
                    } else {
                        me.id_tipo_doc=response.data.id_tipo_doc;
                        me.nom_a_facturar=response.data.nom_a_facturar;
                        me.num_documento=response.data.num_documento;
                        me.cliente_id=cliente.id;
                        me.datos_cliete=response.data.nom_a_facturar+"/"+response.data.num_documento+"/"+response.data.tipo_doc_nombre;  
                    }
                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        break;
        }
          
          
        },

        listarSucursalGet() {
            let me = this;
            var url = "/listarSucursalGet";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursalGet = respuesta;
                    
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarAlmTienda() {
            let me = this;
            var url = "listarSucursal";
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
                    me.tituloModal = "Registro de traspaso origen ";
            
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;                 
                      
                    me.classModal.openModal("registrar");
                    break;
                }
                case "cliente_modal":{
                    me.v99001='';
                    me.id_tipo_doc='';
                    me.razon_social_99001="";
                    me.cliente_id="";
                    me.classModal.openModal("cliente_modal");
                    break;
                }
                case "lote_cliete":{
                    me.buscar='';
                    me.id_tipo_doc=''
                    me.cliente_id='';
                    me.nom_a_facturar='';
                    me.num_documento='';
                    me.datos_cliete='';
       
                    me.classModal.openModal("lote_cliete");
                    break;
                }
                case "registrar_cliente": {
                   
                    me.tituloModal = "Registro de clientes";
                    me.pais='';
                    me.ciudad='';
                    me.direccion='';
                    me.telefono='';
                    me.num_documento2='';
                    me.correo='';
                    me.nombre_a_facturar='';
                    me.nombres='';
                    me.selectEX=0; 
                    me.apellidos='';
                    me.selectTipoDoc=0;
                    me.selectTipo=0;
                    me.classModal.openModal("registrar_cliente");
                    break;
                }            
            }
        },
        cerrarModal(accion) {
            let me = this;          
            me.classModal.closeModal(accion);
            me.tituloModal = "";
            me.v99001="";               
            
        },

     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },
    registrar_cliente() {
            let me = this;
            // Expresión regular para verificar el formato del correo electrónico
            const correoRegex = /^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}$/;
            // Verificar si el correo cumple con el formato válido
            if (!correoRegex.test(me.correo)) {
                this.correoInvalido = true;
                Swal.fire(
                    "Formato de correo invalido.",
                    "asegúrese si esta bien escrito el correo, no olvide la @ y la extencion ejemplo correo@correo.es",
                    "warning",
                );
            } else {
                if (
                me.selectTipoDoc === 0 ||              
                me.num_documento2 === "" ||
                me.nombre_a_facturar === "" ||
                me.correo === ""                
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                
                if (me.num_documento!=99001&&me.num_documento!=99002&&me.num_documento2!=99003) {
                    axios
                    .post("/directorio/registrar", {
                        tipo_per_emp: me.selectTipo,
                        id_tipo_doc: me.selectTipoDoc,
                        nombre: me.nombres,
                        apellido: me.apellidos,
                        num_documento: me.num_documento2,
                        ex: me.selectEX,                       
                        correo: me.correo,
                        nom_a_facturar: me.nombre_a_facturar,
                        telefono: me.telefono,                      
                        direccion: me.direccion,
                        pais: me.pais,
                        ciudad: me.ciudad
                
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                        me.cargardatosCliente();                   
                    })
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
                }   else {
                    Swal.fire(
                    "Los numeros 99001, 99002, 99003. Esta ocupado para actividades especiales",
                    "Haga click en Ok",
                    "warning",
                ); 
                }
            }
            }  
           
        },
    mounted() {
        this.classModal = new _pl.Modals();
        this.listarAlmTienda();
        this.listarSucursalGet();
        this.classModal.addModal("registrar");
        this.classModal.addModal("cliente_modal");
        this.classModal.addModal("lote_cliete"); 
        this.classModal.addModal("registrar_cliente");   
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
