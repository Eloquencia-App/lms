document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 768) {
        sidebar.classList.add('collapsed');
        iToggleButton.classList.remove('fa-times');
        iToggleButton.classList.add('fa-bars');

        // if the url is the home page (don't lookup for the exact url and the #)
        if (window.location.href === 'https://eloquencia.org/lms/' || window.location.href === 'https://eloquencia.org/lms/index.php' || window.location.href === 'https://eloquencia.org/lms/index.php#' || window.location.href === 'https://eloquencia.org/lms/#'){
            //hide the news section
            let news = document.getElementById('news');
            news.style.display = 'none';

            //edit the col class of the nextLesson card parent
            let nextLesson = document.getElementById('nextLesson');
            let nextLessonParent = nextLesson.parentElement;
            nextLessonParent.classList.remove('col-6');
            nextLessonParent.classList.add('col-12');
        }
        if (window.location.href === 'https://eloquencia.org/lms/profile' || window.location.href === 'https://eloquencia.org/lms/profile.php' || window.location.href === 'https://eloquencia.org/lms/profile#') {
            let dates = document.getElementById('dates');
            let datesParent = dates.parentElement;
            datesParent.classList.remove('col-6');
            datesParent.classList.add('col-12');

            let profile = document.getElementById('profile');
            let profileParent = profile.parentElement;
            profileParent.classList.remove('col-6');
            profileParent.classList.add('col-12');
        }
    }
});

function setNavCookie() {
    if (!sidebar.classList.contains('collapsed')) {
        console.log('collapsed');
        document.cookie = 'sidebar=collapsed; SameSite=Strict';
    } else {
        console.log('expanded');
        document.cookie = 'sidebar=expanded; SameSite=Strict';
    }
}