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
                    <button v-if="puedeCrear==1" type="button" class="btn btn-secondary" @click="abrirModal('registrar');listarTipoDoc();listarEX();" :disabled="selectTipo == 0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align: center">
                            <label for="">Persona o Empresa:</label>
                         </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" @change="listarCliente(1)" v-model="selectTipo">
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
                                    @keyup.enter="listarCliente(1)" 
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarCliente(1)"
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align: center">
                            <label for="" :hidden="selectTipo == 0">Rango de clientes:</label>
                         </div>
                         <div class="col-md-4">
                            <div class="input-group">
                          
                    
                    <select class="form-control"  @change="listarCliente(1)" v-model="limite_X" :hidden="selectTipo == 0"
                    :disabled="selectTipo == 0">
                        <option value="0" disabled selected>Seleccionar...</option>
                        <option v-for="l in arrayLimite" :key="l.id" :value="l.limite">
                            <span v-if="l.limite === 10">Todos</span>
                            <span v-else>{{ l.limite }}</span>
                        </option>
                    </select>
              
         
                             </div>
                        </div>        
                    </div>   
                    <div v-if="selectTipo===0" class="alert alert-secondary" role="alert">
  Debe seleccionar una opcion.
</div>
                   <div v-else>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>                             
                                <th class="col-md-1">Numero de doc</th>
                                <th class="col-md-2">Razon social</th>
                                <th class="col-md-1">Tipo de documentos</th>
                                <th class="col-md-2">Correo</th>
                                <th class="col-md-2">Nombre cliente</th>
                                <th class="col-md-2">
                                    <span v-if="selectTipo===1">
                                        Numero de identidad
                                    </span>
                                    <span v-if="selectTipo===2">
                                       Nit
                                    </span>
                                    </th>                              
                                <th class="col-md-1">Fecha/Hora</th>
                                <th class="col-md-1">Usuario</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody v-if="selectTipo == 0"></tbody>

                        <tbody v-else>                            
                           <tr v-for="c in arrayCliente" :key="c.id">
                            <td>
                                <div  class="d-flex justify-content-start">
                                        
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',c);listarTipoDoc();listarEX();" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i>
                                            </button>  
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="c.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminar(c.id)" style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-info btn-sm" @click="activar(c.id)" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button> 
                                        </div>
                                        <div v-else>
                                            <button v-if="c.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                            </button> 
                                        </div>    
                                </div>        
                            </td>
                            <td class="col-md-1" v-text="c.num_documento"></td>
                            <td class="col-md-2" v-text="c.nom_a_facturar"></td>
                            <td class="col-md-1" v-text="c.datos_tipo_documento+'-'+c.nom_documento"></td>
                           <td class="col-md-2" v-text="c.correo"></td>
                            <td class="col-md-2" v-text="c.nombre_completo"></td>
                            <td class="col-md-2" v-text="c.documento_identidad"></td>
                            <td class="col-md-1" v-text="c.fecha_mas_reciente"></td>
                            <td class="col-md-1" v-text="c.name"></td>
                            <td>
                                <div v-if="c.activo==1">
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
                            <li
                                class="page-item"
                                v-if="pagination.current_page > 1" ><a  class="page-link"
                                    href="#" @click.prevent="
                                        cambiarPagina(pagination.current_page - 1,)">Ant</a>
                            </li>
                            <li  class="page-item"
                                v-for="page in pagesNumber"
                                :key="page"
                                :class="[page == isActived ? 'active' : '']" >
                                <a  class="page-link"
                                    href="#"
                                    @click.prevent="cambiarPagina(page)"
                                    v-text="page"
                                ></a>
                            </li>
                            <li class="page-item"
                                v-if="
                                    pagination.current_page <
                                    pagination.last_page
                                "
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page + 1,
                                        )
                                    "
                                    >Sig</a
                                >
                            </li>
                        </ul>
                    </nav>
                   </div>
              
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
                    <div v-if="id_max_2===0 && tipoAccion===2" class="alert alert-primary" role="alert">
                        <i class="fa fa-universal-access" aria-hidden="true"></i> Cliente sin movimiento  
                    </div>
                    <div v-if="id_max_2===1 && tipoAccion===2" class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Este cliente ya tiene movimiento  
                    </div>
                    <form action="" class="form-horizontal">
                        <!-- insertar datos -->
                        <div class="container">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Tipo de docuemento:
                                    <span  v-if="selectTipoDoc==0" class="error">(*)</span>
                                </label>
                                <div class="col-md-9">   
            
                                    <select name="" id=""  class="form-control" v-model="selectTipoDoc" @change="estadoEX();">
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
                                                <strong>Número Documento: <span  v-if="num_documento==''" class="error">(*)</span></strong>
                                                <input type="text" @input="validateInput()" class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" v-on:focus="selectAll" >
                                                <span  v-if="num_documento==''" class="error">Debe ingresar el documento de identidad</span>
                                            </div>
                                        </div> 
                                            <div class="row">
                                                <div class="form-group col-sm-4" >
                                                    <strong>complemento de C.I.: </strong>
                                                    <input :disabled="selectTipoDoc != 1" type="text" class="form-control" placeholder="Solo si tiene C.I." :maxlength="4"  v-model="complemento_">
                                  
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <strong>Correo: </strong>
                                                    <input  type="email" class="form-control" placeholder="Correo@correo.es"  v-model="correo" v-on:focus="selectAll" required>
                                         
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
                                                <strong>Nombre de empresa: </strong>
                                                <input type="text" class="form-control" v-model="nombres"  placeholder="Nombres.">                                              
                                            </div>
                                            
                                        </div> 
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Razon_social: <span  v-if="nombre_a_facturar==''" class="error">(*)</span></strong>
                                                <input type="text" class="form-control" placeholder="Nombre a Facturar"  v-model="nombre_a_facturar" v-on:focus="selectAll">
                                                <span  v-if="nombre_a_facturar==''" class="error">Debe ingresar un nombre del establecimiento</span>
                                            </div>  
                                            <div class="form-group col-sm-4">
                                                <strong>Correo:</strong>
                                                <input type="email" class="form-control" placeholder="Correo@correo.es"  v-model="correo" v-on:focus="selectAll" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" required>
                                                
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Número Documento: <span  v-if="num_documento==''" class="error">(*)</span></strong>
                                                <input type="text" @input="validateInput()" class="form-control" :placeholder="selectTipoDoc == 1 ? 'C.I.' : (selectTipoDoc == 2 ? 'C.I. Extranjero' : (selectTipoDoc == 3 ? 'Número pasaporte' : (selectTipoDoc == 4 ? 'Otro documento' : 'Nit')))" v-model="num_documento" v-on:focus="selectAll">
                                                <span  v-if="num_documento==''" class="error">Debe ingresar el documento de identidad</span>
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
                    <button type="button"  class="btn btn-primary" v-if="tipoAccion==1" @click="registrar()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion==2" @click="actualizar()">Actualizar</button>
                   
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
                buscar:'',
                tituloModal:'',    
                tipoAccion:1, 
                arrayLimite:[{id:1,limite:50},
                {id:2,limite:100},
                {id:3,limite:200},
                {id:4,limite:400},
                {id:5,limite:800},
                {id:6,limite:10},
                ],
                limite_X:50,
                arrayTipo:[{id:1,tipo:'Persona'},
                            {id:2,tipo:'Empresa'}],
                selectTipo:0,  
                buscar:'',
                arrayTipoDocumento:[],    
                selectTipoDoc:0,
                arrayEX:[],
                complemento_:'',    

                //datos de persona
                nombres:'',
                apellidos:'',
                num_documento:'',
               
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
                arrayCliente:'',
                id:'',
                id_per_emp:'',    
                codigo_cliente:'',
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
                id_max_2:0,
                
            }
        },
        computed: {
        sicompleto() {
            let me = this;
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (
                me.selectTipoDoc != 0 &&
                me.nombre_a_facturar != "" &&
                
                
                me.num_documento != ""
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

        listarCliente(page){
                let me=this;
                
                if (me.selectTipo!=0) {
                    var url='/directorio?page='+page+'&buscar='+me.buscar+'&buscarP_E='+me.selectTipo+'&limite='+me.limite_X;
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayCliente = respuesta.clientes.data;
                })
                .catch(function(error){
                    error401(error);
                });     
            }              
        },
        validateInput() {
        this.num_documento = this.num_documento.replace(/[^a-zA-Z0-9]/g, '');
        },
        estadoEX(){
            if (this.selectTipoDoc!=1) {
                this.complemento_='';  
            }
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

        cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarCliente(page);
            },

        abrirModal(accion,data= []){
            let me=this;
            console.log(data);   
              switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Registro de cliente';
                        me.tipoAccion=1;
                        me.selectTipoDoc=0;              
                        me.complemento_='';
                        me.nombres="";
                        me.apellidos="";
                        me.num_documento="";
                        me.razon_social="";
                        me.nom_local="",  
                        //datos de cliente
                        me.cod_cliente="";                
                        me.correo="";
                        me.telefono="";
                        me.direccion="";
                        me.nombre_a_facturar="";
                        me.pais="";
                        me.ciudad="";                        
                       // me.codigo_cliente:'',
                 
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'actualizar':
                        {
                    console.log(data);          
                    me.tituloModal='Registro de cliente';
                        me.tipoAccion=2;
                        if (data.id_max===null) {
                            me.id_max_2=0;
                        } else {
                            me.id_max_2=1;
                        }
                        if (data.selectTipo===1) {
                            me.nombres=data.nombre;
                            me.apellidos=data.apellido;
                        } else {
                            me.nombres=data.nombre_completo; 
                            me.apellidos=data.apellido;                           
                        }
                        me.selectTipoDoc=data.id_tipo_doc === null ? 0:data.id_tipo_doc;              
                   
                        me.num_documento=data.num_documento;
                        me.complemento_=data.complemento;
                        me.razon_social="";
                        me.nom_local="",  
                        me.id_per_emp=data.id_persona_empresa;    
                        me.cod_cliente="";                
                        me.correo=data.correo;
                        me.telefono=data.telefono;
                        me.direccion=data.direccion;
                        me.nombre_a_facturar=data.nom_a_facturar;
                        me.pais=data.pais;
                        me.ciudad=data.ciudad;   
                        me.id=data.id
                        me.classModal.openModal('registrar');
                        }           

                }
                
            },

            cerrarModal(accion){
                let me = this;
                me.tipoAccion=1;
                        me.selectTipoDoc=0;              
                        me.complemento_='';
                        me.nombres="";
                        me.apellidos="";
                        me.num_documento="";
                        me.id="";
                        me.razon_social="";
                        me.nom_local="",  
                        me.id_per_emp=="";
                        me.cod_cliente="";                
                        me.correo="";
                        me.telefono="";
                        me.direccion="";
                        me.nombre_a_facturar="";
                        me.pais="";
                        me.id_max_2=0;
                        me.ciudad="";
                me.classModal.closeModal(accion);
                           
            },

            registrar() {
            let me = this;

            if(me.correo==''){
                me.correo="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com";
            }
         
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
                me.num_documento === "" ||
                me.nombre_a_facturar === "" ||
                me.correo === ""                
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                
                if (me.num_documento!=99001&&me.num_documento!=99002&&me.num_documento!=99003&&me.num_documento!=0&&me.num_documento!="000") {
                    axios
                    .post("/directorio/registrar", {
                        tipo_per_emp: me.selectTipo,
                        id_tipo_doc: me.selectTipoDoc,
                        nombre: me.nombres,
                        apellido: me.apellidos,
                        num_documento: me.num_documento,
                        ex: me.complemento_,                       
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
                        me.listarCliente();                   
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
                    ""+error.response.data.error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
                }   else {
                    Swal.fire(
                    "Los numeros 99001, 99002, 99003, 0, 000. Esta ocupado para actividades especiales",
                    "Haga click en Ok",
                    "warning",
                ); 
                }
            }
            }  
           
        },
        
        actualizar() {
            let me = this;
            if(me.correo==''){
                me.correo="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com";
            }
                    // Expresión regular para verificar el formato del correo electrónico
                    const correoRegex = /^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}$/;
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
                me.num_documento === "" ||
                me.nombre_a_facturar === "" ||
                me.correo === ""                
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            }
            else{
                if (me.num_documento!=99001&&me.num_documento!=99002&&me.num_documento!=99003&&me.num_documento!=0&&me.num_documento!="000") {
                    axios 
            .put("/directorio/actualizar", {
                        id:me.id,
                        id_per_emp:me.id_per_emp,
                        tipo_per_emp: me.selectTipo,
                        id_tipo_doc: me.selectTipoDoc,
                        nombre: me.nombres,
                        apellido:me.apellidos,
                        num_documento:me.num_documento,
                        ex:me.complemento_,                       
                        correo: me.correo,
                        nom_a_facturar:me.nombre_a_facturar,
                        telefono: me.telefono,                      
                        direccion: me.direccion,
                        pais: me.pais,
                        ciudad: me.ciudad 
                })
                .then(function (response) {
                    me.listarCliente();
                 
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
                }else{
                    Swal.fire(
                    "Los numeros 99001, 99002, 99003, 0, 000. Esta ocupado para actividades especiales",
                    "Haga click en Ok",
                    "warning",
                ); 
                }

                
            }    
            }                          
        },
        eliminar(id){
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
                     axios.put('/directorio/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCliente();
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
            activar(id){
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
                     axios.put('/directorio/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCliente();
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
       },
       
       mounted() {
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();
        //      this.listarAlmacenes_tiendas_con_permisos();
        //-----------------------
        this.classModal = new _pl.Modals();
        this.classModal.addModal('registrar');
        this.listarCliente();
        
       }
     }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>