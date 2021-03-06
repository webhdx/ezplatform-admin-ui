(function () {
    const btns = document.querySelectorAll('.btn--udw-add');
    const submitButton = document.querySelector('#content_location_add_data_add');
    const form = document.querySelector('form[name="content_location_add"]');
    const input = form.querySelector('#content_location_add_data_new_locations');
    const udwContainer = document.getElementById('react-udw');
    const closeUDW = () => udwContainer.innerHTML = '';
    const onConfirm = (items) => {
        closeUDW();

        input.value = items[0].id;
        submitButton.click();
    };
    const onCancel = () => closeUDW();
    const openUDW = (event) => {
        event.preventDefault();
        event.stopPropagation();

        ReactDOM.render(React.createElement(eZ.modules.UniversalDiscovery, {
            onConfirm,
            onCancel,
            confirmLabel: 'Add location',
            title: 'Select location',
            multiple: false
        }), udwContainer);
    };

    btns.forEach(btn => btn.addEventListener('click', openUDW, false));
})();
