const form = document.getElementById('form');
const login = document.getElementById('login');
const password = document.getElementById('password');

const signInUser = (user, password, action = 'validate_user') => {
  const Data = new FormData();
  Data.append('user', user);
  Data.append('password', password);
  Data.append('action', action);

  fetch('api.php', {
      method: 'POST',
      body: Data,
    })    
    .then(res => res.json())
    .then((res) => {
      console.log('resData:', res);
      if(res.status === 'error') {
        console.log('error');
      } else {
        window.location.href = '/';
        localStorage.setItem('profiles_list', login.value);
      }
    })
    .catch(console.log);
};

form.addEventListener('submit', e => {
  e.preventDefault();

  signInUser(login.value, password.value);
});