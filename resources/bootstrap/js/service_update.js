function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('sidebar-overlay').classList.toggle('active');
}

function editService(id, name, description, categoryId, price, image) {
    document.getElementById('serviceId').value = id;
    document.getElementById('serviceName').value = name;
    document.getElementById('serviceDescription').value = description;
    document.getElementById('serviceCategory').value = categoryId;
    document.getElementById('servicePrice').value = price;
    // Handle image preview if needed
    document.getElementById('formTitle').innerText = "Edit Service";
    document.getElementById('submitButton').innerText = "Update Service";
    // Optionally scroll to the form
    window.scrollTo({ top: 0, behavior: 'smooth' });
}