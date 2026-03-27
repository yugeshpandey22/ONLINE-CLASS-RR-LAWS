// Main JavaScript file
console.log('Script loaded successfully.');

document.addEventListener('DOMContentLoaded', () => {
    const mobileToggle = document.getElementById('mobileToggle');
    const navElements = document.querySelector('.nav-elements');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            navElements.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            if (navElements.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
});
