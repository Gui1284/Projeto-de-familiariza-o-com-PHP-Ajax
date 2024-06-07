$("#form").submit(function (e) {
  e.preventDefault();

  let user = $("#name").val();
  let age = $("#age").val();

  let userData = {
    user: user,
    age: age,
  };

  $.ajax({
    url: "../src/controller/insert.php",
    method: "POST",
    data: userData,
    dataType: "json",
  })
    .done(function (response) {
      if (response.status_code) {
        popUp(response.msg, response.type);
        if (response.status_code === 201) {
          $("#name").val("");
          $("#age").val("");
          getUser();
        }
      }
    })
    .fail(function (error) {
      console.error("Erro ao ENVIAR requisição AJAX:", error);
    });
});

/**
 * Function getUser
 *
 * Essa função quando resgata todas as informações de todos os usuarios
 */
function getUser() {
  $.ajax({
    url: "../src/controller/select.php",
    method: "GET",
    dataType: "json",
  })
    .done(function (response) {
      // isso vai evitar os dados duplicados
      var dados = document.querySelector(".table_dados");
      while (dados.firstChild) {
        dados.firstChild.remove();
      }

      for (var i = 0; i < response[0].length; i++) {
        $(".table_dados").prepend(
          "<tr><td>" +
            response[0][i].id_user +
            "</td><td>" +
            response[0][i].user +
            "</td><td>" +
            response[0][i].age +
            "</td><td><button class='btn-excluir' data-id='" +
            response[0][i].id_user +
            "'>Excluir</button></td></tr>"
        );
      }
    })
    .fail(function (error) {
      console.error("Erro ao RESGATAR requisição AJAX:", error);
    });
}

/**
 * Function delete
 *
 * Essa função é responsavel por deletar o usuario.
 */
$(".table_dados").on("click", ".btn-excluir", function () {
  var id_user = $(this).data("id");
  Swal.fire({
    title: "Excluir",
    text: "Tem certeza que deseja EXCLUIR?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sim",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../src/controller/delete.php",
        method: "POST",
        data: { id_user },
        dataType: "json",
      })
        .done(function (response) {
          if (response.status_code === 400 || response.status_code === 500) {
            popUp(response.msg, response.type);
          }
          if (response.status_code === 204) {
            getUser();
            popUp(response.msg, response.type);
          }
        })
        .fail(function (error) {
          console.error("Erro ao RESGATAR requisição AJAX:", error);
        });
    }
  });
});

/**
 * Espera a pagina carregar para executar a função de consultar usuarios
 */
$(document).ready(function () {
  getUser();
});

/**
 * Function login
 *
 * Essa função é responsavel por amostrar os Popup na tela.
 */
function popUp(msg, type) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    },
  });
  Toast.fire({
    icon: type,
    title: msg,
  });
}
