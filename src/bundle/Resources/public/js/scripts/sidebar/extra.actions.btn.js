(function () {
    const btns = document.querySelectorAll('.ez-btn--extra-actions');

    btns.forEach(btn => {
        const actions = btn.parentNode.querySelector('.ez-extra-actions');

        btn.addEventListener('click', () => actions.classList.toggle('ez-extra-actions--hidden'), false);
    });
})();
