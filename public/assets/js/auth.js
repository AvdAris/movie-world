document.addEventListener('DOMContentLoaded', () => {
  const popup = document.getElementById('popup');
  const popupTitle = document.getElementById('popup-title');
  const usernameInput = document.getElementById('username');
  const passwordInput = document.getElementById('password');
  const submitBtn = document.getElementById('submitBtn');
  const logoutBtn = document.getElementById('logoutBtn');
  const loginBtn = document.getElementById('loginBtn');
  const signupBtn = document.getElementById('signupBtn');

  let currentMode = 'login';

  if (loginBtn) {
    loginBtn.addEventListener('click', () => {
      openPopup('login');
    });
  }

  if (signupBtn) {
    signupBtn.addEventListener('click', () => {
      openPopup('signup');
    });
  }

  if (submitBtn) {
    submitBtn.addEventListener('click', () => {
      const username = usernameInput.value;
      const password = passwordInput.value;

      if (!username || !password) {
        alert("Please fill in all fields.");
        return;
      }

      fetch(`/api/auth/${currentMode}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          popup.style.display = 'none';
          window.location.reload();
        } else {
          alert(data.message || "Authentication failed.");
        }
      })
      .catch(err => {
        console.error("Auth error:", err);
        alert("Server error occurred.");
      });
    });
  }

  if (popup) {
    popup.addEventListener('click', (e) => {
      if (e.target === popup) {
        popup.style.display = 'none';
      }
    });
  }

  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      fetch('/api/auth/logout', { method: 'POST' })
        .then(() => window.location.reload());
    });
  }

  function openPopup(mode) {
    currentMode = mode;
    popupTitle.textContent = mode === 'login' ? "Login" : "Sign Up";
    popup.style.display = 'flex';
    usernameInput.value = '';
    passwordInput.value = '';
  }
});
