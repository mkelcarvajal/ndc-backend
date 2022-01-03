const {request, response} = require('express');
const {encryptPassword} = require('../helpers/utils');
const { getCursosRepository, crearCursoRepository, getCursoByIdRepository, asignarAlumnoRepository, getAsignadoByIdRepository, getCursosExternosRepository, crearCursoExternosRepository, patchCursoByIdRepository, getCursoByClaveRepository, patchCalificacionByIdRepository, updateCursoRepository, deleteRepository } = require('../repository/curso.repository');
const { getUserBySerialRepository } = require('../repository/user.repository');
const PDFDocument = require("pdfkit-table");
const moment = require('moment');
const qr = require("qrcode");
const path = require('path');
const fs = require("fs");
moment.locale('es');

const getCursos = async (req = request, res = response) => {

    const cursos = await getCursosRepository();
    res.json({
        cursos
    });
}

const getCursoById = async (req = request, res = response) => {

    const id = req.params.id;
    const cursos = await getCursoByIdRepository(id);
    res.json(cursos);
}

const getAsignadoById = async (req = request, res = response) => {

    const id = req.params.id;
    const asignados = await getAsignadoByIdRepository(id);
    res.json(asignados);
}

const createCurso = async (req = request, res = response) => {
    try {

        const curso = await crearCursoRepository(req);
        res.json({
            msg: 'Curso creado con exito',
            curso
        });
    } catch (e) {
        console.log(e);
    }
}


const createCursoExternos = async (req = request, res = response) => {
    try {
        const curso = await crearCursoExternosRepository(req);
        res.json({
            msg: 'Curso creado con exito',
            curso
        });
    } catch (e) {
        console.log(e);
    }
}

const asignarAlumno = async (req = request, res = response) => {
    try {

        const curso = await asignarAlumnoRepository(req);
        res.json({
            msg: 'Alumno Asignado con exito',
            curso
        });
    } catch (e) {
        console.log(e);
    }
}

async function buildPDF(response, dataCallback, endCallback) {

    const doc = new PDFDocument({ bufferPages: true, layout: "landscape", size: "A4", });

    doc.on('data', dataCallback);
    doc.on('end', endCallback);

    const qrimage = await qr.toDataURL(`https://ndc-backend.crvsoft.cl/api/curso-manual/verificarCertificacion/${response.curso.id}/`);

    doc.image("./images/certificaciones.png", 0, 0, { width: 842 });
    
    doc.font('./fonts/calibri-bold.ttf').fontSize(13).text(`CERTIFICADO DE ASISTENCIA Y APROBACIÓN Nº ${response.curso.id} - ${moment().year()}`, {
        align: 'center',
    });
        
    const table = {
        headers: [
          { label:"", property: 'name', renderer: null },
          { label:"", property: 'name2', renderer: null },
        ],
        datas: [
            {
                name: 'bold:NOMBRE DEL CURSO:',
                name2: 'bold:' + response.curso.nombre_curso ? response.curso.nombre_curso : '-',
            },
            {
                name: 'bold:DURACION:',
                name2: 'bold:' + response.curso.duracion ? response.curso.duracion + ' HORAS' : '-',
            },
            {
                name: 'bold:CODIGO SENCE:',
                name2: 'bold:' + response.curso.codigo_sence ? response.curso.codigo_sence : '-',
            },
            {
                name: 'bold:LUGAR DE EJECUCIÓN:',
                name2: 'bold:' + response.curso.lugarexamen ? response.curso.lugarexamen : '-',
            },
            {
                name: 'bold:FECHA INICIO:',
                name2: 'bold:' + response.curso.fecha_inicio ? response.curso.fecha_inicio.split('T')[0] : '-',
            },
            {
                name: 'bold:HORA INICIO:',
                name2: 'bold:' + response.curso.hora_inicio ? response.curso.hora_inicio : '-',
            },
            {
                name: 'bold:FECHA DE TÉRMINO:',
                name2: 'bold:' + response.curso.fecha_fin ? response.curso.fecha_fin.split('T')[0] : '-',
            },
            {
                name: 'bold:HORA DE TÉRMINO:',
                name2: 'bold:' + response.curso.hora_fin ? response.curso.hora_fin : '-',
            },
            {
                name: 'bold:FACILITADOR OTEC - CESSO:',
                name2: 'bold:' + response.curso.facilitador ? response.curso.facilitador : '-',
            },
            {
                name: 'bold:ORGANISMO EJECUTOR - OTEC:',
                name2: 'bold:' + response.curso.organismo_ejecutor ? response.curso.organismo_ejecutor : '-',
            },
            {
                name: 'bold:OC No:',
                name2: 'bold:' + response.curso.oc_numero ? response.curso.oc_numero : '-',
            }
        ]
      };
    // options
    const options = {};
    // callback
    const callback = () => { };
    // the magic
    await doc.table(table, options);

    doc.font('./fonts/calibri-regular.ttf').fontSize(10).text(`Se extiende a `, {continued: true}); 
    doc.font('./fonts/calibri-bold.ttf').fontSize(10).text(`${response.curso.empresa}, ${response.curso.rut_empresa} `, {continued: true});
    doc.font('./fonts/calibri-regular.ttf').fontSize(10).text(`el presente certificado de asistencia y calificación del siguiente personal, en el curso denominado: `, {continued: true}); 
    doc.font('./fonts/calibri-bold.ttf').fontSize(10).text(`${response.curso.nombre_curso} `, {continued: true});
    doc.font('./fonts/calibri-regular.ttf').fontSize(10).text(`con fecha de inicio el `, {continued: true}); 
    doc.font('./fonts/calibri-bold.ttf').fontSize(10).text(`${moment(response.curso.fecha_inicio).format('LL')} `, {continued: true});
    doc.font('./fonts/calibri-regular.ttf').fontSize(10).text(`y de término el `, {continued: true}); 
    doc.font('./fonts/calibri-bold.ttf').fontSize(10).text(`${moment(response.curso.fecha_fin).format('LL')}`, {continued: true});
    doc.font('./fonts/calibri-regular.ttf').fontSize(10).text(`, con una duración de `, {continued: true}); 
    doc.font('./fonts/calibri-bold.ttf').fontSize(10).text(`${response.curso.duracion}`, {continued: true});
    doc.font('./fonts/calibri-regular.ttf').fontSize(10).text(` horas cronológicas. `, {continued: true}); 
    
    const arrayInscritos = []
    for(let [index, inscrito] of response.inscritos.entries()) {
        const alumno = await getUserBySerialRepository(inscrito.id_user);
        arrayInscritos.push(
            [   
                index + 1, 
                alumno.user.nombrecompleto, 
                alumno.user.rut, 
                alumno.user.sap ? alumno.user.sap : '-', 
                alumno.user.nombreempresa ? alumno.user.nombreempresa : '-', 
                inscrito.asistencia, 
                inscrito.eva_1, 
                inscrito.eva_2, 
                inscrito.eva_final, 
                inscrito.calificacion
            ]
        )
    }

      const table2 = {
        title: "----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",
        subtitle: "USUARIOS",
        headers: [
          { label:"N°", width: 20, renderer: null },
          { label:"NOMBRE COMPLETO", width: 75, renderer: null }, 
          { label:"RUT", width: 75, renderer: null }, 
          { label:"SAP", width: 75, renderer: null }, 
          { label:"ORGANIZACIÓN", width: 75, renderer: null }, 
          { label:"ASISTENCIA", width: 75, renderer: null }, 
          { label:"EVALUACIÓN 1 (%)", width: 75, renderer: null },
          { label:"EVALUACIÓN 2 (%)", width: 75, renderer: null },
          { label:"EVALUACIÓN FINAL (%)", width: 70, renderer: null },
          { label:"CALIFICACIÓN", width: 80, renderer: null },
        ],
        rows: arrayInscritos
      };
    // options
    const options2 = {
    };
    // the magic
    await doc.table(table2, options2);

    doc.moveDown();
    doc.image(qrimage, { fit: [85, 85], align: 'center', valign: 'center' });

    doc.end();
}

