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
           
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                
              
                <li class="nav-item">
                    <a class="nav-link" @click="listarStockMedio();listarTabla();listarSucursalLista();" id="pills-bitacora-tab" data-toggle="pill" href="#pills-bitacora" role="tab" aria-controls="pills-bitacora" aria-selected="false">Bitacora stock</a>
                </li>                         
            </ul>
        </div>
        <div class="card-body">           
             <div class="tab-content" id="pills-tabContent">
                  
<!--------------------------------------------------------------------------------------------------------------------------------------------->
        
                    <div class="tab-pane fade" id="pills-bitacora" role="tabpanel" aria-labelledby="pills-bitacora-tab">
                        <div class="card">
                            <div class="card-header">
                                Modo manual
                            </div>
                           
    <div class="card-body">
        <div class="alert alert-danger" v-show="tipoStockMedio==0" role="alert">
            <span>Modo activado <strong>por defecto</strong> stock medio 1</span>
        </div>
        <div class="alert alert-success" v-show="tipoStockMedio==1" role="alert">
            <span>Modo activado <strong>stock medio 1</strong> revisar el configuración de administrador</span>
        </div>
        <div class="alert alert-success" v-show="tipoStockMedio==2" role="alert">
            <span>Modo activado <strong>stock medio 2</strong> revisar el configuración de administrador</span>
        </div>
            <div id="accordion">
  <div class="card">
<!-------------------usuario 1:1 sucursal---------------------------->
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Sucursal X Usuario
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <div class="row" v-if="sucursalNoActivada==0">
            <div class="col-sm-4">
                <span>Realizar operación: </span>
                <button  type="button" class="btn btn-primary" @click="actualziarTablaBitacora(0)"><i class="fa fa-code-fork" aria-hidden="true"></i> Bistacora stock</button>                               
            </div>
            <div class="col-sm-8">
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(a, index) in arrayTabla" :key="index">
                                <td>{{a.tipo}}</td>
                                <td>{{a.name}}</td>
                                <td>{{a.fecha}}</td>
                                <td>{{a.hora}}</td>
                                <td>{{a.accion}}</td>
                            </tr>
                        </tbody>
                    </table>        
            </div>
            </div>
            <div class="alert alert-danger" role="alert" v-else>
                <h3>LA SUCURSAL NO ESTA ACTIVADA CONCTATE CON EL ADMINISTRADOR</h3>
            </div>
        </div>
    </div>
  </div>
  <!-------------------sucursales ---------------------------->
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Todas las sucursales
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-3">Opciones</th>
                                <th class="col-md-9">Nombre</th>
                            </tr>
                        </thead>    
                            <tbody>
                                <tr v-for="(i, index) in arraySucursal" :key="index">
                                    <td class="col-md-3">
                                         <button @click="actualziarTablaBitacora(i.id)" type="button" class="btn btn-primary"><i class="fa fa-code-fork" aria-hidden="true"></i> Bistacora stock</button>     
                                    </td>
                                    <td class="col-md-9">
                                        {{i.razon_social}}
                                    </td>
                                </tr>
                            </tbody>
                        
                    </table>        
                </div>
            </div>        
        </div>
    </div>
  </div>

</div>
              
        
    </div>
  
                        </div>
                    </div>        
<!---------------------------------------------------------------------------------------------------------------------------->
            </div>            
        </div>
    </div>
</div>   
        
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import { data } from "jquery";
//Vue.use(VeeValidate);

export default {
    components: { VueMultiselect},
     //---permisos_R_W_S
     props: ['codventana','idmodulo'],
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
            tipoAccion:1,
            tituloModal:'',
       showModal: false,
                arrayIndex:[], 
                arraySucursal:[],
                
                //-------stockmedio gestion stock----------
                    tipoStockMedio:100,
                    arrayTabla:[],
                    sucursalNoActivada:0,
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------   
                



        };
    },
   
   

    computed: {
      
        isActived: function () {
            return this.pagination.current_page;
        },

       function () {
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
            console.log(respuesta);
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

                    
         
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;
            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },      
       

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
        //   me.listarIndex(page);
        },
        
        listarSucursalLista() {
            let me = this;   
            me.arraySucursal=[];    
           var url = "/listarSucursalNormal";
            axios.get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    me.arraySucursal=respuesta;                                                  
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarTabla() {
            let me = this;   
            me.arrayTabla=[];    
           var url = "/configuracion-stock/listarTabla";
            axios.get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    if (respuesta==0) {
                       me.sucursalNoActivada=1; 
                    }else{                        
                       me.arrayTabla=respuesta;
                    }                               
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        actualziarTablaBitacora(data_succursal){      
            let me = this;
                  
                axios.post("/configuracion-stock/añadirBitacora", {
                        'tipoTabla':  me.tipoStockMedio,
                        'data_sucursal': data_succursal                   
                    })
                    .then(function (response) {               
                          var respuesta = response.data;
                          me.listarTabla();
                          console.log(respuesta);
                          if (respuesta.length>=1) {  
                            Swal.fire("Error encontrado: "+respuesta,"Haga click en Ok","error",);                                
                          }else{
                            Swal.fire("Operación realizada con exito.","Haga click en Ok","success",); 
                          }                        
                    })
                   .catch(function (error) {                               
            });            
        },

         listarStockMedio() {
            let me = this;       
           var url = "/gestor-stock/stockMedio";
            axios.get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    me.tipoStockMedio=respuesta.stock_medio;
                    console.log(me.tipoStockMedio);                               
                })
                .catch(function (error) {
                    error401(error);
                });
        },
             
        abrirModal(accion, data = []) {
            let me = this;     
         switch (accion) {
              
                case "modal": {
                    //me.showModal = true;
                    //me.tipoAccion = 1; 
                    //me.tituloModal="Sincronización manual"; 
                    //me.classModal.openModal("manual");
                  
                    break;
                }                                      
            }
        },

        cerrarModal(accion) {
            let me = this;
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
        //-------------------------      
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
<style scoped>
.modal {
  transition: opacity 0.5s ease;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active en versiones de Vue < 2.1.8 */ {
  opacity: 0;
}
</style>