// backend/db.js
const mysql = require("mysql2");

const conexion = mysql.createConnection({
    host: "localhost",
    port: 3306,
    user: "root",
    password: "root1234", 
    database: "tienda_la13"
});

conexion.connect(err => {
    if (err) {
        console.error("Error conectando a MySQL:", err);
        return;
    }
    console.log("Conexión MySQL establecida.");
});

module.exports = conexion;
