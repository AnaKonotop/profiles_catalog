const logOut = document.getElementById('log_out');
const profilesListBlock = document.getElementById('profiles-list_block');

const isLoggedIn = localStorage.getItem('profiles_list');

console.log(isLoggedIn);

const getProfilesList = async (action = 'profiles_list') => {

  const Data = new FormData();
  Data.append('action', action);

  return await fetch('./api.php', {
    method: 'POST',
    body: Data,
  });
}

getProfilesList()
  .then(res => res.json())
  .then(data => {
    console.log(data);
    profilesListBlock.textContent = '';
    const profilesListWrap = document.createElement('ul');
    profilesListWrap.classList.add('profiles-list');
    data.map(profileItem => {
      const item = document.createElement('li');
      const dateCreated = document.createElement('span');
      const awaWrap = document.createElement('div');
      const name = document.createElement('h3');
      const img = document.createElement('img');
      const specialization = document.createElement('span');
      const job = document.createElement('span');
      const profileInfoBlock = document.createElement('div');

      name.textContent = profileItem.name;
      name.classList.add('profiles-list__name');
      img.src = profileItem.img;
      img.alt = profileItem.img;
      dateCreated.classList.add('profiles-list__date-created');
      item.classList.add('profiles-list__item');
      dateCreated.textContent = profileItem.date_added;
      awaWrap.classList.add('profiles-list__avatar-wrapper');
      specialization.textContent = profileItem.specialization_name;
      job.textContent = profileItem.job_title_name;
      profileInfoBlock.classList.add('profiles-list__info-block');

      profilesListWrap.append(item);
      awaWrap.append(img);
      item.append(dateCreated);
      item.append(awaWrap);
      item.append(name);
      item.append(profileInfoBlock);
      profileInfoBlock.append(job);
      profileInfoBlock.append(specialization);
    });
    profilesListBlock.append(profilesListWrap);
  })

if(!isLoggedIn) {
  window.location.href = '/login-page.html';
}

logOut.addEventListener('click', () => {
  localStorage.removeItem('profiles_list');
  location.reload();
})