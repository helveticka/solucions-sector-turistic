<template>
  <table class="channel-table">
    <thead>
    <tr>
      <th>Tipus</th>
      <th v-for="d in dies" :key="d">{{ format(d) }}</th>
    </tr>
    </thead>

    <tbody>
    <tr v-for="t in tipus" :key="t.codiTipusHabitacio">
      <td class="tipus">{{ t.denominacio }}</td>

      <td v-for="d in dies" :key="d">
        <div class="cell">
          <input
              type="number"
              v-model.number="valors[t.codiTipusHabitacio][d].cupo"
              @change="onChange(t.codiTipusHabitacio, d)"
              placeholder="cupo"
          />

          <input
              type="number"
              step="0.01"
              v-model.number="valors[t.codiTipusHabitacio][d].preu"
              @change="onChange(t.codiTipusHabitacio, d)"
              placeholder="preu"
          />

          <label class="actiu-label">
            <input
                type="checkbox"
                v-model="valors[t.codiTipusHabitacio][d].actiu"
                @change="onChange(t.codiTipusHabitacio, d)"
            />
            actiu
          </label>
        </div>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script setup>
const props = defineProps(["dies", "tipus", "valors", "registreCanvi"]);

const format = (d) => {
  const date = new Date(d);
  return date.toLocaleDateString("ca-ES", {
    day: "2-digit",
    month: "short",
  });
};

const onChange = (tipus, data) => {
  props.registreCanvi(tipus, data);
};
</script>

<style scoped>
.channel-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  overflow: hidden;
  border-radius: 12px;
  background: #fffdf9;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  border: 1px solid #f3c6a1;
}

.channel-table thead {
  background: #ffe2c3;
}

.channel-table th {
  padding: 12px;
  font-weight: 600;
  font-size: 14px;
  color: #8a4b12;
  border-bottom: 1px solid #f3c6a1;
}

.channel-table td {
  padding: 12px;
  border-bottom: 1px solid #f7dcc3;
  transition: background 0.2s;
}

.channel-table tbody tr:hover {
  background: #fff3e6;
}

.cell {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.cell input[type="number"] {
  padding: 8px 10px;
  border: 1px solid #e7b086;
  border-radius: 8px;
  font-size: 13px;
  transition: border-color 0.2s, box-shadow 0.2s;
  background: #fffaf4;
}

.cell input[type="number"]:focus {
  border-color: #d18a54;
  outline: none;
  box-shadow: 0 0 4px rgba(209,138,84,0.45);
}

.actiu-label {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: #8a4b12;
}

.tipus {
  background: #ffeede;
  font-weight: 600;
  font-size: 14px;
  color: #8a4b12;
}

.channel-table tr:last-child td {
  border-bottom: none;
}
</style>