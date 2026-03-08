// =======================================================
//  WEBSERVICE PMS45609588J
// =======================================================

const mysql = require('mysql2');
const http = require('http');
const url = require('url');

// =======================================================
// 1. Connexió BD
// =======================================================
const conn = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'PMS45609588'
});

conn.connect(err => {
    if (err) console.log("Error connectant a la BD");
    else console.log("Connexió OK amb PMS45609588");
});

// Funció utilitària per respondre JSON
function respond(res, obj) {
    res.setHeader('Content-Type', 'application/json');
    res.end(JSON.stringify(obj));
}

// =======================================================
// 2. Consultar disponibilitat
// =======================================================
function consultarDisponibilitat(req, res) {
    const { idHotel, tipus, entrada, sortida } = url.parse(req.url, true).query;

    if (!idHotel || !tipus || !entrada || !sortida)
        return respond(res, { idresp: "R1NOK", motiu: "Falten paràmetres" });

    const sql = `
        SELECT h.numeroHabitacio, t.denominacio, t.pax, t.llits
        FROM HABITACIO h
        JOIN TIPUS_HABITACIO t ON h.codiTipusHabitacio = t.codiTipusHabitacio
        WHERE h.codiHotel = ? AND h.codiTipusHabitacio = ?
          AND h.numeroHabitacio NOT IN (
              SELECT r.numeroHabitacio
              FROM RESERVA r
              WHERE r.codiHotel = ?
                AND NOT (? >= r.dataSortida OR ? <= r.dataEntrada)
          );
    `;

    conn.query(sql, [idHotel, tipus, idHotel, entrada, sortida], (err, result) => {
        if (err) return respond(res, { error: "Error BD", detall: err.message });

        respond(res, {
            idresp: "R1OK",
            idHotel,
            tipus,
            disponibles: result.length,
            habitacions: result.map(h => ({
                numeroHabitacio: h.numeroHabitacio,
                denominacio: h.denominacio,
                pax: h.pax,
                llits: h.llits
            }))
        });
    });
}

// =======================================================
// 3. Crear reserva
// =======================================================
function ferReserva(req, res) {
    const { codiClient, codiHotel, numeroHabitacio, regim, entrada, sortida } = url.parse(req.url, true).query;

    if (!codiClient || !codiHotel || !numeroHabitacio || !regim || !entrada || !sortida)
        return respond(res, { idresp: "R2NOK", motiu: "Falten paràmetres" });

    const sqlDisponibilitat = `
        SELECT 1
        FROM RESERVA r
        WHERE r.codiHotel = ?
          AND r.numeroHabitacio = ?
          AND r.dataEntrada < ?
          AND r.dataSortida > ?
        LIMIT 1;
    `;

    const sqlInsert = `
        INSERT INTO RESERVA (dataReserva, dataEntrada, dataSortida, estatReserva,
                             codiClient, numeroHabitacio, codiRegim, codiHotel)
        VALUES (CURDATE(), ?, ?, 'Pendent', ?, ?, ?, ?);
    `;

    conn.query(sqlDisponibilitat, [codiHotel, numeroHabitacio, sortida, entrada], (err, rows) => {
        if (err) return respond(res, { error: "Error BD", detall: err.message });

        if (rows && rows.length > 0) {
            return respond(res, { idresp: "R2NOK", motiu: "No disponible: solapament de dates" });
        }

        conn.query(sqlInsert, [entrada, sortida, codiClient, numeroHabitacio, regim, codiHotel], (err2, result) => {
            if (err2) return respond(res, { error: "Error BD", detall: err2.message });

            respond(res, {
                idresp: "R2OK",
                reserva: {
                    codiReserva: result.insertId,
                    codiClient,
                    codiHotel,
                    numeroHabitacio,
                    regim,
                    entrada,
                    sortida,
                    estatReserva: "Pendent"
                }
            });
        });
    });
}

// =======================================================
// 4. Crear client
// =======================================================
function crearUsuari(req, res) {
    const { nom, llinatges, email, telefon, dni, nacionalitat, dataNaixement } =
        url.parse(req.url, true).query;

    if (!nom || !email)
        return respond(res, { idresp: "R3NOK", motiu: "Falten camps obligatoris" });

    const sql = `
        INSERT INTO CLIENT (codiClient, nomClient, llinatges, emailClient, telefonClient,
                            dni, nacionalitat, dataNaixement, genere, vip)
        VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 'No definit', 0);
    `;

    conn.query(sql, [nom, llinatges, email, telefon, dni, nacionalitat, dataNaixement], err => {
        if (err) return respond(res, { idresp: "R3NOK", motiu: "Error BD", detall: err.message });

        respond(res, {
            idresp: "R3OK",
            client: { nom, llinatges, email, telefon, dni, nacionalitat, dataNaixement }
        });
    });
}

// =======================================================
// 5. Consultar reserves d’un client
// =======================================================
function consultarReserva(req, res) {
    const { codiClient } = url.parse(req.url, true).query;

    if (!codiClient)
        return respond(res, { idresp: "R4NOK", motiu: "Falta codiClient" });

    const sql = `
        SELECT r.codiReserva, r.dataEntrada, r.dataSortida, r.estatReserva,
               h.nomHotel, t.denominacio AS tipusHabitacio, re.descripcioRegim
        FROM RESERVA r
        JOIN HABITACIO ha ON r.codiHotel = ha.codiHotel AND r.numeroHabitacio = ha.numeroHabitacio
        JOIN HOTEL h ON ha.codiHotel = h.codiHotel
        JOIN TIPUS_HABITACIO t ON ha.codiTipusHabitacio = t.codiTipusHabitacio
        JOIN REGIM re ON r.codiRegim = re.codiRegim
        WHERE r.codiClient = ?;
    `;

    conn.query(sql, [codiClient], (err, result) => {
        if (err) return respond(res, { error: "Error BD" });

        respond(res, {
            idresp: "R4OK",
            codiClient,
            reserves: result
        });
    });
}

// =======================================================
// 6. Routing
// =======================================================
const servidor = http.createServer((req, res) => {
    const f = url.parse(req.url, true).query.func;

    if (f == 1) consultarDisponibilitat(req, res);
    else if (f == 2) ferReserva(req, res);
    else if (f == 3) crearUsuari(req, res);
    else if (f == 4) consultarReserva(req, res);
    else respond(res, { error: "Funció no reconeguda" });
});

servidor.listen(3000, () => console.log("Webservice actiu a http://localhost:3000"));