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
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-general" aria-selected="true" @click="listarIndexConfiguracion();cambioPestañaIn(1,0,0);">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" @click="cambioPestañaIn(0,1,0);">End Points</a>
                </li>       
                <li class="nav-item">
                    <a class="nav-link" id="pills-concepto-tab" data-toggle="pill" href="#pills-concepto" role="tab" aria-controls="pills-concepto" aria-selected="false" @click="listar_catalogo();cambioPestañaIn(0,0,1);">Conceptos</a>
                </li>    
                <li class="nav-item">
                    <a class="nav-link" id="pills-varios-tab" data-toggle="pill" href="#pills-varios" role="tab" aria-controls="pills-varios" aria-selected="false" @click="cambioPestañaIn(1,1,1);listarTablaList_siat();resetData(1);">Datos varios</a>
                </li>    
                <li class="nav-item">
                    <a class="nav-link" id="pills-gesPrueba-tab" data-toggle="pill" href="#pills-gesPrueba" role="tab" aria-controls="pills-gesPrueba" aria-selected="false" @click="cambioPestañaIn(0,1,1);listarListaSiat(3);listarIndexXML()">Gestor de prueba</a>
                </li>   
                            
            </ul>
        </div>
        <div class="card-body">           
             <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-general-tab" v-show="show_1==1&&show_2==0&&show_3==0">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
    <span>Configuración general de siat</span>
    <button type="button" class="btn btn-info ml-2 ml-sm-0" style="color: white;" @click="resetear_x()">Resetear datos</button>   
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
                                    <div class="d-flex align-items-center">
                                        <input type="text"  class="form-control" v-model="forFecha"  placeholder="Debe ingresar el formato de fecha">
                                        <button type="button" class="btn btn-info ml-2 ml-sm-0" style="color: white;" @click="autoComplentadoSiat(1)"><i class="fa fa-keyboard-o" aria-hidden="true"></i></button> 
                                    </div>
                                  
                                    <span  v-if="forFecha==''" class="error">Debe Ingresar formato</span>
                                </div>
                               
                                <div class="form-group col-sm-3">
                                    <strong>Maximo de facturas por paquete:<span  v-if="paquetes===''" class="error">(*)</span></strong>
                                    <div class="d-flex align-items-center">
                                        <input type="number"  @input="validateInput($event, 'integer')"  class="form-control" v-model="paquetes"  placeholder="Debe ingresar numero entero de paquetes">
                                        <button type="button" class="btn btn-info ml-2 ml-sm-0" style="color: white;" @click="autoComplentadoSiat(2)"><i class="fa fa-keyboard-o" aria-hidden="true"></i></button>   
                                
                                    </div>
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
                                      <div class="d-flex align-items-center">
                                <textarea class="form-control"  v-model="qr_" id="exampleFormControlTextarea2" rows="2" placeholder="ingrese los datos"></textarea>
                                       <button type="button" class="btn btn-info ml-2 ml-sm-0" style="color: white;" @click="autoComplentadoSiat(3)"><i class="fa fa-keyboard-o" aria-hidden="true"></i></button> 
                                  
                                      </div>
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
                                           <div class="d-flex align-items-center">
                                                <input type="text" @input="validateInput($event, 'integer')" class="form-control" v-model=" maxTiempoRespuesta"  placeholder="Tiempo de espera">                                 
                           <button type="button" class="btn btn-info ml-2 ml-sm-0" style="color: white;" @click="autoComplentadoSiat(4)"><i class="fa fa-keyboard-o" aria-hidden="true"></i></button> 
                                  
                                           </div>
                                </div> 
                                <div class="form-group col-sm-4"> 
                                    <strong>Tipo de modalidad: <span  v-if="codigoModalidad===0" class="error">(*)</span></strong>
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
                                    <button v-if="activarCambioFirma===0" type="button" @click="activarBoton()" class="btn btn-secondary btn-sm btn-block">Activar cambio de firma</button>
                                    <button v-else type="button" @click="desactivarBoton()" style="color: white;" class="btn btn-success btn-sm btn-block">Desactivar cambio de firma</button>
                                    <span  v-if="selectCertificado===0" class="error">Debe Ingresar codigo</span>     
                                </div>                      
                            
                                        
                            </div>  
                            
                            
                            
                            
                            <div class="row">
  <div class="col-sm-6">
    <div class="card"  v-if="selectCertificado==='1' || selectCertificado==='2'">
      <div class="card-body">
        <div class="alert alert-success" role="alert" v-if="activarCambioFirma===1">
  <h5 class="card-title">File_PEM_Value activado</h5>
</div>
 <div class="alert alert-danger" role="alert" v-else>
  <h5 class="card-title">File_PEM_Value sin activacion modo vista</h5>
</div>
       
        <p class="card-text">No necesita validacion solo la información.</p>
            <div class="row">
                                   
                                    <div class="form-group col-sm-6">
                                        <strong>Llave privada:<span  v-if="key_privade===''" class="error">(*)</span></strong>
                                        <textarea class="form-control" v-model="key_privade" id="exampleFormControlTextarea3" rows="3" placeholder="ingrese la llave privada que se le dio"></textarea>                                   
                                    </div> 
                                <div class="form-group col-sm-6" >
                                    <strong>Certificado X509:<span  v-if="certificado_x509===''" class="error">(*)</span></strong>
                                    <textarea class="form-control" v-model="certificado_x509" id="exampleFormControlTextarea5" rows="3" placeholder="ingrese elc ertificado"></textarea>                                      
                                </div>     
                            </div>  
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card" v-if="selectCertificado==='1' || selectCertificado==='3'">
      <div class="card-body">
        <div class="alert alert-success" role="alert" v-if="activarCambioFirma===1">
  <h5 class="card-title">Archivo P12 activado</h5>
</div>
 <div class="alert alert-danger" role="alert" v-else>
  <h5 class="card-title">Archivo P12 sin activacion modo vista</h5>
</div>
     
        <p class="card-text">Necesita certificacion y validacion previa.</p>
        <div class="row">
            <div class="form-group col-sm-6">
                                    <strong>Archivo P12:</strong>
                                        <input type="file" ref="fileInput" class="form-control" accept=".p12,.pem,.token,.crt,.cert"  @change="validateFile"/>                                        
                                        <small v-if="errorMessage" class="text-danger">{{ errorMessage }}</small>  
                                        <br>
                                        <strong v-if="name_firma===''||name_firma===null" style="color: red;">Sin firma</strong>
                                        <strong v-else style="color: green;">{{name_firma+' '+path_firma}}</strong>                      
                                </div> 
                                <div class="form-group col-sm-6"> 
                                    <strong>Contraseña del archivo .p12: <span  v-if="password===''" class="error">(*)</span></strong>
                                    <input type="password"  v-model="password" placeholder="escriba la contraseña de archivo P.12" class="form-control">  
                                    <strong v-if="data_pass===''||data_pass===null" style="color: red;">Sin contraseña</strong>
                                        <strong v-else style="color: green;">{{"contraseña ya disponible"}}</strong>                                   
                                </div>
        </div>
         <div class="row">
            <div class="form-group col-sm-3">
                <button type="button" class="btn btn-primary" @click="verificadorLlave()"> Validar</button>
            </div>
            <div class="form-group col-sm-9">
                <div class="alert alert-success" role="alert" v-if="activador_pen12===1">
                    Validación lista 
                </div>
                <div class="alert alert-danger" role="alert" v-else>
                    Valicadción sin datos    
                </div>
            </div>
        </div>        
        
        
      </div>
    </div>
  </div>
