const { Pool } = require("pg");
// const excelToJson = require('convert-excel-to-json');
const axios = require('axios');
// const moment = require('moment');
// moment.locale('es');
//DATABASE_URL=postgres://dfhvxjac:q0P8cF8kbm42Wi9mXXfxTMChFZjSovzN@kesavan.db.elephantsql.com/dfhvxjac
const pool = new Pool({
  connectionString: process.env.DATABASE_URL
});

// const moment = require('moment');
// moment.locale('es');

// let userCertificates = [];
// let certificates = [];
// let users = [];

// let historicos = [];

// const lms = async () => {
//   let result = await axios({
//     url: 'https://us-api.365.systems/odata/v2/Users', //your url
//     method: 'GET',
//     responseType: 'json', // important
//     auth: {
//       username: 'api',
//       password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
//     }
//   });
//   users = result.data;
//   result =  await axios({
//     url: 'https://us-api.365.systems/odata/v2/Certificates', //your url
//     method: 'GET',
//     responseType: 'json', // important
//     auth: {
//       username: 'api',
//       password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
//     }
//   });
//   certificates = result.data;
//   certificates.value.forEach(async (element) => {
//     users.value.forEach(async (elementUser) => {
//       if (element.UserId === elementUser.Id) {
//         // const httpOptions = {
//         //   headers: new HttpHeaders(
//         //     {
//         //       'Content-Type': 'application/json',
//         //       'Authorization': `Basic ` + btoa('api:ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'),
//         //     }
//         //   )
//         // };
//         // let result = await this.http.get('https://us-api.365.systems/odata/v2/Courses(65c61b1a-04c1-46e6-9d6e-41576a0ce14f)', httpOptions).toPromise();
//         // let resultCourse : any = {};
//         // resultCourse = result;
//         if ('65c61b1a-04c1-46e6-9d6e-41576a0ce14f' === element.CourseId) {
//           const userCertificate = {
//             "nombrecompleto": elementUser.Title,
//             "rut": elementUser.Department,
//             "puestotrabajo": elementUser.JobTitle,
//             "idcurso": element.CourseId,
//             "correopersonal": elementUser.LoginName.replace("i:0#.f|membership|", ""),
//             "fechafinalizacion": element.Issued,
//             "fechavencimiento": element.Expiry,
//             "nombrecurso": "Inducción de Mantención - TECK Carmen de Andacollo"
//           }
//           userCertificates.push(userCertificate);
//         }

//         if ('cc49457f-da5d-40c4-8e06-271f7bed6819' === element.CourseId) {
//           const userCertificate = {
//             "nombrecompleto": elementUser.Title,
//             "rut": elementUser.Department,
//             "puestotrabajo": elementUser.JobTitle,
//             "idcurso": element.CourseId,
//             "correopersonal": elementUser.LoginName.replace("i:0#.f|membership|", ""),
//             "fechafinalizacion": element.Issued,
//             "fechavencimiento": element.Expiry,
//             "nombrecurso": "Inducción de Persona Nueva - TECK Carmen de Andacollo"
//           }
//           userCertificates.push(userCertificate);
//         }
//       }
//     });
//   });

//   // const queryResult = await pool.query("SELECT * FROM historicocert WHERE to_char(fechavencimiento , 'yyyy-MM-dd') = $1", ['2021-10-20']);
//   // console.log(queryResult.rowCount);
//   let contador = 0;
//   // const fixFecha = moment(userCertificates[0].fechavencimiento).format().split("T");
//   // console.log(fixFecha, userCertificates[0].fechavencimiento, userCertificates[0].rut, userCertificates[0].nombrecompleto);
//   // console.log(moment.utc(fixFecha[0]).format())

//   console.log(moment.utc(new Date()).format().toString().split("T")[0]);
//   const currentArray = userCertificates.filter(x => moment.utc(x.fechafinalizacion).format().toString().split("T")[0] === moment.utc(new Date()).format().toString().split("T")[0]);
//   currentArray.forEach(async (element) => {
//     const fixFecha = moment(element.fechavencimiento).format().split("T");
//     const queryResult = await pool.query("SELECT * FROM historicocert WHERE to_char(fechavencimiento , 'YYYY-MM-DD') = $1 AND rut = $2 AND idcurso = $3", [fixFecha[0], element.rut, element.idcurso]);
//     if (queryResult.rowCount === 0) {
//       await pool.query('INSERT INTO historicocert (rut, nombrecompleto, correopersonal, puestotrabajo, idcurso, nombrecurso, fechafinalizacion, fechavencimiento, fechainscripcion, nombreempresa, rutempresa) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [!!element.rut ? element.rut : "NA", !!element.nombrecompleto ? element.nombrecompleto : "NA", !!element.correopersonal ? element.correopersonal : "NA",!!element.puestotrabajo ? element.puestotrabajo : "NA", element.idcurso, element.nombrecurso,element.fechafinalizacion, element.fechavencimiento, '-', 'NA', 'NA']);
//     }
//   })


