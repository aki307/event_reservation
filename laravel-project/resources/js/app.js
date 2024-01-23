import './bootstrap';
import Alpine from 'alpinejs';

require('./bootstrap');

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('DOMContentLoaded', (event) => {
    const navbarToggler = document.getElementById('navbar-toggler');
    const navbarMenu = document.getElementById('navbar-menu');

    if (navbarToggler && navbarMenu) {
        navbarToggler.addEventListener('click', () => {
            navbarMenu.classList.toggle('hidden');
        });
    } else {
        console.error('Navbar elements not found!');
    }

   
});