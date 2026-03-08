import axios from "axios";

const api = axios.create({
    baseURL: "http://127.0.0.1:5000/api",
});

export default {
    login(email, password) {
        return api.post("/login", { email, password });
    },

    getHotels(codiUsuari) {
        return api.get("/hotels", { params: { codiUsuari } });
    },

    getDisponibilitat(codiHotel, dataInici, dataFi) {
        return api.get("/disponibilitat", {
            params: { codiHotel, dataInici, dataFi }
        });
    },

    saveDisponibilitat(codiHotel, canvis) {
        return api.post("/disponibilitat", { codiHotel, canvis });
    }
};