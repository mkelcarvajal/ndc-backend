const pool = require('../database/configpg');
const { generateCertificate } = require('../helpers/utils');

const createUserRepositoryMicrosoftGlobal = async (req) => {
    const id = req.body.id;
    const nombreempresa = req.body.nombreempresa;
    const nombrecompleto = req.body.nombrecompleto;
    const primernombre = req.body.primernombre;
    const puestotrabajo = req.body.puestotrabajo;
    const correopersonal = req.body.correopersonal;
    const telefonopersonal = req.body.telefonopersonal;
    const rut = req.body.rut;
    const rutempresa = req.body.rutempresa;
    const apellidos = req.body.apellidos;
    const correondc = req.body.correondc;
    try {
        const resp = await pool.query('INSERT INTO users (id, rut, nombreempresa, rutempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc, rol, fecha_creacion) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)', [id, rut, nombreempresa, rutempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc, 'USER_ROLE', new Date()]);
        return {
            message: 'Usuario agregado',
            body: {
                user: { id, rut, rutempresa, nombreempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc }
            }
        }
    } catch (error) {
        return {
            message: 'Error al crear el usuario',
            body: {
                error: error
            }
        }
    }
};

const createUserRepositoryMicrosoft = async (req) => {
    const id = req.body.id;
    const nombreempresa = '';
    const nombrecompleto = req.body.displayName;
    const primernombre = req.body.givenName;
    const puestotrabajo = req.body.jobTitle;
    const correopersonal = '';
    const telefonopersonal = '';
    const rut = '';
    const rutempresa = '';
    const apellidos = req.body.surname;
    const correondc = req.body.userPrincipalName;
    try {
        const resp = await pool.query('INSERT INTO users (id, rut, nombreempresa, rutempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc, rol, fecha_creacion) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)', [id, rut, nombreempresa, rutempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc, 'USER_ROLE', new Date()]);
        return {
            message: 'Usuario agregado',
            body: {
                user: { id, rut, rutempresa, nombreempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc }
            }
        }
    } catch (error) {
        return {
            message: 'Error al crear el usuario',
            body: {
                error: error
            }
        }
    }
};

const getUserByIdRepository = async (id) => {
    try {
        const response = await pool.query('SELECT * FROM users WHERE id = $1', [id]);
        return {
            message: 'Usuario encontrado con exito',
            body: {
                user: response.rows[0]
            }
        }
    } catch (error) {
        return 0;
    }
};

const getUserByEmailRepository = async (email) => {
    try {
        const response = await pool.query('SELECT * FROM users WHERE email = $1', [email]);
        return {
            message: 'Usuario encontrado con exito',
            body: {
                user: response.rows[0]
            }
        }
    } catch (error) {
        return 0;
    }
};

const generateUserCertificateRepository = async (req) => {
    await generateCertificate(req);
};

const getAllHistoricRepository = async () => {
    try {
        const response = await pool.query('SELECT * FROM historicocert');
        return {
            message: 'Lista entregada con exito',
            body: response.rows
        }
    } catch (error) {
        return 0;
    }
};

const getUserByData = async (data) => {
    try {
        const response = await pool.query('SELECT * FROM historicocert WHERE nombrecompleto = $1 AND rut = $2 AND puestotrabajo = $3 AND idcurso = $4 ', [data.nombrecompleto, data.rut, data.puestotrabajo, data.idcurso]);
        console.log(data);
        console.log(response.rows);
        return {
            user: response.rows[0]
        }
    } catch (error) {
        return 0;
    }
};

module.exports = {
    createUserRepositoryMicrosoft,
    createUserRepositoryMicrosoftGlobal,
    getUserByIdRepository,
    getUserByEmailRepository,
    generateUserCertificateRepository,
    getAllHistoricRepository,
    getUserByData
};