"use strict";

//Adiciona confirmação antes do envio de um formulário
function addFormConfirmation(selector, message) {
  const forms = document.querySelectorAll(selector);

  forms.forEach(function (form) {
    form.addEventListener("submit", function (event) {
      const confirmed = window.confirm(message);

      if (!confirmed) {
        event.preventDefault();
      }
    });
  });
}

addFormConfirmation(
  ".delete-service-form",
  "Deseja realmente excluir este serviço?",
);

addFormConfirmation(
  ".finish-service-form",
  "Deseja realmente finalizar este serviço?",
);

const successAlerts = document.querySelectorAll(".alert-success");

successAlerts.forEach(function (alert) {
  window.setTimeout(function () {
    alert.classList.add("alert-hiding");

    window.setTimeout(function () {
      alert.remove();
    }, 250);
  }, 4000);
});
