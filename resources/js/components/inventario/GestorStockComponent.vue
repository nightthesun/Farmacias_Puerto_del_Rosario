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
                    <i class="fa fa-align-justify"></i> Gestor de sctock               
                    <button type="button" class="btn btn-secondary" style="color:white;" @click="abrirModal('registrar');iniciarOperacio();" :disabled="sucursalSeleccionada == 0" :hidden="sucursalSeleccionada == 0">
                        <i class="icon-plus"></i>&nbsp;Generar
                    </button>&nbsp;
             
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.id"
                                        v-text="sucursal.razon_social"
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                                 @keyup.enter="listarIndex(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                     @click="listarIndex(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

            <div class="row">
                <div class="col-md-2" >
                     <label for="">&nbsp;</label>
                </div>
    
        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" @change="listarIndex()">
        </div>
        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" @change="listarIndex()">
        </div>
     
        
        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
            <div class="d-flex flex-column">
            <label for="end-date">Exportar PDF:</label>
            <button type="button" class="btn btn-info mt-1" style="color: white;">
           <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </button>
            </div>
        </div>

        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
            <div class="d-flex flex-column">
            <label for="end-date">Exportar Excel:</label>
            <button type="button" class="btn btn-primary mt-1" style="color: white;">
            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
            </button>
            </div>
        </div>


  </div>
  <br>
  <div class="alert alert-warning" role="alert" v-if="sucursalSeleccionada=='0'">
  Debe seleccionar una <strong>sucursal</strong> 
</div>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" v-else>
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th>Nro</th>
                        <th>Distribuidor</th>
                        <th>Linea</th>
                        <th>Fecha de pedido</th>
                        <th>Total</th>
                        <th>Fecha de pago</th>
                        <th>Formato de pago</th>
                        <th>Turno de entrega</th>
                        <th>Plazo</th>
                        <th>Obs.</th>
                        <th>Usuario</th>
                        <th>Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(a, index) in arrayInicio" :key="index">
                        <td>  
                            <button type="button" class="btn btn-info btn-sm" style="margin-right: 5px; color: white;">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px; color: white;">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                            <button v-if="a.activo==1" type="button" class="btn btn-danger btn-sm" style="margin-right: 5px; color: white;" @click="eliminar(a.id)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>                            
                            <button v-else type="button" class="btn btn-info btn-sm" style="margin-right: 5px;"  @click="activar(a.id)">
                                <i class="icon-check"></i>
                            </button>
                        </td>
                        <td>{{a.id}}</td>
                        <td>{{a.nom_distribuidor}}</td>
                        <td>{{a.linea}}</td>
                        <td>{{(a.fecha_pedido).split(" ")[0]}}</td>
                        <td>{{a.total_programacion+" "+a.simbolo}}</td>
                        <td>{{a.fecha_pago}}</td>
                        <td>{{a.formato_pago}}</td>
                        <td>{{a.formato_turno}}</td>
                        <td>{{a.plazo_pedido}}</td>
                        <td>{{a.observacion}}</td>
                        <td>{{a.nom_user}}</td>
                
                           <td>                              
                                     <span  v-if="a.activo==1" class="badge badge-success" >Activo</span>                              
                                     <span v-else class="badge badge-warning">Desactivado</span>   
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
            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   
        
           <!--Inicio del modal agregar/actualizar-->
         <transition name="fade"> 
  <div v-if="showModal" class="modal d-block fullscreen-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ tituloModal }}</h4>
          <button type="button" class="close" @click="cerrarModal('registrar')">
            <span>Cerrar</span>
          </button>
        </div>
        <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">     
    <div class="card-header">
        <br>
                    <i class="fa fa-list-alt" aria-hidden="true"></i> Opciones:            
                    
                    <button type="button" class="btn btn-info"  style="color:white;" @click="accionDescargarPDF()">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Exportar PDF
                    </button>&nbsp;
                    <button type="button" class="btn btn-primary" @click="descargarExcell()"   >
                       <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel
                    </button>
             
                </div>
                <br>
                 <div class="form-group row">
                  
                <div class="col-md-1" style="text-align: center">
                     <label for="">Accion:</label>
                </div>
                        <div class="col-md-6">
                           <button type="button" @click="datosFiltradosF(10)" class="btn" style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver todo</button>&nbsp;
<button type="button" class="btn" @click="datosFiltradosF(2)" style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver stock alerta</button>&nbsp;
<button type="button" class="btn" @click="datosFiltradosF(1)"  style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver stock minimo </button>&nbsp;
<button type="button" class="btn" @click="datosFiltradosF(0)"  style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver stock cero </button>&nbsp;

                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar por liena o nombre de producto"
                                    v-model="buscar"
                               
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="buscarProductos()"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             
          <!-- Formulario -->
          <form class="form-horizontal">
            <div class="container-fluid">
              <div class="form-group row">
                <div class="modal-body" style="max-height: 120vh; overflow-y: auto;">                    
          
                <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr>
                            <th>Linea</th>
                            <th>Producto</th>
                            <th>Ciclo de Stock</th>
                            <th>Consumo promedio mensual</th>
                            <th>Plazo de entrega</th>
                            <th>Consumo promedio venta</th>
                            <th>Stock Maximo</th>
                            <th>Stock Medio</th>
                            <th>Stock Actual</th>
                            <th>Stock pedido</th>
                            <th>Indice de rotacion</th>
                            <th>Indice de cobertura</th>
                            <th>Rentabilidad</th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr v-for="(i, index)  in arrayModalOperacionGestor" :key="index"
                      :style="i.color === 0 || i.color === 1 || i.color === 2 ? {
  backgroundColor: i.color === 0 ? 'red' :
                   i.color === 1 ? '#FFE300' :
                   '#FF7300',
  color: 'white',
  fontWeight: 'bold'
} : {}" >
                            <td style="text-align: left;">{{i.linea}}</td>
                            <td style="text-align: left;">{{i.producto}}</td>
                            <td style="text-align: right;">{{ i.ciclo }}</td>
                            <td style="text-align: right;">{{i.consumo_mensual}}</td>
                            <td style="text-align: right;">{{i.plazo}}</td>
                            <td style="text-align: right;">{{i.consumo_dia}}</td>
                            <td style="text-align: right;">{{i.stmax}}</td>
                            <td style="text-align: right;">{{i.stmedio}}</td>
                            <td style="text-align: right;">{{i.stock_total}}</td>
                            <td style="text-align: right;">{{i.stpedido}}</td> 
                            <td style="text-align: right;">{{i.indicerot}}</td>
                            <td style="text-align: right;">{{i.indicecober}}</td>
                            <td style="text-align: right;">{{i.rentabilidad}}</td>
                           
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
          </form>
        </div>

      
       
            <div class="card">
  <h5 class="card-header">Crear gestor stock</h5>
  <div class="card-body">
    
   
    <div class="container"> 
         <div class="form-check">
  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" @click="cambioSelecto(1)">
  <label class="form-check-label" style="font-size: 15px;">
    Seleccionar por linea en la tabla
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2" @click="cambioSelecto(2)">
  <label class="form-check-label" style="font-size: 15px;">
    Seleccionar por distribuidor 
  </label>
