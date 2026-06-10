function togglePassword() {
    let pwd = document.getElementById("password");
    pwd.type = pwd.type === "password" ? "text" : "password";
}

let currentForm = null;
