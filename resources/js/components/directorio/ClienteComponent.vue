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
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar');listarTipoDoc();listarEX();" :disabled="selectTipo == 0">
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
                                    Tipo de docuemento:
                                    <span   class="error">(*)</span>
                                </label>
                                <div class="col-md-9">   
            
                                    <select name="" id=""  class="form-control" v-model="selectTipoDoc" @change="estadoEX">
                                        <option value="0" selected disabled>-Seleccione un dato </option>
                                        <option v-for="t in arrayTipoDocumento" :key="t.id"
                                            :value="t.id"
                                            v-text="t.datos+'-'+t.nombre_doc">
                                        </option>
                                      
                                    </select>
                                </div>
                            </div>
                            <!---- acoordion ----> 
                            <div class="accordion" id="accordionExample" v-if="selectTipoDoc!=0">
                                <div class="card">
                                  <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                      <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Datos de persona
                                      </button>
                                    </h2>
                                  </div>
                              
                                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
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
                                                <strong>Número Documento: <span  v-if="num_documento==''" class="error">(*)</span></strong>
                                                <input type="text" class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" v-on:focus="selectAll">
                                                <span  v-if="num_documento==''" class="error">Debe ingresar el documento de identidad</span>
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
                                                    <input type="email" class="form-control" placeholder="Correo@correo.es"  v-model="correo" v-on:focus="selectAll">
                                                    <span  v-if="correo==''" class="error">Debe Ingresar un correo</span>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <strong>Nombre a Facturar: <span  v-if="nombre_a_facturar==''" class="error">(*)</span></strong>
                                                    <input type="text" class="form-control" placeholder="Razon social"  v-model="nombre_a_facturar" v-on:focus="selectAll">
                                                    <span  v-if="nombre_a_facturar==''" class="error">Debe ingresar un nombre a quien va la factura</span>
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
                arrayTipoDocumento:[],    
                selectTipoDoc:0,
                arrayEX:[],
                selectEX:0,    

                //datos de persona
                nombres:'',
                apellidos:'',
                num_documento:'',
                complemento:'',
                //datos de empresa
                razon_social:'',
                nom_local:'',  
                //datos de cliente
                cod_cliente:'',                
                correo:'',
                telefono:'',
                direccion:'',
                nombre_a_facturar:'',
                pais:'',
                ciudad:'',
                numero_tributario:'',            
                codigo_cliente:'',
                arrayTipos:[],
                listarTipo:0,
            }
        },
        
       methods :{
        estadoEX(){
            if (this.selectTipoDoc!=1) {
                this.selectEX=0;  
            }
        },
        listarEX(){
            let me=this;
            var url='/directorio/listarEx';
           
            axios.get(url).then(function(response){
                var respuesta=response.data;
                    me.arrayEX=respuesta;
            }).catch(function(error){
                    error401(error);
                    console.log(error);
                })
        },

        listarTipoDoc(){
            let me=this;
            var url='/directorio/listarTipoDoc';
            
            axios.get(url).then(function(response){
                var respuesta=response.data;
                    me.arrayTipoDocumento=respuesta;
            }).catch(function(error){
                    error401(error);
                    console.log(error);
                })
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
                        me.tituloModal='Registro de cliente'
                        
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
       
        
       }
     }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>