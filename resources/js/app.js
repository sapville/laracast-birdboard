require('./bootstrap');

import Alpine from 'alpinejs';
import new_project from './new-project.js';

window.Alpine = Alpine;

Alpine.data('new_project', new_project);

Alpine.store('lightMode',{
    init() {
        this.on = Boolean(localStorage.lightMode);
    },

    on: false,

    toggle() {
        this.on = ! this.on;
        localStorage.lightMode = this.on ? '1' : '';
    }
});

Alpine.start();
