const pool = require('../database/configpg');

const userExist = async (id) => {
    const response = await pool.query('SELECT * FROM users WHERE id = $1', [id]);
    if (!response.rows) {
        throw new Error(`El usuario no existe`);
    }
};


const emailExist = async (email) => {
    const response = await pool.query('SELECT * FROM users WHERE email = $1', [email]);
    if (!response.rows) {
        throw new Error(`El usuario no existe`);
    }
};

module.exports = {
    emailExist,
    userExist
}