</div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-3 d-flex justify-content-center">       
        <button v-if="puedeEditar==1 && cod_sis!='' && selectTipoAmbiente!=0 && forFecha!='' && paquetes!='' && token_delegado!='' && qr_!='' && selectVenToken!='' && maxTiempoRespuesta!='' && codigoModalidad!=0 &&selectCertificado!=0" 
         type="button" class="btn btn-warning" style="color: white;" @click="crearConfiguracion()">Actualizar configuración</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar configuración</button>
   
    </div>
</div>

                          
                        </div>
                        
                    </div>
                    <!--------------------------------------------------------------------------------------------------------------------------->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" v-show="show_1==0&&show_2==1&&show_3==0">

                        <div class="card">
                            <div class="card-header">
                              End points
                            </div>
                            <div class="alert alert-info" role="alert">
  Recuerde que no debe haber ningun espacion en los <strong>end points</strong>
</div>
<div class="card-body">    
                                <div class="row">
                                <div class="form-group col-sm-1">
                                    <strong >Tipo:</strong>
                                </div> 
                                <div class="form-group col-sm-5">
                                     <select  class="form-control"  v-model="selectModalidad" @change="listarIndexEndPoint()">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Producción</option>
                                            <option value="2">Piloto</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <button v-show="selectModalidad==='1' && crear_x===1" type="button" class="btn btn-primary btn-sm btn-block" @click="abrirModal('regcuenta')"><i class="fa fa-link" aria-hidden="true" ></i> Crear end point</button>
                                 </div>  
                                 <div class="form-group col-sm-3">
                                    <button v-show="selectModalidad==='2' && crear_x===1" type="button" class="btn btn-info btn-sm btn-block" style="color: white;" @click="abrirModal('regcuenta')"><i class="fa fa-link" aria-hidden="true" ></i> Crear end point</button>
                                 </div>                                 
                                </div>    
                                 <!---inserte tabla-->
                            <div v-show="selectModalidad!='0'">
                                <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">ID</th>   
                        <th class="col-md-4">Descripción</th>                       
                        <th class="col-md-5">Url</th>
                        <th class="col-md-2">Versión</th>
                 
                    </tr>
                </thead> 
                <tbody>
                    <tr v-for="e in arrayEndpoint" :key="e.id">
                        <td>
                            <div  v-if="puedeEditar==1">
                                    <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px;" @click="abrirModal('regcuenta_edit',e)">
                                <i class="icon-pencil"></i>
                            </button> 
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                                </button> 
                            </div>
                        </td>
                        <td class="col-md-1">{{ e.id }}</td>
                        <td class="col-md-4">{{ e.Descripcion }}</td>
                        <td class="col-md-5">{{ e.Url }}</td>
                        <td class="col-md-2">{{ e.Version }}</td>
                   
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
            <!-----fin de tabla------->
              
                            </div>
                              
                          
                            </div>
                   
                        </div>
                    </div>        
                    <!-----------------------------------------------------------------CONCEPTOS----------------------------------------------------------->
             
                      <div class="tab-pane fade" id="pills-concepto" role="tabpanel" aria-labelledby="pills-concepto-tab"  v-show="show_1==0&&show_2==0&&show_3==1">
<div class="card">
    <div class="card-header">
      Catalogo SIAT
    </div>

<div class="card-body">    
        <div class="row">
        
        <div class="form-group col-sm-2">
            <strong >Actividad:</strong>
        </div> 
        <div class="form-group col-sm-4">
            <select class="form-control" v-model="selectCatalogo">
    <option value="0" disabled selected>Seleccionar...</option>
    
    <option v-for="i in arrayCatalogo" :key="i.id" :value="i.id">{{ "( "+i.id+")"+i.catalogo }}</option>
</select>
        </div>
        <div class="form-group col-sm-2">
                  <button v-show="selectCatalogo!='0'" @click="listar_emision(selectCatalogo)" type="button" class="btn btn-primary btn-sm btn-block" ><i class="fa fa-download" aria-hidden="true"></i> Descargar</button>                
        </div> 
        <div class="form-group col-sm-2">
            <label class="btn btn-primary btn-sm btn-block" v-show="selectCatalogo!='0'">
      <i class="fa fa-upload" aria-hidden="true"></i> Subir
      <input type="file" @change="handleFileUpload" accept=".xlsx, .xls" style="display: none;" />
    </label>
 </div> 
        
                                      
        </div>    
         <!---inserte tabla-->
 
      
  
    </div>

</div>
</div>    

<!---------------------------------------------------------------------------VARIOS---------------------------------------------------->

 <div class="tab-pane fade" id="pills-varios" role="tabpanel" aria-labelledby="pills-varios-tab"  v-show="show_1==1&&show_2==1&&show_3==1">
