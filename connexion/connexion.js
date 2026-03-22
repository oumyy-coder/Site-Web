document.getElementById('form-connexion').addEventListener('submit', function (e) {
    let valide = true;

    const login = document.getElementById('login');
    const mdp   = document.getElementById('mot_de_passe');
    const errLogin = document.getElementById('err-login');
    const errMdp   = document.getElementById('err-mdp');

    // Réinitialiser les messages
    [login, mdp].forEach(function (champ) {
        champ.classList.remove('champ-invalide');
    });
    [errLogin, errMdp].forEach(function (msg) {
        msg.style.display = 'none';
    });

    // Vérification login
    if (login.value.trim() === '') {
        login.classList.add('champ-invalide');
        errLogin.style.display = 'block';
        valide = false;
    }

    // Vérification mot de passe
    if (mdp.value === '') {
        mdp.classList.add('champ-invalide');
        errMdp.style.display = 'block';
        valide = false;
    }

    if (!valide) {
        e.preventDefault();
    }
});
