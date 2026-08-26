document.addEventListener('DOMContentLoaded', () => {

    const menuButton = document.querySelector('.mobile-menu-button');

    const sidebar = document.querySelector('.mobile-sidebar');

    const overlay = document.querySelector('.menu-overlay');

    const closeButton = document.querySelector('.close-sidebar');

    const dropdowns = document.querySelectorAll('.mobile-dropdown');

    /* ABRIR MENU */

    function openMenu(){

        sidebar.classList.add('active');

        overlay.classList.add('active');

        document.body.style.overflow = 'hidden';
    }

    /* FECHAR MENU */

    function closeMenu(){

        sidebar.classList.remove('active');

        overlay.classList.remove('active');

        document.body.style.overflow = '';
    }

    menuButton.addEventListener('click', openMenu);

    closeButton.addEventListener('click', closeMenu);

    overlay.addEventListener('click', closeMenu);

    /* DROPDOWN */

    dropdowns.forEach(dropdown => {

        const toggle = dropdown.querySelector('.mobile-dropdown-toggle');

        toggle.addEventListener('click', () => {

            dropdown.classList.toggle('active');
        });

    });

});