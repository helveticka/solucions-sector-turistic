import tkinter as tk
from tkinter import ttk, messagebox
import datetime
import conexio

# Connexió amb la base de dades

db = conexio.get_connection()

# Funcions auxiliars de la base de dades

#
# Consulta que retorna totes les files resultants d'una SELECT
def fetch_all(query, params=None):
    cur = db.cursor(dictionary=True)
    try:
        cur.execute(query, params or ())
        return cur.fetchall()
    finally:
        cur.close()

#
# Executa una consulta que modifica dades (INSERT, UPDATE, DELETE)
def execute_query(query, params=None):
    cur = db.cursor(dictionary=True)
    try:
        cur.execute(query, params or ())
        db.commit()
    finally:
        cur.close()

# Finestra principal de l'aplicació

root = tk.Tk()
root.title("PMS Cadena Hotelera - Recepció")
root.geometry("1000x600")

# Contenidor principal de frames
frames = {}

#
# Canvia entre les diferents pantalles del PMS
def show_frame(name):
    for f in frames.values():
        f.pack_forget()
    frames[name].pack(fill="both", expand=True)

# Gestió dels clients

frame_clients = tk.Frame(root)
frames["clients"] = frame_clients

# --- Widgets Clients ---

# Llista (Treeview)
tree_clients = ttk.Treeview(frame_clients, columns=("id", "nom", "llinatges", "email", "telefon"), show="headings")
tree_clients.heading("id", text="ID")
tree_clients.heading("nom", text="Nom")
tree_clients.heading("llinatges", text="Llinatges")
tree_clients.heading("email", text="Email")
tree_clients.heading("telefon", text="Telèfon")
tree_clients.column("id", width=50)
tree_clients.grid(row=0, column=0, columnspan=4, padx=10, pady=10, sticky="nsew")

scroll_clients = ttk.Scrollbar(frame_clients, orient="vertical", command=tree_clients.yview)
tree_clients.configure(yscroll=scroll_clients.set)
scroll_clients.grid(row=0, column=4, sticky="ns")

frame_clients.rowconfigure(0, weight=1)
frame_clients.columnconfigure(0, weight=1)

# Formulari de clients
tk.Label(frame_clients, text="ID (només per modificar):").grid(row=1, column=0, sticky="e", padx=5, pady=5)
entry_client_id = tk.Entry(frame_clients, width=10)
entry_client_id.grid(row=1, column=1, sticky="w")

tk.Label(frame_clients, text="Nom:").grid(row=2, column=0, sticky="e", padx=5, pady=5)
entry_client_nom = tk.Entry(frame_clients, width=30)
entry_client_nom.grid(row=2, column=1, sticky="w")

tk.Label(frame_clients, text="Llinatges:").grid(row=3, column=0, sticky="e", padx=5, pady=5)
entry_client_llinatges = tk.Entry(frame_clients, width=30)
entry_client_llinatges.grid(row=3, column=1, sticky="w")

tk.Label(frame_clients, text="Email:").grid(row=2, column=2, sticky="e", padx=5, pady=5)
entry_client_email = tk.Entry(frame_clients, width=30)
entry_client_email.grid(row=2, column=3, sticky="w")

tk.Label(frame_clients, text="Telèfon:").grid(row=3, column=2, sticky="e", padx=5, pady=5)
entry_client_telefon = tk.Entry(frame_clients, width=20)
entry_client_telefon.grid(row=3, column=3, sticky="w")

tk.Label(frame_clients, text="DNI:").grid(row=4, column=0, sticky="e", padx=5, pady=5)
entry_client_dni = tk.Entry(frame_clients, width=20)
entry_client_dni.grid(row=4, column=1, sticky="w")

tk.Label(frame_clients, text="Nacionalitat:").grid(row=4, column=2, sticky="e", padx=5, pady=5)
entry_client_nacionalitat = tk.Entry(frame_clients, width=20)
entry_client_nacionalitat.grid(row=4, column=3, sticky="w")

tk.Label(frame_clients, text="Data naixement (YYYY-MM-DD):").grid(row=5, column=0, sticky="e", padx=5, pady=5)
entry_client_data = tk.Entry(frame_clients, width=15)
entry_client_data.grid(row=5, column=1, sticky="w")

