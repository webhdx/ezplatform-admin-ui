(function () {
    const btns = document.querySelectorAll('.btn--open-udw');
    const udwContainer = document.getElementById('react-udw');
    const closeUDW = () => udwContainer.innerHTML = '';
    const onConfirm = (form, content) => {
        const field = form.querySelector('#role_assignment_locations_value');
        field.value = content.map(item => item.ContentInfo.Content._id).join();
        closeUDW();
    };
    const onCancel = () => closeUDW();
    const openUDW = (event) => {
        event.preventDefault();

        const form = event.target.closest('form[name=role_assignment]');
        ReactDOM.render(React.createElement(eZ.modules.UniversalDiscovery, {
            onConfirm: onConfirm.bind(this, form),
            onCancel: onCancel,
        }), udwContainer);
    };

    btns.forEach(btn => btn.addEventListener('click', openUDW, false));
})();
