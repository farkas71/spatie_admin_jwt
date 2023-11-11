document.addEventListener("DOMContentLoaded", function () {
    //multichack role/create, role/edit
    var selectAllCheckbox = document.getElementById("selectAllPermissions");
    var permissionCheckboxes = document.querySelectorAll(
        'input[name="permissions[]"]'
    );

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function () {
            permissionCheckboxes.forEach(function (checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }

    //modal ablak törlés role/list
    $("#staticBackdrop").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget);
        var roleName = button.data("role-name");
        var modal = $(this);

        // Az adatok beillesztése modal részekbe
        modal.find(".modal-title").text(roleName);
        modal.find(".modal-text").text("Biztosan törölöd ezt a szerepkört?");

        // Törlés gombra kattintáskos művelet
        modal.find(".delete-button").on("click", function () {
            window.location.href = "roles/torol/" + roleName;
        });
    });
});