tk.Label(frame_clients, text="Gènere:").grid(row=5, column=2, sticky="e", padx=5, pady=5)
entry_client_genere = tk.Entry(frame_clients, width=10)
entry_client_genere.grid(row=5, column=3, sticky="w")

tk.Label(frame_clients, text="VIP (0/1):").grid(row=6, column=0, sticky="e", padx=5, pady=5)
entry_client_vip = tk.Entry(frame_clients, width=5)
entry_client_vip.grid(row=6, column=1, sticky="w")

#
# Actualitza la llista de clients a la interfície
def carregar_clients():
    for row in tree_clients.get_children():
        tree_clients.delete(row)
    dades = fetch_all("SELECT codiClient, nomClient, llinatges, emailClient, telefonClient FROM CLIENT ORDER BY codiClient")
    for c in dades:
        tree_clients.insert("", "end", values=(c["codiClient"], c["nomClient"], c["llinatges"], c["emailClient"], c["telefonClient"]))

def netejar_formulari_client():
    entry_client_id.delete(0, tk.END)
    entry_client_nom.delete(0, tk.END)
    entry_client_llinatges.delete(0, tk.END)
    entry_client_email.delete(0, tk.END)
    entry_client_telefon.delete(0, tk.END)
    entry_client_dni.delete(0, tk.END)
    entry_client_nacionalitat.delete(0, tk.END)
    entry_client_data.delete(0, tk.END)
    entry_client_genere.delete(0, tk.END)
    entry_client_vip.delete(0, tk.END)

def on_client_select(event):
    selection = tree_clients.selection()
    if not selection:
        return
    sel = selection[0]
    values = tree_clients.item(sel, "values")
    if not values:
        return
    client_id = values[0]
    result = fetch_all("SELECT * FROM CLIENT WHERE codiClient = %s", (client_id,))
    if not result:
        return
    c = result[0]
    netejar_formulari_client()
    entry_client_id.insert(0, c["codiClient"])
    entry_client_nom.insert(0, c["nomClient"])
    entry_client_llinatges.insert(0, c["llinatges"])
    entry_client_email.insert(0, c["emailClient"] if c["emailClient"] else "")
    entry_client_telefon.insert(0, c["telefonClient"] if c["telefonClient"] else "")
    entry_client_dni.insert(0, c["dni"] if c["dni"] else "")
    entry_client_nacionalitat.insert(0, c["nacionalitat"] if c["nacionalitat"] else "")
    entry_client_data.insert(0, c["dataNaixement"].strftime("%Y-%m-%d") if c["dataNaixement"] else "")
    entry_client_genere.insert(0, c["genere"] if c["genere"] else "")
    entry_client_vip.insert(0, str(c["vip"]))

tree_clients.bind("<<TreeviewSelect>>", on_client_select)

#
# Dona d'alta un nou client
def alta_client():
    try:
        nom = entry_client_nom.get().strip()
        llin = entry_client_llinatges.get().strip()
        email = entry_client_email.get().strip() or None
        telefon = entry_client_telefon.get().strip() or None
        dni = entry_client_dni.get().strip() or None
        nacionalitat = entry_client_nacionalitat.get().strip() or None
        data_txt = entry_client_data.get().strip()
        data_naix = None
        if data_txt:
            data_naix = datetime.date.fromisoformat(data_txt)
        genere = entry_client_genere.get().strip() or None
        vip_txt = entry_client_vip.get().strip()
        vip = int(vip_txt) if vip_txt else 0

        if not nom or not llin:
            messagebox.showwarning("Dades insuficients", "Nom i llinatges són obligatoris.")
            return

        execute_query("""
            INSERT INTO CLIENT (nomClient, llinatges, emailClient, telefonClient, dni,
                                nacionalitat, dataNaixement, genere, vip)
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)
        """, (nom, llin, email, telefon, dni, nacionalitat, data_naix, genere, vip))

        messagebox.showinfo("Client", "Alta de client correcta.")
        carregar_clients()
        netejar_formulari_client()
    except Exception as e:
        messagebox.showerror("Error", f"S'ha produït un error en donar d'alta el client:\n{e}")

