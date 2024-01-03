
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

    // 削除ボタン
    const deleteUserButton = document.getElementById('deleteUserButton');
    if (deleteUserButton) {
        deleteUserButton.addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('本当に削除してよろしいですか？')) {
                document.getElementById('delete-form').submit();
            }
        });
    }
});