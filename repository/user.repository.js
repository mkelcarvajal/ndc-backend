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
    const giro = req.body.giro;
    const sap = req.body.sap;
    const cliente = req.body.cliente;
    try {
        const resp = await pool.query('INSERT INTO users (id, rut, nombreempresa, rutempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc, rol, fecha_creacion, cliente, giro, sap) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16)', [id, rut, nombreempresa, rutempresa, nombrecompleto, primernombre, puestotrabajo, correopersonal, telefonopersonal, apellidos, correondc, 'USER_ROLE', new Date(), cliente, giro, sap]);
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

const getAllUsersRepository = async () => {
    try {
        const response = await pool.query('SELECT * FROM users ORDER BY fecha_creacion DESC;');
        return {
            message: 'Usuarios encontrados con exito',
            body: {
                user: response.rows
            }
        }
    } catch (error) {
        return 0;
    }
};

const getUserByRutRepository = async (id) => {
    try {
        const response = await pool.query('SELECT * FROM users WHERE rut = $1', [id]);
        if (response.rows.length > 0) {
            return {
                message: 'Usuario encontrado con exito',
                body: {
                    user: response.rows[0]
                }
            }
        } else {
            return {
                message: 'Usuario no encontrado',
                body: {
                    user: 0
                }
            }
        }
    } catch (error) {
        return 0;
    }
};

const patchUserByIdRepository = async (id, data) => {
    try {
        const response = await pool.query('UPDATE users SET nombrecompleto = $1, rut = $2, rutempresa = $3, nombreempresa = $4, correopersonal = $5,  telefonopersonal = $6, puestotrabajo = $7, primernombre = $8, apellidos = $9, sap = $10, giro = $11, cliente = $12 WHERE id_serial = $13', [data.nombre + " " + data.apellidos, data.rut, data.rutempresa, data.nombreempresa, data.correopersonal, data.telefonopersonal, data.puestotrabajo, data.nombre, data.apellidos, data.sap, data.giro, data.cliente, parseInt(id)]);
        return {
            message: 'Usuario actualizado con exito',
            user: response.rows[0]
        }
    } catch (error) {
        return 0;
    }
};

const patchCursoByIdRepository = async (id, data) => {
    try {
        const response = await pool.query('UPDATE cursos SET vigencia = $1, duracion = $2, empresa = $3 WHERE id = $4', [data.vigencia, data.duracion, data.empresa, id]);
        return {
            message: 'Curso actualizado con exito',
            user: response.rows[0]
        }
    } catch (error) {
        return 0;
    }
};

const patchHistoricByIdRepository = async (id, data) => {
    try {
        const response = await pool.query('UPDATE historicocert SET nombrecompleto = $1, rut = $2, rutempresa = $3, nombreempresa = $4, correopersonal = $5 WHERE id = $6', [data.nombrecompleto, data.rut, data.rutempresa, data.nombreempresa, data.correopersonal, id]);
        return {
            message: 'Usuario actualizado con exito',
            user: response.rows[0]
        }
    } catch (error) {
        return 0;
    }
};

const getUserByEmailRepository = async (email) => {
    try {
        const response = await pool.query('SELECT * FROM users WHERE correondc = $1', [email]);
        if (response.rows.length > 0) {
            return {
                message: 'Usuario encontrado con exito',
                body: {
                    user: response.rows[0]
                }
            }
        } else {
            return {
                message: 'Usuario no encontrado',
                body: {
                    user: 0
                }
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
        const response = await pool.query('select * from public.historicocert h where h.fechavencimiento::date >= CURRENT_DATE order by rutempresa;');
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
        const response = await pool.query('SELECT * FROM historicocert WHERE fechavencimiento::date >= CURRENT_DATE AND rut = $1 AND puestotrabajo = $2 AND idcurso = $3 ', [data.rut, data.puestotrabajo, data.idcurso]);
        return {
            user: response.rows[0]
        }
    } catch (error) {
        return 0;
    }
};

const getCursoByIdRepository = async (id) => {
    try {
        const response = await pool.query('SELECT * FROM cursos WHERE id = $1', [id]);
        return response.rows[0];
    } catch (error) {
        return 0;
    }
}

const getCursosByRepository = async () => {
    try {
        const response = await pool.query('SELECT * FROM cursos ORDER BY nombre_curso ASC;');
        return response.rows;
    } catch (error) {
        return 0;
    }
}

const createCursoRepository = async (req) => {
    const id = req.body.id;
    const vigencia = req.body.vigencia;
    const nombre_curso = req.body.nombre_curso;
    const empresa = req.body.empresa;
    try {
        await pool.query('INSERT INTO cursos (id, vigencia, nombre_curso, empresa) VALUES ($1, $2, $3, $4)', [id, vigencia, nombre_curso, empresa]);
        return {
            message: 'Curso agregado',
            body: {
                curso: { id, vigencia, nombre_curso}
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
};


module.exports = {
    createUserRepositoryMicrosoft,
    createUserRepositoryMicrosoftGlobal,
    getUserByIdRepository,
    getUserByEmailRepository,
    generateUserCertificateRepository,
    getAllHistoricRepository,
    getUserByData,
    patchHistoricByIdRepository,
    getUserByRutRepository,
    getAllUsersRepository,
    patchUserByIdRepository,
    getCursoByIdRepository,
    getCursosByRepository,
    createCursoRepository,
    patchCursoByIdRepository,
};