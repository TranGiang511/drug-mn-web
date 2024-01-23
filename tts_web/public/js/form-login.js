document.getElementById('form-login').addEventListener('submit', function(e) {
    e.preventDefault();
    //tối thiểu 8 ký tự, có ít nhất 1 chữ thường, 1 chữ in hoa, 1 số và 1 ký tự đặc biệt
    const PASSWORD_REGEX = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let hasError = false;
    document.getElementById("error_email").innerText = "";
    document.getElementById("error_password").innerText = "";

    if (email.trim().length === 0) {
        document.getElementById("error_email").innerText = "Email không được để trống!";
        hasError = true;
    }

    if (password.trim().length === 0) {
        document.getElementById("error_password").innerText = "Password không được để trống!";
        hasError = true;
    } else {
        if (!PASSWORD_REGEX.test(password)) {
            document.getElementById("error_password").innerText = "Password phải có tối thiểu 8 ký tự, ít nhất 1 chữ cái, 1 số và 1 ký tự đặc biệt!";
            hasError = true;
        }
    }

    if (!hasError) {
        // Nếu không có lỗi, submit form
        document.getElementById('form-login').submit();
    }
})