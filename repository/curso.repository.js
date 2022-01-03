const pool = require('../database/configpg');
const { generateCertificate } = require('../helpers/utils');

const crearCursoRepository = async (req, res) => {
    const {
        nombre_curso,
        duracion,
        codigo_sence,
        lugarExamen,
        fecha_inicio,
        hora_inicio,
        fecha_fin,
        hora_fin,
        facilitador,
        organismo_ejecutador,
        oc_numero,
        empresa,
        rut_empresa
    } = req.body;

    try {

        await pool.query('INSERT INTO cursos_manual (nombre_curso, duracion, codigo_sence, lugarexamen, fecha_inicio, hora_inicio, fecha_fin, hora_fin, facilitador, organismo_ejecutador, oc_numero, empresa, rut_empresa) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)', [nombre_curso, duracion, codigo_sence, lugarExamen, fecha_inicio, hora_inicio, fecha_fin, hora_fin, facilitador, organismo_ejecutador, oc_numero, empresa, rut_empresa]);
        return {
            message: 'Curso agregado',
            body: {
                curso: { nombre_curso, duracion, codigo_sence, lugarExamen, fecha_inicio, hora_inicio, fecha_fin, hora_fin, facilitador, organismo_ejecutador, oc_numero, empresa, rut_empresa }
            }
        }
    } catch (error) {
        return {
            message: 'Error al crear el curso',
            body: {
                error: error
            }
        }
    }
}

const updateCursoRepository = async (id, data) => {
    const {
        nombre_curso,
        duracion,
        codigo_sence,
        lugarExamen,
        fecha_inicio,
        hora_inicio,
        fecha_fin,
        hora_fin,
        facilitador,
        organismo_ejecutador,
        oc_numero,
        empresa,
        rut_empresa
    } = data;
    try {

        await pool.query('UPDATE cursos_manual SET nombre_curso = $1, duracion = $2, codigo_sence = $3, lugarExamen = $4, fecha_inicio = $5, hora_inicio = $6, fecha_fin = $7, hora_fin = $8, facilitador = $9, organismo_ejecutador = $10, oc_numero = $11, empresa = $12, rut_empresa = $13 WHERE id = $14', [nombre_curso, duracion, codigo_sence, lugarExamen, fecha_inicio, hora_inicio, fecha_fin, hora_fin, facilitador, organismo_ejecutador, oc_numero, empresa, rut_empresa, id]);
        return {
            message: 'Curso actualizado',
            body: {
                curso: { nombre_curso, duracion, codigo_sence, lugarExamen, fecha_inicio, hora_inicio, fecha_fin, hora_fin, facilitador, organismo_ejecutador, oc_numero, empresa, rut_empresa }
            }
        }
    } catch (error) {
        return {
            message: 'Error al crear el curso',
            body: {
                error: error
            }
        }
    }
}

const asignarAlumnoRepository = async (req, res) => {
    const {
        id_user,
        id_cursomanual,
        asistencia,
        eva_1,
        eva_2,
        eva_final,
        calificacion
    } = req.body;

    try {

        await pool.query('INSERT INTO cursomanual_x_user (id_user, id_cursomanual, asistencia, eva_1, eva_2, eva_final, calificacion) VALUES ($1, $2, $3, $4, $5, $6, $7)', [id_user, id_cursomanual, asistencia, eva_1, eva_2, eva_final, calificacion]);
        return {
            message: 'Curso agregado',
            body: {
                curso_manual: { id_user, id_cursomanual, asistencia, eva_1, eva_2, eva_final, calificacion }
            }
        }
    } catch (error) {
        return {
            message: 'Error al crear el curso',
            body: {
                error: error
            }
        }
    }
}

const getCursosRepository = async () => {
    try {
        const response = await pool.query('SELECT * FROM cursos_manual');
        return response.rows;
    } catch (error) {
        return 0;
    }
}

const getCursosExternosRepository = async () => {
    try {
        const response = await pool.query('SELECT * FROM cursos_externos');
        return response.rows;
    } catch (error) {
        return 0;
    }
}

const getCursoByIdRepository = async (id) => {
    try {
        const response = await pool.query('SELECT * FROM cursos_manual WHERE id = $1', [id]);
        return response.rows[0];
    } catch (error) {
        return 0;
    }
}

const getCursoByIdRepository2 = async (id) => {
    try {
        const response = await pool.query('SELECT * FROM cursos_manual WHERE id = $1', [id]);
        return response.rows[0];
    } catch (error) {
        return 0;
    }
}

const getAsignadoByIdRepository = async (id) => {
    try {
        const response = await pool.query('SELECT * FROM cursomanual_x_user WHERE id_cursomanual = $1', [id]);
        return response.rows;
    } catch (error) {
        return 0;
    }
}

const crearCursoExternosRepository = async (req, res) => {
    const {
        id: id,
        duracion: duracion,
        vigencia: vigencia,
        nombre_curso: nombre_curso,
        empresa: empresa,
        clave: clave
    } = req.body;
    try {
        await pool.query('INSERT INTO cursos_externos (vigencia, duracion, nombre_curso, empresa, clave, id)  VALUES ($1, $2, $3, $4, $5, $6)', [vigencia, duracion, nombre_curso, empresa, clave.toString(), id]);
        return {
            message: 'Curso agregado',
            body: {
                curso: { duracion, vigencia, nombre_curso, empresa, clave }
            }
        }
    } catch (error) {
        return {
            message: 'Error al crear el curso',
            body: {
                error
            }
        }
    }
}

const patchCursoByIdRepository = async (id, data) => {
    console.log(data);
    try {
        const response = await pool.query('UPDATE cursos_externos SET vigencia = $1, duracion = $2, empresa = $3, clave = $4, nombre_curso = $5 WHERE id = $6', [data.vigencia, data.duracion, data.empresa, data.clave, data.nombre_curso, id]);
        return {
            message: 'Curso actualizado con exito',
            user: response.rows[0]
        }
    } catch (error) {
        return 0;
    }
};

const getCursoByClaveRepository = async (id) => {
    try {
        const response = await pool.query('SELECT * FROM cursos_externos WHERE clave = $1', [id]);
        return response.rows;
    } catch (error) {
        return 0;
    }
}

const patchCalificacionByIdRepository = async (id_user, id_cursomanual, data) => {
    try {
        const response = await pool.query('UPDATE cursomanual_x_user SET asistencia = $1, eva_1 = $2, eva_2 = $3, eva_final = $4, calificacion = $5 WHERE id_user = $6 AND id_cursomanual = $7', [data.asistencia, data.eva_1, data.eva_2, data.eva_final, data.calificacion, id_user, id_cursomanual]);
        return {
            message: 'Curso actualizado con exito',
            user: response.rows[0]
        }
    } catch (error) {
        return 0;
    }
};

const deleteRepository = async (id_user, id_cursomanual) => {
    try {
        await pool.query('delete from cursomanual_x_user WHERE id_user = $1 AND id_cursomanual = $2', [id_user, id_cursomanual]);
        return {
            message: 'Curso eliminado con exito',
            curso: true
        }
    } catch (error) {
        return 0;
    }
};

module.exports = {
    crearCursoRepository,
    getCursosRepository,
    getCursoByIdRepository,
    asignarAlumnoRepository,
    getAsignadoByIdRepository,
    getCursosExternosRepository,
    crearCursoExternosRepository,
    patchCursoByIdRepository,
    getCursoByClaveRepository,
    getCursoByIdRepository2,
    patchCalificacionByIdRepository,
    updateCursoRepository,
    deleteRepository
};