const generarCertificado = async (req = request, res = response) => {
    try {
        const titulo = 'Certificacion de Asistencia y Calificacion';
        const response = JSON.parse(req.query.data);
        const stream = res.writeHead(200, {
            'Content-Type': 'application/pdf',
            'Content-Disposition': `attachment;filename=${titulo}.pdf`,
        });

        await buildPDF(response ,(chunk) => stream.write(chunk), () => stream.end());

    } catch (e) {
        console.log(e);
    }

}

const verificarCertificacion = async (req = request, res = response) => {
    try {

        let curso = await getCursoByIdRepository(req.params.id);
        console.log(curso);
        const id = curso.id;
        const empresa = curso.empresa;
        const fecha_inicio = moment(new Date(curso.fecha_inicio)).format('LL');
        const fecha_termino = moment(new Date(curso.fecha_fin)).format('LL');
        const nombrecurso = curso.nombre_curso;
        fs.readFile(path.join(__dirname, '../public/verificarcertificado-curso.html'), 'utf8', function (err,data) {
            if (err) {
                return console.log(err);
            }
            const html = data.replace("{1}", id.toString());
            const test = html.replace("{2}", nombrecurso.toString());
            const test1 = test.replace("{3}", fecha_inicio.toString());
            const test2 = test1.replace("{4}", fecha_termino.toString());
            const test3 = test2.replace("{5}", empresa.toString());
            res.type('.html')
            res.status(200).send(test3);
        });
    } catch (e) {
        fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err,data) {
            if (err) {
            return console.log(err);
            }
            res.type('.html')
            res.write(data);
        });
    }
}

const getCursosExternos = async (req = request, res = response) => {

    const cursos = await getCursosExternosRepository();
    res.json({
        cursos
    });
}

const getCursoByClave = async (req = request, res = response) => {
    try {
        const id = req.params.id;
        const result = await getCursoByClaveRepository(id);

        res.json({
            result
        });

    } catch (e) {
        console.log(e);
    }
}

const patchCursoManualById = async (req = request, res = response) => {
    const id = req.params.id;
    const curso = await updateCursoRepository(id, req.body);
    res.json({
        curso
    });
}


const patchCursoById = async (req = request, res = response) => {
    const id = req.params.id;
    const curso = await patchCursoByIdRepository(id, req.body);
    res.json({
        curso
    });
}

const patchCalificacionById = async (req = request, res = response) => {
    const id_user = req.params.id_user;
    const id_curso = req.params.id_curso;
    const curso = await patchCalificacionByIdRepository(id_user, id_curso, req.body);
    res.json({
        curso
    });
}

const deleteUser = async (req = request, res = response) => {
    const id_user = req.params.id_user;
    const id_curso = req.params.id_curso;
    const curso = await deleteRepository(id_user, id_curso);
    res.json({
        curso
    });
}

module.exports = {
    getCursos,
    createCurso,
    getCursoById,
    asignarAlumno,
    getAsignadoById,
    generarCertificado,
    verificarCertificacion,
    getCursosExternos,
    createCursoExternos,
    patchCursoById,
    getCursoByClave,
    patchCalificacionById,
    patchCursoManualById,
    deleteUser
}