#
# Modifica les dades d'un client existent
def modificar_client():
    try:
        id_txt = entry_client_id.get().strip()
        if not id_txt:
            messagebox.showwarning("Selecció", "Has de seleccionar un client per modificar.")
            return
        client_id = int(id_txt)

        nom = entry_client_nom.get().strip()
        llin = entry_client_llinatges.get().strip()
        email = entry_client_email.get().strip() or None
        telefon = entry_client_telefon.get().strip() or None
        dni = entry_client_dni.get().strip() or None
        nacionalitat = entry_client_nacionalitat.get().strip() or None
        data_txt = entry_client_data.get().strip()
        data_naix = None
        if data_txt:
            data_naix = datetime.date.fromisoformat(data_txt)
        genere = entry_client_genere.get().strip() or None
        vip_txt = entry_client_vip.get().strip()
        vip = int(vip_txt) if vip_txt else 0

        execute_query("""
            UPDATE CLIENT
            SET nomClient=%s, llinatges=%s, emailClient=%s, telefonClient=%s, dni=%s,
                nacionalitat=%s, dataNaixement=%s, genere=%s, vip=%s
            WHERE codiClient=%s
        """, (nom, llin, email, telefon, dni, nacionalitat, data_naix, genere, vip, client_id))

        messagebox.showinfo("Client", "Client modificat correctament.")
        carregar_clients()
    except Exception as e:
        messagebox.showerror("Error", f"S'ha produït un error en modificar el client:\n{e}")

#
# Elimina un client sempre que no tingui reserves
def esborrar_client():
    try:
        id_txt = entry_client_id.get().strip()
        if not id_txt:
            messagebox.showwarning("Selecció", "Selecciona un client per eliminar.")
            return

        client_id = int(id_txt)

        reserves = fetch_all("SELECT 1 FROM RESERVA WHERE codiClient = %s", (client_id,))
        if reserves:
            messagebox.showwarning("No es pot eliminar", "El client té reserves actives i no es pot eliminar.")
            return

        execute_query("DELETE FROM CLIENT WHERE codiClient = %s", (client_id,))
        carregar_clients()
        netejar_formulari_client()
        messagebox.showinfo("Client", "Client eliminat correctament.")
    except Exception as e:
        messagebox.showerror("Error", f"No s'ha pogut eliminar el client:\n{e}")

btn_alta_client = tk.Button(frame_clients, text="Alta client", command=alta_client)
btn_alta_client.grid(row=7, column=1, pady=10)

btn_mod_client = tk.Button(frame_clients, text="Modificar client", command=modificar_client)
btn_mod_client.grid(row=7, column=2, pady=10)

btn_refresca_clients = tk.Button(frame_clients, text="Refrescar llista", command=carregar_clients)
btn_refresca_clients.grid(row=7, column=3, pady=10)

btn_esborra_client = tk.Button(frame_clients, text="Eliminar client", command=esborrar_client)
btn_esborra_client.grid(row=7, column=4, pady=10)

# Gestió de les reserves

frame_reserves = tk.Frame(root)
frames["reserves"] = frame_reserves

# Llistat de reserves
tree_reserves = ttk.Treeview(frame_reserves,
                             columns=("id", "client", "hotel", "hab", "regim", "entrada", "sortida", "estat"),
                             show="headings")
for col, txt in [
    ("id", "ID"), ("client", "Client"), ("hotel", "Hotel"),
    ("hab", "Hab."), ("regim", "Règim"),
    ("entrada", "Entrada"), ("sortida", "Sortida"), ("estat", "Estat")
]:
    tree_reserves.heading(col, text=txt)

tree_reserves.grid(row=0, column=0, columnspan=6, padx=10, pady=10, sticky="nsew")
scroll_res = ttk.Scrollbar(frame_reserves, orient="vertical", command=tree_reserves.yview)
tree_reserves.configure(yscroll=scroll_res.set)
scroll_res.grid(row=0, column=6, sticky="ns")
frame_reserves.rowconfigure(0, weight=1)
frame_reserves.columnconfigure(0, weight=1)

# Combos i entrades per nova reserva
tk.Label(frame_reserves, text="Client:").grid(row=1, column=0, sticky="e", padx=5, pady=5)
combo_res_client = ttk.Combobox(frame_reserves, state="readonly", width=30, exportselection=False)
combo_res_client.grid(row=1, column=1, sticky="w")

