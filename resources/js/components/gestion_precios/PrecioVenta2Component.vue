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
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align:center">
                            <label for="">Tiendas y/o Almacenes Disponibles:</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control"  @change="listarProductosTiendaAlmacen(1)"
                                v-model="tiendaalmacenselected">
                                    
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option
                                        v-for="sucursal in arrayAlmacenesTiendas"
                                        :key="sucursal.codigo"
                                        :value="sucursal.codigo"
                                        v-text="sucursal.codigoS + ' -> ' +
                                            sucursal.codigo+ ' ' +
                                            sucursal.razon_social
                                            
                                        "
                                    ></option>
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
                               
                                    :hidden="tiendaalmacenselected == 0"
                                    :disabled="tiendaalmacenselected == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                 
                                    :hidden="tiendaalmacenselected == 0"
                                    :disabled="tiendaalmacenselected == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--fin de form-group row-->
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo</th>
                                <th>Linea o Marca</th>
                                <th>Entrada</th>
                                <th>Cantidad</th>
                                <th>Stock</th>
                                <th>Precio Lista</th>
                                <th>Costo Compra</th>
                                <th>Precio Venta</th>
                                <th>% Utilidad Bruta</th>
                                <th>Tipo Entrada</th>
                                <th>Fecha y Hora</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tr v-for="producto in arrayProductosAlterado" :key="producto.id" :style="[ producto.listo_venta == 1 ? '':'background-color: #FAD537' ]">
                            <td>

                            </td>
                        </tr>     
                    </table>    
                </div>        
            </div>
        </div>
    </main>
</template>   
<script>
    import Swal from 'sweetalert2';
    import { error401 } from '../../errores';