<div class="card">
    <div class="card-header">
      Complementos de configuración          
    </div>
    <div class="card-body">
       <div class="row">
            <div class="form-group col-sm-2">
            </div>
            <div class="form-group col-sm-4">
                  <button type="button" style="color: white;" class="btn btn-info btn-sm btn-block" @click="configDefault_xd()"><i class="fa fa-cogs" aria-hidden="true"></i> Configuracion por default</button>                
       
            </div>
            <div class="form-group col-sm-6">
            </div>
        </div>    
        <div class="row">
        <div class="form-group col-sm-2">
            <strong >Tipo de emision:</strong>
        </div> 
        <div class="form-group col-sm-4">
            <select class="form-control" v-model="selectEmision_datos" v-show="arrayEmision_datos.length>0">
                <option value="0" disabled selected>Seleccionar...</option>    
                <option v-for="(i, index) in arrayEmision_datos" :key="index" :value="i.codigo">{{ i.descripcion }}</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
                  <button type="button" style="color: white;" class="btn btn-info btn-sm btn-block" @click="listarListaSiat(1)"><i class="fa fa-assistive-listening-systems" aria-hidden="true"></i> Listar</button>                
        </div> 
        <div class="form-group col-sm-2">
                  <button  v-if="selectEmision_datos=='0'" style="color: white;" type="button" class="btn btn-secondary btn-sm btn-block"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
                  <button  v-else type="button" style="color: white;" class="btn btn-primary btn-sm btn-block"  @click="cargarListaSiat(1,selectEmision_datos)"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
        
        </div>                               
        </div>  
        
        <div class="row">
        <div class="form-group col-sm-2">
            <strong >Tipo de sector:</strong>
        </div> 
        <div class="form-group col-sm-4">
            <select class="form-control" v-model="selectSector_datos" v-show="arraySector_datos.length>0">
                <option value="0" disabled selected>Seleccionar...</option>    
                <option v-for="(i, index) in arraySector_datos" :key="index" :value="i.codigo">{{ i.descripcion }}</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
                  <button type="button" style="color: white;" class="btn btn-info btn-sm btn-block" @click="listarListaSiat(3)"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</button>                
        </div> 
        <div class="form-group col-sm-2">
                  <button  v-if="selectSector_datos=='0'" style="color: white;" type="button" class="btn btn-secondary btn-sm btn-block"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
                  <button  v-else type="button" style="color: white;" class="btn btn-primary btn-sm btn-block"  @click="cargarListaSiat(3,selectSector_datos)"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
        
        </div>                               
        </div> 


        <div class="row">
        <div class="form-group col-sm-2">
            <strong >Tipo de moneda:</strong>
        </div> 
        <div class="form-group col-sm-4">
            <select class="form-control" v-model="selectMoneda_datos" v-show="arrayMoneda_datos.length>0">
                <option value="0" disabled selected>Seleccionar...</option>    
                <option v-for="(i, index) in arrayMoneda_datos" :key="index" :value="i.codigo">{{ i.descripcion }}</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
                  <button type="button" style="color: white;" class="btn btn-info btn-sm btn-block" @click="listarListaSiat(9)"><i class="fa fa-money" aria-hidden="true"></i> Listar</button>                
        </div> 
        <div class="form-group col-sm-2">
                  <button  v-if="selectMoneda_datos=='0'" style="color: white;" type="button" class="btn btn-secondary btn-sm btn-block"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
                  <button  v-else type="button" style="color: white;" class="btn btn-primary btn-sm btn-block"  @click="cargarListaSiat(9,selectMoneda_datos)"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
        
        </div>                               
        </div> 
        <div class="row">
        <div class="form-group col-sm-2">
            <strong >Tipo de leyenda:</strong>
        </div> 
        <div class="form-group col-sm-4">
            <select class="form-control" v-model="selectLeyenda_datos" v-show="arrayLeyenda_datos.length>0">
                <option value="0" disabled selected>Seleccionar...</option>    
                <option v-for="(i, index) in arrayLeyenda_datos" :key="index" :value="i.codigo" v-show="i.id_erp!=null">{{ i.descripcion }}</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
                  <button type="button" style="color: white;" class="btn btn-info btn-sm btn-block" @click="listarListaSiat(11)"><i class="fa fa-align-center" aria-hidden="true"></i> Listar</button>                
        </div> 
        <div class="form-group col-sm-2">
                  <button  v-if="selectLeyenda_datos=='0'" style="color: white;" type="button" class="btn btn-secondary btn-sm btn-block"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
                  <button  v-else type="button" style="color: white;" class="btn btn-primary btn-sm btn-block"  @click="cargarListaSiat(11,selectLeyenda_datos)"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
        
        </div>                               
        </div>
        
          <div class="row">
        <div class="form-group col-sm-2">
            <strong >Tipo de factura:</strong>
        </div> 
        <div class="form-group col-sm-4">
            <select class="form-control" v-model="selectFactura_datos" v-show="arrayFactura_datos.length>0">
                <option value="0" disabled selected>Seleccionar...</option>    
                <option v-for="(i, index) in arrayFactura_datos" :key="index" :value="i.codigo" >{{ i.descripcion }}</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
                  <button type="button" style="color: white;" class="btn btn-info btn-sm btn-block" @click="listarListaSiat(2)"><i class="fa fa-file-text-o" aria-hidden="true"></i> Listar</button>                
        </div> 
        <div class="form-group col-sm-2">
                  <button  v-if="selectFactura_datos=='0'" style="color: white;" type="button" class="btn btn-secondary btn-sm btn-block"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
                  <button  v-else type="button" style="color: white;" class="btn btn-primary btn-sm btn-block"  @click="cargarListaSiat(2,selectFactura_datos)"><i class="fa fa-envelope" aria-hidden="true"></i> Activar</button>                
        
        </div>                               
        </div> 
        <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>id catalogo</th>
                        <th>codigo</th>   
                        <th>Descripción</th>
                    </tr>
                </thead> 
                <tbody>
                    <tr v-for="(i, index) in arrayListaTabla_xd" :key="index">
                        <td>{{i.id_catalogo}}</td>
                        <td>{{i.codigo}}</td>
                        <td>{{i.descripcion}}</td>
                    </tr>
                </tbody>
           </table>         
         <!---inserte tabla-->
 
      
  
    </div>


</div>
</div>    
     <!-----------------------------------------------------------------GESTOR DE PRUEBA ----------------------------------------------------------->
    
 <div class="tab-pane fade" id="pills-gesPrueba" role="tabpanel" aria-labelledby="pills-gesPrueba-tab"  v-show="show_1==0&&show_2==1&&show_3==1">
<div class="card">
    <div class="card-header">
      Gestor de prueba programado        
    </div>
    <div class="card-body">
      <button   type="button" class="btn btn-primary btn-sm" @click="abrirModal('gestorPruebaModal');"> Crear XML</button>                
      
      <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
 <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th> 
                        <th class="col-md-1">Numero</th> 
                        <th class="col-md-3">Nombre</th> 
                        <th class="col-md-2">Sector</th> 
                        <th class="col-md-3">Modalidad</th> 
                        
                    </tr>
                </thead> 
                <tbody>
                    <tr  v-for="(i, index) in arrayIndex_xml" :key="index">
                        <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                            <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px;" @click="abrirModal('gestorPruebaModal_actulizar',i)">
                                <i class="icon-pencil"></i>
                            </button> 
                             <button  type="button" class="btn btn-danger btn-sm" style="margin-right: 5px;" @click="eliminarXML_2(i.id)">
                                <i class="icon-trash"></i>
                            </button>
                            <button  type="button" class="btn btn-primary  btn-sm" style="margin-right: 5px;" @click="hacer_prueba_siat(i)">
                               <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            </button>
                           
                            </div>
                            
                        </td>
                        <td class="col-md-1">{{i.id}}</td>
                        <td class="col-md-3">{{i.nombre}}</td>
                        <td class="col-md-2">{{i.sector}}</td>
                        <td class="col-md-3">
                            <span v-if="i.modalidad==1">Electronica</span>
                            <span v-else-if="i.modalidad==2">Computarizada</span>
                             <span v-else>Error</span>                             
                        </td>
                    </tr>
                   
                </tbody>
           </table> 
      </div>
        
   
               
         <!---inserte tabla-->
 
      
  
    </div>


</div>
</div>    
 <!-----------------------------------------------------------------TIPO LEYENDA ----------------------------------------------------------->
             
           
<!---------------------------------------------------------------------------------------------------------------------------->
            </div>            
        </div>
    </div>
