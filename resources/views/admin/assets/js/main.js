

'use strict';

let menu, animate;

(function () {



    let layoutMenuEl = document.querySelectorAll('#layout-menu');
    layoutMenuEl.forEach(function (element) {
        menu = new Menu(element, {
            orientation: 'vertical', closeChildren: false
        });

        window.Helpers.scrollToActive((animate = false));
        window.Helpers.mainMenu = menu;
    });


    let menuToggler = document.querySelectorAll('.layout-menu-toggle');
    menuToggler.forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            window.Helpers.toggleCollapsed();
        });
    });


    let delay = function (elem, callback) {
        let timeout = null;
        elem.onmouseenter = function () {

            if (!Helpers.isSmallScreen()) {
                timeout = setTimeout(callback, 300);
            } else {
                timeout = setTimeout(callback, 0);
            }
        };

        elem.onmouseleave = function () {

            document.querySelector('.layout-menu-toggle').classList.remove('d-block');
            clearTimeout(timeout);
        };
    };
    if (document.getElementById('layout-menu')) {
        delay(document.getElementById('layout-menu'), function () {

            if (!Helpers.isSmallScreen()) {
                document.querySelector('.layout-menu-toggle').classList.add('d-block');
            }
        });
    }


    let menuInnerContainer = document.getElementsByClassName('menu-inner'),
        menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
    if (menuInnerContainer.length > 0 && menuInnerShadow) {
        menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
            if (this.querySelector('.ps__thumb-y').offsetTop) {
                menuInnerShadow.style.display = 'block';
            } else {
                menuInnerShadow.style.display = 'none';
            }
        });
    }

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const accordionActiveFunction = function (e) {
        if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
            e.target.closest('.accordion-item').classList.add('active');
        } else {
            e.target.closest('.accordion-item').classList.remove('active');
        }
    };

    const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
    const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
        accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
        accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
    });


    window.Helpers.setAutoUpdate(true);


    window.Helpers.initPasswordToggle();


    window.Helpers.initSpeechToText();

    if (window.Helpers.isSmallScreen()) {
        return;
    }




    window.Helpers.setCollapsed(true, false);
})();


$(document).ready(function () {
    $('.card').on('click', async function (e) {
        let current = $(e.target)
        if ($(e.target).is('[data-fd-toggle="handle-copy"]')) {
            let current = $(e.target)
            if (!current.hasClass('span.bx')) {
                current = current.closest('button[data-fd-toggle="handle-copy"]')
            }

            const target = current.data('fd-target')
            const parent = current.data('fd-parent')
            const text = current.closest(parent).find(target).text();

            try {
                await navigator.clipboard.writeText(text);
                current.find('span.bx').removeClass('bx-copy');
                current.find('span.bx').hide().addClass('bx-check').fadeIn(200);

                setTimeout(function () {
                    current.find('span.bx').removeClass('bx-check');
                    current.find('span.bx').hide().addClass('bx-copy').fadeIn(200);
                }, 1000)

            } catch (err) {
                alert(err)
            }
        }
    })

    const forms = document.querySelectorAll('.needs-validation')


    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()

                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})
