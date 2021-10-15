const { Pool } = require("pg");

const pool = new Pool({
  connectionString: "postgres://busqueda:busqueda123@localhost:5432/ndc"
});

module.exports = pool;