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
                    <i class="fa fa-align-justify"></i> Procesar traspasos
                    
                </div>
                <div class="card-body">
                    <!--incluicion de datos-->
                    <form  enctype="multipart/form-data" class="form-horizontal">
                <div class="container" >
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="text-input">
                              Nro. Traspaso:          
                        </label>
                        <div class="col-md-5 input-group mb-1">
                            <input
                                            type="text"
                                           
                                            v-model="nro_traspaso"
                                            class="form-control"
                                            placeholder="Ingrese un numero de traspaso"
                                            v-on:focus="selectAll"
                                        />
                                        <button class="btn btn-primary"                                      
                                            type="button"
                                            id="button-addon1"
                                            @click="abrirModal('registrar');ListartraspasoModal();"   >
                                            <i class="fa fa-search"></i>
                                        </button>
                                        
                        </div>
                    </div>
                 
                    
                    <div class="form-group row mb-1"  >
                                
                                <div class="form-group  col-sm-3">
                                    <span>Origen:</span>
                                    <select v-model="selectOrigen" class="form-control">
                                        <option v-bind:value="0" disabled>Seleccionar...</option>
                                        <option value="" v-for="suOri in arraySucursalOrigen"
                                        :key="suOri.id"
                                        :value="suOri.codigo"
                                        v-text="
                                            suOri.codigoS +
                                            ' -> ' +
                                            suOri.codigo+
                                            ' ' +
                                            suOri.razon_social
                                        "
                                    ></option>
                                </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <span>Destino:</span>
                                    
                                    <select v-model="selectDestino" class="form-control">
                                        <option v-bind:value="0" disabled>Seleccionar...</option>
                                        <option value="" v-for="suOri in arraySucursalDestino"
                                        :key="suOri.id"
                                        :value="suOri.codigo"
                                        v-text="
                                            suOri.codigoS +
                                            ' -> ' +
                                            suOri.codigo+
                                            ' ' +
                                            suOri.razon_social
                                        "
                                    ></option>
                                </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <span>Procesos:</span>
                                    
                                    <select v-model="selectProceso" class="form-control rounded"  >
                                        <option  v-bind:value="3" disabled>Seleccionar...</option>
                                        <option v-for="pro in arrayProceso" :key="pro.id" :value="pro.id" v-text="pro.tipo"></option>
                                       
                                      </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <span>Responsable:</span>
                                    
                                    <select v-model="selectUsuario" class="form-control rounded"  >
                                        <option  v-bind:value="0" disabled>Seleccionar...</option>
                                        <option v-for="usu in arraySucursalUsuario" :key="usu.id_user" :value="usu.id_user" v-text="usu.nombre+' '+usu.apellidos_combinados"></option>
                                       
                                      </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <div class="form-group col-sm-2">
                                    <span>Rango Iniciar:</span>
                                    <input   type="date" v-model="fechaIni" class="form-control"/>
                                </div>
                                <div class="form-group col-sm-2">
                                    <span>Rango Final:</span>
                                    <input   type="date" v-model="fechaFini" class="form-control"/>
                                </div>
                      
                            </div>
                            <div class="form-group row  mt-1">
                                <div class="form-group col-sm-2">
                                    <button type="submit" class="btn btn-primary "  style="width: 150px;" ><i class="fa fa-search" ></i> Buscar</button>
                                 </div>   
                            </div>


                            
                           
                </div>    
            </form>
              
            <div class="alert alert-primary" role="alert">
                A simple primary alert—check it out!
            </div>    
            <div class="alert alert-warning" role="alert">
                Debe realziar una busqueda para ver los trapasos que se realizaron.
            </div>   
                <table class="table table-bordered table-striped table-sm table-responsive">
   
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigó de traspaso</th>
                                <th>Producto</th>
                                <th>Linea</th>
                                <th>Cantidad</th>
                                <th>Fecha</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                
                                <th>Tipo</th>
                                <th>Glosa</th>
                                <th>Fecha</th>
                                <th>Emisor</th>
                                <th>Receptor</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                           <tr>
                     
                          
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
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
        <div
            class="modal fade"
            id="registrar"
            tabindex="-2"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-scrollable modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Busqueda por producto
                        </h5>
                        <button type="button" @click="cerrarModal('registrar')"
                            class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            X
                        </button>
                    </div>

                    <div class="modal-body">
                       
                        <form>
                           
                            <div class="mb-3">
                                <label
                                    for="exampleInputEmail1"
                                    class="form-label"
                                    >Descripcion de producto:
                                </label>
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="inputTextBuscarProductoIngreso"
                                    @input="ListartraspasoModal()"                            
                                  
                                />
                                <!--  @input="ListarretornarProductosIngreso()" <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                            </div>
                            <div>
           
                                <table
                                    class="table table-hover"
                                    id="tablaProductosIngreso"
                                    style="
                                        height: 450px;
                                  
                                        display: block;
                                        overflow: scroll;
                                    "
                                >
                                    <thead>
                                        <tr>
                                            <th scope="col">Nro Traspaso.</th>
                                            <th scope="col" >
                                                Descripción Prod.
                                            </th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">
                                                Origen
                                            </th>
                                            <th scope="col">
                                                Destino
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                      <tr v-for="modalTraspaso in arrayTraspaso" :key=" modalTraspaso.id" @click="guardarDato(modalTraspaso.numero_traspaso);cerrarModal('registrar');"
                                        >
                                        
                                            <td v-text=" modalTraspaso.numero_traspaso  "></td>
                                            <td v-text=" modalTraspaso.leyenda "></td>
                                            <td v-text=" modalTraspaso.fecha "></td>
                                            <td
                                                v-text="
                                                    modalTraspaso.origen
                                                "
                                            ></td>
                                            <td
                                                v-text="
                                                    modalTraspaso.destino
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
                                cerrarModal('registrar');
                           
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
        <!--fin del modal-->
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
               arrayProceso:[
                {'id':0,'tipo':'Pendientes'},
                {'id':1,'tipo':'Procesados'},
                {'id':2,'tipo':'Todo'},
                ],
                selectProceso:3,
                arraySucursalOrigen:[],
                selectOrigen:0,
                arraySucursalDestino:[],
                selectDestino:0,
                arraySucursalUsuario:[],
                selectUsuario:0,
                nro_traspaso:"",
                ProductoLineaIngresoSeleccionado:0,
                arrayTraspaso:[],
                inputTextBuscarProductoIngreso:"",
                fechaIni:"",
                fechaFini:"",

            }
        },
        
       methods :{
        sucursalOrigen() {
            let me = this;
            var url = "/procesar-traspaso/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursalOrigen = respuesta;
  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        sucursalDestino() {
            let me = this;
            var url = "/procesar-traspaso/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursalDestino = respuesta;
           
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        guardarDato(valor){
            let me =this;
            me.nro_traspaso=valor;
          
        },
        usuario() {
            let me = this;
            var url = "/procesar-traspaso/listarUsuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursalUsuario = respuesta;
           
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarAlmacenes(page);
            },
        ListartraspasoModal() {
            let me = this;    
                var url ="/procesar-traspaso?buscar="+me.inputTextBuscarProductoIngreso +
                    "&identificador=" + "1";     
            console.log(url);
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;

                    me.arrayTraspaso = respuesta;
         console.log(me.arrayTraspaso);
   })                       
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        abrirModal(accion,data= []){
            let me=this;
               
             

                switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Busqueda por producto';
                        me.inputTextBuscarProductoIngreso="";
                        me.arrayTraspaso="";
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'actualizar':
                        {
                            me.classModal.openModal('registrar');
                        }
                 

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.inputTextBuscarProductoIngreso="";
                me.arrayTraspaso="";
                me.classModal.closeModal(accion);
                           
            },

       },
       
       mounted() {
        this.classModal = new _pl.Modals();
        this.classModal.addModal('registrar');
        this.sucursalOrigen();
        this.sucursalDestino();
        this.usuario();
        this.ListartraspasoModal();
        this.guardarDato();
        
       }
     }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>