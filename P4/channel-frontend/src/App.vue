<template>
  <component
      :is="view"
      :usuari="usuari"
      :setUsuari="(u) => (usuari = u)"
      :setView="changeView"
  />
</template>

<script setup>
import { ref } from "vue";
import LoginView from "./views/LoginView.vue";

const usuari = ref(null);

const view = ref(LoginView);

const changeView = (component) => {
  if (component === null) {
    view.value = LoginView;
  } else {
    view.value = component;
  }
};
</script>

<style>
body {
  margin: 0;
  background: #f5f7fa;
  font-family: system-ui, sans-serif;

}
</style>