// import router from "@/router";
export default {
    data() {
        return {
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            offset: 3,
            tituloModal:'',
            tipo: 0,
            tipoAccion: 1, 
            buscar:'',
            tiendaalmacenselected:0,  
            arrayAlmacenesTiendas:[],
            arrayProductosAlterado:[],        
        }

    },
    computed: {

sicompleto(){
    let me = this;
    if(me.p_venta > 0 && me.margen_20>0 && me.margen_30>0 && me.utilidad_neta>=0)
    {
        return true;
    }
    else{
        return false;
    }
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
    var to = from + (this.offset * 2);
    if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
    }
    var pagesArray = [];
    while (from <= to) {
        pagesArray.push(from);
        from++
    }
    return pagesArray;
},


},
methods: {
    listarProductosTiendaAlmacen(page) {
        let me=this;
                var url='/gestionprecioventa2?page='+page+'&buscar='+me.buscar+'&buscarAlmTdn='+me.tiendaalmacenselected;
             
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayProductosAlterado = respuesta.queryCombinacion.data;
                })
                .catch(function(error){
                    error401(error);
                });
        
    },  
    listarAlmTienda() {
            let me = this;
            var url = "/gestionprecioventa2/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmacenesTiendas = respuesta;                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
     
    abrirModal(accion, data = []) {
            let me = this;
            switch (accion) {
               

                case 'editarPrecioUtilidadProducto':
                    {
                        let me = this;
                        me.tituloModal = 'Modificar Utilidad del Producto';
                     
                        me.classModal.openModal('calculadoraModal');
                        break;
                    }

            }

        },
        cerrarModal(accion) {
            let me = this;
            me.existe_registro_gespreventa = 0;
            me.id_gespreventa = 0;
            me.tienda_gespreventa = 0;
            me.almacen_gespreventa = 0;
            me.tipoAccion = 1;
            me.margen_20 = 0;
            me.margen_30 = 0;
            me.p_venta = 0;
            me.utilidad_bruta = 0;
            me.utilidad_neta = 0;
            me.dpc1 = 0;
            me.dpc2 = 0;
            me.dpc3 = 0;
            me.dbsc = 0;
            me.l20pc = 0;
            me.l30pc = 0;
            me.pucc = 0;
            me.ubc = 0;
            me.upc =  0;
            me.pvc = 0;
            me.pcc = 0;
            me.pcdc = 0;
            me.classModal.closeModal(accion);        
        },
        selectAll: function (event) {
            setTimeout(function () {
                event.target.select()
            }, 0)
        },
        utilidad() {

let me = this;
// var p_compra = parseFloat(me.p_compra);
me.margen_20 = ((parseFloat(me.p_compra) * 100) / 80).toFixed(2);
me.margen_30 = ((parseFloat(me.p_compra) * 100) / 70).toFixed(2);
me.utilidad_bruta = (parseFloat(me.p_venta) - parseFloat(me.p_compra)).toFixed(2);
me.utilidad_neta = ((parseFloat(me.p_venta) - parseFloat(me.p_compra)) / me.p_venta) * 100;
me.utilidad_neta = Math.round(me.utilidad_neta);
},
calculadoraCostoCompra(){

let me = this;

me.pcc = parseFloat(me.pcc); // pcc = Precio de Compra
me.dpc1 = parseFloat(me.dpc1); // dpc1 = Descuento % (primer input)
me.dpc2 = parseFloat(me.dpc2); // dpc2 = Descuento % (segundo input)
me.dpc3 = parseFloat(me.dpc3); // dpc3 = Descuento % (tercer input)
me.dbsc = parseFloat(me.dbsc); // dbsc = Descuento Bs.

var cd = parseInt((/[a-z]/.test(me.c_disp.toLowerCase())?1:me.c_disp)); // cd = Cantidad Dispenser
me.pcdc = (me.pcc - (me.pcc * me.dpc1 / 100)); //  pcdc = Costo Compa C/Desc.
me.pcdc = (me.pcdc - (me.pcdc * me.dpc2 / 100)); 
me.pcdc = (me.pcdc - (me.pcdc * me.dpc3 / 100));
me.pcdc = me.pcdc - me.dbsc;
me.puc = (me.pcdc / cd); // puc = Precio Unitario


me.pcc = me.pcc.toFixed(2);
me.dpc1 = me.dpc1.toFixed(2);
me.dpc2 = me.dpc2.toFixed(2);
me.dpc3 = me.dpc3.toFixed(2);
me.dbsc = me.dbsc.toFixed(2);
me.puc = me.puc.toFixed(2);
me.pcdc = me.pcdc.toFixed(2);
},
calculadoraPrecioVenta() {
            let me = this;
            // me.pcc = parseFloat(me.pcc);
            // me.dpc1 = parseFloat(me.dpc1);
            // me.dpc2 = parseFloat(me.dpc2);
            // me.dpc3 = parseFloat(me.dpc3);
            // me.dbsc = parseFloat(me.dbsc);
            // var cd = parseInt((/[a-z]/.test(me.c_disp.toLowerCase())?1:me.c_disp));
            // me.pcdc = (me.pcc - me.dbsc - (me.pcc * me.dpc1 / 100)).toFixed(2);
            // me.pcdc = (me.pcdc - (me.pcdc * me.dpc2 / 100)).toFixed(2);
            // me.pcdc = (me.pcdc - (me.pcdc * me.dpc3 / 100)).toFixed(2);
            // me.puc = (me.pcdc / cd).toFixed(2);
            // me.l20pc = ((me.puc * 100) / 70).toFixed(2);
            // me.l30pc = ((me.puc * 100) / 60).toFixed(2);
            // me.pucc = parseFloat(me.pucc).toFixed(2);
            // me.pvc = parseFloat(me.pvc).toFixed(2);
            // me.ubc = (me.pvc - me.pucc).toFixed(2);
            // me.upc = ((me.ubc * 100) / me.pvc).toFixed(2);
            // me.pcc = me.pcc.toFixed(2);
            // me.dpc1 = me.dpc1.toFixed(2);
            // me.dpc2 = me.dpc2.toFixed(2);
            // me.dpc3 = me.dpc3.toFixed(2);
            // me.dbsc = me.dbsc.toFixed(2);
            

            me.pcdc = parseFloat(me.pcdc);
            me.pvc = parseFloat(me.pvc == 0 ? me.p_venta:me.pvc); // pvc = Precio de Venta
            me.pucc = parseFloat(me.pucc == 0 ? me.puc:me.pucc); // pucc = P/U de Compra
            me.l20pc = ((me.pucc * 100) / 80); // l20pc = Liq. 20 %
            me.l30pc = ((me.pucc * 100) / 70); // l30pc = Liq. 30 %
            me.ubc = (me.pvc - me.pucc); // ubc = Utilidad Bruta
            me.upc = ((me.ubc * 100) / me.pvc); //  upc = Utilidad Bruta%

            // console.log("**********Utilidad********");
            // console.log(        
            // 'pdcd: '+me.pcdc+'\n'+
            // 'puc: '+me.puc+'\n'+
            // 'l20pc: '+me.l20pc+'\n'+
            // 'l30pc: '+me.l30pc+'\n'+
            // 'ubc : '+me.ubc +'\n'+
            // 'upc: '+me.upc+'\n'+
            // 'pucc: '+me.pucc+'\n'+
            // 'pvc: '+me.pvc+'\n'
            // );


            me.pcdc = me.pcdc.toFixed(2);            
            me.l20pc = me.l20pc.toFixed(2);
            me.l30pc = me.l30pc.toFixed(2);
            me.pucc = me.pucc.toFixed(2);
            me.ubc = me.ubc.toFixed(2);
            me.upc = me.upc.toFixed(2); // Math.round(me.upc);
            me.pvc = me.pvc.toFixed(2);
        }

},
mounted() {
        this.classModal = new _pl.Modals();
        this.listarProductosTiendaAlmacen();
        this.listarAlmTienda();
        this.classModal.addModal('calculadoraModal');
       
    }
}
    
</script> 
<style scoped>

#area-botones-guarcancelar{
    margin-top: 28px;
}

h1 {
    color: red;
}

label {
    font-size: 11px;
}

.modal-xl {
    width: 900px;
}

table > thead > tr > th {
    text-align: center;
    display: table-cell;
    vertical-align: middle;    
}
table > tbody > tr > td > div {
    font-size: 15px;
}
</style>       