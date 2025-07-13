function showLogin() {
    document.getElementById('welcomeScreen').style.display = 'none';
    document.getElementById('mainContent').style.display = 'flex';
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('registerForm').style.display = 'none';
    document.getElementById('recoverForm').style.display = 'none';
}

function showRegister() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'block';
}

function showRecover() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('recoverForm').style.display = 'block';
};
