import { notify } from "./modules/notify";
import fab from "./modules/fab";
import http from "./modules/http";
import label from "./modules/label";
import topper from "./modules/topper";
import products from "./modules/products";

const danger = document.querySelector(".alert.alert-danger");
if (danger !== null && danger.innerHTML !== "") {
  notify.error(danger.innerHTML);
}

const success = document.querySelector(".alert.alert-success");
if (success !== null && success.innerHTML !== "") {
  notify.success(success.innerHTML);
}

fab.initialize();
http.initialize();
label.initialize();
topper.initialize();
products.initialize();
