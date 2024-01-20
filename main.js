document.addEventListener('DOMContentLoaded', () => {

    if (document.getElementById('bi0sForm')) {
        const form = document.getElementById('bi0sForm').addEventListener('submit', (event) => {
            event.preventDefault();
            upload();
        });
    }

    if (document.getElementById('loginForm')) {
        const form = document.getElementById('loginForm').addEventListener('submit', (event) => {
            event.preventDefault();
            login();
        });
    }
    
    if (document.getElementById('file') && document.getElementById('username')) {
        document.getElementById('file').addEventListener('change', () => {
            preview();
        });
    }

    if (document.getElementById('registerForm')) {
        const form = document.getElementById('registerForm').addEventListener('submit', (event) => {
            event.preventDefault();
            register();
        });
    }

})

function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const error = document.getElementById('error');

    const form = new FormData();

    form.append('username', username);
    form.append('password', password);
    form.append('login', 123);

    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'http://localhost/bi0s/login.php');

    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            console.log(xhr.status);
            if (xhr.status === 200) {
                location.href = "http://localhost/bi0s/bios.php";
            } else if (xhr.status === 401) {
                console.log("Login failed");
                error.style.display = 'block';
                error.innerText = 'Invalid Username Or Password';
            } else if (xhr.status === 301) {
                location.href = "http://localhost/bi0s/admin.php";
            } else {
                console.log("Login failed");
                error.style.display = 'block';
                error.innerText = 'Login Failed';
            }
        }
    }

    xhr.send(form);
}

function register() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const file = document.getElementById('file').files[0];
    const error = document.getElementById('error');

    const form = new FormData();

    form.append('username', username);
    form.append('password', password);
    form.append('file', file);
    form.append('register', 123);

    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'http://localhost/bi0s/register.php');

    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            console.log(xhr.status);
            if (xhr.status === 200) {
                location.href = "http://localhost/bi0s/login.php";
            } else if (xhr.status === 401) {
                console.log("Registration failed");
                error.style.display = 'block';
                error.innerText = 'Username Already Exists';
            } else if (xhr.status === 413) {
                console.log("File size exceeded");
                error.style.display = 'block';
                error.innerText = 'File size exceeded (500kB)';
            } else if (xhr.status === 406) {
                console.log("Password Not Strong");
                error.style.display = 'block';
                error.innerText = 'Password Not Strong';
            } else if (xhr.status === 415) {
                console.log("Invalid Image File");
                error.style.display = 'block';
                error.innerText = 'Invalid Image File';
            } else {
                console.log("Registration failed");
                error.style.display = 'block';
                error.innerText = 'Registration failed';
            }
        }
    }

    xhr.send(form);
}

function upload() {
    const fileName = document.getElementById('name').value;
    const file = document.getElementById('file').files[0];
    const error = document.getElementById('error');
    const form = new FormData();

    form.append('file', file);
    form.append('fileName', fileName);
    form.append('submitFile', 'upload');

    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'http://localhost/bi0s/bios.php');

    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log("Uploaded the file");
                error.setAttribute('class', "para upload");
                error.innerText = xhr.responseText;
            } else {
                console.log("Failed");
                error.setAttribute('class', "para error");
                error.innerText = xhr.responseText;
            }
        }
    }

    xhr.send(form);

}

function registerPage() {
    location.href = 'http://localhost/bi0s/register.php';
}

function loginPage() {
    location.href = 'http://localhost/bi0s/login.php';
}

function preview() {
    const file = document.getElementById('file');
    const previewImg = document.getElementById('preview');
    const reader = new FileReader();

    reader.readAsDataURL(file.files[0])
    reader.onload = () => {
        previewImg.setAttribute('src', reader.result);
    }
}

function  notes() {
    const all = document.querySelectorAll('button');
    const notes = document.querySelector('.notes');
    const iframe = document.querySelectorAll('iframe')[0];
    const xhr = new XMLHttpRequest;

    iframe.style.display = 'block';
    all.forEach(btn => {
        btn.classList.remove('active');
    });

    notes.setAttribute('class', 'notes active')


    iframe.setAttribute('src', 'https://kidshealth.org/en/teens/take-notes.html');
}

function  format() {
    const all = document.querySelectorAll('button');
    const format = document.querySelector('.format');
    const iframe = document.querySelectorAll('iframe')[0];
    const xhr = new XMLHttpRequest;

    iframe.style.display = 'block';
    all.forEach(btn => {
        btn.classList.remove('active');
    });

    format.setAttribute('class', 'format active')

    iframe.setAttribute('src', 'https://becomeawritertoday.com/note-taking-cornell-method/');
}

function  fetchTop() {
    const all = document.querySelectorAll('button');
    const btn = document.querySelector('.fetch');
    const iframe = document.querySelectorAll('iframe')[0];
    const xhr = new XMLHttpRequest;

    iframe.style.display = 'none';
    all.forEach(button => {
        button.classList.remove('active');
    });

    btn.setAttribute('class', 'fetch active')

    
}