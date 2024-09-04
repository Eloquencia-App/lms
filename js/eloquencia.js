document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 768) {
        sidebar.classList.add('collapsed');
        iToggleButton.classList.remove('fa-times');
        iToggleButton.classList.add('fa-bars');

        if (window.location.href === 'https://eloquencia.org/lms/' || window.location.href === 'https://eloquencia.org/lms/index.php') {
            //hide the news section
            let news = document.getElementById('news');
            news.style.display = 'none';

            //edit the col class of the nextLesson card parent
            let nextLesson = document.getElementById('nextLesson');
            let nextLessonParent = nextLesson.parentElement;
            nextLessonParent.classList.remove('col-6');
            nextLessonParent.classList.add('col-12');
        } else {
            //show the news section
            let news = document.getElementById('news');
            news.style.display = 'block';
        }
    }
});