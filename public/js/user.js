document.addEventListener("DOMContentLoaded", function () {
    //multichack users/create, users/edit
    var selectAllCheckbox = document.getElementById("selectAllRoles");
    var roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function () {
            roleCheckboxes.forEach(function (checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }

    //modal ablak törlés users/list
     $("#staticBackdrop").on("show.bs.modal", function (event) {
         var button = $(event.relatedTarget);
         var userName = button.data("user-name");
         var modal = $(this);

         // Az adatok beillesztése modal részekbe
         modal.find(".modal-title").text(userName);
         modal.find(".modal-text").text("Biztosan törölöd ezt a felhasználót?");

         // Törlés gombra kattintáskos művelet
         modal.find(".delete-button").on("click", function () {
             window.location.href = "users/torol/" + userName;
         });
     });
});
