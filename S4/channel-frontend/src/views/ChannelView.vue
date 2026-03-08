<template>
  <div class="channel-page">
    <div class="top-bar">
      <button class="back-button" @click="goBack">← Hotels</button>
      <div class="title">Channel Manager</div>
      <div class="user-menu" @click="toggleMenu">
        {{ usuari?.nomUsuari }}
        <div v-if="menuOpen" class="menu-dropdown">
          <p>{{ usuari?.email }}</p>
          <button @click.stop="logout">Sortir</button>
        </div>
      </div>
    </div>
    <div class="channel">
      <h2>Channel: {{ nomHotel || '(sense hotel)' }}</h2>

      <div class="filtres">
        <label>
          Des de:
          <input type="date" v-model="dataInici" />
        </label>

        <label>
          Fins:
          <input type="date" v-model="dataFi" />
        </label>

        <button @click="carregar">Carregar</button>
        <button @click="guardar" :disabled="canvis.length === 0">
          Guardar canvis
        </button>
      </div>

      <TablaChannel
          v-if="dies.length"
          :dies="dies"
          :tipus="tipus"
          :valors="valors"
          :registreCanvi="registreCanvi"
      />

      <p v-else class="avís">
        Introdueix un rang de dates i clica "Carregar".
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import api from "../api";
import TablaChannel from "../components/TablaChannel.vue";
import HotelsView from "./HotelsView.vue";
import LoginView from "./LoginView.vue";

const props = defineProps(["usuari", "setView"]);

const codiHotel = ref(Number(sessionStorage.getItem("codiHotel")));
const nomHotel = ref(sessionStorage.getItem("nomHotel") || "");

const dataInici = ref("");
const dataFi = ref("");

const dies = ref([]);
const tipus = ref([]);
const valors = ref({});
const canvis = ref([]);

const carregar = async () => {
  if (!codiHotel.value || !dataInici.value || !dataFi.value) {
    return;
  }

  if (new Date(dataInici.value) > new Date(dataFi.value)) {
    alert("Les dates són incorrectes: la data inicial no pot ser posterior a la final.");
    return;
  }

  try {
    const r = await api.getDisponibilitat(
        codiHotel.value,
        dataInici.value,
        dataFi.value
    );
    if (r.data.ok) {
      dies.value = r.data.dies;
      tipus.value = r.data.tipusHabitacio;
      valors.value = r.data.valors;
      canvis.value = [];
    } else {
      console.error(r.data.error);
    }
  } catch (e) {
    console.error(e);
  }
};

const registreCanvi = (codiTipusHabitacio, data) => {
  if (
    !canvis.value.find(
      (c) => c.codiTipusHabitacio === codiTipusHabitacio && c.data === data
    )
  ) {
    canvis.value.push({ codiTipusHabitacio, data });
  }
};

const guardar = async () => {
  if (!codiHotel.value || canvis.value.length === 0) return;

  const payload = canvis.value.map((c) => ({
    codiTipusHabitacio: c.codiTipusHabitacio,
    data: c.data,
    cupo: valors.value[c.codiTipusHabitacio][c.data].cupo,
    preu: valors.value[c.codiTipusHabitacio][c.data].preu,
    actiu: valors.value[c.codiTipusHabitacio][c.data].actiu,
  }));

  try {
    const r = await api.saveDisponibilitat(codiHotel.value, payload);
    if (r.data.ok) {
      alert("Canvis guardats");
      canvis.value = [];
    } else {
      alert("Error guardant els canvis");
    }
  } catch (e) {
    console.error(e);
    alert("Format de les dades incorrecte");
  }
};

const menuOpen = ref(false);
const toggleMenu = () => menuOpen.value = !menuOpen.value;

const goBack = () => {
  props.setView(HotelsView);
};

const logout = () => {
  sessionStorage.clear();
  props.setView(LoginView);
};
</script>

<style scoped>
.channel-page {
  min-height: 100vh;
  background: #fff3e6;
  display: flex;
  flex-direction: column;
  width: 100%;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

.top-bar {
  width: 100%;
  background: #ffd1a6;
  padding: 16px 24px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

.back-button {
  position: absolute;
  left: 32px;
  background: #f8b36a;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  color: #8a4b12;
}

.back-button:hover {
  background: #f4a556;
}

.title {
  font-size: 22px;
  font-weight: 600;
  color: #8a4b12;
  text-align: center;
  flex: none;
}

.user-menu {
  position: absolute;
  right: 32px;
  cursor: pointer;
  font-weight: 500;
  color: #8a4b12;
  padding: 10px 14px;
  border-radius: 8px;
  background: #ffe2c3;
}

.menu-dropdown {
  position: absolute;
  top: 42px;
  right: 0;
  background: #ffffff;
  border: 1px solid #f3c6a1;
  border-radius: 8px;
  padding: 12px 16px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  min-width: 180px;
  text-align: left;
}

.menu-dropdown p {
  margin: 0 0 10px 0;
  color: #8a4b12;
}

.menu-dropdown button {
  width: 100%;
  padding: 8px;
  background: #f8b36a;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
}

.menu-dropdown button:hover {
  background: #f4a556;
}

.channel {
  margin: 20px auto 0 auto;
  width: 100%;
  max-width: 1100px;
  padding: 20px;
}

h2 {
  font-size: 24px;
  margin-bottom: 20px;
  color: #8a4b12;
}

.filtres {
  display: flex;
  gap: 20px;
  align-items: center;
  margin-bottom: 25px;
  flex-wrap: wrap;
}

.filtres input[type="date"] {
  padding: 6px 10px;
  border: 1px solid #e7b086;
  border-radius: 6px;
  font-size: 14px;
}

button {
  padding: 8px 16px;
  background: #f8b36a;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background 0.2s;
}

button:hover {
  background: #f4a556;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.avís {
  margin-top: 20px;
  font-style: italic;
  color: #8a4b12;
}
</style>