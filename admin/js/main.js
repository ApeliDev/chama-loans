document.getElementById("sidebar-toggle").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    const header = document.getElementById("header");

    sidebar.classList.toggle("collapsed");
    mainContent.classList.toggle("collapsed");
    header.classList.toggle("collapsed");
});