</div>

 <!--Inicio del modal agregar/actualizar-->
 <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="regcuenta" aria-hidden="true"
            data-backdrop="static" data-key="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('regcuenta')">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="alert alert-info" role="alert">
                            Debe verificar si los datos no tiene espacios
                        </div>

                        <form action="" class="form-horizontal">                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                <div class="form-group row" >                                   
                                    <div class="col-md-5">
                                        <label>Descripción: </label>
                                        <input v-if="tipoAccion===1" type="text" class="form-control" v-model="descripcion_endpoint">
                                        <input v-else type="text" class="form-control" v-model="descripcion_endpoint" disabled>
                                        <span  v-if="descripcion_endpoint==''" class="error">Debe llenar el dato</span> 
                                    </div>
                                    <div class="col-md-5">
                                    <label for="end-date">URL: </label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" v-model="url_endpoint" rows="3"></textarea>
                                    
                                        <span  v-if="url_endpoint==''" class="error">Debe llenar el dato</span> 
                                    </div>   
                                    <div class="col-md-2">
                                    <label >Versión:</label>
                                            <input  v-if="tipoAccion===1" type="text" class="form-control" v-model="version_endpoint">
                                            <input  v-else type="text" class="form-control" v-model="version_endpoint" disabled>
                                            <span  v-if="version_endpoint==''" class="error">Debe llenar el dato</span> 
                                    </div>     
                                </div> 
                               
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('regcuenta')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button v-if="tipoAccion===1" @click="crearEndPoint()" type="button" class="btn btn-primary rounded" :disabled="descripcion_endpoint===''|| url_endpoint==='' || version_endpoint===''">Guardar</button>
                                <button v-else type="button" @click="editarEndPoint()" class="btn btn-primary rounded" :disabled="descripcion_endpoint===''|| url_endpoint==='' || version_endpoint===''">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                                <button type="button" v-else class="btn btn-light">Actualizar</button>
                            </div>
                        </div>
                                             
                    </div>
                </div>
            </div>
        </div>
        <!-------------------------modal gestor de pruebas----------------------->
         <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('gestorPruebaModal')">
                            <span>&times;</span>
                        </button>
                        </div>
  <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">                        

                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                <div class="form-group row" >                                   
                                    <div class="col-md-4">
                                        <label for="">Nombre: </label>
                                        <input  type="text" class="form-control" v-model="nomGesPrueba">
                                    </div>
                                    <div class="col-md-4">  
                                         <label for="">Sector: </label>
                                         <select class="form-control" v-model="selectSector_datos" >
                <option value="0" disabled selected>Seleccionar...</option>    
                <option v-for="(i, index) in arraySector_datos" :key="index" :value="i.codigo">{{ i.descripcion }}</option>
            </select>
                                     </div>   
                                     <div class="col-md-4">  
                                         <label for="">Modalidad: </label>
                                         <select class="form-control" v-model="selectModalidadGesPrueba" >
                <option value="0" disabled selected>Seleccionar...</option>    
               <option value="1">Electronica</option> 
               <option value="2">Computarizada</option>    
            </select>
                                     </div>  
                                </div>  
                                 <div class="form-group row" >                                   
                                    <div class="col-md-4">
                                    <label for="">Codigo de emision</label>
                                         <select class="form-control" v-model="selectEmisionGesPrueba" >
                <option value="0" disabled selected>Seleccionar...</option>    
               <option value="1">En linea</option> 
               <option value="2">Offline</option>
               <option value="3">Masiva</option>    
            </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Punto de venta</label>
                                        <select class="form-control" v-model="selectPuntoVenPrueba" >
                <option value="3" disabled selected>Seleccionar...</option>    
               <option value="0">Casa matriz</option> 
               <option value="1">Punto de venta</option>    
            </select>
                                    </div>
                                </div>        
                                       <label for="">XML: </label>
                                            <textarea class="form-control" id="exampleFormControlTextarea1" v-model="xmlGesPrueba" rows="16"></textarea>
                                  
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('gestorPruebaModal')">Cerrar</button>   
                        <button type="button" v-if="isSubmitting==false" v-show="tipoAccion==1" class="btn btn-primary" @click="enviarXML_2()">Guardar</button>
                        <button type="button" v-else class="btn btn-secondary" v-show="tipoAccion==1">Guardar</button>
                        <button type="button" v-if="isSubmitting==false" v-show="tipoAccion==2" class="btn btn-warning"  @click="actualizarXML_2()">Actualizar</button>
                        <button type="button" v-else class="btn btn-secondary" v-show="tipoAccion==2">Actualizar</button >                       
                    </div>

                    </div>
                </div>
            </div>
           </transition>   
        <!--fin del modal-->
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import { data } from "jquery";
import * as XLSX from 'xlsx';



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

          show_1:0,
          show_2:0,
          show_3:0,

            tipoAccion:0,
            cod_sis:'',
            selectTipoAmbiente:0,
            forFecha:'',
            paquetes:'',
            token_delegado:'',
            qr_:'',
            codigoModalidad:0,
            selectVenToken:'',
            maxTiempoRespuesta:'',
            certificado_x509:'', 
            password:'',
            data_pass:'',
            key_privade:'',
            selectCertificado:0,
            errorMessage: 'Seleccione el archivo', // Para manejar mensajes de error
            id_configuracion:'',
            tituloModal:'',

            
            selectModalidad:'0',

            descripcion_endpoint:'',
            url_endpoint:'',
            version_endpoint:'',
            arrayEndpoint:[],
            id_endpoint:'',
            crear_x:0,
            archivo:'',
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
               

                isSubmitting: false, // Controla el estado del botón de envío
            //--catalogo
            arrayCatalogo:[],
            selectCatalogo:'0',
            nombreCatalogo:'',
            activarCambioFirma:0,
            name_firma:'',
            path_firma:'',
            //--leyenda
            arrayLeyenda:[],
            selectLeyenda:"0",
            //--factura
            arrayFactura_v:[],
            selectFactura_v:"0",
            activador_pen12:0,
            //--datos varios
            selectEmision_datos:'0',
            arrayEmision_datos:[],

            selectSector_datos:'0',
            arraySector_datos:[],

            selectMoneda_datos:'0',
            arrayMoneda_datos:[],

             selectLeyenda_datos:'0',
            arrayLeyenda_datos:[],

             selectFactura_datos:'0',
            arrayFactura_datos:[],

            rubro_xd:0,
            cambioEstado:'',
            arrayListaTabla_xd:[],
            nombreXml:'',
            textXML:'',
            showModal: false,

            nomGesPrueba:'',
            xmlGesPrueba:'', 
            arrayIndex_xml:[],
            idGesPrueba:'',
            selectModalidadGesPrueba:'0',
            selectPuntoVenPrueba:'3',
            selectEmisionGesPrueba:'0',
        };
    },

    watch: {
        selectCatalogo: function (newValue) {
                this.nombreCatalogo='';
                let selector = this.arrayCatalogo.find(
                    (element) => element.id === newValue,

                );
          
               if (selector) {
                   this.nombreCatalogo = selector.catalogo;                 
                }          
        },
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
       
        });
},
//-------------------------------------------------------------- 

resetData(dato){
    let me=this;
    switch (dato) {
        case 1:
                    me.selectEmision_datos="0";
                    me.arrayEmision_datos=[];
           
                    me.selectSector_datos="0";
                    me.arraySector_datos=[];
        
                    me.selectMoneda_datos="0";
                    me.arrayMoneda_datos=[];
           
                    me.selectLeyenda_datos="0";
                    me.arrayLeyenda_datos=[];
             
                    me.selectFactura_datos="0";
                    me.arrayFactura_datos=[];    
        break;
    
        default:
            break;
    }
},

