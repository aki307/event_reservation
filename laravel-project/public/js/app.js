// import '../../resources/js/bootstrap';

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
// 日付判定用
document.addEventListener('DOMContentLoaded', function () {
    function isValidDate(y, m, d) {
        var date = new Date(y, m - 1, d);
        return date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d;
    }

    function updateDOB() {
        var year = document.getElementById('year').value;
        var month = document.getElementById('month').value;
        var day = document.getElementById('day').value;
        var dobValue = year + '-' + month + '-' + day;
        if (isValidDate(year, month, day)) {
            document.getElementById('dob').value = dobValue;
            document.getElementById('date-error').style.display = 'none';
        } else {
            document.getElementById('dob').value = '';
            document.getElementById('date-error').style.display = 'block';
        }
    }

    document.getElementById('year').addEventListener('change', updateDOB);
    document.getElementById('month').addEventListener('change', updateDOB);
    document.getElementById('day').addEventListener('change', updateDOB);
});