//   // userCertificates.forEach(async (element) => {
//   //   const fixFecha = moment(element.fechavencimiento).format().split("T");
//   //   const queryResult = await pool.query("SELECT * FROM historicocert WHERE to_char(fechavencimiento , 'YYYY-MM-DD') = $1 AND rut = $2 AND idcurso = $3", [fixFecha[0], element.rut, element.idcurso]);

//   //   if (queryResult.rowCount === 0) {
//   //     contador = contador + 1;
//   //     await pool.query('INSERT INTO historicocert (rut, nombrecompleto, correopersonal, puestotrabajo, idcurso, nombrecurso, fechafinalizacion, fechavencimiento, fechainscripcion, nombreempresa, rutempresa) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [!!element.rut ? element.rut : "NA", !!element.nombrecompleto ? element.nombrecompleto : "NA", !!element.correopersonal ? element.correopersonal : "NA",!!element.puestotrabajo ? element.puestotrabajo : "NA", element.idcurso, element.nombrecurso,element.fechafinalizacion, element.fechavencimiento, '-', 'NA', 'NA']);
//   //     console.log(contador);
//   //   }
//   //   if (queryResult.rows.length === 0) {
//   //     await pool.query('INSERT INTO historicocert (rut, nombrecompleto, correopersonal, puestotrabajo, idcurso, nombrecurso, fechafinalizacion, fechavencimiento, fechainscripcion, nombreempresa, rutempresa) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [!!element.rut ? element.rut : "NA", !!element.nombrecompleto ? element.nombrecompleto : "NA", !!element.correopersonal ? element.correopersonal : "NA",!!element.puestotrabajo ? element.puestotrabajo : "NA", element.idcurso, element.nombrecurso,element.fechafinalizacion, element.fechavencimiento, '-', 'NA', 'NA']);
//   //   }
//   // });
// }



//   const response = await pool.query('SELECT * FROM historicocert');
//   console.log(userCertificates.length);
//   let repetidos = [];
//   userCertificates.forEach( elementLms => {
//     response.rows.forEach( elementBd => {
//       let fixFechaFinalizacionBD = moment(elementBd.fechafinalizacion).format().split("T");
//       let fixFechaVencimientoBD = moment(elementBd.fechavencimiento).format().split("T");
//       let fixFechaFinalizacionLMS = elementLms.fechafinalizacion.split("T");
//       let fixFechaVencimientoLMS = elementLms.fechavencimiento.split("T");
//       if (elementBd.nombrecompleto === elementLms.nombrecompleto && elementBd.puestotrabajo === elementLms.puestotrabajo && elementBd.rut === elementLms.rut && elementBd.idcurso == elementLms.idcurso && fixFechaFinalizacionBD[0] === fixFechaFinalizacionLMS[0] && fixFechaVencimientoBD[0] === fixFechaVencimientoLMS[0] ) {
//         repetidos.push(elementBd);
//       } 
//     });
//   });


//   // repetidos.forEach(async (element) => {
//   //   await pool.query('INSERT INTO historicocert2 (rut, nombrecompleto, correopersonal, puestotrabajo, nombreempresa, rutempresa, idcurso, nombrecurso, fechainscripcion, fechafinalizacion, fechavencimiento) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [element.rut, element.nombrecompleto, !!element.correopersonal ? element.correopersonal : 'NA', !!element.puestotrabajo ? element.puestotrabajo : 'NA', element.nombreempresa, element.rutempresa, element.idcurso, element.nombrecurso, !!element.fechainscripcion ? element.fechainscripcion : '-', element.fechafinalizacion, element.fechavencimiento]);
//   // });
  
  

//   // console.log(repetidos.length);

