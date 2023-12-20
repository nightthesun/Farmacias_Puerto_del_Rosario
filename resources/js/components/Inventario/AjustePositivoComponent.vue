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
                    <i class="fa fa-align-justify"></i> Ajustes Positivos
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar');ProductoLineaIngreso();" :disabled="sucursalSeleccionada==0" >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada==0" class="error">&nbsp; &nbsp;Debe Seleccionar un almacen o tienda.</span>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align:center">
                            <label for="" >Almacen o Tienda:</label>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" @change="listarAjustePositivos(0)" v-model="sucursalSeleccionada">
                                    <option value="0" >Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.codigo" v-text="sucursal.tipoCodigo+':'+sucursal.codigo+' Sucursal:'+sucursal.razon_social "></option>
                                </select>                              
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarAjustePositivos(1)">
                                <button type="submit" class="btn btn-primary" @click="listarAjustePositivos(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                  <!---------------------------------------------------------------->
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Codigó</th>
                                <th>Linea</th>
                                <th>Producto</th>
                                <th>Cantidad de Ingreso</th>
                                <th>Stock</th>  
                                <th>Lote</th>                               
                                <th>Fecha de Ingreso</th>
                                <th>Fecha de Vencimiento</th>
                                <th>Tipo</th>    
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                         <!--botones de opciones-->   
                          <tr v-for="AjustePositivos  in arrayAjustePositivos" :key="AjustePositivos.id">
                              <td>
                                 
                                 <button type="button" class="btn btn-warning btn-sm"  @click="abrirModal('actualizar',AjustePositivos);ProductoLineaIngreso();">
                                    <i class="icon-pencil"></i>    
                                 </button> &nbsp;
                                 <button v-if="AjustePositivos.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarAjustePositivos(AjustePositivos.id)" >
                                     <i class="icon-trash"></i>
                                 </button>
                                 <button v-else type="button" class="btn btn-info btn-sm" @click="activarAjustePositivos(AjustePositivos.id)" >
                                     <i class="icon-check"></i>
                                 </button>
                              
                             </td>
                             <td v-text="AjustePositivos.nombre_usuario"></td>
                             <td v-text="AjustePositivos.codigo"></td>
                             <td v-text="AjustePositivos.linea"></td>
                             <td v-text="AjustePositivos.nombreProd"></td>
                             <td v-text="AjustePositivos.cantidad_ingreso"></td>
                             <td v-text="AjustePositivos.stock_ingreso"></td>
                             <td v-text="AjustePositivos.lote"></td>
                             <td v-text="AjustePositivos.fecha_ingreso"></td>
                             <td v-text="AjustePositivos.fecha_vencimiento"></td>
                             <td v-text="AjustePositivos.nombreTipo"></td>
                             <td v-text="AjustePositivos.fecha"></td>
                        
                             <td>
                                 <div v-if="AjustePositivos.activo==1">
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
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="registrar" aria-hidden="true" data-backdrop="static" data-key="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">{{ tituloModal }} </h4>
                    <button  type="button" class="close" aria-label="Close" @click="cerrarModal('registrar')">
                        <span aria-hidden="true">x</span>
                    </button>
                    
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        Todos los campos con (*) son requeridos
                    </div>
                    <form action=""  class="form-horizontal">
                       <!-- insertar datos -->
                     <div class="container">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Producto
                                    <span v-if="ProductoLineaIngreso=='0'"  class="error">(*)</span>
                                </label>
                                <div class="col-md-9">
                                    
                                    <select name="" id="" v-model="ProductoLineaIngresoSeleccionado" class="form-control" @change="cambioDeEstado">
                                        <option v-bind:value="0" disabled>Seleccionar...</option>
                                        <option v-for="ProductoLineaIngreso in arrayProductoLineaIngreso" :key="ProductoLineaIngreso.id_ingreso" v-bind:value="ProductoLineaIngreso.id_ingreso" v-text="ProductoLineaIngreso.nombre+'-D1:'+(ProductoLineaIngreso.cantidad_dispenser_p === null?'': ProductoLineaIngreso.cantidad_dispenser_p)+'-D2:'+(ProductoLineaIngreso.cantidad_dispenser_s === null?'': ProductoLineaIngreso.cantidad_dispenser_s)+'-D3:'+(ProductoLineaIngreso.cantidad_dispenser_t === null?'': ProductoLineaIngreso.cantidad_dispenser_t)+'-'+ProductoLineaIngreso.nombre_farmaceutica_1+'-'+ProductoLineaIngreso.nombre_linea+'-LOTE:'+ProductoLineaIngreso.lote+'-FI:'+ProductoLineaIngreso.fecha_ingreso+'-FV:'+(ProductoLineaIngreso.fecha_vencimiento === null?'sin registro':ProductoLineaIngreso.fecha_vencimiento)+'-Stock:'+ProductoLineaIngreso.stock_ingreso"></option>
                                    </select>
                                    <input type="text" v-model="id_codigo"  >
                                    <input type="text"  v-model="codigo" >
                                    <input type="text"  v-model="linea" >
                                    <input type="text"  v-model="producto" >
                                    
                                    <input type="number" v-model="cantidadProductoLineaIngreso" >
                                    
                                    <input type="number" v-model="stock_ingreso" >
                                    <input type="number" v-model="lote" >
                                    <input type="text"  v-model="fecha_ingreso" >
                                    <input type="text"  v-model="fecha_vencimiento" >                                    

                                    <input type="text"  v-model="id_sucursal" >
                                    <input type="text" v-model="id_producto" >
                                    <input type="text" v-model="id_ingreso" >

                                 
                                     </div>
                            </div>
                                   <div class="form-group row">
                                      <label class="col-md-3 form-control-label" for="text-input">Cantidad 
                                        <span v-if="cantidadS==''" class="error">(*)</span>
                                      </label>
                                        <div class="col-md-9">
                                         <input type="number" id="cantidad" name="cantidad" v-model="cantidadS" class="form-control" placeholder="Datos de stock" v-on:focus="selectAll" >
                                           <span v-if="cantidadS==''" class="error">Debe Ingresar una cantidad</span> 
                                        </div>
                                  </div>   

                           <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">
                                    Tipo
                                    <span   class="error">(*)</span>
                                </label>
                                <div class="col-md-9">
                                    <select name="" id="" v-model="TiposSeleccionado" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="Tipos in arrayTipos" :key="arrayTipos.id" :value="Tipos.id" v-text="Tipos.nombre"></option>
                                    </select>
                                    <span v-if="TiposSeleccionado==0" class="error">Debe seleccionar una opcion</span>
                                </div>
                            </div>
                          </div>    
                   
                               
 
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrorAjustePositivo()" :disabled="!sicompleto" >Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAjusteNegativo()">Actualizar</button>
                   
                    
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
                pagination:{
                    'total' :0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0
                },
                pestañaActiva: 'pills-envase-primario-tab',
    
     

                
                tituloModal:'',
                arrayTipos:[],
                TiposSeleccionado:0,
                id_tipo:'',
                arrayProductoLineaIngreso:[],
                ProductoLineaIngresoSeleccionado:0,
               
                id_producto_linea:'',
                cantidadS:'',
                listarTipo:0,
                cantidadProductoLineaIngreso:'',
          
                codigo:'',
                linea:'',
                producto:'',
                stock_ingreso:'',
                lote:'',
                fecha_ingreso:'',
                fecha_vencimiento:'',
                id_ingreso:'',
                id_producto:'',
                id_codigo:'',
                arrayAjustePositivos:[],
     
                buscar:'',
                idAjustePositivos:0,
                offset:3,
                tipoAccion:1,
                id_sucursal:'',
                arraySucursal:[],
                sucursalSeleccionada:0,
                sucuralName:'',
                sucursalId:0,

             

            }
        },
    
      watch:{
        ProductoLineaIngresoSeleccionado:function(newValue){
            if (newValue > 0 ) {
               if (this.tipoAccion === 1) {
                this.cantidadS=0;
               } 
          
            let productoSeleccionado=this.arrayProductoLineaIngreso.find(element=>element.id_ingreso === newValue);
       
            if (productoSeleccionado) {
           this.cantidadProductoLineaIngreso=productoSeleccionado.stock_ingreso;
          this.codigo=productoSeleccionado.codigo_producto;
         this.linea=productoSeleccionado.nombre_linea;
         this.producto=productoSeleccionado.nombre;
        this.fecha_ingreso=productoSeleccionado.fecha_ingreso;
        this.fecha_vencimiento=productoSeleccionado.fecha_vencimiento;
        this.lote=productoSeleccionado.lote;
        this.stock_ingreso=productoSeleccionado.stock_ingreso;
         this.id_sucursal=productoSeleccionado.id_sucursal;
         this.id_producto=productoSeleccionado.id_producto; 
         this.id_ingreso=productoSeleccionado.id_ingreso;      
                } else {
    console.log("No matching element found in arrayProductoLineaIngreso.");
        }
            } else {
                this.cantidadS ='';
            }
         
        },
        

        cantidadS: function (valor) {
            if (valor > this.cantidadProductoLineaIngreso) {
                if (this.tipoAccion === 1) {
                this.cantidadS=0;
               } 
               
                Swal.fire(
                      'No puede ingresar un número mayor al stock actual',
                        'Haga click en Ok',
                        'error'
                )
                console.log("No se puede ingresar datos mayor que el stock actual");
            } else if (valor !== this.cantidadProductoLineaIngreso) {
                this.cantidadS = valor;
            }
        },
        buscar: function (valor){
            if (this.buscar !== '') {
              this.sucursalSeleccionada='';
            } 
        }
     
      },

       computed:{
           sicompleto(){
            let me=this;
            if (me.TiposSeleccionado!='' && me.cantidadS!='' && me.ProductoLineaIngresoSeleccionado)
                return true;
               else 
                return false;    
            
           },
           isActived:function(){
                return this.pagination.current_page;
            },
           
            pagesNumber:function(){
                if(!this.pagination.to){
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from<1){
                    from=1;
                }
                var to = from +(this.offset * 2);
                if(to>= this.pagination.last_page){
                    to=this.pagination.last_page;
                }
                var pagesArray =[];
                while(from<=to){
                    pagesArray.push(from);
                    from++
                }
                return pagesArray;
            }, 
           
           
        },
      
       methods :{
        cambiarPestana(idPestana) {
      this.pestañaActiva = idPestana;
    
      // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
    },
        sucursalFiltro(){

               let me=this;
                var url='/ajustes-positivo/listarSucursal';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arraySucursal=respuesta;
                    console.log( me.arraySucursal);
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
        
        
        ajustesPositivos(){
                let me=this;
                var url='/ajustes-positivo/listarTipo';
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
          
            cambiodeEstado(valor){
                 let me=this;
                 const productoSeleccionado = me.arrayProductoLineaIngreso.find(element => element.id == this.ProductoLineaIngresoSeleccionado);
      

              }  ,
          
                
          ProductoLineaIngreso(){
                let me=this;
               
                if(me.tipoAccion==1){
                    var url='/ajustes-positivo/listarProductoLineaIngreso?respuesta0=' +this.sucursalSeleccionada;
                 }
                 if (me.tipoAccion==2) {
                    var url='/ajustes-positivo/listarProductoLineaIngreso?respuesta0=' +this.id_codigo;
                 
                 }
                
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    
                    me.arrayProductoLineaIngreso=respuesta;
        
                    console.log( me.arrayProductoLineaIngreso);
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
          

        cambiarPagina(page){
               let me =this;
              me.pagination.current_page = page;
               me.listarAjustePositivos(page);
           },


        abrirModal(accion,data= []){
            let me=this;
            let respuesta=me.arraySucursal.find(element=>element.codigo==me.sucursalSeleccionada);
            console.log(data.id+" "+data.id_ingreso+" "+data.cod);
            switch(accion){
                
                    case 'registrar':

                    {
                        me.tipoAccion=1;
                        me.tituloModal='Rejistro para Ajuste de positivo en la sucursal: '+respuesta.razon_social;
                        me.ProductoLineaIngresoSeleccionado=0;
                       
                        me.id_codigo=me.sucursalSeleccionada;
                        me.cantidadProductoLineaIngreso=''; 
                        me.TiposSeleccionado=0; 
                        me.cambiodeEstado='';
                       
                        me.codigo='';
                        me.linea='';
                        me.producto='',
                        me.cantidadS='';
                        me.stock_ingreso='',
                        me.lote='',
                        me.fecha_ingreso='',
                        me.fecha_vencimiento='',
                        me.id_sucursal='';
                        me.id_producto='';
                        me.id_ingreso='';
                        me.classModal.openModal('registrar');
                        break;
                    }
                    case 'actualizar':
                        { 
                            me.id_codigo=data.cod; 
                            me.tipoAccion=2;
                            me.tituloModal='Actualizacion para Ajuste de negativos en la sucursal: ';
                            me.codigo=data.codigo;
                            me.cantidadProductoLineaIngreso=data.cantidad_ingreso;
                        me.linea=data.linea;
                        me.producto=data.nombreProd;
                        me.cantidadS=0;
                        me.stock_ingreso=data.stock_ingreso,
                        me.lote=data.lote,
                        me.fecha_ingreso=data.fecha_ingreso,
                        me.fecha_vencimiento=data.fecha_vencimiento,
                
                       me.idAjustePositivos=data.id;
                       me.id_sucursal=data.id_sucursal;
                        me.id_producto=data.cod;
                       me.id_ingreso=data.id_ingreso;
                        me.TiposSeleccionado=data.id_tipo===null?0:data.id_tipo;
                       me. ProductoLineaIngresoSeleccionado=data.id_ingreso===null?0:data.id_ingreso;
                    
                           
                        
                       
                        me.classModal.openModal('registrar');
                            break;
                        }
                 

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.ProductoLineaIngresoSeleccionado=0;
                me.TiposSeleccionado=0; 
                me.cantidadProductoLineaIngreso='';
                me.tipoAccion=1;
                me.id_codigo='';
                        me.codigo='';
                        me.linea='';
                        me.producto='',
                        me.cantidadS='';
                        me.stock_ingreso='',
                        me.lote='',
                        me.fecha_ingreso='',
                        me.fecha_vencimiento='',
                        me.fecha='';
                        me.id_sucursal='';
                      me.id_ingreso='';
                        me.id_producto='';
                           
            },
        
            registrorAjustePositivo(){
                let me =  this;
              let suma = me.cantidadProductoLineaIngreso+me.cantidadS
               
                if (me.codigo === "" || me.linea  === "" || me.producto ===  "" || me.cantidadS === "" || me.TiposSeleccionado === ""  || me.fecha === "" ) {
                    Swal.fire(
                        'No puede ingresar valor nulos  o vacios',
                        'Haga click en Ok',
                        'warning'
                    )    
                }
                else {
                    axios.post('/ajustes-positivo/registrar',{
                   'id_tipo':me.TiposSeleccionado,
                   'id_producto_linea':me.ProductoLineaIngresoSeleccionado,
                   'codigo':me.codigo,
                   'linea':me.linea,
                   'producto':me.producto,
                   'cantidad_ingreso':suma,
                    'stock_ingreso':me.stock_ingreso,        
                   'fecha_ingreso':me.fecha_ingreso,
                   'fecha_vencimiento':me.fecha_vencimiento,
                   'lote':me.lote,
                   'activo':1,
                   'id_sucursal':me.id_sucursal,
                   'id_producto':me.id_producto,
                 'cod':me.sucursalSeleccionada,
                 'id_ingreso':me.id_ingreso,
                   
                 
            }).then(function(response){
                    me.cerrarModal('registrar');
                    Swal.fire(
                        'Ajuste de positivo Registrado exitosamente',
                        'Haga click en Ok',
                        'success'
                    )
                    
                 me.listarAjustePositivos();
                 me.sucursalFiltro();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });      
                }
            },
            actualizarAjusteNegativo(){
                let me =this;
                let suma = me.cantidadProductoLineaIngreso-me.cantidadS
                axios.put('/ajustes-positivo/actualizar',{

                    'id': me.idAjustePositivos,
                   'id_tipo':me.TiposSeleccionado,
                   'id_producto_linea':me.ProductoLineaIngresoSeleccionado,
                  'codigo':me.codigo,
                   'linea':me.linea,
                  'producto':me.producto,
                  'cantidad':suma,
                
                  'fecha':me.fecha,                  
                  'id_sucursal':me.id_sucursal,
                   'id_ingreso':me.id_ingreso, 
                  'id_producto':me.id_producto,
                  'cod':me.sucursalSeleccionada,
                 'id_ingreso':me.id_ingreso,
                
                }).then(function (response) {
                    me.listarAjustePositivos();
                 me.sucursalFiltro();
                    Swal.fire(
                        'Actualizado Correctamente!',
                        'El registro a sido actualizado Correctamente',
                        'success'
                    )
                }).catch(function (error) {
                    error401(error);
                });
                me.cerrarModal('registrar'); 
            },
            //para listar db alm__ajuste_positivos
            listarAjustePositivos(page){
               let me=this;
             
               if (me.sucursalSeleccionada==0 ) {
                
                var url='/ajustes-positivo?page='+page+'&buscar='+me.buscar;
                
                me.sucursalSeleccionada=0;
               }else{
                
                var url='/ajustes-negpositivoativo?page='+page+'&buscar='+me.sucursalSeleccionada;
                me.buscar='';
               }
              
               axios.get(url)
               .then(function(response){
                    var respuesta =  response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayAjustePositivos = respuesta.query_ajuste_negativos.data;
               }).catch(function (error) {
                    error401(error);
               });             
            },

            eliminarAjustePositivos(idAjustePositivos){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/ajustes-positivo/desactivar',{
                        'id': idAjustePositivos
                    }).then(function (response) {
                        me.listarAjustePositivos();
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarAjustePositivos();
                        
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
            
            activarAjustePositivos(idAjustePositivos){
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
                     axios.put('/ajustes-positivo/activar',{
                        'id': idAjustePositivos
                    }).then(function (response) {
                        me.listarAjustePositivos();
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
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },

       },
  
       mounted() {
        this.classModal = new _pl.Modals();
        this.classModal.addModal('registrar');
        this.listarAjustePositivos(1);
        this.ajustesPositivos();
      
        this.cambiodeEstado();
        this.sucursalFiltro();
       
        
       }
     }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;  
}
</style>