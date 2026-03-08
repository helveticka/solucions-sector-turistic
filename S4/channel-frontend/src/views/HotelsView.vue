<template>
  <div class="hotels-page">
    <div class="top-bar">
      <div class="title">Channel Manager</div>
      <div class="user-menu" @click="toggleMenu">
        {{ usuari.nomUsuari }}
        <div v-if="menuOpen" class="menu-dropdown">
          <p>{{ usuari.email }}</p>
          <button @click.stop="logout">Sortir</button>
        </div>
      </div>
    </div>
    <div class="hotels-container">
      <h2>Benvingut, {{ usuari.nomUsuari }}</h2>

      <h3>Selecciona un hotel</h3>

      <ul class="hotel-list">
        <li v-for="h in hotels" :key="h.codiHotel">
          <button @click="selectHotel(h)">
            {{ h.nomHotel }} ({{ h.ciutatHotel }})
          </button>
        </li>
      </ul>

      <p v-if="error" class="error">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "../api";
import ChannelView from "./ChannelView.vue";

const props = defineProps(["usuari", "setView"]);

const hotels = ref([]);
const error = ref("");

onMounted(async () => {
  try {
    const r = await api.getHotels(props.usuari.codiUsuari);
    if (r.data.ok) {
      hotels.value = r.data.hotels;
    } else {
      error.value = "Error carregant els hotels";
    }
  } catch (e) {
    console.error(e);
    error.value = "Error de connexió amb el servidor";
  }
});

const selectHotel = (hotel) => {
  sessionStorage.setItem("codiHotel", hotel.codiHotel);
  sessionStorage.setItem("nomHotel", hotel.nomHotel);
  props.setView(ChannelView);
};

const menuOpen = ref(false);
const toggleMenu = () => menuOpen.value = !menuOpen.value;

const logout = () => {
  sessionStorage.clear();
  props.setView(null);
};
</script>

<style scoped>
.hotels-page {
  min-height: 100vh;
  background: #fff3e6;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.top-bar {
  width: 100%;
  background: #ffd1a6;
  padding: 16px 24px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  display: flex;
  justify-content: center;
  position: relative;
  align-items: center;
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

.hotels-container {
  margin-top: 80px;
  background: #ffffff;
  padding: 30px 40px;
  border-radius: 12px;
  border: 1px solid #f3c6a1;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  text-align: center;
}

.hotel-list {
  list-style: none;
  padding: 0;
}

.hotel-list li {
  margin: 12px 0;
}

.hotel-list button {
  padding: 14px 26px;
  font-size: 16px;
  background: #f8b36a;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.2s;
}

.hotel-list button:hover {
  background: #f4a556;
}

.error {
  color: #b04400;
}
</style>