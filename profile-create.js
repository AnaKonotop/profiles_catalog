const reg_form = document.getElementById('reg_form');
const reg_name = document.getElementById('reg_name');
const reg_surname = document.getElementById('reg_surname');
const reg_middlename = document.getElementById('reg_middlename');
const reg_file = document.getElementById('reg_file');

reg_form.addEventListener('submit', e => {
  e.preventDefault();

  // signInUser(login.value, password.value);

  console.log(reg_name.value);
  console.log(reg_surname.value);
  console.log(reg_middlename.value);
  console.log(reg_file.files[0]);
});