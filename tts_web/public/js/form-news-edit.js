document.getElementById('form-news-edit').addEventListener('submit', function(e) {
    e.preventDefault();

    let title = document.getElementById("title").value;
    let thumbnailInput = document.getElementById("thumbnail");
    let thumbnailFile = thumbnailInput.files[0];
    let content = document.getElementById("content").value;

    let hasError = false;

    document.getElementById('error_title').innerText = "";
    document.getElementById('error_thumbnail').innerText = "";
    document.getElementById('error_content').innerText = "";

    if (title.trim().length === 0) {
        document.getElementById("error_title").innerText = "Tiêu đề không được để trống!";
        hasError = true;
    } else if (title.trim().length > 255) {
        document.getElementById("error_title").innerText = "Tiêu đề không được quá 255 ký tự!";
        hasError = true;
    }

    if (thumbnailFile) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!allowedTypes.includes(thumbnailFile.type)) {
            document.getElementById("error_thumbnail").innerText = "File không phải là hình ảnh!";
            hasError = true;
        }
    }

    if (content.trim().length === '') {
        document.getElementById("error_content").innerText = "Nội dung không được để trống!";
        hasError = true;
    }

    if (!hasError) {
        document.getElementById('form-news-edit').submit(); // Gửi form
    }
});
