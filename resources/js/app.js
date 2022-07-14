require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

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
