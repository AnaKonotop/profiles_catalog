const logOut = document.getElementById('log_out');
const profilesListBlock = document.getElementById('profiles-list__block');
const profilesFilter = document.getElementById('profiles-filter');
const isLoggedIn = localStorage.getItem('profiles_list');


const Data = new FormData();
Data.append('action', 'nav_list');

fetch('./api.php', { method: 'POST', body:  Data })
  .then(res => res.json())
  .then(data => {
    console.log(data);
});

profilesFilter.addEventListener('change', (e) => {
  const id = e.target.value;

  if(id === '0') {
    getProfilesList('profiles_list');
  } else {
    getProfilesList('profiles_by_specialization', id);
  }
})

const getProfilesList = async (action, id) => {

  const Data = new FormData();
  Data.append('action', action);
  id && Data.append('id', id);

  fetch('./api.php', { method: 'POST', body: Data })
    .then(res => res.json())
    .then(data => {
      profilesListBlock.textContent = '';
      const profilesListWrap = document.createElement('ul');
      profilesListWrap.classList.add('profiles-list');
      profilesListWrap.id = 'profiles_list';

      data.map(profileItem => {
        const item = document.createElement('li');
        const dateCreated = document.createElement('span');
        const avatarWrap = document.createElement('div');
        const name = document.createElement('h3');
        const img = document.createElement('img');
        const specialization = document.createElement('span');
        const job = document.createElement('span');
        const profileInfoBlock = document.createElement('div');

        name.textContent = profileItem.name;
        name.classList.add('profiles-list__name');
        dateCreated.textContent = profileItem.date_added;
        dateCreated.classList.add('profiles-list__date-created');
        item.classList.add('profiles-list__item');
        avatarWrap.classList.add('profiles-list__avatar-wrapper');
        specialization.textContent = profileItem.specialization_name;
        job.textContent = profileItem.job_title_name;
        profileInfoBlock.classList.add('profiles-list__info-block');

        img.src = profileItem.img;
        img.alt = profileItem.img;
        img.classList.add('profiles-list__item-avatar');
        img.onerror = () => img.src = './img/empty-avatar.png';

        profilesListWrap.append(item);
        avatarWrap.append(img);
        item.append(dateCreated);
        item.append(avatarWrap);
        item.append(profileInfoBlock);
        profileInfoBlock.append(name);
        profileInfoBlock.append(job);
        profileInfoBlock.append(specialization);
      });
      profilesListBlock.append(profilesListWrap);

      if(data.length > 4) {
        startSlick();
      }
    })
};


getProfilesList('profiles_list');

if(!isLoggedIn) {
  window.location.href = '/login-page.html';
}

logOut.addEventListener('click', () => {
  localStorage.removeItem('profiles_list');
  window.location.reload();
})

const startSlick = () => {
  $('.profiles-list').slick({
    rows: 2,
    dots: true,
    speed: 800,
    arrows: false,
    slidesToShow: 2,
    autoplay: true,
    infinite: false,
    slidesToScroll: 2,
    variableWidth: false,
    appendDots: $('.profiles-list__dots'),
  });
}