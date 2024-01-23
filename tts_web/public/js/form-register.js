document.getElementById('form-register').addEventListener('submit', function(e) {
    e.preventDefault();
    const EMAIL_REGEX = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const PHONE = /^(0\d{9,10})$/;
    //tối thiểu 8 ký tự, có ít nhất 1 chữ thường, 1 chữ in hoa, 1 số và 1 ký tự đặc biệt
    const PASSWORD_REGEX = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let password = document.getElementById("password").value;
    let confirm_password = document.getElementById("confirm_password").value;
    let checkbox = document.getElementById("check_box").checked;
    let hasError = false;
    document.getElementById("error_name").innerText = "";
    document.getElementById("error_email").innerText = "";
    document.getElementById("error_phone").innerText = "";
    document.getElementById("error_password").innerText = "";
    document.getElementById("error_confirm_password").innerText = "";
    document.getElementById("error_check_box").innerText = "";

    if (name.trim().length === 0) {
        document.getElementById("error_name").innerText = "Name không được để trống!";
        hasError = true;
    }

    if (email.trim().length === 0) {
        document.getElementById("error_email").innerText = "Email không được để trống!";
        hasError = true;
    } else {
        if (!EMAIL_REGEX.test(email)) {
            document.getElementById("error_email").innerText = "Email không đúng định dạng!";
            hasError = true;
        }
    }

    if (phone.trim().length === 0) {
        document.getElementById("error_phone").innerText = "SĐT không được để trống!";
        hasError = true;
    } else {
        if (!PHONE.test(phone)) {
            document.getElementById("error_phone").innerText = "SĐT không đúng định dạng!";
            hasError = true;
        }
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

    if (confirm_password.trim().length === 0) {
        document.getElementById("error_confirm_password").innerText = "Confirm_password không được để trống!";
        hasError = true;
    } else {
        if (confirm_password !== password) {
            document.getElementById("error_confirm_password").innerText = "Xác nhận mật khẩu chưa đúng!";
            hasError = true;
        }
    }

    if (!checkbox) {
        document.getElementById("error_check_box").innerText = "Bạn cần đồng ý với điều khoản!";
        hasError = true;
    }

    if (!hasError) {
        // Nếu không có lỗi, submit form
        document.getElementById('form-register').submit();
    }
})