//   userCertificates.forEach( async (elementLms, index) => {
//     response.rows.forEach( async (elementBd) => {
//       let fixFechaFinalizacionBD = moment(elementBd.fechafinalizacion).format().split("T");
//       let fixFechaVencimientoBD = moment(elementBd.fechavencimiento).format().split("T");
//       let fixFechaFinalizacionLMS = elementLms.fechafinalizacion.split("T");
//       let fixFechaVencimientoLMS = elementLms.fechavencimiento.split("T");
//       if (elementBd.nombrecompleto === elementLms.nombrecompleto && elementBd.puestotrabajo === elementLms.puestotrabajo && elementBd.rut === elementLms.rut && elementBd.idcurso == elementLms.idcurso && fixFechaFinalizacionBD[0] === fixFechaFinalizacionLMS[0] && fixFechaVencimientoBD[0] === fixFechaVencimientoLMS[0] ) {
//         userCertificates[index] = elementBd;
//       } 
//     });
//   });


//   userCertificates.forEach(async (element) => {
//     if(element.rutempresa === undefined) {
//       //aca se ingresan a la bd en formato LMS 
//       await pool.query('INSERT INTO historicocert2 (rut, nombrecompleto, correopersonal, puestotrabajo, idcurso, nombrecurso, fechafinalizacion, fechavencimiento, fechainscripcion, nombreempresa, rutempresa) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [!!element.rut ? element.rut : "NA", !!element.nombrecompleto ? element.nombrecompleto : "NA", !!element.correopersonal ? element.correopersonal : "NA",!!element.puestotrabajo ? element.puestotrabajo : "NA", element.idcurso, element.nombrecurso,element.fechafinalizacion, element.fechavencimiento, '-', 'NA', 'NA']);
//     } else {
//       await pool.query('INSERT INTO historicocert2 (rut, nombrecompleto, correopersonal, puestotrabajo, nombreempresa, rutempresa, idcurso, nombrecurso, fechainscripcion, fechafinalizacion, fechavencimiento) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [!!element.rut ? element.rut : "NA", !!element.nombrecompleto ? element.nombrecompleto : "NA", !!element.correopersonal ? element.correopersonal : "NA", !!element.puestotrabajo ? element.puestotrabajo : "NA", !!element.nombreempresa ? element.nombreempresa : "NA", !!element.rutempresa ? element.rutempresa : "NA", element.idcurso, element.nombrecurso, !!element.fechainscripcion ? element.fechainscripcion : "-", element.fechafinalizacion, element.fechavencimiento]);
//     }
//   });

//   console.log(userCertificates.length);
  
  //a este punto, userCertificates tiene los usuarios que no estan en la base de datos historica 2
  //procedo a ver si los datos de esos usuarios estan en la bdglobal y le cambio los datos segun los de la bd global

  
// }
// lms();


/**
 * 
 * HISTORICOS
 * {
    rut: '11935117-0',
    nombrecompleto: 'FLORIDOR OGALDE CORTES',
    correopersonal: 'melisa.toro@gruasimaq.cl',
    puestotrabajo: 'OPERADOR',
    nombreempresa: 'Servicio Integral de Maquinarias Ltda',
    rutempresa: '77.434.790-9',
    idcurso: 'cc49457f-da5d-40c4-8e06-271f7bed6819',
    nombrecurso: 'Inducción Persona Nueva - TECK Carmen de Andacollo',
    fechainscripcion: 2021-09-22T03:00:46.000Z,
    fechafinalizacion: 2021-10-06T03:00:46.000Z,
    fechavencimiento: 2024-10-05T03:00:46.000Z
  },

  LMS
  {
    nombrecompleto: 'JUAN RAMON GONZALEZ LEÓN',
    rut: '12806848-1',
    puestotrabajo: 'CONDUCTOR DE SERVICIOS',
    idcurso: 'cc49457f-da5d-40c4-8e06-271f7bed6819',
    correopersonal: 'juan.gonzalez01@ndcpersso.onmicrosoft.com',
    fechafinalizacion: '2021-09-23T11:31:07.46Z',
    fechavencimiento: '2024-09-22T11:31:07.46Z',
    nombrecurso: 'Inducción de Persona Nueva - TECK Carmen de Andacollo'
  }
 */


// const result = excelToJson({
//   sourceFile: 'teste.xlsx'
// });


// result['Hoja1'].forEach(async (element, index) => {
//   if (index !== 0) {
//     await pool.query('INSERT INTO historicocert (rut, nombrecompleto, correopersonal, puestotrabajo, nombreempresa, rutempresa, idcurso, nombrecurso, fechainscripcion, fechafinalizacion, fechavencimiento) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [element['A'], element['B'], element['C'], element['D'], element['E'], element['F'], element['G'], element['H'], element['I'], element['J'], element['K']]);
//   }
// });
module.exports = pool;