autoComplentadoSiat(data){
    let me = this;
    switch (data) {
        case 1:
           me.forFecha="yyyy-MM-ddTHH:mm:ss.ffff"; 
        break;
        case 2:
           me.paquetes =50; 
        break;
        case 3:
            if (me.selectTipoAmbiente===1) {
                me.qr_ ="https://siat.impuestos.gob.bo/consulta/QR?nit={nit_emisor}&cuf={cuf}&numero={nro_factura}&t={formato 1=rollo/ 2= A4 carta default= 2}"; 
            } else {
                if (me.selectTipoAmbiente===2) {
                    me.qr_ ="https://pilotosiat.impuestos.gob.bo/consulta/QR?nit={nit_emisor}&cuf={cuf}&numero={nro_factura}&t={formato 1=rollo/ 2= A4 carta default= 2}"; 
                } else {
                    Swal.fire("Error!","debe seleccionar un tipo de ambiente","error",); 
                }
            }           
        break;
        case 4:
           me.maxTiempoRespuesta ="10"; 
        break;
    
        default:
             Swal.fire("Error!","de entrada","error",); 
        break;
    }
},

 listarIndexXML()
            {
                let me=this;  
                 var url='/siat/inicio_xml_2';                      
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.arrayIndex_xml = respuesta;  
                    console.log(me.arrayIndex_xml);                 
                })
                .catch(function(error){
                    error401(error);
                });               
            },

            
 hacer_prueba_siat(data)
{
    let me = this;
    let id=data.id;
    axios.get('/siat/hacer_prueba_siat', {
        params: {
            id: data.id
        }
    })
    .then(function(response){
        var respuesta = response.data;
        Swal.fire("Respuesta!",respuesta,"warning",);
    })
    .catch(function(error){
        error401(error);
    });
},

 listarTablaList_siat() {         
    let me = this;  
    me.arrayListaTabla_xd=[];         
    var url = '/siat/listarTablaList_siat';  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;
            me.arrayListaTabla_xd = respuesta;        
        })
        .catch(function(error) {
            error401(error);       
        });
},

configDefault_xd(){
  let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-success",
    cancelButton: "btn btn-danger"
  },
  buttonsStyling: false
});
swalWithBootstrapButtons.fire({
  title: "Desea asignar valores por defecto?",
  text: "Este cambio cambiara toda la informacion dada!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonText: "Si",
  cancelButtonText: "No",
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
   axios.post("/siat/cargar_todo", {
                           
                })
                .then(function (response) {
                
                    let respuesta=response.data;
                    me.listarTablaList_siat();
                    if (respuesta==0) {
                        Swal.fire( "Carga!","Existosa","success");
                    } else {
                         Swal.fire("Error!",respuesta,"error",);  
                    }                   
                                
                })               
                .catch(function (error) {                
                         
            }); 
  } 
});
},

listarListaSiat(codigo) {
         
    let me = this;       
    let url ='/siat/listarListaSiat?id_catalogo='+codigo+'&id_rubro='+me.rubro_xd;
  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;
            switch (codigo) {
                case 1:
                    me.selectEmision_datos="0";
                    me.arrayEmision_datos=respuesta;
                break;
                case 3:
                    me.selectSector_datos="0";
                    me.arraySector_datos=respuesta;
                break;
                case 9:
                    me.selectMoneda_datos="0";
                    me.arrayMoneda_datos=respuesta;
                break;
                case 11:
                    me.selectLeyenda_datos="0";
                    me.arrayLeyenda_datos=respuesta;
                break;
                case 2:
                    me.selectFactura_datos="0";
                    me.arrayFactura_datos=respuesta;
                break;
            
                default:
                    break;

                     
            }
        
            console.log(respuesta);          
        })
        .catch(function(error) {
            error401(error);       
        });
},


cargarListaSiat(id_catalogo,codigo){
        let me = this;  
         me.cambioEstado="";
       console.log(id_catalogo+"-"+codigo);
        
    let resultado;

            if (codigo=='0'||codigo==null || codigo=='') {
                 Swal.fire("Error!","no exite seleccion","error",);  
            } else {

                  switch (id_catalogo) {
                case 1:
                     resultado = me.arrayEmision_datos.find(item => item.codigo === codigo);
            if (resultado) {
            me.cambioEstado=resultado.descripcion;
            } else {
             me.cambioEstado="no existe descripcion";
            }
                break;
                case 3:
                      resultado = me.arraySector_datos.find(item => item.codigo === codigo);
            if (resultado) {
            me.cambioEstado=resultado.descripcion;
            } else {
             me.cambioEstado="no existe descripcion";
            }                  
                break;
                case 9:
                       resultado = me.arrayMoneda_datos.find(item => item.codigo === codigo);
            if (resultado) {
            me.cambioEstado=resultado.descripcion;
            } else {
             me.cambioEstado="no existe descripcion";
            }                   
                break;
                case 11:
                      resultado = me.arrayLeyenda_datos.find(item => item.codigo === codigo);
            if (resultado) {
            me.cambioEstado=resultado.descripcion;
            } else {
             me.cambioEstado="no existe descripcion";
            }  
                break;
                case 2:
                      resultado = me.arrayFactura_datos.find(item => item.codigo === codigo);
            if (resultado) {
            me.cambioEstado=resultado.descripcion;
            } else {
             me.cambioEstado="no existe descripcion";
            }  
                   
                break;
            
                default:
                    me.cambioEstado="Error";
                    break;

                     
            }
               
            axios.post("/siat/cargarListaSiat", {
                id_catalogo:id_catalogo,
                codigo:codigo,
                descripcion:me.cambioEstado,               
                            
                })
                .then(function (response) {
                
                    let respuesta=response.data;  
                  me.listarTablaList_siat();
                    console.log(respuesta);                 
                    if (respuesta==0) {
                        Swal.fire("Registro creado!","Correctamente","success",);  
                        switch (id_catalogo) {
                            case 1:
                                me.selectEmision_datos=codigo;
                            break;
                            case 3:
                                me.selectSector_datos=codigo;
                            break;
                            case 9:
                                me.selectMoneda_datos=codigo;
                            break;
                            case 11:
                                me.selectLeyenda_datos=codigo;
                            break;
                        
                            default:
                                break;
                        }  
                    } else {                         
                         Swal.fire("Error!",respuesta,"error",);   
                    }              
                })               
                .catch(function (error) {             
            });                      
            } 
 },




cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;
            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        }, 

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
           me.listarIndexEndPoint(page);
        },

        cambioPestañaIn(data_1,data_2,data_3){
            let me=this;
            me.show_1=data_1;
            me.show_2=data_2;
            me.show_3=data_3; 
        },
       
    listarIndexEndPoint(page)
            {
                let me=this;     
                me.arrayEndpoint=[];         
                    var url='/siat/index_endpoint?page='+page+'&tipo='+me.selectModalidad;                         
                axios.get(url).then(function(response){
                    var respuesta = response.data; 
                    me.arrayEndpoint=respuesta.index.data;  
                    me.pagination = respuesta.pagination;                                
                })
                .catch(function(error){
                    error401(error);
                });                       
            },

            activarBoton(){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-success",
    cancelButton: "btn btn-danger"
  },
  buttonsStyling: false
});
swalWithBootstrapButtons.fire({
  title: "Cambiar firma?",
  text: "Este cambio borrar la información anterior!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonText: "Si",
  cancelButtonText: "No",
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
    me.activarCambioFirma=1;  
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {
    me.activarCambioFirma=0;  
  }
});
    
            },
            desactivarBoton(){
                let me=this;
                me.activarCambioFirma=0;
            },    

