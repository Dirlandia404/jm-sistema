"use strict";

const deleteForms = document.querySelectorAll(".delete-service-form");

deleteForms.forEach(function (form) {
  form.addEventListener("submit", function (event) {
    const confirmed = window.confirm("Deseja realmente excluir este serviço?");

    if (!confirmed) {
      event.preventDefault();
    }
  });
});
