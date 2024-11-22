$(document).ready(function () {
    loadServices();

    var addModal = $('#addServiceModal');
    var editModal = $('#editServiceModal');

    var span = $('.close');
    var addServiceBtn = $('#addService');

    span.on('click', function () {
        addModal.hide();
        editModal.hide();
    });

    $(window).on('click', function (event) {
        if ($(event.target).is(addModal)) {
            addModal.hide();
        }
        if ($(event.target).is(editModal)) {
            editModal.hide();
        }
    });

    addServiceBtn.on('click', function () {
        $('#addServiceForm')[0].reset();
        addModal.show();
    });

    $('#addServiceForm').on('submit', function (event) {
        event.preventDefault();
        var serviceName = $('#addServiceName').val();
        var servicePrice = $('#addServicePrice').val();
        $.ajax({
            url: '../actions/addService.php',
            type: 'POST',
            data: {
                service_name: serviceName,
                price: servicePrice
            },
            success: function (response) {
                Swal.fire({
                    icon: response.status === 'success' ? 'success' : 'error',
                    title: response.message
                });
                if (response.status === 'success') {
                    addModal.hide();
                    loadServices();
                }
            }
        });
    });

    $('#editServiceForm').on('submit', function (event) {
        event.preventDefault();
        var serviceId = $('#editServiceId').val();
        var serviceName = $('#editServiceName').val();
        var servicePrice = $('#editServicePrice').val();
        $.ajax({
            url: '../actions/updateService.php',
            type: 'POST',
            data: {
                service_id: serviceId,
                service_name: serviceName,
                price: servicePrice
            },
            success: function (response) {
                Swal.fire({
                    icon: response.status === 'success' ? 'success' : 'error',
                    title: response.message
                });
                if (response.status === 'success') {
                    editModal.hide();
                    loadServices();
                }
            }
        });
    });

    function loadServices() {
        $.ajax({
            url: '../actions/getServiceByProvider.php',
            type: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    var servicesList = $('#servicesList');
                    servicesList.empty();
                    response.data.forEach(function (service) {
                        servicesList.append(`
                            <div class="service-card">
                                <div>
                                    <h3>${service.service_name}</h3>
                                    <p>$${service.price}</p>
                                </div>
                                <div class="service-actions">
                                    <button class="edit-service" data-service-id="${service.service_id}">Edit</button>
                                    <button class="delete-service" data-service-id="${service.service_id}">Delete</button>
                                </div>
                            </div>
                        `);
                    });
                    attachEventHandlers();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
            }
        });
    }

    function attachEventHandlers() {
        $('.edit-service').on('click', function () {
            var serviceId = $(this).data('service-id');
            $.ajax({
                url: '../actions/getServiceById.php',
                type: 'GET',
                data: { service_id: serviceId },
                success: function (response) {
                    if (response.status === 'success') {
                        var service = response.data;
                        $('#editServiceName').val(service.service_name);
                        $('#editServicePrice').val(service.price);
                        $('#editServiceId').val(service.service_id);
                        editModal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                }
            });
        });

        $('.delete-service').on('click', function () {
            var serviceId = $(this).data('service-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../actions/deleteService.php',
                        type: 'POST',
                        data: { service_id: serviceId },
                        success: function (response) {
                            Swal.fire({
                                icon: response.status === 'success' ? 'success' : 'error',
                                title: response.message
                            });
                            if (response.status === 'success') {
                                loadServices();
                            }
                        }
                    });
                }
            });
        });
    }
});