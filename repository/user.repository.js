const pool = require('../database/configpg');

const createUserRepository = async (req) => {
    const id = req.body.id;
    const businessPhones = req.body.businessPhones;
    const displayName = req.body.displayName;
    const givenName = req.body.givenName;
    const jobTitle = req.body.jobTitle;
    const mail = req.body.mail;
    const mobilePhone = req.body.mobilePhone;
    const officeLocation = req.body.officeLocation;
    const preferredLanguage = req.body.preferredLanguage;
    const surname = req.body.surname;
    const userPrincipalName = req.body.userPrincipalName;

    try {
        const resp = await pool.query('INSERT INTO users (id, businessPhones, displayName, givenName, jobTitle, mail, mobilePhone, officeLocation, preferredLanguage, surname, userPrincipalName, rol, created_on) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)', [id, businessPhones, displayName, givenName, jobTitle, mail, mobilePhone, officeLocation, preferredLanguage, surname, userPrincipalName, 'USER_ROLE', new Date()]);
        return {
            message: 'Usuario agregado',
            body: {
                user: {id, businessPhones, displayName, givenName, jobTitle, mail, mobilePhone, officeLocation, preferredLanguage, surname, userPrincipalName}
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

module.exports = {
    createUserRepository,
    getUserByIdRepository,
    getUserByEmailRepository
};