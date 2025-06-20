<link rel="stylesheet" href="Front/assets/CSS/loginUsers.css">
<div class="container">
    <div class="logo">
        <img src="logo.png" height="250px" width="250" alt="Logo">
    </div>

    <div class="form-wrapper">
        <div id="errors"></div>
        <div><h1>Connexion</h1></div>
        <form method="POST" id="login-form" autocomplete="off">
            <div class="form-group">
                <label for="username">
                    <i class="fa fa-user me-2"></i>Identifiant
                </label>
                <input type="text" id="username" name="username" required autocomplete="off" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">
                    <i class="fa fa-lock me-2"></i>Mot de passe
                </label>
                <input type="password" id="password" name="password" required autocomplete="off" class="form-control">
            </div>
            <div class="button-group mt-3">
                <button type="submit" class="btn btn-primary" name="valid_login-btn" id="valid-login-btn">
                    <i class="fa fa-check me-2"></i>Valider
                </button>
            </div>
        </form>

        <div class="mt-3 text-center">
            <p>
                Vous n'avez pas de compte ? <a href="index.php?component=creation_Compte">Creer un Compte</a>
            </p>
        </div>
    </div>

</div>


<script src="./Front/assets/JS/Services/loginUsers.js" type="module"></script>
<script type="module">
    import { loginUsers } from "./Front/assets/JS/Services/loginUsers.js";

    document.addEventListener('DOMContentLoaded', () => {
        const loginForm = document.querySelector('#login-form');
        const errorElement = document.querySelector('#errors');

        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const username = loginForm.elements['username'].value.trim();
            const password = loginForm.elements['password'].value.trim();

            if (!username || !password) {
                errorElement.innerHTML = `<div class="alert alert-danger">Please fill in all fields.</div>`;
                return;
            }

            try {
                const loginResult = await loginUsers(username, password);
                console.log('Login Result:', loginResult); // Debugging log

                if (loginResult.authentication) {
                    document.location.href = 'index.php';
                } else if (loginResult.errors) {
                    const errors = loginResult.errors.map(
                        err => `<div class="alert alert-danger" role="alert">${err}</div>`
                    );
                    errorElement.innerHTML = errors.join('');
                } else {
                    errorElement.innerHTML = `<div class="alert alert-danger">An unexpected error occurred.</div>`;
                }
            } catch (error) {
                errorElement.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
                console.error('Login error:', error);
            }
        });
    });
</script>