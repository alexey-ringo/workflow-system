/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//Импорт vue-роутера
import VueRouter from 'vue-router';
Vue.use(VueRouter);


import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

//import Vuex from 'vuex';
//Vue.use(Vuex);

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//Импорт корневого компонента
import App from "./components/App";


//Создание объекта собственного маршрутизатора и его импорт 
import router from './router';

const app = new Vue({
    el: '#app',
    //компонент, который будет рендериться в начальный момент времени
    render : h => h(App),
    router
});
