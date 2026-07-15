
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';


//pdfMake.vfs = pdfFonts.pdfMake.vfs;
pdfMake.vfs = pdfFonts.vfs;

export function generarPDF_factura_rollo(direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,
                      total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,
                      nombre_empresa,actividad_economica,num_auto,cod_autorizacion,fecha_e_2,ciudad_su_1,departamento_su_1,numero_factura,cliente_id,
                      descuento_final_2,total_literal,nit_2,tipo_venta_1,monto_vale_1,monto_apagar_1,credito_fiscal,leyenda,puntoVenta,url_qr,ambiente,factura_,montoGiftCard,moneda,anulado) {
          try {
         
            let watermark = {};   
           
      if (ambiente===2) { // Aquí puedes poner tu condición
  watermark = { text: 'SIN VALOR LEGAL', color: 'red', angle: -65, opacity: 0.3, bold: true, italics: false, fontSize: 30 };
}
  
 let watermark_2 = {};    
if (anulado==1) {
   watermark_2 = { text: 'ANULADO', color: 'red', angle: 65, opacity: 0.3, bold: true, italics: false, fontSize: 30 };
}
              if (montoGiftCard===0) {
                        montoGiftCard="0.00";
                       } 
                 

// Itera sobre los datos y agrega filas a la tabla
  const tableBody_2=[];
  let sumador_subtotal=0;
  array_recibo.forEach(item => {
    
    let descuento_dosificacion_2="";
    if (Number.isInteger(Number(item.descuento))) {
      descuento_dosificacion_2= Number(item.descuento).toFixed(2);
    } else if (item.descuento === 0 || item.descuento === "0") {
      descuento_dosificacion_2= "0.00";
    } else {
        // Si es decimal, devuelve el valor tal como está
        descuento_dosificacion_2=item.descuento;
    }
    sumador_subtotal=sumador_subtotal+((item.cant * item.p_u)-descuento_dosificacion_2);
    tableBody_2.push([
    { text: item.descrip , fontSize: 7, alignment: 'left' }, // Salto de línea
      {},   
    ]);
    tableBody_2.push([
    { text: 'Unidad de medida: '+item.unidad_medida, fontSize: 7, margin: [5, 0, 0, 0],alignment: 'left' }, // Salto de línea
      {},   
    ]);
    tableBody_2.push([
    { text: item.cant+'.00 x '+item.p_u+' - '+(descuento_dosificacion_2), fontSize: 7, alignment: 'left' }, // Salto de línea
    { text: ((item.cant * item.p_u)-descuento_dosificacion_2).toFixed(2), fontSize: 7, alignment: 'right' },   
    ]);
  });
  
  const table_totales = [
    // Agrega los encabezados de la tabla
    [
      { text: 'SubTotal '+moneda+': ' ,  fontSize: 7, alignment: 'right' }, 
      { text: (sumador_subtotal).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Descuento '+moneda+': ',  fontSize: 7, alignment: 'right' }, 
      { text: (descuento_final_2).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Total '+moneda+': ',  fontSize: 7, alignment: 'right' }, 
      { text: (total_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Monto gift card '+moneda+': ',  fontSize: 7, alignment: 'right' }, 
      { text: montoGiftCard, fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Monto a pagar '+moneda+': ',  fontSize: 7, alignment: 'right',bold: true }, 
      { text: (total_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Importe base crédito fiscal '+moneda+': ',  fontSize: 7, alignment: 'right',bold: true }, 
      { text: (total_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ]
  ];

  const table_pago_efe_cambio=[
  [
      { text: 'Pago en efectivo '+moneda+': ',  fontSize: 7, alignment: 'right' }, 
      { text: (efectivo_venta).toFixed(2), fontSize: 7, alignment: 'right' }     
    ],
    [
      { text: 'Cambio '+moneda+': ',  fontSize: 7, alignment: 'right' }, 
      { text: cambio_venta, fontSize: 7, alignment: 'right' }     
    ],
  ];
   
      const documentDefinition = {
        pageMargins: [10, 12, 10, 8], // Configura los márgenes en cero
        pageSize: {
    width: 80 * 2.83465, // Ancho en puntos (conversión a puntos desde mm)
    height: 'auto',
    columnGap: 2,
  },
 
      content: [
      {
        text: factura_, bold: true,
      
        style: 'header'
      },
      {
        text: credito_fiscal, bold: true,
      
        style: 'header'
      },
      {
        text: nombre_empresa,
      
        style: 'header'
      },
      {
        text: nomsucursal,
   
        style: 'header'
      },
      {
        text: 'No. Punto venta '+puntoVenta,
   
        style: 'header'
      },
      {
        text: direccionMayusculas,
    
        style: 'header'
      },
      {
        text: 'Telefono: '+numero_referencia,
    
        style: 'header'
      },      
      {
        text: ciudad_su_1,
    
        style: 'header'
      },
      
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'NIT', bold: true,
    
        style: 'header'
      },
      {
        text: nit_2,
    
        style: 'header'
      },
      {
        text: 'FACTURA Nº',bold: true,    
        style: 'header'
      },
      {
        text: numero_factura,    
        style: 'header'
      },
      {
        text: 'CÓD. AUTORIZACIÓN', bold: true,    
        style: 'header'
      },
      {
        text: num_auto,    
        style: 'header'
      },
           
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'NOMBRE/RAZÓN SOCIAL:    '+nom_a_facturar,
     style: 'normal', margin: [19, 0, 0, 0]        
      },
      {
        text: 'NIT/CI/CEX:    '+num_documento,
        style: 'normal', margin: [63, 0, 0, 0]             
      },
      {
        text: 'COD.CLIENTE:    '+cliente_id,
        style: 'normal', margin: [56, 0, 0, 0]        
      },
     
      {
        text: 'FECHA DE EMISIÓN:    '+fecha+' '+hora,
        style: 'normal', margin: [37, 0, 0, 0] 
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'DETALLE',
    
        style: 'header'
      },       

      {
       
        table: {
          headerRows: 1,
          widths: ['75%', '25%'], // Ajusta los anchos de las columnas
          body: tableBody_2
        },
        layout: 'noBorders'
		},
        {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
        {         
            table:{
                headesRows:1,
                widths: ['75%','25%'],
                body: table_totales
            }, layout: 'noBorders'
        },
    
    
      
      {
        text: 'Son: '+total_literal,      
        alignment: 'left',fontSize: 7
      },
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },     
      {
        margin: [1, 1, 1, 1],         
            table:{
                headesRows:1,
                widths: ['75%','25%'],
                body: table_pago_efe_cambio
            }, layout: 'noBorders'
      },     
      {
        text: '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -',    
        style:'linea_2' 
      },
      {
        text: 'ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY', bold: true,    
        style: 'header', margin: [7, 5, 7, 1]
      },
      {
        text: leyenda,   
        style: 'header' ,margin: [7, 1, 7, 3]
      },
      {
        text: 'Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea',   
        style: 'header' ,margin: [7, 1, 7, 3]
      },
      { qr:  url_qr,alignment: 'center',  fit: '85',margin: [0, 4, 0, 4] },
      
        ],
         watermark: watermark, // Agrega la marca de agua condicionalmente
         watermark_2:watermark_2,
        styles: {
            linea_2: {
                fontSize: 9,
                 margin: [1, 1, 1, 1],
                 alignment: 'center',  
            },
            normal: {
            fontSize: 7,                  
          },
          header: {
            fontSize: 7,
           
            alignment: 'center',         
          },
          header_1: {
            fontSize: 7,
           
            alignment: 'right', 
                    
          },
          datos_f: {
            fontSize: 7,
         
            alignment: 'left',         
          },
          
        tableHeader_1: {
  
        fontSize: 7,

            alignment: 'justify',
      },
      tableHeader_2: {

        fontSize: 7,
      
            alignment: 'center',
      }
        }
      };

      // Genera el PDF y abre una nueva ventana con el documento

    pdfMake.createPdf(documentDefinition).open();
          } catch (error) {
            return error;
          }   
    } 


