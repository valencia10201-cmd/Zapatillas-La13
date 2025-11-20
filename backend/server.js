// backend/server.js
const express = require("express");
const cors = require("cors");
const db = require("./db");

const app = express();
app.use(cors());
app.use(express.json());

app.get("/", (req, res) => {
  res.send("Servidor Tienda La13 funcionando");
});

/* -------------------- PRODUCTOS CRUD -------------------- */

// GET all
app.get("/productos", (req, res) => {
  db.query("SELECT * FROM productos", (err, results) => {
    if (err) return res.status(500).json({ error: err });
    res.json(results);
  });
});

// POST create
app.post("/productos", (req, res) => {
  const { nombre, descripcion, precio, stock, categoria_id } = req.body;
  const sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id) VALUES (?, ?, ?, ?, ?)";
  db.query(sql, [nombre, descripcion, precio, stock, categoria_id], (err, result) => {
    if (err) return res.status(500).json({ error: err });
    res.json({ id: result.insertId, mensaje: "Producto creado" });
  });
});

// PUT update
app.put("/productos/:id", (req, res) => {
  const id = req.params.id;
  const { nombre, descripcion, precio, stock, categoria_id } = req.body;
  db.query(
    "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, categoria_id=? WHERE id=?",
    [nombre, descripcion, precio, stock, categoria_id, id],
    (err) => {
      if (err) return res.status(500).json({ error: err });
      res.json({ mensaje: "Producto actualizado" });
    }
  );
});

// DELETE
app.delete("/productos/:id", (req, res) => {
  const id = req.params.id;
  db.query("DELETE FROM productos WHERE id=?", [id], (err) => {
    if (err) return res.status(500).json({ error: err });
    res.json({ mensaje: "Producto eliminado" });
  });
});

/* -------------------- CATEGORIAS CRUD -------------------- */

app.get("/categorias", (req, res) => {
  db.query("SELECT * FROM categorias", (err, results) => {
    if (err) return res.status(500).json({ error: err });
    res.json(results);
  });
});

app.post("/categorias", (req, res) => {
  const { nombre } = req.body;
  db.query("INSERT INTO categorias (nombre) VALUES (?)", [nombre], (err, result) => {
    if (err) return res.status(500).json({ error: err });
    res.json({ id: result.insertId, mensaje: "Categoria creada" });
  });
});

app.put("/categorias/:id", (req, res) => {
  const id = req.params.id;
  const { nombre } = req.body;
  db.query("UPDATE categorias SET nombre=? WHERE id=?", [nombre, id], (err) => {
    if (err) return res.status(500).json({ error: err });
    res.json({ mensaje: "Categoria actualizada" });
  });
});

app.delete("/categorias/:id", (req, res) => {
  const id = req.params.id;
  db.query("DELETE FROM categorias WHERE id=?", [id], (err) => {
    if (err) return res.status(500).json({ error: err });
    res.json({ mensaje: "Categoria eliminada" });
  });
});

/* -------------------- LOGIN SIMPLE -------------------- */
/* Nota: sin hashing. Úsalo solo en dev. */
app.post("/login", (req, res) => {
  const { usuario, clave } = req.body;
  db.query("SELECT id, usuario, rol FROM usuarios WHERE usuario=? AND clave=?", [usuario, clave], (err, results) => {
    if (err) return res.status(500).json({ error: err });
    if (results.length === 0) return res.status(401).json({ error: "Credenciales inválidas" });
    // devolver usuario mínimo (sin clave)
    res.json({ usuario: results[0] });
  });
});

/* -------------------- CONFIRMAR CARRITO -> CREAR VENTA --------------------
  Espera un body:
  {
    cliente: { nombre, correo, telefono },
    items: [ { productoId, cantidad, precio } ],
    total: 12345.67
  }
  Funcion: crea cliente si no existe (por correo), luego crea venta y detalle_venta, descuenta stock.
  Todo dentro de transacción.
*/
app.post("/carrito/confirmar", (req, res) => {
  const { cliente, items, total } = req.body;

  if (!cliente || !items || !Array.isArray(items) || items.length === 0) {
    return res.status(400).json({ error: "Payload inválido" });
  }

  db.query("START TRANSACTION", (err) => {
    if (err) return rollbackSend(err);

    // 1) buscar o crear cliente
    db.query("SELECT id FROM clientes WHERE correo = ?", [cliente.correo], (err, rows) => {
      if (err) return rollbackSend(err);

      const crearVenta = (clienteId) => {
        // 2) crear venta
        db.query("INSERT INTO ventas (cliente_id, total) VALUES (?, ?)", [clienteId, total], (err, resultVenta) => {
          if (err) return rollbackSend(err);
          const ventaId = resultVenta.insertId;

          // 3) insertar detalle_venta por cada item y descontar stock
          const tareas = items.map(it => {
            return new Promise((resolve, reject) => {
              db.query("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)", [ventaId, it.productoId, it.cantidad, it.precio], (err) => {
                if (err) return reject(err);
                // descontar stock
                db.query("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?", [it.cantidad, it.productoId, it.cantidad], (err, r2) => {
                  if (err) return reject(err);
                  if (r2.affectedRows === 0) return reject(new Error("Stock insuficiente para el producto id " + it.productoId));
                  resolve();
                });
              });
            });
          });

          Promise.all(tareas)
            .then(() => {
              db.query("COMMIT", (err) => {
                if (err) return rollbackSend(err);
                res.json({ mensaje: "Compra confirmada", ventaId });
              });
            })
            .catch(rollbackSend);
        });
      };

      if (rows.length > 0) {
        crearVenta(rows[0].id);
      } else {
        // crear cliente
        db.query("INSERT INTO clientes (nombre, correo, telefono) VALUES (?, ?, ?)", [cliente.nombre, cliente.correo, cliente.telefono], (err, resultCliente) => {
          if (err) return rollbackSend(err);
          crearVenta(resultCliente.insertId);
        });
      }
    });
  });

  function rollbackSend(err) {
    db.query("ROLLBACK", () => {
      console.error(err);
      res.status(500).json({ error: err.message || err });
    });
  }
});

/* ---------------------------------------------------- */

app.listen(3000, () => {
  console.log("Backend Tienda La13 listo en http://localhost:3000");
});
