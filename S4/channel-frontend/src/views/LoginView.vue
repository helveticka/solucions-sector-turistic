<template>
  <div class="login-page">
    <div class="top-bar">
      <h1>Channel Manager</h1>
    </div>

    <div class="login-container">
      <h2>Iniciar sessió</h2>

      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Contrasenya" />

      <button @click="login">Entrar</button>

      <p v-if="error" class="error">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import api from "../api";
import HotelsView from "./HotelsView.vue";

const props = defineProps(["setUsuari", "setView"]);

const email = ref("");
const password = ref("");
const error = ref("");

const login = async () => {
  try {
    const r = await api.login(email.value, password.value);
    if (r.data.ok) {
      props.setUsuari(r.data.usuari);
      props.setView(HotelsView);
    }
  } catch (e) {
    error.value = "Credencials incorrectes";
  }
};
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: #fff3e6;
  align-items: center;
}

.top-bar {
  width: 100%;
  background: #ffd1a6;
  padding: 20px 0;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.top-bar h1 {
  margin: 0;
  font-size: 28px;
  font-weight: 600;
  color: #8a4b12;
}

.login-container {
  margin-top: 80px;
  background: #ffffff;
  padding: 30px 40px;
  border: 1px solid #f3c6a1;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  gap: 12px;
  width: 300px;
  text-align: center;
}

.login-container h2 {
  margin-bottom: 10px;
  color: #5c4b00;
}

input {
  padding: 10px;
  border: 1px solid #e7b086;
  border-radius: 8px;
  font-size: 14px;
}

input:focus {
  outline: none;
  border-color: #d18a54;
  box-shadow: 0 0 4px rgba(209,138,84,0.45);
}

button {
  padding: 10px;
  background: #f8b36a;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.2s;
}

button:hover {
  background: #f4a556;
}

.error {
  color: #b04400;
  margin-top: 5px;
  font-size: 13px;
}
</style>