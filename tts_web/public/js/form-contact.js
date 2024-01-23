document.getElementById('form-contact').addEventListener('submit', function(e) {
    e.preventDefault();
    const EMAIL_REGEX = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const PHONE = /^(0\d{9,10})$/;
    //tối thiểu 8 ký tự, có ít nhất 1 chữ thường, 1 chữ in hoa, 1 số và 1 ký tự đặc biệt
    const PASSWORD_REGEX = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let title = document.getElementById("title").value;
    let message = document.getElementById("message").value;
    let captcha = grecaptcha.getResponse();

    let hasError = false;
    document.getElementById("error_name").innerText = "";
    document.getElementById("error_email").innerText = "";
    document.getElementById("error_phone").innerText = "";
    document.getElementById("error_title").innerText = "";
    document.getElementById("error_message").innerText = "";
    document.getElementById('error_g-recaptcha-response').innerText = "";


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

    if (title.trim().length === 0) {
        document.getElementById("error_title").innerText = "Tiêu đề không được để trống!";
        hasError = true;
    }

    if (message.trim().length === 0) {
        document.getElementById("error_message").innerText = "Message không được để trống!";
        hasError = true;
    }

    if (captcha.length === 0) {
        document.getElementById('error_g-recaptcha-response').innerText = 'Vui lòng xác minh reCAPTCHA.';
        hasError = true;
    }

    if (!hasError) {
        // Nếu không có lỗi, submit form
        document.getElementById('form-contact').submit();
    }
})