document.addEventListener('DOMContentLoaded', () => {
        const sectionDetail = document.querySelector('#detail-blog-wp .section-detail');
        const buttonShowMore = document.querySelector('#detail-blog-wp .button .show-more');
        const buttonShowLess = document.querySelector('#detail-blog-wp .button .show-less');
        const button = document.querySelector('#detail-blog-wp .button');
        const buttonContainer = document.querySelector('#detail-blog-wp .button-container');
        const arrowIcon = document.querySelector('#detail-blog-wp .button .fa-solid');

        const initial = {
            showAllContent: true,
            currentIcon: 'fa-chevron-down',
        };

        if (!button || !sectionDetail || !buttonShowMore || !buttonShowLess || !arrowIcon) {
            console.warn('One or more elements not found in #detail-blog-wp.');
            return;
        }

        // 🔥 ban đầu có blur
        buttonContainer.classList.add('button-blur');

        button.addEventListener('click', () => {
            if (initial.showAllContent) {
                // Show more → bỏ blur
                showButton(buttonShowLess, true);
                showButton(buttonShowMore, false);
                sectionDetail.classList.remove('maxHeight');
                buttonContainer.classList.remove('button-blur');
                changeIcon(arrowIcon, 'fa-chevron-down', 'fa-chevron-up');
                initial.currentIcon = 'fa-chevron-up';
            } else {
                // Show less → thêm blur lại
                showButton(buttonShowMore, true);
                showButton(buttonShowLess, false);
                sectionDetail.classList.add('maxHeight');
                buttonContainer.classList.add('button-blur');
                changeIcon(arrowIcon, 'fa-chevron-up', 'fa-chevron-down');
                initial.currentIcon = 'fa-chevron-down';
            }

            initial.showAllContent = !initial.showAllContent;
        });

        function changeIcon(element, currentIcon, newIcon) {
            if (element) {
                element.classList.remove(currentIcon);
                element.classList.add(newIcon);
            }
        }

        function showButton(button, visible = true) {
            if (button) {
                button.classList.toggle('d-none', !visible);
            }
        }
    });