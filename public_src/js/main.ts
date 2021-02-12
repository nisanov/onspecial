import { notify } from './modules/notify';
import fab from './modules/fab';
import http from './modules/http';
import label from './modules/label';
import search from './modules/search';
import products from './modules/products';

const danger = document.querySelector('.alert.alert-danger');
if (danger !== null && danger.innerHTML !== '') {
  notify.error(danger.innerHTML);
}

const warning = document.querySelector('.alert.alert-warning');
if (warning !== null && warning.innerHTML !== '') {
  notify.open({type: 'warning', message: warning.innerHTML});
}

const success = document.querySelector('.alert.alert-success');
if (success !== null && success.innerHTML !== '') {
  notify.success(success.innerHTML);
}

fab.initialize();
http.initialize();
label.initialize();
search.initialize();
products.initialize();