</div>
                             
               <div class="form-group row align-items-center">     
    <!-- Selector línea -->
    <div class="col-md-6 input-group mb-0" >  

        <select class="form-control" v-model="selectEspecifico" v-show="showSelector==1">
            <option value="0" disabled selected>Seleccionar linea</option>
            <option v-for="(a, index) in arrayLiena" 
                    :key="index" 
                    :value="a.id_linea"
                    v-text="'Linea: '+a.linea+' Color: '+a.color">
            </option>
        </select>                          
          

    <!-- Selector distribuidor -->
    
        <VueMultiselect v-show="showSelector==2"
            v-model="selected"
            :options="arrayDistribuidor"
            :max-height="190"                   
            :block-keys="['Tab', 'Enter']"                       
            placeholder="Seleccione un distribuidor"
            label="id" 
            :custom-label="nameWithLang"                     
            track-by="id"
            class="w-100"
            selectLabel="Añadir a seleccion"
            deselectLabel="Quitar seleccion"
            selectedLabel="Seleccionado">
            <template #noResult>
                No se encontraron elementos. Considere cambiar la consulta de búsqueda.
            </template>
        </VueMultiselect> 
    </div>
                       
    <!-- Botones -->
    <div class="col-md-6 d-flex gap-2 flex-wrap">
        <div v-if="showSelector==1">
            <button type="button" class="btn"
                :style="selectEspecifico =='0'?{backgroundColor: '#eeeeee', color: 'white'}:{backgroundColor: '#FF7300', color: 'white'}"
                @click="selectEspecifico !='0' && modalAlerta_open(0, 0, 1, selectEspecifico)">
                <strong>Stock alerta</strong>
            </button>
            <button type="button" class="btn"
                :style="selectEspecifico =='0'?{backgroundColor: '#eeeeee', color: 'white'}:{backgroundColor: '#FFE300', color: 'white'}"
                @click="selectEspecifico !='0' && modalAlerta_open(0, 0, 2, selectEspecifico)">
                <strong>Stock minimo</strong>
            </button>
        </div>
        <div v-else>
            <button type="button" class="btn"
                :style="selected == null ? {backgroundColor: '#eeeeee', color: 'white'}:{backgroundColor: '#FF7300', color: 'white'}"
                @click="selected != null && modalAlerta_open(selected.id, selected.id_linea_array, 1, selectEspecifico)">
                <strong>Stock alerta</strong>
            </button>
            <button type="button" class="btn"
                :style="selected == null ? {backgroundColor: '#eeeeee', color: 'white'}:{backgroundColor: '#FFE300', color: 'white'}"
                @click="selected != null && modalAlerta_open(selected.id, selected.id_linea_array, 2, selectEspecifico)">
                <strong>Stock minimo</strong>
            </button>
        </div>

        <button type="button" class="btn" style="background-color: red; color: white;" 
            @click="modalCero_open()">
            <strong>Stock cero</strong>
        </button>
        <button type="button" class="btn btn-primary" 
            @click="cerrarModal('registrar')">
            <strong>Cerrar modal</strong>
        </button>              
    </div>
</div>
     
            </div>
  </div>
</div>
      
      
      </div>
    </div>
  </div>
</transition>

        <!--fin del modal-->

<!---modal de alerta----->
   <transition name="fade">
            <div v-if="showModal_2" class="modal d-block fullscreen-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-secondary modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal_2 }}</h4>
                        <button type="button" class="close" @click="cerrarModal('alerta')">
                            <span>&times;</span>
                        </button>
                        </div>

                        <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                  
                                        <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" @click="estadoBotton(1)">
          Vista normal, automatica y auto designada
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
               <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr style="background-color: darkgray;">
                            <th>Opciones</th>
                            <th>Linea</th>
                            <th class="col-md-3">Producto</th>
                            <th>Ciclo de Stock</th>
                            <th>Consumo promedio mensual</th>
                            <th>Plazo de entrega</th>
                            <th>Consumo promedio venta</th>
                            <th>Stock Maximo</th>
                            <th>Stock Actual</th>
                            <th>Stock Pedido</th>
                            <th class="col-md-2">Candidad Dispenser</th>
                            <th class="col-md-2">Precio de lista</th>
                            <th class="col-md-2">Precio Importe</th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr v-for="(a, index) in arrayModalSuperiror_naranja" :key="a.id">
                            <td>
                        
<button type="button" class="btn btn-danger" @click="quitarEleArray(a.id,index)">
    <i class="fa fa-minus" aria-hidden="true"></i>