crearEndPoint(){
        let me = this;
    if (me.crear_x===1) {
        if (me.isSubmitting) return;
                me.isSubmitting = true; // Deshabilita el botón
                axios.post("/siat/crear_endpoint", {
                descripcion:me.descripcion_endpoint,
                endpoint:me.url_endpoint,
                version:me.version_endpoint,  
                prod_piloto:me.selectModalidad,         
                })
                .then(function (response) {
                    me.listarIndexEndPoint(); 
                    let respuesta=response.data;    
             
                    if (respuesta.length>0) {
                        Swal.fire(
                        "Error!",
                        ""+respuesta,
                        "error",
                    );    
                    } else {
                        Swal.fire(
                        "Registro creado!",
                        "Correctamente",
                        "success",
                    );  
                    }
                    me.cerrarModal('regcuenta');
                                
                })               
                .catch(function (error) {                
                            
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });       
    } 
 },


eliminarXML_2(id){
        let me = this;  
    Swal.fire({
  title: "Desea eliminar?",
  text: "La eliminación no se puede revertir!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, eliminar!"
}).then((result) => {
  if (result.isConfirmed){
    axios.post("/siat/eliminarXML_2", {
                    id:id,             
                })
                .then(function (response) {
                   // me.listarIndexEndPoint(); 
                   me.isSubmitting=false;
                   let respuesta=response.data;                                 
                    if (respuesta==0) {
                        me.listarIndexXML();
                        Swal.fire("Registro eliminado!","Correctamente","success",);                         
                    } else {
                         Swal.fire("Error!",""+respuesta,"error",);   
                    }  
                                
                })               
                .catch(function (error) {        
                         
            }); 
  }
});
                   
 },
 
actualizarXML_2(){
        let me = this;  
        if (me.nomGesPrueba==null ||me.nomGesPrueba=="" ||
            me.xmlGesPrueba==null || me.xmlGesPrueba=="" ||
            me.selectSector_datos==null || me.selectSector_datos=="0" ||
            me.selectModalidadGesPrueba==null || me.selectModalidadGesPrueba=="0"
            ||me.selectPuntoVenPrueba=="3"||me.selectEmisionGesPrueba=="0"
        ) {
                Swal.fire("Error!","Debe llenar todos los campos","error",);  
                return ;   
        } 
         me.isSubmitting=true;  
                axios.put("/siat/actualizarXML_2", {
                    id:me.idGesPrueba,
                nomGesPrueba:me.nomGesPrueba, 
                xmlGesPrueba:me.xmlGesPrueba,
                sector:me.selectSector_datos,
                modalidad:parseInt(me.selectModalidadGesPrueba),
                  selectPuntoVenPrueba:parseInt(me.selectPuntoVenPrueba),
                selectEmisionGesPrueba:parseInt(me.selectEmisionGesPrueba),                  
                })
                .then(function (response) {
                   // me.listarIndexEndPoint(); 
                   me.isSubmitting=false;
                   let respuesta=response.data;  
                     me.cerrarModal('gestorPruebaModal');                 
                    if (respuesta==0) {
                        me.listarIndexXML();
                        Swal.fire("Registro editado!","Correctamente","success",);                         
                    } else {
                         Swal.fire("Error!",""+respuesta,"error",);   
                    }  
                                
                })               
                .catch(function (error) {                
                         
            });    
 },

  enviarXML_2(){
        let me = this;  
        if (me.nomGesPrueba==null ||me.nomGesPrueba=="" ||
            me.xmlGesPrueba==null || me.xmlGesPrueba=="" ||
            me.selectSector_datos==null || me.selectSector_datos=="0"||
            me.selectModalidadGesPrueba==null || me.selectModalidadGesPrueba=="0"
            ||me.selectPuntoVenPrueba=="3"||me.selectEmisionGesPrueba=="0"        
        ) {
                Swal.fire("Error!","Debe llenar todos los campos","error",);  
                return ;   
        } 
         me.isSubmitting=true;  
                axios.post("/siat/enviarXML_2", {
              
                nomGesPrueba:me.nomGesPrueba, 
                xmlGesPrueba:me.xmlGesPrueba,
                sector:me.selectSector_datos,
                modalidad:parseInt(me.selectModalidadGesPrueba),
                  selectPuntoVenPrueba:parseInt(me.selectPuntoVenPrueba),
                selectEmisionGesPrueba:parseInt(me.selectEmisionGesPrueba),            
                })
                .then(function (response) {
                   // me.listarIndexEndPoint(); 
                   me.isSubmitting=false;
                   let respuesta=response.data;  
                     me.cerrarModal('gestorPruebaModal');                 
                    if (respuesta==0) {
                        me.listarIndexXML();
                        Swal.fire("Registro creado!","Correctamente","success",);                         
                    } else {
                         Swal.fire("Error!",""+respuesta,"error",);   
                    }  
                                
                })               
                .catch(function (error) {                
                         
            });    
 },

 editarEndPoint(){
        let me = this;    
                axios.put("/siat/editar_endpoint", {
                id:me.id_endpoint,
                endpoint:me.url_endpoint,               
                })
                .then(function (response) {
                    me.listarIndexEndPoint(); 
                    let respuesta=response.data;                   
                    if (respuesta.length>0) {
                        Swal.fire("Error!",""+respuesta,"error",);    
                    } else {
                        Swal.fire("Registro creado!","Correctamente","success",);  
                    }
                    me.cerrarModal('regcuenta');
                                
                })               
                .catch(function (error) {                
                         
            });       
   
 },

    crearConfiguracion(){
                let me = this; 
                const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-success",
    cancelButton: "btn btn-danger"
  },
  buttonsStyling: false
});
swalWithBootstrapButtons.fire({
  title: "¿Esta seguro?",
  text: "¡De cambiar la información!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonText: "Si, Actualizar!",
  cancelButtonText: "No, cancelar!",
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {

   

    let controlador=0;
               switch (data) {
                case '0': 
                controlador=1;                 
                Swal.fire({icon: "error",title: "Tipo de certificado", text: "Sin seleccionar",});              
                    break;

                    case '1':
                     if (me.errorMessage!='' && me.password!='' && me.errorMessage!='' && me.password==='' ) {
                        controlador=1;
                        Swal.fire({icon: "error",title: "opcion Todo seleccionada", text: "Verifique si todos los campos estan llenos",});
                     }   
                    break;
                case '2':
                     if (me.errorMessage!='' && me.password!='') {
                        controlador=1;
                        Swal.fire({icon: "error",title: "Archivo P12 o Contraseña del archivo .p12", text: "Esta seleccionadas mal",});
                     }   
                    break;
                case '3':
                     if (me.errorMessage!='' && me.password!='' ) {
                        controlador=1;
                        Swal.fire({icon: "error",title: "Llave privada o Certificado X509", text: "Estan ",});
                     }   
                    break;    
                default:
                    controlador=0;
                    break;
               }

               if (me.activarCambioFirma===1) {
                    if (me.selectCertificado==1||me.selectCertificado==3) {
                        if (me.activador_pen12!=1) {
                            Swal.fire({icon: "error",title: " Error de activación", text: "Debe validar la llave pem.12 para esta opcion",});
                            return ;
                        }
                    }
               }

               if (controlador===0) {              
                 
                // Crear un objeto FormData para enviar el archivo
                const formData = new FormData();
                formData.append('id', me.id_configuracion);
                formData.append('cod_sis', me.cod_sis);
                formData.append('selectTipoAmbiente', me.selectTipoAmbiente);
                formData.append('forFecha', me.forFecha);
                formData.append('paquetes', me.paquetes);
                formData.append('token_delegado', me.token_delegado);
                formData.append('qr_', me.qr_);
                formData.append('selectVenToken', me.selectVenToken);
                formData.append('maxTiempoRespuesta', me.maxTiempoRespuesta);
                formData.append('codigoModalidad', me.codigoModalidad); 
                formData.append('activarCambioFirma', me.activarCambioFirma);               

                formData.append('selectCertificado', me.selectCertificado);
                formData.append('password', me.password);
                formData.append('firma', me.archivo);
 
                formData.append('key_privade', me.key_privade);
                formData.append('certificado_x509', me.certificado_x509);

                formData.append('id_modulo', me.idmodulo);
                formData.append('id_sub_modulo', me.codventana);
                formData.append('des', 'actualziacion la configuracion siat configuracion');
                axios.post('/siat/crear_configuracion', formData, {headers : {'content-type': 'multipart/form-data'}})
                .then(function(response){
                    me.password="";
                    me.activador_pen12=0;
                    var respuesta = response.data; 
                      console.log(respuesta);  
                    if (respuesta.length>0) {                        
                        Swal.fire("Error!",""+respuesta,"error",);
                    }else{                        
                        Swal.fire("Datos!","actualziado correctamente","success",);
                    }      
                    me.listarIndexConfiguracion(); 
                    me.activarCambioFirma=0;                   
                }).catch(function(error){
                    me.password="";
                    error401(error);
     
                });
               } 
 
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) { 
  }
});                           
    },

    
    verificadorLlave()
        {
            let me=this;      
 const formData = new FormData();
                formData.append('password', me.password);
                formData.append('firma', me.archivo);

              
                axios.post('/siat/verificador_llave', formData)
                .then(function(response){
                 let respuesta = response.data;      
                 console.log(respuesta);       
                   if (respuesta===0) {     
                    me.activador_pen12=respuesta;                   
                        Swal.fire("Error!","La contraseña es incorrecta","error",);
                    }else{                        
                        if (respuesta===1) {
                            me.activador_pen12=respuesta;
                        Swal.fire("Validacion de !","Contraseña es correctacta","success",);    
                        } else {
                            me.activador_pen12=respuesta;
                            Swal.fire("Error!","Error de entrada","error",);
                        }                        
                    } 
                                   
                }).catch(function(error){                  
                    error401(error);     
                });          
    
                       
            },

