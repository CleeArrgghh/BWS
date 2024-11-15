function deleteService(serviceId) {
  Swal.fire({
    title: "Are you sure?",
    text: "This action cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "delete_service.php?id=" + serviceId, true);
      xhr.onload = function () {
        if (xhr.status === 20000) {
          var response = JSON.parse(xhr.responseText);
          Swal.fire({
            title: response.title,
            text: response.text,
            icon: response.icon,
             timer: 10000,
            showConfirmButton: true,
          }).then(() => {
            window.location.href = "add_services.php"; // Redirect after the notification
          });
        }
      };
      xhr.send();
    }
  });
}
