document.getElementById('form-news').addEventListener('submit', function(e) {
    e.preventDefault();

    let selectedLang = document.querySelector('input[name="lang"]:checked');
    let title = document.getElementById("title").value;
    let thumbnailInput = document.getElementById("thumbnail");
    let thumbnailFile = thumbnailInput.files[0];

    let publicDateInput = document.getElementById("public_date");
    let public_date = null;
    if (publicDateInput) {
        public_date = publicDateInput.value;
    }

    let content = document.getElementById("content").value;

    let hasError = false;

    document.getElementById('lang_error').innerText = "";
    document.getElementById("error_title").innerText = "";
    document.getElementById("error_thumbnail").innerText = "";
    document.getElementById("error_public_date").innerText = "";
    document.getElementById("error_content").innerText = "";

    if (selectedLang) {
        let langValue = selectedLang.value;
        if (!['vi', 'en'].includes(langValue)) {
            document.getElementById('lang_error').innerText = 'Ngôn ngữ không hợp lệ.';
        }
    } else {
        document.getElementById('lang_error').innerText = 'Vui lòng chọn ngôn ngữ.';
    }

    if (title.trim().length === 0) {
        document.getElementById("error_title").innerText = "Tiêu đề không được để trống!";
        hasError = true;
    } else if (title.trim().length > 255) {
        document.getElementById("error_title").innerText = "Tiêu đề không được quá 255 ký tự!";
        hasError = true;
    }

    if (!thumbnailFile) {
        document.getElementById("error_thumbnail").innerText = "Vui lòng chọn một file hình ảnh!";
        hasError = true;
    } else {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!allowedTypes.includes(thumbnailFile.type)) {
            document.getElementById("error_thumbnail").innerText = "File không phải là hình ảnh!";
            hasError = true;
        }
    }

    if (public_date !== null) {
        if (public_date.trim().length === 0) {
            document.getElementById("error_public_date").innerText = "Ngày công bố không được để trống!";
            hasError = true;
        }
    }

    if (!content.trim()) {
        document.getElementById("error_content").innerText = "Nội dung không được để trống!";
        hasError = true;
    }

    if (!hasError) {
        document.getElementById('form-news').submit(); // Gửi form
    }
});
