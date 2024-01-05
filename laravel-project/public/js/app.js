
window.addEventListener('DOMContentLoaded', (event) => {
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