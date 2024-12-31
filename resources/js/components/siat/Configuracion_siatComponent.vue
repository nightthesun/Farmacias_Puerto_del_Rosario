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
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-general" aria-selected="true">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">End Points</a>
                </li>                       
            </ul>
        </div>
        <div class="card-body">           
             <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-general-tab">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
    <span>Configuración general de siat</span>
    <button type="button" class="btn btn-info ml-2 ml-sm-0" style="color: white;" @click="resetFecha()">Resetear formato de fecha</button>   
</div>


             
                            <div class="card-body">       
                                
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <strong>Codigo de sistema: <span  v-if="cod_sis===''" class="error">(*)</span></strong>
                                    <input type="text" @input="validateInput($event, 'alphanumeric')" class="form-control" v-model="cod_sis"  placeholder="Codigo de sistema dada por INS">
                                    <span  v-if="cod_sis==''" class="error">Debe Ingresar codigo</span>
                                </div>  
                         
                                <div class="form-group col-sm-3">
                                    <strong>Tipo ambiente: <span  v-if="selectTipoAmbiente===0" class="error">(*)</span></strong>
                                    <select  class="form-control"  v-model="selectTipoAmbiente">
                                            <option value=0 disabled selected>Seleccionar...</option>
                                            <option value=1>Producción</option>
                                            <option value=2>Prueba</option>
                                        </select>
                                    <span  v-if="selectTipoAmbiente==''" class="error">Debe Ingresar codigo</span>
                                </div> 
                                <div class="form-group  col-sm-3">
                                    <strong>Formato de fecha: <span  v-if="forFecha===''" class="error">(*)</span></strong>
                                    <input type="text"  class="form-control" v-model="forFecha"  placeholder="Debe ingresar el formato de fecha">
                                    <span  v-if="forFecha==''" class="error">Debe Ingresar formato</span>
                                </div>
                               
                                <div class="form-group col-sm-3">
                                    <strong>Maximo de facturas por paquete:<span  v-if="paquetes===''" class="error">(*)</span></strong>
                                    <input type="number"  @input="validateInput($event, 'integer')"  class="form-control" v-model="paquetes"  placeholder="Debe ingresar numero entero de paquetes">
                                    <span  v-if="paquetes==''" class="error">Debe Ingresar formato</span>
                                </div>                           
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <strong>Token delegado:<span  v-if="token_delegado===''" class="error">(*)</span></strong>
                                    <textarea class="form-control" v-model="token_delegado" id="exampleFormControlTextarea1" rows="2" placeholder="ingrese el token delegado"></textarea>
                                     <span  v-if="token_delegado==''" class="error">Debe Ingresar formato</span>
                                 </div>
                                 <div class="form-group col-sm-6">
                                    <strong>Url QR:<span  v-if="qr_===''" class="error">(*)</span></strong>
                                    <textarea class="form-control"  v-model="qr_" id="exampleFormControlTextarea2" rows="2" placeholder="ingrese los datos"></textarea>
                                     <span  v-if="qr_==''" class="error">Debe Ingresar formato</span>
                                 </div>
                            </div>  
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <strong>Vencimiento token: <span  v-if="selectVenToken===''" class="error">(*)</span></strong>
                                    <input  type="date" class="form-control"  v-model="selectVenToken">       
                                </div>     
                                <div class="form-group col-sm-4">
                                    <strong>Maximo de tiempo para respuesta SIAT [seg]:</strong>
                                    <input type="text" @input="validateInput($event, 'integer')" class="form-control" v-model="maxTiempoResouesta"  placeholder="Tiempo de espera">                                 
                                </div> 
                                <div class="form-group col-sm-4"> 
                                    <strong>Tipo ambiente: <span  v-if="codigoModalidad===0" class="error">(*)</span></strong>
                                    <select  class="form-control"  v-model="codigoModalidad">
                                            <option value=0 disabled selected>Seleccionar...</option>
                                            <option value=1>Electrónica en linea</option>
                                            <option value=2>Computarizada en linea</option>
                                    </select>
                                    <span  v-if="codigoModalidad==''" class="error">Debe Ingresar codigo</span>
                                </div>
                                                    
                            </div> 
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <strong>Tipo de certificado: <span  v-if="selectCertificado===0" class="error">(*)</span></strong>
                                    <select  class="form-control"  v-model="selectCertificado">
                                            <option value=0 disabled selected>Seleccionar...</option>
                                            <option value=1>Todos</option>
                                            <option value=2>File_PEM_Value</option>
                                            <option value=3>File_P12</option>
                                    </select>
                                    <span  v-if="selectCertificado===0" class="error">Debe Ingresar codigo</span>     
                                </div> 
                       
                                <div class="form-group col-sm-4" v-if="selectCertificado==='1' || selectCertificado==='2'">
                                    <strong>Archivo P12:</strong>
                                        <input type="file" ref="fileInput" class="form-control" accept=".p12,.pem,.token,.crt,.cert"  @change="validateFile"/>
                                        <small v-if="errorMessage" class="text-danger">{{ errorMessage }}</small>                        
                                </div> 
                                <div class="form-group col-sm-4" v-if="selectCertificado==='1' || selectCertificado==='2'"> 
                                    <strong>Contraseña del archivo .p12: <span  v-if="password===''" class="error">(*)</span></strong>
                                    <input type="password"  v-model="password" placeholder="escriba la contraseña de archivo P.12" class="form-control">                                   
                                </div>
                            
                                        
                            </div>   
                            <div class="row">
                                <div class="form-group col-sm-4">

                                </div>     
                                    <div class="form-group col-sm-4"  v-if="selectCertificado==='1' || selectCertificado==='3'">
                                        <strong>Llave privada:<span  v-if="key_privade===''" class="error">(*)</span></strong>
                                        <textarea class="form-control" v-model="key_privade" id="exampleFormControlTextarea3" rows="3" placeholder="ingrese la llave privada que se le dio"></textarea>                                   
                                    </div> 
                                <div class="form-group col-sm-4"  v-if="selectCertificado==='1' || selectCertificado==='3'">
                                    <strong>Certificado X509:<span  v-if="certificado_x509===''" class="error">(*)</span></strong>
                                    <textarea class="form-control" v-model="certificado_x509" id="exampleFormControlTextarea5" rows="3" placeholder="ingrese elc ertificado"></textarea>                                      
                                </div>     
                            </div>   
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 d-flex justify-content-center">       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;">Actualizar configuración</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar configuración</button>
   
    </div>