listarIndexConfiguracion()
            {
                let me=this;                
            
                    var url='/siat/configuracion';                        
                axios.get(url).then(function(response){
                    var respuesta = response.data; 
                    me.cod_sis=respuesta.cod_sis;
                    me.selectTipoAmbiente=respuesta.tipo_ambiente;
                    me.forFecha=respuesta.formato_fecha;
                    me.paquetes=respuesta.max_paquete;
                    me.token_delegado=respuesta.token_delegado;
                    me.qr_=respuesta.url_QR;
                    me.codigoModalidad=respuesta.tipo_modalidad;
                    me.selectVenToken=respuesta.vencimiento_token;
                    me.maxTiempoRespuesta=respuesta.tiempo_espera;
                    me.id_configuracion=respuesta.id;
                    me.selectCertificado=respuesta.tipo_certificado;
                    me.data_pass=respuesta.password;
                    me.key_privade=respuesta.llave_privada;
                    me.certificado_x509=respuesta.certificado_x509;
                    me.name_firma=respuesta.name;
                    me.path_firma=respuesta.path;
                           
                    
                })
                .catch(function(error){
                    error401(error);
                }); 
                       
            },

validateFile() {
    let me = this;
    me.archivo="";
    const file = this.$refs.fileInput.files[0]; // Obtén el archivo seleccionado
    me.archivo=file;
    if (file) {
        const extension = file.name.split('.').pop().toLowerCase(); // Extrae la extensión
        const allowedExtensions = ["p12", "pem", "token", "crt", "cert"]; // Lista de extensiones permitidas

        if (!allowedExtensions.includes(extension)) {
            me.errorMessage = `Solo se permiten archivos con las extensiones: ${allowedExtensions.join(", ")}.`;
            me.$refs.fileInput.value = ""; // Reinicia el campo del archivo
            Swal.fire("Error", me.errorMessage, "error");
        } else {
            me.errorMessage = "";
          
            // Aquí puedes continuar con el procesamiento del archivo
        }
    } else {
        me.errorMessage = "Por favor selecciona un archivo.";
    }
},

