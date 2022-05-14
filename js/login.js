const form = document.getElementById('form');
const login = document.getElementById('login');
const password = document.getElementById('password');
const submitBtn = document.getElementById('submit-btn');


const errorLoginMsg = (message) => {
  let errorMsg = document.getElementById('form__error');

  if(!errorMsg) {
    errorMsg = document.createElement('h3');
    errorMsg.id = 'form__error';
  }
  
  errorMsg.classList.add('sign-in-page__form-error');
  errorMsg.textContent = message;
  form.append(errorMsg);
}

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
      // console.log('resData:', res);
      if(res.status === 'error') {
        // console.log('error');
        errorLoginMsg('Wrong login data');
      } else {
        window.location.href = '/';
        localStorage.setItem('profiles_list', login.value);
      }
    })
    .catch((e) => {
      errorLoginMsg(e.message);
    });
};

form.addEventListener('submit', e => {
  e.preventDefault();

  if(login.value && password.value) {
    signInUser(login.value, password.value);
  } else {
    errorLoginMsg('All fields are required');
  }
});