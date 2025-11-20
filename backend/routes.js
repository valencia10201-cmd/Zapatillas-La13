const express = require("express");
const router = express.Router();
const db = require("./db");

// CRUD genérico
function crearCRUD(tabla) {
    
    // Listar
    router.get(`/${tabla}`, (req, res) => {
        db.query(`SELECT * FROM ${tabla}`, (err, data) => {
            if (err) return res.status(500).send(err);
            res.json(data);
        });
    });

    // Obtener uno
    router.get(`/${tabla}/:id`, (req, res) => {
        const id = req.params.id;
        db.query(`SELECT * FROM ${tabla} WHERE id = ?`, [id], (err, data) => {
            if (err) return res.status(500).send(err);
            res.json(data[0]);
        });
    });

    // Crear
    router.post(`/${tabla}`, (req, res) => {
        db.query(`INSERT INTO ${tabla} SET ?`, req.body, (err, result) => {
            if (err) return res.status(500).send(err);
            res.json({ id: result.insertId, ...req.body });
        });
    });

    // Editar
    router.put(`/${tabla}/:id`, (req, res) => {
        const id = req.params.id;
        db.query(`UPDATE ${tabla} SET ? WHERE id = ?`, [req.body, id], err => {
            if (err) return res.status(500).send(err);
            res.send("Actualizado");
        });
    });

    // Eliminar
    router.delete(`/${tabla}/:id`, (req, res) => {
        const id = req.params.id;
        db.query(`DELETE FROM ${tabla} WHERE id = ?`, [id], err => {
            if (err) return res.status(500).send(err);
            res.send("Eliminado");
        });
    });
}

// Las 9 tablas
[
    "categorias",
    "productos",
    "clientes",
    "usuarios",
    "ventas",
    "detalle_venta",
    "proveedores",
    "compras",
    "detalle_compra"
].forEach(tabla => crearCRUD(tabla));

module.exports = router;
