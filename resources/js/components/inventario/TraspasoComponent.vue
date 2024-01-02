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
                    <i class="fa fa-align-justify"></i> Traspaso
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')" >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" ><i class="fa fa-search" ></i> Buscar</button>
                           
                                    
                            </div>
                        </div>
                    </div>
             
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>#traspaso</th>                               
                                <th>Codigó</th>
                                <th>Linea</th>
                                <th>Producto</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Cantidad</th>
                                
                                <th>Glosa</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Estado</th>
                                <th>Procesado</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                           <tr>
                            
                            <td><button>edit</button><button>delete</button></td>
                            <td>3000101</td>
                            <td>cod_producto</td>
                            <td>linea</td>
                            <td>nombre_producto</td>
                            <td>almacen_1</td>
                            <td>almacen_2</td>
                            <td>4</td>
                 
                            <td>cal-000-user-000</td>
                            <td>02/01/2024 + hora</td>
                            <td>Admin</td>
                            <td>activado</td>
                            <td>Sin procesar/procesado..</td>
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
                        Todos los campos con (*) son requeridos // debe seleccionar un origen y precionar el buscar
                    </div>
                    <form action="" class="form-horizontal">
                        <!-- insertar datos -->
                        <div class="container">
                         
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Origen
                                    <span   class="error">(*)</span>
                                </label>
                                <div class="col-md-7 input-group mb-3">
                                    <select name="" id=""  class="form-control">
                                        <option value="0" selected disabled>-Seleccione un dato</option>
                                        <option value="1" >1</option>
                                        <option value="2" >1</option>
                                    </select>
                                    <button
                                            class="btn btn-primary"
                                          
                                            type="button"
                                            id="button-addon1"
                                            @click="
                                                abrirModal(
                                                    'bucarProductoIngreso',
                                                );
                                              
                                            "
                                        >
                                            <i class="fa fa-search"></i>
                                        </button>
                                </div>
                               
                            </div>
                            <div class="form-group row">
                                    <label
                                        class="col-md-3 form-control-label"
                                        for="text-input"
                                        >Descripción                               
                                        
                                    </label>
                                        <div class="col-md-3 form-control-label" for="text-input">
                                            <ul class="list-group">
                                                <li class="list-group-item">id_ingreso</li>
                                                <li class="list-group-item">cod_prod</li>
                                                <li class="list-group-item">lote</li>
                                                <li class="list-group-item">cantidad</li>
 
                                            </ul>
                                        </div>
                                </div>
                            <div class="form-group row">
                                    <label
                                        class="col-md-3 form-control-label"
                                        for="text-input"
                                        >Cantidad
                                        <span class="error">(*)</span
                                        >
                                    </label>
                                    <div class="col-md-3">
                                        <input 
                                            type="number"
                                            id="cantidad"
                                            name="cantidad"
                                            v-model="cantidad"
                                            class="form-control"
                                            placeholder="Datos de stock"
                                         
                                        />
                                      
                                        <span
                                         
                                            class="error"
                                            >Debe Ingresar una cantidad</span
                                        >
                                    </div>
                                </div>

                               
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Destino
                                    <span   class="error">(*)</span>
                                </label>
                                <div class="col-md-7 input-group mb-3">
                                    <select name="" id=""  class="form-control">
                                        <option value="0" selected disabled>-Seleccione un dato</option>
                                        <option value="1" >2</option>
                                        <option value="2" >2</option>
                                    </select>
                                </div>
                            </div>

                                                     
                            <div class="form-group row">
                                      <label class="col-md-3 form-control-label" for="text-input">Glosa
                                        <span   class="error">(*)</span>
                                      </label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                           
                                      </div>
                                  </div> 
                            
                        </div>
                       
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                    <button type="button"  class="btn btn-primary" >Guardar</button>
                        <button type="button" class="btn btn-primary" >Actualizar</button>
                   
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
                                            <th scope="col">Id Ingreso.</th>
                                            <th scope="col">
                                                Descripcion Prod.
                                            </th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">
                                                Codigo Internacional
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>0001</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
                            @click="cerrarModal('staticBackdrop'); " >
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
    import Swal from 'sweetalert2';
    import { error401 } from '../../errores';
     //Vue.use(VeeValidate);
     export default{
        data(){
            return{
                tituloModal:'',
                arrayTipos:[],
              
                listarTipo:0,
            }
        },
        
       methods :{
        ajustesNegativos(){
                let me=this;
                var url='/ajustesNeg/listarTipo';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayTipos=respuesta;
                    console.log( me.arrayTipos);
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

        cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarAlmacenes(page);
            },
        abrirModal(accion,data= []){
            let me=this;
               
             

                switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Traspaso'
                        
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'actualizar':
                        {
                            me.classModal.openModal('registrar');
                        }
                        case "bucarProductoIngreso": {
                    //  me.tipoAccion=1;
                
                        me.classModal.openModal("staticBackdrop");
                    break;
                }
                 

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                           
            },

       },
       
       mounted() {
        this.classModal = new _pl.Modals();
        this.classModal.addModal('registrar');
        this.ajustesNegativos();
        this.classModal.addModal("staticBackdrop");
        
       }
     }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>