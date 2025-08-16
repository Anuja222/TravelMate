const $ = (sel, parent = document) => parent.querySelector(sel);
const $$ = (sel, parent = document) => Array.from(parent.querySelectorAll(sel));

const sidebar = $('#sidebar');
const menuToggle = $('#menuToggle');
const userChip = $('#userChip');
const userDropdown = $('#userDropdown');

function closeDropdownOnOutsideClick(e) {
  if (!userChip.contains(e.target)) {
    userDropdown.style.display = 'none';
    userChip.setAttribute('aria-expanded', 'false');
    document.removeEventListener('click', closeDropdownOnOutsideClick);
  }
}

menuToggle?.addEventListener('click', () => {
  const isOpen = sidebar.classList.toggle('open');
  document.body.classList.toggle('sidebar-open', isOpen);
  menuToggle.setAttribute('aria-expanded', String(isOpen));
});

userChip?.addEventListener('click', () => {
  const nowOpen = userDropdown.style.display !== 'block';
  userDropdown.style.display = nowOpen ? 'block' : 'none';
  userChip.setAttribute('aria-expanded', String(nowOpen));
  if (nowOpen) {
    setTimeout(() => document.addEventListener('click', closeDropdownOnOutsideClick), 0);
  }
});

$('#listPropertyBtn')?.addEventListener('click', () => {
  alert('Redirecting to property listing form...');
});

$('#newsletter')?.addEventListener('submit', (e) => {
  e.preventDefault();
  const email = $('#email').value.trim();
  if (!email) return;
  alert(`Subscribed with ${email}`);
  e.target.reset();
});