</div>

                          
                        </div>
                        
                    </div>
                    <!--------------------------------------------------------------------------------------------------------------------------->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        <div class="card">
                            <div class="card-header">
                              End poends
                            </div>
                            <div class="alert alert-info" role="alert">
  La configuración solo afectara a datos del sistema tambien <strong>tomara los datos para la facturación</strong>
</div>
                            <div class="card-body">
                                <div class="form-group row">                                                  
                               
                                </div>  
                                <div class="form-group row">
                                 
                                </div> 
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 d-flex justify-content-center">
       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;" >Actualizar datos de empresa</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar datos de empresa</button>
   
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
//Vue.use(VeeValidate);

export default {
    components: { VueMultiselect},
     //---permisos_R_W_S
     props: ['codventana','idmodulo'],
        //-------------------
    data() {
        return {
            //---correo  
            tipoAccion:0,
            cod_sis:'',
            selectTipoAmbiente:0,
            forFecha:'',
            paquetes:'',
            token_delegado:'',
            qr_:'',
            codigoModalidad:0,
            selectVenToken:'',
            maxTiempoResouesta:'',
            certificado_x509:'', 
            password:'',
            key_privade:'',
            selectCertificado:0,
            errorMessage: "", // Para manejar mensajes de error
            isSubmitting_2: false, // Controla el estado del botón de envío
       
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
               

                isSubmitting: false, // Controla el estado del botón de envío
        };
    },

   

    computed: {
      
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

validateFile() {
    let me = this;
    const file = this.$refs.fileInput.files[0]; // Obtén el archivo seleccionado
    if (file) {
        const extension = file.name.split('.').pop().toLowerCase(); // Extrae la extensión
        const allowedExtensions = ["p12", "pem", "token", "crt", "cert"]; // Lista de extensiones permitidas

        if (!allowedExtensions.includes(extension)) {
            me.errorMessage = `Solo se permiten archivos con las extensiones: ${allowedExtensions.join(", ")}.`;
            me.$refs.fileInput.value = ""; // Reinicia el campo del archivo
            Swal.fire("Error", me.errorMessage, "error");
        } else {
            me.errorMessage = "";
            console.log("Archivo válido:", file);
            // Aquí puedes continuar con el procesamiento del archivo
        }
    } else {
        me.errorMessage = "Por favor selecciona un archivo.";
    }
},

    resetFecha(){
        let me=this;
        me.forFecha='yyyy-MM-ddTHH:mm:ss.fff';
    },

    triggerFileInput() {
      // Simula un clic en el input de archivo
      this.$refs.fileInput.click();
    },

    validateInput(event, type) {
        let me=this;
      let value = event.target.value; // Obtener el valor actual del input
      switch (type) {
        case "integer":
          value = value.replace(/\D/g, ""); // Permitir solo números
          break;
        case "alphanumeric":
          value = value.replace(/[^a-zA-Z0-9]/g, ""); // Permitir solo letras y números
          break;
        default:
          break;
      }
      event.target.value = value; // Actualizar el valor del input
      me.updateModel(event); // Sincronizar con v-model
    },

    updateModel(event) {
        let me=this;
      const model = event.target.getAttribute("v-model"); // Obtener el modelo vinculado
      if (model && this[model] !== undefined) {
        this[model] = event.target.value;
      }
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
         switch (accion) {
              
                case "regcuenta": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
   
                    me.classModal.openModal("regcuenta");
                    break;
                }
                case "regcuenta_edit":{
                    me.tipoAccion = 2;
                    me.isSubmitting=false;                  
                
                    me.classModal.openModal("regcuenta");
                    break;
                }                           
            }
        },
        cerrarModal(accion) {
            let me = this;
            
            if (accion == "regcuenta") {
                me.tipoAccion=1;
                me.isSubmitting=false;
                me.tituloModal="";
          
                me.classModal.closeModal(accion);         
            }
        },


        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();
            //-----------------------
   
        this.classModal.addModal("regcuenta");
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