tk.Label(frame_reserves, text="Hotel:").grid(row=1, column=2, sticky="e", padx=5, pady=5)
combo_res_hotel = ttk.Combobox(frame_reserves, state="readonly", width=30, exportselection=False)
combo_res_hotel.grid(row=1, column=3, sticky="w")

tk.Label(frame_reserves, text="Habitació:").grid(row=2, column=0, sticky="e", padx=5, pady=5)
combo_res_hab = ttk.Combobox(frame_reserves, state="readonly", width=15, exportselection=False)
combo_res_hab.grid(row=2, column=1, sticky="w")

tk.Label(frame_reserves, text="Règim:").grid(row=2, column=2, sticky="e", padx=5, pady=5)
combo_res_regim = ttk.Combobox(frame_reserves, state="readonly", width=25, exportselection=False)
combo_res_regim.grid(row=2, column=3, sticky="w")

tk.Label(frame_reserves, text="Data reserva (YYYY-MM-DD):").grid(row=3, column=0, sticky="e", padx=5, pady=5)
entry_res_datares = tk.Entry(frame_reserves, width=15)
entry_res_datares.grid(row=3, column=1, sticky="w")

tk.Label(frame_reserves, text="Entrada (YYYY-MM-DD):").grid(row=3, column=2, sticky="e", padx=5, pady=5)
entry_res_entrada = tk.Entry(frame_reserves, width=15)
entry_res_entrada.grid(row=3, column=3, sticky="w")

tk.Label(frame_reserves, text="Sortida (YYYY-MM-DD):").grid(row=4, column=0, sticky="e", padx=5, pady=5)
entry_res_sortida = tk.Entry(frame_reserves, width=15)
entry_res_sortida.grid(row=4, column=1, sticky="w")

tk.Label(frame_reserves, text="Estat:").grid(row=4, column=2, sticky="e", padx=5, pady=5)
combo_res_estat = ttk.Combobox(frame_reserves, state="readonly", values=["Pendent", "Confirmada"], width=15)
combo_res_estat.grid(row=4, column=3, sticky="w")
combo_res_estat.set("Pendent")

_clients_cache = {}
_hotels_cache = {}
_regims_cache = {}

def carregar_clients_combo():
    dades = fetch_all("SELECT codiClient, nomClient, llinatges FROM CLIENT ORDER BY nomClient")
    _clients_cache.clear()
    noms = []
    for c in dades:
        etiqueta = f"{c['codiClient']} - {c['nomClient']} {c['llinatges']}"
        noms.append(etiqueta)
        _clients_cache[etiqueta] = c["codiClient"]
    combo_res_client["values"] = noms

def carregar_hotels_combo():
    dades = fetch_all("SELECT codiHotel, nomHotel FROM HOTEL ORDER BY nomHotel")
    _hotels_cache.clear()
    noms = []
    for h in dades:
        etiqueta = f"{h['codiHotel']} - {h['nomHotel']}"
        noms.append(etiqueta)
        _hotels_cache[etiqueta] = h["codiHotel"]
    combo_res_hotel["values"] = noms

def carregar_regims_combo():
    dades = fetch_all("SELECT codiRegim, descripcioRegim FROM REGIM ORDER BY codiRegim")
    _regims_cache.clear()
    noms = []
    for r in dades:
        etiqueta = f"{r['codiRegim']} - {r['descripcioRegim']}"
        noms.append(etiqueta)
        _regims_cache[etiqueta] = r["codiRegim"]
    combo_res_regim["values"] = noms

def carregar_habitacions_combo(*args):
    # Segons hotel seleccionat
    sel = combo_res_hotel.get()
    if not sel:
        combo_res_hab["values"] = []
        return
    id_hotel = _hotels_cache.get(sel)
    if not id_hotel:
        combo_res_hab["values"] = []
        return
    dades = fetch_all("""
        SELECT numeroHabitacio, codiTipusHabitacio
        FROM HABITACIO
        WHERE codiHotel = %s
        ORDER BY numeroHabitacio
    """, (id_hotel,))
    valors = []
    for h in dades:
        etiqueta = f"{h['numeroHabitacio']}"
        valors.append(etiqueta)
    combo_res_hab["values"] = valors

