import "./bootstrap";
import "../css/app.css";
import "../css/records.css"
import "../css/profile.css"
import router from "./router.js";
import { createApp } from "vue";
import App from "./App.vue";

createApp(App).use(router).mount("#app");