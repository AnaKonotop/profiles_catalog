const reg_form = document.getElementById('reg_form');
const reg_name = document.getElementById('reg_name');
const reg_surname = document.getElementById('reg_surname');
const reg_middlename = document.getElementById('reg_middlename');
const job_title = document.getElementById('reg_job_title');
const job_specialization = document.getElementById('reg_specialization');
const reg_file = document.getElementById('reg_file');

reg_form.addEventListener('submit', e => {
  e.preventDefault();

  // console.log(reg_name.value);
  // console.log(reg_surname.value);
  // console.log(reg_middlename.value);
  // console.log(job_title.value);
  // console.log(job_specialization.value);

  const [img] = reg_file.files;

  // const data = {
  //   action: "profile_add",
  //   name: reg_name.value,
  //   surname: reg_surname.value,
  //   middlename: reg_middlename.value,
  //   job_title: job_title.value,
  //   job_specialization: job_specialization.value,
  // }

  const formData = new FormData();

  formData.append('action', 'profile_add');
  formData.append('name', reg_name.value);
  formData.append('surname', reg_surname.value);
  formData.append('middlename', reg_middlename.value);
  formData.append('job_title', job_title.value);
  formData.append('job_specialization', job_specialization.value);
  formData.append('img', img);

  fetch('./api.php', {
    method: 'POST',
    // headers: { 'Content-Type': "multipart/form-data" },
    // body: JSON.stringify(data),
    body: formData,
  });
});

const getSelectData = async (action = 'job_titles_list') => {

  const data = { action }

  const data_2 = await fetch('./api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  return data_2;
}

getSelectData('job_titles_list')
  .then(res => res.json())
  .then((data) => {
    // console.log('YES!', data);
    data.forEach(item => {
      const option = document.createElement('option');
      option.textContent = item.name;
      option.value = item.id;
      job_title.append(option);
    })
  });

getSelectData('specializations_list')
  .then(res => res.json())
  .then((data) => {
    // console.log('YES!', data);
    data.forEach(item => {
      const option = document.createElement('option');
      option.textContent = item.name;
      option.value = item.id;
      job_specialization.append(option);
    })
  });