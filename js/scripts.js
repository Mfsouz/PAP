document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector("#mainNav");
    const navbarToggler = document.querySelector(".navbar-toggler");
    const navLinks = document.querySelectorAll("#navbarResponsive .nav-link");

    function shrinkNavbar() {
        if (window.scrollY === 0) {
            navbar.classList.remove("navbar-shrink");
        } else {
            navbar.classList.add("navbar-shrink");
        }
    }

    // Encolher navbar ao carregar e ao rolar a página
    shrinkNavbar();
    document.addEventListener("scroll", shrinkNavbar);

    // Fechar menu mobile ao clicar em um link
    navLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (window.getComputedStyle(navbarToggler).display !== "none") {
                navbarToggler.click();
            }
        });
    });
});
