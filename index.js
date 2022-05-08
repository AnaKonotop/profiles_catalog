const form = document.getElementById('form');
const login = document.getElementById('login');
const password = document.getElementById('password');

const signInUser = async (user, password, action = 'validate_user') => {

  const data = {
    user: user,
    password: password,
    action: action
  }

  console.log(data);

  const data_2 = await fetch('./api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  return console.log(data_2);
}

form.addEventListener('submit', e => {
  e.preventDefault();

  signInUser(login.value, password.value);

  console.log(login.value);
  console.log(password.value);
});