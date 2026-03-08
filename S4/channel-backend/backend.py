from datetime import datetime, timedelta

from flask import Flask, request, jsonify
from flask_cors import CORS

from db import get_connection

app = Flask(__name__)
CORS(app)

def date_range(start_date: datetime, end_date: datetime):

    current = start_date
    while current <= end_date:
        yield current
        current += timedelta(days=1)

@app.route("/api/login", methods=["POST"])
def login():

    data = request.get_json(silent=True) or {}
    email = data.get("email")
    password = data.get("password")

    if not email or not password:
        return jsonify({"ok": False, "error": "Falten camps"}), 400

    conn = get_connection()
    cur = conn.cursor(dictionary=True)

    cur.execute("SELECT * FROM USUARI WHERE email = %s", (email,))
    usuari = cur.fetchone()

    cur.close()
    conn.close()

    if not usuari:
        return jsonify({"ok": False, "error": "Credencials incorrectes"}), 401

    stored_pass = usuari["password_hash"]

    # Aquí simplement comparam la contrasenya, no feim servir hashing
    if stored_pass != password:
        return jsonify({"ok": False, "error": "Credencials incorrectes"}), 401

    return jsonify({
        "ok": True,
        "usuari": {
            "codiUsuari": usuari["codiUsuari"],
            "nomUsuari": usuari["nomUsuari"],
            "email": usuari["email"],
        }
    })

@app.route("/api/hotels", methods=["GET"])
def get_hotels_for_user():

    codi_usuari = request.args.get("codiUsuari", type=int)
    if not codi_usuari:
        return jsonify({"ok": False, "error": "codiUsuari requerit"}), 400

    conn = get_connection()
    cur = conn.cursor(dictionary=True)

    cur.execute("""
        SELECT H.codiHotel, H.nomHotel, H.ciutatHotel
        FROM HOTEL H
        JOIN USUARI_HOTEL UH ON UH.codiHotel = H.codiHotel
        WHERE UH.codiUsuari = %s
        ORDER BY H.codiHotel
    """, (codi_usuari,))
    hotels = cur.fetchall()

    cur.close()
    conn.close()

    return jsonify({"ok": True, "hotels": hotels})

@app.route("/api/disponibilitat", methods=["GET"])
def get_disponibilitat():

    codi_hotel = request.args.get("codiHotel", type=int)
    data_inici_str = request.args.get("dataInici")
    data_fi_str = request.args.get("dataFi")

    if not codi_hotel or not data_inici_str or not data_fi_str:
        return jsonify({"ok": False, "error": "Paràmetres requerits"}), 400

    try:
        data_inici = datetime.strptime(data_inici_str, "%Y-%m-%d").date()
        data_fi = datetime.strptime(data_fi_str, "%Y-%m-%d").date()
    except ValueError:
        return jsonify({"ok": False, "error": "Format de data incorrecte (YYYY-MM-DD)"}), 400

    if data_inici > data_fi:
        return jsonify({"ok": False, "error": "dataInici > dataFi"}), 400

    conn = get_connection()
    cur = conn.cursor(dictionary=True)

    cur.execute("""
        SELECT codiTipusHabitacio, denominacio, pax
        FROM TIPUS_HABITACIO
        ORDER BY codiTipusHabitacio
    """)
    tipus = cur.fetchall()

    cur.execute("""
        SELECT *
        FROM DISPONIBILITAT
        WHERE codiHotel = %s
          AND data BETWEEN %s AND %s
    """, (codi_hotel, data_inici, data_fi))
    rows = cur.fetchall()

    cur.close()
    conn.close()

    map_disp = {}
    for r in rows:
        key = (r["codiTipusHabitacio"], r["data"].isoformat())
        map_disp[key] = r

    dies = [d.isoformat() for d in date_range(data_inici, data_fi)]
    valors = {}

    for t in tipus:
        codi_tipus = t["codiTipusHabitacio"]
        valors[codi_tipus] = {}
        for d in dies:
            r = map_disp.get((codi_tipus, d))
            if r:
                valors[codi_tipus][d] = {
                    "cupo": r["cupo"],
                    "preu": float(r["preu"]),
                    "actiu": bool(r["actiu"]),
                }
            else:
                valors[codi_tipus][d] = {
                    "cupo": 0,
                    "preu": 0.0,
                    "actiu": False,
                }

    return jsonify({
        "ok": True,
        "dies": dies,
        "tipusHabitacio": tipus,
        "valors": valors
    })

@app.route("/api/disponibilitat", methods=["POST"])
def save_disponibilitat():

    data = request.get_json(silent=True) or {}
    codi_hotel = data.get("codiHotel")
    canvis = data.get("canvis", [])

    if not codi_hotel or not isinstance(canvis, list) or not canvis:
        return jsonify({"ok": False, "error": "Dades incorrectes"}), 400

    conn = get_connection()
    cur = conn.cursor()

    for item in canvis:
        codi_tipus = item.get("codiTipusHabitacio")
        data_str = item.get("data")
        cupo = item.get("cupo")
        preu = item.get("preu")
        actiu = item.get("actiu", True)

        if not codi_tipus or not data_str:
            continue  # si falta informació, no processam aquesta entrada

        try:
            data_sql = datetime.strptime(data_str, "%Y-%m-%d").date()
        except ValueError:
            continue

        cur.execute("""
            INSERT INTO DISPONIBILITAT
              (codiHotel, codiTipusHabitacio, data, cupo, preu, actiu)
            VALUES (%s, %s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE
              cupo = VALUES(cupo),
              preu = VALUES(preu),
              actiu = VALUES(actiu)
        """, (
            codi_hotel,
            codi_tipus,
            data_sql,
            cupo,
            preu,
            1 if actiu else 0
        ))

    conn.commit()
    cur.close()
    conn.close()

    return jsonify({"ok": True})

if __name__ == "__main__":
    app.run(debug=True, port=5000)