validateFileExcel() {
    let me = this;
    
    const file = this.$refs.fileInput.files[0]; // Obt 
    // én el archivo seleccionado
    if (file) {
        const extension = file.name.split('.').pop().toLowerCase(); // Extrae la extensión
        const allowedExtensions = ["p12", "pem", "token", "crt", "cert"]; // Lista de extensiones permitidas

        if (!allowedExtensions.includes(extension)) {
            me.errorMessage = `Solo se permiten archivos con las extensiones: ${allowedExtensions.join(", ")}.`;
            me.$refs.fileInput.value = ""; // Reinicia el campo del archivo
            Swal.fire("Error", me.errorMessage, "error");
        } else {
            me.errorMessage = "";

            // Aquí puedes continuar con el procesamiento del archivo
        }
    } else {
        me.errorMessage = "Por favor selecciona un archivo.";
    }
},

    resetear_x(){
        let me=this;
        let ambiente=me.selectTipoAmbiente;
        me.forFecha='yyyy-MM-ddTHH:mm:ss.fff';
        me.maxTiempoRespuesta= 15;
        switch (ambiente) {
            case '1':
                me.qr_='https://siat.impuestos.gob.bo/consulta/QR?nit={nit_emisor}&cuf={cuf}&numero={nro_factura}&t=2';
                me.paquetes=100;
          
                break;
            case '2':
                me.qr_='https://pilotosiat.impuestos.gob.bo/consulta/QR?nit={nit_emisor}&cuf={cuf}&numero={nro_factura}&t=2';
                me.paquetes=1;
           
                break;
            
            default:
            me.qr_='';
                break;
        }
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


        abrirModal(accion, data = []) { 
            let me = this;     
         switch (accion) {
              
                case "regcuenta": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
                    me.descripcion_endpoint="";
                    me.url_endpoint="";
                    me.version_endpoint="";
                    me.tituloModal="Registro de end points"
                    me.classModal.openModal("regcuenta");
                    break;
                }
                case "regcuenta_edit":{
                    me.tipoAccion = 2;
         
                    me.isSubmitting=false;                  
                    me.descripcion_endpoint=data.Descripcion;
                    me.url_endpoint=data.Url;
                    me.version_endpoint=data.Version;
                    me.id_endpoint=data.id;
                    me.tituloModal="Edicion de end points"
                    me.classModal.openModal("regcuenta");
                    break;
                }   
                case "gestorPruebaModal":{
                    me.showModal=true;
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
                   me.nomGesPrueba="";
                    me.xmlGesPrueba="";
                me.selectModalidadGesPrueba="0";   
                 me.tituloModal="Creacion de XML";
                 me.selectPuntoVenPrueba="3";
                me.selectEmisionGesPrueba="0";
                me.classModal.openModal("gestorPruebaModal");
                break;                
                }  
                case "gestorPruebaModal_actulizar":{
                    me.showModal=true;
                    me.tipoAccion = 2;
                 console.log(data);
               me.nomGesPrueba=data.nombre; 
               me.xmlGesPrueba=data.xml;
                me.idGesPrueba=data.id;
                me.selectSector_datos=data.sector;
                me.selectModalidadGesPrueba=String(data.modalidad);
                me.selectPuntoVenPrueba = data.punto_venta == null ? "3": String(data.punto_venta);
                me.selectEmisionGesPrueba=data.codigoEmision==null? "0": String(data.codigoEmision);
                 me.tituloModal="Edicion de XML"
                me.classModal.openModal("gestorPruebaModal");
                break;                
                }                        
            }
        },

        ///-------inicio catalogo-------------        
        listar_catalogo(){
            let me=this;     
                me.arrayCatalogo=[];         
                    var url='/siat/listar_catalogo';                         
                axios.get(url).then(function(response){
                    var respuesta = response.data; 
                    me.arrayCatalogo=respuesta;                                                 
                })
                .catch(function(error){
                    error401(error);
                });
        },

         ///-------inicio leyenda-------------        
         listar_leyenda(){
            let me=this;     
                me.arrayLeyenda=[];         
                    var url='/leyenda_siat';                         
                axios.get(url).then(function(response){
                    var respuesta = response.data; 
                    me.arrayLeyenda=respuesta;                                                 
                })
                .catch(function(error){
                    error401(error);
                });
        },

         ///-------inicio factura_v-------------        
         listar_factura_v(){
            let me=this;     
                me.arrayFactura_v=[];         
                    var url='/tipoSector_siat';                         
                axios.get(url).then(function(response){
                    var respuesta = response.data; 
                    me.arrayFactura_v=respuesta;                                                 
                })
                .catch(function(error){
                    error401(error);
                });
        },

        handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const data = new Uint8Array(e.target.result);
          const workbook = XLSX.read(data, { type: 'array' });
          const sheetName = workbook.SheetNames[0];
          const sheet = workbook.Sheets[sheetName];
          const jsonData = XLSX.utils.sheet_to_json(sheet);
           // Filtrar y procesar solo las columnas específicas
      const processedData = jsonData.map((row) => ({
        descripcion: row.descripcion || null, // Incluir "descripcion"
        codigo: row.codigo || null,           // Incluir "codigo"
        id_catalogo: row.id_catalogo || null, // Incluir "id_catalogo"
        id_erp: row.id_erp === undefined || row.id_erp === "" ? null : row.id_erp, // Incluir "id_erp" con transformación
         s1: row.s1 === undefined || row.s1 === "" ? null : row.s1, // Incluir "id_erp" con transformación
          s2: row.s2 === undefined || row.s2 === "" ? null : row.s2, // Incluir "id_erp" con transformación
      }));

     
      this.subirExcel(processedData); // Enviar los datos procesados
            
      
        };
        reader.readAsArrayBuffer(file);
      }
    },

    subirExcel(data){
        let me = this;    
        let sumador=0;
        data.forEach(element => {     
            if(element.id_catalogo===me.selectCatalogo){
                sumador++;
            }
        }); 
        if (sumador===data.length) {
            axios.post("/siat/subir_excel", {
                data:data,
                id:me.selectCatalogo,                              
                })
                .then(function (response) {
                  //  me.listarIndexEndPoint(); 
                    let respuesta=response.data;     
            
                    if (respuesta.length>0) {
                        Swal.fire("Error!",""+respuesta,"error",);    
                    } else {
                        Swal.fire("Se subio!","Correctamente","success",);  
                    }                               
                })               
                .catch(function (error) {                
                     
            });
        } else {
            Swal.fire("Error de validacion!","La id_catalogo no es la misma del selector de actividades","error",);  
        }       
 },

    listar_emision(id_catalogo){
            let me=this; 
            
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: '¿Esta seguro de descargar?',
                text: 'en formato excel.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Descargar',
                cancelButtonText: 'No, Descargar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    var url='/siat/listar_emision?id_catalogo='+id_catalogo;                         
                axios.get(url).then(function(response){
                    var respuesta = response.data; 
                    
                      // Convertir los datos a un formato que XLSX pueda entender
        const ws = XLSX.utils.json_to_sheet(respuesta);
         // Crear un libro de trabajo de Excel con esos datos
         const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Emisiones');

        // Generar un archivo Excel y forzar la descarga
        XLSX.writeFile(wb, me.nombreCatalogo+'.xlsx');
                                                               
                })
                .catch(function(error){
                    error401(error);
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
 verRubroSiat_xd(){
            let me=this;
            let url = "/litarRubroSiat_xd";
            axios.get(url)
                .then(function (response) {
                    let respuesta = response.data; 
                    if (respuesta===0) {
                         Swal.fire("Error No existe un rubro asociado con el sistema para la configuracion SIAT!","Debe ir a administracion y rubro y configurar","error",);  
                     me.rubro_xd=respuesta;
                    } else {  
                      me.rubro_xd=respuesta.codigo_activdad_siat;
                    } 
                    
               
                })
                .catch(function (error) {
                    error401(error);
                });
        },
       
        //--------------------
        
      
        cerrarModal(accion) {
            let me = this;
            
            if (accion == "regcuenta") {
                me.tipoAccion=1;
                me.isSubmitting=false;
                me.tituloModal="";
                me.descripcion_endpoint="";
                    me.url_endpoint="";
                    me.version_endpoint="";
                    me.id_endpoint="";
                me.classModal.closeModal(accion);         
            }

            if (accion =="gestorPruebaModal") {
                me.tipoAccion=1;
                me.isSubmitting=false;
                me.tituloModal="";
                me.selectSector_datos="0";
               me.nomGesPrueba=""; 
               me.xmlGesPrueba="";
                me.idGesPrueba="";
                 me.selectModalidadGesPrueba="0";
                me.showModal=false;
                 me.selectPuntoVenPrueba="3";
                me.selectEmisionGesPrueba="0";
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
        this.listarIndexConfiguracion();
        this.verRubroSiat_xd();
        this.classModal.addModal("regcuenta");
         this.classModal.addModal("gestorPruebaModal");
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