combo_res_hotel.bind("<<ComboboxSelected>>", lambda e: root.after_idle(carregar_habitacions_combo))

#
# Recarrega totes les reserves registrades
def carregar_reserves():
    for row in tree_reserves.get_children():
        tree_reserves.delete(row)
    dades = fetch_all("""
        SELECT r.codiReserva, c.nomClient, h.nomHotel,
               r.numeroHabitacio, g.descripcioRegim,
               r.dataEntrada, r.dataSortida, r.estatReserva
        FROM RESERVA r
        JOIN CLIENT c ON r.codiClient = c.codiClient
        JOIN HOTEL h ON r.codiHotel = h.codiHotel
        JOIN REGIM g ON r.codiRegim = g.codiRegim
        ORDER BY r.codiReserva
    """)
    for r in dades:
        tree_reserves.insert(
            "", "end",
            values=(
                r["codiReserva"], r["nomClient"], r["nomHotel"],
                r["numeroHabitacio"], r["descripcioRegim"],
                r["dataEntrada"], r["dataSortida"], r["estatReserva"]
            )
        )

#
# Crea una reserva nova si les dates són vàlides i hi ha disponibilitat
def alta_reserva():
    try:
        cli_txt = combo_res_client.get()
        hot_txt = combo_res_hotel.get()
        hab_txt = combo_res_hab.get()
        reg_txt = combo_res_regim.get()

        if not (cli_txt and hot_txt and hab_txt and reg_txt):
            messagebox.showwarning("Dades", "Has de seleccionar client, hotel, habitació i règim.")
            return

        id_client = _clients_cache[cli_txt]
        id_hotel = _hotels_cache[hot_txt]
        num_hab = int(hab_txt)
        id_regim = _regims_cache[reg_txt]

        data_res = entry_res_datares.get().strip()
        data_ent = entry_res_entrada.get().strip()
        data_sor = entry_res_sortida.get().strip()

        # Camps obligatoris
        if not data_ent or not data_sor:
            messagebox.showwarning("Dades", "Has d'indicar les dates d'entrada i de sortida (YYYY-MM-DD).")
            return

        if not data_res:
            data_res = datetime.date.today().isoformat()

        # Validació de format i coherència de dates
        try:
            entrada_dt = datetime.date.fromisoformat(data_ent)
            sortida_dt = datetime.date.fromisoformat(data_sor)
            data_res_dt = datetime.date.fromisoformat(data_res) if data_res else datetime.date.today()
        except ValueError:
            messagebox.showwarning("Dates", "Format de data incorrecte. Usa YYYY-MM-DD.")
            return

        if sortida_dt <= entrada_dt:
            messagebox.showwarning("Dates", "La data de sortida ha de ser posterior a l'entrada.")
            return

        # Treballarem amb strings ISO per inserir/consultar
        data_res = data_res_dt.isoformat()
        data_ent = entrada_dt.isoformat()
        data_sor = sortida_dt.isoformat()

        estat = combo_res_estat.get()

        # Comprovar disponibilitat: hi ha solapament si entrada < sortida_exist i sortida > entrada_exist
        conflict = fetch_all("""
            SELECT 1
            FROM RESERVA
            WHERE codiHotel = %s
              AND numeroHabitacio = %s
              AND dataEntrada < %s
              AND dataSortida > %s
            LIMIT 1
        """, (id_hotel, num_hab, data_sor, data_ent))

        if conflict:
            messagebox.showwarning("Disponibilitat", "L'habitació NO està disponible: ja hi ha una reserva que solapa aquestes dates.")
            return
        execute_query("""
            INSERT INTO RESERVA (dataReserva, dataEntrada, dataSortida, estatReserva,
                                 codiClient, codiHotel, numeroHabitacio, codiRegim)
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s)
        """, (data_res, data_ent, data_sor, estat, id_client, id_hotel, num_hab, id_regim))

        messagebox.showinfo("Reserva", "Reserva creada correctament.")
        carregar_reserves()
    except Exception as e:
        messagebox.showerror("Error", f"S'ha produït un error en crear la reserva:\n{e}")