</button>

                            </td>
                            <td>
                                <span>{{a.linea}}</span>
                            </td>
                            <td class="col-md-3">
                                <span>{{ a.producto }}</span>
                            </td>
                            <td style="text-align: right;">
                                <span>{{a.ciclo}}</span>
                            </td>
                         <td style="text-align: right;">
                                <span>{{ a.consumo_mensual }}</span>
                            </td>
                            <td style="text-align: right;">
                                <span>{{ a.plazo }}</span>
                            </td>
                           <td style="text-align: right;">
                                <span>{{ a.consumo_dia }}</span>
                            </td>
                          <td style="text-align: right;">
                                <span>{{ a.stmax }}</span>
                            </td>
                           <td style="text-align: right;">
                                <span>{{ a.stock_total }}</span>
                            </td>
                           <td style="text-align: right;">
                                <span>{{ a.stpedido }}</span>
                            </td>
                            <td class="col-md-2">
                       
                             <input style="text-align: right;" class="form-control form-control-sm" type="number" v-model.number="arrayModalSuperiror_naranja[index].dispedido" @keyup="sumar_top(index)">
                            </td>
                            <td class="col-md-2" style="text-align: right;">
                                <span>{{ a.precio_lista+" "+simbolo }}</span>
                            </td>
                             <td class="col-md-2" style="text-align: right;">
                                <span>{{ a.subtotal+" "+simbolo }}</span>
                            </td>
                          
                        </tr>
                        <tr style="background-color: darkgray;">
                            <td colspan="12" style="text-align: center;">                          
                                    <strong style="text-align: center;">SUB TOTAL</strong>                                                       
                            </td>
                            <td class="col-md-2" style="text-align: right;">
                                <span>{{subTotal_modal_superior+" "+simbolo}}</span>
                            </td>
                        </tr>
                    </tbody>    
          
                </table>    
        </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" @click="estadoBotton(2)">
          Vista especifica, manual designada y adicionada
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
         <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr style="background-color: darkgray;">
                            <th class="col-md-7">Producto</th>                           
                            <th class="col-md-2">Precio de lista</th>
                            <th class="col-md-3">Cantidad</th>                            
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr>
                            <td  class="col-md-7">
                                    <VueMultiselect
                        v-model="select_distribuidor_x_producto"
                        
                        :options="arraySelect_distribuidor_x_producto"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="id" 
                        :custom-label="nameWithLang_2"                     
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado">
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect> 
                            </td>
                            <td  class="col-md-2">
                                <span v-if="select_distribuidor_x_producto" style="text-align: right;">
                                    {{select_distribuidor_x_producto.preciolista+" "+simbolo}}
                                </span>
                                <span style="text-align: right;" v-else>
                                    0.00
                                </span>
                            </td>
                            <td  class="col-md-3">
                                <div v-if="select_distribuidor_x_producto" class="d-flex align-items-center gap-2">
                                    <input style="text-align: right;" class="form-control form-control-sm" type="number" v-model="input_bot">
                                    <button type="button" class="btn btn-primary" @click="añadir_elemento_array_bot()"><i class="fa fa-plus" aria-hidden="true"></i></button>   
                                </div>
                                <div v-else>
                                    <span style="text-align: right;">0</span>
                                </div>                                                   
                               
                            </td>
                        </tr>
                      
                    </tbody>    
                </table>    
                <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr style="background-color: darkgray;">
                            <th>Opciones</th>
                            <th class="col-md-2">Linea</th>
                            <th class="col-md-1">Cod. Producto</th>
                            <th class="col-md-4">Producto</th>
                            <th class="col-md-1">Precio lista</th>
                            <th class="col-md-1">Cantidad</th>
                            <th class="col-md-3">Sub Total</th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr v-for="f in arrayInferior_falso" :key="f.id">
                            <td>
                                <button type="button" class="btn btn-danger" @click="quitarArrayFalsoBot(f.contador)">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td class="col-md-2">
                                {{f.linea_nombre}}
                            </td>
                            <td class="col-md-1">
                                {{f.codigoProducto}}
                            </td>
                            <td class="col-md-4">
                                {{f.nom_prod}}
                            </td>
                            <td class="col-md-1">
                                {{f.preciolista+" "+simbolo}}
                            </td>    
                            <td class="col-md-1">
                                {{f.cantidadFalsa}}
                            </td>    
                            <td class="col-md-3">
                                {{f.subTotal_falso+" "+simbolo}}
                            </td>                       
                        </tr>
                        <tr style="background-color: darkgray;">
                            <td colspan="6" style="align-items: center;">
                                <strong style="text-align: center;">SUMATORIA</strong>
                            </td>
                            <th class="col-md-3">
                                <span>{{sumatoriaBot+" "+simbolo}}</span>
                            </th>
                        </tr>                      
                    </tbody>    
                </table>   
      </div>
    </div>
  </div>
 
</div>
<div class="card">
  <div class="card-header">
     <strong>Detalle de pago</strong>
  </div>
  <div class="card-body">
    <blockquote class="blockquote mb-0">
         <div class="row">
                                <div class="form-group col-sm-4">
                                    <span style="font-size: 12px;">Forma de pago:</span>
                                      <select v-model="selectFormaPago" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="a in array_forma_pago" :key="a.id" :value="a.id" v-text="a.tipo"></option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <span style="font-size: 12px;">Fecha de pago:</span>
                                    <input type="date" class="form-control" v-model="fechaPago">
                              
                                </div>
                                <div class="form-group col-sm-4" >
                                    <span style="font-size: 12px;">Plazo de pago:</span>
                                    <input type="text" class="form-control" placeholder=""  v-model="plazoPago">
                                
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="form-group col-sm-4">
                                    <span style="font-size: 12px;">Entrega de pedido:</span>
                                      <select v-model="selectEntregaPedido" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="a in array_entrega_pedido" :key="a.id" :value="a.id" v-text="a.tipo"></option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-4">
                                  <span style="font-size: 12px;">Observación:</span>
                                    <input type="text" class="form-control" placeholder=""  v-model="observacion">
                                </div>
                            </div>
    </blockquote>
  </div>
</div>
                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                  
                              </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('alerta')">Cerrar</button>                       
                        <button :disabled="isSubmitting==true" type="button"  class="btn btn-primary" @click="guardarModal()">Guardar</button>                     
                    </div>

                    </div>
                </div>
            </div>
        </transition>  
<!--finde modal alerta-->

