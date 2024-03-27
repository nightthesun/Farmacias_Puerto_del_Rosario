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
                    <i class="fa fa-align-justify"></i> Gestion de clientes
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')" :disabled="selectTipo == 0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align: center">
                            <label for="">Persona o Empresa:</label>
                         </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control"   v-model="selectTipo">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option
                                        v-for="t in arrayTipo"
                                        :key="t.id"
                                        :value="t.id"
                                        v-text="t.tipo">
                                    </option>
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
                                    
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                  
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
              <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Codigó</th>
                                <th>Linea</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                           <tr>
                            <h4 style="text-align: center;"> Sin datos123...</h4>
                          
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
                                    Producto
                                    <span   class="error">(*)</span>
                                </label>
                                <div class="col-md-9">
                                    <select name="" id=""  class="form-control">
                                        <option value="0" selected disabled>-Seleccione un dato</option>
                                        <option value="1" >1</option>
                                        <option value="2" >1</option>
                                    </select>
                                </div>
                            </div>
                                   <div class="form-group row">
                                      <label class="col-md-3 form-control-label" for="text-input">Cantidad
                                        <span   class="error">(*)</span>
                                      </label>
                                        <div class="col-md-9">
                                         <input type="text" id="cantidad" name="cantidad" class="form-control" placeholder="Datos de stock" >
                                      </div>
                                  </div>   

                           <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Tipo
                                    <span   class="error">(*)</span>
                                </label>
                                <div class="col-md-9">
                                    <select name="" id=""  class="form-control">
                                        <option value="0" disabled>-Seleccione un tipo</option>
                                        <option value="1" >1</option>
                                        <option value="2" >1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                      <label class="col-md-3 form-control-label" for="text-input">Descripción
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
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import { error401 } from '../../errores';
     //Vue.use(VeeValidate);
     export default{
        data(){
            return{
                pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
                tituloModal:'',
                arrayTipo:[{id:1,tipo:'Persona'},
                            {id:2,tipo:'Empresa'}],
                selectTipo:0,  
                buscar:'',
                
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
                        me.tituloModal='Ajuste de negativos'
                        
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
                me.classModal.closeModal(accion);
                           
            },

       },
       
       mounted() {
        this.classModal = new _pl.Modals();
        this.classModal.addModal('registrar');
        this.ajustesNegativos();
        
       }
     }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>