#
# Elimina la reserva seleccionada
def esborrar_reserva():
    try:
        selection = tree_reserves.selection()
        if not selection:
            messagebox.showwarning("Selecció", "Selecciona una reserva per eliminar.")
            return

        sel = selection[0]
        values = tree_reserves.item(sel, "values")
        if not values:
            return
        reserva_id = values[0]
        execute_query("DELETE FROM RESERVA WHERE codiReserva = %s", (reserva_id,))

        carregar_reserves()
        messagebox.showinfo("Reserva", "Reserva eliminada correctament.")
    except Exception as e:
        messagebox.showerror("Error", f"No s'ha pogut eliminar la reserva:\n{e}")


btn_alta_reserva = tk.Button(frame_reserves, text="Crear reserva", command=alta_reserva)
btn_alta_reserva.grid(row=5, column=1, pady=10)

btn_refresca_res = tk.Button(frame_reserves, text="Refrescar reserves", command=carregar_reserves)
btn_refresca_res.grid(row=5, column=2, pady=10)

btn_esborra_res = tk.Button(frame_reserves, text="Eliminar reserva", command=esborrar_reserva)
btn_esborra_res.grid(row=5, column=3, pady=10)

# Consulta general de taules

frame_consultes = tk.Frame(root)
frames["consultes"] = frame_consultes

tk.Label(frame_consultes, text="Consulta de taules (nom de taula):").pack(pady=5)
combo_taula = ttk.Combobox(
    frame_consultes,
    state="readonly",
    values=["ACOMPANYANT", "CHECK_IN", "CLIENT",
            "HABITACIO", "HOTEL",
            "PREU_REGIM", "REGIM", "RESERVA",
            "TARIFA", "TIPUS_HABITACIO"],
    width=30)
combo_taula.pack(pady=5)

tree_consulta = ttk.Treeview(frame_consultes, show="headings")
tree_consulta.pack(fill="both", expand=True, padx=10, pady=10)

scroll_cons = ttk.Scrollbar(frame_consultes, orient="vertical", command=tree_consulta.yview)
tree_consulta.configure(yscroll=scroll_cons.set)
scroll_cons.pack(side="right", fill="y")

#
# Mostra totes les files d'una taula escollida
def executar_consulta_taula():
    taula = combo_taula.get()
    if not taula:
        return
    # buida
    for col in tree_consulta["columns"]:
        tree_consulta.heading(col, text="")
    tree_consulta["columns"] = ()
    for row in tree_consulta.get_children():
        tree_consulta.delete(row)
    # llegeix dades
    cur = db.cursor(dictionary=True)
    try:
        cur.execute(f"SELECT * FROM {taula}")
        rows = cur.fetchall()
        colnames = [desc[0] for desc in cur.description]
    finally:
        cur.close()
    tree_consulta["columns"] = colnames
    for c in colnames:
        tree_consulta.heading(c, text=c)
    for r in rows:
        vals = [r[c] for c in colnames]
        tree_consulta.insert("", "end", values=vals)

btn_exec_consulta = tk.Button(frame_consultes, text="Consultar taula", command=executar_consulta_taula)
btn_exec_consulta.pack(pady=5)

# ==========================
# Menú principal
# ==========================

menubar = tk.Menu(root)

menu_clients = tk.Menu(menubar, tearoff=0)
menu_reserves = tk.Menu(menubar, tearoff=0)
menu_consultes = tk.Menu(menubar, tearoff=0)

menu_clients.add_command(label="Gestió de clients", command=lambda: show_frame("clients"))
menu_reserves.add_command(label="Gestió de reserves", command=lambda: show_frame("reserves"))
menu_consultes.add_command(label="Consulta de taules", command=lambda: show_frame("consultes"))

menubar.add_cascade(label="Clients", menu=menu_clients)
menubar.add_cascade(label="Reserves", menu=menu_reserves)
menubar.add_cascade(label="Consultes", menu=menu_consultes)

root.config(menu=menubar)

# Carrega les dades inicials i mostra la pantalla principal del PMS
def inicialitzar():
    carregar_clients()
    carregar_clients_combo()
    carregar_hotels_combo()
    carregar_regims_combo()
    carregar_reserves()
    show_frame("clients")

inicialitzar()

root.mainloop()