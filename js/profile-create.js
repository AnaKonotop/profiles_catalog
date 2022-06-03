const reg_form = document.getElementById('reg_form');
const reg_name = document.getElementById('reg_name');
const reg_surname = document.getElementById('reg_surname');
const reg_middlename = document.getElementById('reg_middlename');
const job_title = document.getElementById('reg_job_title');
const job_specialization = document.getElementById('reg_specialization');
const reg_file = document.getElementById('reg_file');
const formSelect = document.getElementsByClassName('form-select');
const reg_file_btn = document.getElementById('reg_file_btn');
const avaWrapper = document.getElementById('reg_avatar_wrapper');
const ava = document.getElementById('reg_avatar');

Array.from(formSelect).forEach(selectItem => {
  selectItem.addEventListener('change', () => {
    if (selectItem.value) {
      selectItem.style.border = '2px solid green';
    }
  })
});

reg_file_btn.addEventListener('click', () => {
  reg_file.click();
});

reg_file.addEventListener('change', () => {
  if (!!reg_file.files.length) {

    const fr = new FileReader();
    const file = reg_file.files[0];
    fr.readAsArrayBuffer(file);

    fr.onloadstart = () => {
      console.log('LOADING!');
      ava.src = './img/spinner.gif';
    }

    fr.onloadend = function() {
      const blob = new Blob([fr.result]);
      const url = URL.createObjectURL(blob);
      ava.src = url;
    }
  }
})

reg_form.addEventListener('submit', e => {
  e.preventDefault();

  const [img] = reg_file.files;

  const ProfileData = new FormData();
  ProfileData.append('action', 'profile_add');
  ProfileData.append('name', reg_name.value);
  ProfileData.append('surname', reg_surname.value);
  ProfileData.append('middlename', reg_middlename.value);
  ProfileData.append('job_title', job_title.value);
  ProfileData.append('job_specialization', job_specialization.value);
  ProfileData.append('img', img);

  fetch('./api.php', { method: 'POST',  body: ProfileData });
});

const getSelectData = async (action = 'job_titles_list') => {
  const Data = new FormData();
  Data.set('action', action);

  const data_2 = await fetch('./api.php', {
    method: 'POST',
    body: Data,
  });
  return data_2;
}

getSelectData('job_titles_list')
  .then(res => res.json())
  .then((data) => {
    data.forEach(item => {
      const option = document.createElement('option');
      option.textContent = item.name;
      option.value = item.id;
      job_title.append(option);
    })
  })
  .catch(console.log);

getSelectData('specializations_list')
  .then(res => res.json())
  .then((data) => {
    data.forEach(item => {
      const option = document.createElement('option');
      option.textContent = item.name;
      option.value = item.id;
      job_specialization.append(option);
    })
  });