<!---modal de saldo cero----->
   <transition name="fade">
            <div v-if="showModal_3" class="modal d-block" tabindex="-1" role="dialog">
                 <div class="modal-dialog modal-secondary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal_3 }}</h4>
                        <button type="button" class="close" @click="cerrarModal('saldo_cero')">
                            <span>&times;</span>
                        </button>
                        </div>
                        <div class="card-header">
                        <br>
                            <button type="button" class="btn btn-info"  style="color:white;" @click="descargaPDF_cero()">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Exportar PDF
                            </button>&nbsp;
                            <button type="button" class="btn btn-primary" @click="descargarExcell_cero()">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel
                            </button>
             
                        </div>
                       <br>
                    
                            <div class="form-group row">
                                <div class="col-md-1">

                                </div>
                        <div class="col-md-1">
                     <label for="">Mostrar: </label>
                </div>
                  <div class="col-sm-2">
                                     <select v-model="selectModa_saldoCero" class="form-control" @change="listarSaldoCero()">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="a in arrayModal_saldoCero" :key="a.id" :value="a.id" v-text="a.tipo"></option>
                                    </select>
                    </div> 
                    <div class="col-sm-1">

                    </div>
                      <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar por liena o nombre de producto"
                                    v-model="buscarCero"/>
                                <button type="submit" class="btn btn-primary" @click="buscarProductos_modal_cero()">
                                    <i class="fa fa-search"></i>  Buscar
                                </button>
                            </div>
                        </div>
                         <div class="col-md-1">
                                </div>
                </div>
                          
                        

                        <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">

                  
                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                <table class="table table-bordered table-striped table-sm table-responsive" >                    
                                    <thead>
                                        <tr>
                                        <th class="col-md-1">Linea</th>
                                        <th class="col-md-1">Cod. Producto</th>
                                        <th class="col-md-4">Producto</th>
                                        <th class="col-md-1">Envase</th>
                                        <th class="col-md-1">Stock actual</th>
                                        <th class="col-md-2">Rotura de stock</th>
                                        <th class="col-md-1">Prospesctos pedidos</th>
                                        </tr>                        
                                    </thead>
                                    <tbody>                                        
                                        <tr v-for="(a, index) in arraySaldoCero" :key="index">
                                            <td class="col-md-1">{{a.linea}}</td>
                                            <td class="col-md-1">{{a.codigo}}</td>
                                            <td class="col-md-4">{{a.producto}}</td>
                                            <td class="col-md-1">{{a.envase}}</td>
                                            <td class="col-md-1">{{a.stock}}</td>
                                            <td class="col-md-1">{{a.dias}}</td>                                          
                                            <td class="col-md-1">{{a.perdido}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('saldo_cero')">Cerrar</button>                     
                    </div>

                    </div>
                </div>
            </div>
        </transition>  
<!--finde modal alerta-->
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import * as XLSX from 'xlsx';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import { toInteger } from "lodash";
// Asigna los fonts a pdfmake
pdfMake.vfs = pdfFonts.pdfMake.vfs;
export default {
     components: { VueMultiselect},
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
     
            showModal: false,
            showModal_2:false,
            showModal_3:false,
            offset:3,
  selected:null,
            tituloModal: '',
            tituloModal_2: '',
            tituloModal_3: '',
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:'',
            tipoAccion:1,
            startDate: '',
      endDate: '',

    selectModa_saldoCero:'',
    arrayModal_saldoCero:[{id:1,tipo:"50"},{id:2,tipo:"100"},{id:3,tipo:"200"},{id:4,tipo:"300"},{id:5,tipo:"Todos"}],
    buscarCero:'',
      selectFormaPago:'0',    

    array_forma_pago: [{ id: 1, tipo: "CHEQUE" },{ id: 2, tipo: "CONTADO" },{ id: 3, tipo: "CREDITO" },{ id: 4, tipo: "TRASFERENCIA BANCARIA" }],
    fechaPago:'',
    plazoPago:'',
    selectEntregaPedido:'0',      
    array_entrega_pedido: [{ id: 1, tipo: "MAÑANA" },{ id: 2, tipo: "TARDE" }],
    observacion:'',
            button_estado:0,
            arrayDistribuidor:[],
            subTotal_modal_superior:0,

            arrayModalOperacionGestor:[],
            arrayModalFalso:[],
    
            arrayModalSuperiror_naranja:[],
    
            simbolo:'',
            arraySelect_distribuidor_x_producto:[],
            select_distribuidor_x_producto: null,
            input_bot:1,
            inputTop: [],
            arrayInferior_falso:[],
            count:0,
            sumatoriaBot:0,
            isSubmitting:false,
            id_distribuidor:0, 
            id_lineas:'',
            tipo_modal:0,
            
            arraySaldoCero:[],
            arrayFalsoCero:[],
            buscarCero:'',

            arrayLiena:[],
            filtrados:[],
            selectEspecifico:'0',     
            showSelector:0,  
            
            arrayInicio:[],
          
        };
    },

    

    computed: {
      //  sicompleto() {
      //      let me = this;
       //     if (
          
     //           me.glosa != "" &&
     //           me.cantidadS != "" &&
     //           me.ProductoLineaIngresoSeleccionado
     //       )
       //         return true;
      //      else return false;
      //  },
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

        accionDescargarPDF(){
            let me=this;
            me.descargaPDF(me.arrayModalOperacionGestor);
        },

        descargaPDF(array_reporte){

            const tableBody =[
                //agregar encabezados de la tabla
                [
                    {text: 'Linea', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3'},
                    {text: 'Producto', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Ciclo de stock', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Consumo promedio mensual', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Plazo de entrega', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Consumo promedio venta', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Stock maximo', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Stock medio actual', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Stock pedido', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Indice rotación', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Indice de cobertura', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Rentabilidad', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                ]
            ];
            // Itera sobre los datos y agrega fila a la tabla 
            array_reporte.forEach(item =>{
               tableBody.push([
  {text: item.linea, fontSize:8, alignment: 'left'},
  {text: item.producto, fontSize:8, alignment: 'left'},
  {text: item.ciclo, fontSize:8, alignment: 'right'},
  {text: item.consumo_mensual, fontSize:8, alignment: 'right'},
  {text: item.plazo, fontSize:8, alignment: 'right'}, // ← FALTABA ESTA
  {text: item.consumo_dia, fontSize:8, alignment: 'right'},
  {text: item.stmax, fontSize:8, alignment: 'right'},
  {text: item.stmedio, fontSize:8, alignment: 'right'},
  {text: item.stpedido, fontSize:8, alignment: 'right'},
  {text: item.indicerot, fontSize:8, alignment: 'right'},
  {text: item.indicecober, fontSize:8, alignment: 'left'},
  {text: item.rentabilidad, fontSize:8, alignment: 'right'},
]);

            });

            const docDefinition ={
                pageSize: 'LETTER',
                pageMargins: [25, 30, 25, 30],
                content:[
                  {text: 'INFORME GESTOR DE STOCK', style:'header'},
                  {
                    style: 'tableExample',
                    margin:[0,10,0,0],
                    table:{
                         headerRows: 1,
                        widths: [28,'*',24,34,30,30,30,29,29,29,34,30], // Ajusta los anchos de las columnas
                        body: tableBody
                    }
                  },
                ],
      
                styles: {
                    header: {
                        fontSize: 12,
                        bold: true,
                        color: 'black',
                        alignment: 'center',
                        margin:[0,10,0,10]
                    },
                    tableExample:{
                        fontSize: 8,
                        bold: true,
                    },
                    tableHeader2 : {
			            bold: true,
			            fontSize: 7,
			            color: 'black',
                        margin:[ 180, 0, 20,0] 
		            },
                    tableHeader_2: {
                        bold: true,
                        fontSize: 7,
                        bold: true,
                        alignment: 'center',
                    },
                }
                };    
                  // Generar y abrir el PDF
  pdfMake.createPdf(docDefinition).open();   
        },

        descargaPDF_cero(){
            let me=this;
            const tableBody =[
                //agregar encabezados de la tabla
                [
                    {text: 'Linea', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3'},
                    {text: 'Cod. Producto', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Producto', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Envase', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Stock actual', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Rotura de stock', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'pedidos', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'}                   
                ]
            ];
            // Itera sobre los datos y agrega fila a la tabla 
            me.arraySaldoCero.forEach(item =>{
               tableBody.push([
  {text: item.linea, fontSize:8, alignment: 'center'},
  {text: item.codigo, fontSize:8, alignment: 'center'},
  {text: item.producto, fontSize:8, alignment: 'center'},
  {text: item.envase, fontSize:8, alignment: 'center'},
  {text: item.stock, fontSize:8, alignment: 'center'}, // ← FALTABA ESTA
  {text: item.dias, fontSize:8, alignment: 'center'},
  {text: item.perdido, fontSize:8, alignment: 'center'}
]);

            });

            const docDefinition ={
                pageSize: 'LETTER',
                pageMargins: [25, 30, 25, 30],
                content:[
                  {text: 'INFORME STOCK CERO', style:'header'},
                  {
                    style: 'tableExample',
                    margin:[0,10,0,0],
                    table:{
                         headerRows: 1,
                        widths: [37,37,'*',35,31,31,31,29], // Ajusta los anchos de las columnas
                        body: tableBody
                    }
                  },
                ],
      
                styles: {
                    header: {
                        fontSize: 12,
                        bold: true,
                        color: 'black',
                        alignment: 'center',
                        margin:[0,10,0,10]
                    },
                    tableExample:{
                        fontSize: 8,
                        bold: true,
                    },
                    tableHeader2 : {
			            bold: true,
			            fontSize: 7,
			            color: 'black',
                        margin:[ 180, 0, 20,0] 
		            },
                    tableHeader_2: {
                        bold: true,
                        fontSize: 7,
                        bold: true,
                        alignment: 'center',
                    },
                }
                };    
                  // Generar y abrir el PDF
  pdfMake.createPdf(docDefinition).open();   
        },

        descargarExcell(){
           let me = this;
// 1. Define columnas con títulos bonitos
const columnas = [
  { key: 'id_producto', titulo: 'ID Producto' },
  { key: 'linea', titulo: 'Linea' },
  { key: 'ciclo', titulo: 'Ciclo de stock' },
  { key: 'consumo_mensual', titulo: 'Consumo promedio mensual' },
  { key: 'plazo', titulo: 'Plazo de entrega' },
  { key: 'consumo_dia', titulo: 'Consumo promedio venta' },
  { key: 'stmax', titulo: 'Stock maximo' },
  { key: 'stmedio', titulo: 'Stock medio' },
  { key: 'stock_total', titulo: 'Stock actual' },
  { key: 'stpedido', titulo: 'Stock pedido' }, // corregido aquí
  { key: 'indicerot', titulo: 'Indice de rotación' },
  { key: 'indicecober', titulo: 'Indice de cobertura' },
  { key: 'rentabilidad', titulo: 'Rentabilidad' }
];

// 2. Filtra y renombra columnas
const datosFiltrados = me.arrayModalOperacionGestor.map(item => {
  const fila = {};
  columnas.forEach(col => {
    fila[col.titulo] = item[col.key];
  });
  return fila;
});

// 3. Crear hoja vacía
const ws = XLSX.utils.json_to_sheet([]);

// 4. Agrega un título grande en la primera fila
XLSX.utils.sheet_add_aoa(ws, [['Informe']], { origin: 'A1' });

// 5. Agrega los datos debajo del título (desde fila 3)
XLSX.utils.sheet_add_json(ws, datosFiltrados, {
  origin: 'A3',
  skipHeader: false
});

// 6. Crear libro y exportar
const wb = XLSX.utils.book_new();
XLSX.utils.book_append_sheet(wb, ws, 'Emisiones');
XLSX.writeFile(wb, 'informe_emisiones.xlsx'); 


    // Convertir los datos a un formato que XLSX pueda entender
      //  const ws = XLSX.utils.json_to_sheet(nuevo);
         // Crear un libro de trabajo de Excel con esos datos
      //   const wb = XLSX.utils.book_new();
      //  XLSX.utils.book_append_sheet(wb, ws, 'Emisiones');
      //  let archivo="gestion_stock";    
        // Generar un archivo Excel y forzar la descarga
     //   XLSX.writeFile(wb, archivo+'.xlsx');
        },

         descargarExcell_cero(){
           let me = this;
// 1. Define columnas con títulos bonitos
const columnas = [
  { key: 'linea', titulo: 'Linea' },
  { key: 'codigo', titulo: 'Cod. Producto' },
  { key: 'producto', titulo: 'Producto' },
  { key: 'envase', titulo: 'Envase' },
  { key: 'stock', titulo: 'Stock actual' },
  { key: 'dias', titulo: 'Rotura de stock' },
  { key: 'perdido', titulo: 'Prospesctos pedidos' }
];

// 2. Filtra y renombra columnas
const datosFiltrados = me.arraySaldoCero.map(item => {
  const fila = {};
  columnas.forEach(col => {
    fila[col.titulo] = item[col.key];
  });
  return fila;
});

// 3. Crear hoja vacía
const ws = XLSX.utils.json_to_sheet([]);

// 4. Agrega un título grande en la primera fila
XLSX.utils.sheet_add_aoa(ws, [['Stock en cero']], { origin: 'A1' });

// 5. Agrega los datos debajo del título (desde fila 3)
XLSX.utils.sheet_add_json(ws, datosFiltrados, {
  origin: 'A3',
  skipHeader: false
});

// 6. Crear libro y exportar
const wb = XLSX.utils.book_new();
XLSX.utils.book_append_sheet(wb, ws, 'Emisiones');
XLSX.writeFile(wb, 'informe_stock_cero.xlsx'); 
        },


listarIndex(page){
            let me=this;
                var url='/gestor-stock/index?page='+page+'&buscar='+me.buscar+'&id_sucursal='+me.sucursalSeleccionada+"&ini="+me.startDate+"&fini="+me.endDate;
             
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayInicio = respuesta.resultados.data;
                    console.log(me.arrayInicio);
                })
                .catch(function(error){
                    error401(error);
                });
        },

        cambioSelecto(data){
             let me=this;
                me.selected=null;
                me.selectEspecifico="0";
                me.showSelector=data;
        }, 
        
        modalAlerta_open(id_distri,lineas,tipo,linea_es){
            let me = this;
            me.id_distribuidor=id_distri;
           
            me.tipo_modal=tipo;
            
            if (me.showSelector==1) {           
                 me.id_lineas=linea_es;
            me.listarModalAlerta_superior(me.tipo_modal,linea_es);
            me.listarModalAlerta_inferior(linea_es);
            me.abrirModal('alerta'); 
            }
            if (me.showSelector==2) {
                 me.id_lineas=lineas;
            me.listarModalAlerta_superior(me.tipo_modal,0);
            me.listarModalAlerta_inferior(0);
            me.abrirModal('alerta');   
            }                   
        },

        modalCero_open(){
            let me = this;
            me.selectModa_saldoCero=1
            me.listarSaldoCero();
            me.abrirModal('saldo_cero');

        },

        buscarProductos() {
        let me=this;       
        const texto = me.buscar.toLowerCase(); 
        const existe = me.arrayModalFalso.some(item =>
        (item.linea && item.linea.toLowerCase().includes(texto)) ||
        (item.producto && item.producto.toLowerCase().includes(texto))
        ); 
        if (existe) {
            // Filtra por coincidencias en línea o producto
    me.arrayModalOperacionGestor = me.arrayModalFalso.filter(item =>
      (item.linea && item.linea.toLowerCase().includes(texto)) ||
      (item.producto && item.producto.toLowerCase().includes(texto))
    );
        } else {
            me.arrayModalOperacionGestor=me.arrayModalFalso; 
        }
     
    },
  
    buscarProductos_modal_cero() {
        let me=this;       
        const texto = me.buscarCero.toLowerCase(); 
        const existe = me.arrayFalsoCero.some(item =>
        (item.linea && item.linea.toLowerCase().includes(texto)) ||
        (item.producto && item.producto.toLowerCase().includes(texto)) || (item.codigo && item.codigo.toLowerCase().includes(texto)) || (item.envase && item.envase.toLowerCase().includes(texto))
        ); 
        if (existe) {
            // Filtra por coincidencias en línea o producto
    me.arraySaldoCero = me.arrayFalsoCero.filter(item =>
      (item.linea && item.linea.toLowerCase().includes(texto)) ||
      (item.producto && item.producto.toLowerCase().includes(texto)) || 
      (item.codigo && item.codigo.toLowerCase().includes(texto)) ||
       (item.envase && item.envase.toLowerCase().includes(texto))
    );
        } else {
            me.arraySaldoCero=me.arrayFalsoCero; 
        }
     
    },


datosFiltradosF(data) {
           let me=this;
            if (data===10) {            
                me.arrayModalOperacionGestor=me.arrayModalFalso; 
            }
            if (data===1) {
              me.arrayModalOperacionGestor=me.arrayModalFalso.filter(item => item.color === data);              
            }
            if (data===2) {
              me.arrayModalOperacionGestor=me.arrayModalFalso.filter(item => item.color === data); 
            }
            if (data===0) {
              me.arrayModalOperacionGestor=me.arrayModalFalso.filter(item => item.color === data); 
            }
        },

        iniciarOperacio() {
            let me=this;  
     me.listarModalGestorOperacion();  // espera que termine TODO
     me.listarDistribuidor();          // recién ejecuta la segunda
    
},
    
quitarEleArray(id,i){
            let me=this;         
            me.arrayModalSuperiror_naranja = me.arrayModalSuperiror_naranja.filter(item => item.id !== id);
             let suma = 0;
            for (let ii = 0; ii < me.arrayModalSuperiror_naranja.length; ii++) {
                suma += Number(me.arrayModalSuperiror_naranja[ii].subtotal || 0);
            }
         me.subTotal_modal_superior=parseFloat(suma).toFixed(2);
        },

       añadir_elemento_array_bot() {
    let me = this;    
    if (me.count<0 || me.input_bot<=0 || me.input_bot==""|| me.input_bot==null ) {
        me.cerrarModal('alerta');
        alert("error de entrada valor negativo o nulos");
    }
    me.count=me.count+1;
    let subTotal_falso=(me.select_distribuidor_x_producto).preciolista * me.input_bot;
    me.arrayInferior_falso.push({ 
        contador:me.count,
        id_prod: (me.select_distribuidor_x_producto).id_prod,
        id_linea: (me.select_distribuidor_x_producto).id_linea,
        linea_nombre: (me.select_distribuidor_x_producto).linea_nombre,
        codigoProducto: (me.select_distribuidor_x_producto).codigoProducto,
        nom_prod: (me.select_distribuidor_x_producto).nom_prod,
        distribuidor_nom: (me.select_distribuidor_x_producto).distribuidor_nom,
        id_dispenser: (me.select_distribuidor_x_producto).id_dispenser,
        cantidad: (me.select_distribuidor_x_producto).cantidad,
        tiempo_pedido: (me.select_distribuidor_x_producto).tiempo_pedido,
        dis_nom: (me.select_distribuidor_x_producto).dis_nom,
        nom_for_farmaceutica: (me.select_distribuidor_x_producto).nom_for_farmaceutica,
        preciolista: (me.select_distribuidor_x_producto).preciolista,
        precioventa: (me.select_distribuidor_x_producto).precioventa,
        tipo: (me.select_distribuidor_x_producto).tipo,
        cantidadFalsa:me.input_bot,
        subTotal_falso: parseFloat(subTotal_falso).toFixed(2),
        moneda:me.simbolo
    });
    me.select_distribuidor_x_producto=null;
   
        me.input_bot=1;
         let suma = 0;
            for (let ii = 0; ii < me.arrayInferior_falso.length; ii++) {
                suma += Number(me.arrayInferior_falso[ii].subTotal_falso || 0);
            }
 me.sumatoriaBot=parseFloat(suma).toFixed(2);
},

 quitarArrayFalsoBot(id){
            let me=this;         
            me.arrayInferior_falso = me.arrayInferior_falso.filter(item => item.contador !== id);
             let suma = 0;
            for (let ii = 0; ii < me.arrayInferior_falso.length; ii++) {
                suma += Number(me.arrayInferior_falso[ii].subTotal_falso || 0);
            }
         me.sumatoriaBot=parseFloat(suma).toFixed(2);
        },

        guardarModal(){      
            let me = this;  
           
            if ( me.id_lineas === "" ||
             me.selectFormaPago === "0" || me.fechaPago ==="" ||
                me.plazoPago === "" ||me.selectEntregaPedido==="0"|| me.observacion===""    
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                // Si ya está enviando, no permitas otra solicitud
            me.isSubmitting=true;
                axios.post("/gestor-stock/registrar_naranja", {
                        'id_distribuidor': me.id_distribuidor,
                        'id_lineas': me.id_lineas,
                        'arrayModalSuperiror_naranja': me.arrayModalSuperiror_naranja,
                        'subTotal_modal_superior': parseFloat(me.subTotal_modal_superior).toFixed(2),
                        'arrayInferior_falso': me.arrayInferior_falso,
                        'sumatoriaBot': me.sumatoriaBot,
                        'selectFormaPago': me.selectFormaPago,
                        'fechaPago': me.fechaPago,
                        'plazoPago': me.plazoPago,
                        'turno_pedido': me.selectEntregaPedido,
                        'observacion': me.observacion,  
                        'simbolo': me.simbolo,
                        'id_sucursal':me.sucursalSeleccionada,
                        'tipo':me.tipo_modal                    
                    })
                    .then(function (response) {
                        me.cerrarModal("alerta");
                          me.isSubmitting=false;
                          var respuesta = response.data;
                          if (respuesta.length>0) {
                                if (respuesta===0) {
                                    Swal.fire("Al menos debe estar lleno una lista de de productos.","Haga click en Ok","warning",);
                                }else{
                                    Swal.fire(""+respuesta,"Haga click en Ok","error",);
                                }
                          }else{
                            Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                          }
                    })
                   .catch(function (error) {                
                                  
            });
            }
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
                     axios.put('/gestor-stock/activar',{
                        id: id
                    }).then(function (response) {
                        me.listarIndex();
                    
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                    }).catch(function (error) {
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
                     axios.put('/gestor-stock/desactivar',{
                        id: id
                    }).then(function (response) {
                        me.listarIndex();
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                   
                     
                        
                    }).catch(function (error) {
                        error401(error);
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

        listarModalGestorOperacion(){            
            let me=this;
            me.arrayModalOperacionGestor=[];
            
            var url = "/gestor-stock/listarGestorStockModal?id_sucursal="+me.sucursalSeleccionada;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = (response.data).arrayMostrar;
                    let respuesta_2 = (response.data).arrayLinea;
                    me.arrayModalOperacionGestor = respuesta;
                    me.arrayModalFalso = respuesta; 
                    let filtro=respuesta_2;
                     
                        me.arrayLiena = filtro.filter((item, index, self) =>
  index === self.findIndex((t) => (
    t.id_linea === item.id_linea && t.color === item.color
  ))
);
              
                })
                .catch(function (error) {
                    error401(error);
                });
        },

         listarModalAlerta_inferior(data){            
            let me=this;
     
            me.arraySelect_distribuidor_x_producto=[];
            me.select_distribuidor_x_producto=null;
            if (data==0) {
                 var url = "/gestor-stock/listar_Producto_x_distribuidor?id_distribuidor="+me.selected.id+"&data="+data;
            } else {
                 var url = "/gestor-stock/listar_Producto_x_distribuidor?id_distribuidor="+0+"&data="+data;
            }
           
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                 
                me.arraySelect_distribuidor_x_producto=respuesta;
                })
                .catch(function (error) {
                    error401(error);
                });
        },

      sumar_top(id){
        let me=this;
 
        const element=0;
        if( me.inputTop[id]!="" || me.inputTop[id]!=null || me.inputTop[id]>=0){
             parseFloat(me.arrayModalSuperiror_naranja[id].subtotal=(me.arrayModalSuperiror_naranja[id].dispedido)*me.arrayModalSuperiror_naranja[id].precio_lista).toFixed(2);
            let suma = 0;
    for (let i = 0; i < me.arrayModalSuperiror_naranja.length; i++) {
      suma += Number(me.arrayModalSuperiror_naranja[i].subtotal || 0);
    }
         me.subTotal_modal_superior=parseFloat(suma).toFixed(2);
        }
      },

        listarModalAlerta_superior(tipo,lineaA){
            
            let me=this;
            me.arrayModalSuperiror_naranja=[];
            me.subTotal_modal_superior=0;
            if (lineaA==0) {
                var url = "/gestor-stock/listarAlertaModalSuperior?id_distri_lista="+me.selected.id_linea_array+"&tipo="+tipo+"&id_sucursal="+me.sucursalSeleccionada;
            } else {
                var url = "/gestor-stock/listarAlertaModalSuperior?id_distri_lista="+lineaA+"&tipo="+tipo+"&id_sucursal="+me.sucursalSeleccionada; 
            }
           
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                 
                    me.subTotal_modal_superior=respuesta.importe_total;
                    me.arrayModalSuperiror_naranja=respuesta.arrayMostrar;
                    if ((respuesta.simbolos).simbolo==null||(respuesta.simbolos).simbolo=="") {
                         me.simbolo="Bs";
                    }else{
 me.simbolo=(respuesta.simbolos).simbolo;
                    }
                   

                   
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        estadoBotton(data){
            let me=this;
            me.button_estado=data;
        },

        sucursalFiltro() {
            let me = this;
      
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;                 
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarSaldoCero() {
            let me = this;      
           var url = "/gestor-stock/saldoCero?id_sucursal="+me.sucursalSeleccionada+"&limitador="+me.selectModa_saldoCero;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    if (respuesta.length>0) {
                        me.arraySaldoCero=[];
                        me.arrayFalsoCero=[];   
                        me.arraySaldoCero=respuesta;
                        me.arrayFalsoCero=respuesta;    
                    } else {
                        me.arraySaldoCero=[];
                        me.arrayFalsoCero=[];   
                    }
                           
                               
                })
                .catch(function (error) {
                    error401(error);
              
                });
        },

        sucursalFiltro_v2(){
            let me  = this;
            var url = "/listar_sucursal_x_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;                 
                })
                .catch(function (error) {
                    error401(error);
                });
            
        },
       
        nameWithLang ({nom_a_facturar,nom_linea_array}) {            
            return `Dist: ${nom_a_facturar} Linea: ${nom_linea_array}`
          },

        nameWithLang_2 ({linea_nombre,nom_prod,dis_nom,cantidad,nom_for_farmaceutica,tipo}) {            
            return `${linea_nombre}: ${nom_prod} - ${dis_nom} X ${cantidad} ${nom_for_farmaceutica} ${tipo}`
          },

        listarDistribuidor() {
            let me = this;       
           var url = "/gestor-stock/listarDistribuidor";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayDistribuidor = respuesta;            
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },


        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
              
                    me.tituloModal = "Ventana gestor de stock";
                    me.showModal = true;
                    me.selected0null;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
          
            
                    me.classModal.openModal("registrar");

                    break;
                }

                case "alerta":{ 
                    if (me.tipo_modal===1) {
                        me.tituloModal_2="Orden de pedido alerta naranja";
                    } 
                    if (me.tipo_modal===2) {
                        me.tituloModal_2="Orden de pedido minimo";
                    } 
                    me.selectFormaPago="0";
                    me.button_estado=0;
                    me.showModal_2=true;
                    me.fechaPago="";
                    me.plazoPago="";
                    me.selectEntregaPedido="0";
                    me.observacion="NINGUNO";
                    me.input_bot=1;
                    me.count=0;
                    me.sumatoriaBot=0;
                    me.classModal.openModal("alerta");
                    me.isSubmitting=false;
                   
                    break;
                }
                case "saldo_cero":{
                    me.showModal_3 = true;  
                    me.tituloModal_3 = "Reporte saldo cero"; 
                    me.selectModa_saldoCero = 1;
                    me.buscarCero="";
                    me.classModal.openModal("saldo_cero");
                    break;
                }
            
            }
        },

        fecha_inicial(){
         
            // Obtener la fecha actual
    const today = new Date();
    // Obtener el año, mes y día actual
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const day = String(today.getDate()).padStart(2, '0');

    // Asignar la fecha del primer día del mes al input de fecha de inicio
    this.startDate = `${year}-${month}-01`;
    // Asignar la fecha actual al input de fecha final
    this.endDate = `${year}-${month}-${day}`;
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = " ";            
               me.selected=null;
            }

            if (accion == "alerta") {
                me.showModal_2 = false;
                me.subTotal_modal_superior=0;
                me.arrayModalSuperiror_naranja=[];
                me.button_estado=0;
                me.select_distribuidor_x_producto=null;
                me.arraySelect_distribuidor_x_producto=[];
                me.input_bot=1;
                 me.count=0;
                me.arrayInferior_falso=[];
                me.classModal.closeModal(accion);
                me.isSubmitting=false;
                me.sumatoriaBot=0;
                me.id_distribuidor=0;
                me.id_lineas="";
                me.tipo_modal=0;
                me.tituloModal_2="";
            }

            if (accion == "saldo_cero") {
               me.showModal_3 = false;  
               me.tituloModal_3 = " "; 
               me.selectModa_saldoCero="";
               me.arrayFalsoCero=[];
               me.arraySaldoCero=[];
               me.buscarCero="";
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
       // this.sucursalFiltro();
        this.sucursalFiltro_v2();
        this.fecha_inicial();
        this.classModal.addModal("registrar");
        this.classModal.addModal("alerta");    
        this.classModal.addModal("saldo_cero");   
         this.classModal.addModal("show"); 
       // this.listarIndex();    
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
.fullscreen-modal .modal-dialog {
  max-width: 100%;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

.fullscreen-modal .modal-content {
  height: 100%;
  border-radius: 0;
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>