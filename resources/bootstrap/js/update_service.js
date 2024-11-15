
    function editService(id, name, description, categoryId, price) {
        document.getElementById('serviceId').value = id;
        document.getElementById('serviceName').value = name;
        document.getElementById('serviceDescription').value = description;
        document.getElementById('serviceCategory').value = categoryId;
        document.getElementById('servicePrice').value = price;

        // Change the button text to "Update Service"
        document.getElementById('formSubmitBtn').innerText = 'Update Service';
    }


    