function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.querySelector('main');
    
    if (sidebar.style.left === '0px') {
        sidebar.style.left = '-250px';
        main.style.marginLeft = '0';
    } else {
        sidebar.style.left = '0';
        main.style.marginLeft = '250px';
    }
}
